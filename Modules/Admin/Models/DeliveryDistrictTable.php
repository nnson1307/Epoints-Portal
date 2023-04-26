<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:37 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryDistrictTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_district";
    protected $primaryKey = "district_id";

    //function fillable
    protected $fillable = [
        'district_id',
        'district_id_ghn',
        'district_code_ghn',
        'province_id',
        'name',
        'type',
        'location',
        'postcode',
        'lat',
        'long'
    ];

    /**
     * Lấy danh sách quận/huyện
     * @return mixed
     */
    public function getAll($province_id){
        return $this
            ->select(
                'district_id',
                'district_id_ghn',
                'district_code_ghn',
                'province_id',
                'name',
                'type'
            )
            ->where('province_id',$province_id)
            ->orderBy('district_id','DESC')
            ->get();
    }
}