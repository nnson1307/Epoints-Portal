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

class DeliveryProvinceTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_province";
    protected $primaryKey = "province_id";

    //function fillable
    protected $fillable = [
        'province_id',
        'province_id_ghn',
        'province_code_ghn',
        'location_id',
        'name',
        'type',
        'sort_name',
    ];

    /**
     * Lấy danh sách tỉnh/thành phố
     * @return mixed
     */
    public function getAll(){
        return $this
            ->select(
                'province_id',
                'province_id_ghn',
                'province_code_ghn',
                'name',
                'type',
                'sort_name'
            )
            ->orderBy('province_id','DESC')
            ->get();
    }
}