<?php

use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all users and products
        $users = \App\User::all();
        $products = \App\Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('No users or products found for cart seeding.');
            return;
        }

        // Create sample cart items for some users
        foreach ($users->take(2) as $user) {
            $numItems = rand(2, 5);

            for ($i = 0; $i < $numItems; $i++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->sale_price ?: $product->price;

                // Check if cart item already exists for this user/product
                $existingCart = \App\Cart::where('user_id', $user->id)
                                        ->where('product_id', $product->id)
                                        ->first();

                if (!$existingCart) {
                    \App\Cart::create([
                        'user_id' => $user->id,
                        'session_id' => 'user_' . $user->id . '_session',
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => min($price, 2000000), // Limit to 2M
                    ]);
                }
            }
        }

        $this->command->info('Created sample cart items for users.');
    }
}
