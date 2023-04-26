<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 2:38 PM
 */

namespace Modules\Admin\Repositories\Receipt;


use App\Import\UsersImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Api\PaymentOnline;
use Modules\Admin\Models\CustomerDebtTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\PaymentMethodTable;
use Modules\Admin\Models\ReceiptDetailTable;
use Modules\Admin\Models\ReceiptOnlineTable;
use Modules\Admin\Models\ReceiptTable;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

class ReceiptRepository implements ReceiptRepositoryInterface
{
    protected $receipt;
    protected $timestamps = true;

    public function __construct(ReceiptTable $receipts)
    {
        $this->receipt = $receipts;
    }

    public function add(array $data)
    {
        return $this->receipt->add($data);
    }

    public function getItem($id)
    {
        return $this->receipt->getItem($id);
    }

    public function sumAmmount($id)
    {
        return $this->receipt->sumAmmount($id);
    }

    public function edit(array $data, $id)
    {
        return $this->receipt->edit($data, $id);
    }

    public function list(array $filters = [])
    {
        return $this->receipt->getList($filters);
    }

    public function getReceipt($id)
    {
        // TODO: Implement getReceipt() method.
        return $this->receipt->getReceipt($id);
    }

    public function getAmountDebt($id)
    {
        return $this->receipt->getAmountDebt($id);
    }

    public function getReceiptById($id)
    {
        return $this->receipt->getReceiptById($id);
    }

    //    public function getListReceipt($arrOrderId)
    public function getListReceipt($startTime, $endTime, $filer, $valueFilter, $customerGroup = null)
    {
        //        return $this->receipt->getListReceiptByOrder($arrOrderId);
        return $this->receipt->getListReceiptByOrder($startTime, $endTime, $filer, $valueFilter, $customerGroup);
    }

