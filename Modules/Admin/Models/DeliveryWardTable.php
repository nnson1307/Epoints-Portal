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

class DeliveryWardTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_ward";
    protected $primaryKey = "ward_id";

    //function fillable
    protected $fillable = [
        'ward_id',
        'name',
        'ward_type',
        'ward_code_ghn',
        'ward_id_ghn',
        'location',
        'district_id',
        'is_deleted'
    ];

    const IS_DELETE = 0;

    /**
     * Lấy danh sách quận/huyện
     * @return mixed
     */
    public function getAll($district_id){
        return $this
            ->select(
                'ward_id',
                'name',
                'ward_type',
                'ward_code_ghn',
                'ward_id_ghn',
                'location',
                'district_id',
                'is_deleted'
            )
            ->where('district_id',$district_id)
            ->where('is_deleted',self::IS_DELETE)
            ->orderBy('ward_id','DESC')
            ->get();
    }
}