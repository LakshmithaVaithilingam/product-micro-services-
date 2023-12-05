<?php

namespace App\Http\Controllers\Api;

use App\Models\Product; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        if($products ->count()>0){
            return response()->json([
                'status' => 200,
                'products' => $products,
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No Records Found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191|unique:products',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'size' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'price' => 'required|numeric',
            //'images' => 'required|json',
            'images' => 'required|array',
            'category_id' => 'required|exists:category,category_id',
            'subcategory_id' => 'required|exists:subcategory,subcategory_id',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }

        $products = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'quantity' => $request->input('quantity'),
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'price' => $request->input('price'),
            //'images' => json_decode($request->input('images'), true),
            'images' => $request->input('images'),
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Product created successfully',
            'product' => $products,
        ], 201);
    }

    public function show($id)
    {
        //$products = Product::find($id);
        $products = Product::where('products_id', $id)->first();

        if ($products) {
            return response()->json([
                'status' => 200,
                'product' => $products,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such product found',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        //$products = Product::find($id);
        $products = Product::where('products_id', $id)->first();

        if (!$products) {
            return response()->json([
                'status' => 404,
                'message' => 'No such product found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:191|unique:products',
            'description' => 'sometimes|required|string',
            'quantity' => 'sometimes|required|integer',
            'size' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            //'images' => 'required|json',
            'images' => 'sometimes|required|array',
            'category_id' => 'sometimes|required|exists:category,category_id',
            'subcategory_id' => 'sometimes|required|exists:subcategory,subcategory_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }

        try {
            $products->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'quantity' => $request->input('quantity'),
                'size' => $request->input('size'),
                'color' => $request->input('color'),
                'price' => $request->input('price'),
                //'images' => json_decode($request->input('images'), true),
                'images' => $request->input('images'),
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $request->input('subcategory_id'),
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Product updated successfully',
                'product' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
   {
    $products = Product::find($id);

    if ($products) {
        return response()->json([
            'status' => 200,
            'product' => $products,
        ], 200);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'No such product found',
        ], 404);
    }
    }

    public function destroy($id)
    {
        //$products = Product::find($id);
        $products = Product::where('products_id', $id)->first();

        if ($products) {
            $products->delete();

            return response()->json([
                'status' => 204,
                'message' => 'Product deleted successfully',
            ], 204);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such product found',
            ], 404);
        }

}       
}