<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Services\ReservationService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        $dateFormat = 'Y-m-d';

        return $schema
            ->components([
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->native(false)
                    ->minDate(now()->format($dateFormat))
                    ->maxDate(now()->addWeeks(2)->format($dateFormat))
                    ->format($dateFormat)
                    ->live()
                    ->required(),
                Radio::make('track')
                    ->label(__('Track'))
                    ->options(fn (Get $get) => (new ReservationService())->getAvailableTimesForDate($get('date')))
                    ->hidden(fn (Get $get) => ! $get('date'))
                    ->columnSpan(2)
                    ->required(),
            ]);
    }
}
