<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->visible(fn($record) => $record->status === 'Open'),
            Actions\Action::make('Print')
                ->label('Print Invoice')
                ->translateLabel()
                ->icon('heroicon-o-printer')
                ->color('secondary')
                ->url(fn() => route('print', $this->record))
                ->openUrlInNewTab(),

            //add payment action
            Actions\Action::make('Add Payment')
                ->visible(fn($record) => $record->payment_need_to_be_paid > 0)
                ->requiresConfirmation()
                ->modalIcon('tabler-credit-card-pay')
                ->label('Add Payment')
                ->translateLabel()
                ->icon('tabler-credit-card-pay')
                ->color('orange')
                ->fillForm(fn($record) => [
                    'payment_date' => now(),
                    'amount' => $record->payment_need_to_be_paid,
                ])
                ->form([
                    Grid::make()->schema([
                        TextInput::make('amount')
                            ->translateLabel()
                            ->label('Amount')
                            ->numeric()
                            ->maxValue($this->record->payment_need_to_be_paid)
                            ->minValue(1)
                            ->rules('required', 'numeric'),
                        Select::make('payment_method')
                            ->translateLabel()
                            ->options([
                                'Cash' => 'Cash',
                                'Bank Transfer' => 'Bank Transfer',
                                'Credit Card' => 'Credit Card',
                                'Paypal' => 'Paypal',
                            ])
                            ->label('Payment Method')
                            ->rules('required'),
                    ]),
                    DatePicker::make('payment_date')
                        ->translateLabel()
                        ->default(now())
                        ->label('Payment Date')
                        ->rules('required', 'date'),

                ])
                ->action(fn($record, $data) => $record->payments()->create($data))
        ];
    }
}
