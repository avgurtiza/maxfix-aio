<?php

namespace App\Filament\Resources\VehicleResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('relationship')
                    ->options([
                        'owner' => 'Owner',
                        'driver' => 'Driver',
                        'mechanic' => 'Mechanic',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_primary')
                    ->label('Is Primary'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pivot.relationship')
                    ->label('Relationship'),
                Tables\Columns\IconColumn::make('pivot.is_primary')
                    ->boolean()
                    ->label('Primary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
