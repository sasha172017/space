<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
        return response(Product::with(['images', 'category', 'user'])->take(5)->where('status', true)->orderByDesc('id')->get()->map(function ($product) {
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
    public function store(ProductRequest $request)
    {
        try {
            $user = User::where('token', $request->bearerToken())->first();
            $product = new Product();
            $isAdmin = $user->roles->contains('name', 'ADMIN');
            if($isAdmin){
                $product->status = true;
            }
            $product->name = $request->name;
            $product->description = $request->description;
            $product->created_at = (new \DateTime())->format('Y-m-d H:i:s');
            $product->user_id = $user->id;
            $product->category_id = $request->category;
            DB::transaction(function () use ($product, $request, $isAdmin) {
                $product->save();
                if ($request->shop) {
                    foreach ($request->shop as $shop) {
                        $product->shops()->attach($shop['id'], ['price' => $shop['price'], 'link' => $shop['linkProduct']]);
                    }
                }
                if ($request->shopNew) {
                    foreach ($request->shopNew as $shopNew) {
                        $shop = new Shop();
                        if($isAdmin){
                            $shop->status = true;
                        }
                        $shop->name = $shopNew['name'];
                        $shop->link = $shopNew['linkShop'];
                        $shop->description = $shopNew['description'];
                        $shop->save();
                        $product->refresh();
                        $shop->products()->attach($product->id, ['price' => $shopNew['price'], 'link' => $shopNew['linkProduct']]);
                    }
                }
                if ($request->advantages) {
                    foreach ($request->advantages as $advantage) {
                        $product->tags()->create(['name' => $advantage, 'value' => true]);
                    }
                }
                if ($request->disadvantages) {
                    foreach ($request->disadvantages as $disadvantage) {
                        $product->tags()->create(['name' => $disadvantage, 'value' => false]);
                    }
                }
                $i = 10;
                if ($request->images) {
                    /** @var UploadedFile $image */
                    foreach ($request->images as $image) {
                        $newName = uniqid() . '.' . $image->getClientOriginalExtension();
                        $path = Storage::putFileAs(
                            'public/product', $image, $newName
                        );
                        $product->images()->create(['name' => $newName, 'sort' => $i]);
                        $i = $i + 10;
                    }
                }
            });
            return response()->json('Product Created', 201);
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
    function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            if(!$product) {
                return response()->json('product doesnt exists', 200);
            }
            $product->name = $request->name;
            $product->description = $request->description;
            $product->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
            $product->category_id = $request->category;
            DB::transaction(function () use ($product, $request) {
                $product->save();
                if ($request->shop) {
                    $product->shops()->detach();
                    foreach ($request->shop as $shop) {
                        $product->shops()->attach($shop['id'], ['price' => $shop['price'], 'link' => $shop['linkProduct']]);
                    }
                }
                if ($request->shopNew) {
                    foreach ($request->shopNew as $shopNew) {
                        $shop = new Shop();
                        $shop->name = $shopNew['name'];
                        $shop->link = $shopNew['linkShop'];
                        $shop->description = $shopNew['description'];
                        $shop->save();
                        $product->refresh();
                        $shop->products()->attach($product->id, ['price' => $shopNew['price'], 'link' => $shopNew['linkProduct']]);
                    }
                }
                if ($request->advantages) {
                    $product->tags()->where('value', true)->delete();
                    foreach ($request->advantages as $advantage) {
                        $product->tags()->create(['name' => $advantage, 'value' => true]);
                    }
                }
                if ($request->disadvantages) {
                    $product->tags()->where('value', false)->delete();
                    foreach ($request->disadvantages as $disadvantage) {
                        $product->tags()->create(['name' => $disadvantage, 'value' => false]);
                    }
                }
                if ($request->images) {
                    $image = $product->imagesSortDesc()->first();
                    $i = 0;
                    if($image){
                        $i = $image->sort;
                    }
                    $i = $i + 10;
                    foreach ($request->images as $image) {
                        $newName = uniqid() . '.' . $image->getClientOriginalExtension();
                        $path = Storage::putFileAs(
                            'public/product', $image, $newName
                        );
                        $product->images()->create(['name' => $newName, 'sort' => $i]);
                        $i = $i + 10;
                    }
                }
            });
            return response()->json('Product Update', 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception], 500);
        }
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
        /** @var Product $product */
        $product = Product::findOrFail($id);
        $product->tags()->delete();
        $product->shops()->detach();
        foreach ($product->images as $image) {
            if(Storage::exists('public/product/'.$image->name)){
                Storage::delete('public/product/'.$image->name);
                $image->delete();
            }
        }
        $product->delete();
        return response()->json(null, 204);
    }
}
