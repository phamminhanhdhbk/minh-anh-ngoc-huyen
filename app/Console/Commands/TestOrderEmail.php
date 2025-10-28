<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use App\Order;

class TestOrderEmail extends Command
{
    protected $signature = 'test:order-email {email?} {order_id?}';
    protected $description = 'Test sending order notification email';

    public function handle()
    {
        $email = $this->argument('email') ?: 'minhanh.itqn@gmail.com';
        $orderId = $this->argument('order_id');

        // Get order
        if ($orderId) {
            $order = Order::with('items')->find($orderId);
        } else {
            $order = Order::with('items')->latest()->first();
        }

        if (!$order) {
            $this->error('❌ No orders found in database!');
            $this->info('Please create a test order first.');
            return;
        }

        $this->info('=== ORDER INFORMATION ===');
        $this->info('Order ID: ' . $order->id);
        $this->info('Customer: ' . $order->customer_name);
        $this->info('Email: ' . $order->customer_email);
        $this->info('Total: ' . number_format($order->total, 0, ',', '.') . '₫');
        $this->info('Items: ' . $order->items->count());
        $this->line('');

        $this->info("Sending order email to: {$email}");

        try {
            Mail::to($email)->send(new OrderPlaced($order));

            $this->info('✅ Order email sent successfully!');
            $this->info('📧 Please check inbox (and spam folder) of: ' . $email);
            $this->line('');
            $this->info('Email contains:');
            $this->info('- Order details');
            $this->info('- Customer information');
            $this->info('- Product list with prices');
            $this->info('- Payment method');
            $this->info('- Shipping address');

        } catch (\Exception $e) {
            $this->error('❌ Failed to send order email!');
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
