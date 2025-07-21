<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CartController extends Controller
{
    protected function trySanctumAuth(Request $request)
    {
        if (!Auth::check()) {
            $bearerToken = $request->bearerToken();
            if ($bearerToken) {
                $accessToken = PersonalAccessToken::findToken($bearerToken);
                if ($accessToken && $accessToken->tokenable) {
                    Auth::login($accessToken->tokenable);
                }
            }
        }
    }

    public function index(Request $request)
    {
        $this->trySanctumAuth($request);

        $lang = getActiveLanguage();
        $user = Auth::user();
        $user_id = $user ? $user->id : null;

        $guest_token = $request->header('Guest-Token')
            ?? $request->input('guest_token')
            ?? null;

        if (!$guest_token) {
            $guest_token = uniqid('guest_', true);
        }


        if ($user_id && $guest_token) {
            $guestCartItems = Cart::where('temp_user_id', $guest_token)->get();
            foreach ($guestCartItems as $guestItem) {
                $existingItem = Cart::where('user_id', $user_id)
                    ->where('product_id', $guestItem->product_id)
                    ->where('product_stock_id', $guestItem->product_stock_id)
                    ->first();

                if ($existingItem) {
                    $existingItem->quantity += $guestItem->quantity;
                    $existingItem->save();
                    $guestItem->delete();
                } else {
                    $guestItem->user_id = $user_id;
                    $guestItem->temp_user_id = null;
                    $guestItem->save();
                }
            }
        }

        if ($user_id) {
            $carts = Cart::where('user_id', $user_id)->orderBy('id', 'asc')->with(['product', 'product_stock'])->get();
            $users_id_type = 'user_id';
            $users_id = $user_id;
        } else {
            $carts = Cart::where('temp_user_id', $guest_token)->orderBy('id', 'asc')->with(['product', 'product_stock'])->get();
            $users_id_type = 'temp_user_id';
            $users_id = $guest_token;
        }

        // Cart calculation
        $result = [];
        $overall_subtotal = $total_discount = $total_tax = $total_shipping = $cart_coupon_discount = 0;
        $cart_coupon_code = $cart_coupon_applied = null;

        if ($carts->isNotEmpty()) {
            foreach ($carts as $data) {
                $tax = 0;
                $priceData = getProductPrice($data->product_stock);
                if ($data->product->vat != 0) {
                    $tax = (($priceData['discounted_price'] * $data->quantity) / 100) * $data->product->vat;
                }
                $data->update([
                    'price' => $priceData['original_price'] ?? 0,
                    'offer_price' => $priceData['discounted_price'] ?? 0,
                    'offer_tag' => $priceData['offer_tag'] ?? null,
                    'tax' => $tax,
                    'discount' => $priceData['original_price'] - $priceData['discounted_price'],
                ]);
            }
            $carts = $carts->fresh();

            $coupon_code = $carts[0]->coupon_code;
            // Coupon logic (shortened for brevity, you can expand as needed)
            if ($coupon_code) {
                $coupon = Coupon::whereCode($coupon_code)->first();
                $can_use_coupon = false;
                if ($coupon) {
                    if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                        if ($coupon->one_time_use == 1) {
                            $coupon_used = $user_id
                                ? CouponUsage::where('user_id', $user_id)->where('coupon_id', $coupon->id)->first()
                                : CouponUsage::where('guest_token', $guest_token)->where('coupon_id', $coupon->id)->first();
                            if ($coupon_used == null) {
                                $can_use_coupon = true;
                            }
                        } else {
                            $can_use_coupon = true;
                        }
                    }
                }
                // Apply coupon if valid (expand logic as needed)
            }

            foreach ($carts as $datas) {
                $overall_subtotal += ($datas->price * $datas->quantity);
                $total_discount += (($datas->price * $datas->quantity) - ($datas->offer_price * $datas->quantity)) + ($datas->offer_discount ?? 0);
                $total_tax += $datas->tax;

                $result['products'][] = [
                    'id' => $datas->id,
                    'product' => [
                        'id' => $datas->product->id,
                        'product_variant_id' => $datas->product_stock->id,
                        'name' => $datas->product->getTranslation('name', $lang),
                        'brand' => $datas->product->brand->getTranslation('name', $lang),
                        'slug' => $datas->product->slug,
                        'sku' => $datas->product_stock->sku,
                        'max_qty' => $datas->product_stock->qty,
                        'image' => get_product_image($datas->product->thumbnail_img, '300')
                    ],
                    'stroked_price' => $datas->price,
                    'main_price' => $datas->offer_price,
                    'tax' => $datas->tax,
                    'offer_tag' => $datas->offer_tag,
                    'quantity' => (int) $datas->quantity,
                    'date' => $datas->created_at->diffForHumans(),
                    'total' => $datas->offer_price * $datas->quantity
                ];
                $cart_coupon_code = $datas->coupon_code;
                $cart_coupon_applied = $datas->coupon_applied;
                if ($datas->coupon_applied == 1) {
                    $cart_coupon_discount += $datas->discount;
                }
            }
        } else {
            $result['products'] = [];
        }

        $cart_coupon_discount = round($cart_coupon_discount);
        $cart_total = ($overall_subtotal + $total_tax) - ($total_discount + $cart_coupon_discount);

        // Shipping calculation
        $freeShippingStatus = get_setting('free_shipping_status');
        $freeShippingLimit = get_setting('free_shipping_min_amount');
        $defaultShippingCharge = get_setting('default_shipping_amount');
        $cartCount = count($carts);

        if ($freeShippingStatus == 1 && $cart_total >= $freeShippingLimit) {
            $total_shipping = 0;
            Cart::where($users_id_type, $users_id)->update([
                'shipping_cost' => 0,
                'shipping_type' => 'free',
                'updated_at' => now()
            ]);
        } else {
            $total_shipping = $defaultShippingCharge;
            if ($defaultShippingCharge > 0 && $cartCount != 0) {
                Cart::where($users_id_type, $users_id)->update([
                    'shipping_cost' => $defaultShippingCharge / $cartCount,
                    'shipping_type' => 'paid',
                    'updated_at' => now()
                ]);
            }
        }

        $total_shipping = ($overall_subtotal != 0) ? $total_shipping : 0;
        $cart_total = ($overall_subtotal + $total_shipping + $total_tax) - ($total_discount + $cart_coupon_discount);

        $result['summary'] = [
            'sub_total' => $overall_subtotal,
            'discount' => $total_discount,
            'after_discount' => $overall_subtotal - $total_discount,
            'shipping' => $total_shipping,
            'vat_amount' => $total_tax,
            'total' => $cart_total,
            'coupon_code' => $cart_coupon_code,
            'coupon_applied' => $cart_coupon_applied,
            'coupon_discount' => $cart_coupon_discount
        ];

        return response()->json([
            'status' => true,
            'data' => $result,
            'guest_token' => $user_id ? null : $guest_token // Only return guest_token for guests
        ]);
    }

    public function addToCart(Request $request)
    {
        $this->trySanctumAuth($request);

        $product_slug = $request->input('product_slug');
        $sku = $request->input('sku');
        $quantity = $request->input('quantity', 0);

        $user = Auth::user();
        $user_id = $user ? $user->id : null;
        $guest_token = $request->header('Guest-Token')
            ?? $request->input('guest_token')
            ?? uniqid('guest_', true);

        $users_id_type = $user_id ? 'user_id' : 'temp_user_id';
        $users_id = $user_id ?: $guest_token;

        $variantProduct = ProductStock::leftJoin('products as p', 'p.id', '=', 'product_stocks.product_id')
            ->where('p.sku', $sku)
            ->where('p.slug', $product_slug)
            ->select('product_stocks.*')->first();

        if ($variantProduct) {
            $product_id = $variantProduct['product_id'];
            $product_stock_id = $variantProduct['id'];
            $current_Stock = $variantProduct['qty'];

            $cart = Cart::where([
                $users_id_type => $users_id,
                'product_id' => $product_id,
                'product_stock_id' => $product_stock_id
            ])->first();

            $totalQuantityInCart = $quantity;
            $priceData = getProductPrice($variantProduct);
            $tax = 0;

            if ($cart) {
                $totalQuantityInCart += $cart->quantity;
                if ($current_Stock < $totalQuantityInCart) {
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.product_outofstock_msg') . '!',
                        'cart_count' => $this->cartCount($users_id_type, $users_id)
                    ]);
                }
                if ($variantProduct->product->vat != 0) {
                    $new_quantity = $cart->quantity + $quantity;
                    $tax = (($cart->offer_price * $new_quantity) / 100) * $variantProduct->product->vat;
                }
                $cart->quantity += $quantity;
                $cart->tax = $tax;
                $cart->price = $priceData['original_price'] ?? 0;
                $cart->offer_price = $priceData['discounted_price'] ?? 0;
                $cart->offer_tag = $priceData['offer_tag'] ?? null;
                $cart->save();
            } else {
                if ($current_Stock < $quantity) {
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.product_outofstock_msg') . '!',
                        'cart_count' => $this->cartCount($users_id_type, $users_id)
                    ]);
                }
                if ($variantProduct->product->vat != 0) {
                    $tax = (($priceData['discounted_price'] * ($quantity ?? 1)) / 100) * $variantProduct->product->vat;
                }
                $data[$users_id_type] = $users_id;
                $data['product_id'] = $product_id;
                $data['product_stock_id'] = $product_stock_id;
                $data['quantity'] = $quantity;
                $data['price'] = $priceData['original_price'] ?? 0;
                $data['offer_price'] = $priceData['discounted_price'] ?? 0;
                $data['offer_tag'] = $priceData['offer_tag'] ?? null;
                $data['tax'] = $tax;
                $data['shipping_cost'] = 0;
                Cart::create($data);
            }

            return response()->json([
                'status' => true,
                'message' => trans('messages.product_add_cart_success'),
                'cart_count' => $this->cartCount($users_id_type, $users_id),
                'guest_token' => $user_id ? null : $guest_token
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => trans('messages.product_add_cart_failed'),
                'cart_count' => $this->cartCount($users_id_type, $users_id),
                'guest_token' => $user_id ? null : $guest_token
            ]);
        }
    }

    public function removeCartItem(Request $request, $id)
    {
        $this->trySanctumAuth($request);

        $user = Auth::user();
        $user_id = $user ? $user->id : null;

        $guest_token = $request->header('Guest-Token')
            ?? $request->input('guest_token')
            ?? null;

        if (!$guest_token && !$user_id) {
            $guest_token = uniqid('guest_', true);
        }

        $users_id_type = $user_id ? 'user_id' : 'temp_user_id';
        $users_id = $user_id ?: $guest_token;

        if ($id && $users_id) {
            Cart::where([
                $users_id_type => $users_id,
                'id' => $id
            ])->delete();

            $updatedCart = Cart::where($users_id_type, $users_id)->orderBy('id', 'asc')->with(['product', 'product_stock'])->get();

            $summary = $this->getCartSummary($updatedCart);

            return response()->json([
                'status' => true,
                'message' => trans('messages.cart_item_removed_success'),
                'updatedCartSummary' => $summary,
                'guest_token' => $user_id ? null : $guest_token
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => trans('messages.cart_item_not_found'),
            'guest_token' => $user_id ? null : $guest_token
        ], 200);
    }

    public function changeQuantity(Request $request)
    {
        $this->trySanctumAuth($request);

        $cart_id  = $request->input('cart_id', '');
        $quantity = (int) $request->input('quantity', '');
        $action   = $request->input('action', '');

        $user = Auth::user();
        $user_id = $user ? $user->id : null;

        $guest_token = $request->header('Guest-Token')
            ?? $request->input('guest_token')
            ?? null;

        if (!$guest_token && !$user_id) {
            $guest_token = uniqid('guest_', true);
        }

        $users_id_type = $user_id ? 'user_id' : 'temp_user_id';
        $users_id = $user_id ?: $guest_token;

        if ($cart_id && $quantity !== '' && $action && $users_id) {
            $cart = Cart::where($users_id_type, $users_id)
                ->with(['product', 'product_stock'])
                ->find($cart_id);

            if (!$cart) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart item not found',
                    'guest_token' => $user_id ? null : $guest_token
                ], 200);
            }

            $max_qty = $cart->product_stock->qty;

            if ($action === 'plus') {
                if ($quantity <= $max_qty) {
                    $cart->quantity = $quantity;
                    $cart->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Cart updated',
                        'guest_token' => $user_id ? null : $guest_token
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Maximum quantity reached',
                        'guest_token' => $user_id ? null : $guest_token
                    ], 200);
                }
            } elseif ($action === 'minus') {
                if ($quantity < 1) {
                    $cart->delete();
                } else {
                    $cart->quantity = $quantity;
                    $cart->save();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Cart updated',
                    'guest_token' => $user_id ? null : $guest_token
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Undefined action value',
                    'guest_token' => $user_id ? null : $guest_token
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Missing or invalid data',
            'guest_token' => $user_id ? null : $guest_token
        ], 200);
    }


    public function apply_coupon_code(Request $request)
    {
        $this->trySanctumAuth($request);
        $user = Auth::user();
        $user_id = $user ? $user->id : null;
        $guest_token = $request->header('Guest-Token') ?? $request->input('guest_token') ?? null;

        if (!$user_id && !$guest_token) {
            return response()->json(['success' => false, 'message' => trans('messages.user_not_found')], 200);
        }

        $users_id_type = $user_id ? 'user_id' : 'temp_user_id';
        $users_id = $user_id ?: $guest_token;

        $cart_items = Cart::where($users_id_type, $users_id)->get();
        $cartCount = $cart_items->count();

        if ($cart_items->isEmpty()) {
            return response()->json(['success' => false, 'message' => trans('messages.cart_empty')], 200);
        }

        $coupon = Coupon::where('code', $request->coupon)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => trans('messages.invalid_coupon')], 200);
        }

        $today = strtotime(date('d-m-Y'));
        if ($today < $coupon->start_date || $today > $coupon->end_date) {
            return response()->json(['success' => false, 'message' => trans('messages.coupon_expired')], 200);
        }

        if ($coupon->one_time_use == 1) {
            $is_used = $user_id
                ? CouponUsage::where('user_id', $users_id)->where('coupon_id', $coupon->id)->exists()
                : CouponUsage::where('guest_token', $users_id)->where('coupon_id', $coupon->id)->exists();

            if ($is_used) {
                return response()->json(['success' => false, 'message' => 'Coupon already used'], 200);
            }
        }

        $coupon_details = json_decode($coupon->details);
        $coupon_discount = 0;

        if ($coupon->type == 'cart_base') {
            $subtotal = $tax = $shipping = 0;
            foreach ($cart_items as $item) {
                $subtotal += $item->offer_price * $item->quantity;
                $tax += $item->tax;
                $shipping += $item->shipping_cost;
            }

            $sum = $subtotal + $tax;

            if ($sum >= $coupon_details->min_buy) {
                if ($coupon->discount_type == 'percent') {
                    $coupon_discount = ($sum * $coupon->discount) / 100;
                    $coupon_discount = min($coupon_discount, $coupon_details->max_discount);
                } elseif ($coupon->discount_type == 'amount') {
                    $coupon_discount = $coupon->discount;
                }
            } else {
                return response()->json(['success' => false, 'message' => 'This Coupon cannot be used, please try another one'], 200);
            }
        } elseif ($coupon->type == 'product_base') {
            foreach ($cart_items as $item) {
                foreach ($coupon_details as $detail) {
                    if ($detail->product_id == $item->product_id) {
                        if ($coupon->discount_type == 'percent') {
                            $coupon_discount += ($item->offer_price * $coupon->discount / 100) * $item->quantity;
                        } elseif ($coupon->discount_type == 'amount') {
                            $coupon_discount += $coupon->discount * $item->quantity;
                        }
                    }
                }
            }

            if ($coupon_discount == 0) {
                return response()->json(['success' => false, 'message' => 'Sorry, this coupon cannot be applied to this order'], 200);
            }
        }

        Cart::where($users_id_type, $users_id)->update([
            'discount' => $coupon_discount / $cartCount,
            'coupon_code' => $request->coupon,
            'coupon_applied' => 1
        ]);

        return response()->json(['status' => true, 'message' => 'Coupon applied successfully'], 200);
    }

    public function remove_coupon_code(Request $request)
    {
        $this->trySanctumAuth($request);
        $user = Auth::user();
        $user_id = $user ? $user->id : null;
        $guest_token = $request->header('Guest-Token') ?? $request->input('guest_token') ?? null;
        $users_id_type = $user_id ? 'user_id' : 'temp_user_id';
        $users_id = $user_id ?: $guest_token;

        Cart::where($users_id_type, $users_id)->update([
            'discount' => 0.00,
            'coupon_code' => '',
            'coupon_applied' => 0
        ]);

        return response()->json(['status' => true, 'message' => trans('messages.coupon_removed')], 200);
    }



    public function getCount(Request $request)
    {
        $this->trySanctumAuth($request);

        $user = Auth::user();
        $user_id = $user ? $user->id : null;
        $guest_token = $request->cookie('guest_token')
            ?? $request->header('Guest-Token')
            ?? $request->input('guest_token')
            ?? null;

        $users_id_type = $user_id ? 'user_id' : 'temp_user_id';
        $users_id = $user_id ?: $guest_token;

        return response()->json([
            'status' => true,
            'cart_count' => $this->cartCount($users_id_type, $users_id),
            'guest_token' => $user_id ? null : $guest_token
        ]);
    }

    public function cartCount($users_id_type, $users_id)
    {
        return Cart::where([
            $users_id_type => $users_id
        ])->count();
    }


    private function getCartSummary($cartItems)
    {
        $subTotal = $cartItems->sum('price');
        $discount = 0;
        $shipping = 0;
        $vatAmount = $subTotal * 0.05;
        $total = $subTotal - $discount + $shipping + $vatAmount;

        return [
            'sub_total' => $subTotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'vat_amount' => $vatAmount,
            'total' => $total
        ];
    }
}
