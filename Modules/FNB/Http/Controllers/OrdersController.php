<?php

namespace Modules\FNB\Http\Controllers;

use App\Jobs\CheckMailJob;
use App\Jobs\SaveLogZns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Models\ContractMapOrderTable;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\CustomerAppointmentLogTable;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\CustomerBranchMoneyLogTable;
use Modules\Admin\Models\DeliveryCustomerAddressTable;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\FNB\Http\Api\SendNotificationApi;
use Modules\FNB\Models\DeliveryCostDetailTable;
use Modules\FNB\Models\DeliveryCostTable;
use Modules\FNB\Models\DeliveryTable;
use Modules\FNB\Models\InventoryInputDetailSerialTable;
use Modules\FNB\Models\InventoryInputDetailTable;
use Modules\FNB\Models\InventoryInputTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailTable;
use Modules\Admin\Models\OrderImageTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\FNB\Http\Api\BookingApi;
use Modules\FNB\Models\ConfigTable;
use Modules\FNB\Models\CustomerContactTable;
use Modules\FNB\Models\CustomerServiceCardTable;
use Modules\FNB\Models\CustomerTable;
use Modules\FNB\Models\OrderDetailSerialTable;
use Modules\FNB\Models\OrderDetailTable;
use Modules\FNB\Models\OrderLogTable;
use Modules\FNB\Models\OrderSessionSerialTable;
use Modules\FNB\Models\PaymentMethodTable;
use Modules\FNB\Models\PointHistoryTable;
use Modules\FNB\Models\ProductChildTable;
use Modules\FNB\Models\PromotionLogTable;
use Modules\FNB\Models\ReceiptDetailTable;
use Modules\FNB\Models\ReceiptOnlineTable;
use Modules\FNB\Models\ReceiptTable;
use Modules\FNB\Models\RoomTable;
use Modules\FNB\Models\ServiceCardList;
use Modules\FNB\Models\StaffsTable;
use Modules\FNB\Models\Voucher;
use Modules\FNB\Repositories\Branch\BranchRepositoryInterface;
use Modules\FNB\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\FNB\Repositories\ConfigPrintBill\ConfigPrintBillRepositoryInterface;
use Modules\FNB\Repositories\Customer\CustomerRepositoryInterface;
use Modules\FNB\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\FNB\Repositories\CustomerDebt\CustomerDebtRepositoryInterface;
use Modules\FNB\Repositories\CustomerServiceCard\CustomerServiceCardRepositoryInterface;
use Modules\FNB\Repositories\FNBCustomer\FNBCustomerRepositoryInterface;
use Modules\FNB\Repositories\FNBCustomerRequest\FNBCustomerRequestRepositoryInterface;
use Modules\FNB\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\FNB\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\FNB\Repositories\Order\OrderRepositoryInterface;
use Modules\FNB\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\FNB\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\FNB\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\FNB\Repositories\PrintBillLog\PrintBillLogRepositoryInterface;
use Modules\FNB\Repositories\Product\ProductRepositoryInterface;
use Modules\FNB\Repositories\ProductBranchPrice\ProductBranchPriceRepositoryInterface;
use Modules\FNB\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\FNB\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\FNB\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\FNB\Repositories\ReceiptDetail\ReceiptDetailRepositoryInterface;
use Modules\FNB\Repositories\Service\ServiceRepositoryInterface;
use Modules\FNB\Repositories\ServiceBranchPrice\ServiceBranchPriceRepositoryInterface;
use Modules\FNB\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\FNB\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Modules\FNB\Repositories\ServiceMaterial\ServiceMaterialRepositoryInterface;
use Modules\FNB\Repositories\SmsConfig\SmsConfigRepositoryInterface;
use Modules\FNB\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\FNB\Repositories\Staff\StaffRepositoryInterface;
use Modules\FNB\Repositories\Voucher\VoucherRepositoryInterface;
use Modules\FNB\Repositories\Warehouse\WarehouseRepositoryInterface;
use Nexmo\Message\Callback\Receipt;

class OrdersController extends Controller
{
    private $order;

    const LIVE = 1;


    public function __contruct(OrderRepositoryInterface $order){
        $this->order = $order;
    }

    /**
     * Danh sách
     */
    public function index(){
        $order = app()->get(OrderRepositoryInterface::class);
//        $data = $order->list(['orders$order_source_id' => 1]);
        $data = $order->list();

        return view('fnb::orders.index', [
            'LIST' => $data['list'],
            'receipt' => $data['receipt'],
            //            'receiptDetail' => $data['receiptDetail'],
            'FILTER' => $this->filters(),
        ]);
    }

    protected function filters()
    {
        $rBranch = app()->get(BranchRepositoryInterface::class);
        $optionBranch = $rBranch->getBranch();
        $groupCate = (['' => __('Chọn chi nhánh')]) + $optionBranch;
        return [
            'branches$branch_id' => [
                'data' => $groupCate
            ],
            'orders$process_status' => [
                'data' => [
                    '' => __('Trạng thái'),
                    'confirmed' => __('Đã xác nhận'),
                    'paysuccess' => __('Đã thanh toán'),
                    'pay-half' => __('Thanh toán con thiếu'),
                    'new' => __('Mới'),
                    'ordercancle' => __('Đã hủy')
                ]
            ]
        ];
    }

