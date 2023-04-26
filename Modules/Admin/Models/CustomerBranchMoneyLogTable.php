<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 01/12/2021
 * Time: 17:00
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerBranchMoneyLogTable extends Model
{
    protected $table = "customer_branch_money_log";
    protected $primaryKey = "customer_branch_money_log_id";
    protected $fillable = [
        "customer_branch_money_log_id",
        "customer_id",
        "branch_id",
        "source",
        "type",
        "money",
        "screen",
        "screen_object_code",
        "created_at",
        "updated_at"
    ];

    const MEMBER_MONEY = "member_money";
    const COMMISSION = "commission";

    /**
     * Thêm log + - tiền chi nhánh
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy tổng tiền thành viên
     *
     * @param $customerId
     * @param $branchId
     * @param $type
     * @return mixed
     */
    public function getTotalMoney($customerId, $branchId, $type)
    {
        $ds = $this
            ->select(
                DB::raw('sum(money) as total')
            )
            ->where("source", self::MEMBER_MONEY)
            ->where("customer_id", $customerId)
            ->where("type", $type);

        if ($branchId != null) {
            $ds->where("branch_id", $branchId);
        }
        return $ds->first();
    }

    /**
     * Lấy tổng tiền hoa hồng của khách hàng
     *
     * @param $customerId
     * @param $branchId
     * @param $type
     * @return mixed
     */
    public function getTotalCommission($customerId, $branchId, $type)
    {
        $ds = $this
            ->select(
                DB::raw('sum(money) as total')
            )
            ->where("source", self::COMMISSION)
            ->where("customer_id", $customerId)
            ->where("type", $type);

        if ($branchId != null) {
            $ds->where("branch_id", $branchId);
        }

        return $ds->first();
    }
}