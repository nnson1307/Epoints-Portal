<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;

class ExportSalaryFile implements FromView
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

        return view('Salary::salary.export', [
            'list' => $data,
        ]);
    }
}
