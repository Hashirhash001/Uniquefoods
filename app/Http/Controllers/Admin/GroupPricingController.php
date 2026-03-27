<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\GroupDiscount;
use App\Models\GroupProductOffer;
use App\Models\ProductGroupPrice;
use App\Http\Controllers\Controller;

class GroupPricingController extends Controller
{
    /* ================= GROUP DISCOUNTS ================= */

    public function groupDiscounts(CustomerGroup $customerGroup)
    {
        $discounts = GroupDiscount::where('customer_group_id', $customerGroup->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $group = $customerGroup; // ✅

        return view('admin.group-pricing.discounts', compact('customerGroup', 'group', 'discounts'));
    }

    public function storeGroupDiscount(Request $request, CustomerGroup $customerGroup)
    {
        $validated = $request->validate([
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'is_active'        => 'boolean'
        ], [
            'type.required'               => 'Please select discount type',
            'type.in'                     => 'Invalid discount type',
            'value.required'              => 'Discount value is required',
            'value.numeric'               => 'Discount value must be a number',
            'value.min'                   => 'Discount value must be at least 0',
            'min_order_amount.numeric'    => 'Minimum order amount must be a number',
            'min_order_amount.min'        => 'Minimum order amount must be at least 0'
        ]);

        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return response()->json([
                'success' => false,
                'errors'  => ['value' => ['Percentage cannot exceed 100']]
            ], 422);
        }

        $validated['customer_group_id'] = $customerGroup->id;
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : 1;

        GroupDiscount::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Group discount created successfully'
        ]);
    }

    public function destroyGroupDiscount(GroupDiscount $discount)
    {
        $discount->delete();

        return response()->json([
            'success' => true,
            'message' => 'Discount rule deleted successfully'
        ]);
    }

    public function toggleGroupDiscount(GroupDiscount $discount)
    {
        $discount->update(['is_active' => !$discount->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $discount->is_active
        ]);
    }

    /* ================= PRODUCT-SPECIFIC PRICES ================= */

    public function productPrices(CustomerGroup $customerGroup)
    {
        $products = Product::active()->orderBy('name')->get();

        $groupPrices = ProductGroupPrice::where('customer_group_id', $customerGroup->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        $group = $customerGroup; // ✅ alias so the view can use $group

        return view('admin.group-pricing.product-prices', compact('customerGroup', 'group', 'products', 'groupPrices'));
    }

    public function storeProductPrice(Request $request, CustomerGroup $customerGroup)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price'      => 'required|numeric|min:0'
        ], [
            'product_id.required' => 'Please select a product',
            'product_id.exists'   => 'Selected product does not exist',
            'price.required'      => 'Price is required',
            'price.numeric'       => 'Price must be a number',
            'price.min'           => 'Price must be at least 0'
        ]);

        $validated['customer_group_id'] = $customerGroup->id;

        $existing = ProductGroupPrice::where('customer_group_id', $customerGroup->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            $existing->update(['price' => $validated['price']]);
            $message = 'Product price updated successfully';
        } else {
            ProductGroupPrice::create($validated);
            $message = 'Product price set successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function destroyProductPrice(ProductGroupPrice $price)
    {
        $price->delete();

        return response()->json([
            'success' => true,
            'message' => 'Custom price removed successfully'
        ]);
    }

    /* ================= PRODUCT OFFERS (Time-limited) ================= */

    public function productOffers(CustomerGroup $customerGroup)
    {
        $products   = Product::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $brands     = Brand::active()->orderBy('name')->get();

        $offers = GroupProductOffer::where('customer_group_id', $customerGroup->id)
            ->with(['product', 'category', 'brand'])
            ->orderBy('starts_at', 'desc')
            ->get();

        $group = $customerGroup; // ✅

        return view('admin.group-pricing.product-offers', compact('customerGroup', 'group', 'products', 'categories', 'brands', 'offers'));
    }

    public function storeProductOffer(Request $request, CustomerGroup $customerGroup)
    {
        $rules = [
            'offer_type' => 'required|in:product,category,brand',
            'starts_at'  => 'required|date|after_or_equal:today',
            'ends_at'    => 'required|date|after:starts_at',
        ];

        if ($request->offer_type === 'product') {
            $rules['product_id']  = 'required|exists:products,id';
            $rules['offer_price'] = 'required|numeric|min:0';
        }

        if ($request->offer_type === 'category') {
            $rules['category_id']    = 'required|exists:categories,id';
            $rules['discount_type']  = 'required|in:percentage,fixed';
            $rules['discount_value'] = 'required|numeric|min:0';
        }

        if ($request->offer_type === 'brand') {
            $rules['brand_id']       = 'required|exists:brands,id';
            $rules['discount_type']  = 'required|in:percentage,fixed';
            $rules['discount_value'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules, [
            'discount_type.required'  => 'Please select discount type',
            'discount_value.required' => 'Discount value is required',
        ]);

        if (($validated['discount_type'] ?? null) === 'percentage' && ($validated['discount_value'] ?? 0) > 100) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => ['discount_value' => ['Percentage cannot exceed 100']],
            ], 422);
        }

        $validated['customer_group_id'] = $customerGroup->id;

        if ($validated['offer_type'] !== 'product') {
            $validated['offer_price'] = null;
            $validated['product_id']  = null;
        }

        if ($validated['offer_type'] === 'product') {
            $validated['category_id'] = null;
            $validated['brand_id']    = null;
        }

        $overlap = GroupProductOffer::where('customer_group_id', $customerGroup->id)
            ->where('product_id', $validated['product_id'] ?? null)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('starts_at', [$validated['starts_at'], $validated['ends_at']])
                      ->orWhereBetween('ends_at', [$validated['starts_at'], $validated['ends_at']])
                      ->orWhere(function ($q) use ($validated) {
                          $q->where('starts_at', '<=', $validated['starts_at'])
                            ->where('ends_at', '>=', $validated['ends_at']);
                      });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'errors'  => ['starts_at' => ['An offer already exists for this product in the selected date range']]
            ], 422);
        }

        GroupProductOffer::create($validated);

        return response()->json(['success' => true, 'message' => 'Offer created successfully']);
    }

    public function destroyProductOffer(GroupProductOffer $offer)
    {
        $offer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Offer deleted successfully'
        ]);
    }
}
