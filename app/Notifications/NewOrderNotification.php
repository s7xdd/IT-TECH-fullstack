<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];  // Save notification to the database
    }

    public function toArray($notifiable)
    {
        $name = '';
        if ($this->order->user_id !== null) {
            $name = $this->order->user->name;
        } else {
            $address = json_decode($this->order->billing_address);
            if (isset($address->name)) {
                $name = $address->name;
            }
        }
        return [
            'order_id' => $this->order->id,
            'user_name' => $name ?? 'Guest',
            'message' => 'New order placed by ' . $name ?? 'Guest',
        ];
    }
}
