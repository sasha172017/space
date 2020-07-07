<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Product::with(['images', 'category', 'user'])->take(5)->get()->map(function ($product){
            $product->imageFirst = $product->images->sortBy('sort')->take(1)->first();
            $product->makeHidden('images');
            return $product;
        })->forget('name')->toJson(), 200);
    }

    public function productsByCategory($categoryId)
    {
        $products = Product::with(['user','images','category'])->where('category_id', '=', $categoryId)->paginate(1);
        $products->map(function ($product){
            $product->imageFirst = $product->images->sortBy('sort')->take(1)->first();
            $product->makeHidden('images');
            return $product;
        });
        return response($products->toJson(), 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['user','category','comments','images'])->find($id);
        if (!$product){
            return response('No Product', 400);
        }
        return response($product->toJson(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
