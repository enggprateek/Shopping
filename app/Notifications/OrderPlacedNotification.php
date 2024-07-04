<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $productNameList = '';
        foreach ($this->order->products as $product) {
            $productNameList .= '<li>' . $product->name . '</li>';
        }

        return (new MailMessage)
                ->subject('Order Confirmation')
                ->greeting('Hello!')
                ->line('Thank you for your order. Here are the details:')
                ->line('Email: ' . $this->order->email)
                ->line('Shipping Address: ' . $this->order->shipping_address_1 . ', ' . $this->order->shipping_address_2 . ', ' . $this->order->shipping_address_3)
                ->line('City: ' . $this->order->city)
                ->line('Country Code: ' . $this->order->country_code)
                ->line('Postal Code: ' . $this->order->zip_postal_code)
                ->line('Ordered Products:')
                ->line('<ul>' . $productNameList . '</ul>')
                ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
