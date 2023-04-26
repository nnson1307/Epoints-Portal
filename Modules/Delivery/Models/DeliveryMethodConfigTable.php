<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 2:49 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DeliveryMethodConfigTable extends Model
{
    protected $table = 'delivery_method_config';
    protected $primaryKey = 'delivery_method_config_id';

    const IS_ACTIVE = 1;

    /**
     * lấy tất cả phương thức vận chuyển
     */
    public function getAll(){
        return $this
            ->select(
                'delivery_method_config_id',
                'delivery_method_name',
                'delivery_method_code'
            )
            ->where('is_active',self::IS_ACTIVE)
            ->get();
    }
}