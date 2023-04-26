<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 17:05
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractSpendTable extends Model
{
    use ListTableTrait;
    protected $table = "contract_spend";
    protected $primaryKey = "contract_spend_id";
    protected $fillable = [
        "contract_spend_id",
        "contract_id",
        "content",
        "spend_date",
        "spend_by",
        "prepayment",
        "amount_remain",
        "amount_spend",
        "invoice_date",
        "invoice_no",
        "payment_method_id",
        "payment_code",
        "note",
        "reason",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách đợt chi
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $lang = Config::get('app.locale');

        $ds = $this
            ->select(
                "{$this->table}.contract_spend_id",
                "{$this->table}.contract_id",
                "{$this->table}.content",
                "{$this->table}.spend_date",
                "{$this->table}.amount_spend",
                "{$this->table}.invoice_date",
                "{$this->table}.invoice_no",
                "{$this->table}.note",
                "sf.full_name as update_by_name",
                "sf2.full_name as spend_by_name",
                "payment_method.payment_method_name_$lang as payment_method_name",
                "{$this->table}.updated_at"
            )
            ->leftJoin("staffs as sf", "sf.staff_id", "=", "{$this->table}.updated_by")
            ->join("staffs as sf2", "sf2.staff_id", "=", "{$this->table}.spend_by")
            ->join("payment_method", "payment_method.payment_method_id", "=", "{$this->table}.payment_method_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        //Filter theo HĐ
        if (isset($filter['contract_id'])) {
            $ds->where("{$this->table}.contract_id", $filter['contract_id']);
            unset($filter['contract_id']);
        }

        return $ds;
    }

    /**
     * Tạo đợt chi
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_spend_id;
    }

    /**
     * Chỉnh sửa đợt chi
     *
     * @param array $data
     * @param $spendId
     * @return mixed
     */
    public function edit(array $data, $spendId)
    {
        return $this->where("contract_spend_id", $spendId)->update($data);
    }

    /**
     * Chỉnh sửa đợt chi bằng hợp đồng
     *
     * @param array $data
     * @param $contractId
     * @return mixed
     */
    public function editByContract(array $data, $contractId)
    {
        return $this->where("contract_id", $contractId)->update($data);
    }

    /**
     * Lấy tổng tiền đã chi của hợp đồng
     *
     * @param $contractId
     * @return mixed
     */
    public function getAmountSpend($contractId)
    {
        return $this
            ->select(
                DB::raw("SUM(pm.total_amount) as total_amount")
            )
            ->join("payments as pm", "pm.payment_code", "=", "{$this->table}.payment_code")
            ->where("pm.object_accounting_type_code", "OAT_CONTRACT")
            ->where("pm.accounting_id", $contractId)
            ->where("pm.is_delete", self::NOT_DELETED)
            ->groupBy("pm.accounting_id")
            ->first();
    }

    /**
     * Lấy thông tin đợt chi
     *
     * @param $contractSpendId
     * @return mixed
     */
    public function getInfo($contractSpendId)
    {
        return $this
            ->select(
                "contract_spend_id",
                "contract_id",
                "content",
                "spend_date",
                "spend_by",
                "prepayment",
                "amount_remain",
                "amount_spend",
                "invoice_date",
                "invoice_no",
                "payment_method_id",
                "payment_code",
                "note",
                "reason"
            )
            ->where("contract_spend_id", $contractSpendId)
            ->first();
    }

    public function getReportSpendDetail($filter)
    {
        $ds = $this->select(
            DB::raw("sum({$this->table}.amount_spend) as total_spend"),
            DB::raw("DATE_FORMAT({$this->table}.spend_date, '%d/%m/%Y') as created_group")
        )
            ->join("contracts", "contracts.contract_id", "{$this->table}.contract_id")
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->where("contract_category_status.default_system", "<>",  'cancel')
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);
        if(isset($filter['contract_category_id']) && $filter['contract_category_id'] != ''){
            $ds->where("contracts.contract_category_id", $filter['contract_category_id']);
        }
        $ds->groupBy(DB::raw("DATE_FORMAT({$this->table}.spend_date, '%d/%m/%Y')"));
        return $ds->get()->toArray();
    }
}