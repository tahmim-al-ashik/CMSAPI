<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends BaseController
{


    // index function to fetch all data
public function index(){

    $products = Product::all();

    // return $this->sendResponse($products->toArray(),'Products Retrived');

    return $this->sendResponse(ProductResource::collection($products),'Product Retrived');
}


// store function to store data of product

public function store(Request $request){

    $validator = Validator::make($request->all(),[
        'name'       =>'required',
        'description'=> 'required',
    ]);

    if ($validator->fails())
    {
        return $this->sendError('validation Error', $validator->errors());
    }

    $products= Product::create($request->all());
    return $this->sendResponse(new ProductResource($products),'Product Created Successfully');
}


// show function

public function show($id)
{
    $products = Product::find($id);
    if(is_null($products)){
        return $this->sendError('Product not found');
    }

    return $this->sendResponse(new ProductResource($products), 'Product retrived');
}


// update product

public function update(Request $request, Product $product)
{
    $validator = Validator::make($request->all(),[
        'name'       => 'required',
        'description'=> 'required',
    ]);

    if ($validator->fails())
    {
        return $this->sendError('validation Error', $validator->errors());
    }

    $product->update($request->all());

    return $this->sendResponse(new ProductResource($product),'Product Updated');
}

public function destroy(Product $product){
    $product->delete();
    return $this->sendResponse(new ProductResource($product),'Product destroyed');
}
}
