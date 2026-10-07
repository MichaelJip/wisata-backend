<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //index
    public function index(Request $request)
    {
        $products = Product::query()
            ->with('category')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->keyword}%");
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.products.index', compact('products'));
    }

    //create
    public function create(Request $request)
    {
        abort_unless($request->user()->role === 'admin', 403);
        $categories = Category::orderBy('name')->get();

        return view('pages.products.create', compact('categories'));
    }

    // store
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['image'] = $request->file('image')->store('products', 'public');
        $validated['favorite'] = $request->boolean('favorite');

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product successfully created');
    }

    // edit
    public function edit(Request $request, Product $product)
    {
        abort_unless($request->user()->role === 'admin', 403);
        $categories = Category::orderBy('name')->get();

        return view('pages.products.edit', compact('product', 'categories'));
    }

    // update
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    // destroy
    public function destroy(Request $request, Product $product)
    {
        abort_unless($request->user()->role === 'admin', 403);

        $product->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Product deleted successfully']);
        }

        return back()->with('success', 'Product deleted successfully');
    }
}
