<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/05/2022
 * Time: 11:03
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigRejectOrderDetailTable extends Model
{
    protected $table = "config_reject_order_detail";
    protected $primaryKey = "config_reject_order_detail_id";
    protected $fillable = [
        'config_reject_order_detail_id',
        "config_reject_order_id",
        "province_id",
        "district_id",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy data chi tiết từ chối đơn hàng
     *
     * @param $rejectOrderId
     * @return mixed
     */
    public function getDetail($rejectOrderId)
    {
        return $this->where("config_reject_order_id", $rejectOrderId)->get();
    }

    /**
     * Xoá tất cã dữ liệu chi tiết
     *
     * @return bool|null
     * @throws \Exception
     */
    public function removeAllDetail()
    {
        return $this->truncate();
    }
}