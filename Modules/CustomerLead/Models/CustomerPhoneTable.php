<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/30/2020
 * Time: 3:25 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerPhoneTable extends Model
{
    protected $table = "cpo_customer_phone";
    protected $primaryKey = "customer_phone_id";
    protected $fillable = [
        "customer_phone_id",
        "customer_lead_code",
        "phone",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin sđt của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function getPhone($leadCode)
    {
        return $this
            ->select(
                "customer_phone_id",
                "customer_lead_code",
                "phone"
            )
            ->where("customer_lead_code", $leadCode)
            ->get();
    }

    public function getArrPhone($leadCode)
    {
        return $this
            ->select(
                "phone"
            )
            ->where("customer_lead_code", $leadCode)
            ->get()->toArray();
    }

    /**
     * Xóa sđt kèm theo của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function removePhone($leadCode)
    {
        return $this->where("customer_lead_code", $leadCode)->delete();
    }
}