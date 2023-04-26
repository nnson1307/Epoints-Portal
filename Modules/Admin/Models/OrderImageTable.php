<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/04/2021
 * Time: 15:28
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class OrderImageTable extends Model
{
    protected $table = "order_images";
    protected $primaryKey = "order_image_id";
    protected $fillable = [
        "order_image_id",
        "order_code",
        "type",
        "link",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy hình ảnh sau khi sử dụng
     *
     * @param $orderCode
     * @param $type
     * @return mixed
     */
    public function getOrderImage($orderCode, $type = null)
    {
        $ds = $this
            ->select(
                "order_image_id",
                "type",
                "link"
            )
            ->where("order_code", $orderCode);

        if($type != null) {
            $ds->where("type", $type);
        }

        return $ds->get();
    }

    /**
     * Xoá hình ảnh sau khi sử dụng
     *
     * @param $orderCode
     * @param $type
     * @return mixed
     */
    public function removeOrderImage($orderCode, $type)
    {
        return $this
            ->where("order_code", $orderCode)
            ->where("type", $type)
            ->delete();
    }

}