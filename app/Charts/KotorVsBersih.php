<?php

namespace App\Charts;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Transaksi;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class KotorVsBersih
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        $start_date = Carbon::now()->subDay(7);
        $end_date = Carbon::now();
        $range = CarbonPeriod::create($start_date, $end_date)->toArray();
        $bersih = [];
        $kotor = [];
        $format = [];

        $customer = request('customer');

        foreach($range as $date)
        {
            $tanggal = $date->format('Y-m-d');

            $bersih[] = intval(Transaksi::query()
                ->where(Transaksi::field_report(), $tanggal)
                ->when($customer, function($query) use ($customer){
                    return $query->where(Transaksi::field_customer_code(), $customer);
                })->sum(Transaksi::field_bersih()));

            $kotor[] = Transaksi::query()
                ->where(Transaksi::field_tanggal(), $tanggal)
                ->where(Transaksi::field_status(), TransactionType::KOTOR)
                ->when($customer, function($query) use ($customer){
                    return $query->where(Transaksi::field_customer_code(), $customer);
                })
                ->sum(Transaksi::field_scan());

            $format[] = $date->format('d M');
        }

        return $this->chart->barChart()
            ->setTitle('Perbandingan Bersih vs Kotor')
            ->setSubtitle('Durasi 7 hari kebelakang')
            ->addData('Bersih', $bersih)
            ->addData('Kotor', $kotor)
            ->setXAxis($format);
    }
}
