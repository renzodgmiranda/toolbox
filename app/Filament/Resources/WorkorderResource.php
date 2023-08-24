<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkorderResource\Pages;
use App\Filament\Resources\WorkorderResource\RelationManagers;
use App\Models\Workorder;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkorderResource extends Resource
{
    protected static ?string $model = Workorder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Resources';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Label')
                ->tabs([
                    Tabs\Tab::make('Service Request Overview')
                        ->schema([
                            Select::make('wo_priority')->label('Priority')
                                ->selectablePlaceholder(false)
                                ->default('Low')
                                ->options([
                                    'Low' => 'Low',
                                    'Medium' => 'Medium',
                                    'High' => 'High'
                                ]),
                            TextInput::make('wo_trade')->label('Trade'),
                            TextInput::make('wo_category')->label('Category'),
                            TextInput::make('wo_tech_nte')->label('Tech. NTE'),
                            TextInput::make('wo_schedule')->label('Schedule'),
                        ]),
                    Tabs\Tab::make('Workorder Details')
                        ->schema([
                            TextInput::make('wo_number')->label('Workorder #'),
                            Select::make('customer_id')
                                ->relationship('customers', 'cus_name'),
                            TextInput::make('wo_problem')->label('Problem'),
                            TextInput::make('wo_problem_type')->label('Problem Type'),
                            MarkdownEditor::make('wo_description')->label('Description'),
                            TextInput::make('wo_customer_po')->label('Customer PO'),
                            TextInput::make('wo_asset')->label('Asset'),
                            Select::make('wo_status')->label('Status')
                                ->selectablePlaceholder(false)
                                ->default('Pending')
                                ->options([
                                    'Pending' => 'Pending',
                                    'Ongoing' => 'Ongoing',
                                    'Completed' => 'Completed'
                                ]),
                        ]),
                ])
                ->activeTab(1)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('wo_number')->label('Workorder #'),
                TextColumn::make('wo_problem')->label('Problem'),
                TextColumn::make('wo_problem_type')->label('Problem Type'),
                TextColumn::make('customer_id')->label('Customer'),
                TextColumn::make('wo_priority')->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {            
                        'Low' => 'success',
                        'Medium' => 'warning',
                        'High' => 'danger',
                    }),
                TextColumn::make('wo_status')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {     
                        'Completed' => 'success',
                        'Ongoing' => 'warning',
                        'Pending' => 'danger',
                    })
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
