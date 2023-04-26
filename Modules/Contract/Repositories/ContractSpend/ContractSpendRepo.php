<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 17:14
 */

namespace Modules\Contract\Repositories\ContractSpend;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\BranchTable;
use Modules\Contract\Models\ContractExpectedRevenueTable;
use Modules\Contract\Models\ContractLogReceiptSpendTable;
use Modules\Contract\Models\ContractLogTable;
use Modules\Contract\Models\ContractPaymentTable;
use Modules\Contract\Models\ContractSpendFileTable;
use Modules\Contract\Models\ContractSpendTable;
use Modules\Contract\Models\PaymentMethodTable;
use Modules\Contract\Models\PaymentTable;
use Modules\Contract\Models\PaymentTypeTable;
use Modules\Contract\Models\StaffTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class ContractSpendRepo implements ContractSpendRepoInterface
{
    const SPEND = "spend";
    const SPEND_CONTRACT_TYPE = "OAT_CONTRACT";

    /**
     * Lấy danh sách đợt chi
     *
     * @param array $filter
     * @return array|mixed
     */
    public function list(array $filter = [])
    {
        $mReceiptSpend = app()->get(ContractSpendTable::class);
        $mReceiptSpendFile = app()->get(ContractSpendFileTable::class);

        //Lấy danh sách đọt thu
        $list = $mReceiptSpend->getList($filter);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                //Lấy file kèm theo
                $v['file'] = $mReceiptSpendFile->getFileBySpend($v['contract_spend_id']);
            }
        }

        return [
            'list' => $list
        ];
    }

    /**
     * Lấy data view thêm
     *
     * @param $input
     * @return mixed|void
     */
    public function getDataCreate($input)
    {
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mRevenue = app()->get(ContractExpectedRevenueTable::class);
        $mContractSpend = app()->get(ContractSpendTable::class);
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $mStaff = app()->get(StaffTable::class);

        //Lấy thông tin HĐ
        $payment = $mContractPayment->getPaymentByContract($input['contract_id']);
        //Lấy tiền đã thu của HĐ
        $getAmountPaid = $mContractSpend->getAmountSpend($input['contract_id']);
        //Lấy số lần tạo dự kiến thu
        $totalRevenue = $mRevenue->getNumberCreate($input['contract_id'], self::SPEND);
        //Lấy phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption();

        $amountPaid = $getAmountPaid != null ? $getAmountPaid['total_amount'] : 0;

        //Tính tiền dự kiến thu
        $prepayment = $totalRevenue != 0 ? round(($payment['last_total_amount'] - $amountPaid) / $totalRevenue, 2) : 0;
        //Tính tiền còn lại
        $amountRemain = $payment['last_total_amount'] - $amountPaid;

        return [
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionStaff' => $optionStaff,
            'prepayment' => $prepayment,
            'amountRemain' => $amountRemain
        ];
    }

    /**
     * Thêm đợt chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mLog = app()->get(ContractLogTable::class);
            $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);
            $mContractSpend = app()->get(ContractSpendTable::class);
            $mContractSpendFile = app()->get(ContractSpendFileTable::class);

            //Validate giá trị thanh toán
            if ($input['amount_spend'] > $input['amount_remain']) {
                return response()->json([
                    "error" => true,
                    "message" => __("Giá trị thanh toán không hợp lệ"),
                ]);
            }

            //Thêm đọt chi
            $spendId = $mContractSpend->add([
                'contract_id' => $input['contract_id'],
                'content' => $input['content'],
                'spend_date' => $input['spend_date'] != null ?
                    Carbon::createFromFormat('d/m/Y', $input['spend_date'])->format('Y-m-d') : null,
                'spend_by' => $input['spend_by'],
                'prepayment' => $input['prepayment'],
                'amount_remain' => $input['amount_remain'],
                'amount_spend' => $input['amount_spend'],
                'invoice_date' => $input['invoice_date'] != null ?
                    Carbon::createFromFormat('d/m/Y', $input['invoice_date'])->format('Y-m-d') : null,
                'invoice_no' => $input['invoice_no'],
                'payment_method_id' => $input['payment_method_id'],
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            $arrFile = [];

            if (isset($input['contract_spend_files']) && count($input['contract_spend_files']) > 0) {
                foreach ($input['contract_spend_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_spend_id' => $spendId,
                        'file_name' => $input['contract_spend_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Thêm file kèm theo
            $mContractSpendFile->insert($arrFile);

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::SPEND,
                "note" => __('Thêm đợt chi'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogReceipt->add([
                "contract_log_id" => $logId,
                "object_type" => self::SPEND,
                "object_id" => $spendId
            ]);

            //Insert phiếu chi (payment)
            $paymentCode = $this->_insertPayment($input);
            //Update mã phiếu thu vào đợt thu
            $mContractSpend->edit([
                'payment_code' =>  $paymentCode
            ], $spendId);
            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Chi tiết chi'));
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
     * Insert phiếu chi
     *
     * @param $input
     * @return string
     */
    private function _insertPayment($input)
    {
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mPayment = app()->get(PaymentTable::class);
        $mPaymentType = app()->get(PaymentTypeTable::class);
        $mBranch = app()->get(BranchTable::class);

        //Lấy thông tin chi nhánh
        $getBranch = $mBranch->getItem(Auth()->user()->branch_id);
        //Lấy thông tin phương thức thanh toán
        $methodInfo = $mPaymentMethod->getInfo($input['payment_method_id']);
        //Lấy thông tin loại phiếu chi
        $getType = $mPaymentType->getTypeBySystem('CONTRACT_TYPE');
        //Thêm phiếu chi
        $paymentId = $mPayment->add([
            'payment_code' => 'abc',
            'staff_id' => $input['spend_by'],
            'branch_code' => $getBranch != null ? $getBranch['branch_code'] : '',
            'total_amount' => $input['amount_spend'],
            'status'=> 'paid',
            'note' => $input['note'],
            'object_accounting_type_code' => self::SPEND_CONTRACT_TYPE,
            'accounting_id' => $input['contract_id'],
            'payment_type' => $getType['payment_type_id'],
            'payment_method' => $methodInfo['payment_method_code'],
            'is_delete' => 0,
            'created_by' => Auth()->id(),
        ]);
        //Update mã phiếu chi
        $paymentCode = 'TT_' . date('dmY') . sprintf("%01d", $paymentId);
        $mPayment->edit(['payment_code' => $paymentCode], $paymentId);

        return $paymentCode;
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
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mContractSpend = app()->get(ContractSpendTable::class);
        $mContractSpendFile = app()->get(ContractSpendFileTable::class);


        //Lấy thông tin HĐ
        $payment = $mContractPayment->getPaymentByContract($input['contract_id']);
        //Lấy tiền đã thu của HĐ
        $getAmountPaid = $mContractSpend->getAmountSpend($input['contract_id']);
        //Lấy số lần tạo dự kiến thu
        $totalRevenue = $mRevenue->getNumberCreate($input['contract_id'], self::SPEND);
        //Lấy phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption();
        //Lấy thông tin đợt chi
        $info = $mContractSpend->getInfo($input['contract_spend_id']);
        //Lấy thông tin file đợt chi
        $getFile = $mContractSpendFile->getFileBySpend($input['contract_spend_id']);

        $amountPaid = $getAmountPaid != null ? $getAmountPaid['total_amount'] : 0;
        //Tính tiền dự kiến thu
        $prepayment = round(($payment['last_total_amount'] - $amountPaid + $info['amount_spend']) / $totalRevenue, 2);
        //Tính tiền còn lại
        $amountRemain = $payment['last_total_amount'] - $amountPaid + $info['amount_spend'];

        return [
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionStaff' => $optionStaff,
            'prepayment' => $prepayment,
            'amountRemain' => $amountRemain,
            'item' => $info,
            'spendFile' => $getFile
        ];
    }

    /**
     * Chỉnh sửa đợt chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mLog = app()->get(ContractLogTable::class);
            $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);
            $mContractSpend = app()->get(ContractSpendTable::class);
            $mContractSpendFile = app()->get(ContractSpendFileTable::class);
            $mPayment = app()->get(PaymentTable::class);

            //Validate giá trị thanh toán
            if ($input['amount_spend'] > $input['amount_remain']) {
                return response()->json([
                    "error" => true,
                    "message" => __("Giá trị thanh toán không hợp lệ"),
                ]);
            }

            //Lấy thông tin đọt chi
            $info = $mContractSpend->getInfo($input['contract_spend_id']);
            //Chỉnh sửa đợt chi
            $mContractSpend->edit([
                'contract_id' => $input['contract_id'],
                'content' => $input['content'],
                'spend_date' => $input['spend_date'] != null ?
                    Carbon::createFromFormat('d/m/Y', $input['spend_date'])->format('Y-m-d') : null,
                'spend_by' => $input['spend_by'],
                'prepayment' => $input['prepayment'],
                'amount_remain' => $input['amount_remain'],
                'amount_spend' => $input['amount_spend'],
                'invoice_date' => $input['invoice_date'] != null ?
                    Carbon::createFromFormat('d/m/Y', $input['invoice_date'])->format('Y-m-d') : null,
                'invoice_no' => $input['invoice_no'],
                'payment_method_id' => $input['payment_method_id'],
                'note' => $input['note'],
                'updated_by' => Auth()->id()
            ], $input['contract_spend_id']);
            //Xoá file kèm theo
            $mContractSpendFile->removeFileBySpend($input['contract_spend_id']);

            $arrFile = [];

            if (isset($input['contract_spend_files']) && count($input['contract_spend_files']) > 0) {
                foreach ($input['contract_spend_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_spend_id' => $input['contract_spend_id'],
                        'file_name' => $input['contract_spend_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Thêm file kèm theo
            $mContractSpendFile->insert($arrFile);

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::SPEND,
                "note" => __('Chỉnh sửa đợt chi'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogReceipt->add([
                "contract_log_id" => $logId,
                "object_type" => self::SPEND,
                "object_id" => $input['contract_spend_id']
            ]);
            //Xoá phiếu chi cũ
            $mPayment->editByCode([
                'is_delete' => 1
            ], $info['payment_code']);

            //Insert phiếu chi (payment)
            $paymentCode = $this->_insertPayment($input);
            //Update mã phiếu thu vào đợt thu
            $mContractSpend->edit([
                'payment_code' =>  $paymentCode
            ], $input['contract_spend_id']);

            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Chi tiết chi'));
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
     * Xoá đợt chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($input)
    {
        DB::beginTransaction();
        try {
            $mLog = app()->get(ContractLogTable::class);
            $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);
            $mContractSpend = app()->get(ContractSpendTable::class);
            $mPayment = app()->get(PaymentTable::class);

            //Lấy thông tin đọt chi
            $info = $mContractSpend->getInfo($input['contract_spend_id']);
            //Xoá đợt chi
            $mContractSpend->edit([
                'is_deleted' => 1,
                'reason' => $input['reason']
            ], $input['contract_spend_id']);

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::SPEND,
                "note" => __('Xoá đợt chi'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogReceipt->add([
                "contract_log_id" => $logId,
                "object_type" => self::SPEND,
                "object_id" => $input['contract_spend_id']
            ]);

            //Xoá phiếu chi cũ
            $mPayment->editByCode([
                'is_delete' => 1
            ], $info['payment_code']);


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