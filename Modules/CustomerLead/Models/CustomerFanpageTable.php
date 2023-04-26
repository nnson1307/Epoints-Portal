<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/30/2020
 * Time: 3:26 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerFanpageTable extends Model
{
    protected $table = "cpo_customer_fanpage";
    protected $primaryKey = "customer_fanpage_id";
    protected $fillable = [
        "customer_fanpage_id",
        "customer_lead_code",
        "fanpage",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin fanpage của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function getFanpage($leadCode)
    {
        return $this
            ->select(
                "customer_fanpage_id",
                "customer_lead_code",
                "fanpage"
            )
            ->where("customer_lead_code", $leadCode)
            ->get();
    }
    public function getArrayFanpage($leadCode)
    {
        return $this
            ->select(
                "fanpage"
            )
            ->where("customer_lead_code", $leadCode)
            ->get()->toArray();
    }

    /**
     * Xóa fanpage kèm theo của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function removeFanpage($leadCode)
    {
        return $this->where("customer_lead_code", $leadCode)->delete();
    }
}