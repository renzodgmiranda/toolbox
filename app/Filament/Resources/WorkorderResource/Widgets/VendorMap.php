<?php

namespace App\Filament\Resources\WorkorderResource\Widgets;

use App\Mail\WorkorderAssigned;
use App\Models\Customer;
use App\Models\User;
use App\Models\Workorder;
use Cheesegrits\FilamentGoogleMaps\Actions\GoToAction;
use Cheesegrits\FilamentGoogleMaps\Actions\RadiusAction;
use Cheesegrits\FilamentGoogleMaps\Filters\RadiusFilter;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Cheesegrits\FilamentGoogleMaps\Columns\MapColumn;
use Cheesegrits\FilamentGoogleMaps\Filters\MapIsFilter;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class VendorMap extends MapTableWidget
{
	protected int | string | array $columnSpan = 'full';

	protected static ?string $heading = 'Customer & Vendor Map';

	protected static ?int $sort = 1;

	protected static ?string $pollingInterval = null;

	protected static ?bool $clustering = true;

	protected static ?string $mapId = 'incidents';

	protected function getTableQuery(): Builder
	{
		return \App\Models\User::query()->latest();
	}

	protected function getTableColumns(): array
	{
		return [
			Tables\Columns\TextColumn::make('name'),
			Tables\Columns\TextColumn::make('email'),
			Tables\Columns\TextColumn::make('user_contact'),
		];
	}

	protected function getTableFilters(): array
	{
		return [
			RadiusFilter::make('user_location')
				->section('Radius Filter')
				->selectUnit(),
            MapIsFilter::make('map'),
		];
	}

	protected function getTableActions(): array
	{
		return [
			GoToAction::make()->label('Show Location')
                ->color('gray')
                ->button()
				->zoom(14),
			RadiusAction::make()->label('Assign WO')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('gray')
                ->button()
                ->form([
                    Select::make('Workorder')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options(Workorder::where('wo_status', 'Pending')
                            ->whereNull('user_id')
                            ->pluck('wo_number', 'id'))
                        ->required()
                        ->live(),
                ])
                ->action(function (User $vendor, array $data) {
                    $workorderId = $data['Workorder'];
                    $workorder = Workorder::find($workorderId);

                    $workorder->update([
                        'user_id' => $vendor->id,
                    ]);

                    $workorder->users()->associate($vendor->id);
                    $workorder->save();

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
		];
	}

	protected function getData(): array
	{
		$locations = $this->getRecords();
        $locationsCustomer = Customer::all();

		$data = [];

		foreach ($locations as $location)
		{
			$data[] = [
				'location' => [
					'lat' => $location->user_lat ? round(floatval($location->user_lat), static::$precision) : 0,
                    'lng' => $location->user_long ? round(floatval($location->user_long), static::$precision) : 0,
				],
                'id'      => $location->id,
                'label'     => $location->name,

                /**
				 * Optionally you can provide custom icons for the map markers,
				 * either as scalable SVG's, or PNG, which doesn't support scaling.
				 * If you don't provide icons, the map will use the standard Google marker pin.
				 */
				'icon' => [
					'url' => url('images/vendor-map-pin.svg'),
					'type' => 'svg',
                    'scale' => [35,35],
				],
			];
		}

        foreach ($locationsCustomer as $location)
        {
			/**
			 * Each element in the returned data must be an array
			 * containing a 'location' array of 'lat' and 'lng',
			 * and a 'label' string (optional but recommended by Google
			 * for accessibility.
			 *
			 * You should also include an 'id' attribute for internal use by this plugin
			 */
            $data[] = [
                'location'  => [
                    'lat' => $location->cus_lat ? round(floatval($location->cus_lat), static::$precision) : 0,
                    'lng' => $location->cus_long ? round(floatval($location->cus_long), static::$precision) : 0,
                ],

                'label'     => $location->cus_name,

				/**
				 * Optionally you can provide custom icons for the map markers,
				 * either as scalable SVG's, or PNG, which doesn't support scaling.
				 * If you don't provide icons, the map will use the standard Google marker pin.
				 */
				'icon' => [
					'url' => url('images/customer-map-pin.svg'),
					'type' => 'svg',
                    'scale' => [35,35],
				],
            ];
        }

		return $data;
	}
}
