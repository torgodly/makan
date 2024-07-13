<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Product::factory(10)->create();
        Customer::factory(10)->create();
        Order::factory(10)->create()->each(function ($order) {
            $prod = Product::inRandomOrder()->first();
            $order->items()->create([
                'product_id' => $prod->id,
                'quantity' => 1,
                'price' => $prod->price
            ]);
        });
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'type' => 'admin'
        ]);
    }
}
