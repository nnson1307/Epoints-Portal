<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/05/2021
 * Time: 13:59
 */

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;

class ExportDealReportStaff implements FromView
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->data;
    }

    public function view(): View
    {
        $data = $this->data;

        return view('customer-lead::report.export-view.view-export-lead-report-staff', [
            'listJourney' => $data['listJourney'],
            'listStaff' => $data['listStaff'],
            'quantity' => $data['quantity'],
            'pipeline_name' => $data['pipeline_name'],
            'created_at' => $data['created_at'],
        ]);
    }
}