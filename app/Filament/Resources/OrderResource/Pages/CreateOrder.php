<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {


        $data['order_number'] = 'Invoice-' . Str::PadLeft(Order::query()->count() + 1, 7, '0');
        $data['user_id'] = auth()->id();
        return $data;
    }
}
