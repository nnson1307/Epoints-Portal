<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/6/2021
 * Time: 5:47 PM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";

    /**
     * Lấy thông tin cấu hình
     *
     * @param $key
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this
            ->select(
                "config_id",
                "name",
                "key",
                "value"
            )
            ->where("key", $key)
            ->first();
    }
}