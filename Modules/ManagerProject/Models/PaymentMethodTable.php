<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:00 AM
 */

namespace Modules\ManagerProject\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\Config;

class PaymentMethodTable extends Model
{
    use ListTableTrait;
    protected $table = "payment_method";
    protected $primaryKey = "payment_method_id";

    protected $fillable = [
        'payment_method_id',
        'payment_method_code',
        'payment_method_name_vi',
        'payment_method_name_en',
        'payment_method_type',
        'payment_method_image',
        'note',
        'is_system',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'url',
        'terminal_id',
        'secret_key',
        'access_key'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_DELETE = 0;

    /**
     * Danh sách hình thức thanh toán, filter, paging
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this->select(
            "{$this->table}.payment_method_id",
            "{$this->table}.payment_method_code",
            "{$this->table}.payment_method_name_vi",
            "{$this->table}.payment_method_name_en",
            "{$this->table}.payment_method_type",
            "{$this->table}.is_system",
            "{$this->table}.is_active")
            ->where("is_delete", self::IS_DELETE);
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.payment_method_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.payment_method_name_vi", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.payment_method_name_en", 'like', '%' . $search . '%');
            });
        }
        if (isset($filter['method_type']) && $filter['method_type'] != "") {
            $methodType = $filter['method_type'];
            $ds->where("{$this->table}.payment_method_type", $methodType);
        }
        unset($filter['method_type']);
        return $ds->orderBy('payment_method_id', 'desc');
    }

    /**
     * Thêm phương thức thanh toán
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->payment_method_id;
    }

    /**
     * Lấy thông tin 1 phương thức thanh toán
     *
     * @param $paymentMethodId
     * @return mixed
     */
    public function getInfo($paymentMethodId)
    {
        return $this
            ->select(
                "{$this->table}.payment_method_id",
                "{$this->table}.payment_method_code",
                "{$this->table}.payment_method_name_vi",
                "{$this->table}.payment_method_name_en",
                "{$this->table}.payment_method_type",
                "{$this->table}.is_system",
                "{$this->table}.is_active",
                "{$this->table}.note",
                "{$this->table}.url",
                "{$this->table}.terminal_id",
                "{$this->table}.secret_key",
                "{$this->table}.access_key"
            )
            ->where("{$this->table}.payment_method_id", $paymentMethodId)
            ->first();
    }

    /**
     * Chỉnh sửa phương thức thanh toán
     *
     * @param array $data
     * @param $paymentMethodId
     * @return mixed
     */
    public function edit(array $data, $paymentMethodId)
    {
        return $this->where("payment_method_id", $paymentMethodId)->update($data);
    }

    /**
     * Xoá 1 PTTT
     *
     * @param $paymentMethodId
     * @return mixed
     */
    public function deleteType($paymentMethodId)
    {
        return $this->where($this->primaryKey, $paymentMethodId)->update(['is_delete' => 1]);
    }

    /**
     * Lấy 1 PTTT
     *
     * @param $paymentMethodCode
     * @return mixed
     */
    public function getPaymentMethodByCode($paymentMethodCode)
    {
        return $this
            ->select(
                "{$this->table}.payment_method_id",
                "{$this->table}.payment_method_code",
                "{$this->table}.payment_method_name_vi",
                "{$this->table}.payment_method_name_en",
                "{$this->table}.payment_method_type",
                "{$this->table}.is_system",
                "{$this->table}.is_active"
            )
            ->where("{$this->table}.payment_method_code", $paymentMethodCode)
            ->first();
    }

    public function getPaymentMethodByNameVi($paymentMethodNameVi)
    {
        return $this
            ->select(
                "{$this->table}.payment_method_id",
                "{$this->table}.payment_method_code",
                "{$this->table}.payment_method_name_vi",
                "{$this->table}.payment_method_name_en",
                "{$this->table}.payment_method_type",
                "{$this->table}.is_system",
                "{$this->table}.is_active"
            )
            ->where("{$this->table}.payment_method_name_vi", $paymentMethodNameVi)
            ->first();
    }

    public function getPaymentMethodByNameEn($paymentMethodNameEn)
    {
        return $this
            ->select(
                "{$this->table}.payment_method_id",
                "{$this->table}.payment_method_code",
                "{$this->table}.payment_method_name_vi",
                "{$this->table}.payment_method_name_en",
                "{$this->table}.payment_method_type",
                "{$this->table}.is_system",
                "{$this->table}.is_active"
            )
            ->where("{$this->table}.payment_method_name_en", $paymentMethodNameEn)
            ->first();
    }

    /**
     * Lấy các loại thanh toán để tạo sự lựa chọn (select option)
     *
     * @return mixed
     */
    public function getPaymentMethodOption()
    {
        $lang = Config::get('app.locale');
        return $this->select('payment_method_id', 'payment_method_code',
            "payment_method_name_$lang as payment_method_name_vi",
            'payment_method_name_en')
            ->where('is_active', 1)->get()->toArray();
    }

    /**
     * Lấy các option phương thức thanh toán
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = Config::get('app.locale');
        $select = $this->select(
            "payment_method_id", "{$this->table}.payment_method_code",
            "payment_method_name_$lang as payment_method_name"
        )
            ->where("is_active", self::IS_ACTIVE);
        return $select->get();
    }

    /**
     * Lấy thông tin thu chi theo từng PTTT
     *
     * @param $time
     * @param $branchCode
     * @param $branchId
     * @param $paymentType
     * @param $receiptType
     * @param $paymentMethod
     * @return mixed
     */
    public function getTotalAmountByPaymentMethod($time, $branchCode, $branchId, $paymentType, $receiptType, $paymentMethod)
    {
        $lang = Config::get('app.locale');
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $lang = Config::get('app.locale');
        $whereBetweenPayment = " and payments.created_at between '$startTime 00:00:00' and '$endTime 23:59:59'";
        $whereBetweenReceipt = " and receipt_details.created_at between '$startTime 00:00:00' and '$endTime 23:59:59'";
        $whereBranchPayment = $whereBranchReceipt = $wherePaymentType = $whereReceiptType = $whereReceiptMethod = $wherePaymentMethod = "";
        if ($branchCode != null) {
            $whereBranchPayment = "and payments.branch_code = '{$branchCode}'";
        }
        if ($branchId != null) {
            $whereBranchReceipt = "and staffs.branch_id = '{$branchId}'";
        }
        if ($paymentType != null) {
            $wherePaymentType = "and payments.payment_type= '{$paymentType}'";
        }
        if ($receiptType != null) {
            $whereReceiptType = "and receipts.receipt_type_code= '{$receiptType}'";
        }
        if ($paymentMethod != null) {
            $wherePaymentMethod = "and payments.payment_method = '{$paymentMethod}'";
            $whereReceiptMethod = "and receipt_details.payment_method_code = '{$paymentMethod}'";
        }
        $select = $this->select(
            "{$this->table}.payment_method_id",
            "{$this->table}.payment_method_code",
            "payment_method_name_$lang as payment_method_name",
            DB::raw('IFNULL(P.total_amount,0) as total_amount'),
            DB::raw('IFNULL(R.amount,0) as amount '),
            DB::raw('(IFNULL(R.amount,0) - IFNULL(P.total_amount,0)) as balance')
        )
            ->from(DB::raw("(select
				payment_method.payment_method_id,payment_method.payment_method_code,payment_method.payment_method_name_$lang
				from payment_method
				left join receipt_details on receipt_details.payment_method_code = payment_method.payment_method_code
				left join receipts on receipts.receipt_id = receipt_details.receipt_id 
				left join staffs on staffs.staff_id = receipts.staff_id
			where receipts.status = 'paid' {$whereBetweenReceipt} {$whereBranchReceipt} {$whereReceiptType} {$whereReceiptMethod}
				and staffs.is_deleted = 0 
				group by payment_method.payment_method_id, payment_method.payment_method_code, payment_method_name_$lang
		union
			SELECT payment_method.payment_method_id,payment_method.payment_method_code,payment_method.payment_method_name_$lang
				from payments right join payment_method on payments.payment_method = payment_method.payment_method_code
					where  payments.status = 'paid' {$whereBetweenPayment} {$whereBranchPayment} {$wherePaymentType} {$wherePaymentMethod}
			group by payment_method_code
			) as payment_method"))
            ->leftJoin(DB::raw("(SELECT IFNULL(SUM(payments.total_amount),0) as total_amount,`payment_method`.`payment_method_code` 
        from payments right join payment_method on `payments`.`payment_method` = `payment_method`.`payment_method_code`
        where  `payments`.`status` = 'paid'  {$whereBetweenPayment} {$whereBranchPayment} {$wherePaymentType} {$wherePaymentMethod}    
        group by payment_method_code
				) as P"), "P.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->leftJoin(DB::raw("(SELECT `payment_method`.`payment_method_code`
	, IFNULL(SUM(receipt_details.amount),0) as amount
	from `payment_method` 
		left join `receipt_details` on `receipt_details`.`payment_method_code` = `payment_method`.`payment_method_code` 
		left join `receipts` on `receipts`.`receipt_id` = `receipt_details`.`receipt_id` 
		left join `staffs` on `staffs`.`staff_id` = `receipts`.`staff_id` 
		where `receipts`.`status` = 'paid' {$whereBetweenReceipt} {$whereBranchReceipt} {$whereReceiptType} {$whereReceiptMethod}
			and `staffs`.`is_deleted` = 0 
		group by `payment_method`.`payment_method_code`
		 ) as R"), "R.payment_method_code", "=", "{$this->table}.payment_method_code");
//        dd($select->toSql());
        return $select->get();
    }

    /**
     * Lấy thông tin phương thức thanh toán bằng code
     *
     * @param $paymentMethodCode
     * @return mixed
     */
    public function getInfoByCode($paymentMethodCode)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "payment_method_id",
                "payment_method_name_$lang as payment_method_name",
                "payment_method_code",
                "note",
                "payment_method_type"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->where("payment_method_code", $paymentMethodCode)
            ->first();
    }
}