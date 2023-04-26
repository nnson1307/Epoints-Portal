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

class ExportErrorPeople implements FromView
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

        return view('People::people.error.table-error', [
            'LIST' => $data['LIST']
        ]);

    }
}