<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Total Amount per Month';

    public function getDescription(): ?string
    {
        return 'This chart shows the total amount of transactions per month.';
    }

    protected function getData(): array
    {
        // Query untuk mendapatkan total_amount per bulan dari transaksi yang is_paid = true
        $results = DB::table('booking_transactions')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_amount) as total_amount'))
            ->where('is_paid', true)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Menyiapkan data untuk chart
        $data = array_fill(0, 12, 0); // Inisialisasi array dengan 12 elemen untuk setiap bulan
        foreach ($results as $row) {
            $data[$row->month - 1] = $row->total_amount; // Mengisi data berdasarkan bulan
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Amount per Month',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
