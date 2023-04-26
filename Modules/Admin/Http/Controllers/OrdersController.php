<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/27/2018
 * Time: 1:21 PM
 */

namespace Modules\Admin\Http\Controllers;

use App\Jobs\SaveLogZns;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\Admin\Http\Requests\Order\SubmitAddressRequest;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\ContractMapOrderTable;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\CustomerAppointmentLogTable;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\CustomerBranchMoneyLogTable;
use Modules\Admin\Models\CustomerBranchMoneyTable;
use Modules\Admin\Models\CustomerBranchTable;
use Modules\Admin\Models\CustomerContactTable;
use Modules\Admin\Models\CustomerDebtTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\CustomerServiceCardTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DeliveryCustomerAddressTable;
use Modules\Admin\Models\DeliveryTable;
use Modules\Admin\Models\DiscountCauseTable;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryInputDetailTable;
use Modules\Admin\Models\InventoryInputTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailTable;
use Modules\Admin\Models\OrderConfigTabTable;
use Modules\Admin\Models\OrderDetailSerialTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\OrderImageTable;
use Modules\Admin\Models\OrderLogTable;
use Modules\Admin\Models\OrderSessionSerialTable;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\PaymentMethodTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Models\PromotionLogTable;
use Modules\Admin\Models\ProvinceTable;
use Modules\Admin\Models\ReceiptDetailTable;
use Modules\Admin\Models\ReceiptOnlineTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\RoomTable;
use Modules\Admin\Models\ServiceMaterialTable;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Models\StaffTable;
use Modules\Admin\Models\ServiceCardList;
use Modules\Admin\Models\WardTable;
use Modules\Admin\Models\WarrantyCardTable;
use Modules\Admin\Repositories\Notification\NotificationRepoInterface;
use Modules\Admin\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\Delivery\Models\DeliveryCostDetailTable;
use Modules\Delivery\Models\DeliveryCostTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function Couchbase\defaultDecoder;
use Illuminate\Support\Collection;
use App\Jobs\CheckMailJob;
use App\Jobs\CheckMailPrintCardJob;
use App\Mail\SendMailable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Admin\Http\Api\BookingApi;
use Modules\Admin\Http\Requests\Order\ApplyBranchRequest;
use Modules\Admin\Libs\help\Help;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\ConfigPrintBill\ConfigPrintBillRepositoryInterface;
use Modules\Admin\Repositories\ConfigPrintServiceCard\ConfigPrintServiceCardRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Admin\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\Admin\Repositories\CustomerDebt\CustomerDebtRepositoryInterface;
use Modules\Admin\Repositories\CustomerServiceCard\CustomerServiceCardRepositoryInterface;
use Modules\Admin\Repositories\EmailLog\EmailLogRepositoryInterface;
use Modules\Admin\Repositories\EmailProvider\EmailProviderRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\PrintBillLog\PrintBillLogRepositoryInterface;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\ProductBranchPrice\ProductBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\Admin\Repositories\ReceiptDetail\ReceiptDetailRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceBranchPrice\ServiceBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Modules\Admin\Repositories\ServiceMaterial\ServiceMaterialRepositoryInterface;
use Modules\Admin\Repositories\SmsConfig\SmsConfigRepositoryInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\Voucher\VoucherRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;
use Modules\Booking\Models\PointHistoryTable;
use config;
use Session;
use App;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Catch_;

class OrdersController extends Controller
{
    protected $order;
    protected $product;
    protected $service;
    protected $service_card;
    protected $customer;
    protected $code;
    protected $order_detail;
    protected $product_child;
    protected $voucher;
    protected $receipt;
    protected $receipt_detail;
    protected $customer_service_card;
    protected $service_card_list;
    protected $staff;
    protected $customer_branch_money;
    protected $service_branch_price;
    protected $product_branch_price;
    protected $smsLog;
    protected $spaInfo;
    protected $configPrintBill;
    protected $printBillLog;
    protected $inventoryOutput;
    protected $warehouse;
    protected $inventoryOutputDetail;
    protected $productInventory;
    protected $productChild;
    protected $serviceMaterial;
    protected $smsConfig;
    protected $branch;
    protected $config_print_service_card;
    protected $email_log;
    protected $email_provider;
    protected $help;
    protected $customer_appointment;
    protected $spa_info;
    protected $customer_debt;
    protected $order_commission;
    protected $bookingApi;
    protected $pointHistory;

    public function __construct(
        OrderRepositoryInterface $orders,
        ProductRepositoryInterface $products,
        ServiceRepositoryInterface $services,
        ServiceCardRepositoryInterface $service_cards,
        CustomerRepository $customers,
        CodeGeneratorRepositoryInterface $codes,
        OrderDetailRepositoryInterface $order_details,
        ProductChildRepositoryInterface $product_childs,
        VoucherRepositoryInterface $vouchers,
        ReceiptRepositoryInterface $receipts,
        ReceiptDetailRepositoryInterface $receipt_details,
        CustomerServiceCardRepositoryInterface $customer_service_cards,
        ServiceCardListRepositoryInterface $service_card_lists,
        StaffRepositoryInterface $staffs,
        CustomerBranchMoneyRepositoryInterface $customer_branch_moneys,
        ServiceBranchPriceRepositoryInterface $service_branch_prices,
        ProductBranchPriceRepositoryInterface $product_branch_prices,
        SmsLogRepositoryInterface $smsLog,
        SpaInfoRepositoryInterface $spaInfo,
        ConfigPrintBillRepositoryInterface $configPrintBill,
        PrintBillLogRepositoryInterface $printBillLog,
        InventoryOutputRepositoryInterface $inventoryOutput,
        WarehouseRepositoryInterface $warehouse,
        InventoryOutputDetailRepositoryInterface $inventoryOutputDetail,
        ProductInventoryRepositoryInterface $productInventory,
        ProductChildRepositoryInterface $productChild,
        ServiceMaterialRepositoryInterface $serviceMaterial,
        SmsConfigRepositoryInterface $smsConfig,
        BranchRepositoryInterface $branch,
        ConfigPrintServiceCardRepositoryInterface $config_print_service_card,
        EmailLogRepositoryInterface $email_log,
        EmailProviderRepositoryInterface $email_provider,
        Help $help,
        CustomerAppointmentRepositoryInterface $customer_appointment,
        SpaInfoRepositoryInterface $spa_info,
        CustomerDebtRepositoryInterface $customer_debt,
        OrderCommissionRepositoryInterface $order_commission,
        BookingApi $bookingApi,
        PointHistoryTable $pointHistory
    ) {
        $this->order = $orders;
        $this->product = $products;
        $this->service = $services;
        $this->service_card = $service_cards;
        $this->customer = $customers;
        $this->code = $codes;
        $this->order_detail = $order_details;
        $this->product_child = $product_childs;
        $this->voucher = $vouchers;
        $this->receipt = $receipts;
        $this->receipt_detail = $receipt_details;
        $this->customer_service_card = $customer_service_cards;
        $this->service_card_list = $service_card_lists;
        $this->staff = $staffs;
        $this->customer_branch_money = $customer_branch_moneys;
        $this->service_branch_price = $service_branch_prices;
        $this->product_branch_price = $product_branch_prices;
        $this->smsLog = $smsLog;
        $this->spaInfo = $spaInfo;
        $this->configPrintBill = $configPrintBill;
        $this->printBillLog = $printBillLog;
        $this->inventoryOutput = $inventoryOutput;
        $this->warehouse = $warehouse;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
        $this->productInventory = $productInventory;
        $this->productChild = $productChild;
        $this->serviceMaterial = $serviceMaterial;
        $this->smsConfig = $smsConfig;
        $this->branch = $branch;
        $this->config_print_service_card = $config_print_service_card;
        $this->email_log = $email_log;
        $this->email_provider = $email_provider;
        $this->help = $help;
        $this->customer_appointment = $customer_appointment;
        $this->spa_info = $spa_info;
        $this->customer_debt = $customer_debt;
        $this->order_commission = $order_commission;
        $this->bookingApi = $bookingApi;
        $this->pointHistory = $pointHistory;
    }

    const LIVE = 1;


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $data = $this->order->list(['orders$order_source_id' => 1]);

