<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class RepairImageTable extends Model
{
    protected $table = "repair_images";
    protected $primaryKey = "repair_image_id";

    const NOT_DELETE = 0;

    /**
     * Lấy hình ảnh trước, sau khi bảo dưỡng
     *
     * @param $repairCode
     * @return mixed
     */
    public function getImage($repairCode)
    {
        return $this
            ->select(
                "repair_image_id",
                "repair_code",
                "type",
                "link"
            )
            ->where("repair_code", $repairCode)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Xóa tất cả hình ảnh của phiếu bảo dưỡng
     *
     * @param $repairCode
     * @return mixed
     */
    public function removeImage($repairCode)
    {
        return $this->where("repair_code", $repairCode)->delete();
    }
}