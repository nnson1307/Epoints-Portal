<?php

namespace Modules\Payment\Repositories\Receipt;

use App\Exports\ExportFile;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Libs\help\Help;
use Modules\Payment\Http\Api\PaymentOnline;
use Modules\Payment\Models\BranchTable;
use Modules\Payment\Models\ConfigPrintBillTable;
use Modules\Payment\Models\ConfigTable;
use Modules\Payment\Models\CustomerBranchMoneyLogTable;
use Modules\Payment\Models\CustomerDebtTable;
use Modules\Payment\Models\CustomerTable;
use Modules\Payment\Models\MemberLevelTable;
use Modules\Payment\Models\ObjectAccountingTypeTable;
use Modules\Payment\Models\OrderTable;
use Modules\Payment\Models\PaymentMethodTable;
use Modules\Payment\Models\PointHistoryTable;
use Modules\Payment\Models\PrintBillLogTable;
use Modules\Payment\Models\ReceiptDebtMapTable;
use Modules\Payment\Models\ReceiptDetailTable;
use Modules\Payment\Models\ReceiptOnlineTable;
use Modules\Payment\Models\ReceiptTable;
use Modules\Payment\Models\ReceiptTypeTable;
use Modules\Payment\Models\SpaInfoTable;
use Modules\Payment\Models\StaffTable;
use Modules\Payment\Models\SupplierTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReceiptRepo implements ReceiptRepoInterface
{
    protected $receipt;
    protected $help;
    protected $customerDept;
    const RECEIPT = 'receipt';

    public function __construct(
        ReceiptTable      $receipt,
        Help              $help,
        CustomerDebtTable $customerDept
    )
    {
        $this->receipt = $receipt;
        $this->help = $help;
        $this->customerDept = $customerDept;
    }

    const TYPE_ORDER = "RTC_ORDER";
    const TYPE_DEBT = "RTC_DEBT";

    public function list(array $filters = [])
    {
        $list = $this->receipt->getList($filters);

        if (count($list->items()) > 0) {
            $mCustomer = app()->get(CustomerTable::class);
            $mSupplier = app()->get(SupplierTable::class);
            $mStaff = app()->get(StaffTable::class);

            foreach ($list->items() as $v) {
                $object_accounting_name = null;

                switch ($v['object_accounting_type_code']) {
                    case 'OAT_CUSTOMER':
                        //Khách hàng
                        $info = $mCustomer->getInfoById($v['object_accounting_id']);

                        $object_accounting_name = $info['full_name'];
                        break;
                    case 'OAT_SUPPLIER':
                        //Nhà cung cấp
                        $info = $mSupplier->getInfo($v['object_accounting_id']);

                        $object_accounting_name = $info['supplier_name'];
                        break;
                    case 'OAT_EMPLOYEE':
                        //Nhân viên
                        $info = $mStaff->getInfo($v['object_accounting_id']);

                        $object_accounting_name = $info['full_name'];
                        break;
                    default:
                        $object_accounting_name = $v['object_accounting_name'];
                }

                $v['object_accounting_name'] = $object_accounting_name;
            }
        }

        return [
            "list" => $list,
        ];
    }

    /**
     * data view thêm phiếu thu
     *
     * @return array|mixed
     */
    public function dataViewCreate()
    {
        $mReceiptType = new ReceiptTypeTable();
        $mObjAccountingType = new ObjectAccountingTypeTable();
        $mPaymentMethod = new PaymentMethodTable();
        $optionReceiptType = $mReceiptType->getOption()->toArray();
        $optionObjAccType = $mObjAccountingType->getOption()->toArray();
        $optionPaymentMethod = $mPaymentMethod->getOption()->toArray();

        return [
            'optionReceiptType' => $optionReceiptType,
            'optionObjAccType' => $optionObjAccType,
            'optionPaymentMethod' => $optionPaymentMethod,
        ];
    }

    /**
     * Thêm mới phiếu thu
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mReceiptDetail = new ReceiptDetailTable();
            // Check loại đối tượng thu chi
            $objectAccountingType = $input['objectAccountingTypeCode'];
            $money = str_replace(',', '', $input['money']);
            $dataInsert = [
                'status' => 'unpaid',
                'staff_id' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'total_money' => $money,
                'amount' => $money,
                'amount_paid' => 0,
                'receipt_type_code' => $input['receiptTypeCode'],
                'type_insert' => 'manual',
                'object_accounting_type_code' => $objectAccountingType,
                'note' => $input['note'],
                'created_by' => Auth::id()
            ];
            switch ($objectAccountingType) {
                case 'OAT_OTHER':
                case 'OAT_SHIPPER':
                    $dataInsert['object_accounting_name'] = $input['objectAccountingName'];
                    break;
                default:
                    $dataInsert['customer_id'] = (int)$input['objectAccountingId'];
                    $dataInsert['object_accounting_id'] = (int)$input['objectAccountingId'];
                    break;
            }
            $receiptId = $this->receipt->add($dataInsert);
            //update receipt code
            $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);
            $this->receipt->edit(['receipt_code' => $receiptCode], $receiptId);
            // insert receipt detail
            $mReceiptDetail->add([
                'receipt_id' => $receiptId,
                'cashier_id' => Auth::id(),
                'receipt_type' => 'cash',
                'amount' => $money,
                'payment_method_code' => $input['paymentMethodId'],
                'created_by' => Auth::id()
            ]);

            $mPaymentMethod = app()->get(PaymentMethodTable::class);
            //Lấy thông tin phương thức thanh toán
            $getMethod = $mPaymentMethod->getInfoByCode($input['paymentMethodId']);

            $url = "";

            if ($input['paymentMethodId'] == "VNPAY") {
                $performerName = null;
                $performerPhone = null;
                //Lấy thông tin đối tượng thực hiện
                switch ($input['objectAccountingTypeCode']) {
                    case 'OAT_CUSTOMER':
                        $mCustomer = new CustomerTable();
                        //Lấy thông tin KH
                        $info = $mCustomer->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['phone1'];
                        }
                        break;
                    case 'OAT_SUPPLIER':
                        $mSupplier = new SupplierTable();
                        //Lấy nhà cung cấp
                        $info = $mSupplier->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['contact_phone'];
                        }
                        break;
                    case 'OAT_EMPLOYEE':
                        $mStaff = new StaffTable();
                        //Lấy nhà cung cấp
                        $info = $mStaff->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['phone'];
                        }
                        break;
                    default:
                        $performerName = $input['objectAccountingName'];
                        break;
                }

                $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                //Lưu vào bảng receipt_online
                $idReceiptOnline = $mReceiptOnline->add([
                    "receipt_id" => $receiptId,
                    "object_type" => "receipt",
                    "object_id" => $receiptId,
                    "object_code" => $receiptCode,
                    "payment_method_code" => $input['paymentMethodId'],
                    "amount_paid" => $money,
                    "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                    "type" => $getMethod['payment_method_type'],
                    "performer_name" => $performerName,
                    "performer_phone" => $performerPhone
                ]);
                //Nếu là vn pay thì call api thanh toán vn pay
                $callVnPay = $this->_paymentVnPay(
                    $receiptCode,
                    $money,
                    $input['objectAccountingId'],
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
                } else {
                    return [
                        'error' => true,
                        'message' => __('Tạo qr thất bại')
                    ];
                }
            }

            DB::commit();

            return [
                'error' => false,
                'data' => ['receiptId' => $receiptId],
                "url" => $url,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

    /**
     * Data view chỉnh sửa
     *
     * @param $id
     * @return array|mixed
     */
    public function dataViewEdit($id)
    {
        $mReceiptType = new ReceiptTypeTable();
        $mObjAccountingType = new ObjectAccountingTypeTable();
        $mPaymentMethod = new PaymentMethodTable();
        $optionReceiptType = $mReceiptType->getOption()->toArray();
        $optionObjAccType = $mObjAccountingType->getOption()->toArray();
        $optionPaymentMethod = $mPaymentMethod->getOption()->toArray();
        $receiptInfo = $this->receipt->getReceiptInfo($id);

        return [
            'optionReceiptType' => $optionReceiptType,
            'optionObjAccType' => $optionObjAccType,
            'optionPaymentMethod' => $optionPaymentMethod,
            'receiptInfo' => $receiptInfo
        ];
    }

    /**
     * Cập nhật phiếu thu
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mReceiptDetail = new ReceiptDetailTable();
            // Check loại đối tượng thu chi
            $receiptId = $input['receiptId'];
            $objectAccountingType = $input['objectAccountingTypeCode'];
            $money = str_replace(',', '', $input['money']);
            $dataEdit = [
                'status' => $input['status'],
                'staff_id' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'total_money' => $money,
                'amount' => $money,
                'amount_paid' => $input['status'] != 'paid' ? 0 : $money,
                'receipt_type_code' => $input['receiptTypeCode'],
                'object_accounting_type_code' => $objectAccountingType,
                'note' => $input['note'],
                'created_by' => Auth::id()
            ];

            switch ($objectAccountingType) {
                case 'OAT_OTHER':
                case 'OAT_SHIPPER':
                    $dataEdit['object_accounting_name'] = $input['objectAccountingName'];
                    break;
                default:
                    $dataEdit['customer_id'] = (int)$input['objectAccountingId'];
                    $dataEdit['object_accounting_id'] = (int)$input['objectAccountingId'];
                    break;
            }
            $this->receipt->edit($dataEdit, $receiptId);
            $mReceiptDetail->editByReceiptId(['payment_method_code' => $input['paymentMethodId']], $receiptId);

            //Lấy thông tin phiếu thu
            $receiptInfo = $this->receipt->getReceiptInfo($receiptId);

            $mPaymentMethod = app()->get(PaymentMethodTable::class);
            //Lấy thông tin phương thức thanh toán
            $getMethod = $mPaymentMethod->getInfoByCode($input['paymentMethodId']);

            $url = "";

            if ($input['paymentMethodId'] == "VNPAY" && $input['gen_qr_code'] == 1) {
                $performerName = null;
                $performerPhone = null;
                //Lấy thông tin đối tượng thực hiện
                switch ($input['objectAccountingTypeCode']) {
                    case 'OAT_CUSTOMER':
                        $mCustomer = new CustomerTable();
                        //Lấy thông tin KH
                        $info = $mCustomer->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['phone1'];
                        }
                        break;
                    case 'OAT_SUPPLIER':
                        $mSupplier = new SupplierTable();
                        //Lấy nhà cung cấp
                        $info = $mSupplier->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['contact_phone'];
                        }
                        break;
                    case 'OAT_EMPLOYEE':
                        $mStaff = new StaffTable();
                        //Lấy nhà cung cấp
                        $info = $mStaff->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['phone'];
                        }
                        break;
                    default:
                        $performerName = $input['objectAccountingName'];
                        break;
                }

                $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                //Lưu vào bảng receipt_online
                $idReceiptOnline = $mReceiptOnline->add([
                    "receipt_id" => $receiptId,
                    "object_type" => "receipt",
                    "object_id" => $receiptId,
                    "object_code" => $receiptInfo['receipt_code'],
                    "payment_method_code" => $input['paymentMethodId'],
                    "amount_paid" => $money,
                    "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                    "type" => $getMethod['payment_method_type'],
                    "performer_name" => $performerName,
                    "performer_phone" => $performerPhone
                ]);
                //Nếu là vn pay thì call api thanh toán vn pay
                $callVnPay = $this->_paymentVnPay(
                    $receiptInfo['receipt_code'],
                    $money,
                    $input['objectAccountingId'],
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
                } else {
                    return [
                        'error' => true,
                        'message' => __('Tạo qr thất bại')
                    ];
                }
            }

            DB::commit();

            $message = __('Chỉnh sửa thành công');

            if ($url != "") {
                $message = __('Tạo qr thành công');
            }

            return [
                'error' => false,
                'message' => $message,
                "url" => $url
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ];
        }
    }

    /**
     * Data view chi tiết
     *
     * @param $id
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dataViewDetail($id)
    {
        $receiptDetail = app()->get(ReceiptDetailTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mSupplier = app()->get(SupplierTable::class);
        $mStaff = app()->get(StaffTable::class);

        //Lấy thông tin phiếu thu
        $item = $this->receipt->getInfoByDetail($id);

        $object_accounting_name = null;

        switch ($item['object_accounting_type_code']) {
            case 'OAT_CUSTOMER':
                //Khách hàng
                $info = $mCustomer->getInfoById($item['object_accounting_id']);

                $object_accounting_name = $info['full_name'];
                break;
            case 'OAT_SUPPLIER':
                //Nhà cung cấp
                $info = $mSupplier->getInfo($item['object_accounting_id']);

                $object_accounting_name = $info['supplier_name'];
                break;
            case 'OAT_EMPLOYEE':
                //Nhân viên
                $info = $mStaff->getInfo($item['object_accounting_id']);

                $object_accounting_name = $info['full_name'];
                break;
            default:
                $object_accounting_name = $item['object_accounting_name'];
        }

        $item['object_accounting_name'] = $object_accounting_name;


        //Lấy chi tiết phiếu thu
        $detail = $receiptDetail->getInfoDetail($id);


        return [
            'item' => $item,
            'detail' => $detail
        ];
    }

    /**
     * Xoá phiếu thu
     *
     * @param $input
     * @return array|mixed
     */
    public function delete($input)
    {
        DB::beginTransaction();
        try {
            $receiptId = $input['receiptId'];

            //Lấy thông tin phiếu thu
            $info = $this->receipt->getReceiptInfo($receiptId);

            if ($info['type_insert'] == 'manual') {
                //Phiếu thu tạo bằng tay thì xoá phiếu thu bình thường
                $this->receipt->edit(['is_deleted' => 1, 'status' => 'cancel'], $receiptId);
            } else {
                //Phiếu thu tạo tự động

                switch ($info['receipt_type_code']) {
                    case "RTC_ORDER":
                        //Xoá phiếu thu của đơn hàng
                        $this->_removeReceiptOrder($info);
                        break;
                    case "RTC_DEBT":
                        //Xoá phiếu thu của công nợ
                        $this->_removeReceiptDebt($info);
                        break;
                }
            }

//            if (isset($input['orderId'])) {
//                $orderId = $input['orderId'];
//                $this->receipt->removeOrderReceipt(['is_deleted' => 1], $orderId);
//                $this->customerDept->removeOrderCustomerDept(['is_deleted' => 1, 'status' => 'cancel'], $orderId);
//                $this->order->edit(['process_status' => 'new'], $orderId);
//            } else {
//                $this->receipt->edit(['is_deleted' => 1], $receiptId);
//            }


            DB::commit();

            return [
                'error' => false,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Xoá thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

    /**
     * Function xử lý xoá phiếu thu của đơn hàng
     *
     * @param $infoReceipt
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function _removeReceiptOrder($infoReceipt)
    {
        $mReceipt = app()->get(ReceiptTable::class);
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        $mOrder = app()->get(OrderTable::class);
        $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mCustomerDebt = app()->get(CustomerDebtTable::class);

        //Lấy tất cả phiếu thu của đơn hàng trừ phiếu thu này ra
        $getReceiptOrder = $mReceipt->getReceiptByObject($infoReceipt['receipt_id'], self::TYPE_ORDER, $infoReceipt['object_accounting_id']);

        $statusOrder = "new";

        //Check nếu có những phiếu thu khách rồi thì update status đơn hàng thanh toán 1 phần - chưa thì update status = mới
        if (count($getReceiptOrder) > 0) {
            $statusOrder = "pay-half";
        }

        //Xoá phiếu thu
        $this->receipt->edit(['is_deleted' => 1, 'status' => 'cancel'], $infoReceipt['receipt_id']);
        //Cập nhật trạng thái đơn hàng
        $mOrder->edit([
            'process_status' => $statusOrder
        ], $infoReceipt['object_accounting_id']);

        //Lấy chi tiết phiếu thu
        $getReceiptDetail = $mReceiptDetail->getInfoDetail($infoReceipt['receipt_id']);

        if (count($getReceiptDetail) > 0) {
            foreach ($getReceiptDetail as $v) {
                if ($v['payment_method_code'] == "MEMBER_MONEY") {
                    //Lấy thông tin khách hàng
                    $infoCustomer = $mCustomer->getInfoById($infoReceipt['customer_id']);

                    //Cộng điểm lại cho khách hàng
                    $mCustomer->edit([
                        'account_money' => $infoCustomer['account_money'] + $v['amount']
                    ], $infoReceipt['customer_id']);

                    //Insert log cộng lại tiền cho khách
                    $mBranchMoneyLog->add([
                        'customer_id' => $infoReceipt['customer_id'],
                        'branch_id' => Auth()->user()->branch_id,
                        'source' => 'member_money',
                        'type' => 'plus',
                        'money' => $v['amount'],
                        'screen' => 'order',
                        'screen_object_code' => $infoReceipt['order_code']
                    ]);
                }
            }
        }

        //Kiểm tra đơn hàng này có công nợ không
        $getDebtByOrder = $mCustomerDebt->getDebtByOrder($infoReceipt['object_accounting_id']);

        if ($getDebtByOrder != null) {
            //Xoá công nợ của đơn hàng
            $mCustomerDebt->edit([
                'status' => 'cancel',
                'is_deleted' => 1
            ], $getDebtByOrder['customer_debt_id']);

            //Xoá phiếu thu của công nợ này
            $mReceipt->removeReceiptByDebt([
                'status' => "cancel",
                "is_deleted" => 1
            ], $getDebtByOrder['customer_debt_id']);
        }

        //Trừ điểm đã được công từ phiếu thu này
        $this->__subtractPointReceipt($infoReceipt['customer_id'], $infoReceipt['order_id'], $infoReceipt['receipt_id']);
    }

    /**
     * Function xử lý xoá phiếu thu của công nợ
     *
     * @param $infoReceipt
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function _removeReceiptDebt($infoReceipt)
    {
        $mReceipt = app()->get(ReceiptTable::class);
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        $mCustomerDebt = app()->get(CustomerDebtTable::class);
        $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mOrder = app()->get(OrderTable::class);

        //Xoá phiếu thu
        $this->receipt->edit(['is_deleted' => 1, 'status' => 'cancel'], $infoReceipt['receipt_id']);

        if ($infoReceipt['object_id'] == 0 || $infoReceipt['object_id'] == null) {
            //Thanh toan nhanh cong no

            $mReceiptDebtMap = app()->get(ReceiptDebtMapTable::class);
            //Lay thong tin cong no thanh toan cho dot thu nay
            $getDebtMap = $mReceiptDebtMap->getDebtMapByReceipt($infoReceipt['receipt_id']);

            if (count($getDebtMap) > 0) {
                foreach ($getDebtMap as $v) {
                    $statusDebt = "unpaid";

                    if ($v['amount_paid'] - $v['amount_paid_turn'] > 0) {
                        $statusDebt = "part-paid";
                    }

                    //Cap nhat lai cong no
                    $mCustomerDebt->edit([
                        'status' => $statusDebt,
                        'amount_paid' => $v['amount_paid'] - $v['amount_paid_turn']
                    ], $v['customer_debt_id']);
                }
            }
        } else {
            //Xu ly Thanh toan don hang

            //Lấy tất cả phiếu thu của công nợ trừ phiếu thu này ra
            $getReceiptDebt = $mReceipt->getReceiptByObject($infoReceipt['receipt_id'], self::TYPE_DEBT, $infoReceipt['object_accounting_id']);

            $statusDebt = "unpaid";
            //Check nếu có những phiếu thu khác rồi thì update status công nợ thanh toán 1 phần - chưa thì update status = chưa thanh to
            if (count($getReceiptDebt) > 0) {
                $statusDebt = "part-paid";
            }

            //Lấy thông tin công nợ
            $infoDebt = $mCustomerDebt->getCustomerDebt($infoReceipt['object_accounting_id']);

            //Cập nhật trạng thái công nợ, + lại tiền nợ của khách
            $mCustomerDebt->edit([
                'status' => $statusDebt,
                'amount_paid' => $infoDebt['amount_paid'] - $infoReceipt['amount_paid']
            ], $infoReceipt['object_accounting_id']);

            //Cập nhật lại trạng thái đơn hàng là chưa hoàn thành
            $mOrder->edit([
                'process_status' => "pay-half"
            ], $infoDebt['order_id']);

            //Lấy chi tiết phiếu thu
            $getReceiptDetail = $mReceiptDetail->getInfoDetail($infoReceipt['receipt_id']);

            if (count($getReceiptDetail) > 0) {
                foreach ($getReceiptDetail as $v) {
                    if ($v['payment_method_code'] == "MEMBER_MONEY") {
                        //Lấy thông tin khách hàng
                        $infoCustomer = $mCustomer->getInfoById($infoReceipt['customer_id']);

                        //Cộng điểm lại cho khách hàng
                        $mCustomer->edit([
                            'account_money' => $infoCustomer['account_money'] + $v['amount']
                        ], $infoReceipt['customer_id']);

                        //Insert log cộng lại tiền cho khách
                        $mBranchMoneyLog->add([
                            'customer_id' => $infoReceipt['customer_id'],
                            'branch_id' => Auth()->user()->branch_id,
                            'source' => 'member_money',
                            'type' => 'plus',
                            'money' => $v['amount'],
                            'screen' => 'order',
                            'screen_object_code' => $infoReceipt['order_code']
                        ]);
                    }
                }
            }

            //Trừ điểm đã được công từ phiếu thu này
            $this->__subtractPointReceipt($infoReceipt['customer_id'], $infoDebt['order_id'], $infoReceipt['receipt_id']);
        }
    }

    /**
     * Xử lý trừ điểm khi huỷ phiếu thu
     *
     * @param $customerId
     * @param $orderId
     * @param $receiptId
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function __subtractPointReceipt($customerId, $orderId, $receiptId)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mPointHistory = app()->get(PointHistoryTable::class);
        $mConfig = app()->get(ConfigTable::class);

        //Lấy thông tin KH
        $infoCustomer = $mCustomer->getInfoById($customerId);
        //Lấy điểm đã cộng từ phiếu thu
        $getPointHistory = $mPointHistory->getPlusPointByReceipt($orderId, $receiptId);

        $point = $getPointHistory['point'] ?? 0;

        $pointCustomer = $infoCustomer['point'] + floatval($point);
        $pointBalance = $infoCustomer['point_balance'] + floatval($point);

        //Cập nhật điểm cho KH
        $mCustomer->edit([
            'point' => $pointCustomer,
            'point_balance' => $pointBalance,
        ], $customerId);

        //Lưu history
        $pointHistoryId = $mPointHistory->add([
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'point' => floatval($point),
            'type' => 'subtract',
            'point_description' => __('Trừ điểm khi huỷ phiếu thu'),
            'object_id' => $receiptId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        //Lấy cấu hình có reset rank trực tiếp không
        $reset = $mConfig->getConfig('reset_member_ranking');

        //Reset rank trực tiếp
        if ($reset['value'] == 0) {
            $mMemberLevel = app()->get(MemberLevelTable::class);

            //Lấy cấu hình rank hạng của hệ thống
            $getMemberLevel = $mMemberLevel->getMemberLevel();

            $level = 1;

            if (count($getMemberLevel) > 0) {
                foreach ($getMemberLevel as $v) {
                    if (floatval($pointCustomer) >= floatval($v['point'])) {
                        $level = $v['member_level_id'];
                    }
                }
            }

            //Cập nhật hạng cho KH
            $mCustomer->edit([
                'member_level_id' => $level,
            ], $customerId);
        }
    }

    /**
     * Load option các đối tượng theo loại
     *
     * @param $input
     * @return mixed
     */
    public function loadOptionObjectAccounting($input)
    {
        $type = $input['objAccountingType'];
        $option = [];
        switch ($type) {
            case 'OAT_CUSTOMER':
                $mCustomer = new CustomerTable();
                $option = $mCustomer->getOption();
                break;
            case 'OAT_SUPPLIER':
                $mSupplier = new SupplierTable();
                $option = $mSupplier->getOption();
                break;
            case 'OAT_EMPLOYEE':
                $mStaff = new StaffTable();
                $option = $mStaff->getOption();
                break;
            default:
                break;
        }
        return $option->toArray();
    }

    /**
     * in bill
     *
     * @param $input
     * @return array
     */
    public function printBill($input)
    {
        try {
            // Lấy thông tin spa
            $mSpaInfo = new SpaInfoTable();
            $mPrintBillLog = new PrintBillLogTable();
            $spaInfo = $mSpaInfo->getItem(1);
            // Tách sdt theo dấu ,
            $arrPhoneSpa = explode(",", $spaInfo['phone']);
            $arrPhoneNew = [];
            if (count($arrPhoneSpa) > 0) {
                foreach ($arrPhoneSpa as $value) {
                    $arrPhoneNew[] = str_replace(' ', '', $value);
                }
            }
            $spaInfo['phone'] = $arrPhoneNew;
            // Lấy thông tin config bill
            $mConfigPrintBill = new ConfigPrintBillTable();
            $configPrintBill = $mConfigPrintBill->getItem(1);

            $mCustomer = app()->get(CustomerTable::class);
            $mSupplier = app()->get(SupplierTable::class);
            $mStaff = app()->get(StaffTable::class);
            // Lấy thông tin phiếu thu
            $receiptId = $input['print_receipt_id'];
            $receiptInfo = $this->receipt->getReceiptInfo($receiptId);

            $object_accounting_name = null;

            switch ($receiptInfo['object_accounting_type_code']) {
                case 'OAT_CUSTOMER':
                    //Khách hàng
                    $info = $mCustomer->getInfoById($receiptInfo['object_accounting_id']);

                    $object_accounting_name = $info['full_name'];
                    break;
                case 'OAT_SUPPLIER':
                    //Nhà cung cấp
                    $info = $mSupplier->getInfo($receiptInfo['object_accounting_id']);

                    $object_accounting_name = $info['supplier_name'];
                    break;
                case 'OAT_EMPLOYEE':
                    //Nhân viên
                    $info = $mStaff->getInfo($receiptInfo['object_accounting_id']);

                    $object_accounting_name = $info['full_name'];
                    break;
                default:
                    $object_accounting_name = $receiptInfo['object_accounting_name'];
            }

            $receiptInfo['object_accounting_name'] = $object_accounting_name;
            // Lấy thông tin người trả tiền
            $objectAccInfo = [
                'accounting_id' => 0,
                'accounting_name' => '',
            ]; // default
            // Lấy mã bill log lớn nhất
            $maxPrintBillLogId = $mPrintBillLog->getBiggestId();
            // Lấy số lần in bill
            $checkPrintBill = $mPrintBillLog->checkPrintBillOrder($receiptId);
            $printTime = 0;
            if ($checkPrintBill != null) {
                $printTime = count($checkPrintBill);
            }
            $printReply = '';
            if ($printTime > 0) {
                $printReply = '(In lại)';
            }
            switch ($receiptInfo['object_accounting_type_code']) {
                case 'OAT_CUSTOMER':
                    $mCustomer = new CustomerTable();
                    $objectAccInfo = $mCustomer->getItem($receiptInfo['object_accounting_id']);
                    break;
                case 'OAT_SUPPLIER':
                    $mSupplier = new SupplierTable();
                    $objectAccInfo = $mSupplier->getItem($receiptInfo['object_accounting_id']);
                    break;
                case 'OAT_EMPLOYEE':
                    $mStaff = new StaffTable();
                    $objectAccInfo = $mStaff->getItem($receiptInfo['object_accounting_id']);
                    break;
                case 'OAT_SHIPPER':
                case 'OAT_OTHER':
                    $objectAccInfo['accounting_name'] = $receiptInfo['object_accounting_name'];
                    break;
            }
            //Lấy thông tin chi nhánh của đơn hàng
            $mBranch = new BranchTable();
            $branchInfo = $mBranch->getItem(Auth()->user()->branch_id);
            if ($branchInfo != null) {
                // cắt hot line thành mảng
                $arrPhoneBranch = explode(",", $branchInfo['hot_line']);
                $strPhone = '';
                $temp = 0;
                $countPhoneBranch = count($arrPhoneBranch);
                if ($countPhoneBranch > 0) {
                    foreach ($arrPhoneBranch as $value) {
                        if ($temp < $countPhoneBranch - 1) {
                            $strPhone = $strPhone . str_replace(' ', '', $value) . ' - ';
                        } else {
                            $strPhone = $strPhone . str_replace(' ', '', $value);
                        }
                        $temp++;
                    }
                }
                $branchInfo['hot_line'] = $strPhone;
            }
            $template = 'payment::receipt.print.content-print';

            switch ($configPrintBill->template) {
                case 'k58':
                    $template = 'payment::receipt.print.template-k58';
                    break;
                case 'A5':
                    $template = 'payment::receipt.print.template--a5';
                    break;
                case 'A5-landscape':
                    $template = 'payment::receipt.print.template--a5';
                    break;
                case 'A4':
                    $template = 'payment::receipt.print.template-a4';
                    break;
            }
            $convertNumberToWords = $this->help->convertNumberToWords($receiptInfo['amount_paid']);

            return view($template, [
                'receipt' => $receiptInfo,
                'spaInfo' => $spaInfo,
                'cash' => 0,
                'visa' => 0,
                'transfer' => 0,
                'member_money' => 0,
                'configPrintBill' => $configPrintBill,
                'receiptId' => $receiptId,
                'printTime' => $printReply,
                'QrCode' => $receiptInfo['receipt_code'],
                'objectAccInfo' => $objectAccInfo,
                'STT' => $maxPrintBillLogId['id'],
                'convertNumberToWords' => $convertNumberToWords,
                'branchInfo' => $branchInfo,
                'text_total_amount_paid' => $this->convert_number_to_words(floatval($receiptInfo['amount_paid']))
            ]);
        } catch (\Exception $e) {
            // return view 404
            //            dd($e->getMessage() . $e->getLine());
        }
    }

    /**
     * Save log print bill
     *
     * @param $input
     * @return array|mixed
     */
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
     * Function đọc tiền tiếng việt
     *
     * @param $number
     * @return string
     */
    function convert_number_to_words($number)
    {
        $hyphen = ' ';
        $conjunction = ' ';
        $separator = ' ';
        $negative = __('âm') . ' ';
        $decimal = ' ' . __('phẩy') . ' ';
        $dictionary = array(
            0 => __('không'),
            1 => __('một'),
            2 => __('hai'),
            3 => __('ba'),
            4 => __('bốn'),
            5 => __('năm'),
            6 => __('sáu'),
            7 => __('bảy'),
            8 => __('tám'),
            9 => __('chín'),
            10 => __('mười'),
            11 => __('mười một'),
            12 => __('mười hai'),
            13 => __('mười ba'),
            14 => __('mười bốn'),
            15 => __('mười năm'),
            16 => __('mười sáu'),
            17 => __('mười bảy'),
            18 => __('mười tám'),
            19 => __('mười chín'),
            20 => __('hai mươi'),
            30 => __('ba mươi'),
            40 => __('bốn mươi'),
            50 => __('năm mươi'),
            60 => __('sáu mươi'),
            70 => __('bảy mươi'),
            80 => __('tám mươi'),
            90 => __('chín mươi'),
            100 => __('trăm'),
            1000 => __('nghìn'),
            1000000 => __('triệu'),
            1000000000 => __('tỷ'),
            1000000000000 => __('nghìn tỷ'),
            1000000000000000 => __('nghìn triệu triệu'),
            1000000000000000000 => __('tỷ tỷ')
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

    /**
     * Export excel ds phiếu thu
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function exportExcel($input)
    {
        $heading = [
            __('STT'),
            __('MÃ PHIẾU'),
            __('LOẠI PHIẾU'),
            __('ĐỐI TƯỢNG'),
            __('TÊN ĐỐI TƯỢNG'),
            __('TRẠNG THÁI'),
            __('SỐ TIỀN THU'),
            __('NGƯỜI TẠO'),
            __('NGÀY GHI NHẬN'),
            __('NGÀY THANH TOÁN')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];

        $mCustomer = app()->get(CustomerTable::class);
        $mSupplier = app()->get(SupplierTable::class);
        $mStaff = app()->get(StaffTable::class);

        //Lấy ds phiếu thu
        $getData = $this->receipt->getReceiptExportExcel([
            'search' => $input['search_export'],
            'status' => $input['status_export'],
            'created_at' => $input['created_at_export']
        ]);

        if (count($getData) > 0) {
            foreach ($getData as $k => $v) {
                $object_accounting_name = null;

                switch ($v['object_accounting_type_code']) {
                    case 'OAT_CUSTOMER':
                        //Khách hàng
                        $info = $mCustomer->getInfoById($v['object_accounting_id']);

                        $v['object_accounting_name'] = $info['full_name'];
                        break;
                    case 'OAT_SUPPLIER':
                        //Nhà cung cấp
                        $info = $mSupplier->getInfo($v['object_accounting_id']);

                        $v['object_accounting_name'] = $info['supplier_name'];
                        break;
                    case 'OAT_EMPLOYEE':
                        //Nhân viên
                        $info = $mStaff->getInfo($v['object_accounting_id']);

                        $v['object_accounting_name'] = $info['full_name'];
                        break;
                }

                if ($v['object_type'] != 'debt' && $v['order_id'] === 0) {
                    $objectType = $v['object_accounting_type_name'];
                    $objectName = $v['object_accounting_name'];
                } else if ($v['object_type'] == 'debt') {
                    $objectType = __('Công nợ');
                    $objectName = $v['customer_name_debt'];
                } else {
                    $objectType = __('Khách hàng');
                    $objectName = $v['customer_name'];
                }

                $status = "";

                switch ($v['status']) {
                    case 'unpaid':
                        $status = __('Chưa thanh toán');
                        break;
                    case 'part-paid':
                        $status = __('Thanh toán một phần');
                        break;
                    case 'paid':
                        $status = __('Đã thanh toán');
                        break;
                    case 'cancel':
                        $status = __('Hủy');
                        break;
                    case 'fail':
                        $status = __('Lỗi');
                        break;
                }

                $datePayment = "";

                if ($v['status'] == 'paid') {
                    $datePayment = Carbon::parse($v['updated_at'])->format('d/m/Y H:i');
                }

                $data[] = [
                    $k + 1,
                    $v['receipt_code'],
                    $v['receipt_type_name'],
                    $objectType,
                    $objectName,
                    $status,
                    number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    $v['staff_name'],
                    Carbon::parse($v['created_at'])->format('d/m/Y H:i'),
                    $datePayment
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'receipt.xlsx');
    }
}