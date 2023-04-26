<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 11:56 AM
 */

namespace Modules\ZNS\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class DistrictTable extends Model
{
    protected $table="district";


    public function getOption($province_id = "")
    {
        if($province_id){
            $oSelect = $this->select('districtid','name')->where("provinceid",$province_id)->get();
            return ($oSelect->pluck("name", "districtid")->toArray());
        }
        $oSelect = $this->select('districtid','name')->get();
        return ($oSelect->pluck("name", "districtid")->toArray());
    }

}