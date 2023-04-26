<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/08/2021
 * Time: 14:32
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractExpectedRevenueTable extends Model
{
    use ListTableTrait;
    protected $table = "contract_expected_revenue";
    protected $primaryKey = "contract_expected_revenue_id";
    protected $fillable = [
        "contract_expected_revenue_id",
        "contract_id",
        "type",
        "title",
        "contract_category_remind_id",
        "send_type",
        "send_value",
        "send_value_child",
        "note",
        "amount",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách thu - chi
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.contract_expected_revenue_id",
                "{$this->table}.type",
                "{$this->table}.title",
                "{$this->table}.note",
                "staffs.full_name as staff_name",
                "{$this->table}.updated_at",
                "rm.title as title_remind",
                "{$this->table}.amount"
            )
            ->leftJoin("staffs", "staffs.staff_id", '=', "{$this->table}.created_by")
            ->leftJoin("contract_category_remind as rm", "rm.contract_category_remind_id", "=","{$this->table}.contract_category_remind_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.contract_expected_revenue_id", "desc");

        //Filter theo HĐ
        if (isset($filter['contract_id'])) {
            $ds->where("{$this->table}.contract_id", $filter['contract_id']);
            unset($filter['contract_id']);
        }
        //Filter theo loại thu or chi
        if (isset($filter['type'])) {
            $ds->where("{$this->table}.type", $filter['type']);
            unset($filter['type']);
        }

        return $ds;
    }

    /**
     * Thêm đợt dự kiến thu - chi
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_expected_revenue_id;
    }

    /**
     * Chỉnh sửa dự kiến thu - chi
     *
     * @param array $data
     * @param $revenueId
     * @return mixed
     */
    public function edit(array $data, $revenueId)
    {
        return $this->where("contract_expected_revenue_id", $revenueId)->update($data);
    }

    /**
     * Lấy thông tin dự kiến thu - chi
     *
     * @param $revenueId
     * @return mixed
     */
    public function getInfo($revenueId)
    {
        return $this
            ->select(
                "contract_expected_revenue_id",
                "contract_id",
                "type",
                "title",
                "contract_category_remind_id",
                "send_type",
                "send_value",
                "send_value_child",
                "note",
                "amount"
            )
            ->where("contract_expected_revenue_id", $revenueId)
            ->first();
    }

    /**
     * Đếm số lần lần dự kiến thu - chi của HĐ
     *
     * @param $contractId
     * @param $type
     * @return mixed
     */
    public function getNumberCreate($contractId, $type)
    {
        return $this
            ->where("contract_id", $contractId)
            ->where("type", $type)
            ->where("is_deleted", self::NOT_DELETED)
            ->get()
            ->count();
    }

    public function getReportExpectedRevenueDetail($filter)
    {
        $ds = $this->select(
            DB::raw("SUM(IF({$this->table}.type = 'receipt', {$this->table}.amount, 0)) as total_expected_receipt"),
            DB::raw("SUM(IF({$this->table}.type = 'spend', {$this->table}.amount, 0)) as total_expected_spend"),
            DB::raw("DATE_FORMAT(contract_expected_revenue_log.date_send, '%d/%m/%Y') as created_group")
        )
            ->join("contracts", "contracts.contract_id", "{$this->table}.contract_id")
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->where("contract_category_status.default_system", "<>",  'cancel')
            ->join("contract_expected_revenue_log", "contract_expected_revenue_log.contract_expected_revenue_id",
                "{$this->table}.contract_expected_revenue_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);
        if(isset($filter['contract_category_id']) && $filter['contract_category_id'] != ''){
            $ds->where("contracts.contract_category_id", $filter['contract_category_id']);
        }
        $ds->groupBy(DB::raw("DATE_FORMAT(contract_expected_revenue_log.date_send, '%d/%m/%Y')"));

        return $ds->get()->toArray();
    }

    /**
     * Lấy dự kiến thu - chi của HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function getExpectedRevenueByContract($contractId)
    {
        return $this
            ->select(
                "contract_expected_revenue_id",
                "contract_id",
                "type",
                "title",
                "contract_category_remind_id",
                "send_type",
                "send_value",
                "send_value_child",
                "note",
                "amount"
            )
            ->where("contract_id", $contractId)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}