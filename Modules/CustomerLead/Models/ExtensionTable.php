<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/07/2021
 * Time: 16:06
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class ExtensionTable extends Model
{
    protected $table = "oc_extensions";
    protected $primaryKey = "extension_id";

    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy thông tin extension của nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getInfoByStaff($staffId)
    {
        return $this
            ->where("staff_id", $staffId)
            ->where("status", self::IS_ACTIVED)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }
}