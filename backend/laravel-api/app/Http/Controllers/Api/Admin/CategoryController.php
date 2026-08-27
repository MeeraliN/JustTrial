<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        abort_unless(request()->user()->can('category.view'), 403);

        return response()->json(
            Category::query()
                ->orderBy('category_group')
                ->orderBy('sort_order')
                ->get()
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        abort_unless($request->user()->can('category.create'), 403);

        $category = Category::create($request->validated());

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        abort_unless(request()->user()->can('category.view'), 403);

        return response()->json($category);
    }

    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        abort_unless($request->user()->can('category.edit'), 403);

        $category->update($request->validated());

        return response()->json($category->fresh());
    }

    public function destroy(Category $category): JsonResponse
    {
        abort_unless(request()->user()->can('category.delete'), 403);

        $category->delete();

        return response()->json([], 204);
    }
}
