<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property string $button_text
 * @property string|null $button_link
 * @property string|null $image
 * @property string $background_color
 * @property string $text_color
 * @property string|null $title_color
 * @property string|null $subtitle_color
 * @property string|null $description_color
 * @property string|null $subtitle_bg_color
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $image_url
 * @method static \Illuminate\Database\Eloquent\Builder|Banner active()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereButtonLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereDescriptionColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereSubtitleBgColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereSubtitleColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereTextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereTitleColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banner whereUpdatedAt($value)
 */
	class Banner extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupProductOffer> $groupOffers
 * @property-read int|null $group_offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Brand active()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand withoutTrashed()
 */
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $order_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain query()
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain whereOrderCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BuyAgain withoutTrashed()
 */
	class BuyAgain extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $subtotal
 * @property-read mixed $total_items
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $weight
 * @property string $price
 * @property string|null $price_per_kg
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @property-read mixed $subtotal
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePricePerKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereWeight($value)
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property int|null $parent_id
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read mixed $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupProductOffer> $groupOffers
 * @property-read int|null $group_offers_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category active()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category parents()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupDiscount> $groupDiscounts
 * @property-read int|null $group_discounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupProductOffer> $groupProductOffers
 * @property-read int|null $group_product_offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductGroupPrice> $productGroupPrices
 * @property-read int|null $product_group_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup withoutTrashed()
 */
	class CustomerGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $customer_group_id
 * @property string $type
 * @property string $value
 * @property string|null $min_order_amount
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CustomerGroup|null $group
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereCustomerGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereMinOrderAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupDiscount withoutTrashed()
 */
	class GroupDiscount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $customer_group_id
 * @property string $offer_type
 * @property string $discount_type
 * @property string|null $discount_value
 * @property int|null $product_id
 * @property int|null $category_id
 * @property int|null $brand_id
 * @property string|null $offer_price
 * @property \Illuminate\Support\Carbon|null $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\CustomerGroup $customerGroup
 * @property-read mixed $offer_name
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer active()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer expired()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer forBrand($brandId)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer forCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer forProduct($productId)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereCustomerGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereOfferPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereOfferType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupProductOffer withoutTrashed()
 */
	class GroupProductOffer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_number
 * @property int|null $user_id
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property string $shipping_address
 * @property string|null $restaurant_store
 * @property string $shipping_city
 * @property string $shipping_postcode
 * @property string $shipping_country
 * @property string|null $billing_address
 * @property string|null $billing_city
 * @property string|null $billing_postcode
 * @property string|null $billing_country
 * @property string $subtotal
 * @property string $shipping_cost
 * @property string $tax
 * @property string $discount
 * @property string $total
 * @property string $payment_method
 * @property string $payment_status
 * @property string|null $stripe_payment_intent_id
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property string $status
 * @property string|null $customer_notes
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBillingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBillingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBillingPostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRestaurantStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingPostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStripePaymentIntentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order withoutTrashed()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int|null $product_id
 * @property string $product_name
 * @property string|null $product_sku
 * @property string $price
 * @property int|null $quantity
 * @property string|null $weight
 * @property string $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereProductSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereWeight($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $mobile
 * @property string $otp
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Otp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Otp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Otp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereUpdatedAt($value)
 */
	class Otp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string $sku
 * @property int $category_id
 * @property int|null $brand_id
 * @property string|null $description
 * @property string $price
 * @property string|null $mrp
 * @property string|null $price_per_kg
 * @property int $stock
 * @property bool $is_weight_based
 * @property string $unit
 * @property string|null $min_weight
 * @property string|null $max_weight
 * @property bool $is_active
 * @property bool $is_featured
 * @property bool $is_popular
 * @property string|null $barcode
 * @property string|null $tax_rate
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerGroup> $customerGroups
 * @property-read int|null $customer_groups_count
 * @property-read mixed $average_rating
 * @property-read mixed $discount_percentage
 * @property-read mixed $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupProductOffer> $groupOffers
 * @property-read int|null $group_offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductGroupPrice> $groupPrices
 * @property-read int|null $group_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\ProductImage|null $primaryImage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuantityDiscount> $quantityDiscounts
 * @property-read int|null $quantity_discounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductReview> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\SubstitutionGroup|null $substitutionGroup
 * @method static \Illuminate\Database\Eloquent\Builder|Product active()
 * @method static \Illuminate\Database\Eloquent\Builder|Product featured()
 * @method static \Illuminate\Database\Eloquent\Builder|Product inStock()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product visibleTo(?\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsWeightBased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMaxWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMrp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePricePerKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property int $customer_group_id
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CustomerGroup|null $group
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice whereCustomerGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroupPrice withoutTrashed()
 */
	class ProductGroupPrice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $image_path
 * @property int $is_primary
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage withoutTrashed()
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property int|null $order_id
 * @property int $rating
 * @property string|null $title
 * @property string|null $body
 * @property int $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReview whereUserId($value)
 */
	class ProductReview extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property int $min_quantity
 * @property string $discount_amount
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount whereMinQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|QuantityDiscount withoutTrashed()
 */
	class QuantityDiscount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SubstitutionGroup withoutTrashed()
 */
	class SubstitutionGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $avatar
 * @property string|null $mobile
 * @property mixed|null $password
 * @property string|null $provider
 * @property string|null $provider_id
 * @property int $is_admin
 * @property int $is_verified
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserAddress> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BuyAgain> $buyAgainProducts
 * @property-read int|null $buy_again_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read mixed $profile_picture
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerGroup> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlistItems
 * @property-read int|null $wishlist_items_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $label
 * @property string $recipient_name
 * @property string $phone
 * @property string $address_line1
 * @property string|null $address_line2
 * @property string|null $restaurant_store
 * @property string $city
 * @property string|null $county
 * @property string $postcode
 * @property string $country
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereRecipientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereRestaurantStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress whereUserId($value)
 */
	class UserAddress extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total_items
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WishlistItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wishlist whereUserId($value)
 */
	class Wishlist extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $wishlist_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Wishlist $wishlist
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishlistItem whereWishlistId($value)
 */
	class WishlistItem extends \Eloquent {}
}

