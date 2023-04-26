<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/2/2018
 * Time: 4:09 PM
 */

namespace Modules\Admin\Http\Controllers;

use App\Exports\ExportFile;
use App\Jobs\SaveLogZns;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\Admin\Models\ConfigCustomerParameterTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\CustomerBranchMoneyLogTable;
use Modules\Admin\Models\CustomerBranchTable;
use Modules\Admin\Models\CustomerConfigTabDetailTable;
use Modules\Admin\Models\CustomerCustomDefineTable;
use Modules\Admin\Models\CustomerFileTable;
use Modules\Admin\Models\CustomerNoteTable;
use Modules\Admin\Models\CustomerSourceTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DistrictTable;
use Modules\Admin\Models\MemberLevelTable;
use Modules\Admin\Models\PointHistoryTable;
use Modules\Admin\Models\ProvinceTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\WardTable;
use Modules\Admin\Repositories\AppointmentService\AppointmentServiceRepositoryInterface;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\CommissionLog\CommissionLogRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepositoryInterface;
use Modules\Admin\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\Admin\Repositories\CustomerDebt\CustomerDebtRepositoryInterface;
use Modules\Admin\Repositories\CustomerGroup\CustomerGroupRepositoryInterface;
use Modules\Admin\Repositories\CustomerServiceCard\CustomerServiceCardRepositoryInterface;
use Modules\Admin\Repositories\CustomerSource\CustomerSourceRepositoryInterface;
use Modules\Admin\Repositories\District\DistrictRepositoryInterface;
use Modules\Admin\Repositories\MemberLevel\MemberLevelRepositoryInterface;
use Modules\Admin\Repositories\Notification\NotificationRepoInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\PointHistory\PointHistoryRepoInterface;
use Modules\Admin\Repositories\PointRewardRule\PointRewardRuleRepositoryInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use App\Exports\CustomerExport;
use App\Jobs\CheckMailJob;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Repositories\Loyalty\LoyaltyRepositoryInterface;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;
use Modules\CustomerLead\Models\CustomerLogTable;
use Modules\CustomerLead\Models\CustomerLogUpdateTable;
use Modules\OnCall\Models\CustomerRealCareTable;

//
class CustomerController extends Controller
{
    protected $customer;
    protected $customer_group;
    protected $customer_source;
    protected $code;
    protected $province;
    protected $district;
    protected $staff;
    protected $order;
    protected $customer_appointment;
    protected $appointment_service;
    protected $customer_service_card;
    protected $branch;
    protected $customer_branch_money;
    protected $customer_appointment_detail;
    protected $service_card_list;
    protected $smsLog;
    protected $order_detail;
    protected $service_card;
    protected $receipt;
    protected $customer_debt;
    protected $commission_log;
    protected $order_commission;
    protected $memberLevel;
    protected $pointHistory;
    protected $loyalty;
    protected $pointReward;
    protected $loyaltyApi;

    /**
     * CustomerController constructor.
     * @param CustomerRepositoryInterface $customers
     */
    public function __construct(
        CustomerRepositoryInterface $customers,
        CustomerGroupRepositoryInterface $customer_groups,
        CustomerSourceRepositoryInterface $customer_sources,
        CodeGeneratorRepositoryInterface $codes,
        ProvinceRepositoryInterface $provinces,
        DistrictRepositoryInterface $districts,
        StaffRepositoryInterface $staffs,
        OrderRepositoryInterface $orders,
        OrderDetailRepositoryInterface $order_details,
        CustomerAppointmentRepositoryInterface $customer_appointments,
        AppointmentServiceRepositoryInterface $appointment_services,
        CustomerServiceCardRepositoryInterface $customer_sv_cards,
        BranchRepositoryInterface $branches,
        CustomerBranchMoneyRepositoryInterface $customer_branch_moneys,
        CustomerAppointmentDetailRepositoryInterface $customer_appointment_details,
        ServiceCardListRepositoryInterface $service_card_lists,
        SmsLogRepositoryInterface $smsLog,
        ServiceCardRepositoryInterface $service_card,
        ReceiptRepositoryInterface $receipt,
        CustomerDebtRepositoryInterface $customer_debt,
        CommissionLogRepositoryInterface $commission_log,
        OrderCommissionRepositoryInterface $order_commission,
        MemberLevelRepositoryInterface $memberLevel,
        PointHistoryRepoInterface $pointHistory,
        LoyaltyRepositoryInterface $loyalty,
        PointRewardRuleRepositoryInterface $pointReward,
        LoyaltyApi $loyaltyApi
    ) {
        $this->customer = $customers;
        $this->customer_group = $customer_groups;
        $this->customer_source = $customer_sources;
        $this->code = $codes;
        $this->province = $provinces;
        $this->district = $districts;
        $this->staff = $staffs;
        $this->order = $orders;
        $this->order_detail = $order_details;
        $this->customer_appointment = $customer_appointments;
        $this->appointment_service = $appointment_services;
        $this->customer_service_card = $customer_sv_cards;
        $this->branch = $branches;
        $this->customer_branch_money = $customer_branch_moneys;
        $this->customer_appointment_detail = $customer_appointment_details;
        $this->service_card_list = $service_card_lists;
        $this->smsLog = $smsLog;
        $this->service_card = $service_card;
        $this->receipt = $receipt;
        $this->customer_debt = $customer_debt;
        $this->commission_log = $commission_log;
        $this->order_commission = $order_commission;
        $this->memberLevel = $memberLevel;
        $this->pointHistory = $pointHistory;
        $this->loyalty = $loyalty;
        $this->pointReward = $pointReward;
        $this->loyaltyApi = $loyaltyApi;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $get = $this->customer->list();
        //Lấy option tỉnh thành
        $optionProvice = $this->province->getOptionProvince();

        return view('admin::customer.index', [
            'LIST' => $get,
            'FILTER' => $this->filters(),
            'optionProvince' => $optionProvice
        ]);
    }

    /**
     * @return array
     */
    protected function filters()
    {
        $optionGroup = $this->customer_group->getOption();
        $group = (["" => __('Chọn nhóm')]) + $optionGroup;
        return [
            'customers$customer_group_id' => [
                'data' => $group
            ],
            //            'customers$gender' => [
            //                'data' => [
            //                    '' => 'Chọn giới tính',
            //                    'male' => 'Nam',
            //                    'female' => 'Nữ',
            //                    'other' => 'Khác'
            //                ]
            //            ]
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page', 'display', 'search_type', 'search_keyword',
            'customers$customer_group_id', 'customers$gender', 'created_at', 'birthday', 'search', 'customer_refer_id',
            'customers$province_id', 'customers$district_id', 'customers$ward_id'
        ]);

