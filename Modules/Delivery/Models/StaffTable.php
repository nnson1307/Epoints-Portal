<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 7:20 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const NOT_DELETED = 0;
    CONST IN_ACTIVE = 1;

    /**
     * Option chi nhánh
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "staff_id", 
                "full_name", 
                "address", 
                "phone1"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->where("is_actived", self::IN_ACTIVE)
            ->get();
    }
}