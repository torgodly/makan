<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {


        $data['order_number'] = 'Invoice-' . Str::PadLeft(Order::query()->count() + 1, 7, '0');
        $data['user_id'] = auth()->id();
//        dd($data);

        return $data;
    }

    protected function afterCreate(): void
    {
       if ($this->record->payment_method !== 'Deferred_Payment') {

           $this->record->payments()->create([
               'payment_date' => now(),
               'amount' => $this->record->payment_need_to_be_paid,
               'payment_method' => $this->record->payment_method,
           ]);
           $this->record->update([
                'status' => 'Finished',
            ]);
        }
    }


}
