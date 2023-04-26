<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/04/2021
 * Time: 18:19
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerAccountTable extends Model
{
    protected $table = "customer_account";
    protected $primaryKey = "customer_account_id";

    /**
     * Xoá account của khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function removeAccount($customerId)
    {
        return $this->where("customer_id", $customerId)->delete();
    }
}