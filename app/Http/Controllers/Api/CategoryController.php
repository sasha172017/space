<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('children')->whereNull('category_id')->get()->makeHidden('category_id');
        return response($categories->toJson(), 200);
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
        $category = Category::with(['parent'])->find($id);
        if(!$category){
            return response()->json(['message' => 'Error in request'], 400);
        }

        $idAnyChild = $this->findAnyChild($category);
        if ($idAnyChild != $id) {
            return response()->json(['message' => 'Unable to access this category'], 400);
        }
        return response($category->toJson(), 200);
    }

    public function findAnyChild($category)
    {
        if ($category->children->isNotEmpty()) {
            return $this->findAnyChild($category->children->first());
        }
        return $category->id;
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
