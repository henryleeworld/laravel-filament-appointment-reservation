<?php

namespace App\Filament\Resources\Tracks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TrackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->maxLength(255)
                    ->required(),
            ]);
    }
}
