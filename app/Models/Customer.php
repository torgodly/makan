<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public static function Form(): array
    {
        return [
            TextInput::make('name')
                ->translateLabel()
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            TextInput::make('email')
                ->translateLabel()
                ->email()
                ->required()
                ->unique('customers', 'email', ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('phone')
                ->translateLabel()
                ->required()
                ->tel()
                ->maxLength(255),
        ];
    }
}
