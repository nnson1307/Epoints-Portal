<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/09/2021
 * Time: 14:55
 */

namespace Modules\Contract\Repositories\ContractReceipt;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractExpectedRevenueTable;
use Modules\Contract\Models\ContractLogReceiptSpendTable;
use Modules\Contract\Models\ContractLogTable;
use Modules\Contract\Models\ContractMapOrderTable;
use Modules\Contract\Models\ContractPaymentTable;
use Modules\Contract\Models\ContractReceiptDetailTable;
use Modules\Contract\Models\ContractReceiptFileTable;
use Modules\Contract\Models\ContractReceiptTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\OrderTable;
use Modules\Contract\Models\PaymentMethodTable;
use Modules\Contract\Models\ReceiptDetailTable;
use Modules\Contract\Models\ReceiptTable;
use Modules\Contract\Models\StaffTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class ContractReceiptRepo implements ContractReceiptRepoInterface
{
    const RECEIPT = "receipt";
    const RECEIPT_CONTRACT_TYPE = "RTC_CONTRACT";

    /**
     * Lấy ds đợt thu
     *
     * @param array $filter
     * @return array|mixed
     */
    public function list(array $filter = [])
    {
        $mContractReceipt = app()->get(ContractReceiptTable::class);
        $mContractReceiptFile = app()->get(ContractReceiptFileTable::class);

        //Lấy danh sách đợt thu
        $list = $mContractReceipt->getList($filter);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                //Lấy file kèm theo
                $v['file'] = $mContractReceiptFile->getFileByReceipt($v['contract_receipt_id']);
            }
        }

        return [
            'list' => $list
        ];
    }

    /**
     * Lấy dữ liệu view tạo
     *
     * @param $input
     * @return array|mixed
     */
    public function getDataCreate($input)
    {
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mRevenue = app()->get(ContractExpectedRevenueTable::class);
        $mContractReceipt = app()->get(ContractReceiptTable::class);
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mContract = app()->get(ContractTable::class);
        $mReceipt = app()->get(ReceiptTable::class);

        //Lấy thông tin HĐ
        $info = $mContract->getInfo($input['contract_id']);
        //Lấy thông tin thanh toán HĐ
        $payment = $mContractPayment->getPaymentByContract($input['contract_id']);
        //Lấy thông tin đơn hàng gần nhất để thanh toán
        $getOrder = $mContractMapOrder->getOrderMap($info['contract_code']);

        if ($getOrder != null) {
            $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);
        } else {
            //Lấy tiền đã thu của đơn hàng
            $getAmountPaid = $mContractReceipt->getAmountReceipt($input['contract_id']);
        }

        //Lấy số lần tạo dự kiến thu
        $totalRevenue = $mRevenue->getNumberCreate($input['contract_id'], self::RECEIPT);
        //Lấy phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption();

        //Tính tiền đã thanh toán
        $amountPaid = $getAmountPaid != null ? $getAmountPaid['amount_paid'] : 0;
        $lastTotalAmount = $payment['last_total_amount'] != null ? $payment['last_total_amount'] : 0;

        //Tính tiền dự kiến thu
        $prepayment = $totalRevenue > 0 ?
                round(($lastTotalAmount - $amountPaid) / $totalRevenue, 2) : 0;
        //Tính tiền còn lại
        $amountRemain = $lastTotalAmount - $amountPaid;

        return [
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionStaff' => $optionStaff,
            'prepayment' => $prepayment,
            'amountRemain' => $amountRemain
        ];
    }

    /**
     * Thêm đợt thu
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mContractReceipt = app()->get(ContractReceiptTable::class);
            $mContractReceiptDetail = app()->get(ContractReceiptDetailTable::class);
            $mContractReceiptFile = app()->get(ContractReceiptFileTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);

            $totalAmountReceipt = 0;

            if (isset($input['arrayMethod']) && count($input['arrayMethod']) > 0) {
                foreach ($input['arrayMethod'] as $k => $v) {
                    $totalAmountReceipt += $v;
                }
            }

            //Validate giá trị thanh toán
            if ($totalAmountReceipt > $input['amount_remain'] || $totalAmountReceipt == 0) {
                return response()->json([
                    "error" => true,
                    "message" => __("Giá trị thanh toán không hợp lệ"),
                ]);
            }

            //Thêm đợt thu
            $contractReceiptId = $mContractReceipt->add([
                'contract_id' => $input['contract_id'],
                'content' => $input['content'],
                'collection_date' => $input['collection_date'] != null ?
                    Carbon::createFromFormat('d/m/Y', $input['collection_date'])->format('Y-m-d') : null,
                'collection_by' => $input['collection_by'],
                'prepayment' => $input['prepayment'],
                'amount_remain' => $input['amount_remain'],
                'total_amount_receipt' => $totalAmountReceipt,
                'invoice_date' => $input['invoice_date'] != null ?
                    Carbon::createFromFormat('d/m/Y', $input['invoice_date'])->format('Y-m-d') : null,
                'invoice_no' => $input['invoice_no'],
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            $arrReceiptDetail = [];

            if (isset($input['arrayMethod']) && count($input['arrayMethod']) > 0) {
                foreach ($input['arrayMethod'] as $k => $v) {
                    $arrReceiptDetail [] = [
                        "contract_receipt_id" => $contractReceiptId,
                        "amount_receipt" => $v,
                        "payment_method_id" => $k,
                        "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                        "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Thêm chi tiết đợt thu
            $mContractReceiptDetail->insert($arrReceiptDetail);

            $arrFile = [];

            if (isset($input['contract_receipt_files']) && count($input['contract_receipt_files']) > 0) {
                foreach ($input['contract_receipt_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_receipt_id' => $contractReceiptId,
                        'file_name' => $input['contract_receipt_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Thêm file kèm theo
            $mContractReceiptFile->insert($arrFile);

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::RECEIPT,
                "note" => __('Thêm đợt thu'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogReceipt->add([
                "contract_log_id" => $logId,
                "object_type" => self::RECEIPT,
                "object_id" => $contractReceiptId
            ]);

            $input['total_amount_receipt'] = $totalAmountReceipt;
            //Insert phiếu thu (receipt)
            $receiptCode = $this->_insertReceipt($input);
            //Update mã phiếu thu vào đợt thu
            $mContractReceipt->edit([
                'receipt_code' => $receiptCode
            ], $contractReceiptId);

            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Chi tiết thu'));
            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Thêm thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Thêm thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Insert phiếu thu
     *
     * @param $input
     * @return string
     */
    private function _insertReceipt($input)
    {
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mReceipt = app()->get(ReceiptTable::class);
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mContract = app()->get(ContractTable::class);
        $mOrder = app()->get(OrderTable::class);

        //Lấy thông tin HĐ
        $infoContract = $mContract->getInfo($input['contract_id']);
        //Lấy thông tin đơn hàng gần nhất để thanh toán
        $getOrder = $mContractMapOrder->getOrderMap($infoContract['contract_code']);
        //Lấy thông tin thanh toán của đơn hàng
        $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);
        //Tiền đã thanh toán của đơn hàng (trươc đó)
        $amountPaidOrder = $getAmountPaid != null ? $getAmountPaid['amount_paid'] : 0;
        //Tính tổng tiền đã thanh toán (cũ + mới)
        $amountPaid = $input['total_amount_receipt'] + $amountPaidOrder;

        $status = 'paysuccess';
        if ($amountPaid < $getOrder['amount']) {
            $status = 'pay-half';
        }

        //Cập nhật trạng thái đơn hàng
        $mOrder->edit([
            'process_status' => $status
        ], $getOrder['order_id']);

        //Insert phiếu thu
        $receiptId = $mReceipt->add([
            'status' => 'paid',
            'staff_id' => $input['collection_by'],
            'object_type' => 'order',
            'object_id' => $getOrder['order_id'],
            'order_id' => $getOrder['order_id'],
            'total_money' => $input['total_amount_receipt'],
            'amount' => $input['total_amount_receipt'],
            'amount_paid' => $input['total_amount_receipt'],
            'receipt_type_code' => 'RTC_ORDER',
            'type_insert' => 'auto',
            'object_accounting_type_code' => $getOrder['order_code'],
            'object_accounting_id' => $getOrder['order_id'],
            'note' => $input['note'],
            'created_by' => Auth()->id()
        ]);
        //Update mã phiếu thu
        $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);
        $mReceipt->edit(['receipt_code' => $receiptCode], $receiptId);

        if (isset($input['arrayMethod']) && count($input['arrayMethod']) > 0) {
            foreach ($input['arrayMethod'] as $k => $v) {
                //Lấy thông tin phương thức thanh toán
                $methodInfo = $mPaymentMethod->getInfo($k);

                //Insert chi tiết phiếu thu (receipt_detail)
                $mReceiptDetail->add([
                    'receipt_id' => $receiptId,
                    'cashier_id' => $input['collection_by'],
                    'amount' => $v,
                    'payment_method_code' => $methodInfo['payment_method_code'],
                    'created_by' => Auth()->id()
                ]);
            }
        }
        return $receiptCode;
    }

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $input
     * @return array|mixed
     */
    public function getDataEdit($input)
    {
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mRevenue = app()->get(ContractExpectedRevenueTable::class);
        $mContractReceipt = app()->get(ContractReceiptTable::class);
        $mContractReceiptDetail = app()->get(ContractReceiptDetailTable::class);
        $mContractReceiptFile = app()->get(ContractReceiptFileTable::class);
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mContract = app()->get(ContractTable::class);
        $mReceipt = app()->get(ReceiptTable::class);

        //Lấy thông tin đợt thu
        $info = $mContractReceipt->getInfo($input['contract_receipt_id']);
        //Lấy chi tiết đợt thu
        $infoDetail = $mContractReceiptDetail->getDetail($input['contract_receipt_id']);
        //Lấy thông tin file đợt thu
        $getFile = $mContractReceiptFile->getFileByReceipt($input['contract_receipt_id']);
        //Lấy thông tin HĐ
        $infoContract = $mContract->getInfo($input['contract_id']);
        //Lấy thông tin thanh toán HĐ
        $payment = $mContractPayment->getPaymentByContract($input['contract_id']);
        //Lấy thông tin đơn hàng gần nhất để thanh toán
        $getOrder = $mContractMapOrder->getOrderMap($infoContract['contract_code']);
        //Lấy tiền đã thu của đơn hàng
//        $getAmountPaid = $mContractReceipt->getAmountReceipt($input['contract_id']);
        $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);
        //Lấy số lần tạo dự kiến thu
        $totalRevenue = $mRevenue->getNumberCreate($input['contract_id'], self::RECEIPT);
        //Lấy phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption();

        $amountPaid = $getAmountPaid != null ? $getAmountPaid['amount_paid'] : 0;
        //Tính tiền dự kiến thu
        $prepayment = $totalRevenue > 0 ?
            round(($payment['last_total_amount'] - $amountPaid + $info['amount_receipt']) / $totalRevenue, 2) : 0;
        //Tính tiền còn lại
        $amountRemain = $payment['last_total_amount'] - $amountPaid + $info['amount_receipt'];

        $arrMethodId = [];

        if (count($infoDetail) > 0) {
            foreach ($infoDetail as $v) {
                $arrMethodId [] = $v['payment_method_id'];
            }
        }

        return [
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionStaff' => $optionStaff,
            'prepayment' => $prepayment,
            'amountRemain' => $amountRemain,
            'item' => $info,
            'detail' => $infoDetail,
            'receiptFile' => $getFile,
            'arrMethodId' => $arrMethodId
        ];
    }

    /**
     * Chỉnh sửa đợt thu
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mContractReceipt = app()->get(ContractReceiptTable::class);
            $mContractReceiptFile = app()->get(ContractReceiptFileTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);
            $mReceipt = app()->get(ReceiptTable::class);

            //Lấy thông tin đơt thu
            $info = $mContractReceipt->getInfo($input['contract_receipt_id']);
            //Chỉnh sửa đợt thu
            $mContractReceipt->edit([
                'invoice_date' => $input['invoice_date'] != null ?
                    Carbon::createFromFormat('d/m/Y', $input['invoice_date'])->format('Y-m-d') : null,
                'invoice_no' => $input['invoice_no'],
                'note' => $input['note'],
                'updated_by' => Auth()->id()
            ], $input['contract_receipt_id']);

            //Xoá file đợt thu
            $mContractReceiptFile->removeFileByReceipt($input['contract_receipt_id']);

            $arrFile = [];

            if (isset($input['contract_receipt_files']) && count($input['contract_receipt_files']) > 0) {
                foreach ($input['contract_receipt_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_receipt_id' => $input['contract_receipt_id'],
                        'file_name' => $input['contract_receipt_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Thêm file kèm theo
            $mContractReceiptFile->insert($arrFile);

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::RECEIPT,
                "note" => __('Chỉnh sửa đợt thu'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogReceipt->add([
                "contract_log_id" => $logId,
                "object_type" => self::RECEIPT,
                "object_id" => $input['contract_receipt_id']
            ]);

            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Chi tiết thu'));
            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Xoá đợt thu
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($input)
    {
        DB::beginTransaction();
        try {
            $mContractReceipt = app()->get(ContractReceiptTable::class);
            $mReceipt = app()->get(ReceiptTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);


            //Lấy thông tin đợt thu
            $info = $mContractReceipt->getInfo($input['contract_receipt_id']);
            //Xoá đợt thu
            $mContractReceipt->edit([
                'is_deleted' => 1,
                'reason' => $input['reason']
            ], $input['contract_receipt_id']);
            //Xoá thanh toán
            $mReceipt->editByCode([
                'is_deleted' => 1
            ], $info['receipt_code']);

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::RECEIPT,
                "note" => __('Xoá đợt thu'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogReceipt->add([
                "contract_log_id" => $logId,
                "object_type" => self::RECEIPT,
                "object_id" => $input['contract_receipt_id']
            ]);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Xoá thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Xoá thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }
}