<?php

namespace App\Http\Controllers\Api;

use App\Models\Subcategory; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategory = Subcategory::all();

        return response()->json([
            'status' => 200,
            'subcategory' => $subcategory,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191|unique:subcategory',
            'category_id' => 'required|exists:category,category_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }

        $subcategory = Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Subcategory created successfully',
            'subcategory' => $subcategory,
        ], 201);
    }

    public function show($id)
    {
        $subcategory = Subcategory::find($id);

        if ($subcategory) {
            return response()->json([
                'status' => 200,
                'subcategory' => $subcategory,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such subcategory found',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        //$subcategory = Subcategory::find($id);
        $subcategory = Subcategory::where('subcategory_id', $id)->first();

        if (!$subcategory) {
            return response()->json([
                'status' => 404,
                'message' => 'No such subcategory found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:191|unique:category',
            'category_id' => 'sometimes|required|exists:category,category_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }

        try {
            $subcategory->update([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Subcategory updated successfully',
                'subcategory' => $subcategory,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update subcategory',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $subcategory = Subcategory::find($id);

        if ($subcategory) {
            $subcategory->delete();

            return response()->json([
                'status' => 204,
                'message' => 'Subcategory deleted successfully',
            ], 204);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such subcategory found',
            ], 404);
        }
    }
}
