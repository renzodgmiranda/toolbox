<?php

namespace App\Filament\Resources\WorkorderResource\Widgets;

use Cheesegrits\FilamentGoogleMaps\Filters\RadiusFilter;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Cheesegrits\FilamentGoogleMaps\Columns\MapColumn;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class VendorMap extends MapTableWidget
{
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
			MapColumn::make('location')
				->extraImgAttributes(
					fn ($record): array => ['title' => $record->user_lat . ',' . $record->user_long]
				)
				->height('150')
				->width('250')
				->type('hybrid')
				->zoom(15),
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
			Tables\Actions\ViewAction::make(),
			Tables\Actions\EditAction::make(),
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
