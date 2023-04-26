<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 4:11 PM
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy option khách hàng
     *
     * @return mixed
     */
    public function getCustomer()
    {
        return $this
            ->select(
                "customer_id",
                "customer_code",
                "full_name as customer_name",
                "phone1 as phone"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Lấy thông tin khách hàng
     *
     * @param $customerCode
     * @return mixed
     */
    public function getInfo($customerCode)
    {
        return $this
            ->select(
                "customer_id",
                "customer_code",
                "full_name as customer_name",
                "phone1 as phone",
                "address",
                "gender",
                "email as email"
            )
            ->where("customer_code", $customerCode)
            ->first();
    }
}