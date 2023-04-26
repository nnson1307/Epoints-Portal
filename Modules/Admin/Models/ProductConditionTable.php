<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/17/2018
 * Time: 1:26 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductConditionTable extends Model
{
    protected $table = 'product_condition';
    protected $primaryKey = 'product_condition_id';

    const IS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_condition_id', 'key', 'product_condition_name', 'type', 'is_active', 'created_at','created_by','updated_at','updated_by'
    ];


//    Lấy danh sách điều kiện
    public function getListCondition(){
        return $this
            ->select(
                'product_condition_id',
                'key',
                'product_condition_name',
                'type'
            )
            ->where('is_active',self::IS_ACTIVE)
            ->get();
    }
}
//