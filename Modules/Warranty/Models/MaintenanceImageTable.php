<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 10:34 AM
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class MaintenanceImageTable extends Model
{
    protected $table = "maintenance_images";
    protected $primaryKey = "maintenance_image_id";

    const NOT_DELETE = 0;

    /**
     * Lấy hình ảnh trước, sau khi bảo trì
     *
     * @param $maintenanceCode
     * @return mixed
     */
    public function getImage($maintenanceCode)
    {
        return $this
            ->select(
                "maintenance_image_id",
                "maintenance_code",
                "type",
                "link"
            )
            ->where("maintenance_code", $maintenanceCode)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Xóa tất cả hình ảnh của phiếu bảo trì
     *
     * @param $maintenanceCode
     * @return mixed
     */
    public function removeImage($maintenanceCode)
    {
        return $this->where("maintenance_code", $maintenanceCode)->delete();
    }
}