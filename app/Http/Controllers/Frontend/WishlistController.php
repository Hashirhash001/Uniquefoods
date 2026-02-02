<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Get wishlist (session or database)
     */
    private function getWishlist()
    {
        if (Auth::check()) {
            $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);
            return $wishlist->items()->pluck('product_id')->toArray();
        } else {
            return session()->get('wishlist', []);
        }
    }

    /**
     * Toggle wishlist (add/remove)
     */
    public function toggle(Request $request)
    {
        $productId = $request->product_id;
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if (Auth::check()) {
            return $this->toggleDatabase($product);
        } else {
            return $this->toggleSession($product);
        }
    }

    /**
     * Toggle in database (authenticated)
     */
    private function toggleDatabase($product)
    {
        DB::beginTransaction();
        try {
            $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);

            $wishlistItem = WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('product_id', $product->id)
                ->first();

            if ($wishlistItem) {
                // Remove
                $wishlistItem->delete();
                $action = 'removed';
                $message = $product->name . ' removed from wishlist';
            } else {
                // Add
                WishlistItem::create([
                    'wishlist_id' => $wishlist->id,
                    'product_id' => $product->id
                ]);
                $action = 'added';
                $message = $product->name . ' added to wishlist';
            }

            DB::commit();

            $count = WishlistItem::where('wishlist_id', $wishlist->id)->count();

            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update wishlist'
            ], 500);
        }
    }

    /**
     * Toggle in session (guest)
     */
    private function toggleSession($product)
    {
        $wishlist = session()->get('wishlist', []);

        if (in_array($product->id, $wishlist)) {
            // Remove
            $wishlist = array_diff($wishlist, [$product->id]);
            $action = 'removed';
            $message = $product->name . ' removed from wishlist';
        } else {
            // Add
            $wishlist[] = $product->id;
            $action = 'added';
            $message = $product->name . ' added to wishlist';
        }

        session()->put('wishlist', array_values($wishlist));

        return response()->json([
            'success' => true,
            'action' => $action,
            'message' => $message,
            'count' => count($wishlist)
        ]);
    }

    /**
     * Add to wishlist
     */
    public function add(Request $request)
    {
        $productId = $request->product_id;
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if (Auth::check()) {
            $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);

            WishlistItem::firstOrCreate([
                'wishlist_id' => $wishlist->id,
                'product_id' => $product->id
            ]);

            $count = WishlistItem::where('wishlist_id', $wishlist->id)->count();
        } else {
            $wishlist = session()->get('wishlist', []);
            if (!in_array($product->id, $wishlist)) {
                $wishlist[] = $product->id;
            }
            session()->put('wishlist', $wishlist);
            $count = count($wishlist);
        }

        return response()->json([
            'success' => true,
            'message' => $product->name . ' added to wishlist',
            'count' => $count
        ]);
    }

    /**
     * Remove from wishlist
     */
    public function remove(Request $request)
    {
        $productId = $request->product_id;

        if (Auth::check()) {
            $wishlist = Wishlist::where('user_id', Auth::id())->first();
            if ($wishlist) {
                WishlistItem::where('wishlist_id', $wishlist->id)
                    ->where('product_id', $productId)
                    ->delete();

                $count = WishlistItem::where('wishlist_id', $wishlist->id)->count();
            } else {
                $count = 0;
            }
        } else {
            $wishlist = session()->get('wishlist', []);
            $wishlist = array_diff($wishlist, [$productId]);
            session()->put('wishlist', array_values($wishlist));
            $count = count($wishlist);
        }

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist',
            'count' => $count
        ]);
    }

    /**
     * Get wishlist count
     */
    public function count()
    {
        $count = count($this->getWishlist());

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Get wishlist items (product IDs)
     */
    public function get()
    {
        $wishlist = $this->getWishlist();

        return response()->json([
            'success' => true,
            'items' => $wishlist
        ]);
    }

    /**
     * Wishlist index page
     */
    public function index()
    {
        if (Auth::check()) {
            $wishlist = Wishlist::where('user_id', Auth::id())->first();
            $products = $wishlist
                ? Product::whereIn('id', $wishlist->items()->pluck('product_id'))->get()
                : collect();
        } else {
            $wishlistIds = session()->get('wishlist', []);
            $products = Product::whereIn('id', $wishlistIds)->get();
        }

        return view('frontend.wishlist', compact('products'));
    }

    /**
     * Merge session wishlist to database (called after login)
     */
    public static function mergeSessionWishlistToDatabase($userId)
    {
        $sessionWishlist = session()->get('wishlist', []);

        if (empty($sessionWishlist)) {
            return;
        }

        DB::beginTransaction();
        try {
            $wishlist = Wishlist::firstOrCreate(['user_id' => $userId]);

            foreach ($sessionWishlist as $productId) {
                WishlistItem::firstOrCreate([
                    'wishlist_id' => $wishlist->id,
                    'product_id' => $productId
                ]);
            }

            // Clear session wishlist
            session()->forget('wishlist');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wishlist merge failed: ' . $e->getMessage());
        }
    }
}
