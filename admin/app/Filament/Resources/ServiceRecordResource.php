<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceRecordResource\Pages;
use App\Filament\Resources\ServiceRecordResource\RelationManagers;
use App\Models\ServiceRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceRecordResource extends Resource
{
    protected static ?string $model = ServiceRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Service Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Service Details')
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'display_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('shop_id')
                            ->relationship('shop', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('service_date')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('mileage')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\Select::make('service_type')
                            ->options([
                                'oil_change' => 'Oil Change',
                                'tire_rotation' => 'Tire Rotation',
                                'brake_service' => 'Brake Service',
                                'air_filter' => 'Air Filter Replacement',
                                'transmission' => 'Transmission Service',
                                'coolant' => 'Coolant Flush',
                                'battery' => 'Battery Replacement',
                                'spark_plugs' => 'Spark Plugs',
                                'belt_replacement' => 'Belt Replacement',
                                'inspection' => 'Inspection',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('cost')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0),
                        Forms\Components\FileUpload::make('receipt_path')
                            ->directory('receipts')
                            ->preserveFilenames(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('service_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.display_name')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('service_type')
                    ->colors([
                        'primary' => ['oil_change', 'tire_rotation', 'brake_service'],
                        'success' => ['air_filter', 'battery', 'spark_plugs'],
                        'warning' => ['transmission', 'coolant', 'belt_replacement'],
                        'gray' => ['inspection', 'other'],
                    ]),
                Tables\Columns\TextColumn::make('mileage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('shop.name')
                    ->label('Shop')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('service_type')
                    ->options([
                        'oil_change' => 'Oil Change',
                        'tire_rotation' => 'Tire Rotation',
                        'brake_service' => 'Brake Service',
                        'air_filter' => 'Air Filter Replacement',
                        'transmission' => 'Transmission Service',
                        'coolant' => 'Coolant Flush',
                        'battery' => 'Battery Replacement',
                        'spark_plugs' => 'Spark Plugs',
                        'belt_replacement' => 'Belt Replacement',
                        'inspection' => 'Inspection',
                        'other' => 'Other',
                    ]),
                Tables\Filters\Filter::make('service_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['from'] ?? null) {
                            $query->whereDate('service_date', '>=', $data['from']);
                        }
                        if ($data['until'] ?? null) {
                            $query->whereDate('service_date', '<=', $data['until']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            RelationManagers\ShopRelationManager::class,
            RelationManagers\CreatorRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceRecords::route('/'),
            'create' => Pages\CreateServiceRecord::route('/create'),
            'edit' => Pages\EditServiceRecord::route('/{record}/edit'),
        ];
    }
}
