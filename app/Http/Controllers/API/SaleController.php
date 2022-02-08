<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        return response()->json([
            'status' => 1,
            'data' => (object)["sales" => $sales],
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
