<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 12:45 PM
 */

namespace Modules\Booking\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class DistrictTable extends Model
{
    protected $table="district";

    protected $fillable=['districtid','name','type','location','provinceid'];


    public function getOptionDistrict($id)
    {
        $a= $this->select('districtid','name','location','type')->where('provinceid',$id)->get();
        return $a;

    }



}