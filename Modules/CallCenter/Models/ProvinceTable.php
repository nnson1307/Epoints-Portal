<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 11:56 AM
 */

namespace Modules\CallCenter\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class ProvinceTable extends Model
{
    protected $table="province";


    public function getOptionProvince()
    {
        return $this->select('provinceid','name','type')->get();
    }

    /**
     * Lấy thông tin tỉnh/thành bằng tên
     *
     * @param $provinceName
     * @return mixed
     */
    public function getProvinceByName($provinceName)
    {
        return $this->whereRaw('TRIM(LOWER(`name`)) LIKE ? ',[trim(strtolower($provinceName)).'%'])->first();
    }
}