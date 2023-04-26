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

class ExportInventory implements FromView
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

        return view('report::product-inventory.view-export', [
            'LIST' => $data['list'],
            'listWarehouse' => $data['listWarehouse'],
            'warehouse_name' => $data['warehouse_name'],
            'created_at' => $data['created_at'],
        ]);
    }
}