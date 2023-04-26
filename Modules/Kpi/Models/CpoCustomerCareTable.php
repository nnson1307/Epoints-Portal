<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/07/2022
 * Time: 14:08
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;

class CpoCustomerCareTable extends Model
{
    protected $table = "cpo_customer_care";
    protected $primaryKey = "customer_care_id";

    /**
     * Lấy lần đầu chăm sóc của KHTN
     *
     * @param $leadCode
     * @return mixed
     */
    public function getFirstCall($leadCode)
    {
        return $this
            ->where("{$this->table}.customer_lead_code", $leadCode)
            ->orderBy("{$this->table}.customer_care_id", "asc")
            ->first();
    }
}