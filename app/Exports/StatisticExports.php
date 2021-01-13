<?php

namespace App\Exports;

use App\Models\Statistic;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StatisticExports implements FromView
{
    private $start;
    private $end;
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        return view('exports.view', [
            'statistic' => Statistic::whereBetWeen('order_date', [$this->start, $this->end])->get()
        ]);
    }
}
