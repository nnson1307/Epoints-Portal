<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/07/2022
 * Time: 15:15
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    /**
     * Lấy thông tin KH bằng code
     *
     * @param $customerCode
     * @return mixed
     */
    public function getInfoByCode($customerCode)
    {
        return $this
            ->where("customer_code", $customerCode)
            ->first();
    }
}