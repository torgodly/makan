<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            $order->items()->create([
                'product_id' => Product::inRandomOrder()->first()->id,
                'quantity' => 1,
            ]);
        });
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'type' => 'admin'
        ]);
    }
}
