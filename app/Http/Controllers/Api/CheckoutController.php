<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderTracking;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Address;
use App\Models\User;
use App\Notifications\NewOrderNotification;

class CheckoutController extends Controller
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

    public function placeOrder(Request $request)
    {
        $this->trySanctumAuth($request);

        $validatedData = $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_zipcode' => 'nullable|string',
            'billing_phone' => 'required|string|min:10',
            'billing_email' => 'required|email|max:255',
            'shipping_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_zipcode' => 'nullable|string',
            'shipping_phone' => 'nullable|string|min:10',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_country' => 'nullable|string|max:255',
        ], [
            'billing_name.required' => 'This field is required.',
            'billing_address.required' => 'This field is required.',
            'billing_city.required' => 'This field is required.',
            'billing_state.required' => 'This field is required.',
            'billing_country.required' => 'This field is required.',
            'billing_phone.required' => 'This field is required.',
            'billing_phone.min' => 'The phone number must be at least 10 digits.',
            'billing_email.required' => 'This field is required.',
            'billing_email.email' => 'The email address must be a valid email.',
            'billing_email.max' => 'The email address must not exceed 255 characters.',
            'shipping_phone.min' => 'The phone number must be at least 10 digits.',
        ]);



        $guest_token = $request->header('Guest-Token')
            ?? $request->input('guest_token')
            ?? $request->cookie('guest_token')
            ?? uniqid('guest_', true);

        $user = auth()->user();
        $user_id = $user ? $user->id : null;

        $users_id_type = $user_id ? 'user_id' : 'temp_user_id';
        $users_id = $user_id ?? $guest_token;

        $billing_address = [
            'name' => $request->billing_name,
            'email' => $request->billing_email,
            'address' => $request->billing_address,
            'zipcode' => $request->billing_zipcode,
            'city' => $request->billing_city,
            'state' => $request->billing_state,
            'country' => $request->billing_country,
            'phone' => $request->billing_phone,
        ];

        $billing_shipping_same = $request->same_as_billing ?? null;

        if ($billing_shipping_same != 'on') {
            $shipping_address = [
                'name' => $request->shipping_name ?? $request->billing_name,
                'email' => $request->billing_email,
                'address' => $request->shipping_address ?? $request->billing_address,
                'zipcode' => $request->shipping_zipcode ?? $request->billing_zipcode,
                'city' => $request->shipping_city ?? $request->billing_city,
                'state' => $request->shipping_state ?? $request->billing_state,
                'country' => $request->shipping_country ?? $request->billing_country,
                'phone' => $request->shipping_phone ?? $request->billing_phone,
            ];
        } else {
            $shipping_address = $billing_address;
        }

        $shipping_address_json = json_encode($shipping_address);
        $billing_address_json = json_encode($billing_address);

        if ($user_id && empty($request->address_id)) {
            $addressNew = new Address;
            $addressNew->user_id = $user_id;
            $addressNew->address = $request->billing_address ?? null;
            $addressNew->name = $request->billing_name ?? null;
            $addressNew->city = $request->billing_city ?? null;
            $addressNew->state_name = $request->billing_state ?? null;
            $addressNew->country_name = $request->billing_country ?? null;
            $addressNew->postal_code = $request->billing_zipcode ?? null;
            $addressNew->type = 'other';
            $addressNew->phone = $request->billing_phone;
            $addressNew->save();
        }

        $carts = Cart::where($users_id_type, $users_id)->orderBy('id', 'asc')->with(['product', 'product_stock'])->get();

        if ($carts->isNotEmpty()) {
            $sub_total = $discount = $coupon_applied = $total_coupon_discount = $grand_total = $total_shipping = $total_tax = 0;
            $coupon_code = '';

            $order = Order::create([
                'user_id' => $user_id,
                'shipping_address' => $shipping_address_json,
                'billing_address' => $billing_address_json,
                'order_notes' => $request->order_note ?? '',
                'shipping_type' => 'free_shipping',
                'shipping_cost' => 0,
                'delivery_status' => 'pending',
                'payment_type' => $request->payment_method ?? '',
                'payment_status' => 'un_paid',
                'grand_total' =>  0,
                'tax' => 0,
                'sub_total' => 0,
                'offer_discount' => 0,
                'coupon_discount' => 0,
                'code' => date('Ymd-His') . rand(10, 99),
                'date' => strtotime('now'),
                'delivery_viewed' => 0
            ]);

            $track = new OrderTracking;
            $track->order_id = $order->id;
            $track->status = 'pending';
            $track->description = "The order has been placed successfully";
            $track->status_date = date('Y-m-d H:i:s');
            $track->save();

            $orderItems = [];
            $productQuantities = [];

            foreach ($carts as $data) {
                $sub_total += ($data->price * $data->quantity);
                $total_tax += $data->tax;
                $total_shipping += $data->shipping_cost;
                $discount += (($data->price * $data->quantity) - ($data->offer_price * $data->quantity)) + ($data->offer_discount ?? 0);
                $coupon_code = $data->coupon_code;
                $coupon_applied = $data->coupon_applied;
                if ($coupon_applied == 1) {
                    $total_coupon_discount += $data->discount;
                }

                $orderItems[] = [
                    'order_id' => $order->id,
                    'product_id' => $data->product_id,
                    'product_stock_id' => $data->product_stock->id,
                    'og_price' => $data->price,
                    'tax' => $data->tax,
                    'shipping_cost' => $data->shipping_cost,
                    'offer_price' => $data->offer_price,
                    'price' => $data->offer_price * $data->quantity,
                    'quantity' => $data->quantity,
                ];
                $productQuantities[$data->product_id] = $data->quantity;

                $product = Product::find($data->product_id);
                if ($product) {
                    $product->num_of_sale += $data->quantity;
                    $product->save();
                }
            }

            OrderDetail::insert($orderItems);

            $grand_total = ($sub_total + $total_tax + round($total_shipping)) - ($discount + $total_coupon_discount);

            $order->grand_total = $grand_total;
            $order->sub_total = $sub_total;
            $order->offer_discount = $discount;
            $order->tax = $total_tax;
            $order->shipping_cost = round($total_shipping);
            $order->shipping_type = ($total_shipping == 0) ? 'free_shipping' : 'flat_rate';
            $order->coupon_discount = round($total_coupon_discount);
            $order->coupon_code = $coupon_code;
            $order->save();

            if (!empty($coupon_code)) {
                $coupon_usage = new CouponUsage;
                if ($user_id) {
                    $coupon_usage->user_id = $user_id;
                } else {
                    $coupon_usage->guest_token = $guest_token;
                }
                $coupon = Coupon::where('code', $coupon_code)->first();
                if ($coupon) {
                    $coupon_usage->coupon_id = $coupon->id;
                    $coupon_usage->save();
                }
            }

            reduceProductQuantity($productQuantities);

            if ($user_id) {
                Cart::where('user_id', $user_id)->delete();
            } elseif ($guest_token) {
                Cart::where('temp_user_id', $guest_token)->delete();
            }

            // NotificationUtility::sendOrderPlacedNotification($order);
            $admins = User::where('user_type', 'admin')->get();
            $admins->each(function ($admin) use ($order) {
                $admin->notify(new NewOrderNotification($order));
            });

            return response()->json([
                'success' => true,
                'message' => "Order placed successfully.",
                'data' => [
                    ...$order->toArray(),
                    'shipping_address' => json_decode($order->shipping_address),
                    'billing_address' => json_decode($order->billing_address),
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Order failed. Please try again"
            ]);
        }
    }

}