    /**
     * Danh sách đơn hàng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function list(Request $request)
    {
        $order = app()->get(OrderRepositoryInterface::class);
        $filter = $request->only([
            'page',
            'display',
            'search_type',
            'search',
            'branches$branch_id',
            'created_at',
            'orders$process_status',
            'receive_at_counter',
            'orders$customer_id'
        ]);

        if (isset($filter['orders$customer_id']) && $filter['orders$customer_id'] == null) {
            $filter['orders$order_source_id'] = 1;
        }

        $data = $order->list($filter);

        return view('fnb::orders.list', [
            'LIST' => $data['list'],
            'receipt' => $data['receipt'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Chi tiết
     */
    public function detail($id){
        $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
        $mReceipt = app()->get(ReceiptTable::class);
        $mOrderDetail = app()->get(OrderDetailRepositoryInterface::class);
        $mStaff = app()->get(StaffsTable::class);
        $mReceipt = app()->get(ReceiptTable::class);
        $rOrder = app()->get(OrderRepositoryInterface::class);

        //Lấy thông tin đơn hàng
        $order = $rOrder->getItemDetail($id);
        $list_table = $mOrderDetail->getItem($order['order_id']);
        $arr = [];
        $arrChild = [];
        foreach ($list_table as $key => $item) {
            $staffName = "";
            if ($item['staff_id'] != null && $item['staff_id'] != "") {
                $arrStaff = explode(",", $item['staff_id']);
                if (count($arrStaff) > 0) {
                    foreach ($arrStaff as $value) {
                        $staffInfo = $mStaff->getItem($value);
                        if ($staffName == "") {
                            $staffName = $staffInfo['full_name'];
                        } else {
                            $staffName = $staffName . ', ' . $staffInfo['full_name'];
                        }
                    }
                }
            }

            if (!isset($item['order_detail_id_parent'])){
                $arr[] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'object_image' => $item['object_image'],
                    'full_name' => $staffName,
                    'name_attribute' => isset($item['name_attribute']) ? $item['name_attribute'] : []
                ];
            } else {
                $arrChild[$item['order_detail_id_parent']][] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'object_image' => $item['object_image'],
                    'full_name' => $staffName
                ];
            }

        }
        $listOrderDetailSerial = $mOrderDetailSerial->getListSerialByOrderId($order['order_id']);

        if (count($listOrderDetailSerial) != 0) {
            $listOrderDetailSerial = collect($listOrderDetailSerial)->groupBy('order_detail_id');
        }

        $receipt = $mReceipt->getItem($id);

        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        //Lấy chi tiết thanh toán
        $list_receipt_detail = $mReceiptDetail->getListDetailByOrderId($order['order_id']);

        if ($order != null) {
            $mOrderImage = app()->get(OrderImageTable::class);
            $mContractMapOrder = app()->get(ContractMapOrderTable::class);
            $mConfig = app()->get(ConfigTable::class);

            //Lấy lịch sử thanh toán của đơn hàng
            $receiptOrder = $mReceipt->getReceiptByOrder($order['order_id']);
            //Lấy hình ảnh trước/sau khi sử dụng
            $orderImage = $mOrderImage->getOrderImage($order['order_code']);
            //Cờ cho tạo hợp đồng không
            $getContractMap = $mContractMapOrder->getContractMapOrder($order['order_code']);
            //Lấy cấu hình có module hợp đồng chưa
            $configContract = $mConfig->getInfoByKey('contract')['value'];

            $isCreateContract = 0;

            if ($getContractMap == null && $configContract == 1) {
                $isCreateContract = 1;
            }

            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);

            $detailAddress = $mCustomerContact->getDetailByCode($order['customer_contact_code']);

            return view('fnb::orders.detail-load', [
                'order' => $order,
                'oder_detail' => $arr,
                'order_detail_child' => $arrChild,
                'receipt' => $receipt,
                'receipt_detail' => $list_receipt_detail,
                'orderImage' => $orderImage,
                'isCreateContract' => $isCreateContract,
                'listOrderDetailSerial' => $listOrderDetailSerial,
                'detailAddress' => $detailAddress,
                'receiptOrder' => $receiptOrder
            ]);
        } else {
            return redirect()->route('fnb.orders');
        }
    }

    /**
     * Giao diện thanh toán sau
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function receipt(Request $request)
    {
        session()->forget('topping_product');
        session()->forget('table_selected');
        session()->forget('page_add');
        $id = $request->id;
        $paymentType = $request->type;
        $mConfig = new ConfigTable();
        $rOrder = \app()->get(OrderRepositoryInterface::class);
        $rOrderDetail = \app()->get(OrderDetailRepositoryInterface::class);
        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
        $rStaff = \app()->get(StaffRepositoryInterface::class);
        $rCustomer = \app()->get(CustomerRepositoryInterface::class);
        $rCustomerServiceCard = \app()->get(CustomerServiceCardRepositoryInterface::class);
        $rServiceBranchPrice = \app()->get(ServiceBranchPriceRepositoryInterface::class);
        $rCustomerDebt = \app()->get(CustomerDebtRepositoryInterface::class);

        $session = Carbon::now()->format('YmdHisu');

        //Lấy thông tin nv phục vụ
        $staff_technician = $rStaff->getStaffTechnician();
        $customer_default = $rCustomer->getCustomerOption();
        //Lấy thông tin đơn hàng
        $data_receipt = $rOrder->getItemDetail($id);
        session()->put('table_selected',$data_receipt['fnb_table_id']);

        $listOrderTable = $rOrder->viewListOrderTable($data_receipt['fnb_table_id'],$id);

        //Lấy thông tin chi tiết đơn hàng
        $order_detail = $rOrderDetail->getItem($id);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($data_receipt['customer_id'], $branchId, 'plus');
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($data_receipt['customer_id'], $branchId, 'subtract');

        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        //Lấy tiền thành viên
        $money_customer = $accountMoney > 0 ? $accountMoney : 0;

        //Lấy thẻ dịch vụ theo chi nhánh
        $list_card_active = $rCustomerServiceCard->loadCardMember($data_receipt['customer_id'], $branchId);

        $data = [];
        $data_detail = [];
        $data_detail_child = [];
        $dataSessionProduct = [];

        //        Lấy sách serial theo id đơn hàng
        $listSerialOrder = $rOrder->getListSerialOrder($id, $session);
        $staff = [];
        foreach ($list_card_active as $key => $item) {
            if ($item['expired_date'] == null) {
                if ($item['number_using'] == 0) {
                    $data[] = [
                        'customer_service_card_id' => $item['customer_service_card_id'],
                        'card_code' => $item['card_code'],
                        'card_name' => $item['name_code'],
                        'image' => $item['image'],
                        'number_using' => $item['number_using'],
                        'count_using' => __('Không giới hạn'),
                    ];
                } else {
                    if ($item['number_using'] > $item['count_using']) {
                        $data[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => $item['number_using'] - $item['count_using'],
                        ];
                    }
                }
            } else {
                if (date('Y-m-d', strtotime($item['expired_date'])) >= date('Y-m-d')) {
                    if ($item['number_using'] == 0) {
                        $data[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => __('Không giới hạn'),
                            'expired_date' => date('d/m/Y', strtotime($item['expired_date']))
                        ];
                    } else {
                        if ($item['number_using'] > $item['count_using']) {
                            $data[] = [
                                'customer_service_card_id' => $item['customer_service_card_id'],
                                'card_code' => $item['card_code'],
                                'card_name' => $item['name_code'],
                                'image' => $item['image'],
                                'number_using' => $item['number_using'],
                                'count_using' => $item['number_using'] - $item['count_using'],
                                'expired_date' => date('d/m/Y', strtotime($item['expired_date']))
                            ];
                        }
                    }
                }
            }
        }

        $n = 0;
        foreach ($order_detail as $item) {
            $staff['staff_id'] = $item['staff_id'];
            $staff['staff_name'] = $item['full_name'];
            if(!isset($item['order_detail_id_parent'])) {
                $data_detail[] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'product_id' => $item['product_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'max_quantity_card' => $rCustomerServiceCard->searchCard($item['object_code']),
                    'staff_id' => explode(',', $item['staff_id']),
                    'refer_id' => $item['refer_id'],
                    'is_change_price' => $item['is_change_price'],
                    'is_check_promotion' => $item['is_check_promotion'],
                    'inventory_management' => $item['inventory_management'],
                    'name_attribute' => isset($item['name_attribute']) ? $item['name_attribute'] : []
                ];
                $dataSessionProduct[$item['product_id'].'_'.$item['order_detail_id']]['product_attribute_id'] = json_decode($item['product_attribute_json']);
                $dataSessionProduct[$item['product_id'].'_'.$item['order_detail_id']]['note'] = $item['note'];
                $dataSessionProduct[$item['product_id'].'_'.$item['order_detail_id']]['topping'] = collect($order_detail)->where('order_detail_id_parent',$item['order_detail_id'])->pluck('object_id')->toArray();
                $n++;
            } else {
                $data_detail_child[$item['order_detail_id_parent']][] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'max_quantity_card' => $rCustomerServiceCard->searchCard($item['object_code']),
                    'staff_id' => explode(',', $item['staff_id']),
                    'refer_id' => $item['refer_id'],
                    'is_change_price' => $item['is_change_price'],
                    'is_check_promotion' => $item['is_check_promotion'],
                    'inventory_management' => $item['inventory_management']
                ];
            }

        }

        session()->put('topping_product',$dataSessionProduct);
        // Bổ sung option payment method
        $mPaymentMethod = new PaymentMethodTable();
        $optionPaymentMethod = $mPaymentMethod->getOption();

        $mConfig = new ConfigTable();
        //Lấy cấu hình thay đổi giá
        $customPrice = $mConfig->getInfoByKey('customize_price')['value'];
        $decimalQuantity = $mConfig->getInfoByKey('decimal_quantity')['value'];
        //Lấy option dịch vụ
        $optionService = $rServiceBranchPrice->getOptionService(Auth()->user()->branch_id);
        //Lấy option phòng
        $mRoom = new RoomTable();
        $optionRoom = [];
        foreach ($mRoom->getRoomOption() as $item) {
            $optionRoom[$item['room_id']] = $item['name'];
        }
        // sale : 1 1 0 1
        // bs: 0 1 0 1
        // thu ngân: 0 0 1 0

        $is_edit_full = 0;
        $is_edit_staff = 0;
        $is_payment_order = 0;
        if (in_array('is_payment_order', session('routeList'))) {
            $is_payment_order = 1;
        }
        if (in_array('is_edit_full', session('routeList'))) {
            $is_edit_full = 1;
        }
        if (in_array('is_edit_staff', session('routeList'))) {
            $is_edit_staff = 1;
        }

        $is_update_order = 0;
        if ($is_edit_full == 1) {
            $is_update_order = 1;
            $is_edit_staff = 1;
        }
        if ($is_edit_staff == 1) {
            $is_update_order = 1;
        }

        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy công nợ của KH
        $amountDebt = $rCustomerDebt->getItemDebt($data_receipt['customer_id']);

        $debt = 0;
        if (count($amountDebt) > 0) {
            foreach ($amountDebt as $item) {
                $debt += $item['amount'] - $item['amount_paid'];
            }
        }

        if (isset($data_receipt->process_status) && in_array($data_receipt->process_status, ['new', 'confirmed'])) {

            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);

            $detailAddress = $mCustomerContact->getDetailByCode($data_receipt['customer_contact_code']);

            $itemFee = null;
            if ($detailAddress != null) {
                $mDeliveryCostDetail = app()->get(DeliveryCostDetailTable::class);
                $mDeliveryCost = app()->get(DeliveryCostTable::class);
                $itemFee = $mDeliveryCostDetail->checkAddress($detailAddress['province_id'], $detailAddress['district_id']);
                if ($itemFee == null) {
                    $itemFee = $mDeliveryCost->checkAddressDefault();
                }
            }



            return view('fnb::orders.receipt-after', [
                'item' => $data_receipt,
                'order_detail' => $data_detail,
                'order_detail_child' => $data_detail_child,
                'data' => $data,
                'money' => $money_customer,
                'orderIdsss' => $id,
                'staff_technician' => $staff_technician,
                'customer_refer' => $customer_default,
                'optionPaymentMethod' => $optionPaymentMethod,
                'customPrice' => $customPrice,
                'optionStaff' => $staff_technician,
                'optionService' => $optionService,
                'optionRoom' => $optionRoom,
                'configToDate' => $configToDate,
                'is_payment_order' => $is_payment_order,
                'is_edit_full' => $is_edit_full,
                'is_edit_staff' => $is_edit_staff,
                'is_update_order' => $is_update_order,
                'listSerialOrder' => $listSerialOrder,
                'session' => $session,
                'paymentType' => $paymentType,
                'detailAddress' => $detailAddress,
                'itemFee' => $itemFee,
                'debt' => $debt,
                'staff' => $staff,
                'listOrderTable' => $listOrderTable,
                'decimalQuantity' => $decimalQuantity
            ]);
        } else {
            return redirect()->route('fnb.orders');
        }
    }

    /**
     * Xóa
     */
    public function remove(Request $request){
        $view = \View::make('fnb::orders.modal-cancel', [
            'order_id' => $request->order_id,
        ])->render();
        return response()->json([
            'view' => $view
        ]);
    }

    /**
     * Export
     */
    public function exportList(Request $request){
        $rOrder = app()->get(OrderRepositoryInterface::class);
        $params = $request->all();
        return $rOrder->exportList($params);
    }

    public function addOrders(Request $request){
        session()->forget('topping_product');
        session()->forget('table_selected');
        session()->forget('page_add');
        session()->put('page_add',true);
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->addOrders($request->all());

        return view('fnb::orders.add',$data);
    }
    public function noteOrders(){
        return view('fnb::orders.popup.note');
    }

    /**
     * Chọn sản phẩm/ khu vực - bàn
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseType(Request $request)
    {
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->chooseType($request->all());

        return response()->json($data);
    }

    public function listAdd(Request $request){
        $param = $request->all();

        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->listAddAction($param);

        return response()->json($data);
    }

    /**
     * Hiển thị popup chọn topping
     */
    public function selectTopping(Request $request){
        $param = $request->all();

        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->selectTopping($param);

        return response()->json($data);
    }

    /**
     * Lưu cấu hình topping
     * @param Request $request
     */
    public function saveToppingSelect(Request $request){
        $param = $request->all();

        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->saveToppingSelect($param);

        return response()->json($data);
    }

    public function changeToppingSelect(Request $request){
        $param = $request->all();

        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->changeToppingSelect($param);

        return response()->json($data);
    }

    public function submitOrUpdate(Request $request){
        $param = $request->all();

        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->submitOrUpdate($param);

        return $data;
    }

    /**
     * Chọn nhân viên phục vụ
     */
    public function chooseWaiter(Request $request){
        $param = $request->all();

        $staff = app()->get(StaffRepositoryInterface::class);
        $data = $staff->chooseWaiter($param);

        return response()->json($data);
    }

    /**
     * Xóa session lưu sản phẩm được chọn
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function removeSessionProduct(Request $request){
        $param = $request->all();

        $rOrder = app()->get(OrderRepositoryInterface::class);
        $data = $rOrder->removeSessionProduct($param);

        return response()->json($data);
    }

    /**
     * Lưu session bàn đã chọn
     */
    public function saveSessionTable(Request $request){
        $param = $request->all();

        $rOrder = app()->get(OrderRepositoryInterface::class);
        $data = $rOrder->saveSessionTable($param);

        return response()->json($data);
    }

    /**
     * Xóa đơn hàng
     * @param Request $request
     */
    public function removeOrder(Request $request){
        $param = $request->all();

        $rOrder = app()->get(OrderRepositoryInterface::class);
        $data = $rOrder->removeOrder($param);

        return response()->json($data);
    }

    /**
     * Thêm mới đơn hàng và thanh toán trực tiếp
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submitAddReceipt(Request $request)
    {

        DB::beginTransaction();
        try {

            if (!isset($request->table_id)){
                $request->table_id = 1;
//                return response()->json([
//                    'error' => false,
//                    'message' => __('Vui lòng chọn bàn')
//                ]);
            }

            $order = app()->get(OrderRepositoryInterface::class);
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mOrderLog = new OrderLogTable();
            $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
            $mStaff = app()->get(StaffsTable::class);
            $rVoucher = app()->get(VoucherRepositoryInterface::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $rProductChild = app()->get(ProductChildRepositoryInterface::class);
            $session = $request->sessionSerial;

            $sessionProduct = [];
            if (session()->has('topping_product')){
                $sessionProduct = session()->get('topping_product');
            };

            $mStaff = new StaffsTable();

            //Lấy số lẻ decimal
            $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
            $id_order = $request->order_id;
            $orderCode = $request->order_code;
            $staff_branch = $mStaff->getItem(Auth::id());
            $detailAddress = null;
            $mCustomerContact = app()->get(CustomerContactTable::class);

            $checkQuantityOrderDetail = $this->checkQuantityOrderDetail($id_order);

            if ($checkQuantityOrderDetail['error'] == false) {
                return response()->json([
                    'error' => $checkQuantityOrderDetail['error'],
                    'message' => $checkQuantityOrderDetail['message']
                ]);
            }

            //Chỉnh sửa đơn hàng
            $update = $order->edit([
                'process_status' => 'paysuccess',
                'receive_at_counter' => $request->receipt_info_check == 1 ? 0 : 1,
                'type_time' => $request->receipt_info_check == 1 ? $request->type_time : '',
                'time_address' => $request->receipt_info_check == 1 && $request->time_address != '' ? Carbon::createFromFormat('d/m/Y', $request->time_address)->format('Y-m-d') : '',
                'tranport_charge' => $request->tranport_charge,
                'type_shipping' => $request->delivery_type,
                'delivery_cost_id' => $request->delivery_cost_id,
                'fnb_table_id' => $request->table_id,
                'fnb_customer_id' => $request->customer_id,
            ], $id_order);

            //
            if ($request->voucher_bill != null) {
                $get = $rVoucher->getCodeItem($request->voucher_bill);
                $data = [
                    'total_use' => ($get['total_use'] + 1)
                ];

                $rVoucher->editVoucherOrder($data, $request->voucher_bill);
            }

            $list_card_print = [];
            $arrPromotionLog = [];
            $arrQuota = [];
            $arrObjectBuy = [];
            $arrRemindUse = [];
            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $rSpaInfo = app()->get(SpaInfoRepositoryInterface::class);
            $rCustomerDebt = app()->get(CustomerDebtRepositoryInterface::class);
            $mProductChild = app()->get(ProductChildTable::class);
            $orderDetail = app()->get(OrderDetailTable::class);
            $tmpSerialLog = [];
            $day_code = date('dmY');
            if ($request->table_add != null) {
                $mOrderDetail->remove($id_order);
                $aData = $request->table_add;
                foreach ($aData as $key => $value) {
                    $value['amount'] = str_replace(',', '', $value['amount']);
                    $isChangePrice = isset($value['is_change_price']) ? $value['is_change_price'] : 0;
                    $isCheckPromotion = isset($value['is_check_promotion']) ? $value['is_check_promotion'] : 0;
                    $productCode = $value['object_code'];
                    $listSerialLog = [];

                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        if ($isCheckPromotion == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['product_child_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => 1,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }
                        //Lấy array nhắc sử dụng lại
                        $arrRemindUse[] = [
                            'object_type' => $value['object_type'],
                            'object_id' => $value['product_child_id'],
                            'object_code' => $value['object_code'],
                            'object_name' => $value['product_name'],
                        ];
                    }
                    if ($value['object_type'] == 'product') {
                        $data_order_detail = [
                            'order_id' => $id_order,
                            'object_id' => $value['product_child_id'],
                            'object_name' => $value['product_name'],
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'discount' => str_replace(',', '', $value['discount']),
                            'voucher_code' => $value['voucher_code'],
                            'amount' => str_replace(',', '', $value['amount']),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'staff_id' => isset($request->staff_id) ? $request->staff_id : null,
                            'is_change_price' => $isChangePrice,
                            'is_check_promotion' => $isCheckPromotion,
                            'note' => isset($sessionProduct[$value['product_id'].'_'.$value['key_string']]) ? $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note'] : null
                        ];
                        $id_detail = $mOrderDetail->add($data_order_detail);

                        if(isset($sessionProduct[$value['product_id'].'_'.$value['key_string']])){
                            $arrProductChildTmp = $sessionProduct[$value['product_id'].'_'.$value['key_string']]['topping'];
                            if (count($arrProductChildTmp) != 0){
                                $listProductChild = $mProductChild->getProductChildInId($arrProductChildTmp);
                                foreach ($listProductChild as $itemChildProduct){
                                    $data_order_detail_child = [
                                        'order_id' => $id_order,
                                        'object_id' => $itemChildProduct['productId'],
                                        'object_name' => $itemChildProduct['productName'],
                                        'object_type' => $value['object_type'],
                                        'object_code' => $value['object_code'],
                                        'price' => 0,
                                        'quantity' => 1,
                                        'discount' => 0,
                                        'voucher_code' => '',
                                        'amount' => 0,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                                        'staff_id' => isset($request->staff_id) ? $request->staff_id : null,
                                        'is_change_price' => $isChangePrice,
                                        'is_check_promotion' => $isCheckPromotion,
                                        'order_detail_id_parent' => $id_detail,
                                        'note' => $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note']
                                    ];

                                    $orderDetail->add($data_order_detail_child);
                                }
                            }
                        }

                        //Lấy hoa hồng sản phẩm
                        $check_commission = $rProductChild->getItem($value['product_child_id']);
                        $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;


                        // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                        $order->calculatedCommission($value['quantity'], $request->refer_id, $check_commission, $id_detail, $value['product_name'], null, str_replace(',', '', $value['amount']), $request->staff_id);

                        //                        Kiểm tra serial của đơn hàng đã được tạo hay chưa
//                        $checkOrderSerial = $mOrderDetailSerial->getListSerialByOrder($id_order, $productCode);
//                        if (count($checkOrderSerial) == 0) {
//                            $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $position, $productCode);
//                            foreach ($listSerialLog as $itemSerialLog) {
//                                $tmpSerialLog[] = [
//                                    'order_id' => $id_order,
//                                    'order_detail_id' => $id_detail,
//                                    'product_code' => $productCode,
//                                    'serial' => $itemSerialLog['serial'],
//                                    'created_at' => Carbon::now(),
//                                    'updated_at' => Carbon::now(),
//                                    'created_by' => Auth::id(),
//                                    'updated_by' => Auth::id()
//                                ];
//                            }
//                        }
                    }

                    if ($value['voucher_code'] != null) {
                        $get = $rVoucher->getCodeItem($value['voucher_code']);
                        $data = [
                            'total_use' => ($get['total_use'] + 1)
                        ];
                        $rVoucher->editVoucherOrder($data, $value['voucher_code']);
                    }
                    if (in_array($value['object_type'], ['service_gift', 'product_gift', 'service_card_gift'])) {
                        $data_order_detail = [
                            'order_id' => $id_order,
                            'object_id' => $value['product_child_id'],
                            'object_name' => $value['product_name'],
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'discount' => str_replace(',', '', $value['discount']),
                            'voucher_code' => $value['voucher_code'],
                            'amount' => str_replace(',', '', $value['amount']),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'staff_id' => $value['staff_id'] != null ? implode(',', $value['staff_id']) : null,
                            'refer_id' => $request->refer_id
                        ];
                        $mOrderDetail->add($data_order_detail);
                    }
                }

                if (count($tmpSerialLog) != 0) {
                    $mOrderDetailSerial->insertSerial($tmpSerialLog);
                }
            } else {
                return response()->json([
                    'table_error' => 1
                ]);
            }

            //Insert order log đơn hàng mới, hoàn tất
            $mOrderLog->insert([
                [
                    'order_id' => $id_order,
                    'created_type' => 'backend',
                    'status' => 'new',
                    //                    'note' => __('Đặt hàng thành công'),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Đặt hàng thành công',
                    'note_en' => 'Order success',
                ],
                [
                    'order_id' => $id_order,
                    'created_type' => 'backend',
                    'status' => 'ordercomplete',
                    //                    'note' => __('Hoàn tất'),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Hoàn tất',
                    'note_en' => 'Order completed',
                ]
            ]);

            if (isset($request->custom_price) && $request->custom_price == 1) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $mOrderApp->plusQuotaUsePromotion($arrQuota);
            }

            // amount bill, amount return, receipt type, order id,
            $amount_bill = str_replace(',', '', $request->amount_bill);

            if ($request->amount_all != '') {
                $amount_receipt_all = str_replace(',', '', $request->amount_all);
                $amount_receipt_all > $amount_bill ? $amount_receipt_all = $amount_bill : $amount_receipt_all;
            } else {
                $amount_receipt_all = 0;
            }
            $amount_return = str_replace(',', '', $request->amount_return);
            $receipt_type = $request->receipt_type;
            $status = '';

            if ($amount_receipt_all >= $amount_bill) {
                $status = 'paid';
            } else {
                //Cập nhật trạng thái đơn hàng thanh toán còn thiếu
                $this->order->edit(['process_status' => 'pay-half'], $id_order);
            }

            if ($amount_bill != 0) {

                if ($amount_receipt_all < $amount_bill) {
                    //Check KH là hội viên
                    if ($request->customer_id != 1) {
                        //Check cấu hình thanh toán nhiều lần
                        $check_info = $rSpaInfo->getInfoSpa();
                        if ($check_info['is_part_paid'] == 1) {
                            $status = 'paid';
                            //insert customer debt
                            $data_debt = [
                                'customer_id' => $request->customer_id,
                                'debt_code' => 'debt',
                                'staff_id' => Auth::id(),
                                'branch_id' => Auth::user()->branch_id,
                                'note' => $request->note,
                                'debt_type' => 'order',
                                'order_id' => $id_order,
                                'status' => 'unpaid',
                                'amount' => $amount_bill - $amount_receipt_all,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ];
                            $debt_id = $rCustomerDebt->add($data_debt);
                            //update debt code
                            $day_code = date('dmY');
                            if ($debt_id < 10) {
                                $debt_id = '0' . $debt_id;
                            }
                            $debt_code = [
                                'debt_code' => 'CN_' . $day_code . $debt_id
                            ];
                            $rCustomerDebt->edit($debt_code, $debt_id);
                        } else {
                            return response()->json([
                                'amount_detail_small' => 1,
                                'message' => __('Số tiền không hợp lệ')
                            ]);
                        }
                    } else {
                        return response()->json([
                            'amount_detail_small' => 1,
                            'message' => __('Số tiền không hợp lệ')
                        ]);
                    }
                }
            }

            $mReceipt = new ReceiptTable();
            $mReceiptDetail = new ReceiptDetailTable();
            $mCustomer = app()->get(CustomerTable::class);
            // get receipt by order id => remove receipt and receipt detail
            $dataReceipt = $mReceipt->getItem($id_order);
            // no receipt by order_id but $dataReceipt still have data (data with all null field) ?????
            if ($dataReceipt != null && isset($dataReceipt['receipt_id']) && $dataReceipt['receipt_id'] != null) {
                $mReceipt->removeReceipt($id_order);
                $mReceiptDetail->removeReceiptDetail($dataReceipt['receipt_id']);
            }
            $data_receipt = [
                'customer_id' => $request->customer_id,
                'staff_id' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'object_id' => $id_order,
                'object_type' => 'order',
                'order_id' => $id_order,
                'total_money' => $amount_receipt_all,
                'voucher_code' => $request->voucher_bill,
                'status' => $status,
                'is_discount' => 1,
                'amount' => $amount_receipt_all,
                'amount_paid' => $amount_receipt_all,
                'amount_return' => $amount_return,
                'note' => $request->note,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => $orderCode, // order code
                'object_accounting_id' => $id_order, // order id
            ];
            if ($request->voucher_bill != null) {
                $data_receipt['discount'] = $request->discount_bill;
            } else {
                $data_receipt['custom_discount'] = $request->discount_bill;
            }

            $receipt_id = $mReceipt->add($data_receipt);

            $day_code = date('dmY');
            $data_code = [
                'receipt_code' => 'TT_' . $day_code . $receipt_id
            ];
            $mReceipt->edit($data_code, $receipt_id);

            if ($request->table_add != null) {
//                $aData = array_chunk($request->table_add, 15, false);
                $aData = $request->table_add;
                foreach ($aData as $key => $value) {
                    if ($value['object_type'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $receipt_id,
                            'cashier_id' => Auth::id(),
                            //                            'receipt_type' => 'member_card',
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $value['object_code'],
                            'amount' => $value['amount'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $mReceiptDetail->add($data_receipt_detail);
                    }
                }
            }

            // Chi tiết thanh toán
            $arrMethodWithMoney = $request->array_method;
            $arrVnPay = null;

            $mReceiptOnline = new ReceiptOnlineTable();
            $mPaymentMethod = new \Modules\Payment\Models\PaymentMethodTable();

            $isNotifyMinAccount = 0;

            foreach ($arrMethodWithMoney as $methodCode => $money) {
                $itemMethod = $mPaymentMethod->getPaymentMethodByCode($methodCode);
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $receipt_id,
                        'cashier_id' => Auth::id(),
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amount_bill) { // trừ tiên thành viên
                            if ($money < $request->member_money) {
                                //Thêm chi tiết thanh toán
                                $mReceiptDetail->add($dataReceiptDetail);
                                //Lấy thông tin KH
                                $customerMoney = $mCustomer->getItem($request->customer_id);
                                //Chỉnh sửa thông tin KH
                                $this->customer->edit([
                                    'account_money' => $customerMoney['account_money'] - $money
                                ], $request->customer_id);

                                $mConfig = app()->get(ConfigTable::class);
                                //Lấy cấu hình số tiền tối thiểu
                                $configMinAccount = $mConfig->getInfoByKey('money_account_min')['value'];

                                if (($customerMoney['account_money'] - $money) <= $configMinAccount) {
                                    $isNotifyMinAccount = 1;
                                }

                                //Lưu log - tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $request->customer_id,
                                    "branch_id" => Auth()->id(),
                                    "source" => "member_money",
                                    "type" => 'subtract',
                                    "money" => $money,
                                    "screen" => 'order',
                                    "screen_object_code" => $orderCode
                                ]);
                            } else {
                                return response()->json([
                                    'error_account_money' => 1,
                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                    'money' => $request->member_money
                                ]);
                            }
                        } else {
                            return response()->json([
                                'money_large_moneybill' => 1,
                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                            ]);
                        }
                    } elseif ($methodCode == 'VNPAY') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $receipt_id,
                            'status' => 'success'
                        ], 'order', $id_order, $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // get receipt_online of method/order
                        $dataReceiptOnline = $mReceiptOnline->getReceiptOnlineByTypeAndOrderId('order', $id_order, $methodCode);
                        if ($dataReceiptOnline != null) {
                            // update status, receipt_id of receipt_online
                            $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                                'amount_paid' => $money,
                                'receipt_id' => $receipt_id,
                                'status' => 'success'
                            ], 'order', $id_order, $methodCode);
                        } else {
                            // create status, receipt_id of receipt_online
                            $dataReceiptOnline = [
                                'receipt_id' => $receipt_id,
                                'object_type' => 'order',
                                'object_id' => $id_order,
                                'object_code' => $orderCode,
                                'payment_method_code' => $methodCode,
                                'amount_paid' => $money,
                                'payment_time' => Carbon::now(),
                                'status' => 'success',
                                'performer_name' => $staff_branch['name'],
                                'performer_phone' => $staff_branch['phone1'],
                                'type' => $itemMethod['payment_method_type'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            $mReceiptOnline->createReceiptOnline($dataReceiptOnline);
                        }
                    } else {
                        $mReceiptDetail->add($dataReceiptDetail);
                    }
                }
            }

            $rServiceCardList = \app()->get(ServiceCardListRepositoryInterface::class);
            $rServiceCard = \app()->get(ServiceCardRepositoryInterface::class);
            $rWarehouse = \app()->get(WarehouseRepositoryInterface::class);
            $rInventoryOutput = \app()->get(InventoryOutputRepositoryInterface::class);
            $rCode = \app()->get(CodeGeneratorRepositoryInterface::class);
            $data_print = [];
            if (count($list_card_print) > 0) {
                foreach ($list_card_print as $k => $v) {
                    $get_cus_card = $rServiceCardList->searchCard($v);

                    $get_sv_card = $rServiceCard->getServiceCardInfo($get_cus_card['service_card_id']);

                    $data_print[] = [
                        'customer_id' => $request->customer_id,
                        'type' => $get_sv_card['service_card_type'],
                        'card_name' => $get_cus_card['card_name'],
                        'card_code' => $get_cus_card['code'],
                        'number_using' => $get_sv_card['number_using'],
                        'date_using' => $get_sv_card['date_using'],
                        'money' => $get_sv_card['money'],
                        'service_card_id' => $get_sv_card['service_card_id'],
                    ];
                }
            }

            //TẠO PHIẾU XUẤT KHO
            $listOrderProduct = $mOrderDetail->getValueByOrderIdAndObjectType($id_order, 'product');
            $listService = $mOrderDetail->getValueByOrderIdAndObjectType($id_order, 'service');
            $listServiceMaterials = [];

            //check có sp đi kèm ko
            $isCheckProductAttach = false;

            if (count($listOrderProduct) > 0) {
                $checkWarehouse = $rWarehouse->getWarehouseByBranch(Auth::user()->branch_id);
                $warehouseId = 0;
                foreach ($checkWarehouse as $item) {
                    if ($item['is_retail'] == 1) {
                        $warehouseId = $item['warehouse_id'];
                    }
                }
                $dataInventoryOutput = [
                    'warehouse_id' => $warehouseId,
                    'po_code' => 'XK',
                    'created_by' => Auth::user()->staff_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'success',
                    'note' => '',
                    'type' => 'retail',
                    'object_id' => $id_order
                ];

                $idInventoryOutput = $rInventoryOutput->add($dataInventoryOutput);
                $idCode = $idInventoryOutput;
                if ($idInventoryOutput < 10) {
                    $idCode = '0' . $idCode;
                }
                $rInventoryOutput->edit(['po_code' => $rCode->codeDMY('XK', $idCode)], $idInventoryOutput);
            }
            // Lấy thông tin bán âm
            $mConfig = new ConfigTable();
            $configSellMinus = $mConfig->getInfoByKey('sell_minus');
            $sellMinus = 1;
            $configSellMinus != null ? $sellMinus = $configSellMinus['value'] : $sellMinus = 1;
            // Danh sách sản phẩm

            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $rInventoryOutputDetail = \app()->get(InventoryOutputDetailRepositoryInterface::class);
            $rProductInventory = \app()->get(ProductInventoryRepositoryInterface::class);
            $rSmsConfig = \app()->get(SmsConfigRepositoryInterface::class);
            $bookingApi = \app()->get(BookingApi::class);
            $checkSerialQuantity = 0;
            $tmpListSerial = [];
            foreach ($listOrderProduct as $item) {
                //                kiểm tra mã sản phẩm đã được tạo trong phiếu xuất kho hay chưa
                $checkProductInventotyOutput = $mInventoryOutputDetail->checkProductInventotyOutput($idInventoryOutput, $item['object_code']);

                $getDetailOutputDetail = $mInventoryOutputDetail->checkInventoryOutput($idInventoryOutput, $item['object_code']);

                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $item['object_code'],
                    'quantity' => $getDetailOutputDetail != null ? (double)$getDetailOutputDetail['quantity'] + (double)$item['quantity'] : $item['quantity'],
                    'current_price' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['current_price'] + (float)$item['price'] : $item['price'],
                    'total' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['total'] + (float)$item['amount'] : $item['amount'],
                ];

                if ($getDetailOutputDetail != null) {
                    $idIOD = $getDetailOutputDetail['inventory_output_detail_id'];
                    $mInventoryOutputDetail->editDetail($idIOD, $dataInventoryOutputDetail);
                } else {
                    $idIOD = $rInventoryOutputDetail->add($dataInventoryOutputDetail);
                }

                if (count($checkProductInventotyOutput) == 0) {
                    //                Lấy danh sách serial theo sản phẩm ở đơn hàng

                    $listOrderSerialDetail = $mOrderDetailSerial->getListSerialByOrder($id_order, $item['object_code']);

                    if (count($listOrderSerialDetail) == 0) {
                        $listOrderSerialDetail = $mOrderSessionSerialLog->getListProductOrder(['session' => $request->sessionSerial, 'productCode' => $item['object_code']]);
                    }

                    if (count($listOrderSerialDetail) != 0 && $dataInventoryOutputDetail['quantity'] != count($listOrderSerialDetail)) {
                        $rInventoryOutput->edit(['status' => 'new'], $idInventoryOutput);
                        $checkSerialQuantity = 1;
                    }

                    $tmpOrderSerial = [];
                    //                Tạo danh sách serial xuất kho mới
                    foreach ($listOrderSerialDetail as $itemSerial) {
                        $tmpOrderSerial[] = [
                            'inventory_output_detail_id' => $idIOD,
                            'product_code' => $item['object_code'],
                            'serial' => $itemSerial['serial'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                        $tmpListSerial[] = $itemSerial['serial'];
                    }

                    //                Thêm danh sách serial vào kho mới
                    if (count($tmpOrderSerial) != 0) {
                        $mInventoryOutputDetailSerial->insertListSerial($tmpOrderSerial);
                    }
                }

                //Trừ tồn kho.
                //Lấy id của product child bằng code. is deleted=0.
                $productId = $rProductChild->getProductChildByCode($item['object_code'])['product_child_id'];
                $checkProductInventory = $rProductInventory->checkProductInventory($item['object_code'], $warehouseId);

                $quantityss = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;;
                // Nếu k cho bán âm thì kiểm tra số lượng trong kho có đủ cho đơn hàng không
                if ($sellMinus == 0 && $quantityss < 0) {
                    // Lấy tên sản phẩm
                    DB::rollback();
                    return response()->json([
                        'error' => false,
                        'message' => __("Trong kho không đủ sản phẩm ") . $productId
                    ]);
                }

                //                Update thêm kiểm tra nếu số lượng sản phẩm không trùng với tổng số serial theo sản phẩm thì không cập nhật sản phẩm trong kho
                //                $checkSerialQuantity với 0 là trùng , 1 là không trùng
                if ($productId != null && $checkSerialQuantity == 0) {
                    if ($checkProductInventory != null) {
                        $dataEditProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $item['object_code'],
                            'warehouse_id' => $warehouseId,
                            'export' => $item['quantity']
                                + $checkProductInventory['export'],
                            'quantity' => $quantityss,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::id(),
                        ];
                        $rProductInventory->edit(
                            $dataEditProductInventory,
                            $checkProductInventory['product_inventory_id']
                        );
                    } else {
                        if ($productId != null) {
                            $dataEditProductInventoryInsert = [
                                'product_id' => $productId,
                                'product_code' => $item['object_code'],
                                'warehouse_id' => $warehouseId,
                                'import' => 0,
                                'export' => $item['quantity'],
                                'quantity' => $quantityss,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ];
                            $rProductInventory->add($dataEditProductInventoryInsert);
                        }
                    }
                }
            }

            //            Thêm serial ở kho và cập nhật serial ở phiếu nhập kho
            if (count($tmpListSerial) != 0 && $checkSerialQuantity == 0) {
                $mInventoryInputDetailSerial->updateSerialOrder($tmpListSerial, ['is_export' => 1]);
                $mProductInventorySerial->updateByArrSerial($tmpListSerial, ['status' => 'export']);
            }

            if (count($listServiceMaterials) > 0) {
                foreach ($listServiceMaterials as $item) {
                    $dataInventoryOutputDetail = [
                        'inventory_output_id' => $idInventoryOutput,
                        'product_code' => $item['product_code'],
                        'quantity' => $item['quantity'],
                        'current_price' => $item['current_price'],
                        'total' => $item['total'],
                    ];
                    $idIOD = $rInventoryOutputDetail->add($dataInventoryOutputDetail);

                    //Trừ tồn kho.
                    $productId = $rProductChild->getProductChildByCode($item['product_code'])['product_child_id'];
                    $checkProductInventory = $rProductInventory->checkProductInventory($item['product_code'], $warehouseId);
                    $quantitysss = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;;
                    if ($productId != null) {
                        if ($checkProductInventory != null) {
                            $dataEditProductInventory = [
                                'product_id' => $productId,
                                'product_code' => $item['product_code'],
                                'warehouse_id' => $warehouseId,
                                'export' => $item['quantity'] + $checkProductInventory['export'],
                                'quantity' => $quantitysss,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'updated_by' => Auth::id(),
                            ];
                            $rProductInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
                        } else {
                            if ($productId != null) {
                                $dataEditProductInventoryInsert = [
                                    'product_id' => $productId,
                                    'product_code' => $item['product_code'],
                                    'warehouse_id' => $warehouseId,
                                    'import' => 0,
                                    'export' => $item['quantity'],
                                    'quantity' => $quantitysss,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id()
                                ];
                                $rProductInventory->add($dataEditProductInventoryInsert);
                            }
                        }
                    }
                }
            }
            // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
            if (isset($request->arrAppointment)) {
                $arrAppointment = $request->arrAppointment;
                if ($arrAppointment['checked'] == 1) {
                    // Thêm lịch hẹn
                    $result = $this->_addQuickAppointment($arrAppointment, $request->customer_id);
                    if ($result['error'] == false) {
                        return response()->json($result);
                    }
                }
            }
            // END UPDATE

            DB::commit();
            return response()->json([
                'error' => true,
                'message' => __('Thanh toán thành công'),
                'print_card' => $data_print,
                'orderId' => $id_order,
                'data' => [
                    'order_id' => $id_order,
                    'order_code' => $orderCode
                ], // data for chathub
                'isSMS' => 0,
            ]);


            // Thêm phiếu bảo hành điện tử
            $customer = $mCustomer->getItem($request->customer_id);
            $dataTableAdd = $request->table_add;
            if ($customer['customer_code'] != null) {
                $this->order->addWarrantyCard($customer['customer_code'], $id_order, $orderCode, $dataTableAdd);
            }

            $checkSendSms = $rSmsConfig->getItemByType('paysuccess');

            //Lưu log dự kiến nhắc sử dụng lại
            $this->order->insertRemindUse($id_order, $request->customer_id, $arrRemindUse);

            //Tính điểm thưởng khi thanh toán
            if ($amount_receipt_all >= $amount_bill) {
                $bookingApi->plusPointReceiptFull(['receipt_id' => $receipt_id]);
            } else {
                $bookingApi->plusPointReceipt(['receipt_id' => $receipt_id]);
            }

            DB::commit();

            // //Send notification
            if ($request->customer_id != 1) {
                //Gửi thông báo thanh toán thành công
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_S',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Cộng điểm khi mua hàng trực tiếp
                $mPlusPoint = new LoyaltyApi();
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $request->customer_id,
                    'rule_code' => 'order_direct',
                    'object_id' => $id_order
                ]);

                //Lưu log ZNS (thanh toán thành công)
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_thanks',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);

                if ($isNotifyMinAccount == 1) {
                    //Gửi thông báo tiền trong tài khoản sắp hết
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'money_account_min',
                        'customer_id' => $request->customer_id,
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }
            }
            //Thông báo NV khi có đơn hàng mới
            App\Jobs\FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'order_status_W',
                'customer_id' => $request->customer_id,
                'object_id' => $id_order,
                'branch_id' => Auth()->user()->branch_id,
                'tenant_id' => session()->get('idTenant')
            ]);


            return response()->json([
                'error' => true,
                'message' => __('Thanh toán thành công'),
                'print_card' => $data_print,
                'orderId' => $id_order,
                'data' => [
                    'order_id' => $id_order,
                    'order_code' => $orderCode
                ], // data for chathub
                'isSMS' => $checkSendSms['is_active'],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ]);
        }
    }

    public function checkQuantityOrderDetail($id_order)
    {
        $mOrderDetail = app()->get(OrderDetailTable::class);
        //            Lấy danh sách sản phẩm tồn kho so sánh

        $listProduct = $mOrderDetail->getListProductCheck($id_order);

        $errorMessage = '';
        $arrProductCode = [];

        foreach ($listProduct as $itemProductCheck) {
            if ($itemProductCheck['quantity'] > $itemProductCheck['product_quantity']) {
                if (!isset($arrProductCode[$itemProductCheck['product_child_code']])) {
                    $arrProductCode[$itemProductCheck['product_child_code']] = $itemProductCheck['product_child_code'];
                    $errorMessage = $errorMessage . __('Sản phẩm :product_child_name quản lý theo serial không cho phép bán âm', ['product_child_name' => $itemProductCheck['product_child_name']]) . '</br>';
                }
            }
        }



        if ($errorMessage != '') {
            return [
                'error' => false,
                'message' => $errorMessage
            ];
        } else {
            return [
                'error' => true,
                'message' => ''
            ];
        }
    }

    //Lấy thông tin để in hóa đơn.
    public function printBill(Request $request)
    {

        $id = $request->ptintorderid;
        $rOrder = \app()->get(OrderRepositoryInterface::class);
        $rOrderDetail = \app()->get(OrderDetailRepositoryInterface::class);
        $rReceipt = \app()->get(ReceiptRepositoryInterface::class);
        $rReceiptDetail = \app()->get(ReceiptDetailRepositoryInterface::class);
        $rProduct = \app()->get(ProductRepositoryInterface::class);
        $rConfigPrintBill = \app()->get(ConfigPrintBillRepositoryInterface::class);
        $rBranch = \app()->get(BranchRepositoryInterface::class);
        //Lấy q tin đơn hàng
        $order = $rOrder->getItemDetail($id);

        $lstReceipt = $rReceipt->getReceiptOrderList($id);
        $list_receipt_detail = $rReceiptDetail->getItemPaymentByOrder($id);
        $amount_paid = 0;
        $amount_return = 0;
        $totalDiscount = 0;
        foreach ($lstReceipt as $key => $objReceipt) {
            $amount_paid += $objReceipt['amount_paid'];
            $amount_return += $objReceipt['amount_return'];
            $totalDiscount += $objReceipt['discount'];
        }
        //Lấy chi tiết đơn hàng
        $list_table = $rOrderDetail->getItem($id);

        // $totalDiscount = $order['discount'];

        $arr = [];
        $arrChild = [];
        $totalQuantity = 0;
        $totalDiscountDetail = 0;
        foreach ($list_table as $key => $item) {
            $unitName = null;
            //Lấy đơn vị tính nếu là sản phẩm, sản phẩm quà tặng (object_type = product, product_gift)
            if ($item['object_type'] == 'product' || $item['object_type'] == 'product_gift') {
                $productInfo =$rProduct->getItem($item['object_id']);
                if ($productInfo != null) {
                    $unitName = $productInfo['unitName'];
                }
            }
//            $arr[] = [
//                'order_detail_id' => $item['order_detail_id'],
//                'object_id' => $item['object_id'],
//                'object_name' => $item['object_name'],
//                'object_type' => $item['object_type'],
//                'price' => $item['price'],
//                'quantity' => $item['quantity'],
//                'discount' => $item['discount'],
//                'amount' => $item['amount'],
//                'voucher_code' => $item['voucher_code'],
//                'object_code' => "XXXXXXXXXXXXXX" . substr($item['object_code'], -4),
//                'unit_name' => $unitName
//            ];
            $totalQuantity += (double)$item['quantity'];
            $totalDiscountDetail += $item['discount'];
            $totalDiscount += $item['discount'];

            if (!isset($item['order_detail_id_parent'])){
                $arr[] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'object_image' => $item['object_image'],
                    'name_attribute' => isset($item['name_attribute']) ? $item['name_attribute'] : []
//                    'full_name' => $staffName
                ];
            } else {
                $arrChild[$item['order_detail_id_parent']][] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'object_image' => $item['object_image'],
//                    'full_name' => $staffName
                ];
            }
        }
        //Lấy cấu hình in bill
        $configPrintBill = $rConfigPrintBill->getItem(1);

        isset($order['branch_id']) ? $order['branch_id'] : '';
        //Lấy thông tin chi nhánh của đơn hàng
        $branchInfo = $rBranch->getItem($order['branch_id']);
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
        } else {
            $branchInfo = [
                "branch_name" => "",
                "address" => "",
                "district_type" => "",
                "district_name" => "",
                "province_name" => "",
                "hot_line" => "",
            ];
        }
        //Template mặc định
        $template = 'fnb::orders.print-not-receipt.content-print';

        switch ($configPrintBill->template) {
            case 'k58':
                $template = 'fnb::orders.print-not-receipt.template-k58';
                break;
            case 'A5':
                $template = 'fnb::orders.print-not-receipt.template--a5';
                break;
            case 'A5-landscape':
                $template = 'fnb::orders.print-not-receipt.template--a5-landscape';
                break;
            case 'A4':
                $template = 'fnb::orders.print-not-receipt.template-a4';
                break;
            case 'k80':
                $template = 'fnb::orders.print-not-receipt.template-k80';
                break;
        }
        $rPrintBillLog = \app()->get(PrintBillLogRepositoryInterface::class);
        $rSpaInfo = \app()->get(SpaInfoRepositoryInterface::class);
        //Lấy số lần in bill của đơn hàng này
        $checkPrintBill = $rPrintBillLog->checkPrintBillOrder($order['order_code']);
        $printTime = count($checkPrintBill);
        $printReply = '';
        if ($printTime > 0) {
            $printReply = __('(In lại)');
        }
        $maxId = $rPrintBillLog->getBiggestId();
        // $convertNumberToWords = $this->help->convertNumberToWords($amount);

        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
        $mConfig = new ConfigTable();
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];
        $branchId = null;
        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($order['customer_id'], $branchId, 'plus');

        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($order['customer_id'], $branchId, 'subtract');
        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);

        return view($template, [
            'order' => $order,
            'oder_detail' => $arr,
            'order_detail_child' => $arrChild,
            'spaInfo' => $rSpaInfo->getInfoSpa(),
            'configPrintBill' => $configPrintBill,
            'id' => $id,
            'printTime' => $printReply,
            'STT' => $maxId != null ? $maxId['id'] : 1,
            'QrCode' => $order['order_code'],
            // 'convertNumberToWords' => $convertNumberToWords,
            'branchInfo' => $branchInfo,
            'order_detail' => $arr,
            'totalQuantity' => $totalQuantity,
            'totalDiscount' => $totalDiscount,
            'totalDiscountDetail' => $totalDiscountDetail,
            'amount_return' => $amount_return,
            'amount_paid' => $amount_paid,
            'accountMoney' => $accountMoney,
            'list_receipt_detail' => $list_receipt_detail,
            // 'amount' => $amount,
            // 'text_total_amount_paid' => $this->convert_number_to_words(floatval($order['total']))
        ]);
    }

    /**
     * In hóa đơn chưa thanh toán
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function printBillNotReceiptAction(Request $request)
    {
        $rOrder = \app()->get(OrderRepositoryInterface::class);
        $rReceipt = \app()->get(ReceiptRepositoryInterface::class);
        $rOrderDetail = \app()->get(OrderDetailRepositoryInterface::class);
        $rProduct = \app()->get(ProductRepositoryInterface::class);
        $rConfigPrintBill = \app()->get(ConfigPrintBillRepositoryInterface::class);
        $rBranch = \app()->get(BranchRepositoryInterface::class);
        $rPrintBillLog = \app()->get(PrintBillLogRepositoryInterface::class);
        $rSpaInfo = \app()->get(SpaInfoRepositoryInterface::class);
        $id = $request->ptintorderid;
        //Lấy thông tin đơn hàng
        $order = $rOrder->getItemDetail($id);

        $lstReceipt = $rReceipt->getReceiptOrderList($id);

        $amount_paid = 0;
        $amount_return = 0;
        $totalDiscount = 0;
        foreach ($lstReceipt as $key => $objReceipt) {
            $amount_paid += $objReceipt['amount_paid'];
            $amount_return += $objReceipt['amount_return'];
            $totalDiscount += $objReceipt['discount'];
        }
        //Lấy chi tiết đơn hàng
        $list_table = $rOrderDetail->getItem($id);

        // $totalDiscount = $order['discount'];

        $arr = [];
        $arrChild = [];
        $totalQuantity = 0;
        $totalDiscountDetail = 0;
        foreach ($list_table as $key => $item) {
            $unitName = null;
            //Lấy đơn vị tính nếu là sản phẩm, sản phẩm quà tặng (object_type = product, product_gift)
            if ($item['object_type'] == 'product' || $item['object_type'] == 'product_gift') {
                $productInfo = $rProduct->getItem($item['object_id']);
                if ($productInfo != null) {
                    $unitName = $productInfo['unitName'];
                }
            }
//            $arr[] = [
//                'order_detail_id' => $item['order_detail_id'],
//                'object_id' => $item['object_id'],
//                'object_name' => $item['object_name'],
//                'object_type' => $item['object_type'],
//                'price' => $item['price'],
//                'quantity' => $item['quantity'],
//                'discount' => $item['discount'],
//                'amount' => $item['amount'],
//                'voucher_code' => $item['voucher_code'],
//                'object_code' => "XXXXXXXXXXXXXX" . substr($item['object_code'], -4),
//                'unit_name' => $unitName
//            ];
            $totalQuantity += (double)$item['quantity'];
            $totalDiscountDetail += $item['discount'];
            $totalDiscount += $item['discount'];

            if (!isset($item['order_detail_id_parent'])){
                $arr[] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'object_code' => "XXXXXXXXXXXXXX" . substr($item['object_code'], -4),
                    'unit_name' => $unitName,
                    'name_attribute' => isset($item['name_attribute']) ? $item['name_attribute'] : []
                ];
            } else {
                $arrChild[$item['order_detail_id_parent']][] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'object_code' => "XXXXXXXXXXXXXX" . substr($item['object_code'], -4),
                    'unit_name' => $unitName
                ];
            }
        }
        //Lấy cấu hình in bill
        $configPrintBill = $rConfigPrintBill->getItem(1);

        isset($order['branch_id']) ? $order['branch_id'] : '';
        //Lấy thông tin chi nhánh của đơn hàng
        $branchInfo = $rBranch->getItem($order['branch_id']);
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
        } else {
            $branchInfo = [
                "branch_name" => "",
                "address" => "",
                "district_type" => "",
                "district_name" => "",
                "province_name" => "",
                "hot_line" => "",
            ];
        }
        //Template mặc định
        $template = 'fnb::orders.print-not-receipt.content-print';

        switch ($configPrintBill->template) {
            case 'k58':
                $template = 'fnb::orders.print-not-receipt.template-k58';
                break;
            case 'A5':
                $template = 'fnb::orders.print-not-receipt.template--a5';
                break;
            case 'A5-landscape':
                $template = 'fnb::orders.print-not-receipt.template--a5-landscape';
                break;
            case 'A4':
                $template = 'fnb::orders.print-not-receipt.template-a4';
                break;
            case 'k80':
                $template = 'fnb::orders.print-not-receipt.template-k80';
                break;
        }
        //Lấy số lần in bill của đơn hàng này
        $checkPrintBill = $rPrintBillLog->checkPrintBillOrder($order['order_code']);
        $printTime = count($checkPrintBill);
        $printReply = '';
        if ($printTime > 0) {
            $printReply = __('(In lại)');
        }
        $maxId = $rPrintBillLog->getBiggestId();
        // $convertNumberToWords = $this->help->convertNumberToWords($amount);

        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
        $mConfig = new ConfigTable();
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];
        $branchId = null;
        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }
        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, 'plus');
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, 'subtract');
        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        return view($template, [
            'order' => $order,
            'oder_detail' => $arr,
            'order_detail_child' => $arrChild,
            'spaInfo' => $rSpaInfo->getInfoSpa(),
            'configPrintBill' => $configPrintBill,
            'id' => $id,
            'printTime' => $printReply,
            'STT' => $maxId != null ? $maxId['id'] : 1,
            'QrCode' => $order['order_code'],
            // 'convertNumberToWords' => $convertNumberToWords,
            'branchInfo' => $branchInfo,
            'order_detail' => $arr,
            'totalQuantity' => $totalQuantity,
            'totalDiscount' => $totalDiscount,
            'totalDiscountDetail' => $totalDiscountDetail,
            'amount_return' => $amount_return,
            'amount_paid' => $amount_paid,
            'accountMoney' => $accountMoney
            // 'amount' => $amount,
            // 'text_total_amount_paid' => $this->convert_number_to_words(floatval($order['total']))
        ]);
    }

    /**
     * Hủy đơn hàng đã thanh toán
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitCancelOrderAction(Request $request)
    {
        try {
            DB::beginTransaction();
            $rOrder = \app()->get(OrderRepositoryInterface::class);
            $rOrderDetail = \app()->get(OrderDetailRepositoryInterface::class);
            $rOrderCommission = \app()->get(OrderCommissionRepositoryInterface::class);
            $rCustomerBranchMoney = \app()->get(CustomerBranchMoneyRepositoryInterface::class);
            $rReceipt = \app()->get(ReceiptRepositoryInterface::class);
            $rCustomerDebt = \app()->get(CustomerDebtRepositoryInterface::class);
            $mPointHistory = \app()->get(PointHistoryTable::class);
            $rCustomer = \app()->get(CustomerRepositoryInterface::class);
            $rProductInventory = \app()->get(ProductInventoryRepositoryInterface::class);
            $param = $request->all();
            $validator = \Validator::make($param, [
                'order_description' => 'required|max:255',
            ], [
                'order_description.required' => __('Hãy nhập ghi chú'),
                'order_description.max' => __('Tối đa 255 kí tự')
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    '_error' => $validator->errors()->all(),
                    'message' => __('Hủy thất bại')
                ]);
            } else {
                //Lấy thông tin đơn hàng
                $item_order = $rOrder->getItemDetail($param['order_id']);
                $data = [
                    'order_description' => strip_tags($param['order_description']),
                    'process_status' => 'ordercancle'
                ];
                //update trạng thái đơn hàng
                $rOrder->edit($data, $param['order_id']);
                //Lấy thông tin chi tiết đơn hàng
                $order_detail = $rOrderDetail->getItem($param['order_id']);
                //Text thông báo lỗi ko cho hủy khi đơn hàng đã có thẻ dv đã dc kích hoạt
                $messageError = '';
                $mCardList = new ServiceCardList();
                $mCustomerServiceCard = new CustomerServiceCardTable();

                foreach ($order_detail as $item) {
                    //Kiểm tra thẻ dịch vụ đã được kích hoạt chưa
                    if ($item['object_type'] == 'service_card') {
                        //Lấy thông tin thẻ dv đã dc kích hoạt
                        $getInfo = $mCardList->getInfoCardActive($item['object_code'], $item_order['order_code']);

                        if ($getInfo != null) {
                            $messageError .= __('Thẻ dịch vụ') . ' ' . $getInfo['card_name'] . ' ' . __('đã được kích hoạt') . '</br>';
                        }
                    }

                    $order_commission = $rOrderCommission->getItemByOrderDetail($item['order_detail_id']);
                    if (isset($order_commission)) {
                        //update order commission
                        $rOrderCommission->edit(['status' => 'cancel'], $order_commission['id']);
                        if (isset($order_commission['refer_id'])) {
                            $get_customer_money = $rCustomerBranchMoney
                                ->getPriceBranch($order_commission['refer_id'], Auth::user()->branch_id);
                            $data_customer_money = [
                                'commission_money' => intval($get_customer_money['commission_money']) - intval($order_commission['refer_money'])
                            ];
                            //update commission money
                            $rCustomerBranchMoney->edit($data_customer_money, $order_commission['refer_id'], Auth::user()->branch_id);
                        }
                    }
                    // Cộng lại thẻ liệu trình ($item_order['customer_id'])
                    if ($item['object_type'] == 'member_card') {
                        $cardDetail = $mCustomerServiceCard->getCardByCode($item['object_code']);
                        $mCustomerServiceCard->editByCardCodeAndCustomerId([
                            'count_using' => $cardDetail['count_using'] - $item['quantity']
                        ], $item['object_code'], $item_order['customer_id']);
                    }
                }
                //Xuất lỗi
                if ($messageError != '') {
                    return response()->json([
                        'error' => true,
                        'message' => $messageError
                    ]);
                }
                //update receipt
                $rReceipt->edit(['status' => 'cancel'], $item_order['receipt_id']);
                //check customer debt
                $item_debt = $rCustomerDebt->getCustomerDebtByOrder($param['order_id']);
                if (isset($item_debt)) {
                    //update status customer debt
                    $rCustomerDebt->edit(['status' => 'cancel'], $item_debt['customer_debt_id']);
                    //check receipt by customer debt
                    $receipt_debt = $rReceipt->getReceipt($item_debt['customer_debt_id']);
                    if (count($receipt_debt) > 0) {
                        foreach ($receipt_debt as $item) {
                            $rReceipt->edit(['status' => 'cancel'], $item['receipt_id']);
                        }
                    }
                }
                //Trừ điểm khi hủy đơn hàng
                $history = $mPointHistory->getPointOrder($param['order_id']);
                if ($history != null) {
                    $customer = $rCustomer->getItem($history['customer_id']);
                    if ($customer != null) {
                        //Update điểm
                        $point = $customer['point'] - $history['point'];
                        $rCustomer->edit(['point' => $point], $history['customer_id']);
                    }
                    $mPointHistory->cancelOrder($param['order_id']);
                }
                //Xóa đơn giao hàng
                $rOrder->removeDelivery($param['order_id']);
                //Trừ quota_user khi đơn hàng có promotion quà tặng
                $mOrderApp = app()->get(OrderAppRepoInterface::class);
                $mOrderApp->subtractQuotaUsePromotion($param['order_id']);
                // BEGIN: CỘNG LẠI KHO HÀNG
                $mInventoryInput = new InventoryInputTable();
                $mInventoryInputDetail = new InventoryInputDetailTable();
                $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
                $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
                $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
                $rInventoryOutput = \app()->get(InventoryOutputRepositoryInterface::class);
                $rInventoryOutputDetail = \app()->get(InventoryOutputDetailRepositoryInterface::class);
                // Lấy warehouse_id từ phiếu xuất kho theo order_id
                $infoInventoryOutput = $rInventoryOutput->getInfoByOrderId($param['order_id'], 'retail');
                if ($infoInventoryOutput != null) {
                    $warehouseId = $infoInventoryOutput['warehouse_id'];
                    $inventoryOutputId = $infoInventoryOutput['inventory_output_id'];
                    // Lấy danh sách sản phẩm đơn hàng đã xuât
                    $listProduct = $rInventoryOutputDetail->getListDetailByParentId($inventoryOutputId, $warehouseId);
                    if (count($listProduct) > 0) {
                        // Tạo phiếu nhập kho
                        $dataInput = [
                            'warehouse_id' => $warehouseId,
                            'status' => 'success',
                            'object_id' => $param['order_id'],
                            'type' => 'return',
                            'created_by' => Auth::id(),
                            'note' => 'Trả hàng'
                        ];
                        $inputId = $mInventoryInput->add($dataInput);

                        $mInventoryInput->edit(['pi_code' => $this->codeDMY('NK', $inputId)], $inputId);

                        foreach ($listProduct as $prod) {
                            // Lấy số lượng hiện tại trong kho
                            $getQuantity = $rProductInventory->getQuantityByProdCodeAndWarehouseId($prod['product_code'], $warehouseId);
                            // Công vào kho
                            $data = [
                                'quantity' => $getQuantity['quantity'] + $prod['quantity']
                            ];
                            $rProductInventory->editQuantityByCode($data, $prod['product_code'], $warehouseId);
                            // Chi tiết nhập kho
                            $dataInputDetail = [
                                'inventory_input_id' => $inputId,
                                //                                'product_code' => $inputId,
                                'product_code' => $prod['product_code'],
                                'quantity' => $prod['quantity'],
                                'status' => 'success',
                                'date_recived' => Carbon::now()->format('Y-m-d'),
                                'object_id' => $param['order_id'],
                                'type' => 'return'
                            ];
                            $inventoryInputDetail = $mInventoryInputDetail->add($dataInputDetail);
                            $tmpSerial = [];
                            $tmpListSerial = [];
                            //                            Lấy danh sách serial trong đơn hàng chi tiết
                            $listSerialOrder = $mOrderDetailSerial->getListSerialByOrder($param['order_id'], $prod['product_code']);

                            if (count($listSerialOrder) != 0) {
                                $tmpListSerial = collect($listSerialOrder)->pluck('serial')->toArray();
                            }

                            foreach ($listSerialOrder as $itemSerial) {
                                $tmpSerial[] = [
                                    'inventory_input_detail_id' => $inventoryInputDetail,
                                    'product_code' => $prod['product_code'],
                                    'serial' => $itemSerial['serial'],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ];
                            }

                            //                            Thêm serial vào nhập kho
                            if (count($tmpSerial) != 0) {
                                $mInventoryInputDetailSerial->insertListSerial($tmpSerial);
                            }
                            //                            Cập nhật lại các serial trong kho thành chưa xuất
                            if (count($tmpListSerial) != 0) {
                                $mProductInventorySerial->updateByArrSerial($tmpListSerial, ['status' => 'new']);
                            }
                        }
                    }
                }
                // END

                DB::commit();

                //Send Notification
                if ($item_order['customer_id'] != 1) {
                    $mNoti = new SendNotificationApi();
                    $mNoti->sendNotification([
                        'key' => 'order_status_C',
                        'customer_id' => $item_order['customer_id'],
                        'object_id' => $param['order_id']
                    ]);
                    //Lưu log ZNS
                    SaveLogZns::dispatch('order_cancle', $item_order['customer_id'], $param['order_id']);
                }

                return response()->json([
                    'error' => false,
                    'message' => __('Hủy thành công')
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function codeDMY($string, $stt)
    {
        $time = date("dmY");
        return $string . '_' . $time . $stt;
    }

    /**
     * Lưu thông tin của đơn hàng chưa thanh toán
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitEditAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $mNoti = new SendNotificationApi();
            $mPromotionLog = new PromotionLogTable();
            $rOrder = \app()->get(OrderRepositoryInterface::class);
            $rOrderDetail = \app()->get(OrderDetailRepositoryInterface::class);
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mOrderLog = new OrderLogTable();
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $mProductChild = \app()->get(ProductChildTable::class);
            if ($request->receipt_info_check == 1 && !isset($request->delivery_type)) {
                return response()->json([
                    'error' => true,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }

            $sessionProduct = [];
            if (session()->has('topping_product')){
                $sessionProduct = session()->get('topping_product');
            };

            $detailAddress = null;
            $mCustomerContact = app()->get(CustomerContactTable::class);
            if ($request->receipt_info_check == 1) {
                $detailAddress = $mCustomerContact->getDetail($request->customer_contact_id);
            }

            $data_order = [
                'total' => $request->total_bill,
                'discount' => $request->discount_bill,
                'voucher_code' => $request->voucher_bill,
                'order_description' => $request->order_description,
                'amount' => str_replace(',', '', $request->amount_bill),
                'updated_by' => Auth::id(),
//                'refer_id' => $request->refer_id,
                //                'tranport_charge' => str_replace(',', '', $request->tranport_charge),
                'customer_contact_code' => $request->receipt_info_check == 1 && $detailAddress != null ? $detailAddress['customer_contact_code'] : '',
                'customer_contact_id' => $request->receipt_info_check == 1 ? $request->customer_contact_id : '',
                'receive_at_counter' => $request->receipt_info_check == 1 ? 0 : 1,
                'type_time' => $request->receipt_info_check == 1 ? $request->type_time : '',
                'time_address' => $request->receipt_info_check == 1 && $request->time_address != '' ? Carbon::createFromFormat('Y-m-d', $request->time_address)->format('Y-m-d') : '',
                'tranport_charge' => $request->tranport_charge,
                'type_shipping' => $request->delivery_type,
                'delivery_cost_id' => $request->delivery_cost_id,
                'discount_member' => $request->discount_member,
                'fnb_table_id' => $request->table_id,
                'fnb_customer_id' => $request->customer_id,
            ];

            $rOrder->edit($data_order, $request->order_id);

            //Xóa chi tiết đơn hàng cũ
            $rOrderDetail->remove($request->order_id);
            //            Xoá serial đơn hàng cũ
            $mOrderDetailSerial->removeSerial($request->order_id);

            $arrPromotionLog = [];
            $arrQuota = [];
            $arrObjectBuy = [];

            $id_order = $request->order_id;
            $session = $request->sessionSerial;

            if ($request->table_edit != null) {
//                $aData = array_chunk($request->table_edit, 16, false);
                $aData = $request->table_edit;

                foreach ($aData as $key => $value) {
                    //Replace thành tiền sp
                    $value['amount'] = str_replace(',', '', $value['amount']);
                    $value['price'] = str_replace(',', '', $value['price']);
                    $isChangePrice = isset($value['is_change_price']) ? $value['is_change_price'] : 0;
                    $isCheckPromotion = isset($value['is_check_promotion']) ? $value['is_check_promotion'] : 0;

                    if (in_array($value['object_type'], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                        $arrObjectBuy[] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['product_child_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $request->customer_id,
                            'order_source' => 1,
                            'order_id' => $request->order_id,
                            'order_code' => $request->order_code
                        ];
                    }
                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['product_child_id'],
                        'object_name' => $value['product_name'],
                        'object_type' => $value['object_type'],
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => str_replace(',', '', $value['discount']),
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
//                        'refer_id' => $request->refer_id,
                        'staff_id' => $request->staff_id != null ? $request->staff_id : null,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $orderDetailId = $rOrderDetail->add($data_order_detail);

                    if(isset($sessionProduct[$value['product_id'].'_'.$value['key_string']])){
                        $arrProductChildTmp = $sessionProduct[$value['product_id'].'_'.$value['key_string']]['topping'];
                        if (count($arrProductChildTmp) != 0){
                            $listProductChild = $mProductChild->getProductChildInId($arrProductChildTmp);
                            foreach ($listProductChild as $itemChildProduct){
                                $data_order_detail_child = [
                                    'order_id' => $id_order,
                                    'object_id' => $itemChildProduct['productId'],
                                    'object_name' => $itemChildProduct['productName'],
                                    'object_type' => $value['object_type'],
                                    'object_code' => $value['object_code'],
                                    'price' => 0,
                                    'quantity' => 1,
                                    'discount' => 0,
                                    'voucher_code' => '',
                                    'amount' => 0,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                                    'staff_id' => isset($request->staff_id) ? $request->staff_id : null,
                                    'is_change_price' => $isChangePrice,
                                    'is_check_promotion' => $isCheckPromotion,
                                    'order_detail_id_parent' => $orderDetailId,
                                    'note' => $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note']
                                ];

                                $rOrderDetail->add($data_order_detail_child);
                            }
                        }
                    }

                    if ($value['object_type'] == 'product') {
                        $tmpSerial = [];
                        if (isset($value[15])) {
                            $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $value[15], $value['object_code']);
                            foreach ($listSerialLog as $item) {
                                $tmpSerial[] = [
                                    'order_id' => $id_order,
                                    'order_detail_id' => $orderDetailId,
                                    'product_code' => $value['object_code'],
                                    'serial' => $item['serial'],
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id(),
                                ];
                            }
                        }

                        if (count($tmpSerial) != 0) {
                            $mOrderDetailSerial->insertSerial($tmpSerial);
                        }
                    }
                }
            }

            if ($request->table_add != null) {
//                $aData = array_chunk($request->table_add, 15, false);
                $aData = $request->table_add;
                foreach ($aData as $key => $value) {
                    //Replace thành tiền sp
                    $value['amount'] = str_replace(',', '', $value['amount']);
                    $value['price'] = str_replace(',', '', $value['price']);
                    //                    $isChangePrice = isset($value['is_change_price']) ? $value['is_change_price'] : 0;
                    //                    $isCheckPromotion = isset($value['is_change_price']) ? $value['is_change_price'] : 0;

                    $isChangePrice = isset($value['is_change_price']) ? $value['is_change_price'] : 0;
                    $isCheckPromotion = isset($value['is_check_promotion']) ? $value['is_check_promotion'] : 0;

                    if (in_array($value['object_type'], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                        $arrObjectBuy[] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['product_child_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $request->customer_id,
                            'order_source' => 1,
                            'order_id' => $request->order_id,
                            'order_code' => $request->order_code
                        ];
                    }
                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['product_child_id'],
                        'object_name' => $value['product_name'],
                        'object_type' => $value['object_type'],
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => str_replace(',', '', $value['discount']),
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
//                        'refer_id' => $request->refer_id,
                        'staff_id' => $request->staff_id != null && $request->staff_id != 0 ? $request->staff_id : null,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $orderDetailId = $rOrderDetail->add($data_order_detail);

                    if(isset($sessionProduct[$value['product_id'].'_'.$value['key_string']])){
                        $arrProductChildTmp = $sessionProduct[$value['product_id'].'_'.$value['key_string']]['topping'];
                        if (count($arrProductChildTmp) != 0){
                            $listProductChild = $mProductChild->getProductChildInId($arrProductChildTmp);
                            foreach ($listProductChild as $itemChildProduct){
                                $data_order_detail_child = [
                                    'order_id' => $id_order,
                                    'object_id' => $itemChildProduct['productId'],
                                    'object_name' => $itemChildProduct['productName'],
                                    'object_type' => $value['object_type'],
                                    'object_code' => $value['object_code'],
                                    'price' => 0,
                                    'quantity' => 1,
                                    'discount' => 0,
                                    'voucher_code' => '',
                                    'amount' => 0,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                                    'staff_id' => isset($request->staff_id) ? $request->staff_id : null,
                                    'is_change_price' => $isChangePrice,
                                    'is_check_promotion' => $isCheckPromotion,
                                    'order_detail_id_parent' => $orderDetailId,
                                    'note' => $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note']
                                ];

                                $rOrderDetail->add($data_order_detail_child);
                            }
                        }
                    }

                    if ($value['object_type'] == 'product') {
                        $tmpSerial = [];
                        $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $value['is_change_price'], $value['object_type']);
                        foreach ($listSerialLog as $item) {
                            $tmpSerial[] = [
                                'order_id' => $id_order,
                                'order_detail_id' => $orderDetailId,
                                'product_code' => $value['object_type'],
                                'serial' => $item['serial'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id(),
                            ];
                        }

                        if (count($tmpSerial) != 0) {
                            $mOrderDetailSerial->insertSerial($tmpSerial);
                        }
                    }
                }
            }
            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $mOrderApp->subtractQuotaUsePromotion($request->order_id);
            //Remove promotion log
            $mPromotionLog->removeByOrder($request->order_id);

            if (!isset($request->custom_price) || $request->custom_price == 0) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $mOrderApp->plusQuotaUsePromotion($arrQuota);
            }

            if (isset($request->delivery_active) && $request->delivery_active == 1) {
                //Cập nhật trạng thái đơn hàng
                $rOrder->edit([
                    'process_status' => 'confirmed'
                ], $request->order_id);
                //Cập nhật trạng thái đơn hàng cần giao
                $mDelivery = new DeliveryTable();
                $mDelivery->edit([
                    'is_actived' => 1
                ], $request->order_id);

                //Insert order log đơn hàng đã xác nhận, đang xử lý
                $mOrderLog->insert([
                    [
                        'order_id' => $request->order_id,
                        'created_type' => 'backend',
                        'status' => 'confirmed',
                        //                        'note' => __('Đã xác nhận đơn hàng'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đã xác nhận đơn hàng',
                        'note_en' => 'Order confirm',
                    ],
                    [
                        'order_id' => $request->order_id,
                        'created_type' => 'backend',
                        'status' => 'packing',
                        //                        'note' => __('Đang xử lý'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đang xử lý',
                        'note_en' => 'Processing',
                    ]
                ]);

                if ($request->customer_id != 1) {
                    //Send notification xác nhận đơn hàng
                    $mNoti->sendNotification([
                        'key' => 'order_status_A',
                        'customer_id' => $request->customer_id,
                        'object_id' => $request->order_id
                    ]);
                }
            }

            //Lưu thông tin hàng hoá cho hợp đồng
            $rOrder->updateContractGoods($request->order_id, 0);
            $mDelivery = new DeliveryTable();
            if (isset($request->receipt_info_check) && $request->receipt_info_check == 1) {
                $mCustomerContact = app()->get(CustomerContactTable::class);
                //Lấy thông tin khách hàng
                $infoCustomer = $mCustomerContact->getDetail($request->customer_contact_id);
                $dataDelivery = [
                    'order_id' => $id_order,
                    'customer_id' => $request->customer_id,
                    'contact_name' => $infoCustomer != null ? $infoCustomer['customer_name'] : '',
                    'contact_phone' => $infoCustomer != null ? $infoCustomer['customer_phone'] : '',
                    'contact_address' => $infoCustomer != null ? $infoCustomer['address'] . ' , ' . $infoCustomer['ward_name'] . ' , ' . $infoCustomer['district_name'] . ' , ' . $infoCustomer['province_name'] : '',
                    'is_actived' => isset($request->delivery_active) ? $request->delivery_active : 1,
                    'time_order' => Carbon::now()->format('Y-m-d H:i')
                ];

                //                Lấy thông tin giao hàng

                $detailDeliveries = $mDelivery->getInfo($id_order);
                if ($detailDeliveries == null) {
                    //Insert thông tin giao hàng
                    $mDelivery->add($dataDelivery);
                } else {
                    //Cập nhật thông tin giao hàng
                    $mDelivery->edit($dataDelivery, $id_order);
                }
            }

            DB::commit();

            return response()->json([
                'order_code' => $request->order_code,
                'order_id' => $request->order_id,
                'error' => false,
                'message' => __('Lưu đơn hàng thành công'),
            ]);
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ]);
        }
    }

    /**
     * Thanh toán sau submit
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitReceiptAfterAction(Request $request)
    {
        DB::beginTransaction();
        try {

            $mNoti = new SendNotificationApi();
            $rOrder = \app()->get(OrderRepositoryInterface::class);
            $rOrderDetail = \app()->get(OrderDetailRepositoryInterface::class);
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mOrderLog = new OrderLogTable();
            $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $rStaff = \app()->get(StaffRepositoryInterface::class);
            $rService = \app()->get(ServiceRepositoryInterface::class);
            $rProductChild = \app()->get(ProductChildRepositoryInterface::class);
            $rServiceCard = \app()->get(ServiceCardRepositoryInterface::class);
            $rCode = \app()->get(CodeGeneratorRepositoryInterface::class);
            $rCustomer = \app()->get(CustomerRepositoryInterface::class);
            $rCustomerServiceCard = \app()->get(CustomerServiceCardRepositoryInterface::class);
            $rServiceCardList = \app()->get(ServiceCardListRepositoryInterface::class);
            $rVoucher = \app()->get(VoucherRepositoryInterface::class);
            $rSpaInfo = \app()->get(SpaInfoRepositoryInterface::class);
            $rCustomerDebt = \app()->get(CustomerDebtRepositoryInterface::class);
            $mProductChild = \app()->get(ProductChildTable::class);
            $session = $request->sessionSerial;
            $id_order = $request->order_id;
            $orderCode = $request->order_code;
            //Lấy số lẻ decimal
            $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
            //Lấy thông tin nhân viên
            $staff = $rStaff->getItem(Auth::id());
            //Lấy thông tin đơn hàng
            $infoOrder = $rOrder->getItemDetail($request->order_id);

            $checkQuantityOrderDetail = $this->checkQuantityOrderDetail($id_order);

            if ($checkQuantityOrderDetail['error'] == false) {
                return response()->json([
                    'error' => $checkQuantityOrderDetail['error'],
                    'message' => $checkQuantityOrderDetail['message']
                ]);
            }

            if ($request->receipt_info_check == 1 && !isset($request->delivery_type)) {
                return response()->json([
                    'error' => false,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }

            $sessionProduct = [];
            if (session()->has('topping_product')){
                $sessionProduct = session()->get('topping_product');
            };

            // cập nhật lại thông tin đơn hàng
            $data_order = [
                'total' => $request->total_bill,
                'discount' => $request->discount_bill,
                'voucher_code' => $request->voucher_bill,
                'order_description' => $request->order_description,
                'amount' => str_replace(',', '', $request->amount_bill),
                'process_status' => 'paysuccess',
                'updated_by' => Auth::id(),
                'discount_member' => $request->discount_member,
                'cashier_by' => Auth()->id(),
                'cashier_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'tranport_charge' => $request->tranport_charge,
                'type_shipping' => $request->delivery_type,
                'delivery_cost_id' => $request->delivery_cost_id,
                'fnb_table_id' => $request->table_id,
                'fnb_customer_id' => $request->customer_id

            ];

            $rOrder->edit($data_order, $request->order_id);

            $list_card_print = [];
            $arrPromotionLog = [];
            $arrQuota = [];
            $arrObjectBuy = [];
            $arrRemindUse = [];
            $data_order_detail_old = [];
            if ($request->table_edit != null) {
                // rule cũ: đối với những sp/dv/thẻ đã được lưu trước đó => update lại thông tin, tính hoa hồng, số lần sử dụng,...
                // rule mới: xoá sp/dv/thẻ => thêm lại và tính lại hoa hồng,...

                // lấy 14 elements trong table_edit => merge chung với table_add để bên table_add sử lý
//                $data_order_detail_old = array_chunk($request->table_edit, 16, false);
                $data_order_detail_old = $request->table_edit;
//                foreach ($data_order_detail_old as $k => $v) {
////                    array_splice($data_order_detail_old[$k], 0, 1);
//                    $data_order_detail_old[$k] = $v['id_detail'];
//                }
            }

            // remove all detail => add again
            $rOrderDetail->remove($request->order_id);

            $arrDataOrderDetail = [];
            if ($request->table_add != null) {
                //                $arrDataOrderDetail = array_chunk($request->table_add, 14, false);
//                $arrDataOrderDetail = array_chunk($request->table_add, 15, false);
                $arrDataOrderDetail = $request->table_add;
            }
            // merge data order old vs data order new
            $arrDataOrderDetail = array_merge($data_order_detail_old, $arrDataOrderDetail);

            // foreach data order => insert detail, commission,...
            foreach ($arrDataOrderDetail as $key => $value) {
                $value['amount'] = str_replace(',', '', $value['amount']);
                $isChangePrice = isset($value['is_change_price']) ? $value['is_change_price'] : 0;
                $isCheckPromotion = isset($value['is_check_promotion']) ? $value['is_check_promotion'] : 0;
                $position = 0;
                $productCode = isset($value['object_code']) ? $value['object_code'] : 0;

                if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                    if ($isCheckPromotion == 1) {
                        $arrObjectBuy[] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['product_child_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $request->customer_id,
                            'order_source' => 1,
                            'order_id' => $request->order_id,
                            'order_code' => $request->order_code
                        ];
                    }
                    //Lấy array nhắc sử dụng lại
                    $arrRemindUse[] = [
                        'object_type' => $value['object_type'],
                        'object_id' => $value['product_child_id'],
                        'object_code' => $value['object_code'],
                        'object_name' => $value['product_name'],
                    ];
                }
                if ($value['object_type'] == 'service') {
                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['product_child_id'],
                        'object_name' => $value['product_name'],
                        'object_type' => 'service',
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => str_replace(',', '', $value['discount']),
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'staff_id' => $request->staff_id != null ? implode(',', $request->staff_id) : null,
                        'refer_id' => $request->refer_id,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $id_detail = $rOrderDetail->add($data_order_detail);
                    if(isset($sessionProduct[$value['product_id'].'_'.$value['key_string']])){
                        $arrProductChildTmp = $sessionProduct[$value['product_id'].'_'.$value['key_string']]['topping'];
                        if (count($arrProductChildTmp) != 0){
                            $listProductChild = $mProductChild->getProductChildInId($arrProductChildTmp);
                            foreach ($listProductChild as $itemChildProduct){
                                $data_order_detail_child = [
                                    'order_id' => $id_order,
                                    'object_id' => $itemChildProduct['productId'],
                                    'object_name' => $itemChildProduct['productName'],
                                    'object_type' => $value['object_type'],
                                    'object_code' => $value['object_code'],
                                    'price' => 0,
                                    'quantity' => 1,
                                    'discount' => 0,
                                    'voucher_code' => '',
                                    'amount' => 0,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                                    'staff_id' => isset($request->staff_id) ? $request->staff_id : null,
                                    'is_change_price' => $isChangePrice,
                                    'is_check_promotion' => $isCheckPromotion,
                                    'order_detail_id_parent' => $id_detail,
                                    'note' => $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note']
                                ];

                                $rOrderDetail->add($data_order_detail_child);
                            }
                        }
                    }
                    $check_commission = $rService->getItem($value['product_child_id']);
                    $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;


                    // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                    $rOrder->calculatedCommission($value['quantity'], $request->refer_id, $check_commission, $id_detail, $value['product_child_id'], null, $value['amount'], $request->staff_id);
                }


                if ($value['object_type'] == 'product') {
                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['product_child_id'],
                        'object_name' => $value['product_name'],
                        'object_type' => 'product',
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => str_replace(',', '', $value['discount']),
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'staff_id' => $request->staff_id != null ? $request->staff_id : null,
                        'refer_id' => $request->refer_id,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $id_detail = $rOrderDetail->add($data_order_detail);

                    if(isset($sessionProduct[$value['product_id'].'_'.$value['key_string']])){
                        $arrProductChildTmp = $sessionProduct[$value['product_id'].'_'.$value['key_string']]['topping'];
                        if (count($arrProductChildTmp) != 0){
                            $listProductChild = $mProductChild->getProductChildInId($arrProductChildTmp);
                            foreach ($listProductChild as $itemChildProduct){
                                $data_order_detail_child = [
                                    'order_id' => $id_order,
                                    'object_id' => $itemChildProduct['productId'],
                                    'object_name' => $itemChildProduct['productName'],
                                    'object_type' => $value['object_type'],
                                    'object_code' => $value['object_code'],
                                    'price' => 0,
                                    'quantity' => 1,
                                    'discount' => 0,
                                    'voucher_code' => '',
                                    'amount' => 0,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                                    'staff_id' => isset($request->staff_id) ? $request->staff_id : null,
                                    'is_change_price' => $isChangePrice,
                                    'is_check_promotion' => $isCheckPromotion,
                                    'order_detail_id_parent' => $id_detail,
                                    'note' => $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note']
                                ];

                                $rOrderDetail->add($data_order_detail_child);
                            }
                        }
                    }

                    $check_commission = $rProductChild->getItem($value['product_child_id']);
                    $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;


                    // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                    $rOrder->calculatedCommission($value['quantity'], $request->refer_id, $check_commission, $id_detail, $value['product_child_id'], null, $value['amount'], $request->staff_id);

                    $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
                    $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);

                    //                        Kiểm tra serial của đơn hàng đã được tạo hay chưa
                    $checkOrderSerial = $mOrderDetailSerial->getListSerialByOrder($id_order, $productCode);
                    $tmpSerialLog = [];
                    if (count($checkOrderSerial) == 0) {
                        $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $position, $productCode);
                        foreach ($listSerialLog as $itemSerialLog) {
                            $tmpSerialLog[] = [
                                'order_id' => $id_order,
                                'order_detail_id' => $id_detail,
                                'product_code' => $productCode,
                                'serial' => $itemSerialLog['serial'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ];
                        }
                    }

                    if (count($tmpSerialLog) != 0) {
                        $mOrderDetailSerial->insertSerial($tmpSerialLog);
                    }
                }

                if ($value['object_type'] == 'service_card') {
                    $sv_card = $rServiceCard->getServiceCardOrder($value['object_code']);
                    //Lấy hoa hồng thẻ dịch vụ
                    $check_commission = $rServiceCard->getServiceCardInfo($value['product_child_id']);
                    $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                    $refer_money = 0;
                    $staff_money = 0;
                    $staffCardCommission = 0;
                    if ($check_commission['type_refer_commission'] == 'money') {
                        if (isset($check_commission['refer_commission_value'])) {
                            $refer_money = ($check_commission['refer_commission_value']);
                        }
                    } else {
                        if (isset($check_commission['refer_commission_value'])) {
                            $refer_money = ($value['amount'] / 100) * ($check_commission['refer_commission_value']);
                        }
                    }
                    if ($check_commission['type_staff_commission'] == 'money') {
                        if (isset($check_commission['staff_commission_value'])) {
                            $staffCardCommission = round($check_commission['staff_commission_value'], $decimal, PHP_ROUND_HALF_DOWN);
                        }
                    } else {
                        if (isset($check_commission['staff_commission_value'])) {
                            $staffMoney = ($value['amount'] / 100) * ($check_commission['staff_commission_value']);
                            $staffCardCommission = round($staffMoney, $decimal, PHP_ROUND_HALF_DOWN);
                        }
                    }

                    $arr_result = [];
                    for ($i = 0; $i < $value['quantity']; $i++) {
                        $code = $rCode->generateCardListCode();
                        while (array_search($code, $arr_result)) {
                            $code = $rCode->generateCardListCode();
                        }
                        $data_card_list = [
                            'service_card_id' => $value['product_child_id'],
                            'is_actived' => 0,
                            'created_by' => Auth::id(),
                            'branch_id' => $staff['branch_id'],
                            'order_code' => $request->order_code,
                            'code' => $code,
                            'price' => $value['price'],
                            'refer_commission' => $refer_money,
                            'staff_commission' => $staffCardCommission
                        ];
                        if ($request->customer_id != 1 && $request->check_active == 1) {
                            $data_card_list['is_actived'] = $request->check_active;
                            $data_card_list['actived_at'] = date("Y-m-d H:i");

                            $data_cus_card = [
                                'customer_id' => $request->customer_id,
                                'service_card_id' => $value['product_child_id'],
                                'number_using' => $sv_card['number_using'],
                                'count_using' => $sv_card['service_card_type'] == 'money' ? 1 : 0,
                                'money' => $sv_card['money'],
                                'actived_date' => date("Y-m-d"),
                                'is_actived' => 1,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'branch_id' => $staff['branch_id'],
                                'card_code' => $code
                            ];
                            if ($sv_card['date_using'] != 0) {
                                $data_cus_card['expired_date'] = strftime("%Y-%m-%d", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $sv_card['date_using'] . 'days'))));
                            }
                            if ($sv_card['service_card_type'] == 'money') {
                                //Lấy thông tin khách hàng
                                $customer = $rCustomer->getItem($request->customer_id);
                                //Cập nhật lại tiền KH
                                $rCustomer->edit([
                                    'account_money' => $customer['account_money'] + $sv_card['money']
                                ], $request->customer_id);
                                //Lưu log + tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $request->customer_id,
                                    "branch_id" => Auth()->user()->branch_id,
                                    "source" => "member_money",
                                    "type" => 'plus',
                                    "money" => $sv_card['money'],
                                    "screen" => 'active_card',
                                    "screen_object_code" => $code
                                ]);
                            }
                            //Thêm vào customer service card thẻ đã active
                            $id_cus_card = $rCustomerServiceCard->add($data_cus_card);
                            //Thêm vào service card list thẻ đã active
                            $id_card_list = $rServiceCardList->add($data_card_list);

                            array_push($list_card_print, $code);
                            $arr_result[] = $code;
                        } else {
                            $id_card_list = $rServiceCardList->add($data_card_list);
                            array_push($list_card_print, $code);
                            $arr_result[] = $code;
                        }
                    }

                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['product_child_id'],
                        'object_name' => $value['product_name'],
                        'object_type' => 'service_card',
                        'object_code' => implode(",", $arr_result),
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => str_replace(',', '', $value['discount']),
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'staff_id' => $request->staff_id != null ? implode(',', $request->staff_id) : null,
//                        'refer_id' => $request->refer_id,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $id_detail = $rOrderDetail->add($data_order_detail);

                    if(isset($sessionProduct[$value['product_id'].'_'.$value['key_string']])){
                        $arrProductChildTmp = $sessionProduct[$value['product_id'].'_'.$value['key_string']]['topping'];
                        if (count($arrProductChildTmp) != 0){
                            $listProductChild = $mProductChild->getProductChildInId($arrProductChildTmp);
                            foreach ($listProductChild as $itemChildProduct){
                                $data_order_detail_child = [
                                    'order_id' => $id_order,
                                    'object_id' => $itemChildProduct['productId'],
                                    'object_name' => $itemChildProduct['productName'],
                                    'object_type' => $value['object_type'],
                                    'object_code' => $value['object_code'],
                                    'price' => 0,
                                    'quantity' => 1,
                                    'discount' => 0,
                                    'voucher_code' => '',
                                    'amount' => 0,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                                    'staff_id' => isset($request->staff_id) ? $request->staff_id : null,
                                    'is_change_price' => $isChangePrice,
                                    'is_check_promotion' => $isCheckPromotion,
                                    'order_detail_id_parent' => $id_detail,
                                    'note' => $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note']
                                ];

                                $rOrderDetail->add($data_order_detail_child);
                            }
                        }
                    }

                    // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                    $rOrder->calculatedCommission($value['quantity'], $request->refer_id, $check_commission, $id_detail, $value['product_child_id'], null, $value['amount'], $request->staff_id);
                }

                if ($value['voucher_code'] != null) {
                    $get = $rVoucher->getCodeItem($value['voucher_code']);
                    $data = [
                        'total_use' => ($get['total_use'] + 1)
                    ];

                    $rVoucher->editVoucherOrder($data, $value['voucher_code']);
                }
                if (in_array($value['object_type'], ['product_gift', 'service_gift', 'service_card_gift'])) {
                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['product_child_id'],
                        'object_name' => $value['product_name'],
                        'object_type' => 'service',
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => str_replace(',', '', $value['discount']),
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'staff_id' => $request->staff_id != null ? implode(',', $request->staff_id) : null,
//                        'refer_id' => $request->refer_id,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $rOrderDetail->add($data_order_detail);
                }
            }

            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $mOrderApp->subtractQuotaUsePromotion($request->order_id);
            //remove promotion log
            $mPromotionLog->removeByOrder($request->order_id);

            // xử lý khuyến mãi, quota
            if (!isset($request->custom_price) || $request->custom_price == 0) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->add($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $mOrderApp->plusQuotaUsePromotion($arrQuota);
            }

            // xử lý voucher
            if ($request->voucher_bill != null) {
                $get = $rVoucher->getCodeItem($request->voucher_bill);
                $data = [
                    'total_use' => ($get['total_use'] + 1)
                ];
                $rVoucher->editVoucherOrder($data, $request->voucher_bill);
            }

            $amount_bill = str_replace(',', '', $request->amount_bill);
            $amount_receipt = str_replace(',', '', $request->amount_receipt);

            if ($request->amount_all != '') {
                $amount_receipt_all = str_replace(',', '', $request->amount_all);
                $amount_receipt_all > $amount_bill ? $amount_receipt_all = $amount_bill : $amount_receipt_all;
            } else {
                $amount_receipt_all = 0;
            }
            $amount_return = str_replace(',', '', $request->amount_return);
            $receipt_type = $request->receipt_type;
            $status = '';
            if ($amount_receipt_all >= $amount_bill) {
                $status = 'paid';
            } else {
                //Cập nhật trạng thái đơn hàng thanh toán còn thiếu
                $rOrder->edit(['process_status' => 'pay-half'], $request->order_id);
            }
            if ($amount_receipt != 0) {
                if ($amount_receipt_all < $amount_receipt) {
                    //Check KH là hội viên
                    if ($request->customer_id != 1) {
                        //Check cấu hình thanh toán nhiều lần
                        $check_info = $rSpaInfo->getInfoSpa();
                        if ($check_info['is_part_paid'] == 1) {
                            if ($request->order_source_id != 2) {
                                $status = 'paid';
                                //insert customer debt
                                $data_debt = [
                                    'customer_id' => $request->customer_id,
                                    'debt_code' => 'debt',
                                    'staff_id' => Auth::id(),
                                    'branch_id' => Auth::user()->branch_id,
                                    'note' => $request->note,
                                    'debt_type' => 'order',
                                    'order_id' => $request->order_id,
                                    'status' => 'unpaid',
                                    'amount' => $amount_receipt - $amount_receipt_all,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id()
                                ];
                                $debt_id = $rCustomerDebt->add($data_debt);
                                //update debt code
                                $day_code = date('dmY');
                                if ($debt_id < 10) {
                                    $debt_id = '0' . $debt_id;
                                }
                                $debt_code = [
                                    'debt_code' => 'CN_' . $day_code . $debt_id
                                ];
                                $rCustomerDebt->edit($debt_code, $debt_id);
                            }
                        } else {
                            return response()->json([
                                'amount_detail_small' => 1,
                                'message' => __('Số tiền không hợp lệ')
                            ]);
                        }
                    } else {
                        return response()->json([
                            'amount_detail_small' => 1,
                            'message' => __('Số tiền không hợp lệ')
                        ]);
                    }
                }
            }

            $mReceipt = new ReceiptTable();
            $mReceiptDetail = new ReceiptDetailTable();
            // get receipt by order id => remove receipt and receipt detail
            $dataReceipt = $mReceipt->getItem($request->order_id);
            if ($dataReceipt != null) {
                $mReceipt->removeReceipt($request->order_id);
                $mReceiptDetail->removeReceiptDetail($dataReceipt['receipt_id']);
            }
            $data_receipt = [
                'customer_id' => $request->customer_id,
                'staff_id' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'object_id' => $request->order_id,
                'object_type' => 'order',
                'order_id' => $request->order_id,
                'total_money' => $amount_receipt_all,
                'voucher_code' => $request->voucher_bill,
                'status' => $status,
                'is_discount' => 1,
                'amount' => $amount_receipt_all,
                'amount_paid' => $amount_receipt_all,
                'amount_return' => $amount_return,
                'note' => $request->note,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => '', // order code
                'object_accounting_id' => $request->order_id, // order id
            ];
            if ($request->voucher_bill != null) {
                $data_receipt['discount'] = $request->discount_bill;
            } else {
                $data_receipt['custom_discount'] = $request->discount_bill;
            }
            $rReceipt = \app()->get(ReceiptRepositoryInterface::class);
            $rReceiptDetail = \app()->get(ReceiptDetailRepositoryInterface::class);
            $id_receipt = $rReceipt->add($data_receipt);
            $day_code = date('dmY');
            $data_code = [
                'receipt_code' => 'TT_' . $day_code . $id_receipt
            ];
            $rReceipt->edit($data_code, $id_receipt);


            if ($request->table_edit != null) {
//                $aData = array_chunk($request->table_edit, 16, false);
                $aData = $request->table_edit;

                foreach ($aData as $key => $value) {
                    if ($value['object_code'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $id_receipt,
                            'cashier_id' => Auth::id(),
                            //                            'receipt_type' => 'member_card',
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $value['price'],
                            'amount' => 0,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $rReceiptDetail->add($data_receipt_detail);
                    }
                }
            }
            if ($request->table_add != null) {
                $aData = array_chunk($request->table_add, 15, false);
                foreach ($aData as $key => $value) {
                    if ($value['object_type'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $id_receipt,
                            'cashier_id' => Auth::id(),
                            //                            'receipt_type' => 'member_card',
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $value['object_code'],
                            'amount' => $value['amount'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $rReceiptDetail->add($data_receipt_detail);
                    }
                }
            }

            $detailOrder = $rOrder->getItemDetail($request->order_id);
            if ($detailOrder['order_source_id'] == 1 && isset($request->receipt_info_check) && $request->receipt_info_check == 1) {
                $mCustomerContact = app()->get(CustomerContactTable::class);
                //Lấy thông tin khách hàng
                $infoCustomer = $mCustomerContact->getDetail($request->customer_contact_id);
                $mDelivery = new DeliveryTable();

                $dataDelivery = [
                    'order_id' => $id_order,
                    'customer_id' => $infoCustomer != null ? $infoCustomer['customer_id'] : '',
                    'contact_name' => $infoCustomer != null ? $infoCustomer['customer_name'] : '',
                    'contact_phone' => $infoCustomer != null ? $infoCustomer['customer_phone'] : '',
                    'contact_address' => $infoCustomer != null ? $infoCustomer['address'] . ' , ' . $infoCustomer['ward_name'] . ' , ' . $infoCustomer['district_name'] . ' , ' . $infoCustomer['province_name'] : '',
                    'is_actived' => 1,
                    'time_order' => Carbon::now()->format('Y-m-d H:i')
                ];
                //Insert thông tin giao hàng
                $mDelivery->add($dataDelivery);
            }

            // Chi tiết thanh toán
            $arrMethodWithMoney = $request->array_method;
            $mReceiptOnline = new ReceiptOnlineTable();
            $mPaymentMethod = new \Modules\Payment\Models\PaymentMethodTable();

            $isNotifyMinAccount = 0;

            foreach ($arrMethodWithMoney as $methodCode => $money) {
                $itemMethod = $mPaymentMethod->getPaymentMethodByCode($methodCode);
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $id_receipt,
                        'cashier_id' => Auth::id(),
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amount_bill) { // trừ tiên thành viên
                            if ($money < $request->member_money) {
                                //Lưu 1 dòng chi tiết thanh toán
                                $rReceiptDetail->add($dataReceiptDetail);
                                //Lấy thông tin KH
                                $customerMoney = $rCustomer->getItem($request->customer_id);
                                //Cập nhật lại tiền KH
                                $rCustomer->edit([
                                    'account_money' => $customerMoney['account_money'] - $money
                                ], $request->customer_id);
                                $mConfig = app()->get(ConfigTable::class);
                                //Lấy cấu hình số tiền tối thiểu
                                $configMinAccount = $mConfig->getInfoByKey('money_account_min')['value'];

                                if (($customerMoney['account_money'] - $money) <= $configMinAccount) {
                                    $isNotifyMinAccount = 1;
                                }
                                //Lưu log - tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $request->customer_id,
                                    "branch_id" => Auth()->id(),
                                    "source" => "member_money",
                                    "type" => 'subtract',
                                    "money" => $money,
                                    "screen" => 'order',
                                    "screen_object_code" => $infoOrder['order_code']
                                ]);
                            } else {
                                return response()->json([
                                    'error_account_money' => 1,
                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                    'money' => $request->member_money
                                ]);
                            }
                        } else {
                            return response()->json([
                                'money_large_moneybill' => 1,
                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                            ]);
                        }
                    } elseif ($methodCode == 'VNPAY') {
                        $rReceiptDetail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $id_receipt,
                            'status' => 'success'
                        ], 'order', $request->order_id, $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $rReceiptDetail->add($dataReceiptDetail);
                        // get receipt_online of method/order
                        $dataReceiptOnline = $mReceiptOnline->getReceiptOnlineByTypeAndOrderId('order', $request->order_id, $methodCode);
                        if ($dataReceiptOnline != null) {
                            // update status, receipt_id of receipt_online
                            $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                                'amount_paid' => $money,
                                'receipt_id' => $id_receipt,
                                'status' => 'success'
                            ], 'order', $request->order_id, $methodCode);
                        } else {
                            // create status, receipt_id of receipt_online
                            $dataReceiptOnline = [
                                'receipt_id' => $id_receipt,
                                'object_type' => 'order',
                                'object_id' => $request->order_id,
                                'object_code' => $request->order_code,
                                'payment_method_code' => $methodCode,
                                'amount_paid' => $money,
                                'payment_time' => Carbon::now(),
                                'status' => 'success',
                                'performer_name' => $staff['name'],
                                'performer_phone' => $staff['phone1'],
                                'type' => $itemMethod['payment_method_type'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            $mReceiptOnline->createReceiptOnline($dataReceiptOnline);
                        }
                    } else {
                        $rReceiptDetail->add($dataReceiptDetail);
                    }
                }
            }
            $data_print = [];
            if (count($list_card_print) > 0) {
                foreach ($list_card_print as $k => $v) {
                    $get_cus_card = $rServiceCardList->searchCard($v);
                    $get_sv_card = $rServiceCard->getServiceCardInfo($get_cus_card['service_card_id']);

                    $data_print[] = [
                        'customer_id' => $request->customer_id,
                        'type' => $get_sv_card['service_card_type'],
                        'card_name' => $get_cus_card['card_name'],
                        'card_code' => $get_cus_card['code'],
                        'number_using' => $get_sv_card['number_using'],
                        'date_using' => $get_sv_card['date_using'],
                        'money' => $get_sv_card['money'],
                        'service_card_id' => $get_sv_card['service_card_id'],
                    ];
                }
            }

            //TẠO PHIẾU XUẤT KHO
            $listOrderProduct = $rOrderDetail->getValueByOrderIdAndObjectType($request->order_id, 'product');
            $listService = $rOrderDetail->getValueByOrderIdAndObjectType($request->order_id, 'service');
            $listServiceMaterials = [];
            $rServiceMaterial = \app()->get(ServiceMaterialRepositoryInterface::class);
            $productBranchPrice = \app()->get(ProductBranchPriceRepositoryInterface::class);
            $rWarehouse = \app()->get(WarehouseRepositoryInterface::class);
            $rInventoryOutput = \app()->get(InventoryOutputRepositoryInterface::class);
            $rCode = \app()->get(CodeGeneratorRepositoryInterface::class);
            $isCheckProductAttach = false;
            foreach ($listService as $item) {
                //Lấy sản phẩm đi kèm dịch vụ.
                $serviceMaterial = $rServiceMaterial->getItem($item['object_id']);
                if (count($serviceMaterial) > 0) {
                    $isCheckProductAttach = true;
                    foreach ($serviceMaterial as $value) {
                        //                    dd($this->product_branch_price->getProductBranchPriceByCode(Auth::user()->branch_id, $value['material_code']));
                        $currentPrice = $productBranchPrice->getProductBranchPriceByCode(Auth::user()->branch_id, $value['material_code'])['new_price'];
                        $listServiceMaterials[] = [
                            'product_code' => $value['material_code'],
                            'quantity' => $item['quantity'] * $value['quantity'],
                            'current_price' => $currentPrice,
                            'total' => $value['quantity'] * $currentPrice * $item['quantity']
                        ];
                    }
                }
            }

            if ($isCheckProductAttach || count($listOrderProduct) > 0) {
                $checkWarehouse = $rWarehouse->getWarehouseByBranch(Auth::user()->branch_id);
                $warehouseId = 0;
                foreach ($checkWarehouse as $item) {
                    if ($item['is_retail'] == 1) {
                        $warehouseId = $item['warehouse_id'];
                    }
                }
                $mOrderDetail = app()->get(OrderDetailTable::class);

                //            Lấy danh sách sản phẩm để kiểm nếu có sản phẩm serial trạng thái là new
                $getListProductCheckStatus = $mOrderDetail->getListProductCheck($request->order_id);

                $dataInventoryOutput = [
                    'warehouse_id' => $warehouseId,
                    'po_code' => 'XK',
                    'created_by' => Auth::user()->staff_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => count($getListProductCheckStatus) != 0 ? 'new' : 'success',
                    'note' => '',
                    'type' => 'retail',
                    'object_id' => $request->order_id
                ];
                $idInventoryOutput = $rInventoryOutput->add($dataInventoryOutput);
                $idCode = $idInventoryOutput;
                if ($idInventoryOutput < 10) {
                    $idCode = '0' . $idCode;
                }
                $rInventoryOutput->edit(['po_code' => $rCode->codeDMY('XK', $idCode)], $idInventoryOutput);
            }

            // Lấy thông tin bán âm
            $mConfig = new ConfigTable();
            $configSellMinus = $mConfig->getInfoByKey('sell_minus');
            $sellMinus = 1;
            $configSellMinus != null ? $sellMinus = $configSellMinus['value'] : $sellMinus = 1;

            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $rProductInventory = \app()->get(ProductInventoryRepositoryInterface::class);
            $checkSerialQuantity = 0;
            $rInventoryOutputDetail = \app()->get(InventoryOutputDetailRepositoryInterface::class);

            $tmpListSerial = [];
            // Danh sách sản phẩm
            foreach ($listOrderProduct as $item) {

                //                kiểm tra mã sản phẩm đã được tạo trong phiếu xuất kho hay chưa
                $checkProductInventotyOutput = $mInventoryOutputDetail->checkProductInventotyOutput($idInventoryOutput, $item['object_code']);

                $getDetailOutputDetail = $mInventoryOutputDetail->checkInventoryOutput($idInventoryOutput, $item['object_code']);

                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $item['object_code'],
                    'quantity' => $getDetailOutputDetail != null ? (double)$getDetailOutputDetail['quantity'] + (double)$item['quantity'] : $item['quantity'],
                    'current_price' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['current_price'] + (float)$item['price'] : $item['price'],
                    'total' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['total'] + (float)$item['amount'] : $item['amount'],
                ];

                //                $dataInventoryOutputDetail = [
                //                    'inventory_output_id' => $idInventoryOutput,
                //                    'product_code' => $item['object_code'],
                //                    'quantity' => $item['quantity'],
                //                    'current_price' => $item['price'],
                //                    'total' => $item['amount'],
                //                ];

                if ($getDetailOutputDetail != null) {
                    $idIOD = $getDetailOutputDetail['inventory_output_detail_id'];
                    $mInventoryOutputDetail->editDetail($idIOD, $dataInventoryOutputDetail);
                } else {
                    $idIOD = $mInventoryOutputDetail->add($dataInventoryOutputDetail);
                }

                if (count($checkProductInventotyOutput) == 0) {
                    //                Lấy danh sách serial theo sản phẩm ở đơn hàng

                    $listOrderSerialDetail = $mOrderDetailSerial->getListSerialByOrder($id_order, $item['object_code']);

                    if (count($listOrderSerialDetail) == 0) {
                        $listOrderSerialDetail = $mOrderSessionSerialLog->getListProductOrder(['session' => $request->sessionSerial, 'productCode' => $item['object_code']]);
                    }

                    if (count($listOrderSerialDetail) != 0 && $dataInventoryOutputDetail['quantity'] != count($listOrderSerialDetail)) {
                        $rInventoryOutput->edit(['status' => 'new'], $idInventoryOutput);
                        $checkSerialQuantity = 1;
                    }

                    $tmpOrderSerial = [];
                    //                Tạo danh sách serial xuất kho mới
                    foreach ($listOrderSerialDetail as $itemSerial) {
                        $tmpOrderSerial[] = [
                            'inventory_output_detail_id' => $idIOD,
                            'product_code' => $item['object_code'],
                            'serial' => $itemSerial['serial'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                        $tmpListSerial[] = $itemSerial['serial'];
                    }

                    //                Thêm danh sách serial vào kho mới
                    if (count($tmpOrderSerial) != 0) {
                        $mInventoryOutputDetailSerial->insertListSerial($tmpOrderSerial);
                    }
                }

                //Trừ tồn kho.
                //Lấy id của product child bằng code. is deleted=0.
                $productId = $rProductChild->getProductChildByCode($item['object_code'])['product_child_id'];
                $checkProductInventory = $rProductInventory->checkProductInventory($item['object_code'], $warehouseId);
                $quantitiss = $checkProductInventory != null ? $checkProductInventory['quantity'] : 0 - $item['quantity'];
                // Nếu k cho bán âm thì kiểm tra số lượng trong kho có đủ cho đơn hàng không
                //                if ($sellMinus == 0 && $quantitiss < 0) {
                //                    // Lấy tên sản phẩm
                //                    DB::rollback();
                //                    return response()->json([
                //                        'error' => false,
                //                        'message' => __("Trong kho không đủ sản phẩm ") . $productId['product_child_name']
                //                    ]);
                //                }
                //
                //                if ($checkProductInventory != null) {
                //                    $dataEditProductInventory = [
                //                        'product_id' => $productId,
                //                        'product_code' => $item['object_code'],
                //                        'warehouse_id' => $warehouseId,
                //                        'export' => $item['quantity'] + $checkProductInventory['export'],
                //                        'quantity' => $quantitiss,
                //                        'updated_at' => date('Y-m-d H:i:s'),
                //                        'updated_by' => Auth::id(),
                //                    ];
                //                    $this->productInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
                //                } else {
                //                    if ($productId != null) {
                //                        $dataEditProductInventoryInsert = [
                //                            'product_id' => $productId,
                //                            'product_code' => $item['object_code'],
                //                            'warehouse_id' => $warehouseId,
                //                            'import' => 0,
                //                            'export' => $item['quantity'],
                //                            'quantity' => $quantitiss,
                //                            'created_at' => date('Y-m-d H:i:s'),
                //                            'updated_at' => date('Y-m-d H:i:s'),
                //                            'created_by' => Auth::id(),
                //                            'updated_by' => Auth::id()
                //                        ];
                //                        $this->productInventory->add($dataEditProductInventoryInsert);
                //                    }
                //                }

                $quantityss = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;;
                // Nếu k cho bán âm thì kiểm tra số lượng trong kho có đủ cho đơn hàng không
                if ($sellMinus == 0 && $quantityss < 0) {
                    // Lấy tên sản phẩm
                    DB::rollback();
                    return response()->json([
                        'error' => false,
                        'message' => __("Trong kho không đủ sản phẩm ") . $productId
                    ]);
                }

                //                Update thêm kiểm tra nếu số lượng sản phẩm không trùng với tổng số serial theo sản phẩm thì không cập nhật sản phẩm trong kho
                //                $checkSerialQuantity với 0 là trùng , 1 là không trùng
                if ($productId != null && $checkSerialQuantity == 0) {
                    if ($checkProductInventory != null) {
                        $dataEditProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $item['object_code'],
                            'warehouse_id' => $warehouseId,
                            'export' => $item['quantity']
                                + $checkProductInventory['export'],
                            'quantity' => $quantityss,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::id(),
                        ];
                        $rProductInventory->edit(
                            $dataEditProductInventory,
                            $checkProductInventory['product_inventory_id']
                        );
                    } else {
                        if ($productId != null) {
                            $dataEditProductInventoryInsert = [
                                'product_id' => $productId,
                                'product_code' => $item['object_code'],
                                'warehouse_id' => $warehouseId,
                                'import' => 0,
                                'export' => $item['quantity'],
                                'quantity' => $quantityss,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ];
                            $rProductInventory->add($dataEditProductInventoryInsert);
                        }
                    }
                }
            }


            //            Thêm serial ở kho và cập nhật serial ở phiếu nhập kho
            if (count($tmpListSerial) != 0 && $checkSerialQuantity == 0) {
                $mInventoryInputDetailSerial->updateSerialOrder($tmpListSerial, ['is_export' => 1]);
                $mProductInventorySerial->updateByArrSerial($tmpListSerial, ['status' => 'export']);
            }

            if (count($listServiceMaterials) > 0) {
                foreach ($listServiceMaterials as $item) {
                    $dataInventoryOutputDetail = [
                        'inventory_output_id' => $idInventoryOutput,
                        'product_code' => $item['product_code'],
                        'quantity' => $item['quantity'],
                        'current_price' => $item['current_price'],
                        'total' => $item['total'],
                    ];
                    $idIOD = $rInventoryOutputDetail->add($dataInventoryOutputDetail);
                    //Trừ tồn kho.
                    $productId = $rProductChild->getProductChildByCode($item['product_code'])['product_child_id'];
                    $checkProductInventory = $rProductInventory->checkProductInventory($item['product_code'], $warehouseId);
                    $quantityss = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;;
                    // Nếu k cho bán âm thì kiểm tra số lượng trong kho có đủ cho đơn hàng không
                    if ($sellMinus == 0 && $quantitiss < 0) {
                        // Lấy tên sản phẩm
                        DB::rollback();
                        return response()->json([
                            'error' => false,
                            'message' => __("Trong kho không đủ sản phẩm ") . $productId
                        ]);
                    }
                    if ($productId != null) {
                        if ($checkProductInventory != null) {
                            $dataEditProductInventory = [
                                'product_id' => $productId,
                                'product_code' => $item['product_code'],
                                'warehouse_id' => $warehouseId,
                                'export' => $item['quantity'] + $checkProductInventory['export'],
                                'quantity' => $quantityss,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'updated_by' => Auth::id(),
                            ];
                            $rProductInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
                        } else {
                            if ($productId != null) {
                                $dataEditProductInventoryInsert = [
                                    'product_id' => $productId,
                                    'product_code' => $item['product_code'],
                                    'warehouse_id' => $warehouseId,
                                    'import' => 0,
                                    'export' => $item['quantity'],
                                    'quantity' => $quantityss,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id()
                                ];
                                $rProductInventory->add($dataEditProductInventoryInsert);
                            }
                        }
                    }
                }
            }

            $smsConfig = \app()->get(SmsConfigRepositoryInterface::class);
            $checkSendSms = $smsConfig->getItemByType('paysuccess');

            if (isset($request->order_source_id) && $request->order_source_id == 2) {
                //Cập nhật trạng thái đơn hàng cần giao
                $mDelivery = new DeliveryTable();
                $mDelivery->edit([
                    'is_actived' => 1
                ], $request->order_id);
            }

            //Insert order log hoàn tất đơn hàng
            $mOrderLog->insert([
                'order_id' => $request->order_id,
                'created_type' => 'backend',
                'status' => 'ordercomplete',
                //                'note' => __('Hoàn tất'),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'note_vi' => 'Hoàn tất',
                'note_en' => 'Order completed',
            ]);
            // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
            if (isset($request->arrAppointment)) {
                $arrAppointment = $request->arrAppointment;
                if ($arrAppointment['checked'] == 1) {
                    // Thêm lịch hẹn
                    $result = $this->_addQuickAppointment($arrAppointment, $request->customer_id);
                    if ($result['error'] == false) {
                        return response()->json($result);
                    }
                }
            }
            // END UPDATE

            //Insert phiếu bảo hành điện tử
            $customer = $rCustomer->getItem($request->customer_id);
            $dataTableAdd = $request->table_add;
            $dataTableEdit = $request->table_edit;

            if ($customer['customer_code'] != null) {
                $rOrder->addWarrantyCard($customer['customer_code'], $request->order_id, $request->order_code, $dataTableAdd, $dataTableEdit);
            }

            //Lưu log dự kiến nhắc sử dụng lại
            $rOrder->insertRemindUse($request->order_id, $request->customer_id, $arrRemindUse);

            //Lưu thông tin hàng hoá cho hợp đồng
            $rOrder->updateContractGoods($request->order_id, 1);

            DB::commit();

            return response()->json([
                'error' => true,
                'message' => 'Thanh toán thành công',
                'print_card' => $data_print,
                'orderId' => $request->order_id,
                'isSMS' => $checkSendSms['is_active']
            ]);

            // gửi thông báo khi là khách hàng
            if ($request->customer_id != 1) {
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'paysuccess',
                    'object_id' => $request->order_id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'paysuccess',
                    'object_id' => $request->order_id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Send notification
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_S',
                    'customer_id' => $request->customer_id,
                    'object_id' => $request->order_id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS (thanh toán thành công)
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_thanks',
                    'customer_id' => $request->customer_id,
                    'object_id' => $request->order_id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                if ($isNotifyMinAccount == 1) {
                    //Gửi thông báo tiền trong tài khoản sắp hết
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'money_account_min',
                        'customer_id' => $request->customer_id,
                        'object_id' => $request->order_id,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }
            }

            $bookingApi = \app()->get(BookingApi::class);
            //Tính điểm thưởng khi thanh toán
            if ($amount_receipt_all >= $amount_bill) {
                $bookingApi->plusPointReceiptFull(['receipt_id' => $id_receipt]);
            } else {
                $bookingApi->plusPointReceipt(['receipt_id' => $id_receipt]);
            }

            return response()->json([
                'error' => true,
                'message' => 'Thanh toán thành công',
                'print_card' => $data_print,
                'orderId' => $request->order_id,
                'isSMS' => $checkSendSms['is_active']
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * thêm lịch hẹn nhanh
     *
     * @param $data
     * @param $customerId
     * @return array|false[]
     * @throws \MyCore\Api\ApiException
     */
    public function _addQuickAppointment($data, $customerId)
    {
        $now = Carbon::now();
        $date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');
        $time = $data['time'];

        $timeAppointment = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        // kiểm tra lịch hẹn phải > hiện tại
        if ($timeAppointment->lessThanOrEqualTo($now)) {
            return [
                'error' => false,
                'message' => __('Ngày hẹn, giờ hẹn không hợp lệ')
            ];
        }
        $rStaff = \app()->get(StaffRepositoryInterface::class);

        $staff = $rStaff->getItem(Auth::id()); // Thông tin nhân viên
        $dataInsert = [
            'customer_id' => $customerId,
            'time' => $time,
            'date' => $date,
            'description' => 'Lich hen nhanh tu don hang',
            'customer_appointment_type' => 'appointment',
            'appointment_source_id' => 1, // Truc tiep
            'customer_quantity' => 1,
            'branch_id' => $staff['branch_id'],
            'status' => 'new',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'time_type' => 'R',
            'number_start' => 0,
        ];

        //Kiểm tra ngày giờ hẹn của user đã có chưa
        $mBooking = new CustomerAppointmentTable();
        $rSmsLog = \app()->get(SmsLogRepositoryInterface::class);
        $checkBooking = $mBooking->checkDateTimeCustomer($customerId, $date, $time, Auth::user()->branch_id, '');
        if ($checkBooking != null) {
            return [
                'error' => false,
                'message' => __('Bạn đã có lịch hẹn vào khung giờ này'),
            ];
        }
        // default: customer type: appointment (đặt lịch trước)
        $appointmentId = $mBooking->add($dataInsert);
        $appointmentCode = 'LH_' . date('dmY') . $appointmentId;
        $mBooking->edit([
            'customer_appointment_code' => $appointmentCode
        ], $appointmentId);
        // default: status: new
        CheckMailJob::dispatch('is_event', 'new_appointment', $appointmentId);
        $rSmsLog->getList('new_appointment', $appointmentId);
        //Lưu log ZNS
        SaveLogZns::dispatch('new_appointment', $customerId, $appointmentId);
        //Insert notification log
        $mNotify = new SendNotificationApi();
        $mNotify->sendNotification([
            'key' => 'appointment_W',
            'customer_id' => $customerId,
            'object_id' => $appointmentId
        ]);
        //Gửi thông báo NV có LH mới
        $mNotify->sendStaffNotification([
            'key' => 'appointment_W',
            'customer_id' => $customerId,
            'object_id' => $appointmentId,
            'branch_id' => Auth()->user()->branch_id
        ]);

        $rServiceBranchPrice = \app()->get(ServiceBranchPriceRepositoryInterface::class);
        $rOrder = \app()->get(OrderRepositoryInterface::class);
        $dataDetail = [];
        if (isset($data['table_quantity']) && $data['table_quantity'] != null) {
            $branchId = Auth()->user()->branch_id;
            // Nếu có data dịch vụ or thẻ liệu trình mới lưu
            $total = 0;
            foreach ($data['table_quantity'] as $key => $value) {
                if ($value['sv'] != null) {
                    //Nếu có data dịch vụ or thẻ liệu trình mới lưu
                    foreach ($value['sv'] as $k => $v) {
                        $price = 0;
                        if ($value['object_type'] == 'service') {
                            //Lấy giá chi nhánh sản phẩm
                            $priceBranch = $rServiceBranchPrice->getItemByBranchIdAndServiceId($branchId, $v);
                            // time_type = R, numberDay = 0: Config ko đặt lịch từ ngày đến ngày

                            //Lấy giá KM của dv
                            $getPrice = $rOrder->getPromotionDetail(
                                'service',
                                $priceBranch['service_code'],
                                $customerId,
                                'live',
                                1
                            );
                            // Nếu có nhưng promotion > giá base thì lấy giá base
                            if ($getPrice != null && $getPrice > $priceBranch['new_price']) {
                                $getPrice = $priceBranch['new_price'];
                            }
                            // Nếu không có promotion
                            if ($getPrice == null) {
                                $getPrice = $priceBranch['new_price'];
                            }
                            $dataDetail[] = [
                                'customer_appointment_id' => $appointmentId,
                                'service_id' => $v,
                                'staff_id' => $value['staff'],
                                'room_id' => $value['room'],
                                'customer_order' => $value['stt'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'price' => (float)$getPrice,
                                'object_type' => $value['object_type'],
                                'object_id' => $v,
                                'object_code' => $priceBranch['service_code'],
                                'object_name' => $priceBranch['service_name'],
                                'is_check_promotion' => 1,
                                'created_at' => date('Y-m-d H:i')
                            ];
                            $total += $getPrice;
                        } else {
                            $dataDetail[] = [
                                'customer_appointment_id' => $appointmentId,
                                'service_id' => $v,
                                'staff_id' => $value['staff'],
                                'room_id' => $value['room'],
                                'customer_order' => $value['stt'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'price' => (float)$price,
                                'object_type' => $value['object_type'],
                                'object_id' => $v,
                                'object_code' => '',
                                'object_name' => null,
                                'is_check_promotion' => 0,
                                'created_at' => date('Y-m-d H:i')
                            ];
                        }
                    }
                }
            }
            // Cập nhật total, amount, discount cho customer-appointment
            $dataUpdate = [
                'total' => $total,
                'discount' => 0,
                'amount' => $total
            ];
            $mBooking->edit($dataUpdate, $appointmentId);
        }
        //Lưu chi tiết lịch hẹn
        $mAppointmentDetail = new CustomerAppointmentDetailTable();
        $mAppointmentDetail->insert($dataDetail);
        //Insert log lịch hẹn
        $this->_insertLogAdd($appointmentId, 'new');
        //Cộng điểm
        $this->_plusPoint($appointmentId, 1, $customerId);
        return [
            'error' => true
        ];
    }

    /**
     * Lưu log khi tạo lịch hẹn
     *
     * @param $appointmentId
     * @param $status
     */
    protected function _insertLogAdd($appointmentId, $status)
    {
        $mAppointmentLog = new CustomerAppointmentLogTable();

        switch ($status) {
            case 'new':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                break;
            case 'confirm':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log confirm
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'confirm',
                    'note' => __('Xác nhận lịch hẹn'),
                    'created_by' => Auth()->id()
                ]);
                break;
            case 'wait':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log confirm
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'confirm',
                    'note' => __('Xác nhận lịch hẹn'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log wait
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'wait',
                    'note' => __('Lịch hẹn được cập nhật đang phục vụ từ backend'),
                    'created_by' => Auth()->id()
                ]);
                break;
        }
    }

    /**
     * Cộng điểm khi thêm lịch hẹn
     *
     * @param $appointmentId
     * @param $appointmentSrcId
     * @param $customerId
     * @throws \MyCore\Api\ApiException
     */
    protected function _plusPoint($appointmentId, $appointmentSrcId, $customerId)
    {
        $mPlusPoint = new LoyaltyApi();
        switch ($appointmentSrcId) {
            case 1:
                //Cộng điểm khi đặt lịch trực tiếp
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_direct',
                    'object_id' => $appointmentId
                ]);
                break;
            case 2:
                //Cộng điểm khi đặt lịch từ facebook
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_fb',
                    'object_id' => $appointmentId
                ]);
                break;
            case 3:
                //Cộng điểm khi đặt lịch từ zalo
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_zalo',
                    'object_id' => $appointmentId
                ]);
                break;
            case 4:
                //Cộng điểm khi đặt lịch bằng gọi điện
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_call',
                    'object_id' => $appointmentId
                ]);
                break;
            case 5:
                //Cộng điểm khi đặt lịch online
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_online',
                    'object_id' => $appointmentId
                ]);
                break;
        }
    }

    /**
     * Hiển thị popup gộp bàn
     * @param Request $request
     */
    public function mergeTable(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $param['type'] = 'merge-table';
        $data = $order->popupSelectTable($param);
        return \response()->json($data);
    }

    /**
     * Hiển thị popup gộp bill
     * @param Request $request
     */
    public function mergeBill(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $param['type'] = 'merge-bill';
        $data = $order->popupSelectTable($param);
        return \response()->json($data);
    }

    /**
     * Hiển thị popup di chuyển bàn
     * @param Request $request
     */
    public function moveTable(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $param['type'] = 'move-table';
        $data = $order->popupSelectTable($param);
        return \response()->json($data);
    }

    /**
     * Hiển thị popup tách bàn
     * @param Request $request
     */
    public function splitTable(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $param['type'] = 'split-table';
        $data = $order->popupSelectTable($param);
        return \response()->json($data);
    }


    /**
     * Chọn khu vực
     * @param Request $request
     */
    public function changeArea(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->changeArea($param);
        return \response()->json($data);
    }

    /**
     * Tìm kiếm bàn muốn chuyển
     * @param Request $request
     */
    public function searchOrder(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->searchOrder($param);
        return \response()->json($data);
    }

    /**
     * Lưu thông tin gộp bàn
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submitMergeTable(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->submitMergeTable($param);
        return \response()->json($data);
    }

    /**
     * Lưu thông tin gộp bill
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submitMergeBill(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->submitMergeBill($param);
        return \response()->json($data);
    }

    /**
     * Lưu thông tin chuyển bàn
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submitMoveTable(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->submitMoveTable($param);
        return \response()->json($data);
    }

    /**
     * Tách bàn
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submitSplitTable(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->submitSplitTable($param);
        return \response()->json($data);
    }

    /**
     * Hiển thị popup danh sách đơn hàng cần in
     * @param Request $request
     */
    public function showPopupOrderTable(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->showPopupOrderTable($param);
        return \response()->json($data);
    }

    /**
     * Hiển thị popup danh sách yêu cầu của khách hàng
     * @param Request $request
     */
    public function showPopupCustomerRequest(Request $request){
        $param = $request->all();
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->showPopupCustomerRequest($param);
        return \response()->json($data);
    }

    public function confirmCustomerRequest(Request $request){
        $param = $request->all();
        $rCustomerRequest = app()->get(FNBCustomerRequestRepositoryInterface::class);
        $data = $rCustomerRequest->confirmCustomerRequest($param);
        return \response()->json($data);
    }

    public function changeInfoAddress(Request $request){
        $order = app()->get(OrderRepositoryInterface::class);
        $data = $order->changeInfoAddress($request->all());
        return response()->json($data);
    }

}
