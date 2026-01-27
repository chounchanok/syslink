<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class Job_projectExport implements FromView
{
    /**
     * ค่าที่จะส่งไปใน View
     */
    protected $start_date;
    protected $end_date;
    protected $project;

    /**
     * คอนสตรักเตอร์เพื่อรับค่าจาก Controller
     *
     * @param string $start_date
     * @param string $end_date
     * @param string $project_name
     */
    public function __construct($start_date, $end_date,$project)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->project = $project;
    }

    /**
     * กำหนด View สำหรับการ Export
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        return view('backoffice.export.excel.tesla_report', [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'project' => $this->project,
        ]);
    }
}
