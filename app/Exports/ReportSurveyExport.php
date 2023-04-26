<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
class ReportSurveyExport implements FromView, WithTitle
{

    public $dataExport;
    public function __construct(array $dataExport)
    {
        $this->dataExport = $dataExport;
    }

    public function array(): array
    {

        return $this->dataExport;
    }

    public function title(): string
    {
        return 'Báo cáo khảo sát';
    }

    public function view(): View
    {
        $data = $this->dataExport;
        return view('survey::survey.report.export', $data);
    }

   
}
