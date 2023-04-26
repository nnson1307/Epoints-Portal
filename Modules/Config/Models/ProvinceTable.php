<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/05/2022
 * Time: 11:59
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class ProvinceTable extends Model
{
    protected $table="province";


    /**
     * Lấy option tỉnh thành
     *
     * @return mixed
     */
    public function getOptionProvince()
    {
        return $this->select('provinceid','name')->get();
    }
}