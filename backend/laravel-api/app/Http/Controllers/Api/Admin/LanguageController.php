<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\StoreLanguageRequest;
use App\Http\Requests\Admin\UpdateLanguageRequest;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    public function index(): JsonResponse
    {
        abort_unless(request()->user()->can('language.view'), 403);

        return response()->json(Language::query()->orderByDesc('is_default')->orderBy('name')->get());
    }

    public function store(StoreLanguageRequest $request): JsonResponse
    {
        abort_unless($request->user()->can('language.create'), 403);

        $payload = $request->validated();
        if (($payload['is_default'] ?? false) === true) {
            Language::query()->update(['is_default' => false]);
        }

        $language = Language::create($payload);

        return response()->json($language, 201);
    }

    public function show(Language $language): JsonResponse
    {
        abort_unless(request()->user()->can('language.view'), 403);

        return response()->json($language);
    }

    public function update(UpdateLanguageRequest $request, Language $language): JsonResponse
    {
        abort_unless($request->user()->can('language.edit'), 403);

        $payload = $request->validated();
        if (($payload['is_default'] ?? false) === true) {
            Language::query()->whereKeyNot($language->id)->update(['is_default' => false]);
        }

        $language->update($payload);

        return response()->json($language->fresh());
    }

    public function destroy(Language $language): JsonResponse
    {
        abort_unless(request()->user()->can('language.delete'), 403);

        if ($language->is_default) {
            return response()->json(['message' => 'Cannot delete default language.'], 422);
        }

        $language->delete();

        return response()->json([], 204);
    }
}
