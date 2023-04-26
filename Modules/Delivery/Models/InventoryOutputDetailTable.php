<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/7/2021
 * Time: 9:48 AM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class InventoryOutputDetailTable extends Model
{
    protected $table = "inventory_output_details";
    protected $primaryKey = "inventory_output_detail_id";

    /**
     * Tạo chi tiết phiếu xuất kho
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->inventory_output_detail_id;
    }
}