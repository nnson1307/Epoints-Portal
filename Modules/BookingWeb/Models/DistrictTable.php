<?php


namespace Modules\BookingWeb\Models;


use Illuminate\Database\Eloquent\Model;

class DistrictTable extends Model
{
    protected $table='district';
    protected $primaryKey = 'districtid';
    protected $fillable = [
        'districtid','name','type','location','provinceid'
    ];

    public function getDistrictOption($id_province)
    {

        $id_province = sprintf("%02d", $id_province);
        $get_all= $this->select('districtid','name','type')->where('provinceid',  $id_province )->get();
        $array = array();
        foreach ($get_all as $item)
        {
            $array[$item['districtid']] = $item['type'].' '.$item['name'];
        }
        return $array;
    }
}