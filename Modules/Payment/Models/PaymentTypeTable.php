<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:00 AM
 */

namespace Modules\Payment\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use MyCore\Models\Traits\ListTableTrait;

class PaymentTypeTable extends Model
{
    use ListTableTrait;
    protected $table = "payment_type";
    protected $primaryKey = "payment_type_id";

    protected $fillable = [
        'payment_type_id ', 'payment_type_name_vi', 'payment_type_name_en','is_active', 'created_by',
        'updated_by', 'created_at','updated_at'
    ];
    const IS_ACTIVE = 1;

    /**
     * Lấy các loại thanh toán để tạo sự lựa chọn (select option)
     *
     * @return mixed
     */
    public function getPaymentTypeOption()
    {
        $lang = Config::get('app.locale');
        return $this->select("payment_type_id",
            "payment_type_name_$lang as payment_type_name_vi",
            "payment_type_name_en")
            ->where('is_active',1)->get()->toArray();
    }

    public function getOption()
    {
        $lang = Config::get('app.locale');
        $select = $this->select(
            "payment_type_id",
            "payment_type_name_$lang as payment_type_name"
        )->where("is_active", self::IS_ACTIVE);
        return $select->get();
    }

    /**
     * Tổng tiền theo từng loại phiếu chi
     *
     * @return mixed
     */
    public function getTotalPaymentByPaymentType($time, $branchCode, $paymentType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $lang = Config::get('app.locale');
        $select = $this->select("{$this->table}.payment_type_id",
            "{$this->table}.payment_type_name_$lang as payment_type_name",
            DB::raw('SUM(payments.total_amount) as total_amount'))
            ->leftJoin("payments","payments.payment_type", "=", "{$this->table}.payment_type_id")
            ->where("payments.status","=","paid")
            ->whereBetween("payments.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"]);

        if ($branchCode != null) {
            $select->where("payments.branch_code", $branchCode);
        }
        if ($paymentType != null) {
            $select->where("payments.payment_type", $paymentType);
        }
        if ($paymentMethod != null) {
            $select->where("payments.payment_method", $paymentMethod);
        }
        $select->groupBy("{$this->table}.payment_type_id","{$this->table}.payment_type_name_$lang");
        return $select->get();
    }

    /**
     * Thêm loại phiếu chi
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->payment_type_id;
    }

    public function _getList(&$filter = [])
    {
        $select = $this->select(
            "{$this->table}.payment_type_id",
            "{$this->table}.payment_type_name_vi",
            "{$this->table}.payment_type_name_en",
            "{$this->table}.is_active",
            "{$this->table}.created_by",
            "{$this->table}.created_at"
        )
//            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->orderBy("{$this->table}.payment_type_id", "desc");
        // filter name, code
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('payment_type_name_vi', 'like', '%' . $search . '%')
                    ->orWhere('payment_type_name_en', 'like', '%' . $search . '%');
            });
        }
        // filter ngày tạo
        if (isset($filter["created_at"]) &&  $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $select;
    }

    /**
     * chi tiết loại phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $select = $this->select(
            "payment_type_id",
//            "payment_type_code",
            "payment_type_name_vi",
            "payment_type_name_en",
            "is_active",
//            "is_system",
            "created_by",
            "updated_by",
            "created_at",
        )->where("{$this->primaryKey}", $id);
        return $select->first();
    }

    /**
     * cập nhật loại phiếu chi
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->primaryKey}", $id)->update($data);
    }

    /**
     * Xoá loại phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function deleteType($id)
    {
        return $this->where("{$this->primaryKey}", $id)->delete();
    }
}