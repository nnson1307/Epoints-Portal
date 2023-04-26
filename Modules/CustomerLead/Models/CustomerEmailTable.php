<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/30/2020
 * Time: 3:25 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerEmailTable extends Model
{
    protected $table = "cpo_customer_email";
    protected $primaryKey = "customer_email_id";
    protected $fillable = [
        "customer_email_id",
        "customer_lead_code",
        "email",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin email của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function getEmail($leadCode)
    {
        return $this
            ->select(
                "customer_email_id",
                "customer_lead_code",
                "email"
            )
            ->where("customer_lead_code", $leadCode)
            ->get();
    }
    public function getArrayEmail($leadCode)
    {
        return $this
            ->select(
                "email"
            )
            ->where("customer_lead_code", $leadCode)
            ->get()->toArray();
    }

    /**
     * Xóa email kèm theo của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function removeEmail($leadCode)
    {
        return $this->where("customer_lead_code", $leadCode)->delete();
    }
}