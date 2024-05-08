<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class ProductsController extends Controller
{

    public function index()
    {
        $products = Product::paginate(10);
        return response()->json([
            'message' => null,
            'data' => [
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'next_page_url' => $products->nextPageUrl(),
                ],
            ],
        ], 200);
    }

    public function categories()
    {
        $categories = Category::paginate(10);

        return response()->json([
            'message' => null,
            'data' => [
                'categories' => $categories->items(),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'next_page_url' => $categories->nextPageUrl(),
                ],
            ],
        ], 200);
    }
}