        return view('admin::orders.index', [
            'LIST' => $data['list'],
            'receipt' => $data['receipt'],
            //            'receiptDetail' => $data['receiptDetail'],
            'FILTER' => $this->filters(),
        ]);
    }

    protected function filters()
    {
        $optionBranch = $this->branch->getBranch();
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
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_type',
            'search',
            'branches$branch_id',
            'created_at',
            'orders$process_status',
            'receive_at_counter',
            'orders$customer_id',
            'order_source_id'
        ]);

        if (isset($filter['orders$customer_id']) && $filter['orders$customer_id'] == null) {
            $filter['orders$order_source_id'] = 1;
        }

        $data = $this->order->list($filter);

        return view('admin::orders.list', [
            'LIST' => $data['list'],
            'receipt' => $data['receipt'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm đơn hàng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function addAction(Request $request)
    {
        $mCustomerGroup = new CustomerGroupTable();
        $mProvince = new ProvinceTable();
        $mPaymentMethod = new PaymentMethodTable();
        $mConfig = new ConfigTable();
        $mDiscountCause = new DiscountCauseTable();

        $customerLoad = null;
        $listMemberCard = [];

        if (isset($request->customer_id) && $request->customer_id != null) {
            $mCustomer = new CustomerTable();
            $mCustomerMoney = new CustomerBranchMoneyTable();
            //Lấy thông tin khách hàng
            $infoCustomer = $mCustomer->getItem($request->customer_id);

            if ($infoCustomer == null) {
                return redirect()->route('admin.order');
            }

            $infoCustomer['money'] = $mCustomerMoney->getPriceBranch($infoCustomer['customer_id'], Auth()->user()->branch_id);
            //Lấy tông tin thẻ liệu trình
            $mMemberCard = new CustomerServiceCardTable();
            $listMemberCard = $mMemberCard->getMemberCard($infoCustomer['customer_id'], Auth::user()->branch_id);

            $mDebt = app()->get(CustomerDebtTable::class);
            //Lấy công nợ của KH
            $amountDebt = $mDebt->getItemDebt($request->customer_id);

            $debt = 0;
            if (count($amountDebt) > 0) {
                foreach ($amountDebt as $item) {
                    $debt += $item['amount'] - $item['amount_paid'];
                }
            }

            $infoCustomer['debt'] = $debt;

            $listMemberCard = $listMemberCard;
            $customerLoad = $infoCustomer;
        }

        $session = Carbon::now()->format('YmdHisu');

        $customer_default = $this->customer->getCustomerOption();
        //Lấy nv phục vụ
        $staff_technician = $this->staff->getStaffTechnician();
        //Lấy nhóm khách hàng
        $customer_group = $mCustomerGroup->getOption();
        //Lấy option tỉnh thành
        $province = $mProvince->getOptionProvince();
        //Lấy hình thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy cấu hình thay đổi giá
        $customPrice = $mConfig->getInfoByKey('customize_price')['value'];
        //Lấy option lý do giảm giá
        $optionDiscountCause = $mDiscountCause->getOption();
        //Lấy option dịch vụ
        $optionService = $this->service_branch_price->getOptionService(Auth()->user()->branch_id);
        //Lấy option phòng
        $mRoom = new RoomTable();
        $optionRoom = [];
        foreach ($mRoom->getRoomOption() as $item) {
            $optionRoom[$item['room_id']] = $item['name'];
        }
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
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

        $params = $request->all();

        $mConfigTab = app()->get(OrderConfigTabTable::class);

        //Lấy cấu hình tab
        $getTab = $mConfigTab->getConfigTab();

        return view('admin::orders.add', [
            'listPhone' => $customer_default,
            'staff_technician' => $staff_technician,
            'customer_refer' => $customer_default,
            'customer_group' => $customer_group,
            'province' => $province,
            'optionPaymentMethod' => $optionPaymentMethod,
            'customPrice' => $customPrice,
            'optionDiscountCause' => $optionDiscountCause,
            'customerLoad' => $customerLoad,
            'listMemberCard' => $listMemberCard,
            'optionStaff' => $staff_technician,
            'optionService' => $optionService,
            'optionRoom' => $optionRoom,
            'configToDate' => $configToDate,
            'is_edit_full' => $is_edit_full,
            'is_edit_staff' => $is_edit_staff,
            'is_payment_order' => $is_payment_order,
            'is_update_order' => $is_update_order,
            'session' => $session,
            'params' => $params,
            'getTab' => $getTab
        ]);
    }

    /**
     * Tab sản phẩm, dịch vụ, thẻ dịch vụ bán hàng khi chọn tab
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function listAddAction(Request $request)
    {
        $type = $request->object_type;
        $arr = [];

        if ($type == 'service') {
            $mServiceMaterial = app()->get(ServiceMaterialTable::class);

            //Lấy dịch vụ theo chi nhánh
            $price_branch = $this->service_branch_price->getItemBranch(Auth()->user()->branch_id, $request->category_id, $request->search, $request->page);

            foreach ($price_branch as $item) {
                $getPrice = $this->order->getPromotionDetail($type, $item['service_code'], $request->customer_id, 'live', 1);

                if ($getPrice != null && $getPrice > $item['new_price']) {
                    $getPrice = $item['new_price'];
                }

                //Kiểm tra có sản phẩm/dịch vụ kèm theo
                $isObjectAttach = 0;

                //Kiểm tra có sản phẩm/dịch vụ kèm theo
                $checkObjectAttach = $mServiceMaterial->getServiceMaterial($item['service_id'], Auth()->user()->branch_id);

                if (count($checkObjectAttach) > 0) {
                    $isObjectAttach = 1;
                }

                $arr[] = [
                    'name' => $item['service_name'],
                    'id' => $item['service_id'],
                    'price' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'price_hidden' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'type' => $type,
                    'avatar' => $item['service_avatar'],
                    'code' => $item['service_code'],
                    'is_sale' => $getPrice != null ? 1 : 0,
                    'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'is_surcharge' => $item['is_surcharge'],
                    'is_object_attach' => $isObjectAttach
                ];
            }
        }
        if ($type == 'service_card') {
            $list = $this->service_card->getListAdd($request->category_id, $request->search, $request->page);

            foreach ($list as $item) {
                $getPrice = $this->order->getPromotionDetail($type, $item['code'], $request->customer_id, 'live', 1);

                if ($getPrice != null && $getPrice > $item['price']) {
                    $getPrice = $item['price'];
                }

                //Kiểm tra có sản phẩm/dịch vụ kèm theo
                $isObjectAttach = 0;

                $arr[] = [
                    'name' => $item['name'],
                    'id' => $item['service_card_id'],
                    'price' => number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'price_hidden' => number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'avatar' => $item['image'],
                    'code' => $item['code'],
                    'type' => $type,
                    'is_sale' => $getPrice != null ? 1 : 0,
                    'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'is_surcharge' => $item['is_surcharge'],
                    'is_object_attach' => $isObjectAttach
                ];
            }
        }
        if ($type == 'product') {
            //Lấy giới hạn 16 sản phẩm
            $list = $this->product_branch_price->getItemBranchLimit(Auth()->user()->branch_id, $request->category_id, $request->search, $request->page);

            foreach ($list as $item) {
                $getPrice = $this->order->getPromotionDetail('product', $item['product_code'], $request->customer_id, 'live', 1);

                if ($getPrice != null && $getPrice > $item['new_price']) {
                    $getPrice = $item['new_price'];
                }

                //Kiểm tra có sản phẩm/dịch vụ kèm theo
                $isObjectAttach = 0;

                $arr[] = [
                    'name' => $item['product_child_name'],
                    'id' => $item['product_child_id'],
                    'price' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'price_hidden' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'avatar' => $item['avatar'],
                    'code' => $item['product_code'],
                    'type' => $type,
                    'is_sale' => $getPrice != null ? 1 : 0,
                    'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'is_surcharge' => $item['is_surcharge'],
                    'inventory_management' => $item['inventory_management'],
                    'is_object_attach' => $isObjectAttach
                ];
            }
        }
        if ($type == 'member_card') {
            $id = $request->customer_id;
            $staff = $this->staff->getItem(Auth::id());
            $list_card_active = $this->customer_service_card->searchCardMember($request->search, $id, $staff['branch_id'], $request->page);
            foreach ($list_card_active as $key => $item) {
                if ($item['expired_date'] == null) {
                    if ($item['number_using'] == 0) {
                        $arr[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => __('Không giới hạn'),
                        ];
                    } else {
                        if ($item['number_using'] > $item['count_using']) {
                            $arr[] = [
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
                            $arr[] = [
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
                                $arr[] = [
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
        }

        $view = view('admin::orders.inc.list-product', [
            'list' => $arr,
            'type' => $type
        ])->render();

        return response()->json($view);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerAction(Request $request)
    {
        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
        $mConfig = new ConfigTable();

        $data = $request->all();

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        if (isset($data['search'])) {
            //Lấy thông tin tìm kiếm khách hàng
            $value = $this->customer->getCustomerSearch($data['search']);

            $search = [];
            foreach ($value as $item) {
                //Lấy tổng tiền thành viên cộng vào
                $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($item['customer_id'], $branchId, self::PLUS);
                //Lấy tổng tiền thành viên trừ ra
                $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($item['customer_id'], $branchId, self::SUBTRACT);

                $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
                //Lấy tiền thành viên
                $money_customer = $accountMoney > 0 ? $accountMoney : 0;

                $mWard = app()->get(WardTable::class);

                $listWard = $mWard->getOptionWard($item['district_id']);

                $viewWard = view('admin::orders.inc.option-ward', [
                    'listWard' => $listWard,
                    'ward_id' => $item['ward_id']
                ])->render();

                $search['results'][] = [
                    'id' => $item['customer_id'],
                    'text' => $item['full_name'] . ' - ' . $item['phone1'],
                    'address' => $item['address'],
                    'name' => $item['full_name'],
                    'phone' => $item['phone1'],
                    'image' => $item['customer_avatar'],
                    'money' => $money_customer,
                    'group_name' => $item['group_name'],
                    'postcode' => $item['postcode'],
                    'customer_group_id' => $item['customer_group_id'],
                    'province_id' => $item['province_id'],
                    'district_id' => $item['district_id'],
                    'ward_id' => $item['ward_id'],
                    'province_name' => $item['province_name'],
                    'district_name' => $item['district_name'],
                    'viewWard' => $viewWard
                ];
            }
            return response()->json($search);
        }
    }

    /**
     * Thêm khách hàng nhanh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCustomerAction(Request $request)
    {
        $mCustomerBranch = new CustomerBranchTable();

        //Kiểm tra sđt đã tồn tại chưa
        $testPhone = $this->customer->testPhone($request->phone, 0);

        if ($testPhone == null) {
            $data = [
                'customer_group_id' => $request->customer_group_id,
                'full_name' => $request->full_name,
                'phone1' => $request->phone,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'postcode' => $request->postcode,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'branch_id' => Auth()->user()->branch_id,
                'gender' => 'other',
                'customer_source_id' => 1,
                'is_actived' => 1,
                'address' => $request->address
            ];
            //Thêm khách hàng
            $id_add = $this->customer->add($data);

            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);

            $contactId = $mCustomerContact->addAddress([
                'customer_id' => $id_add,
                'contact_name' => $request->full_name,
                'contact_phone' => $request->phone,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'full_address' => $request->address,
                'type_address' => $request->customer_group_id == 1 ? 'home' : 'office',
                'address_default' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);

            //Cập nhật địa chỉ liên hệ
            $mCustomerContact->edit([
                'customer_contact_code' => 'CC_' . date('dmY') . sprintf("%02d", $contactId)
            ], $contactId);

            if ($id_add < 10) {
                $id_add = '0' . $id_add;
            }
            //Cập nhật mã khách hàng
            $this->customer->edit([
                'customer_code' => 'KH_' . date('dmY') . $id_add
            ], $id_add);
            //Tự động insert chi nhánh và lấy customer_id ra
            $mCustomerBranch->add([
                'customer_id' => $id_add,
                'branch_id' => Auth()->user()->branch_id
            ]);
            ///Append khách hàng mới
            $item_customer = $this->customer->getItem($id_add);
            $data_customer = [
                'id' => $item_customer['customer_id'],
                'full_name' => $item_customer['full_name'],
                'phone' => $item_customer['phone1'],
                'image' => $item_customer['customer_avatar'],
                'address' => $item_customer['address'],
                'province_name' => $item_customer['province_name'],
                'district_name' => $item_customer['district_name'],
                'postcode' => $item_customer['postcode'],
            ];

            return response()->json([
                'error' => 0,
                'customer' => $data_customer
            ]);
        } else {
            $mConfig = new ConfigTable();
            //Kiểm tra KH đó có ở chi nhánh này không
            $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($testPhone['customer_id'], Auth()->user()->branch_id);

            if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch', session('routeList'))) {
                return response()->json([
                    'error' => 1,
                    'message' => __('Số điện thoại đã tồn tại')
                ]);
            }

            //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
            $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

            if ($getInsertBranch == 1) {
                //Tự động insert chi nhánh và lấy customer_id ra
                $mCustomerBranch->add([
                    'customer_id' => $testPhone['customer_id'],
                    'branch_id' => Auth()->user()->branch_id
                ]);

                ///Append khách hàng mới
                $item_customer = $this->customer->getItem($testPhone['customer_id']);
                $data_customer = [
                    'id' => $item_customer['customer_id'],
                    'full_name' => $item_customer['full_name'],
                    'phone' => $item_customer['phone1'],
                    'image' => $item_customer['customer_avatar'],
                    'address' => $item_customer['address'],
                    'province_name' => $item_customer['province_name'],
                    'district_name' => $item_customer['district_name'],
                    'postcode' => $item_customer['postcode'],
                ];

                return response()->json([
                    'error' => 0,
                    'customer' => $data_customer
                ]);
            } else {
                return response()->json([
                    'error' => 1,
                    'message' => __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này')
                ]);
            }
        }
    }

    /**
     * Giảm giá trên từng item
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDiscountAction(Request $request)
    {
        $amount = $request->amount;
        $discount = $request->discount;
        $type = $request->type;
        $voucher_code = strip_tags($request->voucher_code);
        $type_order = $request->type_order;
        $id_order = $request->id_order;
        $amount_bill = $request->amount_bill;
        $customer_id = $request->customer_id;

        if ($voucher_code == null) {
            //Giảm tiền trực tiếp
            if ($type == 1) {
                if ($discount > $amount) {
                    return response()->json([
                        'error_money' => 1,
                    ]);
                } else {
                    return response()->json([
                        'error_money' => 0
                    ]);
                }
            } else {
                $discount_percent = floatval(($amount / 100) * $discount);

                if ($discount_percent > $amount) {
                    return response()->json([
                        'error_percent' => 1
                    ]);
                } else {
                    return response()->json([
                        'error_percent' => 0,
                        'discount_percent' => $discount_percent
                    ]);
                }
            }
        } else {
            //Sử dụng mã giảm giá

            //Lấy thông tin nhân viên
            $staff = $this->staff->getItem(Auth::id());
            //            $request->total_using_voucher
            //Lấy thông tin voucher
            $code = $this->voucher->getCodeOrder($voucher_code, $type_order);

            if ($code == null || $code['voucher_type'] == 'ship' || $code['object_type'] != $request->type_order) {
                return response()->json([
                    'voucher_null' => 1
                ]);
            }

            if (($code['total_use'] + $request->total_using_voucher) < $code['quota']) {
                if ($code['type_using'] == 'public' || $code['branch_id'] == '') {


                    //Id khác rỗng thì giảm theo id
                    if ($code['object_type_id'] != '') {
                        $arr_id = explode(",", $code['object_type_id']);

                        if (!in_array($id_order, $arr_id) == true) {
                            return response()->json([
                                'voucher_not_exist' => 1
                            ]);
                        }
                    }

                    $now = date("Y-m-d");
                    if ($code['expire_date'] < $now) {
                        //Hết hạn sử dụng
                        return response()->json([
                            'voucher_expired' => 1
                        ]);
                    }

                    if ($code['total_use'] >= $code['quota']) {
                        //Hết số lần sử dụng
                        return response()->json([
                            'voucher_not_using' => 1,
                            'message' => __('Hết hạn sử dụng')
                        ]);
                    }

                    if ($amount_bill < $code['required_price']) {
                        //Không đủ tiền áp dụng
                        return response()->json([
                            'voucher_amount_error' => 1,
                            'message' => __('Tổng tiền đơn hàng không đủ sử dụng thẻ')
                        ]);
                    }

                    // check số lần sử dụng của voucher đối với KH hoặc KHVL
                    $mOrder = new OrderTable();
                    $mOrderDetail = new OrderDetailTable();
                    // count number of using this voucher
                    $countOrder = $mOrder->getOrderOfCustomerUsingVoucherCode($customer_id, $voucher_code);
                    $countOrderDetail = $mOrderDetail->getOrderDetailOfCustomerUsingVoucherCode($customer_id, $voucher_code);
                    $number_using = -1; // khỏi check số lượng sử dụng
                    if ($customer_id == 1) {
                        if ($code['using_by_guest'] == 0) {
                            return response()->json([
                                'voucher_doesnt_use_guest' => 1,
                                'message' => __('Mã giảm giá không áp dụng đối với khách hàng vãng lai')
                            ]);
                        }
                        // $number_using = -1
                    } else {
                        if (!empty($code['number_of_using']) && $code['number_of_using'] != 0) {
                            if (count($countOrder) + count($countOrderDetail) >= $code['number_of_using']) {
                                return response()->json([
                                    'voucher_max_using_by_customer' => 1,
                                    'message' => __('Mã giảm giá đã hết số lần sử dụng đối với khách hàng này')
                                ]);
                            } else {
                                $number_using = $code['number_of_using'] - (count($countOrder) + count($countOrderDetail));
                            }
                        }
                    }

                    //Tính tiền giảm
                    if ($code['type'] == 'sale_cash') {
                        $discount_voucher = $code['cash'];

                        return response()->json([
                            'number_using' => $number_using,
                            'voucher_success' => 1,
                            'voucher_name' => $voucher_code,
                            'discount_voucher' => $discount_voucher
                        ]);
                    }

                    if ($code['type'] == 'sale_percent') {
                        $discount_voucher_percent = floatval(($amount / 100) * $code['percent']);

                        if ($discount_voucher_percent >= $code['max_price']) {
                            $discount_voucher_percent = $code['max_price'];
                            return response()->json([
                                'number_using' => $number_using,
                                'voucher_success' => 1,
                                'voucher_name' => $voucher_code,
                                'discount_voucher' => $discount_voucher_percent
                            ]);
                        } else {
                            return response()->json([
                                'number_using' => $number_using,
                                'voucher_success' => 1,
                                'voucher_name' => $voucher_code,
                                'discount_voucher' => $discount_voucher_percent
                            ]);
                        }
                    }
                } else if ($code['type_using'] == 'private' && $code['branch_id'] != '') {
                    $arr[] = $staff['branch_id'];

                    if (!in_array($code['branch_id'], $arr) == true) {
                        return response()->json([
                            'branch_not' => 1,
                            'message' => __('Voucher không sử dụng cho chi nhánh này')
                        ]);
                    }

                    //Id khác rỗng thì giảm theo id
                    if ($code['object_type_id'] != '') {
                        $arr_id = explode(",", $code['object_type_id']);

                        if (!in_array($id_order, $arr_id) == true) {
                            return response()->json([
                                'voucher_not_exist' => 1
                            ]);
                        }
                    }

                    $now = date("Y-m-d");
                    if ($code['expire_date'] > $now) {
                        //hết hạn sử dụng
                        return response()->json([
                            'voucher_expired' => 1
                        ]);
                    }

                    if ($code['total_use'] >= $code['quota']) {
                        //Hết quota
                        return response()->json([
                            'voucher_not_using' => 1
                        ]);
                    }

                    if ($amount_bill < $code['required_price']) {
                        //Không đủ tiền sử dụng
                        return response()->json([
                            'voucher_amount_error' => 1,
                            'message' => __('Tổng tiền đơn hàng không đủ sử dụng thẻ')
                        ]);
                    }
                    // check số lần sử dụng của voucher đối với KH hoặc KHVL
                    $mOrder = new OrderTable();
                    $mOrderDetail = new OrderDetailTable();
                    // count number of using this voucher
                    $countOrder = $mOrder->getOrderOfCustomerUsingVoucherCode($customer_id, $voucher_code);
                    $countOrderDetail = $mOrderDetail->getOrderDetailOfCustomerUsingVoucherCode($customer_id, $voucher_code);
                    $number_using = -1; // khỏi check số lượng sử dụng
                    if ($customer_id == 1) {
                        if ($code['using_by_guest'] == 0) {
                            return response()->json([
                                'voucher_doesnt_use_guest' => 1,
                                'message' => __('Mã giảm giá không áp dụng đối với khách hàng vãng lai')
                            ]);
                        }
                        // $number_using = -1
                    } else {
                        if (!empty($code['number_of_using']) && $code['number_of_using'] != 0) {
                            if (count($countOrder) + count($countOrderDetail) >= $code['number_of_using']) {
                                return response()->json([
                                    'voucher_max_using_by_customer' => 1,
                                    'message' => __('Mã giảm giá đã hết số lần sử dụng đối với khách hàng này')
                                ]);
                            } else {
                                $number_using = $code['number_of_using'] - (count($countOrder) + count($countOrderDetail));
                            }
                        }
                    }
                    //Tính tiền giảm giá
                    if ($code['type'] == 'sale_cash') {
                        $discount_voucher = $code['cash'];

                        return response()->json([
                            'number_using' => $number_using,
                            'voucher_success' => 1,
                            'voucher_name' => $voucher_code,
                            'discount_voucher' => $discount_voucher
                        ]);
                    }

                    if ($code['type'] == 'sale_percent') {
                        $discount_voucher_percent = floatval(($amount / 100) * $code['percent']);

                        if ($discount_voucher_percent >= $code['max_price']) {
                            $discount_voucher_percent = $code['max_price'];
                            return response()->json([
                                'number_using' => $number_using,
                                'voucher_success' => 1,
                                'voucher_name' => $voucher_code,
                                'discount_voucher' => $discount_voucher_percent
                            ]);
                        } else {
                            return response()->json([
                                'number_using' => $number_using,
                                'voucher_success' => 1,
                                'voucher_name' => $voucher_code,
                                'discount_voucher' => $discount_voucher_percent
                            ]);
                        }
                    }
                }
            } else {
                return response()->json([
                    'voucher_not_using' => 1,
                    'message' => __('Số lần sử dụng voucher vượt quá giới hạn')
                ]);
            }
        }
    }

    /**
     * Giảm giá tổng bill
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDiscountBillAction(Request $request)
    {
        $total_bill = $request->total_bill;
        $discount_bill = $request->discount_bill;
        $type = $request->type_discount_bill;
        $voucher_code_bill = $request->voucher_code_bill;
        $customer_id = $request->customer_id;

        if ($voucher_code_bill == null) {
            //Giảm giá trực tiếp
            if ($type == 1) {
                if ($discount_bill > $total_bill) {
                    return response()->json([
                        'error_money_bill' => 1
                    ]);
                } else {
                    return response()->json([
                        'error_money_bill' => 0,
                        'discount_bill' => $discount_bill
                    ]);
                }
            } else {
                $discount_bill_percent = floatval(($total_bill / 100) * $discount_bill);
                if ($discount_bill_percent > $total_bill) {
                    return response()->json([
                        'error_percent_bill' => 1
                    ]);
                } else {
                    return response()->json([
                        'error_percent_bill' => 0,
                        'discount_bill' => $discount_bill_percent
                    ]);
                }
            }
        } else {
            //Giảm giá bằng voucher

            //Lấy thông tin nhân viên
            $staff = $this->staff->getItem(Auth::id());
            //Lấy mã giảm giá
            $code = $this->voucher->getCodeOrder($voucher_code_bill, 'all');

            if ($code == null) {
                //Mã không tồn tại
                return response()->json([
                    'voucher_bill_null' => 1
                ]);
            }

            if ($code['voucher_type'] == "ship" && !isset($request->transport_charge)) {
                //Mã không tồn tại
                return response()->json([
                    'voucher_bill_null' => 1
                ]);
            }

            if ($code['voucher_type'] == "ship" && $request->receive_at_counter == 1) {
                //Nhận hàng tại quầy
                return response()->json([
                    'voucher_bill_null' => 1
                ]);
            }

            if ($code['voucher_type'] == 'order') {
                //Đơn hàng
                $amountCompare = $request->total_bill;
            } else {
                //Phí vận chuyển
                $amountCompare = $request->transport_charge;
            }


            if ($code['type_using'] == 'public' || $code['branch_id'] == '') {
                $now = date("Y-m-d");

                if ($code['expire_date'] < $now) {
                    //Mã đã hết hạn sử dụng
                    return response()->json([
                        'voucher_bill_expired' => 1
                    ]);
                }

                if ($code['total_use'] >= $code['quota']) {
                    //Mã đã hết quota sử dụng
                    return response()->json([
                        'voucher_bill_not_using' => 1
                    ]);
                }

                if ($total_bill < $code['required_price']) {
                    //Giá trị đơn hàng ko đủ sử dụng mã giảm giá
                    return response()->json([
                        'voucher_amount_bill_error' => 1
                    ]);
                }

                // check số lần sử dụng của voucher đối với KH hoặc KHVL
                $mOrder = new OrderTable();
                $mOrderDetail = new OrderDetailTable();
                // count number of using this voucher
                $countOrder = $mOrder->getOrderOfCustomerUsingVoucherCode($customer_id, $voucher_code_bill);
                $countOrderDetail = $mOrderDetail->getOrderDetailOfCustomerUsingVoucherCode($customer_id, $voucher_code_bill);
                $number_using = -1; // khỏi check số lượng sử dụng
                if ($customer_id == 1) {
                    if ($code['using_by_guest'] == 0) {
                        return response()->json([
                            'voucher_doesnt_use_guest' => 1,
                            'message' => __('Mã giảm giá không áp dụng đối với khách hàng vãng lai')
                        ]);
                    }
                    // $number_using = -1
                } else {
                    if (!empty($code['number_of_using']) && $code['number_of_using'] != 0) {
                        if (count($countOrder) + count($countOrderDetail) >= $code['number_of_using']) {
                            return response()->json([
                                'voucher_max_using_by_customer' => 1,
                                'message' => __('Mã giảm giá đã hết số lần sử dụng đối với khách hàng này')
                            ]);
                        } else {
                            $number_using = $code['number_of_using'] - (count($countOrder) + count($countOrderDetail));
                        }
                    }
                }

                if ($code['type'] == 'sale_cash') {
                    $discount_voucher_bill = $code['cash'] > $amountCompare ? $amountCompare : $code['cash'];

                    return response()->json([
                        'number_using' => $number_using,
                        'voucher_success_bill' => 1,
                        'voucher_name_bill' => $voucher_code_bill,
                        'discount_voucher_bill' => $discount_voucher_bill
                    ]);
                }


                if ($code['type'] == 'sale_percent') {
                    //Tính tiền giảm
                    $discount_voucher_percent_bill = floatval(($amountCompare / 100) * $code['percent']);

                    if ($discount_voucher_percent_bill >= $code['max_price']) {
                        $discount_voucher_percent_bill = $code['max_price'];

                        return response()->json([
                            'number_using' => $number_using,
                            'voucher_success_bill' => 1,
                            'voucher_name_bill' => $voucher_code_bill,
                            'discount_voucher_bill' => $discount_voucher_percent_bill
                        ]);
                    } else {
                        return response()->json([
                            'number_using' => $number_using,
                            'voucher_success_bill' => 1,
                            'voucher_name_bill' => $voucher_code_bill,
                            'discount_voucher_bill' => $discount_voucher_percent_bill
                        ]);
                    }
                }
            } else if ($code['type_using'] == 'private' && $code['branch_id'] != '') {
                //Sử dụng nội bộ và giới hạn chi nhánh
                $arr[] = $staff['branch_id'];

                if (!in_array($code['branch_id'], $arr) == true) {
                    return response()->json([
                        'branch_not' => 1,
                        'message' => __('Voucher không sử dụng cho chi nhánh này')
                    ]);
                }

                //Ngày hiện tại
                $now = date("Y-m-d");

                if ($code['expire_date'] < $now) {
                    //Mã hết hạn sử dụng
                    return response()->json([
                        'voucher_bill_expired' => 1
                    ]);
                }

                if ($code['total_use'] >= $code['quota']) {
                    return response()->json([
                        'voucher_bill_not_using' => 1
                    ]);
                }

                if ($total_bill < $code['required_price']) {
                    //Giá trị đơn hàng ko đủ sử dụng mã giảm giá
                    return response()->json([
                        'voucher_amount_bill_error' => 1
                    ]);
                }

                // check số lần sử dụng của voucher đối với KH hoặc KHVL
                $mOrder = new OrderTable();
                $mOrderDetail = new OrderDetailTable();

                // count number of using this voucher
                $countOrder = $mOrder->getOrderOfCustomerUsingVoucherCode($customer_id, $voucher_code_bill);
                $countOrderDetail = $mOrderDetail->getOrderDetailOfCustomerUsingVoucherCode($customer_id, $voucher_code_bill);
                $number_using = -1; // khỏi check số lượng sử dụng

                if ($customer_id == 1) {
                    if ($code['using_by_guest'] == 0) {
                        return response()->json([
                            'voucher_doesnt_use_guest' => 1,
                            'message' => __('Mã giảm giá không áp dụng đối với khách hàng vãng lai')
                        ]);
                    }
                } else {
                    if (!empty($code['number_of_using']) && $code['number_of_using'] != 0) {
                        if (count($countOrder) + count($countOrderDetail) >= $code['number_of_using']) {
                            return response()->json([
                                'voucher_max_using_by_customer' => 1,
                                'message' => __('Mã giảm giá đã hết số lần sử dụng đối với khách hàng này')
                            ]);
                        } else {
                            $number_using = $code['number_of_using'] - (count($countOrder) + count($countOrderDetail));
                        }
                    }
                }


                //Tính tiền giảm giá
                if ($code['type'] == 'sale_cash') {
                    $discount_voucher_bill = $code['cash'] > $amountCompare ? $amountCompare : $code['cash'];

                    return response()->json([
                        'number_using' => $number_using,
                        'voucher_success_bill' => 1,
                        'voucher_name_bill' => $voucher_code_bill,
                        'discount_voucher_bill' => $discount_voucher_bill
                    ]);
                }

                if ($code['type'] == 'sale_percent') {
                    $discount_voucher_percent_bill = floatval(($amountCompare / 100) * $code['percent']);

                    if ($discount_voucher_percent_bill >= $code['max_price']) {
                        $discount_voucher_percent_bill = $code['max_price'];
                        return response()->json([
                            'number_using' => $number_using,
                            'voucher_success_bill' => 1,
                            'voucher_name_bill' => $voucher_code_bill,
                            'discount_voucher_bill' => $discount_voucher_percent_bill
                        ]);
                    } else {
                        return response()->json([
                            'number_using' => $number_using,
                            'voucher_success_bill' => 1,
                            'voucher_name_bill' => $voucher_code_bill,
                            'discount_voucher_bill' => $discount_voucher_percent_bill
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAction(Request $request)
    {
        $staff = $this->staff->getItem(Auth::id());
        $type = $request->type;
        $search = $request->search;
        $arr = [];
        if ($type == 'service') {
            $list = $this->service_branch_price->getItemBranchSearch($search, $staff['branch_id'], 1);
            foreach ($list as $item) {
                $getPrice = $this->order->getPromotionDetail($type, $item['service_code'], '', 'live', 1);

                if ($getPrice != null && $getPrice > $item['new_price']) {
                    $getPrice = $item['new_price'];
                }

                $arr[] = [
                    'name' => $item['service_name'],
                    'id' => $item['service_id'],
                    'price' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'price_hidden' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'type' => $type,
                    'avatar' => $item['service_avatar'],
                    'code' => $item['service_code'],
                    'is_sale' => $getPrice != null ? 1 : 0,
                    'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'is_surcharge' => $item['is_surcharge']
                ];
            }
        }
        if ($type == 'service_card') {
            $list = $this->service_card->searchServiceCard($search);
            foreach ($list as $item) {
                $getPrice = $this->order->getPromotionDetail($type, $item['code'], '', 'live', 1);

                if ($getPrice != null && $getPrice > $item['price']) {
                    $getPrice = $item['price'];
                }

                $arr[] = [
                    'name' => $item['name'],
                    'id' => $item['service_card_id'],
                    'price' => number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'price_hidden' => number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'avatar' => $item['image'],
                    'code' => $item['code'],
                    'type' => $type,
                    'is_sale' => $getPrice != null ? 1 : 0,
                    'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'is_surcharge' => $item['is_surcharge']
                ];
            }
        }
        if ($type == 'product') {
            //            $list = $this->product_branch_price->getItemBranchSearch($search, $staff['branch_id']);
            //Lấy giới hạn 16 sản phẩm
            $list = $this->product_branch_price->getItemBranchLimit($staff['branch_id'], $request->category_id, $search, 1);
            foreach ($list as $item) {
                $getPrice = $this->order->getPromotionDetail($type, $item['product_code'], '', 'live', 1);

                if ($getPrice != null && $getPrice > $item['new_price']) {
                    $getPrice = $item['new_price'];
                }

                $arr[] = [
                    'name' => $item['product_child_name'],
                    'id' => $item['product_child_id'],
                    'price' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'price_hidden' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'avatar' => $item['avatar'],
                    'code' => $item['product_code'],
                    'type' => $type,
                    'is_sale' => $getPrice != null ? 1 : 0,
                    'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'is_surcharge' => $item['is_surcharge'],
                    'inventory_management' => $item['inventory_management']
                ];
            }
        }
        if ($type == 'member_card') {
            $id = $request->id;
            $staff = $this->staff->getItem(Auth::id());
            $list_card_active = $this->customer_service_card->searchCardMember($search, $id, $staff['branch_id']);
            foreach ($list_card_active as $key => $item) {
                if ($item['expired_date'] == null) {
                    if ($item['number_using'] == 0) {
                        $arr[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => __('Không giới hạn'),
                        ];
                    } else {
                        if ($item['number_using'] > $item['count_using']) {
                            $arr[] = [
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
                            $arr[] = [
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
                                $arr[] = [
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
        }
        return response()->json([
            'type' => $type,
            'list' => $arr
        ]);
    }

    /**
     * Lưu đơn hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddAction(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->table_add == null) {
                return response()->json([
                    'table_error' => 1
                ]);
            }
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $mProductChild = app()->get(ProductChildTable::class);
            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $mOrderLog = new OrderLogTable();

            $session = $request->sessionSerial;

            $staff_branch = $this->staff->getItem(Auth::id());
            $data_order = [
                'customer_id' => $request->customer_id,
                'total' => $request->total_bill,
                'discount' => $request->discount_bill,
                'amount' => $request->amount_bill,
                'voucher_code' => $request->voucher_bill,
                'order_description' => $request->order_description,
                'branch_id' => $staff_branch['branch_id'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'refer_id' => $request->refer_id
            ];
            // check lý do giảm giá
            //            if (isset($request->discountCauseBill) && $request->discountCauseBill != null) {
            //                $data_order['discount_causes_id'] = (int)$request->discountCauseBill;
            //            }
            $id_order = $this->order->add($data_order);
            $day_code = date('dmY');
            if ($id_order < 10) {
                $id_order = '0' . $id_order;
            }

            $orderCode = 'DH_' . $day_code . $id_order;
            $this->order->edit([
                'order_code' => $orderCode
            ], $id_order);

            $arrPromotionLog = [];
            $arrQuota = [];
            $arrObjectBuy = [];

            $messageSerialError = '';
            if ($request->table_add != null) {
                $aData = array_chunk($request->table_add, 15, false);

                foreach ($aData as $key => $value) {
                    $value[4] = str_replace(',', '', $value[4]);
                    $isChangePrice = isset($value[12]) ? $value[12] : 0;
                    $isCheckPromotion = isset($value[13]) ? $value[13] : 0;

                    if (in_array($value[2], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                        $arrObjectBuy[] = [
                            'object_type' => $value[2],
                            'object_code' => $value[3],
                            'object_id' => $value[0],
                            'price' => $value[4],
                            'quantity' => $value[5],
                            'customer_id' => $request->customer_id,
                            'order_source' => self::LIVE,
                            'order_id' => $id_order,
                            'order_code' => $orderCode
                        ];
                    }

                    $data_order_detail = [
                        'order_id' => $id_order,
                        'object_id' => $value[0],
                        'object_name' => $value[1],
                        'object_type' => $value[2],
                        'object_code' => $value[3],
                        'price' => $value[4],
                        'quantity' => $value[5],
                        'discount' => str_replace(',', '', $value[7]),
                        'voucher_code' => $value[8],
                        'amount' => str_replace(',', '', $value[9]),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'refer_id' => $request->refer_id,
                        'staff_id' => $value[10] != '' ? implode(',', $value[10]) : null,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $orderDetailId = $this->order_detail->add($data_order_detail);

                    if ($value[2] == 'product') {
                        $tmpSerial = [];
                        $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $value[14], $value[3]);
                        foreach ($listSerialLog as $item) {
                            $tmpSerial[] = [
                                'order_id' => $id_order,
                                'order_detail_id' => $orderDetailId,
                                'product_code' => $value[3],
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

            if ($messageSerialError != '') {
                return [
                    'error' => false,
                    'message' => $messageSerialError
                ];
            }

            //Insert order log đơn hàng mới
            $mOrderLog->insert([
                'order_id' => $id_order,
                'created_type' => 'backend',
                'status' => 'new',
                //                'note' => __('Đặt hàng thành công'),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'note_vi' => 'Đặt hàng thành công',
                'note_en' => 'Order success',
            ]);

            //Insert sms log
            $mSmsLog = app()->get(SmsLogRepositoryInterface::class);
            $mSmsLog->getList('order_success', $id_order);

            //Insert email log
            CheckMailJob::dispatch('is_event', 'order_success', $id_order);

            if (!isset($request->custom_price) && $request->custom_price == 0) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $mOrderApp->plusQuotaUsePromotion($arrQuota);
            }

            $mPlusPoint = new LoyaltyApi();
            //Cộng điểm khi mua hàng trực tiếp
            $mPlusPoint->plusPointEvent([
                'customer_id' => $request->customer_id,
                'rule_code' => 'order_direct',
                'object_id' => $id_order
            ]);

            DB::commit();

            //Insert sms log
            $mSmsLog = app()->get(SmsLogRepositoryInterface::class);
            $mSmsLog->getList('order_success', $id_order);

            //Insert email log
            CheckMailJob::dispatch('is_event', 'order_success', $id_order);

            $mNoti = new SendNotificationApi();
            //Send notification
            if ($request->customer_id != 1) {
                $mNoti->sendNotification([
                    'key' => 'order_status_W',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id_order
                ]);
            }
            //Gửi thông báo NV khi có đơn hàng mới
            $mNoti->sendStaffNotification([
                'key' => 'order_status_W',
                'customer_id' => $request->customer_id,
                'object_id' => $id_order,
                'branch_id' => Auth()->user()->branch_id
            ]);
            //Lưu log ZNS
            SaveLogZns::dispatch('order_success', $request->customer_id, $id_order);

            return response()->json([
                'order_code' => $orderCode,
                'order_id' => $id_order,
                'data' => [
                    'order_code' => $orderCode,
                    'order_id' => $id_order,
                ],
                'error' => true,
                'message' => __('Thêm thành công')
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
     * Lưu và chỉnh sửa đơn hàng
     *
     * @param Request $request
     * @return JsonResponse|void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function saveOrUpdateOrderV2(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->table_add == null) {
                return response()->json([
                    'error' => false,
                    'message' => __('Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm')
                ]);
            }

            // nếu chưa có order_id thì tạo
            if (!$request->order_id) {
                $mPromotionLog = new PromotionLogTable();
                $mOrderApp = app()->get(OrderAppRepoInterface::class);
                $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
                $mProductChild = app()->get(ProductChildTable::class);
                $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
                $mOrderLog = new OrderLogTable();

                $session = $request->sessionSerial;

                if ($request->receipt_info_check == 1 && !isset($request->delivery_type)) {
                    return response()->json([
                        'error' => false,
                        'message' => __('Vui lòng chọn hình thức nhận hàng')
                    ]);
                }

                $mCustomerContact = app()->get(CustomerContactTable::class);

                $detailAddress = $mCustomerContact->getDetailContact($request->customer_contact_id);

                $data_order = [
                    'customer_id' => $request->customer_id,
                    'total' => $request->total_bill,
                    'discount' => $request->discount_bill,
                    'amount' => $request->amount_bill,
                    'voucher_code' => $request->voucher_bill,
                    'order_description' => $request->order_description,
                    'branch_id' => Auth()->user()->branch_id,
                    'refer_id' => $request->refer_id,
                    'customer_contact_code' => $detailAddress != null ? $detailAddress['customer_contact_code'] : '',
                    'customer_contact_id' => $request->customer_contact_id,
                    'receive_at_counter' => $request->receipt_info_check == 1 ? 0 : 1,
                    'type_time' => $request->type_time,
                    'time_address' => $request->time_address != '' ? Carbon::createFromFormat('d/m/Y', $request->time_address)->format('Y-m-d') : '',
                    'tranport_charge' => $request->tranport_charge,
                    'type_shipping' => $request->delivery_type,
                    'delivery_cost_id' => $request->delivery_cost_id,
                    'discount_member' => $request->discount_member,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];

                //Thêm đơn hàng
                $id_order = $this->order->add($data_order);

                $day_code = date('dmY');
                if ($id_order < 10) {
                    $id_order = '0' . $id_order;
                }

                $orderCode = 'DH_' . $day_code . $id_order;
                $this->order->edit([
                    'order_code' => $orderCode
                ], $id_order);

                $arrObjectBuy = [];

                $messageSerialError = '';

                if (count($request->table_add) > 0) {
                    foreach ($request->table_add as $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card']) && $v['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $v['object_type'],
                                'object_code' => $v['object_code'],
                                'object_id' => $v['object_id'],
                                'price' => $v['price'],
                                'quantity' => $v['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }

                        //Lưu chi tiết đơn hàng
                        $orderDetailId = $this->order_detail->add([
                            'order_id' => $id_order,
                            'object_id' => $v['object_id'],
                            'object_name' => $v['object_name'],
                            'object_type' => $v['object_type'],
                            'object_code' => $v['object_code'],
                            'price' => $v['price'],
                            'quantity' => $v['quantity'],
                            'discount' => str_replace(',', '', $v['discount']),
                            'voucher_code' => $v['voucher_code'],
                            'amount' => str_replace(',', '', $v['amount']),
                            'refer_id' => $request->refer_id,
                            'staff_id' => isset($v['staff_id']) && $v['staff_id'] != null ? implode(',', $v['staff_id']) : null,
                            'is_change_price' => $v['is_change_price'],
                            'is_check_promotion' => $v['is_check_promotion'],
                            'note' => $v['note'] ?? null,
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ]);

                        if ($v['object_type'] == 'product') {
                            $tmpSerial = [];
                            $numberRow = $v['number_row'] ?? null;
                            $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $numberRow, $v['object_code']);
                            foreach ($listSerialLog as $item) {
                                $tmpSerial[] = [
                                    'order_id' => $id_order,
                                    'order_detail_id' => $orderDetailId,
                                    'product_code' => $v['object_code'],
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

                        //Lưu dịch vụ kèm theo
                        if (isset($v['array_attach']) && count($v['array_attach']) > 0) {
                            foreach ($v['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $this->order_detail->add([
                                    'order_id' => $id_order,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
                            }
                        }
                    }
                }

                if ($messageSerialError != '') {
                    return [
                        'error' => false,
                        'message' => $messageSerialError
                    ];
                }

                //Insert order log đơn hàng mới
                $mOrderLog->insert([
                    'order_id' => $id_order,
                    'created_type' => 'backend',
                    'status' => 'new',
                    //                'note' => __('Đặt hàng thành công'),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Đặt hàng thành công',
                    'note_en' => 'Order success',
                ]);

                //Trừ quota_user khi đơn hàng có promotion quà tặng
                $mOrderApp->subtractQuotaUsePromotion($request->order_id);
                //Remove promotion log
                $mPromotionLog->removeByOrder($request->order_id);
                if (!isset($request->custom_price) && $request->custom_price == 0) {
                    //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                    $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                    //Insert promotion log
                    $arrPromotionLog = $getPromotionLog['promotion_log'];
                    $mPromotionLog->insert($arrPromotionLog);
                    //Cộng quota_use promotion quà tặng
                    $arrQuota = $getPromotionLog['promotion_quota'];
                    $mOrderApp->plusQuotaUsePromotion($arrQuota);
                }

                $mPlusPoint = new LoyaltyApi();
                //Cộng điểm khi mua hàng trực tiếp
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $request->customer_id,
                    'rule_code' => 'order_direct',
                    'object_id' => $id_order
                ]);

                $mConfig = app()->get(ConfigTable::class);
                //Kiểm tra có tạo ticket không
                $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

                $isCreateTicket = 0;

                if ($request->customer_id != 1 && $configCreateTicket == 1) {
                    $isCreateTicket = 1;
                }

                DB::commit();

                try {
                    // Insert email log
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_EMAIL_CUSTOMER,
                        'event' => 'is_event',
                        'key' => 'order_success',
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                    // Insert sms log
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_SMS_CUSTOMER,
                        'key' => 'order_success',
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                    //Gửi thông báo khách hàng
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'order_status_W',
                        'customer_id' => $request->customer_id,
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                    //Gửi thông báo nhân viên
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_STAFF,
                        'key' => 'order_status_W',
                        'customer_id' => $request->customer_id,
                        'object_id' => $id_order,
                        'branch_id' => Auth()->user()->branch_id,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                    // Lưu log ZNS
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_ZNS_CUSTOMER,
                        'key' => 'order_success',
                        'customer_id' => $request->customer_id,
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                } catch (Exception $ex) {
                    Log::error($ex->getMessage());
                }

                return response()->json([
                    'order_code' => $orderCode,
                    'order_id' => $id_order,
                    'is_create_ticket' => $isCreateTicket,
                    'error' => true,
                    'message' => __('Thêm thành công')
                ]);
            } else {
                $mPromotionLog = new PromotionLogTable();
                $mOrderApp = app()->get(OrderAppRepoInterface::class);
                $mOrderLog = new OrderLogTable();

                $id_order = $request->order_id;
                $orderCode = $request->order_code;

                $data_order = [
                    'order_code' => $orderCode,
                    'customer_id' => $request->customer_id,
                    'total' => $request->total_bill,
                    'discount' => $request->discount_bill,
                    'amount' => $request->amount_bill,
                    'voucher_code' => $request->voucher_bill,
                    'order_description' => $request->order_description,
                    'branch_id' => Auth()->user()->branch_id,
                    'refer_id' => $request->refer_id,
                    'discount_member' => $request->discount_member,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];
                //Chỉnh sửa đơn hàng
                $this->order->edit($data_order, $id_order);

                $arrObjectBuy = [];

                if (count($request->table_add) > 0) {
                    //Xoá chi tiết đơn hàng
                    $this->order_detail->remove($id_order);

                    foreach ($request->table_add as $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card']) && $v['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $v['object_type'],
                                'object_code' => $v['object_code'],
                                'object_id' => $v['object_id'],
                                'price' => $v['price'],
                                'quantity' => $v['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }

                        //Thêm chi tiết đơn hàng
                        $orderDetailId = $this->order_detail->add([
                            'order_id' => $id_order,
                            'object_id' => $v['object_id'],
                            'object_name' => $v['object_name'],
                            'object_type' => $v['object_type'],
                            'object_code' => $v['object_code'],
                            'price' => $v['price'],
                            'quantity' => $v['quantity'],
                            'discount' => $v['discount'],
                            'voucher_code' => $v['voucher_code'],
                            'amount' => $v['amount'],
                            'refer_id' => $request->refer_id,
                            'staff_id' => isset($v['staff_id']) && $v['staff_id'] != null ? implode(',', $v['staff_id']) : null,
                            'is_change_price' => $v['is_change_price'],
                            'is_check_promotion' => $v['is_check_promotion'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'note' => $v['note'] ?? null
                        ]);

                        //Lưu dịch vụ kèm theo
                        if (isset($v['array_attach']) && count($v['array_attach']) > 0) {
                            foreach ($v['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $this->order_detail->add([
                                    'order_id' => $id_order,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
                            }
                        }
                    }
                }

                if (!isset($request->custom_price) && $request->custom_price == 0) {
                    //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                    $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                    //Insert promotion log
                    $arrPromotionLog = $getPromotionLog['promotion_log'];
                    $mPromotionLog->insert($arrPromotionLog);
                    //Cộng quota_use promotion quà tặng
                    $arrQuota = $getPromotionLog['promotion_quota'];
                    $mOrderApp->plusQuotaUsePromotion($arrQuota);
                }

                $mConfig = app()->get(ConfigTable::class);
                //Kiểm tra có tạo ticket không
                $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

                $isCreateTicket = 0;

                if ($request->customer_id != 1 && $configCreateTicket == 1) {
                    $isCreateTicket = 1;
                }

                DB::commit();

                return response()->json([
                    'order_code' => $orderCode,
                    'order_id' => $id_order,
                    'is_create_ticket' => $isCreateTicket,
                    'error' => true,
                    'message' => __('Thêm thành công'),
                    'data' => [
                        'order_code' => $orderCode,
                        'order_id' => $id_order,
                    ], //data for chathub
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * save/update order without receipt (PAYMENT BUTTON in MAIN VIEW)
     *
     * @param Request $request
     * @return mixed
     */
    public function saveOrderWithoutReceipt(Request $request)
    {
        return $this->order->saveOrderWithoutReceipt($request);
    }

    public function createQrCodeVnPay(Request $request)
    {
        return $this->order->createQrCodeVnPay($request->all());
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

    /**
     * Thêm mới đơn hàng và thanh toán trực tiếp
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submitAddReceiptAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mOrderLog = new OrderLogTable();
            $mBranchMoneyLog = new CustomerBranchMoneyLogTable();

            $session = $request->sessionSerial;

            $mStaff = new StaffsTable();

            if ($request->receipt_info_check == 1 && !isset($request->delivery_type)) {
                return response()->json([
                    'error' => false,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }


            //Lấy số lẻ decimal
            $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
            $id_order = $request->order_id;
            $orderCode = $request->order_code;
            $staff_branch = $this->staff->getItem(Auth::id());
            $detailAddress = null;
            $mCustomerContact = app()->get(CustomerContactTable::class);

            $checkQuantityOrderDetail = $this->checkQuantityOrderDetail($id_order);

            if ($checkQuantityOrderDetail['error'] == false) {
                return response()->json([
                    'error' => $checkQuantityOrderDetail['error'],
                    'message' => $checkQuantityOrderDetail['message']
                ]);
            }

            if ($request->receipt_info_check == 1) {
                $detailAddress = $mCustomerContact->getDetail($request->customer_contact_id);
            }

            //Chỉnh sửa đơn hàng
            $this->order->edit([
                'process_status' => 'paysuccess',
                'customer_contact_code' => $request->receipt_info_check == 1 && $detailAddress != null ? $detailAddress['customer_contact_code'] : '',
                'customer_contact_id' => $request->receipt_info_check == 1 ? $request->customer_contact_id : '',
                'receive_at_counter' => $request->receipt_info_check == 1 ? 0 : 1,
                'type_time' => $request->receipt_info_check == 1 ? $request->type_time : '',
                'time_address' => $request->receipt_info_check == 1 && $request->time_address != '' ? Carbon::createFromFormat('d/m/Y', $request->time_address)->format('Y-m-d') : '',
                'tranport_charge' => $request->tranport_charge,
                'type_shipping' => $request->delivery_type,
                'delivery_cost_id' => $request->delivery_cost_id
            ], $id_order);
            //
            if ($request->voucher_bill != null) {
                $get = $this->voucher->getCodeItem($request->voucher_bill);
                $data = [
                    'total_use' => ($get['total_use'] + 1)
                ];

                $this->voucher->editVoucherOrder($data, $request->voucher_bill);
            }

            $list_card_print = [];
            $arrObjectBuy = [];
            $arrRemindUse = [];
            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $tmpSerialLog = [];
            $day_code = date('dmY');

            if (count($request->table_add) > 0) {
                //Xoá chi tiết đơn hàng
                $this->order_detail->remove($id_order);

                foreach ($request->table_add as $v) {
                    $position = $v['number_row'] ?? null;

                    if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                        if ($v['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $v['object_type'],
                                'object_code' => $v['object_code'],
                                'object_id' => $v['object_id'],
                                'price' => $v['price'],
                                'quantity' => $v['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }
                        //Lấy array nhắc sử dụng lại
                        $arrRemindUse[] = [
                            'object_type' => $v['object_type'],
                            'object_id' => $v['object_id'],
                            'object_code' => $v['object_code'],
                            'object_name' => $v['object_name'],
                        ];
                    }

                    //Thêm chi tiết đơn hàng
                    $id_detail = $this->order_detail->add([
                        'order_id' => $id_order,
                        'object_id' => $v['object_id'],
                        'object_name' => $v['object_name'],
                        'object_type' => $v['object_type'],
                        'object_code' => $v['object_code'],
                        'price' => $v['price'],
                        'quantity' => $v['quantity'],
                        'discount' => str_replace(',', '', $v['discount']),
                        'voucher_code' => $v['voucher_code'],
                        'amount' => $v['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'staff_id' => isset($v['staff_id']) && $v['staff_id'] != null ? implode(',', $v['staff_id']) : null,
                        'refer_id' => $request->refer_id,
                        'is_change_price' => $v['is_change_price'],
                        'is_check_promotion' => $v['is_check_promotion'],
                        'note' => $v['note'] ?? null,
                        'created_at_day' => Carbon::now()->format('d'),
                        'created_at_month' => Carbon::now()->format('m'),
                        'created_at_year' => Carbon::now()->format('Y'),
                    ]);

                    //Lưu dịch vụ kèm theo
                    if (isset($v['array_attach']) && count($v['array_attach']) > 0) {
                        foreach ($v['array_attach'] as $v1) {
                            //Lưu chi tiết đơn hàng
                            $this->order_detail->add([
                                'order_id' => $id_order,
                                'object_id' => $v1['object_id'],
                                'object_name' => $v1['object_name'],
                                'object_type' => $v1['object_type'],
                                'object_code' => $v1['object_code'],
                                'price' => $v1['price'],
                                'quantity' => $v1['quantity'],
                                'amount' => $v1['price'] * $v1['quantity'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'order_detail_id_parent' => $id_detail,
                                'created_at_day' => Carbon::now()->format('d'),
                                'created_at_month' => Carbon::now()->format('m'),
                                'created_at_year' => Carbon::now()->format('Y'),
                            ]);
                        }
                    }

                    switch ($v['object_type']) {
                        case 'service':
                            //Lấy hoa hồng dịch vụ
                            $check_commission = $this->service->getItem($v['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, $check_commission, $id_detail, $v['object_name'], null, $v['amount'], $v['staff_id'] ?? null);

                            break;
                        case 'product':
                            //Lấy hoa hồng sản phẩm
                            $check_commission = $this->product_child->getItem($v['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, $check_commission, $id_detail, $v['object_name'], null, $v['amount'], $v['staff_id'] ?? null);

                            //Kiểm tra serial của đơn hàng đã được tạo hay chưa
                            $checkOrderSerial = $mOrderDetailSerial->getListSerialByOrder($id_order, $v['object_code']);
                            if (count($checkOrderSerial) == 0) {
                                $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $position, $v['object_code']);
                                foreach ($listSerialLog as $itemSerialLog) {
                                    $tmpSerialLog[] = [
                                        'order_id' => $id_order,
                                        'order_detail_id' => $id_detail,
                                        'product_code' => $v['object_code'],
                                        'serial' => $itemSerialLog['serial'],
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id()
                                    ];
                                }
                            }

                            break;
                        case 'service_card':
                            //Lấy thông tin thẻ dịch vụ
                            $sv_card = $this->service_card->getServiceCardOrder($v['object_code']);
                            //Lấy hoa hồng thẻ dịch vụ
                            $check_commission = $this->service_card->getServiceCardInfo($v['object_id']);
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
                                    $refer_money = ($v['amount'] / 100) * ($check_commission['refer_commission_value']);
                                }
                            }
                            if ($check_commission['type_staff_commission'] == 'money') {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffCardCommission = round($check_commission['staff_commission_value'], $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            } else {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffMoney = ($v['amount'] / 100) * ($check_commission['staff_commission_value']);
                                    $staffCardCommission = round($staffMoney, $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            }

                            $arr_result = [];
                            for ($i = 0; $i < $v['quantity']; $i++) {
                                //Generate mã thẻ liệu trình
                                $code = $this->code->generateCardListCode();
                                while (array_search($code, $arr_result)) {
                                    $code = $this->code->generateCardListCode();
                                }

                                $data_card_list = [
                                    'service_card_id' => $v['object_id'],
                                    'order_code' => 'DH_' . $day_code . $id_order,
                                    'branch_id' => Auth()->user()->branch_id,
                                    'price' => $v['price'],
                                    'code' => $code,
                                    'refer_commission' => $refer_money,
                                    'staff_commission' => $staffCardCommission,
                                    'created_by' => Auth::id()
                                ];

                                if ($request->customer_id != 1 && $request->check_active == 1) {
                                    $data_card_list['is_actived'] = $request->check_active;
                                    $data_card_list['actived_at'] = date("Y-m-d H:i");

                                    $data_cus_card = [
                                        'customer_id' => $request->customer_id,
                                        'card_code' => $code,
                                        'service_card_id' => $v['object_id'],
                                        'number_using' => $sv_card['number_using'],
                                        'count_using' => $sv_card['service_card_type'] == 'money' ? 1 : 0,
                                        'money' => $sv_card['money'],
                                        'actived_date' => date("Y-m-d"),
                                        'is_actived' => 1,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'branch_id' => Auth()->user()->branch_id
                                    ];

                                    if ($sv_card['date_using'] != 0) {
                                        $data_cus_card['expired_date'] = strftime("%Y-%m-%d", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $sv_card['date_using'] . 'days'))));
                                    }
                                    if ($sv_card['service_card_type'] == 'money') {
                                        //Lấy thông tin KH
                                        $customer = $this->customer->getItem($request->customer_id);
                                        //Cập nhật tổng tiền của KH
                                        $this->customer->edit([
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
                                    $this->customer_service_card->add($data_cus_card);
                                    //Thêm vào service card list thẻ đã active
                                    $this->service_card_list->add($data_card_list);
                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                } else {
                                    $data_card_list['is_actived'] = 0;
                                    $this->service_card_list->add($data_card_list);
                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                }
                            }

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, $check_commission, $id_detail, $v['object_name'], null, $v['amount'], $v['staff_id'] ?? null);

                            break;
                        case 'member_card':
                            //Lấy thông tin thẻ liệu trình
                            $list_cus_card = $this->customer_service_card->getItemCard($v['object_id']);
                            //Trừ số lần sử dụng thẻ liệu trình
                            $this->customer_service_card->editByCode([
                                'count_using' => $list_cus_card['count_using'] + $v['quantity']
                            ], $v['object_code']);

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, null, $id_detail, $v['object_name'], $v['object_code'], $v['amount'], $v['staff_id'] ?? null, 0, 0, "member_card"
                            );

                            DB::commit();

                            try {
                                //Send email
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_EMAIL_CUSTOMER,
                                    'event' => 'is_event',
                                    'key' => 'service_card_over_number_used',
                                    'object_id' => $v['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                                //Send sms
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_SMS_CUSTOMER,
                                    'key' => 'service_card_over_number_used',
                                    'object_id' => $v['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                                //Lưu log ZNS
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_ZNS_CUSTOMER,
                                    'key' => 'service_card_over_number_used',
                                    'customer_id' => $request->customer_id,
                                    'object_id' => $v['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                                //Send notification
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_NOTIFY_CUSTOMER,
                                    'key' => 'service_card_over_number_used',
                                    'customer_id' => $request->customer_id,
                                    'object_id' => $v['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                            } catch (Exception $ex) {
                                Log::error($ex->getMessage());
                            }

                            break;
                    }
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

            //Array phương thức thanh toán
            $arrMethodWithMoney = $request->array_method;

            // amount bill, amount return, receipt type, order id,
            $amount_bill = str_replace(',', '', $request->amount_bill);

            if ($request->amount_all != '') {
                $amount_receipt_all = 0;

                foreach ($arrMethodWithMoney as $methodCode => $money) {
                    if ($money > 0) {
                        $amount_receipt_all += $money;
                    }
                }
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
                        $check_info = $this->spa_info->getInfoSpa();
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
                            $debt_id = $this->customer_debt->add($data_debt);
                            //update debt code
                            $day_code = date('dmY');
                            if ($debt_id < 10) {
                                $debt_id = '0' . $debt_id;
                            }
                            $debt_code = [
                                'debt_code' => 'CN_' . $day_code . $debt_id
                            ];
                            $this->customer_debt->edit($debt_code, $debt_id);
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
            $dataReceipt = $mReceipt->getItem($id_order);
            // no receipt by order_id but $dataReceipt still have data (data with all null field) ?????
            if ($dataReceipt != null && isset($dataReceipt['receipt_id']) && $dataReceipt['receipt_id'] != null) {
                $mReceipt->removeReceipt($id_order);
                $mReceiptDetail->removeReceiptDetail($dataReceipt['receipt_id']);
            }

            $dataReceipt = [
                'customer_id' => $request->customer_id,
                'staff_id' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'object_id' => $id_order,
                'object_type' => 'order',
                'order_id' => $id_order,
                // 'total_money' => $amount_receipt_all,
                'total_money' => $amount_receipt_all > $amount_bill ? $amount_bill : $amount_receipt_all,
                'voucher_code' => $request->voucher_bill,
                'status' => $status,
                'is_discount' => 1,
                'amount' => $amount_bill,
                'amount_paid' => $amount_receipt_all > $amount_bill ? $amount_bill : $amount_receipt_all,
                'amount_return' => $amount_receipt_all > $amount_bill ? $amount_receipt_all - $amount_bill : 0,
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
            //Thêm phiếu thu
            $receipt_id = $this->receipt->add($dataReceipt);

            $day_code = date('dmY');
            $data_code = [
                'receipt_code' => 'TT_' . $day_code . $receipt_id
            ];
            //Chỉnh sửa mã phiếu thu
            $this->receipt->edit($data_code, $receipt_id);

            if (count($request->table_add) > 0) {
                foreach ($request->table_add as $v) {
                    if ($v['object_type'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $receipt_id,
                            'cashier_id' => Auth::id(),
                            //                            'receipt_type' => 'member_card',
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $v['object_code'],
                            'amount' => $v['amount'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        //Thêm chi tiết phiếu thu
                        $this->receipt_detail->add($data_receipt_detail);
                    }
                }
            }

            // Chi tiết thanh toán
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
                                $this->receipt_detail->add($dataReceiptDetail);
                                //Lấy thông tin KH
                                $customerMoney = $this->customer->getItem($request->customer_id);
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
                        $this->receipt_detail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $receipt_id,
                            'status' => 'success'
                        ], 'order', $id_order, $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $this->receipt_detail->add($dataReceiptDetail);
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
                        $this->receipt_detail->add($dataReceiptDetail);
                    }
                }
            }

            $data_print = [];
            if (count($list_card_print) > 0) {
                foreach ($list_card_print as $k => $v) {
                    $get_cus_card = $this->service_card_list->searchCard($v);

                    $get_sv_card = $this->service_card->getServiceCardInfo($get_cus_card['service_card_id']);

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

            if (isset($request->receipt_info_check) && $request->receipt_info_check == 1) {
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

            //TẠO PHIẾU XUẤT KHO
            $listOrderProduct = $this->order_detail->getValueByOrderIdAndObjectType($id_order, 'product');
            $listService = $this->order_detail->getValueByOrderIdAndObjectType($id_order, 'service');
            $listServiceMaterials = [];

            //check có sp đi kèm ko
            $isCheckProductAttach = false;
            foreach ($listService as $item) {
                //Lấy sản phẩm đi kèm dịch vụ.
                $serviceMaterial = $this->serviceMaterial->getItem($item['object_id']);
                if (count($serviceMaterial) > 0) {
                    $isCheckProductAttach = true;
                    foreach ($serviceMaterial as $value) {
                        $currentPrice = $this->product_branch_price->getProductBranchPriceByCode(Auth::user()->branch_id, $value['material_code']);

                        if ($currentPrice != null) {
                            $listServiceMaterials[] = [
                                'product_code' => $value['material_code'],
                                'quantity' => $item['quantity'] * $value['quantity'],
                                'current_price' => $currentPrice['new_price'],
                                'total' => $value['quantity'] * $currentPrice['new_price'] * $item['quantity']
                            ];
                        }
                    }
                }
            }

            if ($isCheckProductAttach || count($listOrderProduct) > 0) {
                $checkWarehouse = $this->warehouse->getWarehouseByBranch(Auth::user()->branch_id);
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

                $idInventoryOutput = $this->inventoryOutput->add($dataInventoryOutput);
                $idCode = $idInventoryOutput;
                if ($idInventoryOutput < 10) {
                    $idCode = '0' . $idCode;
                }
                $this->inventoryOutput->edit(['po_code' => $this->code->codeDMY('XK', $idCode)], $idInventoryOutput);
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
            $checkSerialQuantity = 0;
            $tmpListSerial = [];
            foreach ($listOrderProduct as $item) {
                //                kiểm tra mã sản phẩm đã được tạo trong phiếu xuất kho hay chưa
                $checkProductInventotyOutput = $mInventoryOutputDetail->checkProductInventotyOutput($idInventoryOutput, $item['object_code']);

                $getDetailOutputDetail = $mInventoryOutputDetail->checkInventoryOutput($idInventoryOutput, $item['object_code']);

                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $item['object_code'],
                    'quantity' => $getDetailOutputDetail != null ? (int)$getDetailOutputDetail['quantity'] + (int)$item['quantity'] : $item['quantity'],
                    'current_price' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['current_price'] + (float)$item['price'] : $item['price'],
                    'total' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['total'] + (float)$item['amount'] : $item['amount'],
                ];

                if ($getDetailOutputDetail != null) {
                    $idIOD = $getDetailOutputDetail['inventory_output_detail_id'];
                    $mInventoryOutputDetail->editDetail($idIOD, $dataInventoryOutputDetail);
                } else {
                    $idIOD = $this->inventoryOutputDetail->add($dataInventoryOutputDetail);
                }

                if (count($checkProductInventotyOutput) == 0) {
                    //                Lấy danh sách serial theo sản phẩm ở đơn hàng

                    $listOrderSerialDetail = $mOrderDetailSerial->getListSerialByOrder($id_order, $item['object_code']);

                    if (count($listOrderSerialDetail) == 0) {
                        $listOrderSerialDetail = $mOrderSessionSerialLog->getListProductOrder(['session' => $request->sessionSerial, 'productCode' => $item['object_code']]);
                    }

                    if (count($listOrderSerialDetail) != 0 && $dataInventoryOutputDetail['quantity'] != count($listOrderSerialDetail)) {
                        $this->inventoryOutput->edit(['status' => 'new'], $idInventoryOutput);
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
                $productId = $this->productChild->getProductChildByCode($item['object_code'])['product_child_id'];
                $checkProductInventory = $this->productInventory->checkProductInventory($item['object_code'], $warehouseId);

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
                        $this->productInventory->edit(
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
                            $this->productInventory->add($dataEditProductInventoryInsert);
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
                    $idIOD = $this->inventoryOutputDetail->add($dataInventoryOutputDetail);

                    //Trừ tồn kho.
                    $productId = $this->productChild->getProductChildByCode($item['product_code'])['product_child_id'];
                    $checkProductInventory = $this->productInventory->checkProductInventory($item['product_code'], $warehouseId);
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
                            $this->productInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
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
                                $this->productInventory->add($dataEditProductInventoryInsert);
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

            // Thêm phiếu bảo hành điện tử
            $customer = $this->customer->getItem($request->customer_id);
            $dataTableAdd = $request->table_add;
            if ($customer['customer_code'] != null) {
                $this->order->addWarrantyCard($customer['customer_code'], $id_order, $orderCode, $dataTableAdd);
            }

            $checkSendSms = $this->smsConfig->getItemByType('paysuccess');

            //Lưu log dự kiến nhắc sử dụng lại
            $this->order->insertRemindUse($id_order, $request->customer_id, $arrRemindUse);

            //Tính điểm thưởng khi thanh toán
            if ($amount_receipt_all >= $amount_bill) {
                $this->bookingApi->plusPointReceiptFull(['receipt_id' => $receipt_id]);
            } else {
                $this->bookingApi->plusPointReceipt(['receipt_id' => $receipt_id]);
            }

            DB::commit();

            // //Send notification
            try {
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
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
            }


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

    public function renderCardAction(Request $request)
    {
        $staff = $this->staff->getItem(Auth::id());
        $branch = $this->branch->getItem($staff['branch_id']);
        $spa_info = $this->spaInfo->getItem();
        $config_service_card = $this->config_print_service_card->getItem(1);
        $config_money_card = $this->config_print_service_card->getItem(2);

        $list_card = $request->list_card;
        $data = [];
        foreach ($list_card as $item) {
            $check_card = $this->service_card_list->searchCard($item['card_code']);
            $data[] = [
                'card_code' => $check_card['code'],
                'card_name' => $check_card['card_name'],
                'card_type' => $check_card['service_card_type'],
                'number_using' => $check_card['number_using'],
                'date_using' => $check_card['date_using'],
                'is_actived' => $check_card['is_actived'],
                'actived_at' => $check_card['actived_at'],
                'money' => $check_card['money']
            ];
        }
        $view = view('admin::orders.modal-print-card', [
            'data_card' => $data,
            'branch' => $branch,
            'spa_info' => $spa_info[0],
            'config_service_card' => $config_service_card,
            'config_money_card' => $config_money_card
        ])->render();
        return response()->json($view);
    }

    public function submitPrintCardAction(Request $request)
    {
        $staff = $this->staff->getItem(Auth::id());
        $list_card = $request->list_card;
        foreach ($list_card as $item) {
            $data = [
                'branch_id' => $staff['branch_id'],
                'service_card_id' => $item['service_card_id'],
                'code' => $item['card_code'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            $this->service_card_list->add($data);
        }
        return response()->json([
            'print_success' => 1,
            'message' => __('In thẻ dịch vụ thành công')
        ]);
    }

    public function getItemDetail(Request $request)
    {
        $id = $request->id;
        $list = $this->order->getItemDetail($id);
        $list_table = $this->order_detail->getItem($list['order_id']);
        $arr = [];
        foreach ($list_table as $key => $item) {
            $arr[] = [
                'order_detail_id' => $item['order_detail_id'],
                'object_id' => $item['object_id'],
                'object_name' => $item['object_name'],
                'object_type' => $item['object_type'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'discount' => $item['discount'],
                'amount' => $item['amount']
            ];
        }
        return response()->json([
            'list' => $list,
            'list_detail' => $arr,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAction($id)
    {
        $data_receipt = $this->order->getItemDetail($id);

        //        if ($data_receipt->process_status == 'new') {
        $this->order->remove($id);
        //        }
        //Xóa đơn giao hàng
        $this->order->removeDelivery($id);
        //Trừ quota_user khi đơn hàng có promotion quà tặng
        $mOrderApp = app()->get(OrderAppRepoInterface::class);
        $mOrderApp->subtractQuotaUsePromotion($id);

        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function receiptAction(Request $request)
    {
        $id = $request->id;
        $list = $this->order->getItemDetail($id);
        //        return response()->json([
        //            'list' => $list
        //        ]);
        return \View::make('admin::orders.receipt-index', ['list' => $list])->render();
    }

    const PLUS = "plus";
    const SUBTRACT = "subtract";

    /**
     * Giao diện thanh toán sau
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function receiptAfterAction(Request $request)
    {
        $id = $request->id;
        $paymentType = $request->type;
        $mConfig = new ConfigTable();
        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();

        $session = Carbon::now()->format('YmdHisu');

        //Lấy thông tin nv phục vụ
        $staff_technician = $this->staff->getStaffTechnician();
        $customer_default = $this->customer->getCustomerOption();
        //Lấy thông tin đơn hàng
        $data_receipt = $this->order->getItemDetail($id);
        //Lấy thông tin chi tiết đơn hàng
        $order_detail = $this->order_detail->getItem($id);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($data_receipt['customer_id'], $branchId, self::PLUS);
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($data_receipt['customer_id'], $branchId, self::SUBTRACT);

        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        //Lấy tiền thành viên
        $money_customer = $accountMoney > 0 ? $accountMoney : 0;

        //Lấy thẻ dịch vụ theo chi nhánh
        $list_card_active = $this->customer_service_card->loadCardMember($data_receipt['customer_id'], $branchId);

        $data = [];
        $data_detail = [];

        //        Lấy sách serial theo id đơn hàng
        $listSerialOrder = $this->order->getListSerialOrder($id, $session);

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

        $collectionDetail = collect($order_detail->toArray());

        foreach ($order_detail as $item) {
            if ($item['order_detail_id_parent'] == null) {
                $data_detail[] = [
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
                    'max_quantity_card' => $this->customer_service_card->searchCard($item['object_code']),
                    'staff_id' => explode(',', $item['staff_id']),
                    'refer_id' => $item['refer_id'],
                    'is_change_price' => $item['is_change_price'],
                    'is_check_promotion' => $item['is_check_promotion'],
                    'inventory_management' => $item['inventory_management'],
                    'note' => $item['note'],
                    'attach' => $collectionDetail->where('order_detail_id_parent', $item['order_detail_id'])->all()
                ];
            }
        }

        // Bổ sung option payment method
        $mPaymentMethod = new PaymentMethodTable();
        $optionPaymentMethod = $mPaymentMethod->getOption();

        $mConfig = new ConfigTable();
        //Lấy cấu hình thay đổi giá
        $customPrice = $mConfig->getInfoByKey('customize_price')['value'];
        //Lấy option dịch vụ
        $optionService = $this->service_branch_price->getOptionService(Auth()->user()->branch_id);
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
        $amountDebt = $this->customer_debt->getItemDebt($data_receipt['customer_id']);

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

            $mConfigTab = app()->get(OrderConfigTabTable::class);

            //Lấy cấu hình tab
            $getTab = $mConfigTab->getConfigTab();

            return view('admin::orders.receipt-after', [
                'item' => $data_receipt,
                'order_detail' => $data_detail,
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
                'getTab' => $getTab
            ]);
        } else {
            return redirect()->route('admin.order');
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
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mOrderLog = new OrderLogTable();
            $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
            $session = $request->sessionSerial;
            $id_order = $request->order_id;
            $orderCode = $request->order_code;
            //Lấy số lẻ decimal
            $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
            //Lấy thông tin nhân viên
            $staff = $this->staff->getItem(Auth::id());
            //Lấy thông tin đơn hàng
            $infoOrder = $this->order->getItemDetail($request->order_id);

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

            //cập nhật lại thông tin đơn hàng
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
                'delivery_cost_id' => $request->delivery_cost_id

            ];
            //Chỉnh sửa đơn hàng
            $this->order->edit($data_order, $request->order_id);

            $list_card_print = [];
            $arrPromotionLog = [];
            $arrQuota = [];
            $arrObjectBuy = [];
            $arrRemindUse = [];

            // remove all detail => add again
            $this->order_detail->remove($request->order_id);

            //Xử lý table
            if (count($request->table_edit) > 0) {
                $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
                $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);

                foreach ($request->table_edit as $value) {
                    $isChangePrice = $value['is_change_price'] ?? 0;
                    $isCheckPromotion = $value['is_check_promotion'] ?? 0;
                    $position = $value['number_row'] ?? 0;
                    $staffId = isset($value['staff_id']) && $value['staff_id'] != null ? implode(',', $value['staff_id']) : null;

                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        if ($isCheckPromotion == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['object_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => self::LIVE,
                                'order_id' => $request->order_id,
                                'order_code' => $request->order_code
                            ];
                        }
                        //Lấy array nhắc sử dụng lại
                        $arrRemindUse[] = [
                            'object_type' => $value['object_type'],
                            'object_id' => $value['object_id'],
                            'object_code' => $value['object_code'],
                            'object_name' => $value['object_name'],
                        ];
                    }

                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['object_id'],
                        'object_name' => $value['object_name'],
                        'object_type' => $value['object_type'],
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => $value['discount'],
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'refer_id' => $request->refer_id,
                        'staff_id' => $staffId,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'note' => $value['note']
                    ];

                    if (isset($value['order_detail_id']) && $value['order_detail_id'] != null) {
                        $data_order_detail['order_detail_id'] = $value['order_detail_id'];
                    }

                    $id_detail = $this->order_detail->add($data_order_detail);

                    switch ($value['object_type']) {
                        case 'service':
                            //Lấy thông tin dịch vụ
                            $check_commission = $this->service->getItem($value['object_id']);
                            if(isset($check_commission)){
                                $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                                // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                                $this->order->calculatedCommission($value['quantity'], $request->refer_id, $check_commission, $id_detail, $value['object_id'], null, $value['amount'], $value['staff_id'] ?? null);
                            }
                           
                            break;
                        case 'product':
                            //Lấy thông tin sản phẩm
                            $check_commission = $this->product_child->getItem($value['object_id']);
                            if(isset($check_commission)){
                             
                                $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;


                                // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                                $this->order->calculatedCommission($value['quantity'], $request->refer_id, $check_commission, $id_detail, $value['object_id'], null, $value['amount'], $value['staff_id'] ?? null);
    
                                //Kiểm tra serial của đơn hàng đã được tạo hay chưa
                                $checkOrderSerial = $mOrderDetailSerial->getListSerialByOrder($id_order, $value['object_code']);
                                $tmpSerialLog = [];
                                if (count($checkOrderSerial) == 0) {
                                    $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $position, $value['object_code']);
                                    foreach ($listSerialLog as $itemSerialLog) {
                                        $tmpSerialLog[] = [
                                            'order_id' => $id_order,
                                            'order_detail_id' => $id_detail,
                                            'product_code' => $value['object_code'],
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
                            
                            break;
                        case 'service_card':
                            //Lấy thông tin thẻ dịch vụ
                            $sv_card = $this->service_card->getServiceCardOrder($value['object_code']);
                            //Lấy hoa hồng thẻ dịch vụ
                            $check_commission = $this->service_card->getServiceCardInfo($value['object_id']);
                            if(isset($check_commission)){
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
                                    $code = $this->code->generateCardListCode();
                                    while (array_search($code, $arr_result)) {
                                        $code = $this->code->generateCardListCode();
                                    }
                                    $data_card_list = [
                                        'service_card_id' => $value['object_id'],
                                        'is_actived' => 0,
                                        'created_by' => Auth::id(),
                                        'branch_id' => Auth()->user()->branch_id,
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
                                            'service_card_id' => $value['object_id'],
                                            'number_using' => $sv_card['number_using'],
                                            'count_using' => $sv_card['service_card_type'] == 'money' ? 1 : 0,
                                            'money' => $sv_card['money'],
                                            'actived_date' => date("Y-m-d"),
                                            'is_actived' => 1,
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'branch_id' => Auth()->user()->branch_id,
                                            'card_code' => $code
                                        ];
                                        if ($sv_card['date_using'] != 0) {
                                            $data_cus_card['expired_date'] = strftime("%Y-%m-%d", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $sv_card['date_using'] . 'days'))));
                                        }
                                        if ($sv_card['service_card_type'] == 'money') {
                                            //Lấy thông tin khách hàng
                                            $customer = $this->customer->getItem($request->customer_id);
                                            //Cập nhật lại tiền KH
                                            $this->customer->edit([
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
                                        $id_cus_card = $this->customer_service_card->add($data_cus_card);
                                        //Thêm vào service card list thẻ đã active
                                        $id_card_list = $this->service_card_list->add($data_card_list);
    
                                        array_push($list_card_print, $code);
                                        $arr_result[] = $code;
                                    } else {
                                        $id_card_list = $this->service_card_list->add($data_card_list);
                                        array_push($list_card_print, $code);
                                        $arr_result[] = $code;
                                    }
                                }
    
                                // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                                $this->order->calculatedCommission($value['quantity'], $request->refer_id, $check_commission, $id_detail, $value['object_id'], null, $value['amount'], $value['staff_id'] ?? null);
    
                            }
                            
                            break;
                        case 'member_card':
                            //Lấy thông tin thẻ liệu trình của KH
                            $list_cus_card = $this->customer_service_card->getItemCard($value['object_id']);

                            $data_edit_card = [
                                'count_using' => $list_cus_card['count_using'] + $value['quantity']
                            ];
                            //Cập nhật lại số lần sử dụng thẻ
                            $this->customer_service_card->editByCode($data_edit_card, $value['object_code']);

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($value['quantity'], $request->refer_id, null, $id_detail, $value['object_id'], $value['object_code'], $value['amount'], $value['staff_id'] ?? null, 0, 0, "member_card");

                            DB::commit();

                            //Insert email log
                            try {
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_EMAIL_CUSTOMER,
                                    'event' => 'is_event',
                                    'key' => 'service_card_over_number_used',
                                    'object_id' => $value['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                                //Insert sms log
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_SMS_CUSTOMER,
                                    'key' => 'service_card_over_number_used',
                                    'object_id' => $value['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                                //Lưu log ZNS
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_ZNS_CUSTOMER,
                                    'key' => 'service_card_over_number_used',
                                    'customer_id' => $request->customer_id,
                                    'object_id' => $value['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                                //Send notification
                                App\Jobs\FunctionSendNotify::dispatch([
                                    'type' => SEND_NOTIFY_CUSTOMER,
                                    'key' => 'service_card_over_number_used',
                                    'customer_id' => $request->customer_id,
                                    'object_id' => $value['object_id'],
                                    'tenant_id' => session()->get('idTenant')
                                ]);
                            } catch (Exception $ex) {
                                Log::error($ex->getMessage());
                            }
                            break;
                    }

                    if ($value['voucher_code'] != null) {
                        //Lấy thông tin voucher
                        $get = $this->voucher->getCodeItem($value['voucher_code']);
                        //Cập nhật số lần sử dụng voucher
                        $this->voucher->editVoucherOrder([
                            'total_use' => ($get['total_use'] + 1)
                        ], $value['voucher_code']);
                    }
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
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $mOrderApp->plusQuotaUsePromotion($arrQuota);
            }

            // xử lý voucher
            if ($request->voucher_bill != null) {
                $get = $this->voucher->getCodeItem($request->voucher_bill);
                $data = [
                    'total_use' => ($get['total_use'] + 1)
                ];
                $this->voucher->editVoucherOrder($data, $request->voucher_bill);
            }

            //Lấy phương thức thanh toán
            $arrMethodWithMoney = $request->array_method;

            $amount_bill = str_replace(',', '', $request->amount_bill);
            $amount_receipt = str_replace(',', '', $request->amount_receipt);

            if ($request->amount_all != '') {
                $amount_receipt_all = 0;

                foreach ($arrMethodWithMoney as $methodCode => $money) {
                    if ($money > 0) {
                        $amount_receipt_all += $money;
                    }
                }
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
                $this->order->edit(['process_status' => 'pay-half'], $request->order_id);
            }
            if ($amount_receipt != 0) {
                if ($amount_receipt_all < $amount_receipt) {
                    //Check KH là hội viên
                    if ($request->customer_id != 1) {
                        //Check cấu hình thanh toán nhiều lần
                        $check_info = $this->spa_info->getInfoSpa();
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
                                $debt_id = $this->customer_debt->add($data_debt);
                                //update debt code
                                $day_code = date('dmY');
                                if ($debt_id < 10) {
                                    $debt_id = '0' . $debt_id;
                                }
                                $debt_code = [
                                    'debt_code' => 'CN_' . $day_code . $debt_id
                                ];
                                $this->customer_debt->edit($debt_code, $debt_id);
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
                'total_money' => $amount_receipt_all > $amount_bill ? $amount_bill : $amount_receipt_all,
                'voucher_code' => $request->voucher_bill,
                'status' => $status,
                'is_discount' => 1,
                'amount' => $amount_bill,
                'amount_paid' => $amount_receipt_all > $amount_bill ? $amount_bill : $amount_receipt_all,
                'amount_return' => $amount_receipt_all > $amount_bill ? $amount_receipt_all - $amount_bill : 0,
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

            $id_receipt = $this->receipt->add($data_receipt);
            $day_code = date('dmY');
            $data_code = [
                'receipt_code' => 'TT_' . $day_code . $id_receipt
            ];
            $this->receipt->edit($data_code, $id_receipt);

            if (count($request->table_edit) > 0) {
                foreach ($request->table_edit as $key => $value) {
                    if ($value['object_type'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $id_receipt,
                            'cashier_id' => Auth::id(),
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $value['price'],
                            'amount' => 0,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $this->receipt_detail->add($data_receipt_detail);
                    }
                }
            }

            $detailOrder = $this->order->getItemDetail($request->order_id);
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
                                $this->receipt_detail->add($dataReceiptDetail);
                                //Lấy thông tin KH
                                $customerMoney = $this->customer->getItem($request->customer_id);
                                //Cập nhật lại tiền KH
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
                        $this->receipt_detail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $id_receipt,
                            'status' => 'success'
                        ], 'order', $request->order_id, $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $this->receipt_detail->add($dataReceiptDetail);
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
                        $this->receipt_detail->add($dataReceiptDetail);
                    }
                }
            }
            $data_print = [];
            if (count($list_card_print) > 0) {
                foreach ($list_card_print as $k => $v) {
                    $get_cus_card = $this->service_card_list->searchCard($v);
                    $get_sv_card = $this->service_card->getServiceCardInfo($get_cus_card['service_card_id']);

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
            $listOrderProduct = $this->order_detail->getValueByOrderIdAndObjectType($request->order_id, 'product');
            $listService = $this->order_detail->getValueByOrderIdAndObjectType($request->order_id, 'service');
            $listServiceMaterials = [];

            $isCheckProductAttach = false;
            foreach ($listService as $item) {
                //Lấy sản phẩm đi kèm dịch vụ.
                $serviceMaterial = $this->serviceMaterial->getItem($item['object_id']);
                if (count($serviceMaterial) > 0) {
                    $isCheckProductAttach = true;
                    foreach ($serviceMaterial as $value) {
                        //                    dd($this->product_branch_price->getProductBranchPriceByCode(Auth::user()->branch_id, $value['material_code']));
                        $currentPrice = $this->product_branch_price->getProductBranchPriceByCode(Auth::user()->branch_id, $value['material_code'])['new_price'];
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
                $checkWarehouse = $this->warehouse->getWarehouseByBranch(Auth::user()->branch_id);
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
                $idInventoryOutput = $this->inventoryOutput->add($dataInventoryOutput);
                $idCode = $idInventoryOutput;
                if ($idInventoryOutput < 10) {
                    $idCode = '0' . $idCode;
                }
                $this->inventoryOutput->edit(['po_code' => $this->code->codeDMY('XK', $idCode)], $idInventoryOutput);
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
            $checkSerialQuantity = 0;

            $tmpListSerial = [];
            // Danh sách sản phẩm
            foreach ($listOrderProduct as $item) {

                //                kiểm tra mã sản phẩm đã được tạo trong phiếu xuất kho hay chưa
                $checkProductInventotyOutput = $mInventoryOutputDetail->checkProductInventotyOutput($idInventoryOutput, $item['object_code']);

                $getDetailOutputDetail = $mInventoryOutputDetail->checkInventoryOutput($idInventoryOutput, $item['object_code']);

                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $item['object_code'],
                    'quantity' => $getDetailOutputDetail != null ? (int)$getDetailOutputDetail['quantity'] + (int)$item['quantity'] : $item['quantity'],
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
                    $idIOD = $this->inventoryOutputDetail->add($dataInventoryOutputDetail);
                }

                if (count($checkProductInventotyOutput) == 0) {
                    //                Lấy danh sách serial theo sản phẩm ở đơn hàng

                    $listOrderSerialDetail = $mOrderDetailSerial->getListSerialByOrder($id_order, $item['object_code']);

                    if (count($listOrderSerialDetail) == 0) {
                        $listOrderSerialDetail = $mOrderSessionSerialLog->getListProductOrder(['session' => $request->sessionSerial, 'productCode' => $item['object_code']]);
                    }

                    if (count($listOrderSerialDetail) != 0 && $dataInventoryOutputDetail['quantity'] != count($listOrderSerialDetail)) {
                        $this->inventoryOutput->edit(['status' => 'new'], $idInventoryOutput);
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
                $productId = $this->productChild->getProductChildByCode($item['object_code'])['product_child_id'];
                $checkProductInventory = $this->productInventory->checkProductInventory($item['object_code'], $warehouseId);
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
                        $this->productInventory->edit(
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
                            $this->productInventory->add($dataEditProductInventoryInsert);
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
                    $idIOD = $this->inventoryOutputDetail->add($dataInventoryOutputDetail);
                    //Trừ tồn kho.
                    $productId = $this->productChild->getProductChildByCode($item['product_code'])['product_child_id'];
                    $checkProductInventory = $this->productInventory->checkProductInventory($item['product_code'], $warehouseId);
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
                            $this->productInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
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
                                $this->productInventory->add($dataEditProductInventoryInsert);
                            }
                        }
                    }
                }
            }
            $checkSendSms = $this->smsConfig->getItemByType('paysuccess');

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
            $customer = $this->customer->getItem($request->customer_id);
            $dataTableAdd = $request->table_add;
            $dataTableEdit = $request->table_edit;

            if ($customer['customer_code'] != null) {
                $this->order->addWarrantyCard($customer['customer_code'], $request->order_id, $request->order_code, $dataTableAdd, $dataTableEdit);
            }

            //Lưu log dự kiến nhắc sử dụng lại
            $this->order->insertRemindUse($request->order_id, $request->customer_id, $arrRemindUse);

            //Lưu thông tin hàng hoá cho hợp đồng
            $this->order->updateContractGoods($request->order_id, 1);

            DB::commit();

            try {
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
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
            }

            //Tính điểm thưởng khi thanh toán
            if ($amount_receipt_all >= $amount_bill) {
                $this->bookingApi->plusPointReceiptFull(['receipt_id' => $id_receipt]);
            } else {
                $this->bookingApi->plusPointReceipt(['receipt_id' => $id_receipt]);
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
     * Chi tiết đơn hàng
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|View|mixed
     */
    public function detailAction($id)
    {
        $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
        $mReceipt = app()->get(ReceiptTable::class);

        //Lấy thông tin đơn hàng
        $order = $this->order->getItemDetail($id);
        $list_table = $this->order_detail->getItem($order['order_id']);
        $arr = [];

        $collectionDetail = collect($list_table->toArray());

        foreach ($list_table as $key => $item) {
            if ($item['order_detail_id_parent'] == null) {
                $staffName = "";
                if ($item['staff_id'] != null && $item['staff_id'] != "") {
                    $arrStaff = explode(",", $item['staff_id']);
                    if (count($arrStaff) > 0) {
                        foreach ($arrStaff as $value) {
                            $staffInfo = $this->staff->getItem($value);
                            if ($staffName == "") {
                                $staffName = $staffInfo['full_name'];
                            } else {
                                $staffName = $staffName . ', ' . $staffInfo['full_name'];
                            }
                        }
                    }
                }

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
                    'note' => $item['note'],
                    'attach' => $collectionDetail->where('order_detail_id_parent', $item['order_detail_id'])->all(),
                ];
            }
        }

        $listOrderDetailSerial = $mOrderDetailSerial->getListSerialByOrderId($order['order_id']);

        if (count($listOrderDetailSerial) != 0) {
            $listOrderDetailSerial = collect($listOrderDetailSerial)->groupBy('order_detail_id');
        }

        //Lấy thông tin thanh toán của đơn hàng
        $receipt = $this->receipt->getItem($id);

        $mReceiptDetail = new ReceiptDetailTable();
        //Lấy chi tiết thanh toán
        // $list_receipt_detail = $mReceiptDetail->getListDetailByOrderId($order['order_id']);

       
        if ($order != null) {
            $mOrderImage = app()->get(OrderImageTable::class);
            $mContractMapOrder = app()->get(ContractMapOrderTable::class);
            $mConfig = app()->get(ConfigTable::class);

            //Lấy lịch sử thanh toán của đơn hàng
            $receiptOrder = $mReceipt->getReceiptByOrder($order['order_id']);
             //Tiền đã thanh toán
            $amountPaid = 0;

            foreach ($receiptOrder as $v) {
                $amountPaid += $v['amount_paid'];
            }
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

            return view('admin::orders.detail-load', [
                'order' => $order,
                'oder_detail' => $arr,
                'receipt' => $receipt,
                // 'receipt_detail' => $list_receipt_detail,
                'orderImage' => $orderImage,
                'isCreateContract' => $isCreateContract,
                'listOrderDetailSerial' => $listOrderDetailSerial,
                'detailAddress' => $detailAddress,
                'receiptOrder' => $receiptOrder,
                'amountPaid' => $amountPaid
            ]);
        } else {
            return redirect()->route('admin.order');
        }
    }

    /**
     * Lấy list thẻ liệu trình của KH
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCardCustomerAction(Request $request)
    {
        $mConfig = new ConfigTable();

        $id = $request->id;

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }
        //Lấy ds thẻ liệu trình còn hạn sử dụng
        $list_card_active = $this->customer_service_card->loadCardMember($id, $branchId);

        $data = [];

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
                        'type' => 'member_card'
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
                            'type' => 'member_card'
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
                            'expired_date' => date('d/m/Y', strtotime($item['expired_date'])),
                            'type' => 'member_card'
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
                                'expired_date' => date('d/m/Y', strtotime($item['expired_date'])),
                                'type' => 'member_card'
                            ];
                        }
                    }
                }
            }
        }
        return response()->json([
            'number_card' => count($data),
            'data' => $data
        ]);
    }

    

    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function printCardAllAction(Request $request)
    {
        $view = view('admin::orders.print-card.print-all', [
            'list_image' => $request->list_image
        ])->render();
        return $view;
    }

    public function printOneCardAction(Request $request)
    {
        $view = view('admin::orders.print-card.print-one', [
            'image' => $request->base
        ])->render();
        return response()->json($view);
    }

    public function saveLogPrintBillAction(Request $request)
    {
        $orderId = $request->id;
        $branch = $this->staff->getItem(Auth::id())->branch_id;
        $orderCode = $this->order->getItemDetail($orderId)->order_code;
        $staffPrintBy = Auth::id();
        $created = date('Y-m-d H:i:s');

        $checkPrintBill = $this->printBillLog->checkPrintBillOrder($orderCode);
        $printTime = count($checkPrintBill);
        $configPrintBill = $this->configPrintBill->getItem(1);
        if ($configPrintBill['is_print_reply'] == 0) {
            if ($printTime > 0) {
                return response()->json(['error' => __('Chỉ được in 01 lần')]);
            } else {
                $data = [
                    'branch_id' => $branch,
                    'order_code' => $orderCode,
                    'staff_print_reply_by' => '',
                    'staff_print_by' => $staffPrintBy,
                    'created_at' => $created,
                ];
                $this->printBillLog->add($data);
                return response()->json(['success' => 1]);
            }
        } else {
            if ($configPrintBill['print_time'] != null) {
                if ($printTime >= $configPrintBill['print_time']) {
                    return response()->json(['error' => __('Vượt quá số lần in cho phép')]);
                } else {
                    if ($printTime == 0) {
                        $data = [
                            'branch_id' => $branch,
                            'order_code' => $orderCode,
                            'staff_print_reply_by' => '',
                            'staff_print_by' => $staffPrintBy,
                            'created_at' => $created,
                        ];
                    } else {
                        $data = [
                            'branch_id' => $branch,
                            'order_code' => $orderCode,
                            'staff_print_reply_by' => $staffPrintBy,
                            'staff_print_by' => '',
                            'created_at' => $created,
                        ];
                    }
                    $this->printBillLog->add($data);
                    return response()->json(['success' => 1, 'error' => '']);
                }
            } else {
                if ($printTime == 0) {
                    $data = [
                        'branch_id' => $branch,
                        'order_code' => $orderCode,
                        'staff_print_reply_by' => '',
                        'staff_print_by' => $staffPrintBy,
                        'created_at' => $created,
                    ];
                } else {
                    $data = [
                        'branch_id' => $branch,
                        'order_code' => $orderCode,
                        'staff_print_reply_by' => $staffPrintBy,
                        'staff_print_by' => '',
                        'created_at' => $created,
                    ];
                }
                $this->printBillLog->add($data);
                return response()->json(['success' => 1, 'error' => '']);
            }
        }
    }

    public function checkEmailCustomerAction(Request $request)
    {
        $customer_id = $request->customer_id;
        $get_customer = $this->customer->getItem($customer_id);
        if ($get_customer['email'] != null) {
            return response()->json([
                'email_success' => 1,
                'success' => __('Khách hàng có email'),
                'email' => $get_customer['email']
            ]);
        } else {
            return response()->json([
                'email_null' => 1,
                'success' => 'email null'
            ]);
        }
    }

    public function submitSendEmailAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer_id = $request->customer_id;
            $email = $request->email;
            $list_image = $request->list_image;
            $data_img = [];
            $list_link_image = [];
            //Mã hóa hình base64 upload vào storage
            $time = Carbon::now();
            foreach ($list_image as $item) {
                $image = str_replace('data:image/png;base64,', '', $item);
                $image = str_replace(' ', '+', $image);
                $imageName = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_card." . 'png';
                $uploads = Storage::disk('public')->put(TEMP_PATH . '/' . $imageName, base64_decode($image));
                $data_img[] = [
                    'image_name' => $imageName,
                    'uploads' => $uploads
                ];
            }
            //Move hình từ storage vào folder chính
            foreach ($data_img as $item) {
                $old_path = TEMP_PATH . '/' . $item['image_name'];
                $new_path = SEND_EMAIL_CARD . date('Ymd') . '/' . $item['image_name'];
                Storage::disk('public')->makeDirectory(CUSTOMER_UPLOADS_PATH . date('Ymd'));
                Storage::disk('public')->move($old_path, $new_path);
                $list_link_image[] = $new_path;
            }
            $customer_name = '';
            if ($customer_id == 1) {
                $customer_name = __('Khách hàng vãng lai');
            } else {
                $customer = $this->customer->getItem($customer_id);
                $customer_name = $customer['full_name'];
            }
            $get_provider = $this->email_provider->getItem(1);
            $data = [
                'customer_name' => $customer_name,
                'email' => $email,
                'email_type' => 'print_card',
                'content_sent' => implode(";", $list_link_image),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'object_id' => $customer_id,
                'object_type' => 'customer',
                'email_status' => 'new'
            ];

            $id_add = $this->email_log->add($data);

            ///Gọi hàm send email
            //            Mail::to($email)->send(new SendMailable($customer_name, 'Danh sách thẻ dịch vụ', implode(";", $list_link_image), 'print_card', '', $get_provider['email_template_id']));
            //            $data_edit = [
            //                'time_sent_done' => date('Y-m-d H:i'),
            //            ];
            //            $this->email_log->edit($data_edit, $id_add);
            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => __('Gửi email thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
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
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mOrderLog = new OrderLogTable();
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
            $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);

            if ($request->receipt_info_check == 1 && !isset($request->delivery_type)) {
                return response()->json([
                    'error' => true,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }

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
                'refer_id' => $request->refer_id,
                //                'tranport_charge' => str_replace(',', '', $request->tranport_charge),
                'customer_contact_code' => $request->receipt_info_check == 1 && $detailAddress != null ? $detailAddress['customer_contact_code'] : '',
                'customer_contact_id' => $request->receipt_info_check == 1 ? $request->customer_contact_id : '',
                'receive_at_counter' => $request->receipt_info_check == 1 ? 0 : 1,
                'type_time' => $request->receipt_info_check == 1 ? $request->type_time : '',
                'time_address' => $request->receipt_info_check == 1 && $request->time_address != '' ? Carbon::createFromFormat('Y-m-d', $request->time_address)->format('Y-m-d') : '',
                'tranport_charge' => $request->tranport_charge,
                'type_shipping' => $request->delivery_type,
                'delivery_cost_id' => $request->delivery_cost_id,
                'discount_member' => $request->discount_member
            ];

            $this->order->edit($data_order, $request->order_id);

            //Xóa chi tiết đơn hàng cũ
            $this->order_detail->remove($request->order_id);
            //Xoá serial đơn hàng cũ
            $mOrderDetailSerial->removeSerial($request->order_id);

            $arrObjectBuy = [];

            $id_order = $request->order_id;
            $session = $request->sessionSerial;

            if (count($request->table_edit) > 0) {
                foreach ($request->table_edit as $value) {
                    $isChangePrice = $value['is_change_price'] ?? 0;
                    $isCheckPromotion = $value['is_check_promotion'] ?? 0;
                    $numberRow = $value['number_row'] ?? null;

                    if (in_array($value['object_type'], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                        $arrObjectBuy[] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['object_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $request->customer_id,
                            'order_source' => self::LIVE,
                            'order_id' => $request->order_id,
                            'order_code' => $request->order_code
                        ];
                    }

                    $data_order_detail = [
                        'order_id' => $request->order_id,
                        'object_id' => $value['object_id'],
                        'object_name' => $value['object_name'],
                        'object_type' => $value['object_type'],
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => $value['discount'],
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'refer_id' => $request->refer_id,
                        'staff_id' => isset($value['staff_id']) && $value['staff_id'] != null ? implode(',', $value['staff_id']) : null,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'note' => $value['note'] ?? null
                    ];

                    if (isset($value['order_detail_id']) && $value['order_detail_id'] != null) {
                        $data_order_detail['order_detail_id'] = $value['order_detail_id'];
                    }

                    //Thêm chi tiết đơn hàng
                    $orderDetailId = $this->order_detail->add($data_order_detail);

                    if ($value['object_type'] == 'product') {
                        $tmpSerial = [];
                        $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $numberRow, $value['object_code']);
                        foreach ($listSerialLog as $item) {
                            $tmpSerial[] = [
                                'order_id' => $id_order,
                                'order_detail_id' => $orderDetailId,
                                'product_code' => $value[3],
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

                    //Lưu dịch vụ kèm theo
                    if (isset($value['array_attach']) && count($value['array_attach']) > 0) {
                        foreach ($value['array_attach'] as $v1) {
                            $dataDetailAttach = [
                                'order_id' => $request->order_id,
                                'object_id' => $v1['object_id'],
                                'object_name' => $v1['object_name'],
                                'object_type' => $v1['object_type'],
                                'object_code' => $v1['object_code'],
                                'price' => $v1['price'],
                                'quantity' => $v1['quantity'],
                                'amount' => $v1['price'] * $v1['quantity'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'order_detail_id_parent' => $orderDetailId
                            ];

                            if (isset($v1['order_detail_id']) && $v1['order_detail_id'] != null) {
                                $dataDetailAttach['order_detail_id'] = $v1['order_detail_id'];
                            }

                            //Lưu chi tiết đơn hàng
                            $this->order_detail->add($dataDetailAttach);
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
                $this->order->edit([
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
            $this->order->updateContractGoods($request->order_id, 0);
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
     * Lưu thông tin đơn hàng từ lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOrderToAppointmentAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);

            //Insert log cập nhật trang thái lịch hẹn
            $this->insertLogEdit($request->customer_appointment_id, 'finish');
            $data_app = [
                'status' => 'finish'
            ];
            $this->customer_appointment->edit($data_app, $request->customer_appointment_id);
            $staff_branch = $this->staff->getItem(Auth::id());
            $data_order = [
                'customer_id' => $request->customer_id,
                'total' => $request->total_bill,
                'discount' => $request->discount_bill,
                'amount' => $request->amount_bill,
                'voucher_code' => $request->voucher_bill,
                'branch_id' => $staff_branch['branch_id'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'order_source_id' => 1
            ];
            $id_order = $this->order->add($data_order);
            $day_code = date('dmY');
            if ($id_order < 10) {
                $id_order = '0' . $id_order;
            }

            $orderCode = 'DH_' . $day_code . $id_order;

            $this->order->edit([
                'order_code' => $orderCode
            ], $id_order);

            $arrPromotionLog = [];
            $arrObjectBuy = [];

            if ($request->table_add != null) {
                $aData = array_chunk($request->table_add, 15, false);
                foreach ($aData as $key => $value) {
                    $value[4] = str_replace(',', '', $value[4]);
                    $value[9] = str_replace(',', '', $value[9]);
                    $isChangePrice = isset($value[13]) ? $value[13] : 0;
                    $isCheckPromotion = isset($value[14]) ? $value[14] : 0;

                    if (in_array($value[2], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                        $arrObjectBuy[] = [
                            'object_type' => $value[2],
                            'object_code' => $value[3],
                            'object_id' => $value[0],
                            'price' => $value[4],
                            'quantity' => $value[5],
                            'customer_id' => $request->customer_id,
                            'order_source' => self::LIVE,
                            'order_id' => $id_order,
                            'order_code' => $orderCode
                        ];
                    }

                    $data_order_detail = [
                        'order_id' => $id_order,
                        'object_id' => $value[0],
                        'object_name' => $value[1],
                        'object_type' => $value[2],
                        'object_code' => $value[3],
                        'price' => $value[4],
                        'quantity' => $value[5],
                        'discount' => str_replace(',', '', $value[7]),
                        'voucher_code' => $value[8],
                        'amount' => $value[9],
                        'staff_id' => $value[10] != null ? implode(',', $value[10]) : null,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    $this->order_detail->add($data_order_detail);
                }
            }

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

            //Cộng điểm khi mua hàng trực tiếp
            $mPlusPoint = new LoyaltyApi();
            $mPlusPoint->plusPointEvent([
                'customer_id' => $request->customer_id,
                'rule_code' => 'order_direct',
                'object_id' => $id_order
            ]);

            DB::commit();
            $mNoti = new SendNotificationApi();
            //Send notification
            if ($request->customer_id != 1) {
                // Thông báo đã đặt hàng thành công
                $mNoti->sendNotification([
                    'key' => 'order_status_W',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id_order
                ]);
            }

            //Thông báo NV khi có đơn hàng mới
            $mNoti->sendStaffNotification([
                'key' => 'order_status_W',
                'customer_id' => $request->customer_id,
                'object_id' => $id_order,
                'branch_id' => Auth()->user()->branch_id
            ]);

            return response()->json([
                'error' => false,
                'message' => __('Thêm thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lưu hoặc chỉnh sửa đơn hàng từ lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function saveOrUpdateOrderToAppointmentAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);

            if ($request->table_add == null) {
                return response()->json([
                    'error' => true,
                    'message' => __('Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm')
                ]);
            }

            if ($request->receipt_info_check == 1 && !isset($request->delivery_type)) {
                return response()->json([
                    'error' => true,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }

            //Insert log cập nhật trang thái lịch hẹn
            $this->insertLogEdit($request->customer_appointment_id, 'finish');
            //Cấp nhật trạng thái lịch hẹn
            $this->customer_appointment->edit( [
                'status' => 'finish'
            ], $request->customer_appointment_id);

            $mCustomerContact = app()->get(CustomerContactTable::class);

            $detailAddress = $mCustomerContact->getDetailContact($request->customer_contact_id);

            $data_order = [
                'customer_id' => $request->customer_id,
                'total' => $request->total_bill,
                'discount' => $request->discount_bill,
                'amount' => $request->amount_bill,
                'voucher_code' => $request->voucher_bill,
                'order_description' => $request->order_description,
                'branch_id' => Auth()->user()->branch_id,
                'refer_id' => $request->refer_id,
                'customer_contact_code' => $detailAddress != null ? $detailAddress['customer_contact_code'] : '',
                'customer_contact_id' => $request->customer_contact_id,
                'receive_at_counter' => $request->receipt_info_check == 1 ? 0 : 1,
                'type_time' => $request->type_time,
                'time_address' => $request->time_address != '' ? Carbon::createFromFormat('d/m/Y', $request->time_address)->format('Y-m-d') : '',
                'tranport_charge' => $request->tranport_charge,
                'type_shipping' => $request->delivery_type,
                'delivery_cost_id' => $request->delivery_cost_id,
                'discount_member' => $request->discount_member,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            $isCreateTicket = 0;

            if (isset($request->order_id) && $request->order_id != '') {
                $id_order = $request->order_id;
                $orderCode = $request->order_code;
                //Chỉnh sửa đơn hàng
                $this->order->edit($data_order, $id_order);

                $arrObjectBuy = [];

                if ($request->table_add != null) {
                    //Xoá chi tiết đơn hàng cũ
                    $this->order_detail->remove($id_order);

                    foreach ($request->table_add as $key => $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card']) && $v['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $v['object_type'],
                                'object_code' => $v['object_code'],
                                'object_id' => $v['object_id'],
                                'price' => $v['price'],
                                'quantity' => $v['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }

                        //Thêm chi tiết đơn hàng
                        $orderDetailId = $this->order_detail->add([
                            'order_id' => $id_order,
                            'object_id' => $v['object_id'],
                            'object_name' => $v['object_name'],
                            'object_type' => $v['object_type'],
                            'object_code' => $v['object_code'],
                            'price' => $v['price'],
                            'quantity' => $v['quantity'],
                            'discount' => $v['discount'],
                            'voucher_code' => $v['voucher_code'],
                            'amount' => $v['amount'],
                            'refer_id' => $request->refer_id,
                            'staff_id' => isset($v['staff_id']) && $v['staff_id'] != null ? implode(',', $v['staff_id']) : null,
                            'is_change_price' => $v['is_change_price'],
                            'is_check_promotion' => $v['is_check_promotion'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'note' => $v['note'] ?? null
                        ]);

                        //Lưu dịch vụ kèm theo
                        if (isset($v['array_attach']) && count($v['array_attach']) > 0) {
                            foreach ($v['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $this->order_detail->add([
                                    'order_id' => $id_order,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
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
            } else {
                //Thêm đơn hàng
                $id_order = $this->order->add($data_order);

                if ($id_order < 10) {
                    $id_order = '0' . $id_order;
                }

                $orderCode = 'DH_' . date('dmY') . $id_order;
                //Cập nhật mã đơn hàng
                $this->order->edit([
                    'order_code' => $orderCode
                ], $id_order);

                $arrPromotionLog = [];
                $arrObjectBuy = [];

                if ($request->table_add != null) {
                    foreach ($request->table_add as $key => $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card']) && $v['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $v['object_type'],
                                'object_code' => $v['object_code'],
                                'object_id' => $v['object_id'],
                                'price' => $v['price'],
                                'quantity' => $v['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }

                        //Lưu chi tiết đơn hàng
                        $orderDetailId = $this->order_detail->add([
                            'order_id' => $id_order,
                            'object_id' => $v['object_id'],
                            'object_name' => $v['object_name'],
                            'object_type' => $v['object_type'],
                            'object_code' => $v['object_code'],
                            'price' => $v['price'],
                            'quantity' => $v['quantity'],
                            'discount' => str_replace(',', '', $v['discount']),
                            'voucher_code' => $v['voucher_code'],
                            'amount' => str_replace(',', '', $v['amount']),
                            'refer_id' => $request->refer_id,
                            'staff_id' => isset($v['staff_id']) && $v['staff_id'] != null ? implode(',', $v['staff_id']) : null,
                            'is_change_price' => $v['is_change_price'],
                            'is_check_promotion' => $v['is_check_promotion'],
                            'note' => $v['note'] ?? null,
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ]);

                        //Lưu dịch vụ kèm theo
                        if (isset($v['array_attach']) && count($v['array_attach']) > 0) {
                            foreach ($v['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $this->order_detail->add([
                                    'order_id' => $id_order,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
                            }
                        }
                    }
                }

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

                //Cộng điểm khi mua hàng trực tiếp
                $mPlusPoint = new LoyaltyApi();
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $request->customer_id,
                    'rule_code' => 'order_direct',
                    'object_id' => $id_order
                ]);

                $mConfig = app()->get(ConfigTable::class);
                //Kiểm tra có tạo ticket không
                $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

                if ($request->customer_id != 1 && $configCreateTicket == 1) {
                    $isCreateTicket = 1;
                }

                DB::commit();

                try {
                    //Insert email log
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_EMAIL_CUSTOMER,
                        'event' => 'is_event',
                        'key' => 'order_success',
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                    //Insert sms log
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_SMS_CUSTOMER,
                        'key' => 'order_success',
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);

                    //Send notification
                    if ($request->customer_id != 1) {
                        //Gửi thông báo khách hàng
                        App\Jobs\FunctionSendNotify::dispatch([
                            'type' => SEND_NOTIFY_CUSTOMER,
                            'key' => 'order_status_W',
                            'customer_id' => $request->customer_id,
                            'object_id' => $id_order,
                            'tenant_id' => session()->get('idTenant')
                        ]);
                    }
                    //Gửi thông báo nhân viên
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_STAFF,
                        'key' => 'order_status_W',
                        'customer_id' => $request->customer_id,
                        'object_id' => $id_order,
                        'branch_id' => Auth()->user()->branch_id,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                } catch (Exception $ex) {
                    Log::error($ex->getMessage());
                }
            }

            return response()->json([
                'error' => false,
                'order_id' => $id_order,
                'order_code' => $orderCode,
                'is_create_ticket' => $isCreateTicket,
                'message' => __('Thêm thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkVoucherReceiptAfterAction(Request $request)
    {
        $is_success = true;
        //Check voucher từng dòng mua hàng
        if (count($request->voucher_using) > 0) {
            $arr = [];
            foreach ($request->voucher_using as $key => $value) {
                $arr[$value['type']][] = $value['code'];
            }
            if (isset($arr['service'])) {
                $code_service = array_count_values($arr['service']);
                if (count($code_service) > 0) {
                    foreach ($code_service as $key => $value) {
                        //Kiểm tra voucher dịch vụ
                        $voucher_service = $this->voucher->getCodeOrder($key, 'service');
                        if (($voucher_service['total_use'] + $value) > $voucher_service['quota']) {
                            $is_success = false;
                        }
                    }
                }
            }
            if (isset($arr['product'])) {
                $code_product = array_count_values($arr['product']);
                if (count($code_product) > 0) {
                    foreach ($code_product as $key => $value) {
                        //Kiểm tra voucher dịch vụ
                        $voucher_service = $this->voucher->getCodeOrder($key, 'product');
                        if (($voucher_service['total_use'] + $value) > $voucher_service['quota']) {
                            $is_success = false;
                        }
                    }
                }
            }
            if (isset($arr['service_card'])) {
                $code_service_card = array_count_values($arr['service_card']);
                if (count($code_service_card) > 0) {
                    foreach ($code_service_card as $key => $value) {
                        //Kiểm tra voucher dịch vụ
                        $voucher_service = $this->voucher->getCodeOrder($key, 'service_card');
                        if (($voucher_service['total_use'] + $value) > $voucher_service['quota']) {
                            $is_success = false;
                        }
                    }
                }
            }
        }
        //Check voucher tổng bill
        $voucher_bill = $this->voucher->getCodeOrder($request->voucher_bill, 'all');
        if (isset($voucher_bill)) {
            if (($voucher_bill['total_use'] + 1) > $voucher_bill['quota']) {
                $is_success = false;
            }
        }
        return response()->json([
            'success' => __('Kiểm tra voucher thành công'),
            'is_success' => $is_success
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
        $id = $request->ptintorderid;
        //Lấy thông tin đơn hàng
        $order = $this->order->getItemDetail($id);

        $lstReceipt = $this->receipt->getReceiptOrderList($id);

        $amount_paid = 0;
        $amount_return = 0;
        $totalDiscount = 0;
        foreach ($lstReceipt as $key => $objReceipt) {
            $amount_paid += $objReceipt['amount_paid'];
            $amount_return += $objReceipt['amount_return'];
            $totalDiscount += $objReceipt['discount'];
        }
        //Lấy chi tiết đơn hàng
        $list_table = $this->order_detail->getItem($id);



        // $totalDiscount = $order['discount'];

        $arr = [];
        $totalQuantity = 0;
        $totalDiscountDetail = 0;
        foreach ($list_table as $key => $item) {
            $unitName = null;
            //Lấy đơn vị tính nếu là sản phẩm, sản phẩm quà tặng (object_type = product, product_gift)
            if ($item['object_type'] == 'product' || $item['object_type'] == 'product_gift') {
                $productInfo = $this->product->getItem($item['object_id']);
                if ($productInfo != null) {
                    $unitName = $productInfo['unitName'];
                }
            }
            $arr[] = [
                'order_detail_id' => $item['order_detail_id'],
                'object_id' => $item['object_id'],
                'note' => $item['note'],
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
            $totalQuantity += (int)$item['quantity'];
            $totalDiscountDetail += $item['discount'];
            $totalDiscount += $item['discount'];
        }
        //Lấy cấu hình in bill
        $configPrintBill = $this->configPrintBill->getItem(1);

        isset($order['branch_id']) ? $order['branch_id'] : '';
        //Lấy thông tin chi nhánh của đơn hàng
        $branchInfo = $this->branch->getItem($order['branch_id']);
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
        $template = 'admin::orders.print-not-receipt.content-print';

        switch ($configPrintBill->template) {
            case 'k58':
                $template = 'admin::orders.print-not-receipt.template-k58';
                break;
            case 'A5':
                $template = 'admin::orders.print-not-receipt.template--a5';
                break;
            case 'A5-landscape':
                $template = 'admin::orders.print-not-receipt.template--a5-landscape';
                break;
            case 'A4':
                $template = 'admin::orders.print-not-receipt.template-a4';
                break;
            case 'k80':
                $template = 'admin::orders.print-not-receipt.template-k80';
                break;
        }
        //Lấy số lần in bill của đơn hàng này
        $checkPrintBill = $this->printBillLog->checkPrintBillOrder($order['order_code']);
        $printTime = count($checkPrintBill);
        $printReply = '';
        if ($printTime > 0) {
            $printReply = __('(In lại)');
        }
        $maxId = $this->printBillLog->getBiggestId();
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
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, self::PLUS);
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, self::SUBTRACT);
        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        return view($template, [
            'order' => $order,
            'oder_detail' => $arr,
            'spaInfo' => $this->spaInfo->getInfoSpa(),
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

    public function getInfoForPrintBill(Request $request){
        $id = $request->ptintorderid;
        //Lấy thông tin đơn hàng
        $order = $this->order->getItemDetail($id);

        $lstReceipt = $this->receipt->getReceiptOrderList($id);

        $amount_paid = 0;
        $amount_return = 0;
        $totalDiscount = 0;
        foreach ($lstReceipt as $key => $objReceipt) {
            $amount_paid += $objReceipt['amount_paid'];
            $amount_return += $objReceipt['amount_return'];
            $totalDiscount += $objReceipt['discount'];
        }
        //Lấy chi tiết đơn hàng
        $list_table = $this->order_detail->getItem($id);



        // $totalDiscount = $order['discount'];

        $arr = [];
        $totalQuantity = 0;
        $totalDiscountDetail = 0;
        foreach ($list_table as $key => $item) {
            $unitName = null;
            //Lấy đơn vị tính nếu là sản phẩm, sản phẩm quà tặng (object_type = product, product_gift)
            if ($item['object_type'] == 'product' || $item['object_type'] == 'product_gift') {
                $productInfo = $this->product->getItem($item['object_id']);
                if ($productInfo != null) {
                    $unitName = $productInfo['unitName'];
                }
            }
            $arr[] = [
                'order_detail_id' => $item['order_detail_id'],
                'object_id' => $item['object_id'],
                'note' => $item['note'],
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
            $totalQuantity += (int)$item['quantity'];
            $totalDiscountDetail += $item['discount'];
            $totalDiscount += $item['discount'];
        }
        //Lấy cấu hình in bill
        $configPrintBill = $this->configPrintBill->getItem(1);

        isset($order['branch_id']) ? $order['branch_id'] : '';
        //Lấy thông tin chi nhánh của đơn hàng
        $branchInfo = $this->branch->getItem($order['branch_id']);
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
        $template = 'admin::orders.print-not-receipt.content-print';

        switch ($configPrintBill->template) {
            case 'k58':
                $template = 'admin::orders.print-not-receipt.template-k58';
                break;
            case 'A5':
                $template = 'admin::orders.print-not-receipt.template--a5';
                break;
            case 'A5-landscape':
                $template = 'admin::orders.print-not-receipt.template--a5-landscape';
                break;
            case 'A4':
                $template = 'admin::orders.print-not-receipt.template-a4';
                break;
            case 'k80':
                $template = 'admin::orders.print-not-receipt.template-k80';
                break;
        }
        //Lấy số lần in bill của đơn hàng này
        $checkPrintBill = $this->printBillLog->checkPrintBillOrder($order['order_code']);
        $printTime = count($checkPrintBill);
        $printReply = '';
        if ($printTime > 0) {
            $printReply = __('(In lại)');
        }
        $maxId = $this->printBillLog->getBiggestId();
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
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, self::PLUS);
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, self::SUBTRACT);
        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        return view($template, [
            'order' => $order,
            'oder_detail' => $arr,
            'spaInfo' => $this->spaInfo->getInfoSpa(),
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
    //Lấy thông tin để in hóa đơn.
    // public function getInfoForPrintBill(Request $request)
    // {

    //     $id = $request->ptintorderid;
    //     //Lấy thông tin đơn hàng
    //     $order = $this->order->getItemDetail($id);

    //     $lstReceipt = $this->receipt->getReceiptOrderList($id);
    //     $list_receipt_detail = $this->receipt_detail->getItemPaymentByOrder($id);
    //     $amount_paid = 0;
    //     $amount_return = 0;
    //     $totalDiscount = 0;
    //     foreach ($lstReceipt as $key => $objReceipt) {
    //         $amount_paid += $objReceipt['amount_paid'];
    //         $amount_return += $objReceipt['amount_return'];
    //         $totalDiscount += $objReceipt['discount'];
    //     }
    //     //Lấy chi tiết đơn hàng
    //     $list_table = $this->order_detail->getItem($id);

    //     // $totalDiscount = $order['discount'];

    //     $arr = [];
    //     $totalQuantity = 0;
    //     $totalDiscountDetail = 0;
    //     foreach ($list_table as $key => $item) {
    //         $unitName = null;
    //         //Lấy đơn vị tính nếu là sản phẩm, sản phẩm quà tặng (object_type = product, product_gift)
    //         if ($item['object_type'] == 'product' || $item['object_type'] == 'product_gift') {
    //             $productInfo = $this->product->getItem($item['object_id']);
    //             if ($productInfo != null) {
    //                 $unitName = $productInfo['unitName'];
    //             }
    //         }
    //         $arr[] = [
    //             'order_detail_id' => $item['order_detail_id'],
    //             'object_id' => $item['object_id'],
    //             'object_name' => $item['object_name'],
    //             'object_type' => $item['object_type'],
    //             'price' => $item['price'],
    //             'quantity' => $item['quantity'],
    //             'discount' => $item['discount'],
    //             'amount' => $item['amount'],
    //             'voucher_code' => $item['voucher_code'],
    //             'object_code' => "XXXXXXXXXXXXXX" . substr($item['object_code'], -4),
    //             'unit_name' => $unitName
    //         ];
    //         $totalQuantity += (int)$item['quantity'];
    //         $totalDiscountDetail += $item['discount'];
    //         $totalDiscount += $item['discount'];
    //     }
    //     //Lấy cấu hình in bill
    //     $configPrintBill = $this->configPrintBill->getItem(1);

    //     isset($order['branch_id']) ? $order['branch_id'] : '';
    //     //Lấy thông tin chi nhánh của đơn hàng
    //     $branchInfo = $this->branch->getItem($order['branch_id']);
    //     if ($branchInfo != null) {
    //         // cắt hot line thành mảng
    //         $arrPhoneBranch = explode(",", $branchInfo['hot_line']);
    //         $strPhone = '';
    //         $temp = 0;
    //         $countPhoneBranch = count($arrPhoneBranch);
    //         if ($countPhoneBranch > 0) {
    //             foreach ($arrPhoneBranch as $value) {
    //                 if ($temp < $countPhoneBranch - 1) {
    //                     $strPhone = $strPhone . str_replace(' ', '', $value) . ' - ';
    //                 } else {
    //                     $strPhone = $strPhone . str_replace(' ', '', $value);
    //                 }
    //                 $temp++;
    //             }
    //         }
    //         $branchInfo['hot_line'] = $strPhone;
    //     } else {
    //         $branchInfo = [
    //             "branch_name" => "",
    //             "address" => "",
    //             "district_type" => "",
    //             "district_name" => "",
    //             "province_name" => "",
    //             "hot_line" => "",
    //         ];
    //     }
    //     //Template mặc định
    //     $template = 'admin::orders.print-not-receipt.content-print';

    //     switch ($configPrintBill->template) {
    //         case 'k58':
    //             $template = 'admin::orders.print-not-receipt.template-k58';
    //             break;
    //         case 'A5':
    //             $template = 'admin::orders.print-not-receipt.template--a5';
    //             break;
    //         case 'A5-landscape':
    //             $template = 'admin::orders.print-not-receipt.template--a5-landscape';
    //             break;
    //         case 'A4':
    //             $template = 'admin::orders.print-not-receipt.template-a4';
    //             break;
    //         case 'k80':
    //             $template = 'admin::orders.print-not-receipt.template-k80';
    //             break;
    //     }
    //     //Lấy số lần in bill của đơn hàng này
    //     $checkPrintBill = $this->printBillLog->checkPrintBillOrder($order['order_code']);
    //     $printTime = count($checkPrintBill);
    //     $printReply = '';
    //     if ($printTime > 0) {
    //         $printReply = __('(In lại)');
    //     }
    //     $maxId = $this->printBillLog->getBiggestId();
    //     // $convertNumberToWords = $this->help->convertNumberToWords($amount);

    //     $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
    //     $mConfig = new ConfigTable();
    //     //Lấy cấu hình 1 chi nhánh or liên chi nhánh
    //     $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];
    //     $branchId = null;
    //     if ($configBranch == 0) {
    //         //Lấy chi nhánh của nv đăng nhập
    //         $branchId = Auth()->user()->branch_id;
    //     }

    //     //Lấy tổng tiền thành viên cộng vào
    //     $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($order['customer_id'], $branchId, self::PLUS);

    //     //Lấy tổng tiền thành viên trừ ra
    //     $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($order['customer_id'], $branchId, self::SUBTRACT);
    //     $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);

    //     return view($template, [
    //         'order' => $order,
    //         'oder_detail' => $arr,
    //         'spaInfo' => $this->spaInfo->getInfoSpa(),
    //         'configPrintBill' => $configPrintBill,
    //         'id' => $id,
    //         'printTime' => $printReply,
    //         'STT' => $maxId != null ? $maxId['id'] : 1,
    //         'QrCode' => $order['order_code'],
    //         // 'convertNumberToWords' => $convertNumberToWords,
    //         'branchInfo' => $branchInfo,
    //         'order_detail' => $arr,
    //         'totalQuantity' => $totalQuantity,
    //         'totalDiscount' => $totalDiscount,
    //         'totalDiscountDetail' => $totalDiscountDetail,
    //         'amount_return' => $amount_return,
    //         'amount_paid' => $amount_paid,
    //         'accountMoney' => $accountMoney,
    //         'list_receipt_detail' => $list_receipt_detail,
    //         // 'amount' => $amount,
    //         // 'text_total_amount_paid' => $this->convert_number_to_words(floatval($order['total']))
    //     ]);
    // }

    /**
     * Modal hủy đơn hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrderAction(Request $request)
    {
        $view = \View::make('admin::orders.modal-cancel', [
            'order_id' => $request->order_id,
        ])->render();
        return response()->json([
            'view' => $view
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
                $item_order = $this->order->getItemDetail($param['order_id']);
                $data = [
                    'order_description' => strip_tags($param['order_description']),
                    'process_status' => 'ordercancle'
                ];
                //update trạng thái đơn hàng
                $this->order->edit($data, $param['order_id']);
                //Lấy thông tin chi tiết đơn hàng
                $order_detail = $this->order_detail->getItem($param['order_id']);
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

                    $order_commission = $this->order_commission->getItemByOrderDetail($item['order_detail_id']);
                    if (isset($order_commission)) {
                        //update order commission
                        $this->order_commission->edit(['status' => 'cancel'], $order_commission['id']);
                        if (isset($order_commission['refer_id'])) {
                            $get_customer_money = $this->customer_branch_money
                                ->getPriceBranch($order_commission['refer_id'], Auth::user()->branch_id);
                            $data_customer_money = [
                                'commission_money' => intval($get_customer_money['commission_money']) - intval($order_commission['refer_money'])
                            ];
                            //update commission money
                            $this->customer_branch_money->edit($data_customer_money, $order_commission['refer_id'], Auth::user()->branch_id);
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
                $this->receipt->edit(['status' => 'cancel'], $item_order['receipt_id']);
                //check customer debt
                $item_debt = $this->customer_debt->getCustomerDebtByOrder($param['order_id']);
                if (isset($item_debt)) {
                    //update status customer debt
                    $this->customer_debt->edit(['status' => 'cancel'], $item_debt['customer_debt_id']);
                    //check receipt by customer debt
                    $receipt_debt = $this->receipt->getReceipt($item_debt['customer_debt_id']);
                    if (count($receipt_debt) > 0) {
                        foreach ($receipt_debt as $item) {
                            $this->receipt->edit(['status' => 'cancel'], $item['receipt_id']);
                        }
                    }
                }
                //Trừ điểm khi hủy đơn hàng
                $history = $this->pointHistory->getPointOrder($param['order_id']);
                if ($history != null) {
                    $customer = $this->customer->getItem($history['customer_id']);
                    if ($customer != null) {
                        //Update điểm
                        $point = $customer['point'] - $history['point'];
                        $this->customer->edit(['point' => $point], $history['customer_id']);
                    }
                    $this->pointHistory->cancelOrder($param['order_id']);
                }
                //Xóa đơn giao hàng
                $this->order->removeDelivery($param['order_id']);
                //Trừ quota_user khi đơn hàng có promotion quà tặng
                $mOrderApp = app()->get(OrderAppRepoInterface::class);
                $mOrderApp->subtractQuotaUsePromotion($param['order_id']);
                // BEGIN: CỘNG LẠI KHO HÀNG
                $mInventoryInput = new InventoryInputTable();
                $mInventoryInputDetail = new InventoryInputDetailTable();
                $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
                $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
                $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
                // Lấy warehouse_id từ phiếu xuất kho theo order_id
                $infoInventoryOutput = $this->inventoryOutput->getInfoByOrderId($param['order_id'], 'retail');
                if ($infoInventoryOutput != null) {
                    $warehouseId = $infoInventoryOutput['warehouse_id'];
                    $inventoryOutputId = $infoInventoryOutput['inventory_output_id'];
                    // Lấy danh sách sản phẩm đơn hàng đã xuât
                    $listProduct = $this->inventoryOutputDetail->getListDetailByParentId($inventoryOutputId, $warehouseId);
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
                            $getQuantity = $this->productInventory->getQuantityByProdCodeAndWarehouseId($prod['product_code'], $warehouseId);
                            // Công vào kho
                            $data = [
                                'quantity' => $getQuantity['quantity'] + $prod['quantity']
                            ];
                            $this->productInventory->editQuantityByCode($data, $prod['product_code'], $warehouseId);
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

    public function loyaltyAction(Request $request)
    {
        //Tính điểm thưởng
        $result = $this->bookingApi->loyalty(['order_id' => $request->order_id]);
        return $result;
    }

    /**
     * View chuyển tiếp chi nhánh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyBranchAction(Request $request)
    {
        $optionBranch = $this->branch->getBranch();
        $info = $this->order->getItemDetail($request->order_id);

        $view = \View::make('admin::orders.apply-branch', [
            'order_id' => $request->order_id,
            'optionBranch' => $optionBranch,
            'item' => $info
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Chuyển tiếp chi nhánh
     *
     * @param ApplyBranchRequest $request
     * @return mixed
     */
    public function submitApplyBranchAction(ApplyBranchRequest $request)
    {
        $data = $this->order->applyBranch($request->all());

        return $data;
    }

    /**
     * Lưu log khi chỉnh sửa lịch hẹn
     *
     * @param $appointmentId
     * @param $status
     */
    public function insertLogEdit($appointmentId, $status)
    {
        $mAppointmentLog = new CustomerAppointmentLogTable();
        //Check trạng thái có thay đổi thì mới insert log

        $checkStatus = $this->customer_appointment->getItemEdit($appointmentId);

        if ($checkStatus['status'] != $status) {
            switch ($status) {
                case 'confirm':
                    //Check log new chưa có thì insert
                    $checkLogNew = $mAppointmentLog->getLogByStatus($appointmentId, 'new');
                    if ($checkLogNew == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'new',
                            'note' => __('Tạo lịch hẹn mới'),
                            'created_by' => Auth()->id()
                        ]);
                    }
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
                    //Check log new chưa có thì insert
                    $checkLogNew = $mAppointmentLog->getLogByStatus($appointmentId, 'new');
                    if ($checkLogNew == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'new',
                            'note' => __('Tạo lịch hẹn mới'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Check log confirm chưa có thì insert
                    $checkLogConfirm = $mAppointmentLog->getLogByStatus($appointmentId, 'confirm');
                    if ($checkLogConfirm == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'confirm',
                            'note' => __('Xác nhận lịch hẹn'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Insert log wait
                    $mAppointmentLog->add([
                        'customer_appointment_id' => $appointmentId,
                        'created_type' => 'backend',
                        'status' => 'wait',
                        'note' => __('Lịch hẹn được cập nhật đang phục vụ từ backend'),
                        'created_by' => Auth()->id()
                    ]);
                    break;
                case 'finish':
                    //Check log new chưa có thì insert
                    $checkLogNew = $mAppointmentLog->getLogByStatus($appointmentId, 'new');
                    if ($checkLogNew == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'new',
                            'note' => __('Tạo lịch hẹn mới'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Check log confirm chưa có thì insert
                    $checkLogConfirm = $mAppointmentLog->getLogByStatus($appointmentId, 'confirm');
                    if ($checkLogConfirm == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'confirm',
                            'note' => __('Xác nhận lịch hẹn'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Check log wait chưa có thì insert
                    $checkLogWait = $mAppointmentLog->getLogByStatus($appointmentId, 'wait');
                    if ($checkLogWait == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'wait',
                            'note' => __('Lịch hẹn được cập nhật đang phục vụ từ backend'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Insert log finish
                    $mAppointmentLog->add([
                        'customer_appointment_id' => $appointmentId,
                        'created_type' => 'backend',
                        'status' => 'finish',
                        'note' => 'Lịch hẹn được cập nhật hoàn tất từ backend',
                        'created_by' => Auth()->id()
                    ]);
                    break;
                case 'cancel':
                    //Insert log finish
                    $mAppointmentLog->add([
                        'customer_appointment_id' => $appointmentId,
                        'created_type' => 'backend',
                        'status' => 'cancel',
                        'note' => __('Lịch hẹn được hủy từ backend'),
                        'created_by' => Auth()->id()
                    ]);
                    break;
            }
        }
    }

    /**
     * Kiểm tra quà tặng khi chọn sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPromotionGiftAction(Request $request)
    {
        $arrParam = [];
        $arrGift = [];

        if (isset($request->arrParam) && count($request->arrParam) > 0) {
            foreach ($request->arrParam as $v) {
                if (in_array($v['objectType'], ['product', 'service', 'service_card'])) {
                    $objectCode = $v['objectCode'];
                    if (!array_key_exists($objectCode, $arrParam)) {
                        $arrParam[$objectCode] = array(
                            'object_type' => $v['objectType'],
                            'object_code' => $objectCode,
                            'quantity' => intval($v['quantity']),
                        );
                    } else {
                        $arrParam[$objectCode]['quantity'] = $arrParam[$objectCode]['quantity'] + $v['quantity'];
                    }
                }
            }
        }

        if (isset($arrParam) && count($arrParam) > 0) {
            foreach ($arrParam as $v) {
                $getPromotion = $this->order->getPromotionDetail(
                    $v['object_type'],
                    $v['object_code'],
                    $request->customer_id,
                    'live',
                    2,
                    $v['quantity']
                );

                if (count($getPromotion) > 0) {
                    $arrGift[] = $getPromotion;
                }
            }
        }

        return response()->json([
            'gift' => count($arrGift),
            'arr_gift' => $arrGift
        ]);
    }

    /**
     *  Export danh sách đơn hàng
     * @param Request $request
     * @return mixed
     */
    public function exportList(Request $request)
    {
        $params = $request->all();
        return $this->order->exportList($params);
    }

    /**
     * Lưu ảnh trước/sau khi sử dụng
     *
     * @param Request $request
     * @return mixed
     */
    public function saveImageAction(Request $request)
    {
        return $this->order->saveImage($request->all());
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
        $staff = $this->staff->getItem(Auth::id()); // Thông tin nhân viên
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
        $this->smsLog->getList('new_appointment', $appointmentId);
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
                            $priceBranch = $this->service_branch_price->getItemByBranchIdAndServiceId($branchId, $v);
                            // time_type = R, numberDay = 0: Config ko đặt lịch từ ngày đến ngày

                            //Lấy giá KM của dv
                            $getPrice = $this->order->getPromotionDetail(
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
     * Hiển thị popup thông tin địa chỉ
     */
    public function showPopupAddress(Request $request)
    {
        $data = $this->order->showPopupAddress($request->all());
        return response()->json($data);
    }

    /**
     * Hiển thị popup thêm địa chỉ
     */
    public function showPopupAddAddress(Request $request)
    {
        $data = $this->order->showPopupAddAddress($request->all());
        return response()->json($data);
    }

    /**
     * Thay đổi tỉnh thành phố
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeProvince(Request $request)
    {
        $data = $this->order->changeProvince($request->all());
        return response()->json($data);
    }

    /**
     * Thay đổi Quận huyện
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeDistrict(Request $request)
    {
        $data = $this->order->changeDistrict($request->all());
        return response()->json($data);
    }

    /**
     * Tạo địa chỉ nhận hàng
     * @param SubmitAddressRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddress(SubmitAddressRequest $request)
    {
        $data = $this->order->submitAddress($request->all());
        return response()->json($data);
    }

    /**
     * Xoá địa chỉ nhận hàng
     * @param SubmitAddressRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAddressCustomer(Request $request)
    {
        $data = $this->order->removeAddressCustomer($request->all());
        return response()->json($data);
    }

    /**
     * Thay đổi thông tin địa chỉ giao hàng
     * @param Request $request
     */
    public function changeInfoAddress(Request $request)
    {
        $data = $this->order->changeInfoAddress($request->all());
        return response()->json($data);
    }

    public function _paymentVnPay($orderCode, $amount, $userId, $branchId, $platform, $paramsExtra)
    {
        //Call api thanh toán vn pay
        return $this->paymentVnPay([
            'method' => 'vnpay',
            'order_id' => $orderCode,
            'amount' => $amount,
            'user_id' => $userId,
            'branch_id' => $branchId,
            'platform' => $platform,
            'params_extra' => $paramsExtra
        ]);
    }

    public function paymentVnPay(array $data = [])
    {
        $oClient = new Client();

        $mConfig = app()->get(ConfigTable::class);
        //Lấy thông tin cấu hình key + secret
        $key = $mConfig->getInfoByKey('oncall_key')['value'];
        $secret = $mConfig->getInfoByKey('oncall_secret')['value'];

        //Gọi api thực hiện cuộc gọi
        $response = $oClient->request('POST', env('DOMAIN_ONCALL') . '/payment/pay', [
            'headers' => [
                'tenant' => session()->get('brand_code'),
                'key' => $key,
                'secret' => $secret
            ],
            'json' => $data
        ]);

        return json_decode($response->getBody());
    }


    /**
     * Kiểm tra số serial trong kho trong đơn hàng
     * @param Request $request
     */
    public function checkSerialEnter(Request $request)
    {
        $data = $this->order->checkSerialEnter($request->all());
        return response()->json($data);
    }

    /**
     * Xoá số serial
     */
    public function removeSerial(Request $request)
    {
        $data = $this->order->removeSerial($request->all());
        return response()->json($data);
    }

    /**
     * Hiển thị popup serial
     */
    public function showPopupSerial(Request $request)
    {
        $data = $this->order->showPopupSerial($request->all());
        return response()->json($data);
    }

    /**
     * Search serial
     */
    public function searchSerial(Request $request)
    {
        $data = $this->order->searchSerial($request->all());
        return response()->json($data);
    }

    /**
     * lấy danh sách serial theo sản phẩm
     */
    public function getListSerial(Request $request)
    {
        $data = $this->order->getListSerial($request->all());
        return response()->json($data);
    }

    /**
     * Function đọc tiền tiếng việt
     *
     * @param $number
     * @return string
     */
    function convert_number_to_words($number)
    {
        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = __('âm') . ' ';
        $decimal     = ' ' . __('phẩy') . ' ';
        $dictionary  = array(
            0                   => __('không'),
            1                   => __('một'),
            2                   => __('hai'),
            3                   => __('ba'),
            4                   => __('bốn'),
            5                   => __('năm'),
            6                   => __('sáu'),
            7                   => __('bảy'),
            8                   => __('tám'),
            9                   => __('chín'),
            10                  => __('mười'),
            11                  => __('mười một'),
            12                  => __('mười hai'),
            13                  => __('mười ba'),
            14                  => __('mười bốn'),
            15                  => __('mười năm'),
            16                  => __('mười sáu'),
            17                  => __('mười bảy'),
            18                  => __('mười tám'),
            19                  => __('mười chín'),
            20                  => __('hai mươi'),
            30                  => __('ba mươi'),
            40                  => __('bốn mươi'),
            50                  => __('năm mươi'),
            60                  => __('sáu mươi'),
            70                  => __('bảy mươi'),
            80                  => __('tám mươi'),
            90                  => __('chín mươi'),
            100                 => __('trăm'),
            1000                => __('nghìn'),
            1000000             => __('triệu'),
            1000000000          => __('tỷ'),
            1000000000000       => __('nghìn tỷ'),
            1000000000000000    => __('nghìn triệu triệu'),
            1000000000000000000 => __('tỷ tỷ')
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
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
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
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
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

    /**
     * Chọn sản phẩm/ dịch vụ/ thẻ dịch vụ
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseTypeAction(Request $request)
    {
        $data = $this->order->chooseType($request->all());

        return response()->json($data);
    }

    /**
     * Show popup sản phẩm/dịch vụ kèm theo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupAttachAction(Request $request)
    {
        //Lấy sản phẩm/ dịch vụ kèm theo
        $getAttach = $this->order->getDataAttach($request->all());
        $attachChose = [];

        if (isset($request->attachChoose) && count($request->attachChoose) > 0) {
            foreach ($request->attachChoose as $v) {
                $attachChose[$v['object_id']] = [
                    'quantity' => $v['quantity']
                ];
            }
        }
        $lstAttach = [];
        foreach ($getAttach as $value => $item) {
            $getPrice = $this->order->getPromotionDetail('service', $item['object_code'], $request->customer_id, 'live', 1);

            if ($getPrice != null && $getPrice > $item['price']) {
                $getPrice = $item['price'];
            }
            $item['promotion_price'] = $getPrice;
            array_push($lstAttach, $item);
        }
        $html = \View::make('admin::orders.pop.pop-attach', [
            'list' => $lstAttach,
            'stt' => $request->stt,
            'attachChoose' => $attachChose,
            'note' => $request->note,
            'object_name' => $request->object_name,
            'object_price' => $request->object_price,
        ])->render();

        return response()->json([
            'html' => $html,
        ]);
    }

    /**
     * Danh sách đơn hàng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function listOrderCustomerAction(Request $request)
    {
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

        $data = $this->order->list($filter);

        return view('admin::orders.list-order-customer', [
            'LIST' => $data['list'],
            'receipt' => $data['receipt'],
            'page' => $filter['page']
        ]);
    }
}
