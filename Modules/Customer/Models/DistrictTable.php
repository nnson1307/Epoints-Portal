<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/05/2021
 * Time: 17:35
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class DistrictTable extends Model
{
    protected $table="district";
    protected $primaryKey = "districtid";
    protected $fillable=[
        'districtid',
        'postcode',
        'name',
        'provinceid',
        'lat',
        'long',
        'type'
    ];

    public $timestamps = false;


    /**
     * Lấy option quận huyện
     *
     * @param $id
     * @return mixed
     */
    public function getOptionDistrict($id)
    {
        return  $this
            ->select(
                'districtid',
                'postcode',
                'name',
                'lat',
                'long',
                'type'
            )
            ->where('provinceid', $id)
            ->get();
    }
}