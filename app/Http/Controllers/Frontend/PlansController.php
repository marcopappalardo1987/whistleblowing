<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    /**
     * Display the plans page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::orderBy('price', 'asc')
            ->get();
        
        return view('frontend.plans', compact('products'));
    }
}
