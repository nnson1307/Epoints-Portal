<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/10/2022
 * Time: 14:31
 */

namespace Modules\Commission\Models;


use Illuminate\Database\Eloquent\Model;

class CommissionObjectMapTable extends Model
{
    protected $table = "commission_object_map";
    protected $primaryKey = "commission_object_map_id";

    /**
     * Lấy đối tượng hàng hoá áp dụng đơn hàng của hoa hồng
     *
     * @param $idCommission
     * @return mixed
     */
    public function getObjectMap($idCommission)
    {
        return $this
            ->select(
                "{$this->table}.commission_object_map_id",
                "{$this->table}.commission_id",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "p.product_code",
                "p.product_child_name",
                "s.service_code",
                "s.service_name",
                "svc.code as service_card_code",
                "svc.name as service_card_name"
            )
            ->leftJoin("product_childs as p", "p.product_child_id", "=", "{$this->table}.object_id")
            ->leftJoin("services as s", "s.service_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards as svc", "svc.service_card_id", "=", "{$this->table}.object_id")
            ->where("{$this->table}.commission_id", $idCommission)
            ->get();
    }
}