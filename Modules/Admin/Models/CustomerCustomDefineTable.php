<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/04/2021
 * Time: 10:19
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerCustomDefineTable extends Model
{
    protected $table = "customer_custom_define";

    /**
     * Lấy thông tin define
     *
     * @return mixed
     */
    public function getDefine()
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "key",
                "title_$lang as title",
                "type"
            )
            ->get();
    }

    public function getItemByKey($key)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "key",
                "title_$lang as title",
                "type"
            )
            ->where("key", $key)
            ->first();
    }
}