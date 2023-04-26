<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/05/2021
 * Time: 11:19 AM
 */

namespace Modules\Payment\Repositories\Payment;

use App\Exports\ExportFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Models\SupplierTable;
use Modules\Payment\Models\ConfigPrintBillTable;
use Modules\Payment\Models\ObjectAccountingTypeTable;
use Modules\Payment\Models\PaymentMethodTable;
use Modules\Payment\Models\PaymentTable;
use Modules\Payment\Models\PaymentTypeTable;
use Modules\Payment\Models\SpaInfoTable;
use Modules\Referral\Models\ReferralMemberDetailTable;
use Modules\Referral\Models\ReferralMemberTable;
use Modules\Referral\Models\ReferralPaymentDetailTable;
use Modules\Referral\Models\ReferralPaymentMemberTable;
use Modules\Referral\Models\ReferralPaymentTable;
use Modules\Referral\Models\ReferralProgramInviteTable;

class PaymentRepository implements PaymentRepositoryInterface
{
    protected $payment;
    protected $timestamps = true;

    public function __construct(PaymentTable $payment)
    {
        $this->payment = $payment;
    }

    //Hàm lấy danh sách

    /**
     * Lấy thông tin danh sách payment và các value option cần thiết
     *
     * @param array $filters
     * @return array
     */
    public function list(array &$filters = [])
    {
        $branchTable = new BranchTable();
        $staffTable = new StaffsTable();
        $paymentTypeTable = new PaymentTypeTable();
        $paymentMethodTable = new PaymentMethodTable();
        $objectAccountTypeTable = new ObjectAccountingTypeTable();
        $supplierTable = new SupplierTable();
        $customerTable = new CustomerTable();

        $data = $this->payment->getList($filters);

        $getBranch = $branchTable->getBranchOption();
        $getStaff = $staffTable->getStaffOption();
        $getPaymentType = $paymentTypeTable->getPaymentTypeOption();
        $getPaymentMethod = $paymentMethodTable->getPaymentMethodOption();
        $getObjectAccountingType = $objectAccountTypeTable->getObjectAccountTypeOption();
        $getSupplier = $supplierTable->getAll();
        $getCustomer = $customerTable->getCustomerOption();
        return [
            'LIST' => $data,
            'BRANCH' => $getBranch,
            'STAFF' => $getStaff,
            'PAYMENT_TYPE' => $getPaymentType,
            'PAYMENT_METHOD' => $getPaymentMethod,
            'OBJECT_ACCOUNTING_TYPE' => $getObjectAccountingType,
            'SUPPLIER' => $getSupplier,
            'CUSTOMER' => $getCustomer
        ];
    }

    /**
     * Lấy thông tin loại thẻ select (staff,customer,supplier) dựa vào object accounting type code
     * Hoặc trả về '' (rỗng) nếu code là shipper hoặc other
     *
     * @param $code
     * @return mixed|string
     */
    public function getSelectOptionByObjectAccountingTypeCode($code)
    {
        $staffTable = new StaffsTable();
        $supplierTable = new SupplierTable();
        $customerTable = new CustomerTable();
        if($code == 'OAT_EMPLOYEE'){
            return $staffTable->getStaffOption();
        }
        else if($code == 'OAT_SUPPLIER'){
            return $supplierTable->getAll();
        }
        else if($code == 'OAT_CUSTOMER'){
            return $customerTable->getCustomerOption();
        }
        else{
            return '';
        }
    }

