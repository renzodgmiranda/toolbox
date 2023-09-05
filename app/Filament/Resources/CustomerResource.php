<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('cus_name')->label('Name'),
                                TextInput::make('cus_store_number')->label('Store #'),
                                TextInput::make('cus_facility_coordinator')->label('Facility Coordinator'),
                                TextInput::make('cus_facility_coordinator_contact')->label('Facility Coordinator Contact #'),
                                TextInput::make('cus_district_coordinator')->label('District Coordinator'),
                                TextInput::make('cus_district_coordinator_contact')->label('District Coordinator Contact #'),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cus_name')->label('Name'),
                TextColumn::make('cus_store_number')->label('Store #'),
                TextColumn::make('cus_facility_coordinator')->label('Facility Coordinator'),
                TextColumn::make('cus_facility_coordinator_contact')->label('Facility Coordinator Contact #'),
                TextColumn::make('cus_district_coordinator')->label('District Coordinator'),
                TextColumn::make('cus_district_coordinator_contact')->label('District Coordinator Contact #'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }    
}