    /**
     * Import công nợ bằng tay
     *
     * @param $input
     * @return mixed|void
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function importExcelManual($input)
    {
        $file = asset('static/backend/excel') . '/' . $input['file'];

        $info = pathinfo($file);
        $ext = $info['extension'];

        //Check xem file có phải là excel hay csv không
        if ($ext == 'xlsx' || $ext == 'csv' || $ext == 'xls') {
            $mCustomer = app()->get(CustomerTable::class);
            $mCustomerDebt = app()->get(CustomerDebtTable::class);

            //Đọc file excel
            $reader = ReaderFactory::create(Type::XLSX);
            $reader->open(public_path('static/backend/excel') . '/' . $input['file']);

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $key => $row) {
                    if ($key == 1) {
                        continue;
                    }

                    $phone = $row[5];
                    $amount = $row[7];

                    //Lấy thông tin KH bằng sđt
                    $getCustomer = $mCustomer->getCusPhone($phone);

                    if ($getCustomer != null && !empty($amount)) {
                        //Thêm công nợ cho khách hàng
                        $debtId = $mCustomerDebt->add([
                            'customer_id' => $getCustomer['customer_id'],
                            'status' => 'unpaid',
                            'amount' => $amount,
                            'amount_paid' => 0,
                            'order_id' => 0,
                            'debt_type' => 'first',
                            'staff_id' => Auth()->id(),
                            'created_by' => Auth()->id(),
                        ]);
                        //Cập nhật mã công nợ
                        if ($debtId < 10) {
                            $debtId = '0' . $debtId;
                        }
                        $mCustomerDebt->edit([
                            'debt_code' => 'CN_' . date('dmY') . $debtId
                        ], $debtId);
                    }
                }
            }

            echo 'Chạy thành công';
        } else {
            echo 'Chạy thất bại';
        }
    }

    /**
     * Tạo qr code thanh toán online
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function genQrCode($input)
    {
        DB::beginTransaction();
        try {
            if (isset($input['amount']) && $input['amount'] <= 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số tiền không hợp lệ')
                ]);
            }

            $mCustomerDebt = app()->get(CustomerDebtTable::class);
            //Lấy thông tin công nợ
            $info = $mCustomerDebt->getCustomerDebt($input['customer_debt_id']);

            $mReceipt = app()->get(ReceiptTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            //Tạo phiếu thu (trạng thái chưa thanh toán)
            $receiptId = $mReceipt->add([
                'customer_id' => $info['customer_id'],
                'order_id' => $info['order_id'],
                'object_id' => $info['customer_debt_id'],
                'object_type' => 'debt',
                'total_money' => $input['amount'],
                'status' => 'unpaid',
                'is_discount' => 1,
                'amount' => $input['amount'],
                'amount_paid' => $input['amount'],
                'amount_return' => 0,
                'receipt_type_code' => 'RTC_DEBT',
                'object_accounting_type_code' => $info['debt_code'], // order code
                'object_accounting_id' => $info['customer_debt_id'], // order id
            ]);

            $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);
            //Update receipt_code
            $mReceipt->edit([
                'receipt_code' => $receiptCode
            ], $receiptId);
            //Tạo chi tiết thu
            $mReceiptDetail->add([
                'receipt_id' => $receiptId,
                'payment_method_code' => $input['payment_method_code'],
                'amount' => $input['amount'],
            ]);

            $mPaymentMethod = app()->get(PaymentMethodTable::class);
            //Lấy thông tin phương thức thanh toán
            $getMethod = $mPaymentMethod->getInfoByCode($input['payment_method_code']);

            $url = "";

            if ($input['payment_method_code'] == "VNPAY") {
                $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                //Lưu vào bảng receipt_online
                $idReceiptOnline = $mReceiptOnline->add([
                    "receipt_id" => $receiptId,
                    "object_type" => "debt",
                    "object_code" => $info['debt_code'],
                    "payment_method_code" => $input['payment_method_code'],
                    "amount_paid" => $input['amount'],
                    "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                    "type" => $getMethod['payment_method_type'],
                    "performer_name" => $info['customer_name'],
                    "performer_phone" => $info['customer_phone']
                ]);
                //Nếu là vn pay thì call api thanh toán vn pay
                $callVnPay = $this->_paymentVnPay(
                    $info['debt_code'],
                    $input['amount'],
                    $info['customer_id'],
                    Auth()->user()->branch_id,
                    'web',
                    ""
                );

                if ($callVnPay['ErrorCode'] == 0) {
                    $url = $callVnPay['Data']['payment_url'];

                    //Update transaction_code cho receipt_online khi call api thành công
                    $mReceiptOnline->edit([
                        "payment_transaction_code" => $callVnPay['Data']['payment_transaction_code']
                    ], $idReceiptOnline);
                }
            }

            if ($url == "") {
                return response()->json([
                    'error' => true,
                    'message' => __('Tạo qr thất bại')
                ]);
            }


            DB::commit();

            return response()->json([
                'error' => false,
                'url' => $url,
                "receipt_id" => $receiptId,
                "message" => __("Tạo qr thành công")
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Tạo qr thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Call api thanh toán vn pay
     *
     * @param $orderCode
     * @param $amount
     * @param $userId
     * @param $branchId
     * @param $platform
     * @param $paramsExtra
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function _paymentVnPay($orderCode, $amount, $userId, $branchId, $platform, $paramsExtra)
    {
        $mPaymentOnline = app()->get(PaymentOnline::class);

        //Call api thanh toán vn pay
        return $mPaymentOnline->paymentVnPay([
            'method' => 'vnpay',
            'order_id' => $orderCode,
            'amount' => $amount,
            'user_id' => $userId,
            'branch_id' => $branchId,
            'platform' => $platform,
            'params_extra' => $paramsExtra
        ]);
    }

    /**
     * Lây danh sách thanh toán hoá đơn
     */
    public function getReceiptOrderList($orderId)
    {
        return $this->receipt->getReceiptOrderList($orderId);
    }
}