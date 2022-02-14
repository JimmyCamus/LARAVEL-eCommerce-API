<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('available', 1)->get();

        return response()->json([
            'status' => 1,
            'data' => (object)['products' => $products],
        ]);
    }

    public function show(Request $request)
    {
        $product = Product::find($request->id);

        if (!isset($product->id)) {
            return response()->json([
                'status' => 0,
                'message' => 'product not found',
            ], 404);
        }

        return response()->json([
            'status' => 1,
            'data' => (object)['product' => $product],
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->rol != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'You do not have permission to perform this action'
            ], 403);
        }
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'short_description' => ['required'],
            'brand' => ['required'],
            'model' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'numeric', 'min:0'],
            'category' => ['required'],
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->brand = $request->brand;
        $product->model = $request->model;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category = $request->category;
        $product->available = 1;
        $product->save();

        return response()->json([
            'status' => 1,
            'message' => 'product creation successful'
        ]);
    }

    public function update(Request $request)
    {
        if (auth()->user()->rol != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'You do not have permission to perform this action'
            ], 403);
        }
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'short_description' => ['required'],
            'brand' => ['required'],
            'model' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'numeric', 'min:0'],
            'category' => ['required'],
        ]);

        $product = Product::find($request->id);

        if (!isset($product->id)) {
            return response()->json([
                'status' => 0,
                'message' => 'product not found',
            ], 404);
        }

        foreach ($request->except('_token') as $key => $part) {
            if ($request[$key] != $product[$key]) $product[$key] = $request[$key];
        }
        $product->save();

        return response()->json([
            'status' => 1,
            'data' => (object)['product' => $product],
        ]);
    }

    public function delete(Request $request)
    {
        if (auth()->user()->rol != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'You do not have permission to perform this action'
            ], 403);
        }
        $product = Product::find($request->id);

        if (!isset($product->id)) {
            return response()->json([
                'status' => 0,
                'message' => 'product not found',
            ], 404);
        }

        $product->available = 0;
        $product->save();
        return response()->json([
            'status' => 1,
            'message' => 'product disable successfully',
        ]);
    }
}
