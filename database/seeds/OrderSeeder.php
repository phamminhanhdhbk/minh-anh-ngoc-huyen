<?php

use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all users
        $users = \App\User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        // Get all products
        $products = \App\Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please seed products first.');
            return;
        }

        // Create sample orders
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        for ($i = 1; $i <= 15; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];

            // Create order
            $order = \App\Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '0' . rand(900000000, 999999999),
                'customer_address' => 'Địa chỉ mẫu số ' . $i . ', Quận ' . rand(1, 12) . ', TP.HCM',
                'subtotal' => 0, // Will calculate later
                'tax' => 0,
                'shipping' => rand(0, 50000),
                'total' => 0, // Will calculate later
                'status' => $status,
                'notes' => $i % 3 == 0 ? 'Ghi chú đặc biệt cho đơn hàng ' . $i : null,
            ]);

            // Add random order items
            $numItems = rand(1, 3);
            $subtotal = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 2);
                $price = min(($product->sale_price ?: $product->price), 5000000); // Limit price to 5M
                $itemTotal = $quantity * $price;
                $subtotal += $itemTotal;

                \App\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'product_price' => $price,
                    'total' => $itemTotal,
                ]);
            }

            // Update order totals
            $total = $subtotal + $order->shipping;
            $order->update([
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        }

        $this->command->info('Created 15 sample orders with order items.');
    }
}
