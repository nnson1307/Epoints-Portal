<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/08/2021
 * Time: 14:39
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractExpectedRevenueFileTable extends Model
{
    protected $table = "contract_expected_revenue_files";
    protected $primaryKey = "contract_expected_revenue_file_id";

    /**
     * Lấy thông tin file thu - chi
     *
     * @param $revenueId
     * @return mixed
     */
    public function getFileByRevenue($revenueId)
    {
        return $this->where("contract_expected_revenue_id", $revenueId)->get();
    }

    /**
     * Xoá file thu - chi
     *
     * @param $revenueId
     * @return mixed
     */
    public function removeFileByRevenue($revenueId)
    {
        return $this->where("contract_expected_revenue_id", $revenueId)->delete();
    }
}