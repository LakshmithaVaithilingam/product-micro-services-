<?php

namespace App\Http\Controllers\Api;

use App\Models\Category; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        if($category ->count()>0){
            return response()->json([
                'status' => 200,
                'categories' => $category,
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
            'name' => 'required|max:191|unique:Category',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->messages(),
            ], 422);
        }else{
            $category = Category::create([
                'name' => $request->name,
            ]);

            if($category){
                return response()->json([
                'status' => 200,
                'message' => "category created successfully"
            ],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => "something went wrong"
                ],500);
            }
        }
    }

    public function show($id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such category found',
            ], 404);
        }
    }


    public function edit($id){
        $category = Category::find($id);
        if($category){
            return response()->json([
                'status' => 200,
                'category' => $category
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "No such Id found"
            ],404);
        }  
    }

    public function update(Request $request, $id)
{
    //$category = Category::find($id);
    $category = Category::where('category_id', $id)->first();

    if (!$category) {
        return response()->json([
            'status' => 404,
            'message' => 'No such category found',
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|max:191|unique:category',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'message' => 'Validation error',
            'errors' => $validator->messages(),
        ], 422);
    }

    try {
        $category->update([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Category updated successfully',
            'category' => $category,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Failed to update category',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /*public function destroy($id)
    {
        //$category = Category::find($id);
        $category = Category::where('category_id', $id)->first();

        if ($category) {
            $category->delete();

            return response()->json([
                'status' => 204,
                'message' => 'Category deleted successfully',
            ], 204);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such category found',
            ], 404);
        }
    }*/

    public function destroy($id)
    {
        //$category = Category::where('category_id', $id)->findOrFail();
        $category = Category::where('category_id', $id)->findOrFail($id);

        $category->delete();

        return response()->json([
            'status' => 204,
            'message' => 'Category deleted successfully',
        ], 204);
    }
}
