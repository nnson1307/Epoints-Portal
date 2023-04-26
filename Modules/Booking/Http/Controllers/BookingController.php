<?php

namespace Modules\Booking\Http\Controllers;

use App\Jobs\SaveLogZns;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Http\Api\SendNotificationApi;
use Modules\Booking\Models\DistrictTable;
use Modules\Booking\Models\ProvinceTable;
//use Modules\Booking\Models\RuleMenuTable;
use Modules\Booking\Models\RuleSettingOtherTable;
use Modules\Booking\Models\SpaInfoTable;
use Modules\Booking\Repositories\BannerSlider\BannerSliderRepositoryInterface;
use Modules\Booking\Repositories\Branch\BranchRepositoryInterface;
use Modules\Booking\Repositories\Loyalty\LoyaltyRepositoryInterface;
use Modules\Booking\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\Booking\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Booking\Repositories\Service\ServiceRepositoryInterface;
use Modules\Booking\Repositories\ServiceCategory\ServiceCategoryRepository;
use Modules\Booking\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Booking\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Booking\Repositories\TimeWork\TimeWorkRepositoryInterface;
use MyCore\Http\Response\ResponseFormatTrait;

use Modules\Booking\Repositories\Customer\CustomerRepository;
use Modules\Booking\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Booking\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepositoryInterface;
use App\Jobs\CheckMailJob;
use Modules\Booking\Repositories\SmsLog\SmsLogRepositoryInterface;

class BookingController extends Controller
{
    use ResponseFormatTrait;

    protected $spaInfo;
    protected $timeWork;
    protected $province;
    protected $district;
    protected $serviceCategory;
    protected $productCategory;
    protected $service;
    protected $productChild;
    protected $branch;
    protected $staff;
    protected $ruleSettingOther;
    protected $customer;
    protected $customer_appointment;
    protected $customer_appointment_detail;
    protected $smsLog;
    protected $bannerSlider;
//    protected $ruleMenu;


    public function __construct(
        SpaInfoRepositoryInterface $spaInfo,
        TimeWorkRepositoryInterface $timeWork,
        ServiceCategoryRepository $serviceCategory,
        ProductCategoryRepositoryInterface $productCategory,
        ServiceRepositoryInterface $service,
        ProductChildRepositoryInterface $productChild,
        BranchRepositoryInterface $branch,
        StaffRepositoryInterface $staff,
        CustomerAppointmentRepositoryInterface $customer_appointment,
        CustomerAppointmentDetailRepositoryInterface $customer_appointment_detail,
        CustomerRepository $customer,
        SmsLogRepositoryInterface $smsLog,
        BannerSliderRepositoryInterface $bannerSlider
    )
    {
        $this->spaInfo = $spaInfo;
        $this->timeWork = $timeWork;
        $this->province = new ProvinceTable();
        $this->district = new DistrictTable();
        $this->serviceCategory = $serviceCategory;
        $this->productCategory = $productCategory;
        $this->service = $service;
        $this->productChild = $productChild;
        $this->branch = $branch;
        $this->staff = $staff;
        $this->ruleSettingOther = new RuleSettingOtherTable();
        $this->customer = $customer;
        $this->customer_appointment = $customer_appointment;
        $this->customer_appointment_detail = $customer_appointment_detail;
        $this->smsLog = $smsLog;
        $this->bannerSlider = $bannerSlider;

//        $this->ruleMenu = new RuleMenuTable();
    }

    public function index()
    {
        echo 'test';
    }

    //Giới thiệu
    public function getAboutUsAction(Request $request)
    {
        $data = $this->validate($request, [
            'spa_id' => 'required|int'
        ]);
        $spaInfo = $this->spaInfo->getItem($request->spa_id);
        return $this->responseJson(CODE_SUCCESS, null, $spaInfo);
    }

    //Thời gian làm việc
    public function getTimeWork()
    {
        $data = $this->timeWork->getTimeWork();
        return $this->responseJson(CODE_SUCCESS, null, $data);
    }

    //Danh sách Tỉnh/Thành phố
    public function getProvinceAction()
    {
        $data = $this->province->getOptionProvince();
        return $this->responseJson(CODE_SUCCESS, null, $data);
    }

    //Danh sách Quận/Huyện theo Tình/Thành phố
    public function getDistrictAction(Request $request)
    {
        $data = $this->validate($request, [
            'province_id' => 'required'
        ]);
        $getOptionDistrict = $this->district->getOptionDistrict($request->province_id);
        $result = [];
        foreach ($getOptionDistrict as $item) {
            $result[] = [
                'district_id' => $item['districtid'],
                'name' => $item['type'] . ' ' . $item['name'],
            ];
        }
        return $this->responseJson(CODE_SUCCESS, null, $result);
    }

