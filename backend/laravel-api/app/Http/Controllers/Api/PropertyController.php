<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PropertyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Property::query()
            ->with(['owner:id,name,phone', 'city:id,name,state_name', 'locality:id,city_id,name', 'media'])
            ->latest();

        if (! $request->user() || ! $request->user()->can('property.view_any')) {
            $query->where('status', 'active');
        } elseif ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->integer('city_id'));
        }

        if ($request->filled('locality_id')) {
            $query->where('locality_id', $request->integer('locality_id'));
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->string('property_type'));
        }

        if ($request->filled('min_rent')) {
            $query->where('rent_amount', '>=', $request->float('min_rent'));
        }

        if ($request->filled('max_rent')) {
            $query->where('rent_amount', '<=', $request->float('max_rent'));
        }

        $properties = $query->paginate(min($request->integer('per_page', 20), 100));

        return response()->json($properties);
    }

    public function store(StorePropertyRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! in_array($user->account_type, ['owner', 'agent', 'staff'], true)) {
            return response()->json(['message' => 'Only owner/agent accounts can post properties.'], 403);
        }

        if ($user->account_type === 'staff' && ! $user->can('property.create')) {
            return response()->json(['message' => 'Permission denied.'], 403);
        }

        $payload = $request->validated();

        if ($user->account_type !== 'staff' || ! $user->can('property.create')) {
            $payload['owner_id'] = $user->id;
        } elseif (! isset($payload['owner_id'])) {
            $payload['owner_id'] = $user->id;
        }

        if (! isset($payload['status'])) {
            $payload['status'] = 'pending';
        }

        if ($payload['status'] === 'active') {
            $payload['published_at'] = Carbon::now();
        }

        $property = Property::create($payload);

        return response()->json($property->load(['owner:id,name,phone', 'city:id,name,state_name', 'locality:id,city_id,name']), 201);
    }

    public function show(Property $property): JsonResponse
    {
        $user = request()->user();
        $isOwner = $user && ($property->owner_id === $user->id || $property->managed_by_user_id === $user->id);
        $canModerate = $user && $user->can('property.view_any');

        if ($property->status !== 'active' && ! $isOwner && ! $canModerate) {
            return response()->json(['message' => 'Property not found.'], 404);
        }

        return response()->json([
            'property' => $property->load(['owner:id,name,phone', 'city:id,name,state_name', 'locality:id,city_id,name', 'media']),
        ]);
    }

    public function update(UpdatePropertyRequest $request, Property $property): JsonResponse
    {
        $user = $request->user();
        $isOwner = $property->owner_id === $user->id || $property->managed_by_user_id === $user->id;
        $canEditAny = $user->can('property.edit_any');
        $canEditOwn = $user->can('property.edit_own');

        if ($user->account_type !== 'staff' && $isOwner) {
            $canEditOwn = true;
        }

        if (! $canEditAny && ! ($canEditOwn && $isOwner)) {
            return response()->json(['message' => 'Permission denied.'], 403);
        }

        $payload = $request->validated();
        $fromStatus = $property->status;

        if (isset($payload['status']) && $payload['status'] !== $fromStatus) {
            $payload['last_status_changed_at'] = Carbon::now();
            if ($payload['status'] === 'active' && ! $property->published_at) {
                $payload['published_at'] = Carbon::now();
            }
        }

        $property->update($payload);

        return response()->json([
            'message' => 'Property updated.',
            'property' => $property->fresh()->load(['owner:id,name,phone', 'city:id,name,state_name', 'locality:id,city_id,name', 'media']),
        ]);
    }

    public function destroy(Property $property): JsonResponse
    {
        $user = request()->user();
        $isOwner = $property->owner_id === $user->id;
        $canDeleteAny = $user->can('property.delete');

        if ($user->account_type !== 'staff' && $isOwner) {
            $canDeleteAny = true;
        }

        if (! $canDeleteAny) {
            return response()->json(['message' => 'Permission denied.'], 403);
        }

        $property->delete();

        return response()->json([], 204);
    }
}
