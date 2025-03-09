<?php

namespace App\Http\Controllers\Api;

use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiProduct extends Controller
{
    
    public function getData()
    {
        $products = Product::with('features')->get();

        return response()->json([
            'products' => $products
        ]);
    }

}
