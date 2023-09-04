<?php

namespace App\Filament\Widgets;

use App\Models\Workorder;
use DB;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB as FacadesDB;

class WorkorderLineChart extends ChartWidget
{
    protected static ?int $sort = 2;
    
    protected static ?string $maxHeight = '200px';

    protected static ?string $heading = 'Monthly Workorders';

    protected function getData(): array
    {
        // Get the aggregated work orders count by month.
        $workorderData = Workorder::select(FacadesDB::raw('YEAR(created_at) as year'), FacadesDB::raw('MONTH(created_at) as month'), FacadesDB::raw('count(*) as count'))
            ->groupBy(FacadesDB::raw('YEAR(created_at)'), FacadesDB::raw('MONTH(created_at)'))
            ->orderBy(FacadesDB::raw('YEAR(created_at)'), 'ASC')
            ->orderBy(FacadesDB::raw('MONTH(created_at)'), 'ASC')
            ->get();

        $labels = [];
        $data = [];

        // Format the data to fit the chart's requirements.
        foreach ($workorderData as $entry) {
            $labels[] = Carbon::create($entry->year, $entry->month, 1)->format('F Y'); // Gives "Month Year" format.
            $data[] = (int)$entry->count; // Cast to ensure whole numbers.
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Number of Workorders (Monthly)',
                    'data' => $data,
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}