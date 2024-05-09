<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    // Display a listing of the products

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::select(['id', 'name', 'price'])->get();

            return datatables()->of($data)
                ->addColumn('action', function ($row) {
                    $editButton = '';
                    $deleteButton = '';

                    if (auth()->user()->can('edit-products')) {
                        $editButton = '<a href="#" class="edit btn btn-primary" data-id="' . $row->id . '">Edit</a>';
                    }

                    if (auth()->user()->can('delete-products')) {
                        $deleteButton = '<button class="delete btn btn-danger " data-id="' . $row->id . '">Delete</button>';
                    }
                    return $editButton . ' ' . $deleteButton;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.sections.products.index', [
            'title' => 'Products',
            'menu_active' => 'products',
        ]);
    }

    public function create()
    {

        $categories = Category::get();
        return view('admin.sections.products.create', [
            'title' => 'Products',
            'menu_active' => 'products',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array|min:1',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:png,jpeg,jpg,webp,svg|max:2048',
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'categories.required' => 'Please select at least one category.',
            'images.required' => 'Please upload at least one image.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->active = $request->active ? 1 : 0;

        if ($request->hasFile('images')) {
            $featuredImage = $request->file('images')[0];
            $product->addMedia($featuredImage)
                ->usingFileName(Str::random(60) . '.' . $featuredImage->getClientOriginalExtension())
                ->toMediaCollection('featured_image');

            foreach (array_slice($request->file('images'), 1) as $image) {
                $product->addMedia($image)
                    ->usingFileName(Str::random(60) . '.' . $image->getClientOriginalExtension())
                    ->toMediaCollection('gallery');
            }
        }

        if ($product->save()) {
            foreach ($request->categories as $categoryId) {
                $productCategory = new ProductCategory();
                $productCategory->product_id = $product->id;
                $productCategory->category_id = $categoryId;
                $productCategory->save();
            }

            return response()->json(['message' => 'Product created successfully'], 200);
        }

        return response()->json(['error' => 'Failed to create product'], 500);
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::get();

        return view('admin.sections.products.edit', [
            'title' => 'Products',
            'menu_active' => 'products',
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array|min:1',
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'categories.required' => 'Please select at least one category.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->active = $request->active ? 1 : 0;

        if ($request->hasFile('featured_image')) {

            $featuredImage = $request->file('featured_image');
            $product->addMedia($featuredImage)
                ->usingFileName(Str::random(60) . '.' . $featuredImage->getClientOriginalExtension())
                ->toMediaCollection('featured_image');
        }

        if ($request->hasFile('images')) {

            foreach (array_slice($request->file('images'), 1) as $image) {
                $product->addMedia($image)
                    ->usingFileName(Str::random(60) . '.' . $image->getClientOriginalExtension())
                    ->toMediaCollection('gallery');
            }
        }

        if ($product->save()) {

            $categories = $request->input('categories', []);
            $product->categories()->detach();
            foreach ($categories as $categoryId) {
                $product->categories()->attach($categoryId);
            }

            return response()->json(['message' => 'Product updated successfully'], 200);

        }

        return response()->json(['error' => 'Failed to create product'], 500);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json();
    }
}
