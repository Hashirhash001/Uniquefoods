<?php
namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\ProductReview;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to write a review.'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'title'      => 'nullable|string|max:100',
            'body'       => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if user has purchased this product
        $hasPurchased = Order::where('user_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->whereHas('items', fn($q) => $q->where('product_id', $request->product_id))
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review products you have purchased and received.'
            ], 403);
        }

        // Check for existing review
        $existing = ProductReview::where('product_id', $request->product_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product.'
            ], 409);
        }

        // Get the order ID
        $order = Order::where('user_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->whereHas('items', fn($q) => $q->where('product_id', $request->product_id))
            ->latest()
            ->first();

        $review = ProductReview::create([
            'product_id'  => $request->product_id,
            'user_id'     => $user->id,
            'order_id'    => $order?->id,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'body'        => $request->body,
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your review!',
            'review'  => [
                'id'     => $review->id,
                'rating' => $review->rating,
                'body'   => $review->body,
                'name'   => $user->name,
                'avatar' => $user->profile_picture,
                'date'   => $review->created_at->format('d M Y'),
            ]
        ]);
    }

    public function update(Request $request, ProductReview $review)
    {
        if ($review->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:100',
            'body'   => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'title'  => $request->title,
            'body'   => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully.',
            'review'  => [
                'id'     => $review->id,
                'rating' => $review->rating,
                'title'  => $review->title,
                'body'   => $review->body,
            ]
        ]);
    }

    public function destroy(ProductReview $review)
    {
        if ($review->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $review->delete();

        return response()->json(['success' => true, 'message' => 'Review deleted.']);
    }

}
