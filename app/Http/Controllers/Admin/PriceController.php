<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\CashewPrice;
use Illuminate\Http\Request; // Ensure this model exists
use App\Http\Controllers\Controller;

class PriceController extends Controller
{
    public function index()
    {
        $price = CashewPrice::orderByDesc('effective_date')->latest()->first();

        return view('backend.admin.prices.index', compact('price'))->with('title', 'Manage Prices');
    }

    public function store(Request $request)
    {
    
        $rules = [
            'price' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        CashewPrice::create([
            'effective_date' => NOW(),
            'price_per_kg' => $request->price,
        ]);

        return response()->json(['success' => 'Price added successfully!']);
    }
}

