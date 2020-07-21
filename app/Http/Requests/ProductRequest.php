<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;


class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function wantsJson()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => $validator->errors()], 400));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $data = file_get_contents('php://input');
        $a = $request->all();
        $rules = [
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|min:4',
            'description' => 'required',
            'shop.*.id' => 'exists:App\Models\Shop,id',
            'shop.*.price' => 'required',
            'shop.*.linkProduct' => 'required|url',
            'category' => 'required|exists:App\Models\Category,id',
            'advantages.*' => 'min:4',
            'shopNew.*.name' => 'required|min:4',
            'shopNew.*.description' => 'required',
            'shopNew.*.linkShop' => 'required|url',
            'shopNew.*.linkProduct' => 'required|url',
            'shopNew.*.price' => 'required'
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'images.*.image' => 'Images shoud be image',
            'images.*.mimes' => 'Images shoud be jpeg,png,jpg,gif',
            'images.*.max' => 'Images shoud not be more :max',
            'name.required' => 'The :attribute field is required',
            'name.min' => 'The :attribute field shoud not be less :min',
            'description.required' => 'The :attribute field is required',
            'category.required' => 'Category is required',
            'category.exists' => 'Category doesnt exist',
            'advantages.*.min' => 'Advantages shoud not be less :min',
            'shop.*.id.exists' => 'Shop doesnt exists',
            'shop.*.price.required' => 'Shop price is required',
            'shop.*.linkProduct.required' => 'Shop link product is required',
            'shop.*.linkProduct.url' => 'Shop link product shoud be url',
            'shopNew.*.name.required' => 'Shop`s name is required',
            'shopNew.*.name.min:4' => 'Shop`s name not be less :min',
            'shopNew.*.description.reqired' => 'Shop`s description is required',
            'shopNew.*.linkShop.required' => 'Shop`s link is required',
            'shopNew.*.linkShop.url' => 'Shop`s link shoud be url',
            'shopNew.*.linkProduct.required' => 'Shop`s link to product is required',
            'shopNew.*.price.required' => 'Shops price is required',
        ];
        return $messages;
    }
}
