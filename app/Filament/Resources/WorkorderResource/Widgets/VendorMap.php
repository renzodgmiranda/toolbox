<?php

namespace App\Filament\Resources\WorkorderResource\Widgets;

use App\Models\User;
use App\Models\Workorder;
use Cheesegrits\FilamentGoogleMaps\Actions\GoToAction;
use Cheesegrits\FilamentGoogleMaps\Actions\RadiusAction;
use Cheesegrits\FilamentGoogleMaps\Filters\RadiusFilter;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Cheesegrits\FilamentGoogleMaps\Columns\MapColumn;
use Cheesegrits\FilamentGoogleMaps\Filters\MapIsFilter;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class VendorMap extends MapTableWidget
{
	protected int | string | array $columnSpan = 'full';

	protected static ?string $heading = 'Vendor Map';

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
                }),
		];
	}

	protected function getData(): array
	{
		$locations = $this->getRecords();

		$data = [];

		foreach ($locations as $location)
		{
			$data[] = [
				'location' => [
					'lat' => $location->user_lat ? round(floatval($location->user_lat), static::$precision) : 0,
                    'lng' => $location->user_long ? round(floatval($location->user_long), static::$precision) : 0,
				],
                'id'      => $location->id,
			];
		}

		return $data;
	}
}
