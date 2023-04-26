<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/05/2021
 * Time: 15:58
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class ProvinceTable extends Model
{
    protected $table = "province";

    /**
     * Lấy option tỉnh thành
     *
     * @return mixed
     */
    public function getOptionProvince()
    {
        return $this->select('provinceid', 'name', 'type')->get();
    }
}