    //Option: Nhóm dịch vụ, nhóm sản phẩm.
    public function getServiceCategoryAction(Request $request)
    {
//        $data = $this->validate($request, [
//            'province_id' => 'required'
//        ]);

        $result['service_category'] = $this->serviceCategory->getOptionServiceCategory();
        $result['product_category'] = $this->productCategory->getAll();
        return $this->responseJson(CODE_SUCCESS, null, $result);
    }

    //Danh sách dịch vụ. Có filter theo nhóm. Phân trang.
    public function getServiceAction(Request $request)
    {
        $data = $this->validate($request, [
            'service_category_id' => 'nullable'
        ]);
        $filters = $request->only(['service_category_id', 'page', 'display']);
        $service = $this->service->getService($filters);

        return $this->responseJson(CODE_SUCCESS, null, $service);
    }

    //Chi tiết dịch vụ.
    public function getServiceDetailAction(Request $request)
    {
        $data = $this->validate($request, [
            'service_id' => 'required|int'
        ]);
        $serviceDetail = $this->service->getServiceDetail($request->service_id);

        return $this->responseJson(CODE_SUCCESS, null, $serviceDetail);
    }

    //Chi tiết group của dịch vụ
    public function getServiceDetailGroupAction(Request $request)
    {
        $data = $this->validate($request, [
            'service_id' => 'required|int'
        ]);
        $serviceDetailGroup = $this->service->getServiceDetailGroup($request->service_id);

        return $this->responseJson(CODE_SUCCESS, null, $serviceDetailGroup);
    }

    //Danh sách sản phẩm. Có filter theo nhóm.(product child). Phân trang.
    public function getProductAction(Request $request)
    {
        $data = $this->validate($request, [
            'product_category_id' => 'nullable'
        ]);
        $filters = $request->only(['product_category_id', 'page', 'display']);
        $product = $this->productChild->getProductChild($filters);
        return $this->responseJson(CODE_SUCCESS, null, $product);
    }

    //Chi tiết sản phẩm.(product child)
    public function getProductDetailAction(Request $request)
    {
        $data = $this->validate($request, [
            'product_child_id' => 'required|int'
        ]);
        $productDetail = $this->productChild->getProductChildById($request->product_child_id);

        return $this->responseJson(CODE_SUCCESS, null, $productDetail);
    }

    //Danh sách chi nhánh.
    public function getBranchAction(Request $request)
    {
        $data = $this->validate($request, [
            'province_id' => 'nullable',
            'district_id' => 'nullable'
        ]);
        $filters = $request->only(['province_id', 'district_id', 'page', 'display']);
        $branch = $this->branch->getBranch($filters);
        return $this->responseJson(CODE_SUCCESS, null, $branch);
    }

    //Đặt lịch giữ chố: Danh sách dịch vụ. Có filter theo id dịch vụ. Phân trang.
    public function bookingGetService(Request $request)
    {
        $data = $this->validate($request, [
            'branch_id' => 'required|int',
            'service_id' => 'nullable',
        ]);
        $filters = $request->only(['branch_id', 'service_id', 'page', 'display']);

        $service = $this->service->bookingGetService($filters);
        return $this->responseJson(CODE_SUCCESS, null, $service);
    }

    //Đặt lịch giữ chố: Danh sách dịch vụ. Có filter theo id dịch vụ. Phân trang.
    public function bookingGetAllService(Request $request)
    {
        $data = $this->validate($request, [
            'branch_id' => 'required|int'
        ]);
        $filters = $request->only(['branch_id']);
        $service = $this->service->bookingGetAllService($filters);
        return $this->responseJson(CODE_SUCCESS, null, $service);
    }

    //Kỹ thuật viên(Nhân viên: Staff) theo chi nhánh. Không có phân trang
    public function bookingGetTechnicianAction(Request $request)
    {
        $data = $this->validate($request, [
            'branch_id' => 'required|int',
        ]);
        $filters = $request->only(['branch_id', 'page', 'display']);
        $technicians = $this->staff->bookingGetTechnician($filters);
        return $this->responseJson(CODE_SUCCESS, null, $technicians);
    }

    public function bookingGetRuleSettingOther()
    {
        $data = $this->ruleSettingOther->getRuleSettingOther();
        return $this->responseJson(CODE_SUCCESS, null, $data);
    }

