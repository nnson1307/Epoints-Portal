<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 04/05/2022
 * Time: 15:51
 */

namespace Modules\People\Models;


use Illuminate\Database\Eloquent\Model;

class ProvinceTable extends Model
{
    protected $table = "province";
    protected $primaryKey = "provinceid";

    /**
     * Lấy thông tin tỉnh thành bằng tên
     *
     * @param $name
     * @return mixed
     */
    public function getProvinceByName($name)
    {
        return $this->where("name", $name)->first();
    }
}