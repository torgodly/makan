<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->requiresConfirmation()
                ->createAnother(false)
                ->modalIcon('heroicon-o-plus')
                ->modalDescription(__('Create User'))
                ->mutateFormDataUsing(function (array $data) {
                    $data['password'] = bcrypt(123456789);
                    return $data;
                })
            ,
        ];
    }
}