    public function bookingSubmitAction(Request $request)
    {

        $data = $this->validate($request, [
            'branch_id' => 'required|int',
            'service_id' => 'nullable',
            'staff_id' => 'nullable',
            'date' => 'required|date',
            'time' => 'required',
            'fullname' => 'required',
            'phone' => 'required',
            'email' => 'nullable|email',
            'description' => 'nullable',
        ]);

        $branchId = $request->branch_id;
        $serviceId = $request->service_id;
        $staffId = $request->staff_id;
        $date = $request->date;
        $time = $request->time;
        $fullName = $request->fullname;
        $phone = $request->phone;
        $email = $request->email;
        $description = $request->description;


        DB::beginTransaction();
        try {
            $checkCustomer = $this->customer->testPhone($phone, 0);
            if ($checkCustomer == null) {
                $data = [
                    'full_name' => $fullName,
                    'phone1' => $phone,
                    'branch_id' => $branchId,
                    'created_by' => 0,
                    'updated_by' => 0,
                    'gender' => 'other',
                    'email' => $email,
                    'customer_source_id' => 1
                ];

                $idAdd = $this->customer->add($data);
                $dayCode = date('dmY');
                if ($idAdd < 10) {
                    $idAdd = '0' . $idAdd;
                }
                $dataCode = [
                    'customer_code' => 'KH_' . $dayCode . $idAdd
                ];

                $this->customer->edit($dataCode, $idAdd);
            }
            $checkCustomer = $this->customer->testPhone($phone, 0);
            if ($checkCustomer != null) {
                //Thêm vào bảng customer_appointments
                $data = [
                    'customer_appointment_code' => '',
                    'customer_id' => $checkCustomer['customer_id'],
                    'branch_id' => $branchId,
                    'customer_appointment_type' => 'appointment',
                    'appointment_source_id' => 5,
                    'customer_quantity' => 1,
                    'date' => $date,
                    'time' => $time,
                    'description' => $description,
                    'status' => 'new',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $idAddAppointment = $this->customer_appointment->add($data);

                $dayCode = date('dmY');
                $dataCode = [
                    'customer_appointment_code' => 'LH_' . $dayCode . $idAddAppointment
                ];
                $this->customer_appointment->edit($dataCode, $idAddAppointment);

                //Thêm vào bảng customer_appointment_details
                if ($serviceId != null) {
                    foreach ($serviceId as $key => $value) {
                        $data = [
                            'customer_appointment_id' => $idAddAppointment,
                            'service_id' => $value,
                            'staff_id' => $staffId ? $staffId : 0,
                            'customer_order' => 1,
                            'created_by' => 0,
                            'updated_by' => 0
                        ];
                        $this->customer_appointment_detail->add($data);
                    }
                }

                //Gửi mail và gửi sms.
                CheckMailJob::dispatch('is_event', 'new_appointment', $idAddAppointment);
                $this->smsLog->getList('new_appointment', $idAddAppointment);
                //Lưu log ZNS
                SaveLogZns::dispatch('new_appointment', $checkCustomer['customer_id'], $idAddAppointment);
                //Cộng điểm khi đặt lịch online
                $mLoyalty = app()->get(LoyaltyRepositoryInterface::class);
                $mLoyalty->plusPointEvent([
                    'customer_id' => $checkCustomer['customer_id'],
                    'rule_code' => 'appointment_online',
                    'object_id' => $idAddAppointment
                ]);

                DB::commit();
                //Send Notification
                $mNoti = new SendNotificationApi();
                $mNoti->sendNotification([
                    'key' => 'appointment_W',
                    'customer_id' => $checkCustomer['customer_id'],
                    'object_id' => $idAddAppointment
                ]);
            }
            return $this->responseJson(CODE_SUCCESS, null, ['error' => 0]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
//    Lấy danh sách Slider cho Header
    public function bookingGetSliderHeaderAction(Request $request){
        $slider = $this->bannerSlider->getSliderHeader();
        $logo =  $this->bannerSlider->getLogoSpa();
//        $slider['logo'] = $logo['logo'];
        return $this->responseJson(CODE_SUCCESS, null, [$slider,'logo' => $logo]);
    }
//    Phú
    //Danh sách dịch vụ. Có filter theo nhóm. Phân trang.
    public function getListServiceAction(Request $request)
    {
        $data = $this->validate($request, [
            'service_category_id' => 'nullable'
        ]);
        $filters = $request->only(['service_category_id', 'page', 'display']);
        $service = $this->service->getListService($filters);

        return $this->responseJson(CODE_SUCCESS, null, $service);
    }

    //Danh sách sản phẩm. Có filter theo nhóm. Phân trang.
    public function getListProductAction(Request $request)
    {
        $data = $this->validate($request, [
            'product_category_id' => 'nullable'
        ]);
        $filters = $request->only(['product_category_id', 'page', 'display']);
        $service = $this->productCategory->getListProduct($filters);

        return $this->responseJson(CODE_SUCCESS, null, $service);
    }

    public function getProductDetailGroupAction(Request $request)
    {
        $data = $this->validate($request, [
            'product_id' => 'required|int'
        ]);
        $productDetailGroup = $this->productCategory->getProductDetailGroup($request->product_id);

        return $this->responseJson(CODE_SUCCESS, null, $productDetailGroup);
    }

    public function getListBrand(Request $request)
    {
        $filters = $request->only(['page', 'display']);
        $branch = $this->branch->getListBrand($filters);
        return $this->responseJson(CODE_SUCCESS, null, $branch);

    }
//    Lấy giới thiệu
    public function getIntroduction(Request $request){
        $introduction = $this->spaInfo->getIntroduction();
        return $this->responseJson(CODE_SUCCESS, null, $introduction);
    }

}
