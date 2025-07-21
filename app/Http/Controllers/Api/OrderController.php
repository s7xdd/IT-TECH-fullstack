<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class OrderController extends Controller
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

    public function index(Request $request, $orderId = null)
    {
        $this->trySanctumAuth($request);

        $user = auth()->user();

        $query = Order::with([
            'orderDetails.product',
            'orderDetails.order',
        ])->where('user_id', $user->id);

        if ($orderId) {
            $query->where('id', $orderId);
        }

        $orders = $query->orderBy('created_at', 'DESC')->get();

        $orderDetails = $orders->map(function ($order) use ($orderId) {
            if ($orderId) {
                return [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'estimated_delivery' => $order->estimated_delivery,
                    'shipping_address' => json_decode($order->shipping_address),
                    'billing_address' => json_decode($order->billing_address),
                    'order_notes' => $order->order_notes,
                    'delivery_status' => $order->delivery_status,
                    'payment_type' => $order->payment_type,
                    'payment_status' => $order->payment_status,
                    'payment_details' => $order->payment_details,
                    'shipping_type' => $order->shipping_type,
                    'shipping_cost' => $order->shipping_cost,
                    'tax' => $order->tax,
                    'grand_total' => $order->grand_total,
                    'sub_total' => $order->sub_total,
                    'coupon_discount' => $order->coupon_discount,
                    'coupon_code' => $order->coupon_code,
                    'offer_discount' => $order->offer_discount,
                    'tracking_code' => $order->tracking_code,
                    'delivery_completed_date' => $order->delivery_completed_date,
                    'date' => $order->date,
                    'cancel_request' => $order->cancel_request,
                    'cancel_request_date' => $order->cancel_request_date,
                    'cancel_approval' => $order->cancel_approval,
                    'cancel_approval_date' => $order->cancel_approval_date,
                    'cancel_reason' => $order->cancel_reason,
                    'return_request' => $order->return_request,
                    'return_request_date' => $order->return_request_date,
                    'return_approval' => $order->return_approval,
                    'return_approval_date' => $order->return_approval_date,
                    'return_reason' => $order->return_reason,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'order_details' => $order->orderDetails->map(function ($orderDetail) {
                        return [
                            'id' => $orderDetail->id,
                            'order_id' => $orderDetail->order_id,
                            'product_id' => $orderDetail->product_id,
                            'product_stock_id' => $orderDetail->product_stock_id,
                            'variation' => $orderDetail->variation,
                            'og_price' => $orderDetail->og_price,
                            'offer_price' => $orderDetail->offer_price,
                            'price' => $orderDetail->price,
                            'tax' => $orderDetail->tax,
                            'shipping_cost' => $orderDetail->shipping_cost,
                            'quantity' => $orderDetail->quantity,
                            'payment_status' => $orderDetail->payment_status,
                            'delivery_status' => $orderDetail->delivery_status,
                            'return_expiry_date' => $orderDetail->return_expiry_date,
                            'created_at' => $orderDetail->created_at,
                            'updated_at' => $orderDetail->updated_at,
                            'product' => [
                                'id' => $orderDetail->product->id,
                                'type' => $orderDetail->product->type,
                                'name' => $orderDetail->product->name,
                                'sku' => $orderDetail->product->sku,
                                'slug' => $orderDetail->product->slug,
                                'published' => $orderDetail->product->published,
                                'category_id' => $orderDetail->product->category_id,
                                'brand_id' => $orderDetail->product->brand_id,
                                'photos' => $orderDetail->product->photos,
                                'thumbnail_img' => $orderDetail->product->thumbnail_img,
                                'product_type' => $orderDetail->product->product_type,
                                'added_by' => $orderDetail->product->added_by,
                                'user_id' => $orderDetail->product->user_id,
                                'vat' => $orderDetail->product->vat,
                                'unit_price' => $orderDetail->product->unit_price,
                                'variant_product' => $orderDetail->product->variant_product,
                                'attributes' => $orderDetail->product->attributes,
                                'choice_options' => $orderDetail->product->choice_options,
                                'variations' => $orderDetail->product->variations,
                                'cash_on_delivery' => $orderDetail->product->cash_on_delivery,
                                'current_stock' => $orderDetail->product->current_stock,
                                'unit' => $orderDetail->product->unit,
                                'min_qty' => $orderDetail->product->min_qty,
                                'low_stock_quantity' => $orderDetail->product->low_stock_quantity,
                                'discount' => $orderDetail->product->discount,
                                'discount_type' => $orderDetail->product->discount_type,
                                'discount_start_date' => $orderDetail->product->discount_start_date,
                                'discount_end_date' => $orderDetail->product->discount_end_date,
                                'tax' => $orderDetail->product->tax,
                                'tax_type' => $orderDetail->product->tax_type,
                                'shipping_type' => $orderDetail->product->shipping_type,
                                'shipping_cost' => $orderDetail->product->shipping_cost,
                                'est_shipping_days' => $orderDetail->product->est_shipping_days,
                                'num_of_sale' => $orderDetail->product->num_of_sale,
                                'return_refund' => $orderDetail->product->return_refund,
                                'updated_by' => $orderDetail->product->updated_by,
                                'created_at' => $orderDetail->product->created_at,
                                'updated_at' => $orderDetail->product->updated_at,
                            ],
                        ];
                    }),
                ];
            } else {
                return [
                    'id' => $order->id,
                    'grand_total' => $order->grand_total,
                    'delivery_status' => $order->delivery_status,
                    'payment_type' => $order->payment_type,
                    'payment_status' => $order->payment_status,
                    'tracking_code' => $order->tracking_code,
                    'created_at' => $order->created_at,
                ];
            }
        });

        return response()->json([
            'success' => true,
            'data' => $orderId ? $orderDetails->first() : $orderDetails,
        ]);
    }


    public function cancelOrderRequest(Request $request)
    {
        $this->trySanctumAuth($request);

        $user = getUser();

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'cancel_reason' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $order_id = $request->order_id;
        $reason = $request->cancel_reason;

        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if (
            ($user['users_id_type'] === 'guest_id' && $order->guest_id != $user['users_id']) ||
            ($user['users_id_type'] === 'user_id' && $order->user_id != $user['users_id'])
        ) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to cancel this order'
            ], 403);
        }

        if ($order->cancel_request == 0 && $order->delivery_status == "pending") {
            $order->cancel_request = 1;
            $order->cancel_request_date = now();
            $order->cancel_reason = $reason;
            $order->save();

            $array['view'] = 'emails.commonmail';
            $array['subject'] = "New Order Cancel Request - " . $order->code;
            $array['from'] = env('MAIL_FROM_ADDRESS');
            $array['content'] = "<p>Hi,</p>
            <p style='line-height: 25px;'>We have received a new order cancel request. Below are the details of the order:</p>
            <p><b>Order Code : </b>" . $order->code . "</p>
            <p><b>Customer Name : </b>" . ($order->user ? $order->user->name : 'Guest') . "</p>
            <p style='line-height: 25px;'><b>Reason for cancel: </b>" . $reason . "</p>
            <p><b>Cancel Request Date: </b>" . now()->format('d-M-Y H:i a') . "</p><br>
            <p>Thank you for your cooperation.</p>
            <p>Best regards,</p>
            <p>Team " . env('APP_NAME') . "</p>";

            // Mail::to(env('MAIL_ADMIN'))->queue(new EmailManager($array));

            return response()->json([
                'status' => true,
                'message' => 'Order cancel request sent successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Order cannot be cancelled'
        ]);
    }


    public function returnOrderRequest(Request $request)
    {
        $this->trySanctumAuth($request);

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'return_reason' => 'required|string|max:255',
            'return_qty' => 'required|array',
            'return_qty.*' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = getUser();

        $order = Order::where('id', $request->order_id)
            ->where($user['users_id_type'], $user['users_id'])
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found or not accessible by you.'
            ], 403);
        }

        if ($order->delivery_status !== "delivered") {
            return response()->json([
                'status' => false,
                'message' => 'Order is not delivered yet. Return cannot be processed.'
            ], 400);
        }

        foreach ($request->return_qty as $orderDetailId => $qty) {
            $orderDetail = OrderDetail::find($orderDetailId);

            if (!$orderDetail || $orderDetail->order_id != $order->id) continue;

            $alreadyReturnedQty = $orderDetail->returns()->sum('return_qty');
            $remainingQty = $orderDetail->quantity - $alreadyReturnedQty;

            if ($qty <= $remainingQty && $qty > 0) {
                OrderReturn::create([
                    'order_id' => $order->id,
                    'order_detail_id' => $orderDetail->id,
                    'product_id' => $orderDetail->product_id,
                    'return_qty' => $qty,
                    'return_reason' => $request->return_reason,
                    'status' => 'Pending',
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Return request submitted successfully.'
        ]);
    }
}
