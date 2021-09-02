<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search_name(Request $request)
    {
        if (isset($request->category) && $request->category == 0) {
            $product = Product::join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                ->where('product_status', 0)->where('category_status', 0)
                ->where('product_quantity', '>', 0)
                ->where('product_name', 'like', '%' . $request->value . '%')
                // ->search($request->value)
                ->get();
        } else {
            $id = $request->category;
            // $product = Product::where('product_name', 'like', '%' . $request->value . '%')->get();
            $product = Product::join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                ->where('product_status', 0)->where('category_status', 0)
                ->where(function ($query) use ($id) {
                    return $query->where('tbl_product.category_id', $id)->orWhere('category_parentId', $id);
                })
                ->where('product_name', 'like', '%' . $request->value . '%')
                // ->search($request->value)
                ->where('product_quantity', '>', 0)
                ->get();
        }

        return response()->json($product);
    }

    public function search_price(Request $request)
    {
        $price = $request->value;
        if (is_numeric($price)) {
            if (isset($request->category) && $request->category != 0) {
                // $product = Product::where('category_id', $request->category)->whereBetween('product_price_discount', [$price - $price * 0.2, $price + $price * 0.2])->get();
                $id = $request->category;
                $product = Product::join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                    ->where('product_status', 0)->where('category_status', 0)
                    ->where(function ($query) use ($id) {
                        return $query->where('tbl_product.category_id', $id)->orWhere('category_parentId', $id);
                    })->whereBetween('product_price_discount', [$price - $price * 0.2, $price + $price * 0.2])
                    ->where('product_quantity', '>', 0)
                    ->get();
            } else {
                $product = Product::whereBetween('product_price_discount', [$price - $price * 0.2, $price + $price * 0.2])
                    ->where('product_status', 0)->where('product_quantity', '>', 0)->get();
            }
            return response()->json($product);
        }
    }
}
