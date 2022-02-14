<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->get();
        $products = [];

        foreach($cart as $item){
            $product = Product::find($item->product_id);
            if (!isset($product->id)) continue;

            $products[] = ["product" => $product, "ammount" => $item->ammount];
        }

        return response()->json([
            "status" => 1,
            "data" => (object)["products" => $products],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required'],
            'ammount' => ['required'],
        ]);

        $item = Cart::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();

        if (isset($item->id)){
            $item->ammount += $request->ammount;
            $item->save();
            return response()->json([
                "status" => 1,
                "message" => 'item added to te cart successfully',
            ]);
        }

        $new_item = new Cart();
        $new_item->user_id = auth()->user()->id;
        $new_item->product_id = $request->product_id;
        $new_item->ammount = $request->ammount;
        $new_item->save();

        return response()->json([
            "status" => 1,
            "message" => 'item added to te cart successfully',
        ]);
    }

    public function delete(Request $request)
    {
        $item = Cart::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();

        if (!isset($item->id)){
            return response()->json([
                'status' => 0,
                'message' => 'item not found',
            ], 404);
        }
        $item->delete();

        return response()->json([
            "status" => 1,
            "message" => 'item deleted successfully',
        ]);
    }
}
