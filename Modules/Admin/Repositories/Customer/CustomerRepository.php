<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/2/2018
 * Time: 4:06 PM
 */

namespace Modules\Admin\Repositories\Customer;


use App\Jobs\CheckMailJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Api\BookingApi;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CommissionLogTable;
use Modules\Admin\Models\ConfigPrintBillTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\CustomerAccountTable;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\CustomerBranchMoneyTable;
use Modules\Admin\Models\CustomerBranchTable;
use Modules\Admin\Models\CustomerCustomDefineTable;
use Modules\Admin\Models\CustomerDebtTable;
use Modules\Admin\Models\CustomerFileTable;
use Modules\Admin\Models\CustomerGroupDefineDetailTable;
use Modules\Admin\Models\CustomerGroupDetailTable;
use Modules\Admin\Models\CustomerInfoTypeTable;
use Modules\Admin\Models\CustomerNoteTable;
use Modules\Admin\Models\CustomerPersonContactTable;
use Modules\Admin\Models\CustomerServiceCardTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\CustomerContactTable;
use Modules\Admin\Models\DeliveryCustomerAddressTable;
use Modules\Admin\Models\OrderCommissionTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\OrderLogTable;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\PaymentMethodTable;
use Modules\Admin\Models\PointHistoryTable;
use Modules\Admin\Models\ReceiptCustomerTable;
use Modules\Admin\Models\ReceiptDebtMapTable;
use Modules\Admin\Models\ReceiptDetailTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\SpaInfoTable;
use Modules\Admin\Models\StaffTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\Admin\Models\SupplierTable;
use Modules\Admin\Models\DealTable;
use Modules\Admin\Models\CustomerCommentTable;
use Modules\Admin\Repositories\CustomerGroupFilter\CustomerGroupFilterRepository;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    protected $customer;
    protected $customerGroupFilter;
    protected $timestamps = true;

    /**
     * CustomerRepository constructor.
     * @param CustomerTable $customers
     * @param CustomerGroupFilterRepository $customerGroupFilter
     */
    public function __construct(CustomerTable $customers, CustomerGroupFilterRepository $customerGroupFilter)
    {
        $this->customer = $customers;
        $this->customerGroupFilter = $customerGroupFilter;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->customer->getList($filters);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->customer->add($data);
    }


    /**
     * @param $data
     * @return mixed
     */
    public function getCustomerSearch($data)
    {
        return $this->customer->getCustomerSearch($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->customer->getItem($id);
    }

    public function getItemLog($id)
    {
        return $this->customer->getItemLog($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemRefer($id)
    {
        return $this->customer->getItemRefer($id);
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->customer->edit($data, $id);
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function remove($id)
    {
        //Xoá tài khoản KH
        $this->customer->remove($id);
        //Xoá account của khách hàng
        $mCustomerAccount = new CustomerAccountTable();
        $mCustomerAccount->removeAccount($id);
    }

    /**
     * @return array
     */
    public function getCustomerOption()
    {
        $array = array();
        foreach ($this->customer->getCustomerOption() as $item) {
            $array[$item['customer_id']] = $item['full_name'] . ' - ' . $item['phone1'];
        }
        return $array;
    }

    public function getCustomerOptionOptimize($listCustomerId)
    {
        $array = array();
        foreach ($this->customer->getCustomerOptionOptimize($listCustomerId) as $item) {
            $array[$item['customer_id']] = $item['full_name'] . ' - ' . $item['phone1'];
        }
        return $array;
    }

    /**
     * @param $phone
     * @param $id
     * @return mixed
     */
    public function testPhone($phone, $id)
    {
        return $this->customer->testPhone($phone, $id);
    }

    /**
     * @param $phone
     * @return mixed
     */
    public function searchPhone($phone)
    {
        // TODO: Implement searchPhone() method.
        return $this->customer->searchPhone($phone);
    }

    /**
     * @param $phone
     * @return mixed
     */
    public function getCusPhone($phone)
    {
        // TODO: Implement getCusPhone() method.
        return $this->customer->getCusPhone($phone);
    }

    public function getCustomerIdName()
    {
        $array = array();
        foreach ($this->customer->getCustomerOption() as $item) {
            $array[$item['customer_id']] = $item['full_name'];
        }
        return $array;
    }

    /**
     * @return mixed
     */
    public function totalCustomer($yearNow)
    {

        return $this->customer->totalCustomer($yearNow);
    }

    /**
     * @param $yearNow
     * @return mixed
     */
    public function totalCustomerNow($yearNow)
    {
        return $this->customer->totalCustomerNow($yearNow);
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function filterCustomerYearBranch($year, $branch)
    {
        return $this->customer->filterCustomerYearBranch($year, $branch);
    }

    /**
     * @param $year
     * @param $branch
     */
    public function filterNowCustomerBranch($year, $branch)
    {
        return $this->customer->filterNowCustomerBranch($year, $branch);
    }

    /**
     * @param $time
     * @param $branch
     * @return mixed
     */
    public function filterTimeToTime($time, $branch)
    {
        return $this->customer->filterTimeToTime($time, $branch);
    }

    /**
     * @param $time
     * @param $branch
     * @return mixed|void
     */
    public function filterTimeNow($time, $branch)
    {
        return $this->customer->filterTimeNow($time, $branch);
    }

    public function searchCustomerEmail($data, $birthday, $gender, $branch, $arrPhone = [], $arrEmail = [])
    {
        // TODO: Implement searchCustomerEmail() method.
        return $this->customer->searchCustomerEmail($data, $birthday, $gender, $branch);
    }

    public function searchCustomerPhoneEmail($data, $birthday, $gender, $branch, $arrPhone = [], $arrEmail = [])
    {
        // TODO: Implement searchCustomerEmail() method.
        return $this->customer->searchCustomerPhoneEmail($data, $birthday, $gender, $branch, $arrPhone, $arrEmail);
    }

    //Lấy danh sách khách hàng có ngày sinh nhật là hôm nay.
    public function getBirthdays()
    {
        return $this->customer->getBirthdays();
    }

    public function searchDashboard($keyword)
    {
        return $this->customer->searchDashboard($keyword);
    }

    /**
     * @param $id_branch
     * @param $time
     * @param $top
     * @return mixed
     */
    public function reportCustomerDebt($id_branch, $time, $top)
    {
        return $this->customer->reportCustomerDebt($id_branch, $time, $top);
    }

    public function getAllCustomer($filter = [])
    {
        return $this->customer->getAllCustomer($filter);
    }

    public function getCustomerAndDefaultContact($id)
    {
        $mCustomerContact = new CustomerContactTable();
        $mCustomerDebt = app()->get(CustomerDebtTable::class);

        //Lấy thông tin KH
        $getItem = $this->customer->getItem($id);
        //Lấy công nợ của KH
        $amountDebt = $mCustomerDebt->getItemDebt($id);

        $debt = 0;
        if (count($amountDebt) > 0) {
            foreach ($amountDebt as $item) {
                $debt += $item['amount'] - $item['amount_paid'];
            }
        }
        $getItem['debt'] = $debt;

        //Lấy địa chỉ mặc định
        $contact = $mCustomerContact->getContactDefault($id);
        $data = [
            'getItem' => $getItem,
            'contact' => $contact,
        ];
        return $data;
    }

    /**
     * Sử dụng thẻ liệu trình
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function usingCard($input)
    {
        DB::beginTransaction();
        try {
            $mCustomerCard = app()->get(CustomerServiceCardTable::class);
            //Lấy thông tin thẻ liệu trình (customer_service_card)
            $infoCard = $mCustomerCard->getCardByCode($input['card_code']);
            //Validate thẻ liệu trình
            $validate = $this->validateCard($infoCard);
            if ($validate['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => $validate['message']
                ]);
            }
            //Tạo đơn hàng
            $mOrder = app()->get(OrderTable::class);
            $idOrder = $mOrder->add([
                'customer_id' => $infoCard['customer_id'],
                'total' => 0,
                'discount' => 0,
                'branch_id' => Auth()->user()->branch_id,
                'amount' => 0,
                'process_status' => 'paysuccess',
                'discount_member' => 0,
                'order_source_id' => 1, //Đơn hàng trực tiếp
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);
            //Cập nhật mã đơn hàng
            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $idOrder);
            $mOrder->edit([
                'order_code' => $orderCode
            ], $idOrder);
            //Tạo chi tiết đơn hàng
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mOrderDetail->add([
                'order_id' => $idOrder,
                'object_type' => 'member_card', //Thẻ liệu trình
                'object_id' => $infoCard['customer_service_card_id'],
                'object_name' => $infoCard['card_name'],
                'object_code' => $infoCard['card_code'],
                'price' => 0,
                'quantity' => 1,
                'discount' => 0,
                'amount' => 0,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),

            ]);
            //Trừ số lần sử dụng thẻ liệu trình
            $mCustomerCard->edit([
                'count_using' => $infoCard['count_using'] + 1
            ], $infoCard['customer_service_card_id']);
            //Insert order log đơn hàng mới, hoàn tất
            $mOrderLog = app()->get(OrderLogTable::class);
            $mOrderLog->insert([
                [
                    'order_id' => $idOrder,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note_vi' => 'Đặt hàng thành công',
                    'note_en' => 'Order success',
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'order_id' => $idOrder,
                    'created_type' => 'backend',
                    'status' => 'ordercomplete',
                    'note_vi' => 'Hoàn tất',
                    'note_en' => 'Order completed',
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            ]);

            if ($infoCard['customer_id'] != 1) {
                //Insert sms log, email log đặt hàng thành công
                //                $mSmsLog = app()->get(SmsLogRepositoryInterface::class);
                //                $mSmsLog->getList('order_success', $idOrder);
                //                CheckMailJob::dispatch('is_event', 'order_success', $idOrder);$idOrder

                //Insert sms, email log thanh toán thành công
                //                CheckMailJob::dispatch('is_event', 'paysuccess', $idOrder);
                //                $mSmsLog->getList('paysuccess', $idOrder);
            }
            //Tạo phiếu thanh toán
            $mReceipt = app()->get(ReceiptTable::class);
            $idReceipt = $mReceipt->add([
                'customer_id' => $infoCard['customer_id'],
                'staff_id' => Auth()->id(),
                'object_id' => $idOrder,
                'object_type' => 'order',
                'order_id' => $idOrder,
                'total_money' => 0,
                'voucher_code' => '',
                'status' => 'paid',
                'is_discount' => 1,
                'amount' => 0,
                'amount_paid' => 0,
                'amount_return' => 0,
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => $orderCode, // order code
                'object_accounting_id' => $idOrder, // order id
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),

            ]);
            //Update mã thanh toán
            $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $idReceipt);
            $mReceipt->edit([
                'receipt_code' => $receiptCode
            ], $idReceipt);
            //Tạo chi tiết phiếu thanh toán
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            $mReceiptDetail->add([
                'receipt_id' => $idReceipt,
                'cashier_id' => Auth()->id(),
                'payment_method_code' => 'MEMBER_CARD',
                'card_code' => $infoCard['card_code'],
                'amount' => 0,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            DB::commit();

            $mNoti = new SendNotificationApi();
            // Thông báo tạo đơn hàng thành công
            //                $mNoti->sendNotification([
            //                    'key' => 'order_status_W',
            //                    'customer_id' => $infoCard['customer_id'],
            //                    'object_id' => $idOrder
            //                ]);
            // Thông báo đơn hàng thanh toán thành công
            $mNoti->sendNotification([
                'key' => 'order_status_S',
                'customer_id' => $infoCard['customer_id'],
                'object_id' => $idOrder
            ]);
            //Cộng điểm khi mua hàng trực tiếp
            $mPlusPoint = new LoyaltyApi();
            $mPlusPoint->plusPointEvent([
                'customer_id' => $infoCard['customer_id'],
                'rule_code' => 'order_direct',
                'object_id' => $idOrder
            ]);
            //Gửi thông báo nhân viên
            $mNoti->sendStaffNotification([
                'key' => 'order_status_W',
                'customer_id' => $infoCard['customer_id'],
                'object_id' => $idOrder
            ]);

            return response()->json([
                'error' => false,
                'message' => __('Sử dụng thẻ liệu trình thành công'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Sử dụng thẻ liệu trình thất bại'),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ]);
        }
    }

    /**
     * Kiểm tra thẻ được sử dụng không
     *
     * @param $infoCard
     * @return bool
     */
    private function validateCard($infoCard)
    {
        //Kiểm tra thẻ đã kích hoạt chưa
        if ($infoCard['is_actived'] == 0) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình chưa được kích hoạt')
            ];
        }
        //Kiểm tra hạn sử dụng
        $dataNow = Carbon::now()->format('Y-m-d');
        $dateExpired = Carbon::parse($infoCard['expired_date'])->format('Y-m-d');
        if ($infoCard['expired_date'] != null && $dataNow > $dateExpired) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình hết hạn sử dụng')
            ];
        }
        //Kiểm tra số lần sử dụng
        if ($infoCard['number_using'] != 0 && $infoCard['number_using'] <= $infoCard['count_using']) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình hết số lần sử dụng')
            ];
        }

        return [
            'error' => false
        ];
    }

    /**
     * Thêm nhanh loại thông tin
     *
     * @param $input
     * @return mixed|void
     */
    public function addInfoType($input)
    {
        $mInfoType = app()->get(CustomerInfoTypeTable::class);

        //Thêm loại thông tin kèm theo
        $idInfoType = $mInfoType->add([
            "customer_info_type_name" => $input['info_name'],
            "created_by" => Auth()->id(),
            "updated_by" => Auth()->id()
        ]);

        return response()->json([
            'info_type_id' => $idInfoType
        ]);
    }

    /**
     * Danh sách khách hàng thuộc nhóm KH (tự định nghĩa hoặc tự động)
     *
     * @param $filterTypeGroup
     * @param $customerGroupFilter
     * @return mixed
     */
    public function searchCustomerGroupFilter($filterTypeGroup, $customerGroupFilter)
    {
        $mCustomerGroupDefineDetail = new CustomerGroupDefineDetailTable();
        $inArr = [];
        // function lấy ds các điều kiện, filter dựa
        if ($filterTypeGroup == 'user_define') {
            $data = $mCustomerGroupDefineDetail->getCustomerIdInGroup($customerGroupFilter);
            foreach ($data as $item) {
                $inArr[] = $item['customer_id'];
            }
        } elseif ($filterTypeGroup == 'auto') {
            $inArr = $this->customerGroupFilter->getCustomerInGroupAuto($customerGroupFilter);
        }
        $data = $this->customer->getCustomerByArrCustomerId($inArr);
        return $data;
    }

    /**
     * Show modal thêm chi nhánh được xem
     *
     * @param $input
     * @return mixed|void
     */
    public function modalCustomerBranch($input)
    {
        $mBranch = new BranchTable();
        $mCustomerBranch = new CustomerBranchTable();

        //Lấy option chi nhánh
        $optionBranch = $mBranch->getBranchOption();
        //Lấy tất cả chi nhánh của KH
        $getBranchCustomer = $mCustomerBranch->getAllBranchCustomer($input['customer_id']);

        $arrBranchCustomer = [];

        if (count($getBranchCustomer) > 0) {
            foreach ($getBranchCustomer as $v) {
                $arrBranchCustomer[] = $v['branch_id'];
            }
        }

        $view = \View::make('admin::customer.pop.modal-customer-branch', [
            'customer_id' => $input['customer_id'],
            'optionBranch' => $optionBranch,
            'arrBranchCustomer' => $arrBranchCustomer
        ])->render();

        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Thêm chi nhánh được xem
     *
     * @param $input
     * @return array|mixed
     */
    public function saveCustomerBranch($input)
    {
        try {
            $mCustomerBranch = new CustomerBranchTable();

            //Xoá chi nhánh của khách hàng
            $mCustomerBranch->removeBranchCustomer($input['customer_id']);

            $dataBranch = [];

            if (isset($input['branch_id']) && count($input['branch_id']) > 0) {
                foreach ($input['branch_id'] as $v) {
                    $dataBranch[] = [
                        'customer_id' => $input['customer_id'],
                        'branch_id' => $v,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            $mCustomerBranch->insert($dataBranch);

            return [
                'error' => false,
                'message' => __('Lưu chi nhánh được xem thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lưu chi nhánh được xem thất bại')
            ];
        }
    }

    /**
     * Cập nhật thông tin khách hàng
     * @param $input
     * @return mixed|void
     */
    public function customerUpdateWard($input)
    {
        try {

            $this->customer->edit(
                [
                    'province_id' => $input['province_id'],
                    'district_id' => $input['district_id'],
                    'ward_id' => $input['ward_id'],
                    'address' => strip_tags($input['address']),
                ],
                $input['id']
            );

            //            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);

            //            $getDetailCustomerAddress = $mDeliveryCustomerAddress->getDetailByCustomer($input['id']);
            $getDetailCustomerAddress = $mCustomerContact->getDetailByCustomer($input['id']);

            if ($getDetailCustomerAddress == null) {
                $detailCustomer = $this->customer->getItem($input['id']);
                $idAddress = $mCustomerContact->add([
                    'customer_id' => $input['id'],
                    'contact_name' => $detailCustomer['full_name'],
                    'contact_phone' => $detailCustomer['phone1'],
                    'province_id' => $detailCustomer['province_id'],
                    'district_id' => $detailCustomer['district_id'],
                    'ward_id' => $detailCustomer['ward_id'],
                    'full_address' => $detailCustomer['address'],
                    'type_address' => $detailCustomer['customer_type'] == 'personal' ? 'home' : 'office',
                    'address_default' => 1,
                    'created_at' => \Carbon\Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'updated_by' => Auth::id()
                ]);

                $mCustomerContact->updateAddress([
                    'customer_contact_code' => 'CC_' . date('dmY') . sprintf("%02d", $idAddress)
                ], $idAddress);
            }


            return [
                'error' => false,
                'message' => __('Cập nhật thông tin thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thông tin thất bại')
            ];
        }
    }

    /**
     * Lấy DS phiếu thu của KH
     *
     * @param $input
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getReceiptCustomer($input)
    {
        $mReceipt = app()->get(ReceiptCustomerTable::class);

        //Lấy lịch sử thanh toán của KH
        $list = $mReceipt->getList($input);

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

        return $list;
    }

    /**
     * Load tab trong chi tiết KH
     *
     * @param $input
     * @return mixed|void
     */
    public function loadTabDetail($input)
    {
        $mConfig = app()->get(ConfigTable::class);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        switch ($input['tab_view']) {
            case 'appointment':
                $mAppointment = app()->get(CustomerAppointmentTable::class);
                $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);

                //Lấy ngày đã hẹn của KH
                $date = $mAppointment->detailDayCustomer($input['customer_id']);

                $dayAppointment = [];
                $data = [];

                if (count($date) > 0) {
                    foreach ($date as $v) {
                        $dayAppointment[] = $v['date'];
                        //Lấy thông tin LH theo ngày
                        $getAppointmentDay = $mAppointment->detailCustomer($v['date'], $input['customer_id'])->toArray();

                        if (count($getAppointmentDay) > 0) {
                            foreach ($getAppointmentDay as $v1) {
                                //Lấy thông tin chi tiết lịch hẹn
                                $v1['service'] = $mAppointmentDetail->getItem($v1['customer_appointment_id']);
                                $data[] = $v1;
                            }
                        }
                    }
                }

                return [
                    "data" => $data,
                    "tab_view" => $input['tab_view'],
                    "customer_id" => $input['customer_id']
                ];

                break;
            case 'service_card':
                $mCustomerCard = app()->get(CustomerServiceCardTable::class);

                $data = [];
                //Lấy thẻ dịch vụ của khách hàng
                $listCard = $mCustomerCard->memberCardDetail($input['customer_id'], $branchId);

                if (count($listCard) > 0) {
                    foreach ($listCard as $v) {
                        $isUse = 0;
                        //Kiểm tra thẻ được sử dụng không
                        $checkUse = $this->checkIsUse($v);

                        if ($checkUse == true) {
                            $isUse = 1;
                        }

                        $v['is_use'] = $isUse;
                        $data[] = $v;
                    }
                }

                return [
                    "data" => $data,
                    "tab_view" => $input['tab_view'],
                    "customer_id" => $input['customer_id']
                ];

                break;
            case 'order_commission':
                $mOrderCommission = app()->get(OrderCommissionTable::class);

                //Lấy lịch sử nhận hoa hồng của KH
                $listCommission = $mOrderCommission->getCommissionByCustomer($input['customer_id']);

                return [
                    "data" => $listCommission,
                    "tab_view" => $input['tab_view'],
                    "customer_id" => $input['customer_id']
                ];

                break;
            case 'commission_log':
                $mCommissionLog = app()->get(CommissionLogTable::class);

                //DS quy đổi
                $getLog = $mCommissionLog->getLogByCustomer($input['customer_id']);

                return [
                    "data" => $getLog,
                    "tab_view" => $input['tab_view'],
                    "customer_id" => $input['customer_id']
                ];

                break;
            case 'attach_info':
                $mCustomerDefine = new CustomerCustomDefineTable();
                $mCustomer = app()->get(CustomerTable::class);
                //Lấy cấu hình thông tin kèm theo của KH
                $customDefine = $mCustomerDefine->getDefine();
                //Lấy thông tin KH
                $info = $mCustomer->getItem($input['customer_id']);

                return [
                    "data" => $customDefine,
                    "tab_view" => $input['tab_view'],
                    "customer_id" => $input['customer_id'],
                    "item" => $info
                ];
                break;
            case 'info':
            case 'warranty_card':
            case 'customer_care':
                $mCustomer = app()->get(CustomerTable::class);

                //Lấy thông tin KH
                $info = $mCustomer->getItem($input['customer_id']);

                $info['full_address'] = $info['address'];

                if ($info['ward_name'] != null) {
                    $info['full_address'] .= ', ' . $info['ward_type'] . ' ' . $info['ward_name'];
                }

                if ($info['district_name'] != null) {
                    $info['full_address'] .= ', ' . $info['district_type'] . ' ' . $info['district_name'];
                }

                if ($info['province_name'] != null) {
                    $info['full_address'] .= ', ' . $info['province_type'] . ' ' . $info['province_name'];
                }

                return [
                    "info" => $info,
                    "tab_view" => $input['tab_view'],
                    "customer_id" => $input['customer_id']
                ];
                break;
          
            default:
                return [
                    "tab_view" => $input['tab_view'],
                    "customer_id" => $input['customer_id']
                ];
                break;
        }
    }

    /**
     * Kiểm tra thẻ được sử dụng không
     *
     * @param $infoCard
     * @return bool
     */
    private function checkIsUse($infoCard)
    {
        //Kiểm tra thẻ đã kích hoạt chưa
        //        if ($infoCard['is_actived'] == 0) {
        //            return false;
        //        }
        //Kiểm tra hạn sử dụng
        $dataNow = Carbon::now()->format('Y-m-d');
        $dateExpired = Carbon::parse($infoCard['expired_date'])->format('Y-m-d');
        if ($infoCard['expired_date'] != null && $dataNow > $dateExpired) {
            return false;
        }
        //Kiểm tra số lần sử dụng
        if ($infoCard['number_using'] != 0 && $infoCard['number_using'] <= $infoCard['count_using']) {
            return false;
        }

        return true;
    }

    /**
     * DS tích luỹ điểm
     *
     * @param $input
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listLoyalty($input)
    {
        $mPointHistory = app()->get(PointHistoryTable::class);

        //DS tích luỹ
        return $mPointHistory->getList($input);
    }

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed|void
     */
    public function addComment($data)
    {
        try {
            $mTicketComment = new CustomerCommentTable();
            $comment = [
                'message' => $data['description'],
                'customer_id' => $data['customer_id'],
                'customer_parent_comment_id' => isset($data['customer_comment_id']) ? $data['customer_comment_id'] : null,
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            //Thêm bình luận ticket
            $idComment = $mTicketComment->createdComment($comment);

            $detailComment = $mTicketComment->getDetail($idComment);

            //Gửi notify bình luận ticket
            // $listCustomer = $this->getListStaff($data['ticket_id']);

            // $mNoti = new SendNotificationApi();

            // foreach ($listCustomer as $item) {
            //     if ($item != Auth()->id()) {
            //         $mNoti->sendStaffNotification([
            //             'key' => 'ticket_finish_processor',
            //             'customer_id' => Auth()->id(),
            //             'object_id' => $data['ticket_id']
            //         ]);
            //     }
            // }

            $view = view('manager-work::managerWork.append.append-message', ['detail' => $detailComment, 'data' => $data])->render();

            // tạo lịch sử
            //   $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã bình luận ' );
            //   $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' approved the material requisition form ');
            //   $mMTicketHistory($note,$note_en, $data['ticket_id']);

            return [
                'error' => false,
                'message' => __('Thêm bình luận thành công'),
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm bình luận thất bại') . $e->getMessage()
            ];
        }
    }

    /**
     * hiển thị form comment
     * @param $data
     * @return mixed|void
     */
    public function showFormComment($data)
    {
        try {

            $view = $view = view('admin::customer.append.append-form-chat', ['customer_comment_id' => $data['customer_comment_id']])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị form trả lời thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách comment
     * @param $id
     * @return mixed|void
     */
    public function getListComment($id)
    {
        $mManageComment = new CustomerCommentTable();
        $listComment = $mManageComment->getListCommentCustomer($id);
        foreach ($listComment as $key => $item) {
            $listComment[$key]['child_comment'] = $mManageComment->getListCommentCustomer($id, $item['customer_comment_id']);
        }
        return $listComment;
    }

    /**
     * Lấy dữ liệu in bill công nợ
     *
     * @param $input
     * @return mixed|void
     */
    public function getDataPrintBillDebt($input)
    {
        $mCustomerDebt = app()->get(CustomerDebtTable::class);
        $mConfigPrintBill = app()->get(ConfigPrintBillTable::class);
        $mBranch = app()->get(BranchTable::class);
        $mSpaInfo = app()->get(SpaInfoTable::class);
        $mReceipt = app()->get(ReceiptCustomerTable::class);
        //Lấy cấu hình in bill
        $configPrintBill = $mConfigPrintBill->getItem(1);

        //Lấy thông tin khách hàng
        $infoCustomer = $this->customer->getInfoById($input['customer_id']);
        //Lấy lịch sử công nợ của khách hàng
        $listDebt = $mCustomerDebt->getDebtByCustomer($input['customer_id']);
        //Lấy thông tin chi nhánh của nhân viên
        $infoBranch = $mBranch->getItem(Auth()->user()->branch_id);
        //Lấy thông tin spa info
        $infoSpa = $mSpaInfo->getInfoSpa();
        //Lấy các lần thanh toán của KH
        $listReceipt = $mReceipt->getReceiptByCustomer($input['customer_id']);

        $totalDebt = 0;
        $totalDebtPaid = 0;

        if (count($listDebt) > 0) {
            foreach ($listDebt as $v) {
                $totalDebt += $v['amount'];
                $totalDebtPaid += $v['amount_paid'];
            }
        }

        if (count($listReceipt) > 0) {
            $mCustomer = app()->get(CustomerTable::class);
            $mSupplier = app()->get(SupplierTable::class);
            $mStaff = app()->get(StaffTable::class);

            foreach ($listReceipt as $v) {
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
            'configPrintBill' => $configPrintBill,
            'infoCustomer' => $infoCustomer,
            'listDebt' => $listDebt,
            'branchInfo' => $infoBranch,
            'spaInfo' => $infoSpa,
            'totalDebt' => $totalDebt,
            'totalDebtPaid' => $totalDebtPaid,
            'listReceipt' => $listReceipt
        ];
    }

    /**
     * Lấy dữ liệu pop thanh toán nhanh
     *
     * @param $input
     * @return mixed|void
     */
    public function getDataQuickReceiptDebt($input)
    {
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mCustomerBranchMoney = app()->get(CustomerBranchMoneyTable::class);

        //Lấy phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOptionNotVnPay();
        //Check tài khoản thành viên theo chi nhánh
        $branch_money = $mCustomerBranchMoney->getPriceBranch($input['customer_id'], Auth::user()->branch_id);

        return [
            'totalDebt' => $input['totalDebt'],
            'branchMoney' => $branch_money,
            'optionPaymentMethod' => $optionPaymentMethod,
            'customerId' => $input['customer_id']
        ];
    }

    /**
     * Submit thanh toán nhanh công nợ (Cách xử lý cũ lưu đợt thu)
     *
     * @param $input
     * @return array
     */
//    public function submitQuickReceiptDebt($input)
//    {
//        DB::beginTransaction();
//
//        try {
//            $totalDebt = floatval(str_replace(',', '', $input['total_debt'])); //Tổng tiền khách nợ
//            $totalAmountPaid = floatval(str_replace(',', '', $input['total_amount_paid'])); //Tổng tiền khách trả
//            $amountReturn = floatval(str_replace(',', '', $input['amount_return'])); //Tiền trả lại khách nếu thanh toán dư
//            $memberMoney = isset($input['member_money']) ? floatval(str_replace(',', '', $input['member_money'])) : 0;
//
//            if ($totalAmountPaid <= 0) {
//                return [
//                    'error' => true,
//                    'message' => __('Hãy nhập tiền thanh toán')
//                ];
//            }
//
//            if (count($input['receipt_type']) > 1 && $totalAmountPaid > $totalDebt) {
//                return [
//                    'error' => true,
//                    'message' => __('Tiền thanh toán không hợp lệ')
//                ];
//            }
//
//            $mCustomerBranchMoney = app()->get(CustomerBranchMoneyTable::class);
//
//            //Check tài khoản thành viên
//            $arrMethodWithMoney = $input['array_method'];
//
//            if (isset($arrMethodWithMoney) && $arrMethodWithMoney != null) {
//                foreach ($arrMethodWithMoney as $methodCode => $money) {
//                    if ($methodCode == 'MEMBER') {
//                        $branch_money = $mCustomerBranchMoney->getPriceBranch($input['customer_id'], Auth::user()->branch_id);
//
//                        if ($money > $branch_money['balance']) {
//                            return [
//                                'error' => true,
//                                'message' => __('Tài khoản thành viên không hợp lệ')
//                            ];
//                        }
//
//                        //Check có xài tiền thành viên ko dc thanh toán dư
//                        if ($money > $totalDebt) {
//                            return response()->json([
//                                'error' => true,
//                                'message' => __('Tiền thanh toán không hợp lệ')
//                            ]);
//                        }
//                    }
//                }
//            }
//
//            $mReceipt = app()->get(ReceiptTable::class);
//            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
//            $mCustomerDebt = app()->get(CustomerDebtTable::class);
//
//            //Insert phiếu thu tổng
//            $idReceipt = $mReceipt->add([
//                'customer_id' => $input['customer_id'],
//                'receipt_code' => 'code',
//                'staff_id' => Auth()->id(),
//                'branch_id' => Auth::user()->branch_id,
//                'object_type' => 'debt',
//                'total_money' => $totalDebt,
//                'status' => 'paid',
//                'amount' => $totalDebt,
//                'amount_paid' => $totalAmountPaid,
//                'amount_return' => $amountReturn,
//                'note' => $input['note'] ?? null,
//                'receipt_type_code' => 'RTC_DEBT',
//                'object_accounting_type_code' => 'OAT_CUSTOMER', // Đối tượng là KH
//                'object_accounting_id' => $input['customer_id'], // Id của KH
//                'created_by' => Auth()->id(),
//                'updated_by' => Auth()->id(),
//            ]);
//
//            //Cập nhật mã phiếu thu
//            $mReceipt->edit([
//                'receipt_code' => 'TT_' . date('dmY') . $idReceipt
//            ], $idReceipt);
//
//            //Insert chi tiết phiếu thu
//            foreach ($arrMethodWithMoney as $methodCode => $money) {
//                if ($money > 0) {
//                    $dataReceiptDetail = [
//                        'receipt_id' => $idReceipt,
//                        'cashier_id' => Auth()->id(),
//                        'amount' => $money,
//                        'payment_method_code' => $methodCode,
//                        'created_by' => Auth()->id(),
//                        'updated_by' => Auth()->id()
//                    ];
//                    //Insert chi tiết phiếu thu
//                    $mReceiptDetail->add($dataReceiptDetail);
//
//                    if ($methodCode == 'MEMBER_MONEY') {
//                        // Check số tiền thành viên
//                        if ($money <= $totalDebt) { // trừ tiên thành viên
//                            if ($money < $memberMoney) {
//                                //Lấy thông tin KH
//                                $customerMoney = $this->customer->getItem($input['customer_id']);
//                                //Cập nhật tiền KH
//                                $this->customer->edit([
//                                    'account_money' => $customerMoney['account_money'] - $money
//                                ], $input['customer_id']);
//
//                                //Lấy tiền KH theo chi nhánh
//                                $customerBranch = $mCustomerBranchMoney->getPriceBranch($input['customer_id'], Auth()->user()->branch_id);
//
//                                if ($customerBranch != null) {
//                                    //Cập nhật tiền KH theo chi nhánh
//                                    $mCustomerBranchMoney > edit([
//                                        'total_using' => $customerBranch['total_using'] + $money,
//                                        'balance' => $customerBranch['total_money'] - ($customerBranch['total_using'] + $money)
//                                    ], $input['customer_id'], $customerMoney['branch_id']);
//                                }
//                            } else {
//                                return [
//                                    'error' => true,
//                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
//                                    'money' => $memberMoney
//                                ];
//                            }
//                        } else {
//                            return [
//                                'error' => true,
//                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
//                            ];
//                        }
//                    }
//                }
//            }
//
//            //Lấy danh sách công nợ của khách hàng
//            $getDebt = $mCustomerDebt->getDebtNotFinishByCustomer($input['customer_id']);
//
//            $arrInsertDebtMap = [];
//
//            //Xử lý từng công nợ
//            if (count($getDebt) > 0) {
//                $mOrder = app()->get(OrderTable::class);
//
//                foreach ($getDebt as $v) {
//                    //TIền còn thiếu của công nợ này
//                    $amountDebt = $v['amount'] - $v['amount_paid'];
//
//                    //Tổng tiền trả đã hết
//                    if ($totalAmountPaid <= 0) {
//                        continue;
//                    }
//
//                    //Trạng thái công nợ
//                    $statusDebt = 'part-paid';
//
//                    if ($totalAmountPaid >= $amountDebt) {
//                        //Trả hết nợ thì cập nhật trạng thái đơn hàng
//                        $mOrder->edit([
//                            'process_status' => 'paysuccess',
//                        ], $v['order_id']);
//
//                        $statusDebt = 'paid';
//                        //Tiền trả cho công nợ này
//                        $amountPaidMyTurn = $amountDebt;
//                    } else {
//                        //Tiền trả cho công nợ này
//                        $amountPaidMyTurn = $totalAmountPaid;
//                    }
//
//                    //Cập nhật công nợ
//                    $mCustomerDebt->edit([
//                        'amount_paid' => $v['amount_paid'] + $amountPaidMyTurn,
//                        'status' => $statusDebt,
//                        'updated_by' => Auth()->id()
//                    ], $v['customer_debt_id']);
//
//                    //Trừ tiền thanh toán cho đợt này so với tổng tiền thanh toán
//                    $totalAmountPaid = $totalAmountPaid - $amountPaidMyTurn;
//
//                    $arrInsertDebtMap[] = [
//                        'receipt_id' => $idReceipt,
//                        'customer_debt_id' => $v['customer_debt_id'],
//                        'amount_paid' => $amountPaidMyTurn,
//                        'created_by' => Auth()->id(),
//                        'updated_by' => Auth()->id(),
//                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
//                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
//                    ];
//                }
//            }
//
//            $mReceiptDebtMap = app()->get(ReceiptDebtMapTable::class);
//            //Insert debt map để biết đợt thu này thanh toán cho những công nợ nào
//            $mReceiptDebtMap->insert($arrInsertDebtMap);
//
//            DB::commit();
//
//            //Tính điểm thưởng khi thanh toán
//            $mBookingApi = new BookingApi();
//            $mBookingApi->plusPointReceipt(['receipt_id' => $idReceipt]);
//
//            return [
//                'error' => false,
//                'message' => __('Thanh toán công nợ thành công'),
//            ];
//        } catch (\Exception $e) {
//            return [
//                'error' => true,
//                'message' => __('Thanh toán công nợ thất bại'),
//                '_message' => $e->getMessage() . $e->getLine(),
//            ];
//        }
//    }

    /**
     * Submit thanh toán nhanh công nợ
     *
     * @param $input
     * @return array
     */
    public function submitQuickReceiptDebt($input)
    {
        DB::beginTransaction();

        try {
            $totalDebt = floatval(str_replace(',', '', $input['total_debt'])); //Tổng tiền khách nợ
            $totalAmountPaid = floatval(str_replace(',', '', $input['total_amount_paid'])); //Tổng tiền khách trả
            $amountReturn = floatval(str_replace(',', '', $input['amount_return'])); //Tiền trả lại khách nếu thanh toán dư
            $memberMoney = isset($input['member_money']) ? floatval(str_replace(',', '', $input['member_money'])) : 0;

            if ($totalAmountPaid <= 0) {
                return [
                    'error' => true,
                    'message' => __('Hãy nhập tiền thanh toán')
                ];
            }

            if (count($input['receipt_type']) > 1 && $totalAmountPaid > $totalDebt) {
                return [
                    'error' => true,
                    'message' => __('Tiền thanh toán không hợp lệ')
                ];
            }

            $mCustomerBranchMoney = app()->get(CustomerBranchMoneyTable::class);
            $mReceipt = app()->get(ReceiptTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            $mCustomerDebt = app()->get(CustomerDebtTable::class);

            //Array phương thức thanh toán
            $arrMethodWithMoney = $input['array_method'];

            //Check tài khoản tha viên
            if (isset($arrMethodWithMoney) && $arrMethodWithMoney != null) {
                foreach ($arrMethodWithMoney as $methodCode => $money) {
                    if ($methodCode == 'MEMBER') {
                        $branch_money = $mCustomerBranchMoney->getPriceBranch($input['customer_id'], Auth::user()->branch_id);

                        if ($money > $branch_money['balance']) {
                            return [
                                'error' => true,
                                'message' => __('Tài khoản thành viên không hợp lệ')
                            ];
                        }

                        //Check có xài tiền thành viên ko dc thanh toán dư
                        if ($money > $totalDebt) {
                            return [
                                'error' => true,
                                'message' => __('Tiền thanh toán không hợp lệ')
                            ];
                        }
                    }
                }
            }

            //Lấy danh sách công nợ của khách hàng
            $getDebt = $mCustomerDebt->getDebtNotFinishByCustomer($input['customer_id']);

            $arrData = [];

            if (count($getDebt) > 0) {
                $mOrder = app()->get(OrderTable::class);

                foreach ($getDebt as $v) {
                    //Tiền nợ ban đầu
                    $amountDebt = $v['amount'] - $v['amount_paid'];
                    //Tiền nợ trả trong đợt thanh toán này
                    $amountPaidMyTurn = 0;
                    //Tiền nợ còn lại sau khi xử lý trừ từ các hình thức thanh toán
                    $amountDebtAfterPaid = $v['amount'] - $v['amount_paid'];

                    $arrMethodMyTurn = [];

                    foreach ($arrMethodWithMoney as $k1 => $v1) {
                        $paid = 0;

                        if ($amountDebtAfterPaid <= 0) {
                            break 1;
                        }

                        if ($v1 >= $amountDebtAfterPaid) {
                            $paid = $amountDebtAfterPaid;
                        } else {
                            $paid = $v1;
                        }

                        //Trừ tiền thanh toán cho phương thức này so với tiền nợ cần thanh toán
                        $amountDebtAfterPaid = $amountDebtAfterPaid - $paid;
                        //Trừ tiền thanh toán từ phương thức thanh toán
                        $arrMethodWithMoney[$k1] = $v1 - $paid;

                        $amountPaidMyTurn += $paid;

                        $arrMethodMyTurn [] = [
                            'payment_method_code' => $k1,
                            'amount' => $paid
                        ];
                    }

                    //Insert phiếu thu
                    $receiptId = $mReceipt->add([
                        'customer_id' => $input['customer_id'],
                        'receipt_code' => 'code',
                        'staff_id' => Auth()->id(),
                        'branch_id' => Auth::user()->branch_id,
                        'object_type' => 'debt',
                        'object_id' => $v['customer_debt_id'],
                        'order_id' => $v['order_id'],
                        'total_money' => $amountDebt,
                        'status' => 'paid',
                        'amount' => $amountDebt,
                        'amount_paid' => $amountPaidMyTurn,
                        'amount_return' => 0,
                        'note' => $input['note'] ?? null,
                        'receipt_type_code' => 'RTC_DEBT',
                        'object_accounting_type_code' => $v['debt_code'], // debt code
                        'object_accounting_id' => $v['customer_debt_id'], // debt id
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                    ]);

                    //Update mã phiếu thu
                    $mReceipt->edit([
                        'receipt_code' => 'TT_' . date('dmY') . $receiptId
                    ], $receiptId);

                    //Insert chi tiết phiếu thu
                    foreach ($arrMethodMyTurn as $v2) {
                        if ($v2['payment_method_code'] == 'MEMBER_MONEY') {
                            // Check số tiền thành viên
                            if ($v['amount'] <= $amountDebt) { // trừ tiên thành viên
                                if ($v['amount'] < $memberMoney) {
                                    //Lấy thông tin KH
                                    $customerMoney = $this->customer->getItem($input['customer_id']);
                                    //Cập nhật tiền KH
                                    $this->customer->edit([
                                        'account_money' => $customerMoney['account_money'] - $v['amount']
                                    ], $input['customer_id']);

                                    //Lấy tiền KH theo chi nhánh
                                    $customerBranch = $mCustomerBranchMoney->getPriceBranch($input['customer_id'], Auth()->user()->branch_id);

                                    if ($customerBranch != null) {
                                        //Cập nhật tiền KH theo chi nhánh
                                        $mCustomerBranchMoney->edit([
                                            'total_using' => $customerBranch['total_using'] + $v['amount'],
                                            'balance' => $customerBranch['total_money'] - ($customerBranch['total_using'] + $v['amount'])
                                        ], $input['customer_id'], $customerMoney['branch_id']);
                                    }
                                } else {
                                    return [
                                        'error' => true,
                                        'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                        'money' => $memberMoney
                                    ];
                                }
                            } else {
                                return [
                                    'error' => true,
                                    'money_large_moneybill' => 1,
                                    'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                                ];
                            }
                        }

                        //Insert chi tiết phiếu thu
                        $mReceiptDetail->add([
                            'receipt_id' => $receiptId,
                            'cashier_id' => Auth()->id(),
                            'amount' => $v2['amount'],
                            'payment_method_code' => $v2['payment_method_code'],
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id()
                        ]);
                    }

                    //Trạng thái công nợ
                    $statusDebt = 'part-paid';

                    if ($amountPaidMyTurn >= $amountDebt) {
                        //Trả hết nợ thì cập nhật trạng thái đơn hàng
                        $mOrder->edit([
                            'process_status' => 'paysuccess',
                        ], $v['order_id']);

                        $statusDebt = 'paid';
                    }

                    //Cập nhật công nợ
                    $mCustomerDebt->edit([
                        'amount_paid' => $v['amount_paid'] + $amountPaidMyTurn,
                        'status' => $statusDebt,
                        'updated_by' => Auth()->id()
                    ], $v['customer_debt_id']);

                    $arrData [] = [
                        'customer_debt_id' => $v['customer_debt_id'],
                        'amount' => $amountDebt,
                        'amount_paid_turn' => $amountPaidMyTurn,
                        'method' => $arrMethodMyTurn,
                        'receipt_id' => $receiptId
                    ];
                }
            }

            DB::commit();

            //Tính điểm thưởng khi thanh toán
            $mBookingApi = new BookingApi();

            if (count($arrData) > 0) {
                foreach ($arrData as $v) {
                    //Cộng điểm khi thanh toán
                    $mBookingApi->plusPointReceipt(['receipt_id' => $v['receipt_id']]);
                }
            }

            return [
                'error' => false,
                'message' => __('Thanh toán công nợ thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thanh toán công nợ thất bại'),
                '_message' => $e->getMessage() . $e->getLine(),
            ];
        }
    }

    /**
     * Lấy danh sách người liên hệ
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function listPersonContact($input)
    {
        $mPersonContact = app()->get(CustomerPersonContactTable::class);

        //Lấy ds người liên hệ
        $list = $mPersonContact->getList($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Lấy dữ liệu view tạo người liên hệ
     *
     * @return mixed|void
     */
    public function getDataCreatePersonContact()
    {
        $mStaffTitle = app()->get(StaffTitleTable::class);

        //Lấy option chức vụ
        $optionTitle = $mStaffTitle->getOption();

        return [
            'optionTitle' => $optionTitle
        ];
    }

    /**
     * Thêm người liên hệ
     *
     * @param $input
     * @return array|void
     */
    public function storePersonContact($input)
    {
        try {
            $mPersonContact = app()->get(CustomerPersonContactTable::class);

            //Check sdt đã tồn tại chưa
            $checkUnique = $mPersonContact->checkUniquePhone($input['person_phone'], $input['customer_id'], 0);

            if ($checkUnique != null) {
                return [
                    'error' => true,
                    'message' => __('Số điện thoại đã trùng với') . ' ' . $checkUnique['person_name'],
                ];
            }

            //Thêm người liên hệ
            $mPersonContact->add([
                'customer_id' => $input['customer_id'],
                'person_name' => $input['person_name'],
                'person_phone' => $input['person_phone'],
                'person_email' => $input['person_email'],
                'staff_title_id' => $input['staff_title_id'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'error' => false,
                'message' => __('Thêm thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy dữ liệu view chỉnh sửa người liên hệ
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getDataEditPersonContact($input)
    {
        $mStaffTitle = app()->get(StaffTitleTable::class);
        $mPersonContact = app()->get(CustomerPersonContactTable::class);

        //Lấy option chức vụ
        $optionTitle = $mStaffTitle->getOption();
        //Lấy thông tin người liên hệ
        $info = $mPersonContact->getInfo($input['customer_person_contact_id']);

        return [
            'optionTitle' => $optionTitle,
            'info' => $info
        ];
    }

    /**
     * Chỉnh sửa người liên hệ
     *
     * @param $input
     * @return array|void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function updatePersonContact($input)
    {
        try {
            $mPersonContact = app()->get(CustomerPersonContactTable::class);

            //Check sdt đã tồn tại chưa
            $checkUnique = $mPersonContact->checkUniquePhone($input['person_phone'], $input['customer_id'], $input['customer_person_contact_id']);

            if ($checkUnique != null) {
                return [
                    'error' => true,
                    'message' => __('Số điện thoại đã trùng với') . ' ' . $checkUnique['person_name'],
                ];
            }

            //Thêm người liên hệ
            $mPersonContact->edit([
                'customer_id' => $input['customer_id'],
                'person_name' => $input['person_name'],
                'person_phone' => $input['person_phone'],
                'person_email' => $input['person_email'],
                'staff_title_id' => $input['staff_title_id'],
                'updated_by' => Auth()->id()
            ], $input['customer_person_contact_id']);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy ds ghi chú
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function listNote($input)
    {
        $mCustomerNote = app()->get(CustomerNoteTable::class);

        //Lấy ds ghi chú
        $list = $mCustomerNote->getList($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Thêm ghi chú
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function storeNote($input)
    {
        try {
            $mCustomerNote = app()->get(CustomerNoteTable::class);

            //Thêm ghi chú
            $mCustomerNote->add([
                'customer_id' => $input['customer_id'],
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'error' => false,
                'message' => __('Thêm thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy data view chỉnh sửa ghi chú
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getDataEditNote($input)
    {
        $mCustomerNote = app()->get(CustomerNoteTable::class);

        //Lấy thông tin ghi chú
        $info = $mCustomerNote->getInfo($input['customer_note_id']);

        return [
            'info' => $info
        ];
    }

    /**
     * Chỉnh sửa ghi chú
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function updateNote($input)
    {
        try {
            $mCustomerNote = app()->get(CustomerNoteTable::class);

            //Thêm ghi chú
            $mCustomerNote->edit([
                'note' => $input['note'],
                'updated_by' => Auth()->id()
            ], $input['customer_note_id']);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Danh sách tập tin
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function listFile($input)
    {
        $mCustomerFile = app()->get(CustomerFileTable::class);

        //Lấy ds tập tin
        $list = $mCustomerFile->getList($input);

        return [
            'list' => $list
        ];
    }

     /**
     * Danh sách deals
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function listDeals($input)
    {
        $mDeal = app()->get(DealTable::class);
        //Lấy ds tập tin
        $list = $mDeal->getListDealLeadDetail($input);
        return [
            'list' => $list
        ];
    }

    /**
     * Thêm tập tin
     *
     * @param $input
     * @return array|void
     */
    public function storeFile($input)
    {
        try {
            if (!isset($input['arrayFile']) || count($input['arrayFile']) == 0) {
                return [
                    'error' => true,
                    'message' => __('Vui lòng tải hồ sơ')
                ];
            }

            $mCustomerFile = app()->get(CustomerFileTable::class);

            $arrFile = [];

            foreach ($input['arrayFile'] as $v) {
                $type = 'file';

                if (in_array($v['type'], ['jpeg', 'jpg', 'png'])) {
                    $type = 'image';
                }

                $arrFile [] = [
                    'customer_id' => $input['customer_id'],
                    'type' => $type,
                    'link' => $v['path'],
                    'file_name' => $v['file_name'],
                    'note' => $input['note'],
                    'file_type' => $v['type'],
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            }

            //Insert file của KH
            $mCustomerFile->insert($arrFile);

            return [
                'error' => false,
                'message' => __('Thêm thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy thông tin view chỉnh sửa file
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getDataEditFile($input)
    {
        $mCustomerFile = app()->get(CustomerFileTable::class);

        //Lấy thông tin file
        $info = $mCustomerFile->getInfo($input['customer_file_id']);

        return [
            'info' => $info
        ];
    }

    /**
     * Chỉnh sửa tập tin
     *
     * @param $input
     * @return mixed|void
     */
    public function updateFile($input)
    {
        try {
            if (!isset($input['arrayFile']) || count($input['arrayFile']) == 0) {
                return [
                    'error' => true,
                    'message' => __('Vui lòng tải hồ sơ')
                ];
            }

            if (count($input['arrayFile']) > 1) {
                return [
                    'error' => true,
                    'message' => __('Hồ sơ tối đa 1 tập tin')
                ];
            }

            $mCustomerFile = app()->get(CustomerFileTable::class);


            foreach ($input['arrayFile'] as $v) {
                $type = 'file';

                if (in_array($v['type'], ['jpeg', 'jpg', 'png'])) {
                    $type = 'image';
                }

                //Chỉnh sửa tập tin
                $mCustomerFile->edit([
                    'type' => $type,
                    'link' => $v['path'],
                    'file_name' => $v['file_name'],
                    'note' => $input['note'],
                    'file_type' => $v['type'],
                    'updated_by' => Auth()->id(),
                ], $input['customer_file_id']);
            }

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }
}
