<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {

        $perPage = min(max($request->integer('per_page', 10), 1), 100);
        $categories = Category::query()
            ->when($request->filled('keyword'), fn($q) => $q->where('name', 'like', "%{$request->keyword}%"))
            ->latest('id')
            ->paginate($perPage);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(CategoryRequest $request): JsonResponse
    // {
    //     $category = Category::create($request->validated());

    //     return (new CategoryResource($category))
    //         ->response()
    //         ->setStatusCode(201);
    // }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(CategoryRequest $request, Category $category): CategoryResource
    // {
    //     $category->update($request->validated());

    //     return new CategoryResource($category);
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Category $category): JsonResponse
    // {
    //     $category->delete();

    //     return response()->json(['message' => 'Category deleted successfully']);
    // }
}
