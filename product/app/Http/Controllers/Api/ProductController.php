<?php

namespace App\Http\Controllers\Api;

use App\Models\Product; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query builder for the Product model
        $query = Product::query();

        // Apply filters if provided in the request

        // Check if 'category_id' is present in the request
        if ($request->has('category_id')) {
            // Add a WHERE clause to filter by category_id
            $query->where('category_id', $request->input('category_id'));
        }

        // Check if 'subcategory_id' is present in the request
        if ($request->has('subcategory_id')) {
            // Add a WHERE clause to filter by subcategory_id
            $query->where('subcategory_id', $request->input('subcategory_id'));
        }

        // Check if 'age_group' is present in the request
        if ($request->has('age_group')) {
            // Add a WHERE clause to filter by age_group
            $query->where('age_group', $request->input('age_group'));
        }

        // Check if 'price_min' and 'price_max' are present in the request
        if ($request->has('price_min') && $request->has('price_max')) {
            // Add a WHERE clause to filter by price range
            $query->whereBetween('price', [$request->input('price_min'), $request->input('price_max')]);
        }

        // Get the final set of products based on applied filters
        $products = $query->get();

        // Return the filtered products in the response
        return response()->json([
            'status' => 200,
            'products' => $products,
        ], 200);
    }

    public function store(Request $request)
    {
        \Log::info($request->all());
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
            'age_group' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }

         // Handle file uploads
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
        $path = $image->store('images','public'); // 'images' is the storage disk you want to use
        $imagePaths[] = asset('storage/' . $path);
        } 

        $products = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'quantity' => $request->input('quantity'),
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'price' => $request->input('price'),
            //'images' => $request->input('images'),
            'images' => $imagePaths,
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
            'age_group' => $request->input('age_group'),
            'discount' => $request->input('discount'),
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Product created successfully',
            'product' => $products,
            'imagePaths' => $imagePaths,
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
        \Log::info($request->all());
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
            'age_group' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }
        // Handle file uploads
        $imagePaths = [];
        $images = $request->file('images');

        if ($images && is_array($images)) {
            foreach ($images as $image) {
            $path = $image->store('images','public'); // 'images' is the storage disk you want to use
            $imagePaths[] = $path;
        }
        } else {
        // Handle the case where 'images' is not present or not an array
        return response()->json([
        'status' => 422,
        'message' => 'Images must be provided as an array in the request.',
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
                //'images' => $request->input('images'),
                'images' => $imagePaths,
                'category_id' => $request->input('category_id'),
                'subcategory_id' => $request->input('subcategory_id'),
                'age_group' => $request->input('age_group'),
                'discount' => $request->input('discount'),
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
    

    public function getRelatedProducts($categoryId, $selectedProductId)
    {
        $relatedProducts = Product::where('category_id', $categoryId)
            ->where('products_id', '!=', $selectedProductId) // Exclude the selected product itself
            ->limit(5) // Adjust the limit as needed
            ->get();

        return response()->json([
            'status' => 200,
            'relatedProducts' => $relatedProducts,
        ], 200);
    }

}