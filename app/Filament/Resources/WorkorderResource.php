<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkorderResource\Pages;
use App\Filament\Resources\WorkorderResource\RelationManagers;
use App\Filament\Widgets\WorkorderStats;
use App\Mail\WorkorderAssigned;
use App\Models\User;
use App\Models\Workorder;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class WorkorderResource extends Resource
{
    protected static ?string $model = Workorder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Resources';

    /**
     * Display Workorder badge count for different user roles.
     */
    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user->hasRole('Vendor')) {
            // Only count workorders assigned to the vendor
            return static::getModel()::where('user_id', $user->id)->count();
        }

        // For other roles or users without a specific role, count all workorders
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Workorder Details')
                            ->schema([
                                TextInput::make('wo_number')->label('Workorder #'),
                                Select::make('customer_id')->label('Customer')
                                    ->searchable()
                                    ->preload()
                                    ->relationship('customers', 'cus_name'),
                                Select::make('user_id')->label('Vendor')
                                    ->searchable()
                                    ->preload()
                                    ->relationship('users', 'name', function ($query) {
                                        $query->whereHas('roles', function ($subQuery) {
                                            $subQuery->where('name', 'vendor');
                                        });
                                    }),
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
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Service Request Overview')
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Function to get grouped vendor options
        $groupedVendors = function () {
            return User::query()
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'vendor');
                })
                ->get()
                ->groupBy('user_preferred')
                ->mapWithKeys(function ($group, $key) {
                    $label = $key ? 'Preferred Vendors' : 'Other Vendors';
                    return [
                        $label => $group->pluck('name', 'id')->toArray()
                    ];
                });
        };
        return $table
            /**
             * Modified table query for Vendors to show only Workorders that were assigned to them
             */
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                if($user->hasRole('Vendor')) {
                    $query->where('user_id', $user->id);
                }

                return $query;
            })
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('wo_number')->label('Workorder #')
                    ->searchable(),
                TextColumn::make('wo_problem')->label('Problem')
                    ->searchable(),
                TextColumn::make('wo_problem_type')->label('Problem Type'),
                TextColumn::make('customers.cus_name')->label('Customer')
                    ->searchable(),
                TextColumn::make('users.name')->label('Vendor')
                    ->searchable(),
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
                Tables\Actions\Action::make('Accept')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->button()
                    ->visible(function (Workorder $workorder) {
                        $user = Auth::user();

                        if($user->hasAnyRole(['Admin', 'Client'])) {
                            return false;
                        }

                        return $workorder->wo_status == 'Pending';
                    })
                    ->action(function (Workorder $workorder) {
                        $user = Auth::user();
                        $vendorId = $workorder->user_id;
                        $workorder->update([
                            'wo_status' => 'Ongoing',
                            'user_id' => $user->id,
                        ]);
                        $workorder->users()->associate($user);
                        $workorder->save();

                        // Get all Admin and Client users
                        $adminAndClient = User::role(['Admin', 'Client'])->get();
                        $vendor = User::find($vendorId)->name;
                        $vendorNotif = User::find($vendorId);

                        // Notify each Admin and Client user
                        foreach ($adminAndClient as $user) {
                            Notification::make()
                                ->success()
                                ->title('Accepted by Vendor (<strong>' . $workorder->wo_number . '</strong>)')
                                ->body('WO has been accepted by <strong>' . $vendor . '</strong>')
                                ->sendToDatabase($user);
                        }

                        Notification::make()
                            ->success()
                            ->title('Workorder Accepeted (<strong>' . $workorder->wo_number . '</strong>)')
                            ->body('You have accepted a Workorder')
                            ->sendToDatabase($vendorNotif);
                    }),
                Tables\Actions\Action::make('Decline')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->button()
                    ->visible(function (Workorder $workorder) {
                        $user = Auth::user();
                        return !$user->hasAnyRole(['Admin', 'Client']) && $workorder->wo_status == 'Pending';
                    })
                    ->action(function (Workorder $workorder) {
                        // Decline logic for current vendor
                        $oldVendorId = $workorder->user_id;
                        $secondVendorId = $workorder->second_user_id;
                        $thirdVendorId = $workorder->third_user_id;
                
                        // Function to reassign workorder and notify new vendor
                        $reassignWorkorder = function($vendorId) use ($workorder) {
                            $workorder->update(['user_id' => $vendorId]);
                            $workorder->save();
                
                            // Notify the new vendor
                            $newVendor = User::find($vendorId);
                            if ($newVendor) {
                                Notification::make()
                                    ->success()
                                    ->title('New Workorder Assignment (<strong>' . $workorder->wo_number . '</strong>)')
                                    ->body('You have been assigned a new work order')
                                    ->sendToDatabase($newVendor);
                            }
                        };
                
                        // Reassign to 2nd vendor, else 3rd vendor, else make it unassigned
                        if ($secondVendorId && $secondVendorId != $oldVendorId) {
                            $reassignWorkorder($secondVendorId);
                        } elseif ($thirdVendorId && $thirdVendorId != $oldVendorId && $thirdVendorId != $secondVendorId) {
                            $reassignWorkorder($thirdVendorId);
                        } else {
                            // No other preferred vendors, so make it unassigned
                            $workorder->update(['user_id' => null]);
                        }
                
                        // Notify Admins and Clients about the decline and reassignment
                        $adminAndClientUsers = User::role(['Admin', 'Client'])->get();
                        $oldVendorName = User::find($oldVendorId)->name;
                        $newVendorName = User::find($workorder->user_id)->name ?? 'No Vendor Found';
                
                        foreach ($adminAndClientUsers as $adminOrClientUser) {
                            Notification::make()
                                ->danger()
                                ->title('Declined by Vendor (<strong>' . $workorder->wo_number . '</strong>)')
                                ->body('WO has been declined by <strong>' . $oldVendorName . '</strong>. Reassigned to <strong>' . $newVendorName . '</strong>')
                                ->sendToDatabase($adminOrClientUser);
                        }
                
                        // Notify the old vendor that they have declined the work order
                        $oldVendorNotif = User::find($oldVendorId);
                        Notification::make()
                            ->danger()
                            ->title('Workorder Declined (<strong>' . $workorder->wo_number . '</strong>)')
                            ->body('You have declined a Workorder')
                            ->sendToDatabase($oldVendorNotif);
                    }),                
                Tables\Actions\Action::make('Complete')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->button()
                    ->visible(function (Workorder $workorder) {
                        $user = Auth::user();

                        if($user->hasAnyRole(['Admin', 'Client'])) {
                            return false;
                        }

                        return $workorder->wo_status == 'Ongoing';
                    })
                    ->action(function (Workorder $workorder) {
                        $user = Auth::user();
                        $vendorId = $workorder->user_id;
                        $workorder->update([
                            'wo_status' => 'Completed',
                            'user_id' => $user->id,
                        ]);
                        $workorder->users()->associate($user);
                        $workorder->save();

                        // Get all Admin and Client users
                        $adminAndClient = User::role(['Admin', 'Client'])->get();
                        $vendor = User::find($vendorId)->name;
                        $vendorNotif = User::find($vendorId);

                        // Notify each Admin and Client user
                        foreach ($adminAndClient as $user) {
                            Notification::make()
                                ->icon('heroicon-o-clipboard-document-check')
                                ->iconColor('success')
                                ->title('Completed by Vendor (<strong>' . $workorder->wo_number . '</strong>)')
                                ->body('WO has been completed by <strong>' . $vendor . '</strong>')
                                ->sendToDatabase($user);
                        }

                        Notification::make()
                            ->icon('heroicon-o-clipboard-document-check')
                            ->iconColor('success')
                            ->title('Workorder Complete (<strong>' . $workorder->wo_number . '</strong>)')
                            ->body('You have completed a Workorder')
                            ->sendToDatabase($vendorNotif);
                    }),
                Tables\Actions\Action::make('Assign WO')->label('Assign WO')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('gray')
                    ->button()
                    ->visible(function (Workorder $workorder) {
                        $user = Auth::user();

                        if($user->hasAnyRole(['Vendor'])) {
                            return false;
                        }

                        return $workorder->wo_status == 'Pending' && is_null($workorder->user_id);
                    })
                    ->form([
                        Select::make('Vendor')->label('1st Preferred Vendor')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->options($groupedVendors()),
                        
                        Select::make('secondVendor')->label('2nd Preferred Vendor')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->options($groupedVendors()),

                        Select::make('thirdVendor')->label('3rd Preferred Vendor')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->options($groupedVendors()),
                    ])
                    ->action(function (Workorder $workorder, array $data) {
                        $vendorId = $data['Vendor'];
                        $secondVendorId = $data['secondVendor'] ?? null;
                        $thirdVendorId = $data['thirdVendor'] ?? null;

                        $workorder->update([
                            'user_id' => $vendorId,
                            'second_user_id' => $secondVendorId,
                            'third_user_id' => $thirdVendorId,
                        ]);

                        $workorder->users()->associate($vendorId);
                        $workorder->save();

                        $vendor = User::find($vendorId);

                        Notification::make()
                            ->icon('heroicon-o-tag')
                            ->iconColor('success')
                            ->title($workorder->wo_problem . ' (<strong>' . $workorder->wo_number . '</strong>)')
                            ->body('You have been assigned a new Workorder')
                            ->sendToDatabase($vendor);

                        /**
                         * Temporarily disabled MailGun email notifications
                         */
                        //Mail::to($vendor->email)->send(new WorkorderAssigned($workorder));

                        /**
                         * Temporarily disabled Twilio SMS notifications
                         */
                        //if ($vendor->user_contact) {
                        //    $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
                        //    $messageBody = 'You have been assigned a (' . $workorder->wo_number . ') - ' . $workorder->wo_problem;
                        //    $message = $twilio->messages->create(
                        //        $vendor->user_contact,
                        //        [
                        //            'from' => config('services.twilio.phone'),
                        //            'body' => $messageBody
                        //        ]
                        //    );
                        //}
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->color('gray'),
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

    /**
     * Get all available widgets for Workorder resource
     */
    public static function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\WorkorderStats::class,
            \App\Filament\Resources\WorkorderResource\Widgets\VendorMap::class,
        ];
    }
}
