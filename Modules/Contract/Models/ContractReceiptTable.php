<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/09/2021
 * Time: 14:17
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractReceiptTable extends Model
{
    use ListTableTrait;
    protected $table = "contract_receipt";
    protected $primaryKey = "contract_receipt_id";
    protected $fillable = [
        "contract_receipt_id",
        "contract_id",
        "content",
        "collection_date",
        "collection_by",
        "prepayment",
        "amount_remain",
        "total_amount_receipt",
        "invoice_date",
        "invoice_no",
        "receipt_code",
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
     * Danh sách đợt thu
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $lang = Config::get('app.locale');

        $ds = $this
            ->select(
                "{$this->table}.contract_receipt_id",
                "{$this->table}.contract_id",
                "{$this->table}.content",
                "{$this->table}.collection_date",
                "{$this->table}.total_amount_receipt",
                "{$this->table}.invoice_date",
                "{$this->table}.invoice_no",
                "{$this->table}.note",
                "sf.full_name as update_by_name",
                "sf2.full_name as collection_by_name",
                "{$this->table}.updated_at"
            )
            ->leftJoin("staffs as sf", "sf.staff_id", "=", "{$this->table}.updated_by")
            ->join("staffs as sf2", "sf2.staff_id", "=", "{$this->table}.collection_by")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        //Filter theo HĐ
        if (isset($filter['contract_id'])) {
            $ds->where("{$this->table}.contract_id", $filter['contract_id']);
            unset($filter['contract_id']);
        }

        return $ds;
    }

    /**
     * Tạo đợt thu
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_receipt_id;
    }

    /**
     * Chỉnh sửa đợt thu
     *
     * @param array $data
     * @param $receiptId
     * @return mixed
     */
    public function edit(array $data, $receiptId)
    {
        return $this->where("contract_receipt_id", $receiptId)->update($data);
    }

    /**
     * Lấy tổng tiền đã thu của hợp đồng
     *
     * @param $contractId
     * @return mixed
     */
    public function getAmountReceipt($contractId)
    {
        return $this
            ->select(
                DB::raw("SUM(rc.amount_paid) as total_amount_paid")
            )
            ->join("receipts as rc", "rc.receipt_code", "=", "{$this->table}.receipt_code")
            ->where("rc.receipt_type_code", "RTC_CONTRACT")
            ->where("rc.object_accounting_id", $contractId)
            ->where("rc.is_deleted", self::NOT_DELETED)
            ->groupBy("rc.object_accounting_id")
            ->first();
    }

    /**
     * Lấy thông tin đợt thu
     *
     * @param $contractReceiptId
     * @return mixed
     */
    public function getInfo($contractReceiptId)
    {
        return $this
            ->select(
                "contract_receipt_id",
                "contract_id",
                "content",
                "collection_date",
                "collection_by",
                "prepayment",
                "amount_remain",
                "total_amount_receipt",
                "invoice_date",
                "invoice_no",
                "receipt_code",
                "note",
                "reason"
            )
            ->where("contract_receipt_id", $contractReceiptId)
            ->first();
    }

    public function getReportReceiptDetail($filter)
    {
        $ds = $this->select(
            DB::raw("sum({$this->table}.total_amount_receipt) as total_receipt"),
            DB::raw("DATE_FORMAT({$this->table}.collection_date, '%d/%m/%Y') as created_group")
        )
            ->join("contracts", "contracts.contract_id", "{$this->table}.contract_id")
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->where("contract_category_status.default_system", "<>",  'cancel')
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);
        if(isset($filter['contract_category_id']) && $filter['contract_category_id'] != ''){
            $ds->where("contracts.contract_category_id", $filter['contract_category_id']);
        }
        $ds->groupBy(DB::raw("DATE_FORMAT({$this->table}.collection_date, '%d/%m/%Y')"));
        return $ds->get()->toArray();
    }
}