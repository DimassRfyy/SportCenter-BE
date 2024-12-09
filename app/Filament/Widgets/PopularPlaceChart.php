<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularPlaceChart extends ChartWidget
{
    protected static ?string $heading = 'Popular Places';
    protected static ?string $maxHeight = '28vh';

    public function getDescription(): ?string
{
    return 'This chart shows the 10 most popular places.';
}

    protected function getData(): array
    {
        // Query untuk mendapatkan 10 tempat yang paling banyak dipesan
        $results = DB::table('booking_transactions')
            ->select('place_id', DB::raw('COUNT(*) as bookings'))
            ->where('is_paid', true)
            ->groupBy('place_id')
            ->orderBy('bookings', 'DESC')
            ->limit(10)
            ->get();

        // Menyiapkan data untuk chart
        $labels = [];
        $data = [];
        $backgroundColor = [];
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', 
            '#FF9F40', '#FFCD56', '#4BC0C0', '#36A2EB', '#FF6384'
        ];

        foreach ($results as $index => $row) {
            // Mengambil nama tempat berdasarkan place_id
            $placeName = DB::table('Places')->where('id', $row->place_id)->value('name');
            $labels[] = $placeName;
            $data[] = $row->bookings;
            $backgroundColor[] = $colors[$index % count($colors)];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Most Booked Places',
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
