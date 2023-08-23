<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkorderResource\Pages;
use App\Filament\Resources\WorkorderResource\RelationManagers;
use App\Models\Workorder;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkorderResource extends Resource
{
    protected static ?string $model = Workorder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Resources';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->relationship('customers', 'cus_name'),
                TextInput::make('wo_number')->label('Workorder #'),
                TextInput::make('wo_problem')->label('Problem'),
                TextInput::make('wo_problem_type')->label('Problem Type'),
                TextInput::make('wo_description')->label('Description'),
                TextInput::make('wo_custome_po')->label('Customer PO'),
                TextInput::make('wo_asset')->label('Asset'),
                TextInput::make('wo_priority')->label('Priority'),
                TextInput::make('wo_trade')->label('Trade'),
                TextInput::make('wo_category')->label('Category'),
                TextInput::make('wo_tech_nte')->label('Tech. NTE'),
                TextInput::make('wo_schedule')->label('Schedule'),
                TextInput::make('wo_status')->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListWorkorders::route('/'),
            'create' => Pages\CreateWorkorder::route('/create'),
            'edit' => Pages\EditWorkorder::route('/{record}/edit'),
        ];
    }    
}
