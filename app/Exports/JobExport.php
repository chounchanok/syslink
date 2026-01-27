<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JobExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // protected $month_job;
    protected $start_date;
    protected $end_date;
    public function __construct($start_date,$end_date)
    {
        // $this->month_job = $month_job;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    public function view(): View
    {
        return view('backoffice.export.excel.team_report', [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }

}
