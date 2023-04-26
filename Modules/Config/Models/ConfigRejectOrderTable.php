<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/05/2022
 * Time: 11:02
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigRejectOrderTable extends Model
{
    protected $table = "config_reject_order";
    protected $primaryKey = "config_reject_order_id";
    protected $fillable = [
        "config_reject_order_id",
        "province_id",
        "created_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy tất cả dữ liệu
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->get();
    }

    /**
     * Thêm tỉnh thành từ chối nhận đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->config_reject_order_id;
    }

    /**
     * Xoá tất cã dữ liệu
     *
     * @return bool|null
     * @throws \Exception
     */
    public function removeAll()
    {
        return $this->truncate();
    }
}