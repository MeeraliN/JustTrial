<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\StoreCityRequest;
use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function index(): JsonResponse
    {
        abort_unless(request()->user()->can('city.view'), 403);

        return response()->json(
            City::query()->orderBy('name')->orderBy('state_name')->get()
        );
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        abort_unless($request->user()->can('city.create'), 403);

        $city = City::create($request->validated());

        return response()->json($city, 201);
    }

    public function show(City $city): JsonResponse
    {
        abort_unless(request()->user()->can('city.view'), 403);

        return response()->json($city);
    }

    public function update(StoreCityRequest $request, City $city): JsonResponse
    {
        abort_unless($request->user()->can('city.edit'), 403);

        $city->update($request->validated());

        return response()->json($city->fresh());
    }

    public function destroy(City $city): JsonResponse
    {
        abort_unless(request()->user()->can('city.delete'), 403);

        $city->delete();

        return response()->json([], 204);
    }
}
