<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/05/2022
 * Time: 14:58
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class DistrictTable extends Model
{
    protected $table="district";
    protected $primaryKey = "districtid";

    public function getOptionDistrict($id)
    {
        return  $this->select('districtid', 'postcode','name','lat', 'long','type')->where('provinceid', $id)->get();
    }
}