<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductsController extends Controller
{
    //
    public function index() {
        $categories = Category::all();
        $products = Product::all();

        foreach ( $products as $product  ) {

            $ProductVariant = ProductVariant::where('product_id', $product->id)->get();

            $product->product_variant = $ProductVariant;
        }

        return response()->json([ 'categorias' => $categories, 'articulos' => $products ], 200);
    }
}