        $list = $this->customer->list($filter);
        return view('admin::customer.list', [
            'LIST' => $list,
            'page' => $filter['page']
        ]);
    }

    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->customer->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    /**
     * View thêm khách hàng
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAction(Request $request)
    {
        $mCustomerDefine = new CustomerCustomDefineTable();
        $mConfigParameter = new ConfigCustomerParameterTable();

        $optionCustomerGroup = $this->customer_group->getOption();
        $optionCustomerSource = $this->customer_source->getOption();
        $code = $this->code->generateServiceCardCode("");
        $optionProvice = $this->province->getOptionProvince();

        //Lấy cấu hình thông tin kèm theo của KH
        $customDefine = $mCustomerDefine->getDefine();
        //Lấy cấu hình tham số
        $getParameter = $mConfigParameter->getParameter();


        return view('admin::customer.add', [
            'optionGroup' => $optionCustomerGroup,
            'optionSource' => $optionCustomerSource,
            'code' => $code,
            'optionProvince' => $optionProvice,
            'customDefine' => $customDefine,
            'getParameter' => $getParameter,
            'params' => $request->all()
        ]);
    }


    public function loadDistrictAction(Request $request)
    {
        $filters = request()->all();
        $district = $this->district->getOptionDistrict($filters);

        $data = [];
        foreach ($district as $key => $value) {
            $data[] = [
                'id' => (int)$value['districtid'],
                'name' => $value['name'],
                'type' => $value['type']
            ];
        }
        return response()->json([
            'optionDistrict' => $data,
            //            'pagination' => $district->nextPageUrl() ? true : false
        ]);
    }

    public function loadWard(Request $request)
    {
        $filters = request()->all();
        $mWard = app()->get(WardTable::class);
        $ward = $mWard->getOptionWard($filters['id_district']);

        $data = [];
        foreach ($ward as $key => $value) {
            $data[] = [
                'id' => $value['ward_id'],
                'name' => $value['name'],
                'type' => $value['type']
            ];
        }
        return response()->json([
            'optionWard' => $data,
            //            'pagination' => $district->nextPageUrl() ? true : false
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddCustomerGroupAction(Request $request)
    {
        $group_name = $request->group_name;
        $test = $this->customer_group->testName($group_name, 0);
        if ($test == null) {
            $data = [
                'group_name' => $group_name,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'slug' => str_slug($group_name)
            ];
            //Insert customer group
            $groupId = $this->customer_group->add($data);
            //Update group_uuid
            $this->customer_group->edit([
                'group_uuid' => 'CUSTOMER_GROUP_' . date('dmY') . sprintf("%02d", $groupId)
            ], $groupId);

            $optionGroup = $this->customer_group->getOption();
            return response()->json([
                'status' => 1,
                'close' => $request->close,
                'optionGroup' => $optionGroup
            ]);
        } else {
            return response()->json([
                'status' => 0,
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddCustomerReferAction(Request $request)
    {
        $mCustomerBranch = new CustomerBranchTable();
        $mConfig = new ConfigTable();

        //Kiểm tra số điện thoại đã tồn tại
        $test_phone1 = $this->customer->testPhone($request->phone1, 0);

        if (!empty($test_phone1)) {
            //Kiểm tra KH đó có ở chi nhánh này không
            $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($test_phone1['customer_id'], Auth()->user()->branch_id);

            if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch', session('routeList'))) {
                return response()->json([
                    'phone_error' => 1,
                    'message' => __('Số điện thoại đã tồn tại')
                ]);
            }

            //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
            $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

            if ($getInsertBranch == 1) {
                //Tự động insert chi nhánh và lấy customer_id ra
                $mCustomerBranch->add([
                    'customer_id' => $test_phone1['customer_id'],
                    'branch_id' => Auth()->user()->branch_id
                ]);

                return response()->json([
                    'status' => 1,
                    'close' => $request->close
                ]);
            } else {
                return response()->json([
                    'phone_error' => 1,
                    'message' => __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này')
                ]);
            }
        }

        $data = [
            'full_name' => $request->full_name,
            'customer_code' => 'cs' . $this->code->generateServiceCardCode(""),
            'phone1' => $request->phone1,
            'address' => $request->address,
            'gender' => 'other',
            'customer_source_id' => 1,
            'branch_id' => Auth()->user()->branch_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'is_actived' => 1
        ];
        //Thêm khách hàng
        $id_add = $this->customer->add($data);

        if ($id_add < 10) {
            $id_add = '0' . $id_add;
        }
        //Thêm khách hàng
        $this->customer->edit([
            'customer_code' => 'KH_' . date('dmY') . $id_add
        ], $id_add);

        return response()->json([
            'status' => 1,
            'close' => $request->close
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerReferAction(Request $request)
    {
        $data = $request->all();
        if (isset($data['search'])) {
            $value = $this->customer->getCustomerSearch($data['search']);
            $search = [];
            foreach ($value as $item) {
                $search['results'][] = [
                    'id' => $item['customer_id'],
                    'text' => $item['full_name'] . " - " . $item['phone1']
                ];
            }
            return response()->json([
                'search' => $search,
                'pagination' => $value->nextPageUrl() ? true : false
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerChathubAction(Request $request)
    {
        $data = $request->all();
        if (isset($data['search'])) {
            $value = $this->customer->getCustomerSearch($data['search']);
            $search = [];
            foreach ($value as $item) {
                $search['results'][] = [
                    'id' => $item['customer_id'],
                    'text' => $item['full_name'] . " - " . $item['phone1']
                ];
            }
            return response()->json([
                'search' => $search,
                'pagination' => $value->nextPageUrl() ? true : false
            ]);
        }
    }

    /**
     * Thêm khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $mCustomerBranch = app()->get(CustomerBranchTable::class);
            $mConfig = app()->get(ConfigTable::class);

            $phone1 = $request->phone1;
            $phone2 = $request->phone2;

            $data = [
                'customer_group_id' => $request->customer_group_id,
                'full_name' => $request->full_name,
                //            'customer_code' => 'cs' . $this->code->generateServiceCardCode(""),
                'branch_id' => Auth()->user()->branch_id,
                //            'birthday' => $birthday,
                'gender' => 'other',
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'email' => $request->email,
                'facebook' => $request->facebook,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'address' => $request->address,
                'customer_source_id' => 1,
                'customer_refer_id' => $request->customer_refer_id,
                //            'customer_avatar'
                'note' => $request->note,
                'is_actived' => 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'postcode' => $request->postcode,
                'customer_type' => $request->customer_type,
                'tax_code' => $request->tax_code ?? '',
                'representative' => $request->representative ?? '',
                'hotline' => $request->hotline ?? '',
                'profile_code' => $request->profile_code,
                'ch_customer_id' => $request->ch_customer_id
            ];

            //Define sẵn 10 trường thông tin kèm theo
            for ($i = 1; $i <= 10; $i++) {
                $custom = "custom_$i";
                $data["custom_$i"] = isset($request->$custom) ? $request->$custom : null;
            }

            if ($request->year != null && $request->month != null && $request->day != null) {
                $birthday = $request->year . '-' . $request->month . '-' . $request->day;
                $data['birthday'] = $birthday;
                if ($birthday > date("Y-m-d")) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Ngày sinh không hợp lệ')
                    ]);
                }
            }

            if ($request->customer_source_id != null) {
                $data['customer_source_id'] = $request->customer_source_id;
            }
            if ($request->gender != null) {
                $data['gender'] = $request->gender;
            }
            //Kiểm tra sđt 1 đã tồn tại chưa
            $test_phone1 = $this->customer->testPhone($phone1, 0);
            //Kiểm tra sđt 2 đã tồn tại chưa
            $test_phone2 = $this->customer->testPhone($phone2, 0);

            if (!empty($test_phone1)) {
                //Kiểm tra KH đó có ở chi nhánh này không
                $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($test_phone1['customer_id'], Auth()->user()->branch_id);

                if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch', session('routeList'))) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại 1 đã tồn tại')
                    ]);
                }
                //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
                $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

                if ($getInsertBranch == 1) {
                    //Tự động insert chi nhánh và lấy customer_id ra
                    $mCustomerBranch->add([
                        'customer_id' => $test_phone1['customer_id'],
                        'branch_id' => Auth()->user()->branch_id
                    ]);

                    DB::commit();

                    return response()->json([
                        'error' => false,
                        'message' => __('Thêm khách hàng thành công')
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này')
                    ]);
                }
            }

            if (!empty($phone2) && !empty($test_phone2)) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số điện thoại 2 đã tồn tại')
                ]);
            }


            if ($request->customer_avatar != null) {
                $data['customer_avatar'] = $request->customer_avatar;
            }
            //Thêm khách hàng
            $id_add = $this->customer->add($data);

            if ($id_add < 10) {
                $id_add = '0' . $id_add;
            }

            $customerCode = 'KH_' . date('dmY') . $id_add;

            $data_code = [
                'customer_code' => $customerCode
            ];
            //Cập nhật mã khách hàng
            $this->customer->edit($data_code, $id_add);

            //Tự động insert chi nhánh và lấy customer_id ra
            $mCustomerBranch->add([
                'customer_id' => $id_add,
                'branch_id' => Auth()->user()->branch_id
            ]);

            $mCustomerFile = new CustomerFileTable();

            $arrImageCustomer = [];
            $arrFileCustomer = [];

            if (isset($request->imageCustomer) && count($request->imageCustomer)) {
                foreach ($request->imageCustomer as $v) {
                    $arrImageCustomer[] = [
                        "customer_id" => $id_add,
                        "type" => "image",
                        "link" => $v['path'],
                        'file_name' => $v['file_name'],
                        'file_type' => $v['type'],
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id()
                    ];
                }
            }
            //Thêm ảnh kèm theo
            $mCustomerFile->insert($arrImageCustomer);

            if (isset($request->fileCustomer) && count($request->fileCustomer)) {
                foreach ($request->fileCustomer as $v) {
                    $arrFileCustomer[] = [
                        "customer_id" => $id_add,
                        "type" => "file",
                        "link" => $v['path'],
                        'file_name' => $v['file_name'],
                        'file_type' => $v['type'],
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id()
                    ];
                }
            }
            //Thêm file kèm theo
            $mCustomerFile->insert($arrFileCustomer);

            //Config Rule Refer
            if ($request->customer_refer_id != null) {
                $configRefer = $this->pointReward->getRuleByCode('refer');
                if ($configRefer['is_actived'] == 1) {
                    //Plus Point Event Refer
                    $this->loyaltyApi->plusPointEvent(['customer_id' => $request->customer_refer_id, 'rule_code' => 'refer', 'object_id' => '']);
                }
            }
            CheckMailJob::dispatch('is_event', 'new_customer', $id_add);
            $this->smsLog->getList('new_customer', $id_add);

            DB::commit();
            //Send notification KH mới
            $mNoti = new SendNotificationApi();
            $mNoti->sendNotification([
                'key' => 'customer_W',
                'customer_id' => $id_add,
                'object_id' => ''
            ]);
            //Lưu log ZNS
            SaveLogZns::dispatch('new_customer', $id_add, $id_add);

            return response()->json([
                'error' => false,
                'message' => __('Thêm khách hàng thành công'),
                'data' => [
                    'customer_id' => $id_add,
                    'customer_code' => $customerCode
                ] // data for chathub
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    //function upload image
    public function uploadAction(Request $request)
    {
        $this->validate($request, [
            "customer_avatar" => "mimes:jpg,jpeg,png,gif|max:10000"
        ], [
            "customer_avatar.mimes" => __('File này không phải file hình'),
            "customer_avatar.max" => __('File quá lớn')
        ]);
        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            return response()->json(["file" => $file, "success" => "1"]);
        }
    }

    //Lưu file image vào folder temp
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_customer." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH . "/" . $file_name, file_get_contents($file));
        return $file_name;
    }

    //Chuyển file từ folder temp sang folder chính
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = CUSTOMER_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(CUSTOMER_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    //function delete image
    public function deleteTempFileAction(Request $request)
    {

        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }

    const PLUS = "plus";
    const SUBTRACT = "subtract";

    /**
     * Chi tiết khách hàng
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detailAction(Request $request, $id)
    {
        $value = Cache::remember('config', 360, function () {
            return ConfigTable::all();
        });
        $collectionDetail = collect($value->toArray());


        //Lấy thông tin khách hàng
        $getItem = $this->customer->getItem($id);
        //Lấy thông tin người giới thiệu
        $getItemRefer = $this->customer->getItemRefer($id);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $collectionDetail->where('key', 'is_total_branch')->first()['value'];
        // $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();

        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, self::PLUS);
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($id, $branchId, self::SUBTRACT);
        //Lấy tổng tiền hoa hồng cộng vào
        $totalPlusCommission = $mBranchMoneyLog->getTotalCommission($id, $branchId, self::PLUS);
        //Lấy tổng tiền hoa hồng trừ ra
        $totalSubtractCommission = $mBranchMoneyLog->getTotalCommission($id, $branchId, self::SUBTRACT);

        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        $commissionMoney = floatval($totalPlusCommission['total']) - floatval($totalSubtractCommission['total']);

        $customer_money = $accountMoney > 0 ? $accountMoney : 0;
        $commission_money = $commissionMoney > 0 ? $commissionMoney : 0;

        //Kiểm tra công nợ
        $amountDebt = $this->customer_debt->getItemDebt($id);

        $debt = 0;
        if (count($amountDebt) > 0) {
            foreach ($amountDebt as $item) {
                $debt += $item['amount'] - $item['amount_paid'];

                $arr_debt[] = [
                    'customer_debt_id' => $item['customer_debt_id'],
                    'order_code' => $item['order_code'],
                    'amount' => $item['amount'],
                    'amount_paid' => $item['amount_paid'],
                    'full_name' => $item['full_name'],
                    'created_at' => $item['created_at'],
                    'status' => $item['status'],
                    'debt_type' => $item['debt_type'],
                    'note' => $item['note'],
                    'order_source_id' => $item['order_source_id'],
                    'order_id' => $item['order_id']
                ];
            }
        }

        if ($getItem != null) {
            if ($getItem['member_level_id'] != 4) {
                $memberLevel = $this->memberLevel->getItem((int)($getItem['member_level_id']) + 1);
            } else {
                $memberLevel = $this->memberLevel->getItem(4);
            }

            $mCustomerFile = new CustomerFileTable();
            //Lấy ảnh/file kèm theo
            $getFile = $mCustomerFile->getCustomerFile($getItem['customer_id']);

            $mConfig = new ConfigTable();
            //Lấy cấu hình đặt lịch từ ngày đến ngày
            // $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
            $configToDate = $collectionDetail->where('key', 'booking_to_date')->first()['value'];
            session()->put('customer_id_appointment', $getItem['customer_id']);

            $params = $request->all();

            $getItem['full_address'] = $getItem['address'];

            if ($getItem['ward_name'] != null) {
                $getItem['full_address'] .=  ', ' . $getItem['ward_type'] . ' ' . $getItem['ward_name'];
            }

            if ($getItem['district_name'] != null) {
                $getItem['full_address'] .=  ', ' . $getItem['district_type'] . ' ' . $getItem['district_name'];
            }

            if ($getItem['province_name'] != null) {
                $getItem['full_address'] .=  ', ' . $getItem['province_type'] . ' ' . $getItem['province_name'];
            }

            $mConfigTabDetail = app()->get(CustomerConfigTabDetailTable::class);

            //Lấy cầu hình tab trong chi tiết KH
            $getConfigTab = $mConfigTabDetail->getConfigTabDetail();

            $mCustomerNote = app()->get(CustomerNoteTable::class);

            //Lấy ghi chú gần nhất
            $getLastNote = $mCustomerNote->getLastNoteByCustomer($id);

            return view('admin::customer.detail', [
                'params' => $params,
                'item' => $getItem,
                'itemRefer' => $getItemRefer,
                'customer_money' => $customer_money,
                'amountDebt' => $debt,
                'commission_money' => $commission_money,
                'memberLevel' => $memberLevel,
                'getFile' => $getFile,
                'configToDate' => $configToDate,
                'configTab' => $getConfigTab,
                'lastNote' => $getLastNote
            ]);
        } else {
            return redirect()->route('admin.customer');
        }
    }

    /**
     * list history customer real care (paging, search)
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getListCustomerRealCare(Request $request)
    {
        $filterCare = $request->all();
        $mCustomerRealCare = new CustomerRealCareTable();
        $listCustomerCare = $mCustomerRealCare->getListCustomerCare($filterCare);
        return view('admin::customer.list-customer-real-care', [
            'listCustomerCare' => $listCustomerCare,
            'page' => $filterCare['page']
        ]);
    }



    /**
     * View chỉnh sửa khách hàng
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     */
    public function editAction($id, Request $request)
    {
        $getItem = $this->customer->getItem($id);

        if ($getItem['birthday'] != null) {
            $birthday = explode('/', date("d/m/Y", strtotime($getItem['birthday'])));
            $day = $birthday[0];
            $month = $birthday[1];
            $year = $birthday[2];
        } else {
            $day = null;
            $month = null;
            $year = null;
        }

        $getItemRefer = $this->customer->getItemRefer($id);
        $optionCustomerGroup = $this->customer_group->getOption();
        $optionCustomerSource = $this->customer_source->getOption();
        $optionProvince = $this->province->getOptionProvince();
        $mWard = app()->get(WardTable::class);

        //Lấy cấu hình tham số
        $mConfigParameter = new ConfigCustomerParameterTable();
        $getParameter = $mConfigParameter->getParameter();

        if ($getItem != null) {
            $mCustomerFile = new CustomerFileTable();
            //Lấy ảnh/file kèm theo
            $getFile = $mCustomerFile->getCustomerFile($getItem['customer_id']);
            //Lấy cấu hình thông tin kèm theo của KH
            $mCustomerDefine = new CustomerCustomDefineTable();
            $customDefine = $mCustomerDefine->getDefine();

            $listWard = $mWard->getOptionWard($getItem['district_id']);

            return view('admin::customer.edit', [
                'item' => $getItem,
                'optionGroup' => $optionCustomerGroup,
                'optionSource' => $optionCustomerSource,
                'getRefer' => $getItemRefer,
                'optionProvince' => $optionProvince,
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'getFile' => $getFile,
                'customDefine' => $customDefine,
                'getParameter' => $getParameter,
                'listWard' => $listWard,
                'params' => $request->all()
            ]);
        } else {
            return redirect()->route('admin.customer');
        }
    }

    public function loadBirthdayAction(Request $request)
    {
        $id = $request->id;
        $getItem = $this->customer->getItem($id);

        $birthday = explode('-', date('Y-m-d', strtotime($getItem['birthday'])));
        return response()->json([
            'day' => $birthday[2],
            'month' => $birthday[1],
            'year' => $birthday[0]
        ]);
    }

    /**
     * Submit chỉnh sửa khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitEditAction(Request $request)
    {
        try {

            DB::beginTransaction();
            $mCustomerBranch = new CustomerBranchTable();
            $mConfig = new ConfigTable();

            $id = $request->id;

            $phone1 = $request->phone1;
            $phone2 = $request->phone2;
            $data = [
                'customer_group_id' => $request->customer_group_id,
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'email' => $request->email,
                'facebook' => $request->facebook,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'address' => $request->address,
                'customer_source_id' => $request->customer_source_id,
                'customer_refer_id' => $request->customer_refer_id,
                //            'customer_avatar'
                'note' => $request->note,
                'is_actived' => $request->is_actived,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'postcode' => $request->postcode,
                'customer_type' => $request->customer_type,
                'tax_code' => $request->tax_code ?? '',
                'representative' => $request->representative ?? '',
                'hotline' => $request->hotline ?? '',
                'profile_code' => $request->profile_code
            ];

            //Define sẵn 10 trường thông tin kèm theo
            for ($i = 1; $i <= 10; $i++) {
                $custom = "custom_$i";
                $data["custom_$i"] = isset($request->$custom) ? $request->$custom : null;
            }

            if ($request->year != null && $request->month != null && $request->day != null) {
                if ($request->month < 10) {
                    $month = '0' . $request->month;
                } else {
                    $month = $request->month;
                }
                if ($request->day < 10) {
                    $day = '0' . $request->day;
                } else {
                    $day = $request->day;
                }
                $birthday = $request->year . '-' . $month . '-' . $day;
                $data['birthday'] = $birthday;
                if ($birthday > date("Y-m-d")) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Ngày sinh không hợp lệ')
                    ]);
                }
            }

            //Kiểm tra sđt 1 đã tồn tại chưa
            $test_phone1 = $this->customer->testPhone($phone1, $id);
            //Kiểm tra sđt 2 đã tồn tại chưa
            $test_phone2 = $this->customer->testPhone($phone2, $id);

            if (!empty($test_phone1)) {
                //Kiểm tra KH đó có ở chi nhánh này không
                $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($test_phone1['customer_id'], Auth()->user()->branch_id);

                if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch', session('routeList'))) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại 1 đã tồn tại')
                    ]);
                }

                //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
                $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

                if ($getInsertBranch == 1) {
                    //Tự động insert chi nhánh và lấy customer_id ra
                    $mCustomerBranch->add([
                        'customer_id' => $test_phone1['customer_id'],
                        'branch_id' => Auth()->user()->branch_id
                    ]);

                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại 1 đã tồn tại')
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này')
                    ]);
                }
            }

            if (!empty($phone2) && !empty($test_phone2)) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số điện thoại 2 đã tồn tại')
                ]);
            }

            if ($request->customer_avatar_upload != null) {
                $data['customer_avatar'] = $request->customer_avatar_upload;
            } else {
                $data['customer_avatar'] = $request->customer_avatar;
            }

            // save action "Update"
            $mCustomerLog = new CustomerLogTable();
            $dataCustomerLog = [
                'object_type' => 'customer',
                'object_id' => $id,
                'key_table' => 'customers',
                'title' => __('Chỉnh sửa khách hàng'),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            $idLog = $mCustomerLog->createLog($dataCustomerLog);

            $infoCustomer = $this->customer->getItemLog($id)->toArray();
            // save log customer update
            $this->_saveCustomerLog($infoCustomer, $data, $idLog, 'info');

            //Chỉnh sửa khách hàng
            $this->customer->edit($data, $id);

            $mCustomerFile = new CustomerFileTable();

            // get current image
            $currImageCustomer = $mCustomerFile->getArrayCustomerFile($id, 'image')->toArray();
            // get current image
            $currFileCustomer = $mCustomerFile->getArrayCustomerFile($id, 'file')->toArray();
            //Xoá tất cả ảnh/file
            $mCustomerFile->removeFile($id);

            $arrImageCustomer = [];
            $arrFileCustomer = [];

            $newImageCustomer = [];
            if (isset($request->imageCustomer) && count($request->imageCustomer)) {
                foreach ($request->imageCustomer as $v) {
                    $arrImageCustomer[] = [
                        "customer_id" => $id,
                        "type" => "image",
                        "link" => $v['path'],
                        "file_name" => $v['file_name'],
                        "file_type" => $v['type'],
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ];
                    $newImageCustomer[] = $v;
                }
            }
            $this->_saveCustomerLog(array_column($currImageCustomer, 'link'), $newImageCustomer, $idLog, 'encode', 'image_customer');
            //Thêm ảnh kèm theo
            $mCustomerFile->insert($arrImageCustomer);


            $newFileCustomer = [];
            if (isset($request->fileCustomer) && count($request->fileCustomer)) {
                foreach ($request->fileCustomer as $v) {
                    $arrFileCustomer[] = [
                        "customer_id" => $id,
                        "type" => "file",
                        "link" => $v['path'],
                        "file_name" => $v['file_name'],
                        "file_type" => $v['type'],
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ];
                    $newFileCustomer[] = $v;
                }
            }
            $this->_saveCustomerLog(array_column($currFileCustomer, 'link'), $newFileCustomer, $idLog, 'encode', 'file_customer');
            //Thêm file kèm theo
            $mCustomerFile->insert($arrFileCustomer);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Cập nhật khách hàng thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    public function updateCustomerFromOncall(Request $request)
    {
        $mCustomerBranch = new CustomerBranchTable();
        $mConfig = new ConfigTable();
        $mCustomerLog = new CustomerLogTable();

        $id = $request->id;
        $phone1 = $request->phone1;
        $data = [
            //            'customer_group_id' => $request->customer_group_id,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'phone1' => $request->phone1,
            'email' => $request->email,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'address' => $request->address,
            //            'customer_source_id' => $request->customer_source_id,
            //            'customer_refer_id' => $request->customer_refer_id,
            //            'note' => $request->note,
            //            'is_actived' => $request->is_actived,
            'updated_by' => Auth::id(),
            //            'postcode' => $request->postcode,
            //            'customer_type' => $request->customer_type,
            //            'tax_code' => $request->tax_code ?? '',
            //            'representative' => $request->representative ?? '',
            //            'hotline' => $request->hotline ?? '',
            //            'facebook' => $request->facebook,
        ];

        if ($request->year != null && $request->month != null && $request->day != null) {
            if ($request->month < 10) {
                $month = '0' . $request->month;
            } else {
                $month = $request->month;
            }
            if ($request->day < 10) {
                $day = '0' . $request->day;
            } else {
                $day = $request->day;
            }
            $birthday = $request->year . '-' . $month . '-' . $day;
            $data['birthday'] = $birthday;
            if ($birthday > date("Y-m-d")) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày sinh không hợp lệ')
                ]);
            }
        }

        //Kiểm tra sđt 1 đã tồn tại chưa
        $test_phone1 = $this->customer->testPhone($phone1, $id);

        if (!empty($test_phone1)) {
            //Kiểm tra KH đó có ở chi nhánh này không
            $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($test_phone1['customer_id'], Auth()->user()->branch_id);

            if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch', session('routeList'))) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số điện thoại 1 đã tồn tại')
                ]);
            }

            //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
            $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

            if ($getInsertBranch == 1) {
                //Tự động insert chi nhánh và lấy customer_id ra
                $mCustomerBranch->add([
                    'customer_id' => $test_phone1['customer_id'],
                    'branch_id' => Auth()->user()->branch_id
                ]);

                return response()->json([
                    'error' => true,
                    'message' => __('Số điện thoại 1 đã tồn tại')
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này')
                ]);
            }
        }

        // save action "Update"
        $dataCustomerLog = [
            'object_type' => 'customer',
            'object_id' => $id,
            'key_table' => 'customers',
            'title' => __('Chỉnh sửa khách hàng'),
            'created_by' => Auth()->id(),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $idLog = $mCustomerLog->createLog($dataCustomerLog);

        $infoCustomer = $this->customer->getItemLog($id)->toArray();
        // save log customer update
        $this->_saveCustomerLog($infoCustomer, $data, $idLog, 'info');

        //Chỉnh sửa khách hàng
        $this->customer->edit($data, $id);

        return response()->json([
            'error' => false,
            'message' => __('Cập nhật khách hàng thành công')
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAction($id)
    {
        $this->customer->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * Kiểm tra mã thẻ dịch vụ khi kích hoạt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCardAction(Request $request)
    {
        $mConfig = new ConfigTable();

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        //Lấy thông tin thẻ dịch vụ theo chi nhánh or liên chi nhánh
        $list_card = $this->service_card_list->searchActiveCard($request->card, $branchId);

        $data_card = [];
        if ($list_card != null) {
            if ($list_card['date_using'] != 0) {
                $date_now_int = strftime("%d/%m/%Y", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $list_card['date_using'] . 'days'))));
            } else {
                $date_now_int = 0;
            }

            $data_card = [
                'card_code' => $list_card['code'],
                'service_card_list_id' => $list_card['service_card_list_id'],
                'name_code' => $list_card['name_code'],
                'card_type' => $list_card['card_type'],
                'money' => $list_card['money'],
                'name_sv' => $list_card['name_sv'],
                'expired_day' => $date_now_int,
                'number_using' => $list_card['number_using'],
                'service_card_id' => $list_card['service_card_id']
            ];
        }
        return response()->json([
            'data_card' => $data_card,
            'day' => Carbon::now()->format('d/m/Y')
        ]);
    }

    /**
     * Kích hoạt thẻ dịch vụ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitActiveCardAction(Request $request)
    {
        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();

        $customer_id = $request->customer_id;
        //Lấy thông tin nhân viên
        $staff = $this->staff->getItem(Auth::id());
        //Lấy table data thẻ cần kích hoạt
        $table_card = $request->table_card;
        if ($table_card != null) {
            $aData = array_chunk($table_card, 8, false);
            foreach ($aData as $key => $value) {
                $data = [
                    'service_card_id' => $value[6],
                    'card_code' => $value[0],
                    'customer_id' => $customer_id,
                    'actived_date' => Carbon::createFromFormat('d/m/Y', $value[1])->format('Y-m-d'),
                    'updated_by' => Auth::id(),
                    'is_actived' => 1,
                    'branch_id' => $staff['branch_id'],
                    'number_using' => $value[5],
                    'count_using' => 0,
                    'created_by' => Auth::id(),
                    'money' => $value[4]
                ];

                if ($value[2] != 0) {
                    $data['expired_date'] = Carbon::createFromFormat('d/m/Y', $value[2])->format('Y-m-d');
                }
                if ($value[3] == 'money') {
                    $data['number_using'] = 1;
                    $data['count_using'] = 1;
                    //Lấy thông tin khách hàng
                    $list_customer = $this->customer->getItem($customer_id);
                    //Update lại tổng tiền KH
                    $this->customer->edit([
                        'account_money' => $list_customer['account_money'] + $value[4]
                    ], $customer_id);
                    //Lưu log + tiền
                    $mBranchMoneyLog->add([
                        "customer_id" => $request->customer_id,
                        "branch_id" => $staff['branch_id'],
                        "source" => "member_card",
                        "type" => 'plus',
                        "money" => $value[4],
                        "screen" => 'active_card',
                        "screen_object_code" => $value[0]
                    ]);
                }
                //Thêm thẻ dv đã active
                $this->customer_service_card->add($data);

                //Update active trong service_card_list
                $this->service_card_list->edit([
                    'is_actived' => 1,
                    'updated_by' => Auth::id(),
                    'actived_at' => date('Y-m-d')
                ], $value[7]);
            }
            return response()->json([
                'success' => 1,
                'message' => __('Active thẻ dịch vụ thành công')
            ]);
        } else {
            return response()->json([
                'card_null' => 1,
                'message' => __('Thẻ dịch vụ rỗng')
            ]);
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelAction()
    {

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new CustomerExport(), 'customer.xlsx');
    }

    /**
     * Import danh sách khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function importExcelAction(Request $request)
    {

        $file = $request->file('file');

        if (isset($file)) {
            $typeFileExcel = $file->getClientOriginalExtension();
            if ($typeFileExcel == "xlsx") {
                $reader = ReaderFactory::create(Type::XLSX);
                $reader->open($file);

                $mCustomerSource = app()->get(CustomerSourceTable::class);
                $mCustomer = app()->get(CustomerTable::class);
                $mProvince = app()->get(ProvinceTable::class);
                $mDistrict = app()->get(DistrictTable::class);
                $mWard = app()->get(WardTable::class);

                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $key => $row) {
                        if ($key > 1) {
                            $colDateUse = strip_tags(isset($row[1]) ? $row[1] : '');
                            $colName = strip_tags(isset($row[2]) ? $row[2] : '');
                            $colGender = strip_tags(isset($row[3]) ? $row[3] : '');
                            $colGroup = strip_tags(isset($row[4]) ? $row[4] : '');
                            $colSource = strip_tags(isset($row[5]) ? $row[5] : '');
                            $colPhone = strip_tags(isset($row[6]) ? $row[6] : '');
                            $colEmail = strip_tags(isset($row[7]) ? $row[7] : '');
                            $colBirthday = strip_tags(isset($row[8]) ? $row[8] : '');
                            $colProvince = strip_tags(isset($row[9]) ? $row[9] : '');
                            $colDistrict = strip_tags(isset($row[10]) ? $row[10] : '');
                            $colWard = strip_tags(isset($row[11]) ? $row[11] : '');
                            $colAddress = strip_tags(isset($row[12]) ? $row[12] : '');
                            $colProfileCode = strip_tags(isset($row[13]) ? $row[13] : '');
                            $colNote = strip_tags(isset($row[14]) ? $row[14] : '');

                            //check sdt dưới 10 số thì loại
                            if (strlen($colPhone) >= 10) {
                                //format phone
                                $change_array = [
                                    '016' => '03',
                                    '0120' => '070',
                                    '0121' => '079',
                                    '0122' => '077',
                                    '0126' => '076',
                                    '0128' => '078',
                                    '0123' => '083',
                                    '0124' => '084',
                                    '0125' => '085',
                                    '0127' => '081',
                                    '0129' => '082',
                                    '0188' => '058',
                                    '0186' => '056',
                                    '0199' => '059'
                                ];

                                $phone = $colPhone;

                                foreach ($change_array as $key => $value) {
                                    if (strstr($phone, $key)) {
                                        $phone = str_replace($key, $value, $phone);
                                    }
                                }

                                //Lấy thông tin nhóm khách hàng
                                $check_customer_group = $this->customer_group->testGroupName(str_slug($colGroup));

                                if (isset($check_customer_group)) {
                                    $id_group = $check_customer_group['customer_group_id'];
                                } else {
                                    $id_group = $this->customer_group->add([
                                        'group_name' => $colGroup,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'slug' => str_slug($colGroup)
                                    ]);
                                }

                                //Lấy thông tin nguồn khách hàng
                                $getInfo = $mCustomerSource->testCustomerSourceName(str_slug($colSource));

                                if ($getInfo != null) {
                                    $idSource = $getInfo['customer_source_id'];
                                } else {
                                    //Thêm nguồn KH
                                    $idSource = $mCustomerSource->add([
                                        'customer_source_name' => $colSource,
                                        'slug' => str_slug($colSource),
                                        'created_by' => Auth()->id(),
                                        'updated_by' => Auth()->id()
                                    ]);
                                }


                                $gender = $colGender == 1 ? 'male' : 'female';
                                //Format data ngày sinh
                                $colBirthday = $colBirthday != '' ? Carbon::createFromFormat('d/m/Y', $colBirthday)->format('Y-m-d') : null;
                                //Format data ngày sử dụng dịch vụ
                                $colDateUse = $colDateUse != '' ? Carbon::createFromFormat('d/m/Y', $colDateUse)->format('Y-m-d') : Carbon::now()->format('Y-m-d H:i:s');
                                //Kiểm tra email đã tồn tại chưa
                                $infoEmail = $mCustomer->getInfoByEmail($colEmail);

                                if ($infoEmail != null) {
                                    continue;
                                }

                                $provinceId = null;
                                $districtId = null;
                                $wardId = null;
                                //Lấy thông tin tỉnh/thành
                                $getProvince = $mProvince->getProvinceByName($colProvince);

                                if ($getProvince != null) {
                                    $provinceId = $getProvince['provinceid'];
                                }

                                if ($provinceId != null) {
                                    //Lấy thông tin quận/huyện
                                    $getDistrict = $mDistrict->getDistrictByName($provinceId, $colDistrict);

                                    if ($getDistrict != null) {
                                        $districtId = $getDistrict['districtid'];
                                    }
                                }

                                if ($districtId != null) {
                                    //Lấy thông tin phường/xã
                                    $getWard = $mWard->getWardByName($districtId, $colWard);

                                    if ($getWard != null) {
                                        $wardId = $getWard['ward_id'];
                                    }
                                }

                                //check sdt tồn tại
                                $check_phone = $this->customer->getCusPhone($phone);

                                if (!isset($check_phone)) {
                                    //Thêm khách hàng
                                    $id_add = $this->customer->add([
                                        'full_name' => $colName,
                                        'branch_id' => Auth::user()->branch_id,
                                        'phone1' => $phone,
                                        'gender' => $gender,
                                        'customer_group_id' => $id_group,
                                        'customer_source_id' => $idSource,
                                        'birthday' => $colBirthday,
                                        'address' => $colAddress,
                                        'note' => $colNote,
                                        'is_actived' => 1,
                                        'created_at' => $colDateUse,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'profile_code' => $colProfileCode,
                                        'email' => $colEmail,
                                        'province_id' => $provinceId,
                                        'district_id' => $districtId,
                                        'ward_id' => $wardId
                                    ]);

                                    if ($id_add < 10) {
                                        $id_add = '0' . $id_add;
                                    }

                                    //Cập nhật mã khách hàng
                                    $this->customer->edit([
                                        'customer_code' => 'KH_' . date('dmY') . $id_add
                                    ], $id_add);
                                } else {
                                    //Cập nhật khách hàng
                                    $this->customer->edit([
                                        'full_name' => $colName,
                                        'gender' => $gender,
                                        'customer_group_id' => $id_group,
                                        'customer_source_id' => $idSource,
                                        'birthday' => $colBirthday,
                                        'address' => $colAddress,
                                        'note' => $colNote,
                                        'created_at' => $colDateUse,
                                        'updated_by' => Auth::id(),
                                        'is_actived' => 1,
                                        'is_deleted' => 0,
                                        'profile_code' => $colProfileCode,
                                        'email' => $colEmail,
                                        'province_id' => $provinceId,
                                        'district_id' => $districtId,
                                        'ward_id' => $wardId
                                    ], $check_phone['customer_id']);
                                }
                            }
                        }
                    }
                }

                $reader->close();
            }
            return response()->json([
                'success' => 1,
                'message' => __('Import thông tin khách hàng thành công')
            ]);
        }
    }

    /**
     *  Modal form thẻ liệu trình
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function formProcessCardAction(Request $request)
    {
        $optionServiceCard = $this->service_card->getOption();

        $view = \View::make('admin::customer.pop.modal-process-card', [
            'customer_id' => $request->id,
            'optionServiceCard' => $optionServiceCard
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    /**
     *  Chọn dịch vụ load ngày kích hoạt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseServiceCardAction(Request $request)
    {
        $service_card = $this->service_card->getItemDetail($request->service_card_id);
        return response()->json([
            'service_card' => $service_card
        ]);
    }

    /**
     * Chọn ngày kích hoạt load ngày hết hạn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeActiveDateAction(Request $request)
    {
        $service_card = $this->service_card->getItemDetail($request->service_card_id);
        $format_active_date = Carbon::createFromFormat('d/m/Y', $request->actived_date)->format('Y-m-d');
        if ($service_card['date_using'] != 0) {
            $date = Carbon::parse($format_active_date)->addDay($service_card['date_using']);
            $expired_date = date_format($date, "d/m/Y");
        } else {
            $expired_date = 0;
        }
        return response()->json([
            'expired_date' => $expired_date
        ]);
    }

    /**
     * Tạo thẻ liệu trình
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitProcessCard(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'service_card_id' => 'required',
            'actived_date' => 'required',
            'expired_date' => 'required',
            'count_using' => 'required|numeric',
            'end_using' => 'required|numeric'
        ], [
            'service_card_id.required' => __('Hãy chọn thẻ dịch vụ'),
            'actived_date.required' => __('Hãy chọn ngày kích hoạt'),
            'expired_date.required' => __('Hãy chọn ngày hết hạn'),
            'count_using.required' => __('Hãy nhập số lần sử dụng'),
            'count_using.numeric' => __('Số lần sử dụng không hợp lệ'),
            'end_using.required' => __('Hãy nhập số lần còn lại'),
            'end_using.numeric' => __('Số lần còn lại không hợp lệ')
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                '_error' => $validator->errors()->all(),
                'message' => __('Thêm thất bại')
            ]);
        } else {
            $actived_date = Carbon::createFromFormat('d/m/Y', $request->actived_date)->format('Y-m-d');
            $expired_date = Carbon::createFromFormat('d/m/Y', $request->expired_date)->format('Y-m-d');

            if ($actived_date > date('Y-m-d')) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày kích hoạt vượt quá ngày hiện tại')
                ]);
            }

            if ($actived_date > $expired_date) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày kích hoạt không hợp lệ')
                ]);
            }

            $service_card = $this->service_card->getItemDetail($request->service_card_id);
            $code = $this->code->generateCardListCode();

            $arr_card_list = [
                'branch_id' => Auth::user()->branch_id,
                'service_card_id' => $request->service_card_id,
                'code' => $code,
                'order_code' => 'THEMBANGTAY',
                'price' => $service_card['price'],
                'is_actived' => 1
            ];

            //insert service card list
            $this->service_card_list->add($arr_card_list);

            $arr_customer_card = [
                'customer_id' => $request->customer_id,
                'branch_id' => Auth::user()->branch_id,
                'card_code' => $code,
                'service_card_id' => $request->service_card_id,
                'actived_date' => $actived_date,
                'expired_date' => $expired_date,
                'number_using' => intval($request->count_using) + intval($request->end_using),
                'count_using' => $request->count_using
            ];
            if ($service_card['service_card_type'] == 'money') {
                $arr_customer_card['money'] = $service_card['money'];

                $customer = $this->customer->getItem($request->customer_id);
                $data = [
                    'account_money' => $customer['account_money'] + $service_card['money']
                ];
                //Cập nhật tiền trong tài khoản khách hàng
                $this->customer->edit($data, $request->customer_id);

                //Check tài khoản KH theo chi nhánh
                $customer_branch = $this->customer_branch_money->getPriceBranch($request->customer_id, Auth::user()->branch_id);
                if ($customer_branch != null) {
                    $data_money_edit = [
                        'total_money' => $customer_branch['total_money'] + $service_card['money'],
                        'balance' => $customer_branch['total_money'] + $service_card['money'] - $customer_branch['total_using'],
                        'updated_by' => Auth::id()
                    ];
                    $this->customer_branch_money->edit($data_money_edit, $request->customer_id, Auth::user()->branch_id);
                } else {
                    $data_money = [
                        'total_money' => $service_card['money'],
                        'total_using' => 0,
                        'balance' => $service_card['money'],
                        'customer_id' => $request->customer_id,
                        'branch_id' => Auth::user()->branch_id,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    $this->customer_branch_money->add($data_money);
                }
            }
            //insert customer service card
            $this->customer_service_card->add($arr_customer_card);
            return response()->json([
                'error' => false,
                'message' => __('Tạo thẻ liệu trình thành công')
            ]);
        }
    }

    /**
     * Nhập công nợ ban đầu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enterDebtAction(Request $request)
    {
        $amountDebt = $request->amountDebt;
        $idCustomer = $request->idCustomer;
        $data = [
            'customer_id' => $idCustomer,
            'status' => 'unpaid',
            'amount' => str_replace(',', '', $amountDebt),
            'amount_paid' => 0,
            'order_id' => 0,
            'staff_id' => Auth::id(),
            "branch_id" => Auth::user()->branch_id,
            'debt_type' => 'first',
            'created_by' => Auth::id(),
            'note' => strip_tags($request->note),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::id()
        ];
        $debt_id = $this->customer_debt->add($data);
        //update debt code
        $day_code = date('dmY');
        if ($debt_id < 10) {
            $debt_id = '0' . $debt_id;
        }
        $debt_code = [
            'debt_code' => 'CN_' . $day_code . $debt_id
        ];
        $this->customer_debt->edit($debt_code, $debt_id);

        if ($debt_id) {
            return response()->json([
                'error' => false,
                'message' => __('Nhập công nợ thành công.')
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => __('Nhập công nợ thất bại.')
            ]);
        }
    }

    /**
     * Form quy đổi tiền hoa hồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function commissionAction(Request $request)
    {
        $view = \View::make('admin::customer.pop.modal-commission', [
            'customer_id' => $request->customer_id,
            'commission_money' => $request->commission_money
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Quy đổi tiền
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitCommissionAction(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'money' => 'required',
            'type' => 'required'
        ], [
            'money.required' => __('Hãy nhập tiền quy đổi'),
            'type.required' => __('Hãy chọn hình thức quy đổi'),
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                '_error' => $validator->errors()->all(),
                'message' => __('Quy đổi thất bại')
            ]);
        } else {
            $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);
            $mConfig = app()->get(ConfigTable::class);
            $mCustomer = app()->get(CustomerTable::class);

            $branchId = null;
            //Lấy cấu hình 1 chi nhánh or liên chi nhánh
            $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

            if ($configBranch == 0) {
                //Lấy chi nhánh của nv đăng nhập
                $branchId = Auth()->user()->branch_id;
            }
            //Tiền cần quy đổi
            $money = str_replace(",", "", $request->money);
            //Lấy tổng tiền hoa hồng cộng vào
            $totalPlusCommission = $mBranchMoneyLog->getTotalCommission($request->customer_id, $branchId, self::PLUS);
            //Lấy tổng tiền hoa hồng trừ ra
            $totalSubtractCommission = $mBranchMoneyLog->getTotalCommission($request->customer_id, $branchId, self::SUBTRACT);
            $commissionMoney = floatval($totalPlusCommission['total']) - floatval($totalSubtractCommission['total']);

            if ($money > $commissionMoney) {
                return response()->json([
                    'error' => true,
                    '_error' => __('Tiền quy đổi lớn hơn tiền còn lại'),
                    'message' => __('Quy đổi thất bại')
                ]);
            }

            //            $data_customer_money = [
            //                'commission_money' => intval($get_customer_money['commission_money']) - intval($money)
            //            ];
            //            if ($request->type == 'tranfer_money') {
            //                $data_customer_money['total_money'] = intval($get_customer_money['total_money']) + intval($money);
            //                $data_customer_money['balance'] = intval($get_customer_money['balance']) + intval($money);
            //            }
            //            //update commission money
            //            $this->customer_branch_money->edit($data_customer_money, $request->customer_id, Auth::user()->branch_id);


            if ($request->type == 'tranfer_money') {
                //Lấy thông tin KH
                $customer = $mCustomer->getItem($request->customer_id);
                //Cập nhật tiền KH
                $mCustomer->edit([
                    'account_money' => $customer['account_money'] + $money
                ], $request->customer_id);
                //Lưu log + tiền
                $mBranchMoneyLog->add([
                    "customer_id" => $request->customer_id,
                    "branch_id" => Auth()->user()->branch_id,
                    "source" => "member_money",
                    "type" => 'plus',
                    "money" => $money,
                    "screen" => 'exchange',
                ]);
            }

            //Lưu log - tiền hoa hồng
            $mBranchMoneyLog->add([
                "customer_id" => $request->customer_id,
                "branch_id" => Auth()->user()->branch_id,
                "source" => "commission",
                "type" => 'subtract',
                "money" => $money,
                "screen" => 'exchange',
            ]);

            //insert commission log
            $this->commission_log->add([
                'customer_id' => $request->customer_id,
                'branch_id' => Auth::user()->branch_id,
                'money' => $money,
                'type' => $request->type,
                'note' => $request->note,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'error' => false,
                'message' => __('Quy đổi thành công')
            ]);
        }
    }

    public function getInfoCustomerDetail(Request $request)
    {
        $id = $request->id;
        //Lấy thông tin KH
        $getItem = $this->customer->getItem($id);
        //Lấy công nợ của KH
        $amountDebt = $this->customer_debt->getItemDebt($id);

        $debt = 0;
        if (count($amountDebt) > 0) {
            foreach ($amountDebt as $item) {
                $debt += $item['amount'] - $item['amount_paid'];
            }
        }

        $getItem['debt'] = $debt;

        return response()->json($getItem);
    }

    /**
     * Cộng điểm cho sự kiện sinh nhật KH
     * @return \Illuminate\Http\JsonResponse
     */
    public function loyaltyEventBirthday()
    {
        DB::beginTransaction();
        try {
            //Cộng điểm loyalty
            $listCustomer = $this->customer->getBirthdays();
            if (count($listCustomer) > 0) {
                foreach ($listCustomer as $item) {
                    $this->loyalty->plusPointEvent(
                        [
                            'customer_id' => $item['customer_id'],
                            'rule_code' => 'birthday',
                            'object_id' => ''
                        ]
                    );
                }
            }
            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function exportExcelAll(Request $request)
    {
        $arr_customer = [];
        //Danh sách khách hàng
        $list_customer = $this->customer->getAllCustomer([
            'search' => $request->search_export,
            'customer_group_id' => $request->customer_group_id_export,
            'created_at' => $request->created_at_export
        ]);

        foreach ($list_customer as $item) {
            $gender = '';
            if ($item['gender'] == 'other') {
                $gender = 'Khác';
            } elseif ($item['gender'] == 'male') {
                $gender = 'Nam';
            } elseif ($item['gender'] == 'female') {
                $gender = 'Nữ';
            }
            // lẤy danh sách sản phẩm, dịch vụ đã mua
            $list_product = $this->order_detail->getObjectByCustomer($item['customer_id'], 'product');
            $list_service = $this->order_detail->getObjectByCustomer($item['customer_id'], 'service');
            $name_product = '';
            $name_service = '';
            if ($list_product != null && count($list_product) > 0) {
                foreach ($list_product as $product) {
                    $name_product = $name_product . $product['object_name'] . chr(13);
                }
            }
            if ($list_service != null && count($list_service) > 0) {
                foreach ($list_service as $service) {
                    $name_service = $name_service . $service['object_name'] . chr(13);
                }
            }

            //Lấy công nợ của khách hàng
            $amountDebt = $this->customer_debt->getItemDebt($item['customer_id']);

            $debt = 0;

            if (count($amountDebt) > 0) {
                foreach ($amountDebt as $v) {
                    $debt += $v['amount'] - $v['amount_paid'];
                }
            }

            $arr_customer[] = [
                'customer_id' => $item['customer_id'],
                'customer_code' => $item['customer_code'],
                'profile_code' => $item['profile_code'],
                'name' => $item['full_name'],
                'phone' => $item['phone1'],
                'gender' => $gender,
                'birthday' => $item['birthday'] != null ? Carbon::parse($item['birthday'])->format('d/m/Y') : '',
                'email' => $item['email'],
                'address' => $item['address'],
                'service_name' => $this->customer_service_card->getCustomerCardAll($item['customer_id']),
                'member_level_name' => $item['member_level_name'],
                'list_product' => $name_product,
                'list_service' => $name_service,
                'note' => $item['note'],
                'created_at' => $item['created_at'],
                'refer_name' => $item['refer_name'],
                'debt' => $debt
            ];
        }
        //Data export
        $arr_data = [];
        foreach ($arr_customer as $key => $item) {
            $card = '';
            if (count($item['service_name']) > 0) {
                foreach ($item['service_name'] as $key1 => $item1) {
                    if ($key1 > 0) {
                        $card .= ' , ' . $item1['card_name'];
                    } else {
                        $card .= $item1['card_name'];
                    }
                }
            }
            $arr_data[] = [
                $key + 1,
                'customer_code' => $item['customer_code'],
                'profile_code' => $item['profile_code'],
                'name' => $item['name'],
                'phone' => $item['phone'],
                'birthday' => $item['birthday'],
                'email' => $item['email'],
                'address' => $item['address'],
                'member_level_name' => $item['member_level_name'],
                'card' => $card,
                'list_product' => $item['list_product'],
                'list_service' => $item['list_service'],
                'debt' => floatval($item['debt']),
                'refer_name' => $item['refer_name'],
                'created_at' => Carbon::parse($item['created_at'])->format('d/m/Y H:i'),
                'note' => $item['note']
            ];
        }
        $heading = [
            __('STT'),
            __('Mã khách hàng'),
            __('Mã hồ sơ'),
            __('Họ & Tên'),
            __('Số điện thoại'),
            __('Ngày sinh'),
            __('Email'),
            __('Địa chỉ'),
            __('Hạng thành viên'),
            __('Thẻ dịch vụ'),
            __('Sản phẩm'),
            __('Dịch vụ'),
            __('Công nợ'),
            __('Người giới thiệu'),
            __('Ngày tạo'),
            __('Ghi chú')
        ];
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ExportFile($heading, $arr_data), 'customer.xlsx');
    }

    /**
     * lay thong tin khach hang va dia chi mac dinh (neu co)
     * @param Request $request
     */
    public function getCustomerAndDefaultContact(Request $request)
    {
        $idCus = $request->id;
        return $this->customer->getCustomerAndDefaultContact($idCus);
    }

    /**
     * Sử dụng thẻ liệu trình
     *
     * @param Request $request
     * @return mixed
     */
    public function usingCardAction(Request $request)
    {
        return $this->customer->usingCard($request->all());
    }

    /**
     * Thêm nhanh loại thông tin
     *
     * @param Request $request
     * @return mixed
     */
    public function addInfoTypeAction(Request $request)
    {
        return $this->customer->addInfoType($request->all());
    }

    /**
     * Show modal thêm chi nhánh được xem
     *
     * @param Request $request
     * @return mixed
     */
    public function modalCustomerBranch(Request $request)
    {
        return $this->customer->modalCustomerBranch($request->all());
    }

    /**
     * Thêm chi nhánh được xem
     *
     * @param Request $request
     * @return mixed
     */
    public function saveCustomerBranch(Request $request)
    {
        return $this->customer->saveCustomerBranch($request->all());
    }


    protected function _saveCustomerLog($cur, $new, $id, $type, $keyData = '')
    {
        $mCustomerLogUpdate = new CustomerLogUpdateTable();
        $dataLog = [];
        if ($type == 'info') {
            // save each field info
            foreach ($cur as $k => $v) {
                if (isset($new[$k])) {
                    if ($cur[$k] != $new[$k]) {
                        $dataLog[] = [
                            'customer_log_id' => $id,
                            'key_table' => 'customers',
                            'key' => $k,
                            'value_old' => $cur[$k],
                            'value_new' => $new[$k],
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        ];
                    }
                }
            }
            $mCustomerLogUpdate->insertLog($dataLog);
        } else {
            // save json_encode field
            if (json_encode($cur) != json_encode($new)) {
                $dataLog[] = [
                    'customer_log_id' => $id,
                    'key_table' => 'customers',
                    'key' => $keyData,
                    'value_old' => json_encode($cur),
                    'value_new' => json_encode($new),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
            }
            $mCustomerLogUpdate->insertLog($dataLog);
        }
    }

    /**
     * Cập nhật thông tin khách hàng
     * @param Request $request
     */
    public function customerUpdateWard(Request $request)
    {
        $data = $this->customer->customerUpdateWard($request->all());
    }

    /**
     * DS phiếu thu của KH
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getListReceiptAction(Request $request)
    {
        //Lấy ds phiếu thu của KH
        $list = $this->customer->getReceiptCustomer($request->all());

        return view('admin::customer.list-receipt-customer', [
            'LIST' => $list,
            'page' => $request->page
        ]);
    }

    /**
     * Load tab trong chi tiết KH
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadTabDetailAction(Request $request)
    {
        //Xử lý lấy data của từng tab
        $data = $this->customer->loadTabDetail($request->all());
       
        $view = '';

        //Xử lý trả view của từng tab
        switch ($request->tab_view) {
            case 'appointment':
                $view = \View::make('admin::customer.tab-detail.appointment', $data)->render();

                break;
            case 'order':
                $view = \View::make('admin::customer.tab-detail.order', $data)->render();

                break;

            case 'service_card':
                $view = \View::make('admin::customer.tab-detail.service-card', $data)->render();

                break;
            case 'debt':
                $view = \View::make('admin::customer.tab-detail.debt', $data)->render();
                
                break;
            case 'order_commission':
                $view = \View::make('admin::customer.tab-detail.order-commission', $data)->render();

                break;

            case 'commission_log':
                $view = \View::make('admin::customer.tab-detail.commission-log', $data)->render();

                break;
            case 'loyalty':
                $view = \View::make('admin::customer.tab-detail.loyalty', $data)->render();

                break;
            case 'attach_info':
                $view = \View::make('admin::customer.tab-detail.attach-info', $data)->render();

                break;
            case 'customer_care':
                $view = \View::make('admin::customer.tab-detail.customer-care', $data)->render();

                break;
            case 'receipt':
                $view = \View::make('admin::customer.tab-detail.receipt', $data)->render();

                break;
            case 'warranty_card':
                $view = \View::make('admin::customer.tab-detail.warranty-card', $data)->render();

                break;
            case 'info':
                $view = \View::make('admin::customer.tab-detail.info', $data)->render();

                break;
            case 'contact':
                $view = \View::make('admin::customer.tab-detail.person-contact', $data)->render();

                break;
            case 'note':
                $view = \View::make('admin::customer.tab-detail.note', $data)->render();

                break;
            case 'file':
                $view = \View::make('admin::customer.tab-detail.file', $data)->render();

                break;
            case 'deal':
                $view = \View::make('admin::customer.tab-detail.deals', $data)->render();

                break;
        };
        
        return response()->json([
            'view' => $view
        ]);
    }

    /**
     * DS tích luỹ
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listLoyaltyAction(Request $request)
    {
        $data = $this->customer->listLoyalty($request->all());

        return view('admin::customer.tab-detail.list-loyalty', [
            'LIST' => $data,
            'page' => $request->page
        ]);
    }

    /*
    *Thêm phần comment cho ticket
    *Hieupc
    */

    /**
     * lấy danh sách bình luận
     * @param Request $request
     */
    public function getListComment(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->customer_id;
            $listComment = $this->customer->getListComment($id);
            $html = \View::make('admin::customer.append.list-customer-comment', [
                'listComment' => $listComment,
            ])->render();
            return response()->json([
                'html' => $html

            ]);
        }
    }

    /**
     * Thêm bình luận
     * @param Request $request
     */
    public function addComment(Request $request)
    {
        $param = $request->all();
        $data = $this->customer->addComment($param);
        return response()->json($data);
    }

    /**
     * hiển thị form comment
     * @param Request $request
     */
    public function showFormComment(Request $request)
    {
        $param = $request->all();
        $data = $this->customer->showFormComment($param);
        return response()->json($data);
    }

    /**
     * In công nợ khách hàng
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function printBillDebtAction(Request $request)
    {
        //Lấy dữ liệu in bill công nợ

        $data = $this->customer->getDataPrintBillDebt($request->all());

        return view('admin::customer.print-debt.view', $data);
    }

    /**
     * Show pop thanh toán nhanh công nợ
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function showPopQuickReceiptDebtAction(Request $request)
    {
        //Lấy dữ liệu view thanh toán nhanh công nợ
        $data = $this->customer->getDataQuickReceiptDebt($request->all());

        $view = \View::make('admin::customer.pop.modal-quick-receipt-debt', $data)->render();

        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Submit thanh toán nhanh
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submitQuickReceiptDebtAction(Request $request)
    {
        $data = $this->customer->submitQuickReceiptDebt($request->all());

        return response()->json($data);
    }

    /**
     * Danh sách người liên hệ
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function listPersonContactAction(Request $request)
    {
        //Lấy danh sách người liên hệ
        $list = $this->customer->listPersonContact($request->all());

        return view('admin::customer.tab-detail.person-contact.list', [
            'LIST' => $list['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Pop thêm người liên hệ
     *
     * @param Request $request
     * @return array
     */
    public function showPopCreatePersonContactAction(Request $request)
    {
        $data = $this->customer->getDataCreatePersonContact();

        $data['customer_id'] = $request->customer_id;

        $html = \View::make('admin::customer.tab-detail.person-contact.create', $data)->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Thêm người liên hệ
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storePersonContactAction(Request $request)
    {
        $data = $this->customer->storePersonContact($request->all());

        return response()->json($data);
    }

    /**
     * Pop chỉnh sửa người liên hệ
     *
     * @param Request $request
     * @return array
     */
    public function showPopEditPersonContactAction(Request $request)
    {
        $data = $this->customer->getDataEditPersonContact($request->all());

        $data['customer_id'] = $request->customer_id;

        $html = \View::make('admin::customer.tab-detail.person-contact.edit', $data)->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Chỉnh sửa người liên hệ
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePersonContactAction(Request $request)
    {
        $data = $this->customer->updatePersonContact($request->all());

        return response()->json($data);
    }

    /**
     * Danh sách ghi chú
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function listNoteAction(Request $request)
    {
        //Lấy danh sách người liên hệ
        $list = $this->customer->listNote($request->all());

        return view('admin::customer.tab-detail.note.list', [
            'LIST' => $list['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Pop thêm ghi chú
     *
     * @param Request $request
     * @return array
     */
    public function showPopCreateNoteAction(Request $request)
    {
        $html = \View::make('admin::customer.tab-detail.note.create', [
            'customer_id' => $request->customer_id
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Thêm ghi chú
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeNoteAction(Request $request)
    {
        $data = $this->customer->storeNote($request->all());

        return response()->json($data);
    }

    /**
     * Pop chỉnh sửa ghi chú
     *
     * @param Request $request
     * @return array
     */
    public function showPopEditNoteAction(Request $request)
    {
        $data = $this->customer->getDataEditNote($request->all());

        $html = \View::make('admin::customer.tab-detail.note.edit', $data)->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Chỉnh sửa ghi chú
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNoteAction(Request $request)
    {
        $data = $this->customer->updateNote($request->all());

        return response()->json($data);
    }

    /**
     * Danh sách tập tin
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function listFileAction(Request $request)
    {
        //Lấy danh sách tập tin
        $list = $this->customer->listFile($request->all());

        return view('admin::customer.tab-detail.file.list', [
            'LIST' => $list['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Danh sách tập tin
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function listDealsAction(Request $request)
    {
        //Lấy danh sách tập tin
        $list = $this->customer->listDeals($request->all());

        return view('admin::customer.tab-detail.list-deals', [
            'LIST' => $list['list'],
            'page' => $request->page
        ]);
    }
    /**
     * Show pop thêm tập tin
     *
     * @param Request $request
     * @return array
     */
    public function showPopCreateFileAction(Request $request)
    {
        $html = \View::make('admin::customer.tab-detail.file.create', [
            'customer_id' => $request->customer_id
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Thêm tập tin
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeFileAction(Request $request)
    {
        $data = $this->customer->storeFile($request->all());

        return response()->json($data);
    }

    /**
     * Show pop chỉnh sửa file
     *
     * @param Request $request
     * @return array
     */
    public function showPopEditFileAction(Request $request)
    {
        //Lấy data view edit
        $data = $this->customer->getDataEditFile($request->all());

        $html = \View::make('admin::customer.tab-detail.file.edit', $data)->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Chỉnh sửa tập tin
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateFileAction(Request $request)
    {
        $data = $this->customer->updateFile($request->all());

        return response()->json($data);
    }
}
