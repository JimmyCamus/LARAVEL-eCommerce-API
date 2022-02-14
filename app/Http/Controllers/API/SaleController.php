<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function index()
    {
        if (auth()->user()->rol != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'You do not have permission to perform this action'
            ], 403);
        }
        return response()->json([
            'status' => 1,
            'data' => (object)['sales' => Sale::all()],
        ]);
    }

    public function show()
    {
        $sales = Sale::where('user_id', auth()->user()->id)->get();
        $products = [];

        foreach ($sales as $sale) {
            $products[] = Product::find($sale->product_id);
        }

        return response()->json([
            'status' => 1,
            'data' => (object)["sales" => $sales, "products" => $products],
        ]);
    }

    public function store(Request $request)
    {
        $sales = [];

        foreach ($request->products as $item) {
            $product = Product::find($item[0]);
            if (!isset($product->id)) continue;
            if ($product->stock - $item[1] <= 0) continue;

            $sale = new Sale();
            $sale->product_id = $product->id;
            $sale->user_id = auth()->user()->id;
            $sale->sale_date = Carbon::now()->toDateTimeString();
            $sale->price = $product->price * $item[1];
            $sale->amount = $item[1];
            $product->stock = $product->stock - $item[1];
            $product->save();

            $cartItem = Cart::where('user_id', auth()->user()->id)->where('product_id', $product->id)->first();
            if (isset($cartItem->id)) {
                $cartItem->delete();
            }

            $sales[] = $sale;
        }

        foreach ($sales as $sale) {
            $sale->save();
        }

        return response()->json([
            'status' => 1,
            'message' => 'the purchase has been made successfully'
        ]);
    }
}
