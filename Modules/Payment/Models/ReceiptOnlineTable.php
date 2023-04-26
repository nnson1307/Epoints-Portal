<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/12/2021
 * Time: 11:41
 */

namespace Modules\Payment\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ReceiptOnlineTable extends Model
{
    use ListTableTrait;
    protected $table = "receipt_online";
    protected $primaryKey = "receipt_online_id";
    protected $fillable = [
        "receipt_online_id",
        "receipt_id",
        "object_type",
        "object_id",
        "object_code",
        "payment_method_code",
        "amount_paid",
        "payment_transaction_code",
        "payment_transaction_uuid",
        "payment_time",
        "status",
        "performer_name",
        "performer_phone",
        "type",
        "note",
        "created_at",
        "updated_at"
    ];

    /**
     * Danh sách giao dịch online
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $lang = app()->getLocale();

        $ds = $this
            ->select(
                "{$this->table}.receipt_online_id",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_code",
                "{$this->table}.payment_method_code",
                "payment_method.payment_method_name_$lang as payment_method_name",
                "{$this->table}.type",
                "{$this->table}.payment_time",
                "{$this->table}.status",
                "{$this->table}.amount_paid",
                "{$this->table}.payment_transaction_code",
                "{$this->table}.performer_name",
                "{$this->table}.performer_phone"
            )
            ->join("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->orderBy("{$this->table}.receipt_online_id", "desc");

        //Filter loại thanh toán
        if (isset($filter['object_type']) && $filter['object_type'] != "") {
            $ds->where("{$this->table}.object_type", '=', $filter['object_type']);
        }

        //Tìm theo mã tham chiếu/giao dịch/đơn hàng
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.payment_transaction_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.object_code",'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.performer_name",'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.performer_phone",'like', '%' . $search . '%');
            });
        }

        //Filter hình thức xác nhận thanh toán
        if (isset($filter['type']) && $filter['type'] != "") {
            $ds->where("{$this->table}.type", '=', $filter['type']);
        }

        //Filter trạng thái
        if (isset($filter['status']) && $filter['status'] != "") {
            $ds->where("{$this->table}.status", '=', $filter['status']);
        }

        //Filter ngày thực hiện
        if (isset($filter["payment_time"]) != "") {
            $arr_filter = explode(" - ", $filter["payment_time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.payment_time", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        //Filter chọn phương thức thanh toán
        if (isset($filter['payment_method_code']) && $filter['payment_method_code'] != "") {
            $ds->where("{$this->table}.payment_method_code", $filter['payment_method_code']);

            unset($filter['payment_method_code']);
        }

        return $ds;
    }

    /**
     * Thêm đợt thanh toán online
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->receipt_online_id;
    }

    /**
     * Chỉnh sửa đợt thanh toán online
     *
     * @param array $data
     * @param $receiptOnlineId
     * @return mixed
     */
    public function edit(array $data, $receiptOnlineId)
    {
        return $this->where("receipt_online_id", $receiptOnlineId)->update($data);
    }

    /**
     * Lấy thông tin giao dịch online
     *
     * @param $receiptOnlineId
     * @return mixed
     */
    public function getInfo($receiptOnlineId)
    {
        return $this
            ->select(
                "receipt_online_id",
                "receipt_id",
                "object_type",
                "object_id",
                "object_code",
                "payment_method_code",
                "amount_paid",
                "payment_transaction_code",
                "payment_transaction_uuid",
                "payment_time",
                "status",
                "performer_name",
                "performer_phone",
                "type",
                "note"
            )
            ->where("receipt_online_id", $receiptOnlineId)
            ->first();
    }
}