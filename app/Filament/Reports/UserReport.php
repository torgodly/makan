<?php

namespace App\Filament\Reports;

use App\Models\User;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Report;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Illuminate\Support\Collection;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class UserReport extends Report
{
    public ?string $heading = "Report";

    // public ?string $subHeading = "A great report";
    public function getHeading(): string
    {
        return __("User Sales Report");
    }



    public function header(Header $header): Header
    {
        return $header
            ->schema([
                // ...
            ]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Layout\BodyColumn::make()
                    ->schema([
                        Body\Table::make()
                            ->columns([
                                Body\TextColumn::make("name")
                                    ->label("Name")
                                    ->translateLabel(),
                                Body\TextColumn::make("orders_count")
                                    ->label("Total Sold")
                                    ->translateLabel()
                                    ->sum(),

                            ])
                            ->data(
                                fn(?array $filters) => $this->getData($filters)
                            )
                    ]),
            ]);
    }

    private function getData(?array $filters): Collection
    {
        // Get the date range from filters
        [$from, $to, $range] = getCarbonInstancesFromDateString($filters["created_at"] ?? null);


        return User::query()->when(optional($filters)['user_id'] ?? false, function ($query, $userId) {
            return $query->where('id', $userId);
        })->get()->map(function ($user) use ($from, $to) {
            return [
                'name' => $user->name,
                'orders_count' => $user->orders->whereBetween('created_at', [$from ?? now()->subDays(7), $to ?? now()])->count(),
                'cost' => $user->orders->sum('total_cost'),
                'price' => $user->orders->sum('total_price'),
            ];
        });


    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                // ...
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
//                Select::make('user_id')
//                    ->label('User')
//                    ->options(
//                        User::all()->pluck('name', 'id')
//                    )
//                    ->placeholder('Select a user'),
                DateRangePicker::make("created_at")
                    ->label("Date Range")
                    ->translateLabel()
                    ->defaultToday()
                    ->placeholder("Select a date range")
            ]);
    }

}
