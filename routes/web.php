<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});


Route::get('/print/{order}', function (\App\Models\Order $order) {
    $order->load('customer', 'items.product');
    return view('print', compact('order'));
})->name('print');
