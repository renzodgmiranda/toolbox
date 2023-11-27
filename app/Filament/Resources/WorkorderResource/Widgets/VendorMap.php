<?php

namespace App\Filament\Resources\WorkorderResource\Widgets;

use Cheesegrits\FilamentGoogleMaps\Actions\GoToAction;
use Cheesegrits\FilamentGoogleMaps\Actions\RadiusAction;
use Cheesegrits\FilamentGoogleMaps\Filters\RadiusFilter;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Cheesegrits\FilamentGoogleMaps\Columns\MapColumn;
use Cheesegrits\FilamentGoogleMaps\Filters\MapIsFilter;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class VendorMap extends MapTableWidget
{
	protected int | string | array $columnSpan = 'full';
	
	protected static ?string $heading = 'User Map';

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
			Tables\Columns\TextColumn::make('user_lat'),
			Tables\Columns\TextColumn::make('user_long'),
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
			GoToAction::make()
				->zoom(14),
			RadiusAction::make(),
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
