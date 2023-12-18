<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews for a specific product.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($productId)
    {
        $reviews = Review::where('products_id', $productId)->get();

        return response()->json(['reviews' => $reviews]);
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'products_id' => 'required|exists:products,products_id',
            'user_id' => 'required|exists:user,user_id',
        ]);

        $review = Review::create([
            'comment' => $request->input('comment'),
            'rating' => $request->input('rating'),
            'products_id' => $request->input('products_id'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json(['review' => $review], 201);
    }

    /**
     * Display the specified review.
     *
     * @param  int  $reviewId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        return response()->json(['review' => $review]);
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $reviewId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $reviewId)
    {
        $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->update([
            'comment' => $request->input('comment', $review->comment),
            'rating' => $request->input('rating', $review->rating),
        ]);

        return response()->json(['review' => $review]);
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  int  $reviewId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
