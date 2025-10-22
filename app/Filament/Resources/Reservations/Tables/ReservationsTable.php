<?php

namespace App\Filament\Resources\Reservations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('User')),
                TextColumn::make('track.title')
                    ->label(__('Track')),
                TextColumn::make('start_time')
                    ->label(__('Start time'))
                    ->dateTime('Y-m-d H:i'),
                TextColumn::make('end_time')
                    ->label(__('End time'))
                    ->dateTime('Y-m-d H:i'),
            ])
            ->defaultSort('start_time', direction: 'desc')
            ->filters([
                //
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
