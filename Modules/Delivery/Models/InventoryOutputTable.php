<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/6/2021
 * Time: 6:43 PM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class InventoryOutputTable extends Model
{
    protected $table = "inventory_outputs";
    protected $primaryKey = "inventory_output_id";
    protected $fillable = [
        "inventory_output_id",
        "warehouse_id",
        "po_code",
        "created_by",
        "updated_by",
        "approved_by",
        "created_at",
        "updated_at",
        "approved_at",
        "status",
        "type",
        "object_id",
        "note"
    ];

    /**
     * Tạo phiếu xuất kho
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->inventory_output_id;
    }

    /**
     * Chỉnh sửa phiếu xuất kho
     *
     * @param array $data
     * @param $idOutput
     * @return mixed
     */
    public function edit(array $data, $idOutput)
    {
        return $this->where("inventory_output_id", $idOutput)->update($data);
    }
}