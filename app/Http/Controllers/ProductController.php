<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product()
    {
        $names = array('John', 'Doe', 'Jane', 'Doe', 'James', 'Quyen');
        return view('product.product',['names' => $names]);
    }
}
