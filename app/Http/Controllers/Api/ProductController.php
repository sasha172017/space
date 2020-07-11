<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Product::with(['images', 'category', 'user'])->take(5)->get()->map(function ($product) {
            $product->imageFirst = $product->images->sortBy('sort')->take(1)->first();
            $product->makeHidden('images');
            return $product;
        })->forget('name')->toJson(), 200);
    }

    public function productsByCategory($categoryId)
    {
        $products = Product::with(['user', 'images', 'category'])->where('category_id', $categoryId)->where('status', true)->paginate(5);
        $products->map(function ($product) {
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
        $data = json_decode($request->input('form'), true);
        try {
            $user = User::where('token', $request->bearerToken())->first();
            $product = new Product();
            $product->status = false;
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->created_at = (new \DateTime())->format('Y-m-d H:i:s');
            $product->user_id = $user->id;
            $product->category_id = $data['category']['selected'];
            $product->save();
            if ($data['shop']['selected'] == 'select') {
                foreach ($data['shop']['select']['selected'] as $shop) {
                    $product->shops()->attach($shop['id'], ['price' => $shop['price']]);
                }
            }
            if ($data['shop']['selected'] == 'new') {
                foreach ($data['shop']['new'] as $newShop) {
                    $shop = new Shop();
                    $shop->name = $newShop['name'];
                    $shop->link = $newShop['link'];
                    $shop->description = $newShop['description'];
                    $shop->save();
                    $product->refresh();
                    $shop->products()->attach($product->id, ['price' => $newShop['price']]);
                }
            }
            foreach ($data['tags']['advantages'] as $advantage) {
                $product->tags()->create(['name' => $advantage['value'], 'value' => true]);
            }
            foreach ($data['tags']['disadvantages'] as $disadvantage) {
                $product->tags()->create(['name' => $disadvantage['value'], 'value' => false]);
            }
            $i = 10;
            foreach ($request->files as $image) {
                $newName = uniqid() . '-' . $image->getClientOriginalName();
                $path = Storage::putFileAs(
                    'public/product', $image, $newName
                );
                $product->images()->create(['name' => $newName, 'sort' => $i]);
                $i = $i + 10;
            }
            return response()->json('product created', 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        $product = Product::with(['user', 'shops', 'category', 'comments', 'images', 'tags'])->where('status', true)->find($id);
        if (!$product) {
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
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
