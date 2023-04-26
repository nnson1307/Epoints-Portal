<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/07/2021
 * Time: 16:24
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class AccountTable extends Model
{
    protected $table = "oc_account";
    protected $primaryKey = "id";

    const IS_ACTIVED = 1;

    /**
     * Lấy thông tin tài khoản
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this
            ->select(
                "user_name",
                "password"
            )
            ->where("is_actived", self::IS_ACTIVED)
            ->first();
    }
}