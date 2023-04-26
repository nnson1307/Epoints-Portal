<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2021
 * Time: 16:48
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PaymentUnitTable extends Model
{
    protected $table = "payment_units";
    protected $primaryKey = "payment_unit_id";
    protected $fillable = [
        "payment_unit_id",
        "name",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option đơn vị thanh toán
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "payment_unit_id",
                "name"
            )
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Thêm đơn vị thanh toán
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->payment_unit_id;
    }

    /**
     * Chỉnh sửa đơn vị thanh toán
     *
     * @param array $data
     * @param $paymentUnitId
     * @return mixed
     */
    public function edit(array $data, $paymentUnitId)
    {
        return $this->where("payment_unit_id", $paymentUnitId)->update($data);
    }
    public function getInfo($id)
    {
        return $this
            ->select(
                "payment_unit_id",
                "name"
            )
            ->where("payment_unit_id", $id)
            ->first();
    }
}