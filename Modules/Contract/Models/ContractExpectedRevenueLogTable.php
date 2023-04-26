<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/08/2021
 * Time: 14:40
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractExpectedRevenueLogTable extends Model
{
    protected $table = "contract_expected_revenue_log";
    protected $primaryKey = "contract_expected_revenue_log_id";

    /**
     * Lấy log thu - chi
     *
     * @param $revenueId
     * @return mixed
     */
    public function getLogByRevenue($revenueId)
    {
        return $this->where("contract_expected_revenue_id", $revenueId)->get();
    }

    /**
     * Xoá log thu - chi
     *
     * @param $revenueId
     * @return mixed
     */
    public function removeLogByRevenue($revenueId)
    {
        return $this->where("contract_expected_revenue_id", $revenueId)->delete();
    }
}