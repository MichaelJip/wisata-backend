<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //index
    public function index(Request $request)
    {
        $categories = Category::query()->when($request->filled('keyword'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%");
            });
        })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.categories.index', compact('categories'));
    }

    // create
    public function create(Request $request)
    {
        abort_unless($request->user()->role === 'admin', 403);

        return view('pages.categories.create');
    }

    // store
    public function store(CategoryRequest $categoryRequest)
    {
        Category::create($categoryRequest->validated());

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    // edit
    public function edit(Request $request, Category $category)
    {
        abort_unless($request->user()->role === 'admin', 403);

        return view('pages.categories.edit', compact('category'));
    }

    // update
    public function update(CategoryRequest $categoryRequest, Category $category)
    {
        $validated = $categoryRequest->validated();

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    // destroy
    public function destroy(Request $request, Category $category)
    {
        abort_unless($request->user()->role === 'admin', 403);

        $category->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Category deleted successfully']);
        }

        return back()->with('success', 'Category deleted successfully');
    }
}
