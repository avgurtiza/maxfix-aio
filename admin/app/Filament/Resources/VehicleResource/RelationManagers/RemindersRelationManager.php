<?php

namespace App\Filament\Resources\VehicleResource\RelationManagers;

use App\Models\MaintenanceReminder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RemindersRelationManager extends RelationManager
{
    protected static string $relationship = 'reminders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('service_name')
                    ->required(),
                Forms\Components\Select::make('reminder_type')
                    ->options([
                        'mileage' => 'Mileage',
                        'days' => 'Days',
                        'both' => 'Both',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('trigger_mileage')
                    ->numeric(),
                Forms\Components\TextInput::make('trigger_days')
                    ->numeric(),
                Forms\Components\DatePicker::make('next_due_date'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reminder_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('next_due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_due_mileage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
