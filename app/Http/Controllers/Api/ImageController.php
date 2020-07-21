<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function updateForProduct(Request $request)
    {
        foreach ($request->images as $image) {
            Image::find($image['id'])->update(['sort' => $image['sort']]);
        };
        return response()->json(null, 200);
    }

    public function destroy($id)
    {
        /** @var Image $image */
        $image = Image::find($id);
        if(!$image){
            return response()->json(['message' => 'Image not found'], 400);
        }
        if(Storage::exists('public/product/'.$image->name)){
            Storage::delete('public/product/'.$image->name);
            $image->delete();
        }
        return response()->json(null, 204);
    }
}
