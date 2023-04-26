<?php

/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:11 AM
 * @author nhandt
 */


namespace Modules\ConfigDisplay\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigCategoryDetailTable extends Model
{

    protected $table = "config_category_destination";
    protected $fillable = [
        "key_destination",
        "name",
        "description",
    ];

    /**
     * Lấy tất cả danh mục cấu hình hiển thị 
     * @return mixed
     */

    public function getAll()
    {
        return $this->get();
    }
}
