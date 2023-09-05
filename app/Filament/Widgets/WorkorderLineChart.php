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
    
    protected static ?string $heading = 'WO Trends';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'month' => 'Monthly',
            'yearly' => 'Yearly'
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $selectRaw = [];
        $groupByRaw = [];
        $orderByRaw = "";
        $labelFormat = "";

        switch ($activeFilter) {
            case 'daily':
                $selectRaw = [FacadesDB::raw('DATE(created_at) as date'), FacadesDB::raw('count(*) as count')];
                $groupByRaw = [FacadesDB::raw('DATE(created_at)')];
                $orderByRaw = "DATE(created_at) ASC";
                $labelFormat = 'd M Y';
                break;

            case 'weekly':
                $selectRaw = [FacadesDB::raw('YEARWEEK(created_at, 1) as week'), FacadesDB::raw('count(*) as count')];
                $groupByRaw = [FacadesDB::raw('YEARWEEK(created_at, 1)')];
                $orderByRaw = "YEARWEEK(created_at, 1) ASC";
                $labelFormat = 'W Y';
                break;

            case 'yearly':
                $selectRaw = [FacadesDB::raw('YEAR(created_at) as year'), FacadesDB::raw('count(*) as count')];
                $groupByRaw = [FacadesDB::raw('YEAR(created_at)')];
                $orderByRaw = "YEAR(created_at) ASC";
                $labelFormat = 'Y';
                break;

            case 'month':
            default:
                $selectRaw = [
                    FacadesDB::raw('YEAR(created_at) as year'),
                    FacadesDB::raw('MONTH(created_at) as month'),
                    FacadesDB::raw('count(*) as count')
                ];
                $groupByRaw = [FacadesDB::raw('YEAR(created_at)'), FacadesDB::raw('MONTH(created_at)')];
                $orderByRaw = "YEAR(created_at) ASC, MONTH(created_at) ASC";
                $labelFormat = 'F Y';
                break;
        }

        $workorderDataQuery = Workorder::select(...$selectRaw)
            ->groupBy(...$groupByRaw)
            ->orderByRaw($orderByRaw);

        $workorderData = $workorderDataQuery->get();

        $labels = [];
        $data = [];

        foreach ($workorderData as $entry) {
            switch ($activeFilter) {
                case 'daily':
                    $labels[] = Carbon::parse($entry->date)->format($labelFormat);
                    break;
                case 'weekly':
                    $labels[] = 'W' . substr($entry->week, -2) . ' ' . substr($entry->week, 0, 4); // gives Week Year format
                    break;
                case 'yearly':
                    $labels[] = $entry->year;
                    break;
                case 'month':
                default:
                    $labels[] = Carbon::create($entry->year, $entry->month, 1)->format($labelFormat);
                    break;
            }
            $data[] = (int)$entry->count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Number of Workorders (' . ucfirst($activeFilter) . ')',
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