<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the categories
     *
     */

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $categories = Category::select(['id', 'name', 'description'])->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($category) {
                    $editButton = '';
                    $deleteButton = '';

                    if (auth()->user()->can('edit-categories')) {
                        $editButton = '<button class="btn btn-info edit-category-btn" data-id="' . $category->id . '">Edit</button>';
                    }
                    if (auth()->user()->can('delete-categories')) {
                        $deleteButton = '<button class="btn btn-danger delete-category-btn" id="delete-category-btn-' . $category->id . '">Delete</button>';
                    }
                    return $editButton . ' ' . $deleteButton;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.sections.categories.index', [
            'title' => 'Categories',
            'menu_active' => 'categories',
        ]);
    }

    // Store category
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Name field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response()->json(['category' => $category]);
    }

    // Show the form for editing the specified category
    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    // Update the category
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Name field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response()->json(['category' => $category->id]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        if ($category->products()->exists()) {
            return response()->json(['error' => 'Cannot delete category with associated products'], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }

}
