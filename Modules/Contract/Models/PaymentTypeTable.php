<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 09/09/2021
 * Time: 17:42
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentTypeTable extends Model
{
    protected $table = "payment_type";
    protected $primaryKey = "payment_type_id";

    /**
     * Lấy thông tin loại thanh toán
     *
     * @param $systemCode
     * @return mixed
     */
    public function getTypeBySystem($systemCode)
    {
        return $this->where("system_code", $systemCode)->first();
    }
}