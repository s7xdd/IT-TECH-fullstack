<?php

namespace App\Utility;

use App\Mail\InvoiceEmailManager;
use App\Models\User;
use App\Models\SmsTemplate;
use App\Http\Controllers\OTPVerificationController;
use Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use App\Models\FirebaseNotification;

class NotificationUtility
{
    public static function sendOrderPlacedNotification($order, $request = null)
    {
        //sends email to customer with the invoice pdf attached

        $array['view'] = 'emails.invoice';
        $array['subject'] = 'Thank you for your order - ' . $order->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['order'] = $order;

        try {
            if ($order->user_id !== null) {
                Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
            } else {
                $address = json_decode($order->billing_address);
                if (isset($address->email)) {
                    Mail::to($address->email)->queue(new InvoiceEmailManager($array));
                }
            }

            if (env('MAIL_ADMIN')) {
                $array['view'] = 'emails.invoice';
                $array['subject'] = 'A new order has been placed - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;
                Mail::to(env('MAIL_ADMIN'))->queue(new InvoiceEmailManager($array));
            }
        } catch (\Exception $e) {
        }
    }
    
}