    /**
     * Tạo 1 phiếu chi mới
     *
     * @param $dataCreate
     * @return array
     */
    public function createPayment($dataCreate)
    {
        try {
            // generate payment_code (P + ddmmyyyy + số tự tăng)
            $payment_code_old = $this->payment->getPaymentMaxId();
            $max_id_old = isset($payment_code_old['payment_code']) ?
                substr($payment_code_old['payment_code'],9,strlen($payment_code_old['payment_code'])) : null;

            if($max_id_old == null){
                $max_id_old = 0;
            }
            $max_id_new = (int)$max_id_old + 1;
            $currentDay = (new \DateTime())->format('d');
            $currentMonth = (new \DateTime())->format('m');
            $currentYear = (new \DateTime())->format('Y');
            $payment_code_new = 'P' .$currentDay . $currentMonth . $currentYear.$max_id_new;
            $dataInsert = [
                'payment_code' => $payment_code_new,
                'branch_code' => $dataCreate['branch_code'],
                'total_amount' =>
                    $dataCreate['total_amount'] = str_replace(',', '', isset($dataCreate['total_amount']) ? $dataCreate['total_amount']: 0),
                'status'=> 'new',
                'note' => $dataCreate['note'],
                'object_accounting_type_code' => $dataCreate['object_accounting_type_code'],
                'accounting_id' => isset($dataCreate['accounting_id']) ? $dataCreate['accounting_id'] : '',
                'accounting_name' => isset($dataCreate['accounting_name']) ? $dataCreate['accounting_name'] : '',
                'payment_type' => $dataCreate['payment_type'],
                'document_code' => $dataCreate['document_code'],
                'payment_method' => $dataCreate['payment_method'],
                'is_delete' => '0',
                'created_by' => Auth::id(),
            ];
            $this->payment->createPayment($dataInsert);

            return [
                'error' => false,
                'message' => __('Thêm mới thành công')
            ];
        }
        catch (\Exception $ex){
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => __($ex->getMessage()) . $ex->getLine()
            ];
        }
    }

    /**
     * Xoá phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function deletePayment($id)
    {
        return $this->payment->deletePayment($id);
    }

    /**
     * Lấy thông tin 1 phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function getDataById($id)
    {
        return $this->payment->getDataById($id);
    }

    /**
     * Lấy data 1 phiếu chi đổ lên view popup
     *
     * @param $input
     * @return array
     */
    public function dataViewEdit($input)
    {

        $branchTable = new BranchTable();
        $staffTable = new StaffsTable();
        $paymentTypeTable = new PaymentTypeTable();
        $paymentMethodTable = new PaymentMethodTable();
        $objectAccountTypeTable = new ObjectAccountingTypeTable();
        $customerTable = new CustomerTable();
        $suppliersTable = new SupplierTable();

        $item = $this->payment->getDataById($input['payment_id']);

        $getBranch = $branchTable->getBranchOption();
        $getStaff = $staffTable->getStaffOption();
        $getPaymentType = $paymentTypeTable->getPaymentTypeOption();
        $getPaymentMethod = $paymentMethodTable->getPaymentMethodOption();
        $getObjectAccountingType = $objectAccountTypeTable->getObjectAccountTypeOption();
        $getCustomer = $customerTable->getCustomerOption();
        $getSupplier = $suppliersTable->getAll();

        $html = \View::make('payment::payment.edit', [
            "item" => $item,
            'load' => $input['load'],
            'OBJECT_ACCOUNTING_TYPE' => $getObjectAccountingType,
            'BRANCH' => $getBranch,
            'STAFF' => $getStaff,
            'PAYMENT_TYPE' => $getPaymentType,
            'PAYMENT_METHOD' => $getPaymentMethod,
            'CUSTOMER' => $getCustomer,
            'SUPPLIER' => $getSupplier,
            'input' => $input
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Cập nhật phiếu chi
     *
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(array $data, $id)
    {
        try{
            DB::beginTransaction();
            $dataInsert = $data;
            // thêm approved_by khi status là aprroved
            $dataInsert['approved_by'] = $data['status'] == 'approved' ? Auth::id() : '';
            // thêm staff_id khi status là paid
            $dataInsert['staff_id'] = $data['status'] == 'paid' ? Auth::id() : '';
            $dataInsert['updated_by'] = Auth::id();
            // change format date before update data
            if($data['status'] == 'paid'){
                $dataInsert['payment_date'] = date('Y-m-d H:i');
            }
            $dataInsert['total_amount'] =
            $dataCreate['total_amount'] = str_replace(',', '', isset($data['total_amount']) ? $data['total_amount']: 0);
            $this->payment->edit($dataInsert, $id);

            if(in_array($data['status'],['paid','unpaid'])){
                $detail = $this->payment->getDataById($id);
                if (isset($detail['referral_payment_member_id'])){
                    $mReferralPaymentMember = app()->get(ReferralPaymentMemberTable::class);
                    $mReferralPaymentDetail = app()->get(ReferralPaymentDetailTable::class);
                    $mReferralProgramInvite = app()->get(ReferralProgramInviteTable::class);
                    $mReferralMember = app()->get(ReferralMemberTable::class);
                    $mReferralMemberDetail = app()->get(ReferralMemberDetailTable::class);
                    $mReferralPayment = app()->get(ReferralPaymentTable::class);
                    $status = $data['status'] == 'paid' ? 'payment' : 'reject';
                    $mReferralPaymentMember->updatePaymentMember(['status' => $status],$detail['referral_payment_member_id']);
//                    Nếu trạng thái duyệt
//                    Cộng tiền vào cột total_commission bảng referral_payment
//                    Trừ tiền cột total_commission bảng referral_member
                    if ($status == 'payment'){
//                        Chi tiết referral_member
                        $detailMember = $mReferralMember->getDetail($detail['referral_member_id']);

//                        Cập nhật total_commmission referral_payment
                        $mReferralPayment->updatePayment(['total_commission' => (double)$detail['referral_payment_total_commission'] + (double)$dataInsert['total_amount']],$detail['referral_payment_id']);

//                        Cập nhật total_commmission referral_member
                        $mReferralMember->updateMember(['total_commission' => (double)$detailMember['total_commission'] - (double)$dataInsert['total_amount']],$detail['referral_member_id']);

                        $mReferralProgramInvite->updateByStatusMemberId(['status' => $status],$detail['referral_member_id'],'waiting_payment');
                    } else {
                        $mReferralProgramInvite->updateByStatusMemberId(['status' => 'approve'],$detail['referral_member_id'],'waiting_payment');
                    }

//                    if ($status == 'reject') {
//                        $status = 'approve';
//
////                        Lấy danh sách chi tiết thanh toán
//                        $listPaymentDetail = $mReferralPaymentDetail->getListByPaymentId($detail['referral_payment_id']);
//                        if (count($listPaymentDetail) != 0){
//                            $tmp = collect($listPaymentDetail)->pluck('referral_member_id')->toArray();
////                            Nhóm theo id referral member id
//                            $tmpGroup = collect($listPaymentDetail)->groupBy('referral_member_id');
////                            Lấy danh sách member
//                            $listMember = $mReferralMember->getListByArrId($tmp);
//
//                            foreach ($listMember as $item){
//                                $dataDetail = [];
//                                $totalCommission = (double)$item['total_commission'];
//                                if (isset($tmpGroup[$item['referral_member_id']])){
//                                    foreach ($tmpGroup[$item['referral_member_id']] as $itemDetail){
//                                        $dataDetail[] = [
//                                            'referral_member_id' => $item['referral_member_id'],
//                                            'referral_multi_level_id' => '',
//                                            'referral_from' => $itemDetail['referral_from'],
//                                            'action' => $itemDetail['action'],
//                                            'type' => 'minus',
//                                            'obj_id' => $itemDetail['obj_id'],
//                                            'total_money' => $itemDetail['total_money'],
//                                            'total_commission' => $itemDetail['total_commission'],
//                                            'Note' => '',
//                                            'created_at' => Carbon::now(),
//                                            'is_run' => 1,
//                                        ];
//
//                                        $totalCommission += (double)$itemDetail['total_commission'];
//                                    }
//                                }
//
//                                if (count($dataDetail) != 0){
//                                    $mReferralMemberDetail->insertData($dataDetail);
//                                    $mReferralMember->updateMember(['total_commission' => $totalCommission],$item['referral_member_id']);
//                                }
//                            }
//
//                        }
//                    }
////                    Cập nhật program invite
//                    $mReferralProgramInvite->updateByMemberId(['referral_program_invite.status' => $status],$detail['referral_member_id']);
                }
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        }catch (\Exception $ex){
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ]);
        }
    }

    /**
     * Xem chi tiết phiếu chi
     *
     * @param $input
     * @return array
     */
    public function dataViewDetail($input)
    {
        $branchTable = new BranchTable();
        $staffTable = new StaffsTable();
        $paymentTypeTable = new PaymentTypeTable();
        $paymentMethodTable = new PaymentMethodTable();
        $objectAccountTypeTable = new ObjectAccountingTypeTable();
        $customerTable = new CustomerTable();
        $suppliersTable = new SupplierTable();

        $item = $this->payment->getDataById($input['payment_id']);

        $getBranch = $branchTable->getBranchOption();
        $getStaff = $staffTable->getStaffOption();
        $getPaymentType = $paymentTypeTable->getPaymentTypeOption();
        $getPaymentMethod = $paymentMethodTable->getPaymentMethodOption();
        $getObjectAccountingType = $objectAccountTypeTable->getObjectAccountTypeOption();
        $getCustomer = $customerTable->getCustomerOption();
        $getSupplier = $suppliersTable->getAll();

        $html = \View::make('payment::payment.detail', [
            "item" => $item,
            'load' => $input['load'],
            'OBJECT_ACCOUNTING_TYPE' => $getObjectAccountingType,
            'BRANCH' => $getBranch,
            'STAFF' => $getStaff,
            'PAYMENT_TYPE' => $getPaymentType,
            'PAYMENT_METHOD' => $getPaymentMethod,
            'CUSTOMER' => $getCustomer,
            'SUPPLIER' => $getSupplier,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * In phiếu chi
     *
     * @param $input
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function printBill($input)
    {
        try {
            // Lấy thông tin spa
            $mSpaInfo = new SpaInfoTable();
            $spaInfo = $mSpaInfo->getItem(1);
            // Lấy thông tin config bill
            $mConfigPrintBill = new ConfigPrintBillTable();
            $configPrintBill = $mConfigPrintBill->getItem(1);
            // Lấy thông tin phiếu chi
            $payment_id = $input['print_payment_id'];
            $paymentInfo = $this->payment->getDataDetail($payment_id);
            // Check số lần in bill-> lớn hơn 0 thì text là 'in lại'



            $template = 'payment::payment.print.template-k58';
            return view($template, [
                'payment' => $paymentInfo,
//                'receipt_detail' => $list_receipt_detail,
                'spaInfo' => $spaInfo,
                'cash' => 0,
                'visa' => 0,
                'transfer' => 0,
                'member_money' => 0,
                'configPrintBill' => $configPrintBill,
                'paymentId' => $payment_id,
//                'printTime' => $printReply,
                'QrCode' => $paymentInfo['payment_code']
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    public function saveLogPrintBill($input)
    {
        try {
            $receiptId = $input['id'];

            return [
                'error' => false,
                'message' => __('Thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thất bại')
            ];
        }
    }

    /**
     * Lấy data phiếu chi
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel($input)
    {
        $heading = [
            __('Stt'),
            __('Mã phiếu'),
            __('Loại người nhận'),
            __('Người tạo'),
            __('Tổng tiền'),
            __('Chi nhánh'),
            __('Trạng thái'),
            __('Ngày ghi nhận')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];

        //Lấy ds phiếu thu
        $getData = $this->payment->getDataExportExcel([
            'search' => $input['search_export'],
            'branch_code' => $input['branch_code_export'],
            'status' => $input['status_export'],
            'created_at' => $input['created_at_export'],
            'created_by' => $input['created_by_export']
        ]);

        if (count($getData) > 0) {
            foreach ($getData as $k => $v) {
                $status = "";

                switch ($v['status']) {
                    case 'new':
                        $status = __('Mới');
                        break;
                    case 'approved':
                        $status = __('Đã xác nhận');
                        break;
                    case 'paid':
                        $status = __('Đã chi');
                        break;
                    case 'unpaid':
                        $status = __('Đã huỷ chi');
                        break;
                }

                $data [] = [
                    $k + 1,
                    $v['payment_code'],
                    $v['object_accounting_type_name_vi'],
                    $v['staff_name'],
                    number_format($v['total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    $v['branch_name'],
                    $status,
                    Carbon::parse($v['created_at'])->format('d/m/Y H:i')
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'payment.xlsx');
    }

    /**
     * Cập nhật payment basic
     * @param $data
     * @param $id
     * @return mixed|void
     */
    public function updatePayment($data, $id)
    {
        return $this->payment->createPayment();
    }
}