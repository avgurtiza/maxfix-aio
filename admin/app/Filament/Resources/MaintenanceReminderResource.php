<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceReminderResource\Pages;
use App\Filament\Resources\MaintenanceReminderResource\RelationManagers;
use App\Models\MaintenanceReminder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenanceReminderResource extends Resource
{
    protected static ?string $model = MaintenanceReminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationGroup = 'Service Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reminder Details')
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'display_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('service_name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., Oil Change, Tire Rotation'),
                        Forms\Components\Select::make('reminder_type')
                            ->options([
                                'mileage' => 'Mileage Based',
                                'days' => 'Time Based (Days)',
                                'both' => 'Both Mileage and Time',
                            ])
                            ->required()
                            ->default('both'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Triggers')
                    ->schema([
                        Forms\Components\TextInput::make('trigger_mileage')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('miles')
                            ->helperText('Trigger reminder after this many miles'),
                        Forms\Components\TextInput::make('trigger_days')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('days')
                            ->helperText('Trigger reminder after this many days'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Next Due')
                    ->schema([
                        Forms\Components\DatePicker::make('next_due_date'),
                        Forms\Components\TextInput::make('next_due_mileage')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('miles'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.display_name')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_name')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('reminder_type')
                    ->colors([
                        'primary' => 'mileage',
                        'success' => 'days',
                        'warning' => 'both',
                    ]),
                Tables\Columns\TextColumn::make('next_due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_due_mileage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\SelectFilter::make('reminder_type')
                    ->options([
                        'mileage' => 'Mileage Based',
                        'days' => 'Time Based',
                        'both' => 'Both',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VehicleRelationManager::class,
            RelationManagers\CreatorRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceReminders::route('/'),
            'create' => Pages\CreateMaintenanceReminder::route('/create'),
            'edit' => Pages\EditMaintenanceReminder::route('/{record}/edit'),
        ];
    }
}
