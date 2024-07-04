<?php

namespace Tests\Feature;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderSubmissionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_dispatches_order_submitted_event_with_notification()
    {
        Event::fake();

        // Create test data
        $productIds = Product::skip(0)->take(3)->pluck('id')->toArray();
        $email = 'test@yopmail.com';
        $shippingAddress = '123 Shipping Street';
        $city = "NY";
        $country_code = "IN";
        $zip_postal_code = "123456";

        // Submit order
        $response = $this->post('/order', [
            'email' => $email,
            'shipping_address_1' => $shippingAddress,
            'product' => $productIds,
            'city' => $city,
            'country_code' => $country_code,
            'zip_postal_code' => $zip_postal_code,
        ]);

        // Assert response is successful
        $response->assertRedirect(route('products'))
                 ->assertSessionHas('success', 'Order placed successfully!');
        $order = Order::latest()->first();
        // Assert event dispatched with correct data
        Event::assertDispatched(OrderPlaced::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
                   
        });

    }
}

