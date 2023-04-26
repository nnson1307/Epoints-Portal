<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\CustomerLead\Repositories\CustomerLead;


use DateTime;
use Carbon\Carbon;
use http\Exception;
use GuzzleHttp\Client;
use App\Helpers\Helper;
use Box\Spout\Common\Type;
use App\Exports\ExportFile;
use Illuminate\Support\Facades\DB;
use App\Exports\CustomerLeadExport;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\BussinessTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\CustomerLead\Models\TagTable;
use App\Http\Middleware\S3UploadsRedirect;
use Modules\Notification\Models\BrandTable;
use Modules\CustomerLead\Models\BranchTable;
use Modules\CustomerLead\Models\ConfigTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\CustomerLead\Models\HistoryTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\ManagerWork\Models\TypeWorkTable;
use Modules\CustomerLead\Models\CustomerTable;
use Modules\CustomerLead\Models\DistrictTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\ProvinceTable;
use Modules\CustomerLead\Models\ExtensionTable;
use Modules\CustomerLead\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\Admin\Repositories\Upload\UploadRepo;
use Modules\CustomerLead\Models\CustomerLogTable;
use Modules\CustomerLead\Models\OrderSourceTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\CustomerLead\Models\CustomerCareTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\CustomerLead\Models\CustomerEmailTable;
use Modules\CustomerLead\Models\CustomerPhoneTable;
use Modules\ManagerWork\Models\ManagerWorkTagTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\MapCustomerTagTable;
use Modules\CustomerLead\Models\CustomerContactTable;
use Modules\CustomerLead\Models\CustomerFanpageTable;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Models\ManageRepeatTimeTable;
use Modules\CustomerLead\Models\CustomerContactsTable;
use Modules\CustomerLead\Models\CustomerLeadFileTable;
use Modules\CustomerLead\Models\CustomerLeadNoteTable;
use Modules\ManagerWork\Models\ManageWorkSupportTable;
use Modules\CustomerLead\Models\CustomerLogUpdateTable;
use Modules\CustomerLead\Models\CustomerLeadCommentTable;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;
use Modules\ManagerWork\Models\ManageStatusConfigMapTable;
use Modules\CustomerLead\Models\CustomerLeadCustomDefineTable;

class CustomerLeadRepo implements CustomerLeadRepoInterface
{
    protected $customerLead;

    public function __construct(
        CustomerLeadTable $customerLead
    ) {
        $this->customerLead = $customerLead;
    }

    /**
     * Danh sách KH tiềm năng
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        //Lay array customer_lead_code khi filter tag
        if (isset($filters['tag_id']) && $filters['tag_id'] != null) {
            $mMapCusTag = new MapCustomerTagTable();
            $listTag = $mMapCusTag->getListLeadByTagId($filters['tag_id'])->toArray();

            $listCustomerCode = [];

            foreach ($listTag as $v) {
                $listCustomerCode[] = $v['customer_lead_code'];
            }
            $filters['customer_tag'] = $listCustomerCode;
            unset($filters['tag_id']);
        }
        // Kiểm tra có quyền trên lead không, nếu có thì được xem hết danh sách, nếu không thì
        //1. User có quyền phân công : thì sẽ xem all lead
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        if (!in_array('customer-lead.permission-assign-revoke', session('routeList'))) {
            $filters['user_id'] = Auth()->id();
        }

        $list = $this->customerLead->getList($filters);

        //Option đầu mối doanh nghiệp
        $optionBusiness = $this->customerLead->getOptionBusiness();

        return [
            'list' => $list,
            "optionBusiness" => $optionBusiness
        ];
    }

    public function getOptionBusiness(){
        return $this->customerLead->getOptionBusiness();
    }

    /**
     * Data view thêm KH tiềm năng
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewCreate($input)
    {
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mCustomerSource = new CustomerSourceTable();
        $mStaff = new StaffsTable();
        $mProvince = new ProvinceTable();

        $optionTag = $mTag->getOption();
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $optionSource = $mCustomerSource->getOption();
        //Option đầu mối doanh nghiệp
        $optionBusiness = $this->customerLead->getOptionBusiness();
        //Option nhân viên (người được phân bổ)
        $optionStaff = $mStaff->getStaffOption();
        //Lấy option tỉnh/ thành
        $optionProvince = $mProvince->getOptionProvince();
        //Lấy cấu hình thông tin kèm theo của KH
        $mCustomerDefine = new CustomerLeadCustomDefineTable();
        $customDefine = $mCustomerDefine->getDefine();

        $mBranch = new BranchTable();
        $listBranch = $mBranch->getLists();

        $mBussiness = new BussinessTable();
        $listBussiness = $mBussiness->getData();

        $mStaffTitle = new StaffTitleTable();
        $listStaffTitle = $mStaffTitle->getData();

        $html = \View::make('customer-lead::customer-lead.popup-create', [
            "optionTag" => $optionTag,
            "optionPipeline" => $optionPipeline,
            "optionSource" => $optionSource,
            'load' => $input['load'],
            "optionBusiness" => $optionBusiness,
            "optionStaff" => $optionStaff,
            "optionProvince" => $optionProvince,
            'customDefine' => $customDefine,
            'listBranch' => $listBranch,
            "listBussiness" => $listBussiness,
            "listStaffTitle" => $listStaffTitle
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Thêm KH tiềm năng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        $mMapCustomerTag = new MapCustomerTagTable();
        $mJourney = new JourneyTable();
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();
        $mPipeline = new PipelineTable();

        DB::beginTransaction();
        try {
            $arrInsertPhone = [];
            $arrInsertEmail = [];
            $arrInsertFanpage = [];
            $arrInsertContact = [];

            //Kiểm tra phone + phone attack có trùng nhau ko
            $arrPhone = [];
            if($input["phone"] != null && $input["phone"] != ""){
                $customerLeadInfo = $this->customerLead->checkPhoneIsExist($input["phone"]);
                if($customerLeadInfo != null){
                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại đã tồn tại'),
                    ]);
                }
                $arrPhone[] = [$input["phone"]];
               
            }
            if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
                foreach ($input['arrPhoneAttack'] as $v) {
                    if(in_array($v['phone'], $arrPhone)) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Số điện thoại đã trùng vui lòng kiểm tra lại'),
                        ]);
                    }  else {
                        $arrPhone[] = $v['phone'];
                    }
                   
                }

                //Check unique phone
                // if ($this->array_has_dupes($arrPhone) == true) {
                //     return response()->json([
                //         'error' => true,
                //         'message' => __('Số điện thoại đã trùng vui lòng kiểm tra lại'),
                //     ]);
                // }
            }
            //Kiểm tra email + email attack có trùng nhau ko
            $arrEmail = [$input["email"]];
            if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
                foreach ($input['arrEmailAttack'] as $v) {
                    if(in_array($v['email'], $arrEmail)) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Email đã trùng vui lòng kiểm tra lại'),
                        ]);
                    }  else {
                        $arrEmail[] = $v['email'];
                    }
                    
                }

                //Check unique email
                // if ($this->array_has_dupes($arrEmail) == true) {
                //     return response()->json([
                //         'error' => true,
                //         'message' => __('Email đã trùng vui lòng kiểm tra lại'),
                //     ]);
                // }
            }
            //Kiểm tra fanpage + fanpage attack có trùng nhau ko
            $arrFanpage = [$input["fanpage"]];
            if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
                foreach ($input['arrFanpageAttack'] as $v) {
                    if(in_array($v['fanpage'], $arrFanpage)) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Fan page đã trùng vui lòng kiểm tra lại'),
                        ]);
                    }  else {
                        $arrFanpage[] = $v['fanpage'];
                    }
                    
                }

                //Check unique email
                // if ($this->array_has_dupes($arrFanpage) == true) {
                //     return response()->json([
                //         'error' => true,
                //         'message' => __('Fan page đã trùng vui lòng kiểm tra lại'),
                //     ]);
                // }
            }

            // Nhân viên được phân bổ
            $saleId = null;
            if (isset($input['sale_id'])) {
                $saleId = $input['sale_id'];
            } else {
                $saleId = Auth()->id();
            }
            // Thời gian giữ tối đa
            $pipelineInfo = $mPipeline->getDetailByCode($input['pipeline_code']);
            $timeRevokeLead = 0;
            $timeNow = Carbon::now();
            if ($pipelineInfo != null && $pipelineInfo['time_revoke_lead']) {
                $timeRevokeLead = $pipelineInfo['time_revoke_lead'];
            }
            $data = [
                "full_name" => $input["full_name"],
                "email" => $input["email"],
                "phone" => $input["phone"],
                "gender" => $input["gender"],
                "address" => $input["address"],
                "pipeline_code" => $input["pipeline_code"],
                "journey_code" => $input["journey_code"],
                "customer_type" => $input["customer_type"],
                "hotline" => isset($input["hotline"]) ? $input["hotline"] : null,
                "fanpage" => $input["fanpage"],
                "zalo" => $input["zalo"],
                "tax_code" => isset($input["tax_code"]) ? $input["tax_code"] : null,
                "representative" => isset($input["representative"]) ? $input["representative"] : null,
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id(),
                "customer_source" => $input['customer_source'],
                // "business_clue" => $input['business_clue'],
                "assign_by" => Auth()->id(),
                "sale_id" => $saleId,
                "tag_id" => isset($input["tag_id"]) ? json_encode($input['tag_id']) : null,
                "date_revoke" => $timeNow->addDay($timeRevokeLead),
                "province_id" => $input['province_id'],
                "district_id" => $input['district_id'],
                'allocation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                "date_last_care" => Carbon::now()->format('Y-m-d H:i:s'),
                "branch_code" => $input["branch_code"],
                "website" => $input["website"],
                "employ_qty" => $input["employ_qty"],
                "business_id" => $input["business_id"],
                "note" => $input['note'],
                'birthday' => $input['birthday'] ? Helper::formatDate($input['birthday'], 'Y-m-d') : null
            ];

            if ($input["avatar"] != null) {
                $data["avatar"] = $input["avatar"];
            }

            if ($input['customer_type'] == "business") {
                $data['business_clue'] = null;
            }

            //Define sẵn 10 trường thông tin kèm theo
            for ($i = 1; $i <= 10; $i++) {
                $custom = "custom_$i";
                $data["custom_$i"] = isset($input[$custom]) ? $input[$custom] : null;
            }

            //Insert customer lead
            $customerLeadId = $this->customerLead->add($data);
            //Update customer_lead_code
            $leadCode = "LEAD_" . date("dmY") . sprintf("%02d", $customerLeadId);
            $this->customerLead->edit([
                "customer_lead_code" => $leadCode
            ], $customerLeadId);

            if (isset($input["tag_id"]) && count($input["tag_id"]) > 0) {
                foreach ($input["tag_id"] as $v) {
                    //Insert map customer lead
                    $mMapCustomerTag->add([
                        "customer_lead_code" => $leadCode,
                        "tag_id" => $v
                    ]);
                }
            }

            if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
                foreach ($input['arrPhoneAttack'] as $v) {
                    $arrInsertPhone[] = [
                        'customer_lead_code' => $leadCode,
                        'phone' => $v['phone'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer phone
            $mCustomerPhone->insert($arrInsertPhone);

            if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
                foreach ($input['arrEmailAttack'] as $v) {
                    $arrInsertEmail[] = [
                        'customer_lead_code' => $leadCode,
                        'email' => $v['email'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer email
            $mCustomerEmail->insert($arrInsertEmail);

            if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
                foreach ($input['arrFanpageAttack'] as $v) {
                    $arrInsertFanpage[] = [
                        'customer_lead_code' => $leadCode,
                        'fanpage' => $v['fanpage'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer fanpage
            $mCustomerFanpage->insert($arrInsertFanpage);

            if (isset($input['arrContact']) && count($input['arrContact']) > 0) {
                foreach ($input['arrContact'] as $v) {
                    $arrInsertContact[] = [
                        'customer_lead_code' => $leadCode,
                        'full_name' => $v['full_name'],
                        'phone' => $v['phone'],
                        'email' => $v['email'],
                        'staff_title_id' => $v['staff_title_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer contact
            $mCustomerContact->insert($arrInsertContact);

            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công")
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Tạo thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Move ảnh từ folder temp sang folder chính
     *
     * @param $filename
     * @param $PATH
     * @return mixed|string
     */
    public function moveImage($filename, $PATH)
    {
        $old_path = TEMP_PATH . "/" . $filename;
        $new_path = $PATH . date("Ymd") . "/" . $filename;
        Storage::disk("public")->makeDirectory($PATH . date("Ymd"));
        Storage::disk("public")->put($new_path, file_get_contents($old_path));
        return $new_path;
    }

    /**
     * Data view edit
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewEdit($input)
    {
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mCustomerSource = new CustomerSourceTable();
        $mMapTag = new MapCustomerTagTable();
        $mJourney = new JourneyTable();
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();
        $mCustomerCare = new CustomerCareTable();
        $mProvince = new ProvinceTable();
        $mDistrict = new DistrictTable();
        $mCustomerDeal = new CustomerDealTable();
        $mMangeWork = new ManagerWorkTable();
        $mNote = new CustomerLeadNoteTable();
        $mManageTypeWork = new TypeWorkTable();
        $mManageStatus = new ManageStatusTable();
        $mStaff = new \Modules\ManagerWork\Models\StaffsTable();
      
        //Lấy thông tin lead
        $item = $this->customerLead->getInfo($input['customer_lead_id']);

        $mapTag = $mMapTag->getMapByCustomer($item["customer_lead_code"]);
     
        $arrPhone = $mCustomerPhone->getPhone($item["customer_lead_code"]);
        $arrEmail = $mCustomerEmail->getEmail($item["customer_lead_code"]);
        $arrFanpage = $mCustomerFanpage->getFanpage($item["customer_lead_code"]);
        $arrContact = $mCustomerContact->getContact($item["customer_lead_code"]);
      
        $optionTag = $mTag->getOption();
       
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $optionJourney = $mJourney->getOptionEdit($item["pipeline_code"], $item["journey_position"]);
        $optionSource = $mCustomerSource->getOption();
        //Option đầu mối doanh nghiệp
        $optionBusiness = $this->customerLead->getOptionBusiness();
        //Lấy option tỉnh thành
        $optionProvince = $mProvince->getOptionProvince();
        //Lấy option quận huyện
        $optionDistrict = $mDistrict->getOptionDistrict($item['province_id']);
        //Lất tất cả journey theo pipeline
        $listJourney = $mJourney->getJourneyByPipeline($item["pipeline_code"]);

        //Lấy lịch sử chăm sóc KH
        //        $getCare = collect($mCustomerCare->getCustomerCare($item['customer_lead_code'])->toArray());
        //        $dataCare = $getCare->groupBy('created_group');
        $filterCare['customer_lead_code'] = $item['customer_lead_code'];
        $dataCare = $mCustomerCare->getListCustomerCare($filterCare);

        $dataDeal = $mCustomerDeal->getListDealLeadDetail($filterCare);
        $arrMapTag = [];

        if (count($mapTag) > 0) {
            foreach ($mapTag as $v) {
                $arrMapTag[] = $v["tag_id"];
            }
        }

        //Lấy cấu hình thông tin kèm theo của KH
        $mCustomerDefine = new CustomerLeadCustomDefineTable();
        $customDefine = $mCustomerDefine->getDefine();


        $data = [
            'customer_id' => $input['customer_lead_id'],
            'manage_work_customer_type' => 'lead',
            'type_search' => 'support'
        ];

        $listWork = $mMangeWork->getListWorkByCustomer($data);

        $data1 = [
            'customer_id' => $input['customer_lead_id'],
            'manage_work_customer_type' => 'lead',
            'type_search' => 'history'
        ];

        //Note
        $listNotes = $mNote->getListNoteCustomer($input['customer_lead_id']);

        //Files
        $mCustomerLeadFile = new CustomerLeadFileTable();
        $listFiles = $mCustomerLeadFile->getListFileCustomerLead($input['customer_lead_id']);

        $historyWork = $mMangeWork->getListWorkByCustomer($data1);

        $listTypeWork = $mManageTypeWork->getListTypeWork(1);

        $listStatusWork = $mManageStatus->getAll();

        $liststaff = $mStaff->getAll();

        $mStaffTitle = new StaffTitleTable();
        $listStaffTitle = $mStaffTitle->getData();

        $mBranch = new BranchTable();
        $listBranch = $mBranch->getLists();

        $mBussiness = new BussinessTable();
        $listBussiness = $mBussiness->getData();
        

        if ($input['view'] == 'edit') {
            $html = \View::make('customer-lead::customer-lead.popup-edit', [
                "optionTag" => $optionTag,
                "optionPipeline" => $optionPipeline,
                "optionJourney" => $optionJourney,
                "optionSource" => $optionSource,
                "arrMapTag" => $arrMapTag,
                "item" => $item,
                'arrPhone' => $arrPhone,
                'arrEmail' => $arrEmail,
                'arrFanpage' => $arrFanpage,
                'arrContact' => $arrContact,
                'listStaffTitle' => $listStaffTitle,
                'listBranch' => $listBranch,
                'listBussiness' => $listBussiness,
                'load' => $input['load'],
                "optionBusiness" => $optionBusiness,
                "optionProvince" => $optionProvince,
                "optionDistrict" => $optionDistrict,
                "customDefine" => $customDefine,
                'listWork' => $listWork,
                'listNotes' => $listNotes,
                'listFiles' => $listFiles,
                'historyWork' => $historyWork,
                'listTypeWork' => $listTypeWork,
                'listStatusWork' => $listStatusWork,
                'liststaff' => $liststaff,
                'chatHubPopup' => $input['chatHubPopup']
            ])->render();
        } else if ($input['view'] == 'detail') {
            $html = \View::make('customer-lead::customer-lead.popup-detail', [
                "optionTag" => $optionTag,
                "optionPipeline" => $optionPipeline,
                "optionJourney" => $optionJourney,
                "optionSource" => $optionSource,
                "arrMapTag" => $arrMapTag,
                "item" => $item,
                'arrPhone' => $arrPhone,
                'arrEmail' => $arrEmail,
                'arrFanpage' => $arrFanpage,
                'arrContact' => $arrContact,
                'listStaffTitle' => $listStaffTitle,
                "optionBusiness" => $optionBusiness,
                "listJourney" => $listJourney,
                "dataCare" => $dataCare,
                "dataDeal" => $dataDeal,
                "optionProvince" => $optionProvince,
                "optionDistrict" => $optionDistrict,
                "customDefine" => $customDefine,
                'listWork' => $listWork,
                'listNotes' => $listNotes,
                'listFiles' => $listFiles,
                'historyWork' => $historyWork,
                'listTypeWork' => $listTypeWork,
                'listStatusWork' => $listStatusWork,
                'liststaff' => $liststaff
            ])->render();
        }

        return [
            'html' => $html
        ];
    }

    /**
     * Chỉnh sửa KH tiềm năng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    // public function update($input)
    // {
    //     $mMapCustomerTag = new MapCustomerTagTable();
    //     $mJourney = new JourneyTable();
    //     $mCustomer = new CustomerTable();
    //     $mCustomerPhone = new CustomerPhoneTable();
    //     $mCustomerEmail = new CustomerEmailTable();
    //     $mCustomerFanpage = new CustomerFanpageTable();
    //     $mCustomerContact = new CustomerContactTable();
    //     $mCustomerLog = new CustomerLogTable();
    //     DB::beginTransaction();
    //     try {
    //         $arrInsertPhone = [];
    //         $arrInsertEmail = [];
    //         $arrInsertFanpage = [];
    //         $arrInsertContact = [];

    //         //Kiểm tra phone + phone attack có trùng nhau ko
    //         $arrPhone = [$input["phone"]];
    //         if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
    //             foreach ($input['arrPhoneAttack'] as $v) {
    //                 $arrPhone[] = $v['phone'];
    //             }

    //             //Check unique phone
    //             if ($this->array_has_dupes($arrPhone) == true) {
    //                 return response()->json([
    //                     'error' => true,
    //                     'message' => __('Số điện thoại đã trùng vui lòng kiểm tra lại'),
    //                 ]);
    //             }
    //         }
    //         //Kiểm tra email + email attack có trùng nhau ko
    //         $arrEmail = [$input["email"]];
    //         if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
    //             foreach ($input['arrEmailAttack'] as $v) {
    //                 $arrEmail[] = $v['email'];
    //             }

    //             //Check unique email
    //             if ($this->array_has_dupes($arrEmail) == true) {
    //                 return response()->json([
    //                     'error' => true,
    //                     'message' => __('Email đã trùng vui lòng kiểm tra lại'),
    //                 ]);
    //             }
    //         }
    //         //Kiểm tra fanpage + fanpage attack có trùng nhau ko
    //         $arrFanpage = [$input["fanpage"]];
    //         if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
    //             foreach ($input['arrFanpageAttack'] as $v) {
    //                 $arrEmail[] = $v['fanpage'];
    //             }

    //             //Check unique email
    //             if ($this->array_has_dupes($arrFanpage) == true) {
    //                 return response()->json([
    //                     'error' => true,
    //                     'message' => __('Fan page đã trùng vui lòng kiểm tra lại'),
    //                 ]);
    //             }
    //         }

    //         $data = [
    //             "full_name" => $input["full_name"],
    //             "email" => $input["email"],
    //             "phone" => $input["phone"],
    //             "gender" => $input["gender"],
    //             "address" => $input["address"],
    //             "pipeline_code" => $input["pipeline_code"],
    //             // "journey_code" => $input["journey_code"],
    //             "customer_type" => $input["customer_type"],
    //             "hotline" => isset($input["hotline"]) ? $input["hotline"] : null,
    //             "fanpage" => $input["fanpage"],
    //             "zalo" => $input["zalo"],
    //             "tax_code" => isset($input["tax_code"]) ? $input["tax_code"] : null,
    //             "representative" => isset($input["representative"]) ? $input["representative"] : null,
    //             "updated_by" => Auth()->id(),
    //             "customer_source" => $input['customer_source'],
    //             "business_clue" => $input['business_clue'],
    //             "province_id" => $input['province_id'],
    //             "district_id" => $input['district_id']
    //         ];

    //         if ($input["avatar"] != null) {
    //             $data["avatar"] = $input["avatar"];
    //         }

    //         if ($input['customer_type'] == "business") {
    //             $data['business_clue'] = null;
    //         }

    //         //Define sẵn 10 trường thông tin kèm theo
    //         for ($i = 1; $i <= 10; $i++) {
    //             $custom = "custom_$i";
    //             $data["custom_$i"] = isset($input[$custom]) ? $input[$custom] : null;
    //         }
    //         // save action "Update"
    //         $dataCustomerLog = [
    //             'object_type' => 'customer_lead',
    //             'object_id' => $input["customer_lead_id"],
    //             'key_table' => 'cpo_customer_lead',
    //             'title' => __('Chỉnh sửa khách hàng tiềm năng'),
    //             'created_by' => Auth()->id(),
    //             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //             'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         ];
    //         $idLog = $mCustomerLog->createLog($dataCustomerLog);
    //         // get current info lead
    //         $curInfoLead = $this->customerLead->getInfoLeadLog($input["customer_lead_id"])->toArray();
    //         $this->_saveLeadLog($curInfoLead, $data, $idLog, 'info');
    //         //Update customer lead
    //         $this->customerLead->edit($data, $input["customer_lead_id"]);

    //         //get list current tag
    //         $currTag = $mMapCustomerTag->getArrayMapByCustomer($input["customer_lead_code"]);

    //         //Remove tag
    //         $mMapCustomerTag->remove($input["customer_lead_code"]);

    //         $newTag = [];
    //         if (isset($input["tag_id"]) && count($input["tag_id"]) > 0) {
    //             foreach ($input["tag_id"] as $v) {
    //                 //Insert map customer lead
    //                 $mMapCustomerTag->add([
    //                     "customer_lead_code" => $input["customer_lead_code"],
    //                     "tag_id" => $v
    //                 ]);
    //                 $newTag[] = [
    //                     "tag_id" => (int)$v
    //                 ];
    //             }
    //         }
    //         $this->_saveLeadLog(array_values($currTag), $newTag, $idLog, 'encode', 'tag');

    //         //Check hành trình phải win ko
    //         //            $checkJourney = $mJourney->getInfo($input["journey_code"]);
    //         //            $checkCustomer = $mCustomer->getCustomerByPhone($input["phone"]);
    //         //
    //         //            if ($checkJourney["default_system"] == "win" && $checkCustomer == null) {
    //         //                //Insert customer
    //         //                $mCustomer->add([
    //         //                    "full_name" => $input["full_name"],
    //         //                    "email" => $input["email"],
    //         //                    "phone1" => $input["phone"],
    //         //                    "gender" => $input["gender"],
    //         //                    "address" => $input["address"],
    //         //                    "branch_id" => Auth()->user()->branch_id,
    //         //                    "member_level_id" => 1,
    //         //                    "created_by" => Auth()->id(),
    //         //                    "updated_by" => Auth()->id()
    //         //                ]);
    //         //            }

    //         // get info phone, email, fanpage, contact
    //         $currPhone = $mCustomerPhone->getArrPhone($input["customer_lead_code"]);
    //         $currEmail = $mCustomerEmail->getArrayEmail($input["customer_lead_code"]);
    //         $currFanpage = $mCustomerFanpage->getArrayFanpage($input["customer_lead_code"]);
    //         $currContact = $mCustomerContact->getArrayContact($input["customer_lead_code"]);

    //         //Remove phone, email, fan page
    //         $mCustomerPhone->removePhone($input["customer_lead_code"]);
    //         $mCustomerEmail->removeEmail($input["customer_lead_code"]);
    //         $mCustomerFanpage->removeFanpage($input["customer_lead_code"]);
    //         $mCustomerContact->removeContact($input["customer_lead_code"]);

    //         $newPhone = [];
    //         if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
    //             foreach ($input['arrPhoneAttack'] as $v) {
    //                 $arrInsertPhone[] = [
    //                     'customer_lead_code' => $input["customer_lead_code"],
    //                     'phone' => $v['phone'],
    //                     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //                     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
    //                 ];
    //             }
    //             $newPhone = $input['arrPhoneAttack'];
    //         }
    //         $this->_saveLeadLog(array_values($currPhone), $newPhone, $idLog, 'encode', 'phone_attack');
    //         //Insert customer phone
    //         $mCustomerPhone->insert($arrInsertPhone);

    //         $newEmail = [];
    //         if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
    //             foreach ($input['arrEmailAttack'] as $v) {
    //                 $arrInsertEmail[] = [
    //                     'customer_lead_code' => $input["customer_lead_code"],
    //                     'email' => $v['email'],
    //                     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //                     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
    //                 ];
    //             }
    //             $newEmail = $input['arrEmailAttack'];
    //         }
    //         $this->_saveLeadLog(array_values($currEmail), $newEmail, $idLog, 'encode', 'email_attack');
    //         //Insert customer email
    //         $mCustomerEmail->insert($arrInsertEmail);

    //         $newFanpage = [];
    //         if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
    //             foreach ($input['arrFanpageAttack'] as $v) {
    //                 $arrInsertFanpage[] = [
    //                     'customer_lead_code' => $input["customer_lead_code"],
    //                     'fanpage' => $v['fanpage'],
    //                     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //                     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
    //                 ];
    //             }
    //             $newFanpage = $input['arrFanpageAttack'];
    //         }
    //         $this->_saveLeadLog(array_values($currFanpage), $newFanpage, $idLog, 'encode', 'fanpage_attack');

    //         //Insert customer fanpage
    //         $mCustomerFanpage->insert($arrInsertFanpage);

    //         $newContact = [];
    //         if (isset($input['arrContact']) && count($input['arrContact']) > 0) {
    //             foreach ($input['arrContact'] as $v) {
    //                 $arrInsertContact[] = [
    //                     'customer_lead_code' => $input["customer_lead_code"],
    //                     'full_name' => $v['full_name'],
    //                     'phone' => $v['phone'],
    //                     'email' => $v['email'],
    //                     'address' => $v['address'],
    //                     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //                     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
    //                 ];
    //             }
    //             $newContact = $input['arrContact'];
    //         }
    //         //            dd($currContact, $newContact);
    //         $this->_saveLeadLog($currContact, $newContact, $idLog, 'encode', 'contact_attack');

    //         //Insert customer contact
    //         $mCustomerContact->insert($arrInsertContact);

    //         //Kiểm tra tạo deal tự động
    //         $checkHaveDeal = $this->checkJourneyHaveDeal($input["journey_code"], $input["customer_lead_id"]);

    //         DB::commit();
    //         return response()->json([
    //             "error" => false,
    //             "message" => __("Chỉnh sửa thành công"),
    //             "create_deal" => $checkHaveDeal,
    //             "lead_id" => $input["customer_lead_id"]
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response()->json([
    //             "error" => true,
    //             "message" => __("Chỉnh sửa thất bại"),
    //             "_message" => $e->getMessage()
    //         ]);
    //     }
    // }
    public function update($input)
    {
        $mMapCustomerTag = new MapCustomerTagTable();
        $mJourney = new JourneyTable();
        $mCustomer = new CustomerTable();
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();
        $mCustomerLog = new CustomerLogTable();
        DB::beginTransaction();
        try {
            $arrInsertPhone = [];
            $arrInsertEmail = [];
            $arrInsertFanpage = [];
            $arrInsertContact = [];

            //Kiểm tra phone + phone attack có trùng nhau ko
            $arrPhone = [];
            if($input["phone"] != null && $input["phone"] != ""){
                $customerLeadInfo = $this->customerLead->checkPhoneIsExist($input["phone"], $input["customer_lead_id"]);
                if($customerLeadInfo != null){
                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại đã tồn tại'),
                    ]);
                }
                $arrPhone[] = [$input["phone"]];
               
            }
            
            if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
                foreach ($input['arrPhoneAttack'] as $v) {
                    if(in_array($v['phone'], $arrPhone)) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Số điện thoại đã trùng vui lòng kiểm tra lại'),
                        ]);
                    }  else {
                        $arrPhone [] = $v['phone'];
                    }
                    
                }
               
                 //Check unique phone
                 
                //  if ($this->array_has_dupes($arrPhone) == true) {
                 
                //     return response()->json([
                //         'error' => true,
                //         'message' => __('Số điện thoại đã trùng vui lòng kiểm tra lại'),
                //     ]);
                // }
             
                
            }
          
            //Kiểm tra email + email attack có trùng nhau ko
            $arrEmail = [$input["email"]];
            if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
                foreach ($input['arrEmailAttack'] as $v) {
                    if(in_array($v['email'], $arrEmail)) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Email đã trùng vui lòng kiểm tra lại'),
                        ]);
                    }  else {
                        $arrEmail [] = $v['email'];
                    }
                   
                }

                //Check unique email
                // if ($this->array_has_dupes($arrEmail) == true) {
                //     return response()->json([
                //         'error' => true,
                //         'message' => __('Email đã trùng vui lòng kiểm tra lại'),
                //     ]);
                // }
            }
          
            //Kiểm tra fanpage + fanpage attack có trùng nhau ko
            $arrFanpage = [$input["fanpage"]];
            if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
                foreach ($input['arrFanpageAttack'] as $v) {
                    if(in_array($v['fanpage'], $arrFanpage)) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Fan page đã trùng vui lòng kiểm tra lại'),
                        ]);
                    }  else {
                        $arrFanpage [] = $v['fanpage'];
                    }
                    
                }

                //Check unique email
                // if ($this->array_has_dupes($arrFanpage) == true) {
                //     return response()->json([
                //         'error' => true,
                //         'message' => __('Fan page đã trùng vui lòng kiểm tra lại'),
                //     ]);
                // }
            }

            $data = [
                "full_name" => $input["full_name"],
                "email" => $input["email"],
                "phone" => $input["phone"],
                "gender" => $input["gender"] ?? null,
                "address" => $input["address"],
                "pipeline_code" => $input["pipeline_code"],
                "journey_code" => $input["journey_code"],
                "customer_type" => $input["customer_type"],
                "hotline" => isset($input["hotline"]) ? $input["hotline"] : null,
                "fanpage" => $input["fanpage"],
                "zalo" => $input["zalo"],
               
                "updated_by" => Auth()->id(),
                "tag_id" => !empty($input['tag_id']) ? json_encode($input['tag_id']) : null,
                "customer_source" => $input['customer_source'],
                "business_clue" => $input['business_clue'] ?? '',
                "province_id" => $input['province_id'],
                "district_id" => $input['district_id'],
                "date_last_care" => Carbon::now()->format('Y-m-d H:i:s'),
                "branch_code" => $input["branch_code"] ?? null,
                "website" => $input["website"] ?? '',
                "employ_qty" => $input["employ_qty"] ?? 0,
                "business_id" => $input["business_id"] ?? null,
                "note" => $input['note'] ?? '',
                'birthday' => $input['birthday'] ? Helper::formatDate($input['birthday'], 'Y-m-d') : null
            ];

            if ($input["avatar"] != null) {
                $data["avatar"] = $input["avatar"];
            }

            if ($input['customer_type'] == "business") {
                $data['business_clue'] = null;
                $data['tax_code'] = isset($input["tax_code"]) ? $input["tax_code"] : null;
                $data['representative'] = isset($input["representative"]) ? $input["representative"] : null;
            }else {
                $data['tax_code'] = null;
                $data['representative'] = null;
            }

            //Define sẵn 10 trường thông tin kèm theo
            for ($i = 1; $i <= 10; $i++) {
                $custom = "custom_$i";
                $data["custom_$i"] = isset($input[$custom]) ? $input[$custom] : null;
            }
            // save action "Update"
            $dataCustomerLog = [
                'object_type' => 'customer_lead',
                'object_id' => $input["customer_lead_id"],
                'key_table' => 'cpo_customer_lead',
                'title' => __('Chỉnh sửa khách hàng tiềm năng'),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            $idLog = $mCustomerLog->createLog($dataCustomerLog);
            // get current info lead
            $curInfoLead = $this->customerLead->getInfoLeadLog($input["customer_lead_id"])->toArray();
            $this->_saveLeadLog($curInfoLead, $data, $idLog, 'info');
            //Update customer lead
            $this->customerLead->edit($data, $input["customer_lead_id"]);

            //get list current tag
            $currTag = $mMapCustomerTag->getArrayMapByCustomer($input["customer_lead_code"]);

            //Remove tag
            $mMapCustomerTag->remove($input["customer_lead_code"]);

            $newTag = [];
            if (isset($input["tag_id"]) && count($input["tag_id"]) > 0) {
                foreach ($input["tag_id"] as $v) {
                    //Insert map customer lead
                    $mMapCustomerTag->add([
                        "customer_lead_code" => $input["customer_lead_code"],
                        "tag_id" => $v
                    ]);
                    $newTag[] = [
                        "tag_id" => (int)$v
                    ];
                }
            }
            $this->_saveLeadLog(array_values($currTag), $newTag, $idLog,'encode','tag');

            //Check hành trình phải win ko
//            $checkJourney = $mJourney->getInfo($input["journey_code"]);
//            $checkCustomer = $mCustomer->getCustomerByPhone($input["phone"]);
//
//            if ($checkJourney["default_system"] == "win" && $checkCustomer == null) {
//                //Insert customer
//                $mCustomer->add([
//                    "full_name" => $input["full_name"],
//                    "email" => $input["email"],
//                    "phone1" => $input["phone"],
//                    "gender" => $input["gender"],
//                    "address" => $input["address"],
//                    "branch_id" => Auth()->user()->branch_id,
//                    "member_level_id" => 1,
//                    "created_by" => Auth()->id(),
//                    "updated_by" => Auth()->id()
//                ]);
//            }

            // get info phone, email, fanpage, contact
            $currPhone = $mCustomerPhone->getArrPhone($input["customer_lead_code"]);
            $currEmail = $mCustomerEmail->getArrayEmail($input["customer_lead_code"]);
            $currFanpage = $mCustomerFanpage->getArrayFanpage($input["customer_lead_code"]);
            $currContact = $mCustomerContact->getArrayContact($input["customer_lead_code"]);

            //Remove phone, email, fan page
            $mCustomerPhone->removePhone($input["customer_lead_code"]);
            $mCustomerEmail->removeEmail($input["customer_lead_code"]);
            $mCustomerFanpage->removeFanpage($input["customer_lead_code"]);
            $mCustomerContact->removeContact($input["customer_lead_code"]);

            $newPhone = [];
            if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
                foreach ($input['arrPhoneAttack'] as $v) {
                    $arrInsertPhone [] = [
                        'customer_lead_code' => $input["customer_lead_code"],
                        'phone' => $v['phone'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
                $newPhone = $input['arrPhoneAttack'];
            }
            $this->_saveLeadLog(array_values($currPhone), $newPhone, $idLog,'encode','phone_attack');
            //Insert customer phone
            $mCustomerPhone->insert($arrInsertPhone);

            $newEmail = [];
            if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
                foreach ($input['arrEmailAttack'] as $v) {
                    $arrInsertEmail [] = [
                        'customer_lead_code' => $input["customer_lead_code"],
                        'email' => $v['email'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
                $newEmail = $input['arrEmailAttack'];
            }
            $this->_saveLeadLog(array_values($currEmail), $newEmail, $idLog,'encode','email_attack');
            //Insert customer email
            $mCustomerEmail->insert($arrInsertEmail);

            $newFanpage = [];
            if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
                foreach ($input['arrFanpageAttack'] as $v) {
                    $arrInsertFanpage [] = [
                        'customer_lead_code' => $input["customer_lead_code"],
                        'fanpage' => $v['fanpage'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
                $newFanpage = $input['arrFanpageAttack'];
            }
            $this->_saveLeadLog(array_values($currFanpage), $newFanpage, $idLog,'encode','fanpage_attack');

            //Insert customer fanpage
            $mCustomerFanpage->insert($arrInsertFanpage);

            if($input['customer_type'] == "business") {
                $newContact = [];
                if (isset($input['arrContact']) && count($input['arrContact']) > 0) {
                    foreach ($input['arrContact'] as $v) {
                        $arrInsertContact [] = [
                            'customer_lead_code' => $input["customer_lead_code"],
                            'full_name' => $v['full_name'],
                            'phone' => $v['phone'],
                            'email' => $v['email'],
                            'staff_title_id' => $v['staff_title_id'],
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                    $newContact = $input['arrContact'];
                }
    //            dd($currContact, $newContact);
                $this->_saveLeadLog($currContact, $newContact, $idLog,'encode','contact_attack');

                //Insert customer contact
                $mCustomerContact->insert($arrInsertContact);
            }

            //Kiểm tra tạo deal tự động
            $checkHaveDeal = $this->checkJourneyHaveDeal($input["journey_code"], $input["customer_lead_id"]);

            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa thành công"),
                "create_deal" => $checkHaveDeal,
                "lead_id" => $input["customer_lead_id"]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa thất bại"),
                "_message" => $e->getMessage()
            ]);
        }
    }

    public function updateFromOncall($input)
    {
        $mCustomerLog = new CustomerLogTable();
        DB::beginTransaction();
        try {

            $data = [
                "full_name" => $input["full_name"],
                //                "email" => $input["email"],
                "phone" => $input["phone"],
                "gender" => $input["gender"],
                "address" => $input["address"],
                //                "pipeline_code" => $input["pipeline_code"],
                //                "journey_code" => $input["journey_code"],
                //                "customer_type" => $input["customer_type"],
                //                "hotline" => isset($input["hotline"]) ? $input["hotline"] : null,
                //                "zalo" => $input["zalo"],
                //                "tax_code" => isset($input["tax_code"]) ? $input["tax_code"] : null,
                //                "representative" => isset($input["representative"]) ? $input["representative"] : null,
                "updated_by" => Auth()->id(),
                //                "customer_source" => $input['customer_source'],
                "province_id" => $input['province_id'],
                "district_id" => $input['district_id']
            ];
            // save action "Update"
            $dataCustomerLog = [
                'object_type' => 'customer_lead',
                'object_id' => $input["customer_lead_id"],
                'key_table' => 'cpo_customer_lead',
                'title' => __('Chỉnh sửa khách hàng tiềm năng'),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            $idLog = $mCustomerLog->createLog($dataCustomerLog);
            // get current info lead
            $curInfoLead = $this->customerLead->getInfoLeadLog($input["customer_lead_id"])->toArray();
            $this->_saveLeadLog($curInfoLead, $data, $idLog, 'info');
            //Update customer lead
            $this->customerLead->edit($data, $input["customer_lead_id"]);

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
                "_message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Kiểm tra hành trình có tạo deal không
     *
     * @param $journeyCode
     * @param $leadId
     * @return int
     */
    private function checkJourneyHaveDeal($journeyCode, $leadId)
    {
        $mJourney = new JourneyTable();
        $mCustomerLead = new CustomerLeadTable();

        //Lấy thông tin hành trình
        $getJourney = $mJourney->getInfo($journeyCode);
        //Lấy thông tin KH tiềm năng
        $getLead = $mCustomerLead->getInfo($leadId);

        $createDeal = 0;

        if ($getJourney['is_deal_created'] == 1 && $getLead['deal_code'] == null) {
            $createDeal = 1;
        }

        return $createDeal;
    }

    /**
     * Xóa KH tiềm năng
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            //Xóa KH tiềm năng
            $this->customerLead->edit([
                'is_deleted' => 1
            ], $input['customer_lead_id']);

            return [
                'error' => false,
                'message' => __('Xóa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xóa thất bại')
            ];
        }
    }

    /**
     * Show popup chăm sóc khách hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function popupCustomerCare($input)
    {
        $mCustomerCare = new CustomerCareTable();
        $mManageTypeWork = new TypeWorkTable();
        $mStaff = new StaffsTable();
        $mCustomerLead = new CustomerLeadTable();
        $mManageWork = new ManagerWorkTable();
        //Lấy thông tin KH tiềm năng
        $item = $this->customerLead->getInfo($input['customer_lead_id']);
        //Lấy lịch sử chăm sóc KH
        $getCare = collect($mCustomerCare->getCustomerCare($item['customer_lead_code'])->toArray());

        $dataCare = $getCare->groupBy('created_group');

        //        if (count($dataCare) > 0) {
        //            foreach ($dataCare as $k => $v) {
        //                $dataCare[$k] = $v->sortBy('created_at');
        //            }
        //        }

        $detailWork = null;
        $is_booking = 0;
        $listStatus = $this->getListStatusWork();
        if (isset($input['manage_work_id'])) {
            $detailWork = $mManageWork->getDetail($input['manage_work_id']);
            $is_booking = $detailWork['is_booking'];
            $listStatus = $this->getListStatusWork($input['manage_work_id']);
        }

        $listTypeWork = $mManageTypeWork->getListTypeWork(1);
        $listStaff = $mStaff->getListStaffByFilter([]);
        $listCustomer = $mCustomerLead->getAllListCustomerLead();

        $data = [
            'customer_id' => $input['customer_lead_id'],
            'manage_work_customer_type' => 'lead',
            'type_search' => 'support'
        ];

        $listWork = $mManageWork->getListWorkByCustomer($data);

        $html = \View::make('customer-lead::customer-lead.popup-customer-care', [
            'customer_lead_id' => $input['customer_lead_id'],
            'dataCare' => $dataCare,
            'listTypeWork' => $listTypeWork,
            'listStaff' => $listStaff,
            'listCustomer' => $listCustomer,
            'detailWork' => $detailWork,
            'listStatus' => $listStatus,
            'listWork' => $listWork
        ])->render();

        return [
            'html' => $html,
            'is_booking' => $is_booking
        ];
    }

    public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
    {
        $results = [];

        $callback = $this->valueRetriever($callback);

        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        dump($results);

        $descending ? arsort($results, $options)
            : asort($results, $options);

        dump($results);

        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        dump($results);

        return new static($results);
    }

    /**
     * Chăm sóc khách hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function customerCare($data)
    {
        try {
            $mCustomerCare = new CustomerCareTable();
            $mManageWork = new ManagerWorkTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageWorkTag = new ManagerWorkTagTable();
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $sendNoti = new SendNotificationApi();
            $mManageTypeWork = new TypeWorkTable();
            $mStaff = app()->get(StaffsTable::class);

            DB::beginTransaction();

            if (isset($data['date_start'])) {
                $date_start = isset($data['time_start']) ? Carbon::createFromFormat('d/m/Y', $data['date_start'])->format('Y-m-d ' . $data['time_start'] . ':00') : Carbon::createFromFormat('d/m/Y', $data['date_start'])->format('Y-m-d 00:00:00');
                $date_end = isset($data['time_end']) ? Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d ' . $data['time_end'] . ':00') : Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d 23:59:59');
                $start_time = strtotime($date_start);
                $end_time = strtotime($date_end);
                if ($start_time > $end_time){
                    return [
                        'error' => true,
                        'message' => 'Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc'
                    ];
                }

                // if ($date_start > $date_end) {
                //     return [
                //         'error' => true,
                //         'message' => 'Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc'
                //     ];
                // }
            }

            $mManageRemind = new ManageRedmindTable();
            $messageErrorRemind = null;
            if (isset($data['date_remind']) || (isset($data['description_remind']) && strlen(strip_tags($data['description_remind'])) != 0)) {

                if (!isset($data['staff'])) {
                    $messageErrorRemind = $messageErrorRemind . 'Vui lòng chọn nhân viên được nhắc <br>';
                }
                if (!isset($data['date_remind'])) {
                    $messageErrorRemind = $messageErrorRemind . 'Vui lòng chọn thời gian nhắc <br>';
                }
                if (isset($data['description_remind']) && strlen(strip_tags($data['description_remind'])) == 0) {
                    $messageErrorRemind = $messageErrorRemind . 'Vui lòng nhập nội dung nhắc <br>';
                }
            }

            if ($messageErrorRemind != null) {
                return [
                    'error' => true,
                    'message' => $messageErrorRemind
                ];
            }

            if (isset($data['date_remind']) && strlen(strip_tags($data['description_remind'])) != 0) {
                $data['time_remind'] = str_replace(',', '', $data['time_remind']);
                $messageError = $this->checkRemind($data);
                if ($messageError != null) {
                    return [
                        'error' => true,
                        'message' => $messageError
                    ];
                }
            }
            if (!isset($data['manage_work_id'])) {
                $dataWork = [
                    'manage_work_code' => $this->codeWork(),
                    'manage_type_work_id' => $data['manage_type_work_id'],
                    'manage_work_customer_type' => isset($data['manage_work_customer_type']) ? $data['manage_work_customer_type'] : 'lead',
                    'manage_work_title' => strip_tags($data['manage_work_title']),
                    'date_end' => $data['is_booking'] == 0 ? Carbon::now() : (isset($data['time_end']) ? Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d ' . $data['time_end'] . ':00') : Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d 23:59:59')),
                    'processor_id' => isset($data['processor_id']) ? $data['processor_id'] : Auth::id(),
                    'assignor_id' => Auth::id(),
                    'obj_id' => $data['obj_id'],
                    'time' => isset($data['time']) ? strip_tags($data['time']) : null,
                    'time_type' => isset($data['time_type']) ? $data['time_type'] : null,
                    'progress' => isset($data['progress']) ? $data['progress'] : 0,
                    'customer_id' => isset($data['customer_lead_id']) ? strip_tags($data['customer_lead_id']) : (isset($data['customer_deal_id']) ? strip_tags($data['customer_deal_id']) : null),
                    'description' => isset($data['content']) ? $data['content'] : null,
                    'approve_id' => isset($data['approve_id']) ? $data['approve_id'] : null,
                    'is_approve_id' => isset($data['is_approve_id']) ? 1 : 0,
                    'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                    'type_card_work' => 'bonus',
                    'priority' => isset($data['priority']) ? $data['priority'] : null,
                    'manage_status_id' => $data['is_booking'] == 0 ? 6 : $data['manage_status_id'],
                    'is_booking' => $data['is_booking'],
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                    'created_by' => Auth::id()
                ];

                $detailStaff = $mStaff->getItem($dataWork['processor_id']);

                $dataWork['branch_id'] = $detailStaff['branch_id'];

            } else {
                $dataWork = [
                    'manage_type_work_id' => $data['manage_type_work_id'],
                    'manage_work_title' => strip_tags($data['manage_work_title']),
                    'description' => isset($data['content']) ? $data['content'] : null,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ];

                if (isset($data['manage_status_id'])) {
                    $dataWork['manage_status_id'] = $data['manage_status_id'];
                }

                if (isset($data['processor_id'])) {
                    $dataWork['processor_id'] = $data['processor_id'];

                    $detailStaff = $mStaff->getItem($dataWork['processor_id']);

                    $dataWork['branch_id'] = $detailStaff['branch_id'];
                }

                if (isset($data['date_end'])) {
                    $dataWork['date_end'] = isset($data['time_end']) ? Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d ' . $data['time_end'] . ':00') : Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d 23:59:59');
                }
            }

            if (isset($data['date_start'])) {
                $dataWork['date_start'] = isset($data['time_start']) ? Carbon::createFromFormat('d/m/Y', $data['date_start'])->format('Y-m-d ' . $data['time_start'] . ':00') : Carbon::createFromFormat('d/m/Y', $data['date_start'])->format('Y-m-d 00:00:00');
            } else {
                $dataWork['date_start'] = $dataWork['date_end'];
            }

            if (!isset($data['manage_work_id'])) {
                $idWork = $mManageWork->createdWork($dataWork);
            } else {
                $dataOld = $mManageWork->getDetail($data['manage_work_id']);
                $mManageWork->updateWork($dataWork, $data['manage_work_id']);
                $idWork = $data['manage_work_id'];
            }

            if (isset($data['date_remind']) && strlen(strip_tags($data['description_remind'])) != 0) {
                $dataRemind = [
                    'staff_id' => isset($data['processor_id']) ? $data['processor_id'] : Auth::id(),
                    'manage_work_id' => $idWork,
                    'date_remind' => Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->format('Y-m-d H:i:00'),
                    'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                    'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                    'description' => strip_tags($data['description_remind']),
                    'is_sent' => 0,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $mManageRemind->insertArrayRemind($dataRemind);
            }

            if (isset($data['customer_lead_id'])) {
                $item = $this->customerLead->getInfo($data['customer_lead_id']);

                $typeDetail = $mManageTypeWork->getItem($data['manage_type_work_id']);

                if (($data['is_booking'] == 0 && !isset($data['manage_work_id'])) || (isset($data['manage_status_id']) && in_array($data['manage_status_id'], [6, 7]))) {
                    //Insert customer care
                    $mCustomerCare->add([
                        "customer_lead_code" => $item['customer_lead_code'],
                        "care_type" => $typeDetail['manage_type_work_key'],
                        "content" => $data['content'],
                        "created_by" => Auth()->id(),
                        "object_id" => $data['history_id']
                    ]);
                }

                //Update tương tác gần nhất
                $this->customerLead->updateById(
                    $data['customer_lead_id'], 
                    [
                        'date_last_care' => Carbon::now()->format('Y-m-d H:i:s')
                    ]
                );
            }

            DB::commit();

            if (!isset($data['manage_work_id'])) {
                if ($dataWork['processor_id'] != Auth::id()) {
                    $dataNoti = [
                        'key' => 'work_assign',
                        'object_id' => $idWork,
                    ];
                }
            } else {
                if (isset($dataWork['manage_status_id']) && $dataWork['manage_status_id'] != $dataOld['manage_status_id']) {
                    $dataNoti = [
                        'key' => 'work_update_status',
                        'object_id' => $idWork,
                    ];
                }
            }

            if (isset($dataNoti)) {
                $sendNoti->sendStaffNotification($dataNoti);
            }

            return [
                'error' => false,
                'message' => __('Chăm sóc khách hàng thành công')
            ];
        } catch (\Exception $e) {
            Db::rollBack();
            return [
                'error' => true,
                'message' => __('Chăm sóc khách hàng thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    public function codeWork()
    {
        $mManageWork = new ManagerWorkTable();
        $codeWork = 'CV_' . Carbon::now()->format('Ymd') . '_';
        $workCodeDetail = $mManageWork->getCodeWork($codeWork);

        if ($workCodeDetail == null) {
            return $codeWork . '001';
        } else {
            $arr = explode($codeWork, $workCodeDetail);
            $value = strval(intval($arr[1]) + 1);
            $zero_str = "";
            if (strlen($value) < 7) {
                for ($i = 0; $i < (3 - strlen($value)); $i++) {
                    $zero_str .= "0";
                }
            }
            return $codeWork . $zero_str . $value;
        }
    }

    public function loadKanBanVue($input){
        $mPipeline = new PipelineTable();
        $mJourney = new JourneyTable();
        $mManageTypeWork = new TypeWorkTable();
        $mManageWork = new ManagerWorkTable();
        $mTag = new TagTable();
        
        if($input['pipeline_id'] == null) {
            return [
                'error' => true
            ];
        }

        if(isset($input['date_from'])){
            $startDate = Carbon::parse(str_replace('/', '-', $input['date_from']))->format('d/m/Y');
        }
        else{
            $startDate = Carbon::now()->startOfMonth()->format('d/m/Y');
        }

        if(isset($input['date_to'])){
            $endDate = Carbon::parse(str_replace('/', '-', $input['date_to']))->format('d/m/Y');
        }
        else{
            $endDate = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        if($startDate && $endDate){
            $input['created_at'] = sprintf('%s - %s', $startDate, $endDate);
        }

        // //Lấy thông tin pipeline
        $getPipeline = $mPipeline->getDetail($input['pipeline_id']);

        //Lấy hành trình của pipeline
        $getJourney = $mJourney->getJourneyByPipeline($getPipeline['pipeline_code']);

        $input['pipeline_code'] = $getPipeline['pipeline_code'];

        // phan quyen data
        $userId = null;
        if (!in_array('customer-lead.permission-assign-revoke', session('routeList'))) {
            $input['user_id'] = Auth()->id();
        }

        $getCustomerLead = $this->customerLead->getCustomerByPipeline($input);

        $now = Carbon::parse(now())->format('Y-m-d');

        foreach($getCustomerLead as $lead){
            $lead->diff_day = 0;
            if ($lead->date_last_care) {
                $lastCare = $lead->date_last_care;
                $lead->diff_day = Helper::getAgoTime($lastCare);
                $lead->last_care = Carbon::parse($lastCare)->format('d/m/Y');
            }

            $numberOfWork = $mManageWork->getWorkLead($lead->customer_lead_id);
            $lead->related_work = $numberOfWork ? $numberOfWork->count() : 0;

            $numberOfWorkLeadOverdue = $mManageWork->getWorkLeadOverdue($lead->customer_lead_id);
            $lead->appointment = $numberOfWorkLeadOverdue;

            $tagIds = $lead->tag_id ? json_decode($lead->tag_id, true) : [];

            $tagLists = [];
            if($tagIds){
                $tagLists = $mTag->getTagByIds($tagIds)->pluck('name')->toArray();
            }

            $lead->tags = $tagLists;
        }

        // ->groupBy('journey_code')->toArray()

        $getCustomerLead = $getCustomerLead->groupBy('journey_code')->toArray();

        $listTotalWork = [];

        $listCustomerLead = [];
        foreach ($getJourney as $key => $value) {
            $listCustomerLead[$key] = $value;
            $listCustomerLead[$key]['items'] = $getCustomerLead[$value['journey_code']] ?? [];
            $listCustomerLead[$key]['count'] = isset($getCustomerLead[$value['journey_code']]) ? count($getCustomerLead[$value['journey_code']]) : 0;
        }

        foreach (collect($getJourney)->pluck('journey_code') as $item) {
            $listTotalWork[$item] = $mManageTypeWork->getListDefault();
        }

        //Lấy quyền gọi
        $isCall = 0;

        if (in_array('customer-lead.modal-call', session('routeList'))) {
            $isCall = 1;
        }

        return [
            'error' => 0,
            'journey' => $getJourney,
            'customerLead' => $listCustomerLead,
            'isCall' => $isCall,
            'listTotalWork' => $listTotalWork
        ];
    }

    /**
     * Load view kan ban
     *
     * @param $input
     * @return mixed|void
     */
    public function loadKanBanView($input)
    {
        $mPipeline = new PipelineTable();
        $mJourney = new JourneyTable();
        $mManageTypeWork = new TypeWorkTable();
        $mManageWork = new ManagerWorkTable();
        if ($input['pipeline_id'] == null) {
            return [
                'error' => true
            ];
        }

        // //Lấy thông tin pipeline
        $getPipeline = $mPipeline->getDetail($input['pipeline_id']);

        //Lấy hành trình của pipeline
        $getJourney = $mJourney->getJourneyByPipeline($getPipeline['pipeline_code']);

        $input['pipeline_code'] = $getPipeline['pipeline_code'];


        // phan quyen data
        $userId = null;
        if (!in_array('customer-lead.permission-assign-revoke', session('routeList'))) {
            $input['user_id'] = Auth()->id();
        }
        $getCustomerLead = $this->customerLead->getCustomerByPipeline($input);

        $listTypeWork = [];
        $listTotalWork = [];
        $arrCustom = array();
        $arr = array();
        foreach (collect($getJourney)->pluck('journey_code') as $key => $item) {
            $listTotalWork[$item] = $mManageTypeWork->getListDefault();
        }
        // foreach ($getJourney as $key => $item) {
        //     $input['journey_code'] = $item['journey_code'];
        //     $lst = $this->customerLead->getCustomerByPipeline($input)->toArray();
        //     var_dump($lst['total']);
        //     die;
        //     $arr = $lst['data'];
        //     $arrCustom = array_merge($arrCustom, $arr);
        // }

        // $getCustomerLead = $arrCustom;
        // if (count($getCustomerLead) != 0) {
        //     $groupCustomer = collect($getCustomerLead)->groupBy('journey_code');
        //     foreach ($groupCustomer as $key => $item) {
        //         if (count($item) != 0) {
        //             $listCustomer = collect($item)->pluck('customer_lead_id')->toArray();

        //             $listTotalWorkType = $mManageTypeWork->getTotalTypeWorkByLead(implode(',', $listCustomer), 'lead');

        //             if (count($listTotalWorkType) == 0) {
        //                 $listTotalWorkType = $mManageTypeWork->getListDefault();
        //             }

        //             $listTotalWork[$key] = $listTotalWorkType;
        //         }
        //     }
        // }

        //Lấy quyền gọi
        $isCall = 0;

        if (in_array('customer-lead.modal-call', session('routeList'))) {
            $isCall = 1;
        }

        // if (isset($input['dataField'])) {
        //     foreach ($getCustomerLead as $key => $item) {
        //         if ($input['dataField'] == $item['journey_code'] && $input['search_manage_type_work_id'] != $item['manage_type_work_id']) {
        //             unset($getCustomerLead[$key]);
        //         }
        //     }
        //     $getCustomerLead = collect(array_values(collect($getCustomerLead)->toArray()));
        // }

        return [
            'error' => false,
            // 'pipeline' => $getPipeline,
            'journey' => $getJourney,
            'customerLead' => $getCustomerLead,
            'isCall' => $isCall,
            'listTotalWork' => $listTotalWork
        ];
    }

    /**
     * Cập nhật hành trình khách hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function updateJourney($input)
    {
        try {
            $mJourney = new JourneyTable();
            $mCustomer = new CustomerTable();

            //Get customer lead
            $getInfo = $this->customerLead->getInfo($input['customer_lead_id']);
            //Get journey old
            $getOld = $mJourney->getInfoUpdateJourney($input['pipeline_id'], $input['journey_old']);
            //Get journey new
            $getNew = $mJourney->getInfoUpdateJourney($input['pipeline_id'], $input['journey_new']);

            //Check journey old dc update qua journey new ko
            if (!in_array($getNew['journey_id'], explode(',', $getOld['journey_updated']))) {
                return [
                    'error' => true,
                    'message' => __('Chỉnh sửa thất bại'),
                    '_message' => 'Journey new ko có trong journey_updated của journey cũ'
                ];
            }
            //Check vị trí journey new nhỏ hơn journey old thì ko cho update
            //            if ($getOld['position'] > $getNew['position']) {
            //                return [
            //                    'error' => true,
            //                    'message' => __('Chỉnh sửa thất bại'),
            //                    '_message' => 'Không thể cập nhật hành trình nhỏ hơn'
            //                ];
            //            }

            
            //Update journey customer lead
            $this->customerLead->edit([
                'journey_code' => $getNew['journey_code']
            ], $input['customer_lead_id']);

            //Check customer có tồn tại chưa
            $checkCustomer = $mCustomer->getCustomerByPhone($getInfo["phone"]);

            if ($getNew["default_system"] == "win" && $checkCustomer == null) {
                //Insert customer
                $mCustomer->add([
                    "full_name" => $getInfo["full_name"],
                    "email" => $getInfo["email"],
                    "phone1" => $getInfo["phone"],
                    "gender" => $getInfo["gender"],
                    "address" => $getInfo["address"],
                    "branch_id" => Auth()->user()->branch_id,
                    "member_level_id" => 1,
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);
            }

            //Kiểm tra tạo deal tự động
            $checkHaveDeal = $this->checkJourneyHaveDeal($input["journey_new"], $input["customer_lead_id"]);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công'),
                "create_deal" => $checkHaveDeal,
                "lead_id" => $input["customer_lead_id"]
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại') . $e->getMessage()
            ];
        }
    }

    /**
     * Function check value trong array có trùng không
     *
     * @param $array
     * @return bool
     */
    function array_has_dupes($array)
    {
        return count($array) !== count(array_unique($array));
    }

    /**
     * Lấy option view kanban
     *
     * @return array|mixed
     */
    public function optionViewKanban()
    {
        $mPipeline = new PipelineTable();
        $mManageTypeWork = new TypeWorkTable();

        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $listWorkType = $mManageTypeWork->getListDefault('ASC');

        return [
            'optionPipeline' => $optionPipeline,
            'listWorkType' => $listWorkType
        ];
    }

    /**
     * Lấy danh sách hành trình theo pipeline code
     * @param $pipelineCode
     * @return array|mixed
     */
    public function loadOptionJourney($pipelineCode)
    {
        $mJourney = new JourneyTable();
        $mPipeline = new PipelineTable();
        $optionJourney = $mJourney->getJourneyByPipeline($pipelineCode);
        $dataPipeline = $mPipeline->getDetailPipelineByCode($pipelineCode);
        return [
            'optionJourney' => $optionJourney,
            'time_revoke_lead' => $dataPipeline['time_revoke_lead']
        ];
    }

    /**
     * Chuyển đổi khách hàng không tạo deal
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function convertCustomerNoDeal($input)
    {
        try {
            // update is_convert
            $this->customerLead->edit([
                "is_convert" => 1,
            ], $input['customer_lead_id']);

            // kiểm tra sdt khách hàng đã có hay chưa
            $mCustomer = new \Modules\Admin\Models\CustomerTable();
            $customerLeadItem = $this->customerLead->getInfo($input['customer_lead_id']);

            $test_phone1 = $mCustomer->testPhone($customerLeadItem['phone'], 0);
            // Nếu sdt đã tồn tại, mà có tạo deal luôn thì lấy thông tin theo sdt đó
            if ($test_phone1 != "") {
                return response()->json([
                    'error' => true,
                    'message' => __('Số điện thoại đã tồn tại')
                ]);
            }
            $data = [
                //              'customer_group_id' => $request->customer_group_id,
                'full_name' => $customerLeadItem['full_name'],
                'birthday' => $customerLeadItem['birthday'],
                'gender' => $customerLeadItem['gender'],
                'phone1' => $customerLeadItem['phone'],
                'email' => $customerLeadItem['email'],
                'address' => $customerLeadItem['address'],
                'customer_source_id' => $customerLeadItem['customer_source'],
                'is_actived' => 1,
                'created_by' => Auth::id(),
            ];
            //Thêm khách hàng
            $idCustomer = $mCustomer->add($data);
            //Cập nhật mã khách hàng
            $customerCode = 'KH_' . date('dmY') . sprintf("%02d", $idCustomer);
            $mCustomer->edit([
                'customer_code' => $customerCode
            ], $idCustomer);

            //         update is_convert
            $this->customerLead->edit([
                'convert_object_type' => 'customer',
                'convert_object_code' => $customerCode,
            ], $input['customer_lead_id']);
            return response()->json([
                "error" => false,
                "message" => __("Chuyển đổi thành công")
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Chuyển đổi thất bại"),
                "_message" => $e->getMessage()
            ]);
        }
    }

    /**
     * View tao deal
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewCreateDeal($input)
    {
        // get data
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mStaff = new StaffsTable();
        $mOrderSource = new OrderSourceTable();
        $mJourney = new JourneyTable();
        $mCustomer = new \Modules\Admin\Models\CustomerTable();
        $mCustomerContact = new CustomerContactsTable();

        $optionTag = $mTag->getOption();
        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionStaff = $mStaff->getStaffOption();
        $optionOrderSource = $mOrderSource->getOption();
        $itemLead = $this->customerLead->getInfo($input['customer_lead_id']);
        $itemLead['tag_id'] = isset($itemLead['tag_id']) != '' ? explode(',', $itemLead['tag_id']) : [];
        $optionJourney = $mJourney->getOptionEdit($itemLead["pipeline_code"], $itemLead["journey_position"]);

        //         update is_convert
        // $this->customerLead->edit([
        //     "is_convert" => 1,
        //     'convert_object_type' => 'deal',
        // ], $input['customer_lead_id']);


        // Kiểm tra sdt
        //        $test_phone1 = $mCustomer->testPhone($itemLead['phone'], 0);
        //        if ($test_phone1 == "") {
        //            // insert KH
        //            $data = [
        //                'full_name' => $itemLead['full_name'],
        //                'birthday' => $itemLead['birthday'],
        //                'gender' => $itemLead['gender'],
        //                'phone1' => $itemLead['phone'],
        //                'email' => $itemLead['email'],
        //                'address' => $itemLead['address'],
        //                'customer_source_id' => $itemLead['customer_source'],
        //                'is_actived' => 1,
        //                'created_by' => Auth::id(),
        //            ];
        //            // thêm khách hàng
        //            $customerId = $mCustomer->add($data);
        //            $customerCode = 'KH_' . date('dmY') . $customerId;
        //            $mCustomer->edit(['customer_code' => $customerCode], $customerId);
        //            $itemLead['customer_code'] = $customerCode;
        //            // thêm liên hệ
        //            $dataContact = [
        //                'customer_id' => $customerId,
        //                'customer_code' => $customerCode,
        //                'contact_name' => $itemLead['full_name'],
        //                'contact_phone' => $itemLead['phone'],
        //                'contact_email' => $itemLead['email'],
        //                'full_address' => $itemLead['address'],
        //            ];
        //            $customerContactId = $mCustomerContact->add($dataContact);
        //            // generate customer contact code
        //            $customerContactCode = [
        //                'customer_contact_code' => 'CC_' . date('dmY') . $customerContactId
        //            ];
        //            // update customer contact code
        //            $mCustomerContact->edit($customerContactCode, $customerContactId);
        //
        //        } else {
        //            // get customer code
        //            $itemCustomer = $mCustomer->getItemByPhone($itemLead['phone']);
        //            $itemLead['customer_code'] = $itemCustomer['customer_code'];
        //            $itemLead['full_name'] = $itemCustomer['full_name'];
        //        }

        $optionContact = $mCustomerContact->getListContactByCustomerCode($itemLead['customer_code']);

        $html = \View::make('customer-lead::customer-lead.popup-create-deal', [
            "optionTag" => $optionTag,
            "optionPipeline" => $optionPipeline,
            "optionJourney" => $optionJourney,
            "optionStaff" => $optionStaff,
            "optionOrderSource" => $optionOrderSource,
            "optionContact" => $optionContact,
            "item" => $itemLead,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Export danh sach lead ra excel
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelAll($input)
    {
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();

        //Lay array customer_lead_code khi filter tag
        if (isset($input['tag_id']) && $input['tag_id'] != null) {
            $mMapCusTag = new MapCustomerTagTable();
            $listTag = $mMapCusTag->getListLeadByTagId($input['tag_id'])->toArray();

            $listCustomerCode = [];

            foreach ($listTag as $v) {
                $listCustomerCode[] = $v['customer_lead_code'];
            }
            $input['customer_tag'] = $listCustomerCode;
        }

        $arr_customer = [];
        //Danh sách khách hàng
        $list_customer = $this->customerLead->getAllCustomerLead($input);

        foreach ($list_customer as $item) {
            $customerLeadCode = $item['customer_lead_code'];
            $gender = '';
            if ($item['gender'] == 'other') {
                $gender = 'Khác';
            } elseif ($item['gender'] == 'male') {
                $gender = 'Nam';
            } elseif ($item['gender'] == 'female') {
                $gender = 'Nữ';
            }
            $customerType = '';
            $businessClue = '';
            if ($item['customer_type'] == 'personal') {
                $customerType = 'Cá nhân';
                // Đầu mối doanh nghiệp
                $lead = $this->customerLead->getLeadNameByCode($item['business_clue']);
                $lead != null ? $businessClue = $lead['full_name'] : '';
            } else if ($item['customer_type'] == 'business') {
                $customerType = 'Doanh nghiệp';
            }
            $dateRevoke = '';
            if ($item['date_revoke'] != null) {
                $dateRevoke = Carbon::createFromFormat('Y-m-d H:i:s', $item['date_revoke'])->format('d/m/Y');
            }
            // Phone: kèm theo
            $listPhone = $mCustomerPhone->getPhone($customerLeadCode)->toArray();
            $phoneAttach = '';
            if (count($listPhone) > 0) {
                foreach ($listPhone as $phone) {
                    $phoneAttach = $phoneAttach . $phone['phone'] . ', ';
                }
            }
            // Email: kèm theo
            $listEmail = $mCustomerEmail->getEmail($customerLeadCode)->toArray();
            $emailAttach = '';
            if (count($listEmail) > 0) {
                foreach ($listEmail as $email) {
                    $emailAttach = $emailAttach . $email['email'] . ', ';
                }
            }
            // Fanpage: kèm theo
            $listFanpage = $mCustomerFanpage->getFanpage($customerLeadCode)->toArray();
            $fanpageAttach = '';
            if (count($listFanpage) > 0) {
                foreach ($listFanpage as $fanpage) {
                    $fanpageAttach = $fanpageAttach . $fanpage['fanpage'] . ', ';
                }
            }
            // Contact (business): kèm theo
            $contactAttach = '';
            if ($item['customer_type'] == 'business') {
                $listContact = $mCustomerContact->getContact($customerLeadCode)->toArray();
                if (count($listContact) > 0) {
                    foreach ($listContact as $contact) {
                        $contactAttach = $contactAttach . $contact['full_name'] . ' - ' . $contact['phone'] . ' - '
                            . $contact['email'] . ' - ' . $contact['address'] . '; ' . chr(10);
                    }
                }
            }

            $arr_customer[] = [
                'customer_lead_id' => $item['customer_lead_id'],
                'customer_lead_code' => $item['customer_lead_code'],
                'full_name' => $item['full_name'],
                'email' => $item['email'],
                'phone' => $item['phone'],
                'gender' => $gender,
                'birthday' => $item['birthday'] != null ? Carbon::parse($item['birthday'])->format('d/m/Y') : '',
                'province_name' => $item['province_name'],
                'district_name' => $item['district_name'],
                'address' => $item['address'],
                'customer_type' => $customerType,
                'journey_name' => $item['journey_name'],
                'customer_source_name' => $item['customer_source_name'],
                'fanpage' => $item['fanpage'],
                'zalo' => $item['zalo'],
                'tax_code' => $item['tax_code'],
                'representative' => $item['representative'],
                'hotline' => $item['hotline'],
                'assign_name' => $item['assign_name'],
                'sale_name' => $item['sale_name'],
                'date_revoke' => $dateRevoke,
                'phone_attach' => $phoneAttach,
                'email_attach' => $emailAttach,
                'fanpage_attach' => $fanpageAttach,
                'contact_attach' => $contactAttach,
                'business_clue' => $businessClue,
                'content_care' => $item['content_care'],
            ];
        }
        //Data export
        $arr_data = [];
        foreach ($arr_customer as $key => $item) {
            $arr_data[] = [
                $key + 1,
                'name' => $item['full_name'],
                'phone' => $item['phone'],
                'phone_attach' => $item['phone_attach'],
                'birthday' => $item['birthday'],
                'gender' => $item['gender'],
                'email' => $item['email'],
                'email_attach' => $item['email_attach'],
                'province_name' => $item['province_name'],
                'district_name' => $item['district_name'],
                'address' => $item['address'],
                'customer_type' => $item['customer_type'],
                'journey_name' => $item['journey_name'],
                'customer_source_name' => $item['customer_source_name'],
                'fanpage' => $item['fanpage'],
                'fanpage_attach' => $item['fanpage_attach'],
                'zalo' => $item['zalo'],
                'assign_name' => $item['assign_name'],
                'sale_name' => $item['sale_name'],
                'date_revoke' => $item['date_revoke'],
                'business_clue' => $item['business_clue'],
                'tax_code' => $item['tax_code'],
                'representative' => $item['representative'],
                'hotline' => $item['hotline'],
                'contact_attach' => $item['contact_attach'],
                'content_care' => $item['content_care'],
            ];
        }
        $heading = [
            __('STT'),
            __('Họ & Tên'),
            __('Số điện thoại'),
            __('SDT kèm theo'),
            __('Ngày sinh'),
            __('Giới tính'),
            __('Email'),
            __('Email kèm theo'),
            __('Tỉnh/ Thành'),
            __('Quận/ Huyện'),
            __('Địa chỉ'),
            __('Loại khách hàng'),
            __('Hành trình hiện tại'),
            __('Nguồn khách hàng'),
            __('Fanpage'),
            __('Fanpage kèm theo'),
            __('Zalo'),
            __('Người phân bổ'),
            __('Người được phân bổ'),
            __('Ngày hết hạn phân bổ'),
            __('Đầu mối doanh nghiệp'),
            __('Mã số thuế'),
            __('Người đại diện'),
            __('Hotline'),
            __('Contact kèm theo (Tên - SDT - Email - Địa chỉ)'),
            __('Lịch sử chăm sóc')
        ];
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ExportFile($heading, $arr_data), 'customer_lead.xlsx');
    }

    /**
     * Lay danh sach tag
     *
     * @return mixed|void
     */
    public function getListTag()
    {
        $mTag = new TagTable();
        $listTag = $mTag->getOption();

        $array = [];
        foreach ($listTag as $item) {
            $array[$item['tag_id']] = $item['name'];
        }
        return $array;
    }

    /**
     * Lấy danh sách nhân viên
     *
     * @return mixed|void
     */
    public function getListStaff()
    {
        $mStaff = new StaffsTable();

        $listStaff = $mStaff->getListStaff();

        $array = [];
        foreach ($listStaff as $item) {
            $array[$item['staff_id']] = $item['full_name'];
        }
        return $array;
    }

    /**
     * Lấy danh sách pipeline
     *
     * @return array|mixed
     */
    public function getListPipeline()
    {
        $mPipeline = app()->get(PipelineTable::class);

        $listPipeline = $mPipeline->getOption('CUSTOMER');

        $array = [];
        foreach ($listPipeline as $item) {
            $array[$item['pipeline_code']] = $item['pipeline_name'];
        }
        return $array;
    }

    /**
     * import khach hang tiem nang
     *
     * @param $file
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function importExcel($file)
    {
        try {
            if (isset($file)) {
                $typeFileExcel = $file->getClientOriginalExtension();

                if ($typeFileExcel == "xlsx") {
                    $reader = ReaderFactory::create(Type::XLSX);
                    $reader->open($file);

                    //Khai báo model
                    $mPipeline = new PipelineTable();
                    $mJourney = new JourneyTable();
                    $mCusSource = new CustomerSourceTable();
                    $mCusPhone = new CustomerPhoneTable();
                    $mCusEmail = new CustomerEmailTable();
                    $mCusFanpage = new CustomerFanpageTable();
                    $mProvince = new ProvinceTable();
                    $mDistrict = new DistrictTable();

                    $arrError = [];
                    $numberSuccess = 0;
                    $numberError = 0;

                    // sẽ trả về các object gồm các sheet
                    foreach ($reader->getSheetIterator() as $sheet) {
                        // đọc từng dòng
                        foreach ($sheet->getRowIterator() as $key => $row) {
                            $colName = strip_tags(isset($row[1]) ? $row[1] : '');
                            $colPhone = strip_tags(isset($row[2]) ? $row[2] : '');
                            $colPhoneAttach = strip_tags(isset($row[3]) ? $row[3] : '');
                            $colBirthday = strip_tags(isset($row[4]) ? $row[4] : '');
                            $colGender = strip_tags(isset($row[5]) ? $row[5] : '');
                            $colEmail = strip_tags(isset($row[6]) ? $row[6] : '');
                            $colEmailAttach = strip_tags(isset($row[7]) ? $row[7] : '');
                            $colProvince = strip_tags(isset($row[8]) ? $row[8] : '');
                            $colDisitrct = strip_tags(isset($row[9]) ? $row[9] : '');
                            $colAddress = strip_tags(isset($row[10]) ? $row[10] : '');
                            $colCustomerType = strip_tags(isset($row[11]) ? $row[11] : '');
                            $colPipeline = strip_tags(isset($row[12]) ? $row[12] : '');
                            $colCustomerSource = strip_tags(isset($row[13]) ? $row[13] : ''); // Nguồn khách hàng
                            $colBusinessClue = strip_tags(isset($row[14]) ? $row[14] : ''); // Đầu mới doanh nghiệp
                            $colFanpage = strip_tags(isset($row[15]) ? $row[15] : '');
                            $colFanpageAttach = strip_tags(isset($row[16]) ? $row[16] : '');
                            $colZalo = strip_tags(isset($row[17]) ? $row[17] : '');
                            $colTag = strip_tags(isset($row[18]) ? $row[18] : '');
                            $colSaleId = strip_tags(isset($row[19]) ? $row[19] : ''); // Người được phân bổ
                            $colTaxCode = strip_tags(isset($row[20]) ? $row[20] : '');  // Mã số thuế
                            $colRepresentative = strip_tags(isset($row[21]) ? $row[21] : ''); // Người đại diện
                            $colHotline = strip_tags(isset($row[22]) ? $row[22] : '');
                            //                        $colContactAttach = $row[21]; // Contact kèm theo

                            //Lưu log lỗi có gì xuất excel
                            $errorRow = [
                                'full_name' => isset($row[1]) ? $row[1] : '',
                                'phone' => isset($row[2]) ? $row[2] : '',
                                'phone_attack' => isset($row[3]) ? $row[3] : '',
                                'birthday' => isset($row[4]) ? $row[4] : '',
                                'gender' => isset($row[5]) ? $row[5] : '',
                                'email' => isset($row[6]) ? $row[6] : '',
                                'email_attach' => isset($row[7]) ? $row[7] : '',
                                'province_name' => isset($row[8]) ? $row[8] : '',
                                'district_name' => isset($row[9]) ? $row[9] : '',
                                'address' => isset($row[10]) ? $row[10] : '',
                                'customer_type' => isset($row[11]) ? $row[11] : '',
                                'pipeline' => isset($row[12]) ? $row[12] : '',
                                'customer_source' => isset($row[13]) ? $row[13] : '',
                                'business_clue' => isset($row[14]) ? $row[14] : '',
                                'fanpage' => isset($row[15]) ? $row[15] : '',
                                'fanpage_attack' => isset($row[16]) ? $row[16] : '',
                                'zalo' => isset($row[17]) ? $row[17] : '',
                                'tag' => isset($row[18]) ? $row[18] : '',
                                'sale_id' => isset($row[19]) ? $row[19] : '',
                                'tax_code' => isset($row[20]) ? $row[20] : '',
                                'representative' => isset($row[21]) ? $row[21] : '',
                                'hotline' => isset($row[22]) ? $row[22] : '',
                                'error' => ''
                            ];

                            //check sdt đúng quy tắc
                            if ($key != 1) {
                                //Kiểm tra định dạng sđt
                                $checkFormatPhone = $this->checkPhoneNumberVN($colPhone, $resPhone);

                                if ($checkFormatPhone == false) {
                                    $errorRow['error'] .= __('Số điện thoại không hợp lệ') . ';';
                                }

                                // check sdt tồn tại
                                $testPhone = $this->customerLead->testPhone($colPhone, 0);

                                // Nếu sdt đã tồn tại
                                if ($testPhone != '') {
                                    $errorRow['error'] .= __('Số điện thoại đã tồn tại') . ';';
                                }
                                // Gender
                                $colGender == 1 ? $gender = 'male' : $gender = 'female';
                                //Lấy thông tin tỉnh/thành
                                $provinceId = null;

                                if (!empty($colProvince)) {
                                    $getProvince = $mProvince->getProvinceByName($colProvince);

                                    $provinceId = $getProvince != null ? $getProvince['provinceid'] : null;
                                }
                                //Lấy thông tin quận/huyện
                                $districtId = null;

                                if ($provinceId != null && !empty($colDisitrct)) {
                                    $getDistrict = $mDistrict->getDistrictByName($provinceId, $colDisitrct);

                                    $districtId = $getDistrict != null ? $getDistrict['districtid'] : null;
                                }
                                // Address
                                $colAddress != '' ? $address = strip_tags($colAddress) : $address = '';
                                // Birthday: datetime của excel (chưa xử lý)
                                $birthday = null;
                                if (is_string($colBirthday) && !empty($colBirthday)) {
                                    $checkFormatDate = $this->validateDate($colBirthday);

                                    if ($checkFormatDate == false) {
                                        $errorRow['error'] .= __('Ngày sinh không đúng định dạng') . ';';
                                    } else {
                                        $birthday = Carbon::createFromFormat('d/m/Y', $colBirthday)->format('Y-m-d');
                                    }
                                }

                                // Check pipeline
                                $pipelineCode = null;
                                //Lấy pipeline
                                $checkPipeline = $mPipeline->getCodePipelineByName($colPipeline);

                                if ($checkPipeline != null) {
                                    //Có thông tin pipeline
                                    $pipelineCode = $checkPipeline['pipeline_code'];
                                } else {
                                    //Không có thông tin pipeline thì lấy pipeline mặc định
                                    $getPipelineDefault = $mPipeline->getPipelineDefault();

                                    if ($getPipelineDefault != null) {
                                        $pipelineCode = $getPipelineDefault['pipeline_code'];
                                    } else {
                                        $errorRow['error'] .= __('Pipeline không tồn tại') . ';';
                                    }
                                }


                                // Journey: mặc định là new
                                $journeyCode = null;
                                $checkJourney = $mJourney->getJourneyCodeByName($pipelineCode);

                                if ($checkJourney != null) {
                                    $journeyCode = $checkJourney['journey_code'];
                                } else {
                                    $errorRow['error'] .= __('Hành trình không tồn tại') . ';';
                                }

                                if (!empty($errorRow['error'])) {
                                    $numberError++;
                                    $arrError[] = $errorRow;
                                    continue;
                                }

                                // Check customer source: không có thì insert
                                $cusSource = null;
                                $checkCusSource = $mCusSource->getIdByName($colCustomerSource);
                                if ($checkCusSource != null) {
                                    $cusSource = $checkCusSource['customer_source_id'];
                                } else {
                                    $cusSource = $mCusSource->add([
                                        "customer_source_name" => $colCustomerSource,
                                        "customer_source_type" => '',
                                        "created_by" => Auth()->id(),
                                        "slug" => str_slug($colCustomerSource)
                                    ]);
                                }
                                // data lead
                                $data = [
                                    "customer_type" => $colCustomerType,
                                    "full_name" => $colName,
                                    "email" => $colEmail,
                                    "phone" => $resPhone[0],
                                    "gender" => $gender,
                                    "province_id" => $provinceId,
                                    "district_id" => $districtId,
                                    "address" => $address,
                                    "birthday" => $birthday,
                                    "pipeline_code" => $pipelineCode,
                                    "journey_code" => $journeyCode,
                                    "customer_source" => $cusSource,
                                    "fanpage" => $colFanpage,
                                    "zalo" => $colZalo,
                                    "created_by" => Auth()->id(),
                                    "updated_by" => Auth()->id()
                                ];
                                // Check người phân bổ (assign_by): Nếu tồn tại người được phân bổ thì mới insert người phân bổ
                                if (isset($colSaleId) && $colSaleId != null) {
                                    $data['assign_by'] = Auth()->id();
                                }
                                // Check đầu mối doanh nghiệp
                                $data['business_clue'] = null;
                                if ($colCustomerType != "business") {
                                    // Dò trong cpo_customer_lead
                                    $checkBusinessClue = $this->customerLead->getLeadByNameCustomer($colBusinessClue);
                                    if ($checkBusinessClue != null) {
                                        $data['business_clue'] = $checkBusinessClue['customer_lead_code'];
                                    }
                                } else {
                                    $data['tax_code'] = $colTaxCode;
                                    $data['representative'] = $colRepresentative;
                                    $data['hotline'] = $colHotline;
                                }
                                //Insert customer lead
                                $customerLeadId = $this->customerLead->add($data);

                                //Update customer_lead_code
                                $leadCode = "LEAD_" . date("dmY") . sprintf("%02d", $customerLeadId);
                                $this->customerLead->edit([
                                    "customer_lead_code" => $leadCode
                                ], $customerLeadId);

                                // Insert SDT kèm theo
                                $arrPhone = [];
                                if ($colPhoneAttach != '') {
                                    $arrTemp = explode(",", $colPhoneAttach);
                                    if (count($arrTemp) > 0) {
                                        foreach ($arrTemp as $v) {
                                            $arrPhone[] = [
                                                'customer_lead_code' => $leadCode,
                                                'phone' => $v
                                            ];
                                        }
                                        $mCusPhone->insert($arrPhone);
                                    }
                                }
                                // Insert email kèm theo
                                $arrEmail = [];
                                if ($colEmailAttach != '') {
                                    $arrTemp = explode(",", $colEmailAttach);
                                    if (count($arrTemp) > 0) {
                                        foreach ($arrTemp as $v) {
                                            $arrEmail[] = [
                                                'customer_lead_code' => $leadCode,
                                                'email' => $v
                                            ];
                                        }
                                        $mCusEmail->insert($arrEmail);
                                    }
                                }
                                // Insert fanpage kèm theo
                                $arrFanpage = [];
                                if ($colFanpageAttach != '') {
                                    $arrTemp = explode(",", $colFanpageAttach);
                                    if (count($arrTemp) > 0) {
                                        foreach ($arrTemp as $v) {
                                            $arrFanpage[] = [
                                                'customer_lead_code' => $leadCode,
                                                'email' => $v
                                            ];
                                        }
                                        $mCusFanpage->insert($arrFanpage);
                                    }
                                }

                                // Check tag
                                $this->checkInsertTag($colTag, $leadCode);

                                //Thành công
                                $numberSuccess++;
                            }
                        }
                    }

                    $reader->close();

                    return response()->json([
                        'success' => 1,
                        'message' => __('Số dòng thành công') . ':' . $numberSuccess . '<br/>' . __('Số dòng thất bại') . ':' . $numberError,
                        'number_error' => $numberError,
                        'data_error' => $arrError
                    ]);
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => __('File không đúng định dạng')
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => __('Import thông tin khách hàng thất bại'),
                '_message' => $e->getMessage() . ' ' . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * Kiểm tra nếu không tồn tại tag thì insert tag
     * Insert Map customer tag
     *
     * @param $input
     * @param $leadCode
     */
    public function checkInsertTag($input, $leadCode)
    {
        $arrTag = [];
        $mTag = new TagTable();
        if ($input != null) {
            $arrTag = explode(",", $input);
            if (!empty($arrTag) && count($arrTag) > 0) {
                $mMapCustomerTag = new MapCustomerTagTable();
                foreach ($arrTag as $value) {
                    // search exist tag
                    $tag = null;
                    $checkTag = $mTag->getIdByTagName($value);
                    if ($checkTag != null) {
                        $dataTag = [
                            'customer_lead_code' => $leadCode,
                            'tag_id' => $checkTag['tag_id']
                        ];
                        $mMapCustomerTag->add($dataTag);
                    } else {
                        $idTagNew = $mTag->add([
                            'type' => 'tag',
                            'keyword' => str_slug($value),
                            'name' => $value,
                        ]);
                        $dataTag = [
                            'customer_lead_code' => $leadCode,
                            'tag_id' => $idTagNew
                        ];
                        $mMapCustomerTag->add($dataTag);
                    }
                }
            }
        }
    }

    /**
     * View danh sách nhân viên
     *
     * @param $input
     * @return array|mixed
     */
    public function popupListStaff($input)
    {
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getListStaff();

        $html = \View::make('customer-lead::customer-lead.popup-list-staffs', [
            "optionStaff" => $optionStaff,
            "customer_lead_id" => $input['customer_lead_id']
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Phân công nhân viên
     *
     * @param $input
     * @return array|mixed
     */
    public function saveAssignStaff($input)
    {
        try {
            $mCustomerLead = new CustomerLeadTable();
            $mPipeline = new PipelineTable();
            $staffId = $input['staff_id'];
            if ($staffId == "") {
                return [
                    'error' => true,
                    'message' => __('Hãy chọn nhân viên đuọc phân công')
                ];
            }

            $customerLeadId = $input['customer_lead_id'];
            // LẤy thông tin của lead -> get pipeline
            $infoLead = $mCustomerLead->getInfo($customerLeadId);
            // Từ pipeline lấy số giờ tối đa để lead chuyển đổi
            $infoPipeline = $mPipeline->getDetailByCode($infoLead['pipeline_code']);
            $maxTime = 0;
            if (isset($infoPipeline['time_revoke_lead']) && $infoPipeline['time_revoke_lead'] != null) {
                $maxTime = (int)$infoPipeline['time_revoke_lead'];
            }

            // Cập nhật customer lead: người được phân công và người phân công
            $timeNow = Carbon::now();
            $dataEdit = [
                'assign_by' => Auth::id(),
                'sale_id' => $staffId,
                'date_revoke' => $timeNow->addDay($maxTime),
                'allocation_date' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            $mCustomerLead->edit($dataEdit, $customerLeadId);
            return [
                'error' => false,
                'message' => __('Phân công nhân viên thành công')
            ];
        } catch (\Exception $e) {
            if ($staffId == "") {
                return [
                    'error' => true,
                    'message' => __('Phân công nhân viên thất bại')
                ];
            }
        }
    }

    /**
     * Thu hồi 1 dòng
     *
     * @param $input
     * @return array|mixed
     */
    public function revokeOne($input)
    {
        try {
            $customerLeadId = $input['customer_lead_id'];
            $dataEdit = [
                'assign_by' => null,
                'sale_id' => null,
                'date_revoke' => null,
                'allocation_date' => null
            ];
            $this->customerLead->edit($dataEdit, $customerLeadId);

            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * View phân bổ nhiều sale, nhiều lead
     *
     * @return array
     */
    public function dataViewAssign()
    {
        $mDepartment = new DepartmentTable();
        $optionDepartment = $mDepartment->getOption();
        return [
            'optionDepartment' => $optionDepartment,
        ];
    }

    /**
     * phân bổ nhiều sale, nhiều lead
     *
     * @param $input
     * @return array|mixed
     */
    public function submitAssign($input)
    {
        try {
            $arrStaff = $input['arrStaff'];
            $arrLead = [];
            $arrAssign = []; // Mảng sau khi phân bổ
            if (session()->get('lead_temp')) {
                $arrLead = session()->get('lead_temp');
            }
            //            dd($arrStaff, $arrLead);
            // Phân bổ đều lead cho mỗi staff
            $amountLead = count($arrLead);
            $amountStaff = count($arrStaff);
            if ($amountLead > 0 && $amountStaff > 0) {
                $i = 0;
                foreach ($arrLead as $value) {
                    $arrAssign[] = [
                        'customer_lead_id' => $value['customer_lead_id'],
                        'sale_id' => $arrStaff[$i % $amountStaff],
                        'time_revoke_lead' => $value['time_revoke_lead']
                    ];
                    $i++;
                }
            } else {
                return [
                    'error' => true,
                    'message' => __('Hãy chọn lead')
                ];
            }
            // Update sale_id trong table cpo_customer_lead
            if (count($arrAssign) > 0) {
                $timeNow = Carbon::now();
                foreach ($arrAssign as $value) {
                    // Lấy thời gian tối đa lead chuyển đổi
                    $timeMax = $value['time_revoke_lead'];
                    $this->customerLead->edit([
                        'sale_id' => $value['sale_id'],
                        'assign_by' => Auth::id(),
                        'date_revoke' => $timeNow->addDay($timeMax),
                        'allocation_date' => Carbon::now()->format('Y-m-d H:i:s')
                    ], $value['customer_lead_id']);
                }
            }
            // Xoa session
            if (session()->get('lead_temp')) {
                session()->forget('lead_temp');
            }
            return [
                'error' => false,
                'message' => __('Phân bổ thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Phân bổ thất bại')
            ];
        }
    }

    /**
     * Popup thu hỒi lead theo sale id
     *
     * @param $input
     * @return array|mixed
     */
    public function popupRevoke($input)
    {
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getListStaff();

        $html = \View::make('customer-lead::customer-lead.popup-revoke', [
            "optionStaff" => $optionStaff,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Sumit thu hồi
     *
     * @param $input
     * @return array|mixed
     */
    public function submitRevoke($input)
    {
        try {
            $mCustomerLead = new CustomerLeadTable();
            $staffId = $input['staff_id'];
            if ($staffId == "") {
                return [
                    'error' => true,
                    'message' => __('Hãy chọn nhân viên đuọc phân công')
                ];
            }
            // Thu hồi lead theo staff id (xoá data assign_by, sale_id, date_revoke)

            $dataEdit = [
                'assign_by' => null,
                'sale_id' => null,
                'date_revoke' => null,
                'allocation_date' => null
            ];
            $mCustomerLead->editWithStaffId($dataEdit, $staffId);
            return [
                'error' => false,
                'message' => __('Thu hồi thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thu hồi thất bại')
            ];
        }
    }

    /**
     * Danh sách nguồn khách hàng
     *
     * @return array|mixed
     */
    public function getListCustomerSource()
    {
        $mCustomerSource = new CustomerSourceTable();
        $listCS = $mCustomerSource->getOption();
        $array = [];
        foreach ($listCS as $item) {
            $array[$item['customer_source_id']] = $item['customer_source_name'];
        }
        return $array;
    }

    /**
     * ajax, phân trang danh sách lead chưa phân bổ
     *
     * @param $filter
     * @return array
     */
    public function listLeadNotAssignYet($filter)
    {
        if (!in_array('customer-lead.permission-assign-revoke', session('routeList'))) {
            $filter['user_id'] = Auth()->id();
        }
        $list = $this->customerLead->listLeadNotAssignYet($filter);
        return [
            'list' => $list,
        ];
    }

    /**
     * Danh sách sale theo mảng department
     *
     * @param $input
     * @return array|mixed
     */
    public function loadOptionSale($input)
    {
        $mStaff = new StaffsTable();
        $arrDepartment = [];
        if (count($input) > 0) {
            foreach ($input as $item => $v) {
                $arrDepartment = $v;
            }
        }
        $optionStaff = $mStaff->getOptionStaffByDepartment($arrDepartment);
        return [
            'optionStaff' => $optionStaff
        ];
    }

    /**
     * Chọn all trên 1 page lead
     *
     * @param $data
     * @return mixed
     */
    public function chooseAll($data)
    {
        //Get session 9
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge vào array temp
        $arrLeadNew = [];
        if (count($data['arr_check']) > 0) {
            foreach ($data['arr_check'] as $v) {
                $arrLeadNew[$v['customer_lead_code']] = [
                    'customer_lead_id' => $v['customer_lead_id'],
                    'customer_lead_code' => $v['customer_lead_code'],
                    'time_revoke_lead' => $v['time_revoke_lead'],
                ];
            }
        }
        //Merge 2 array temp + new
        $arrLeadTempNew = array_merge($arrLeadTemp, $arrLeadNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrLeadTempNew, $arrLead);
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
    }

    /**
     * Chọn lead
     *
     * @param $data
     * @return mixed
     */
    public function choose($data)
    {
        //Get session main
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge vào array temp
        $arrLeadNew = [
            $data['customer_lead_code'] => [
                'customer_lead_id' => $data['customer_lead_id'],
                'customer_lead_code' => $data['customer_lead_code'],
                'time_revoke_lead' => $data['time_revoke_lead'],
            ]
        ];
        //Merge 2 array temp + new
        $arrLeadTempNew = array_merge($arrLeadTemp, $arrLeadNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrLeadTempNew, $arrLead);
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
    }

    /**
     * Bỏ chọn all trên 1 page lead
     *
     * @param $data
     * @return mixed
     */
    public function unChooseAll($data)
    {
        //Get session 9
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrLeadTemp, $arrLead);
        $arrRemoveLeadTemp = [];
        //Unset phần tử
        if (count($data['arr_un_check']) > 0) {
            foreach ($data['arr_un_check'] as $v) {
                $arrRemoveLeadTemp[] = $v['customer_lead_code'];
                unset($arrResult[$v['customer_lead_code']]);
            }
        }
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
        //Get session remove temp
        if (session()->get('remove_lead')) {
            $arrRemoveLeadTemp = session()->get('remove_lead');
        }
        //Lưu session remove temp
        session()->forget('remove_lead');
        session()->put('remove_lead', $arrRemoveLeadTemp);
    }

    /**
     * Bỏ chọn lead
     *
     * @param $data
     * @return mixed
     */
    public function unChoose($data)
    {
        //Get session 9
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrLeadTemp, $arrLead);
        //Unset phần tử
        unset($arrResult[$data['customer_lead_code']]);
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
        //Get session remove temp
        $arrRemoveLeadTemp = [];
        if (session()->get('remove_lead')) {
            $arrRemoveLeadTemp = session()->get('remove_lead');
        }
        //Lưu session remove temp
        $arrRemoveLeadTemp[] = $data['customer_lead_code'];
        session()->forget('remove_lead');
        session()->put('remove_lead', $arrRemoveLeadTemp);
    }

    /**
     * Chọn, bỏ chọn tất cả lead theo filter
     *
     * @param $input
     */
    public function checkAllLead($input)
    {
        // Xoá hết session lead cũ
        if (session()->get('lead')) {
            session()->forget('lead');
        }
        if (session()->get('lead_temp')) {
            session()->forget('lead_temp');
        }
        if (session()->get('remove_lead')) {
            session()->forget('remove_lead');
        }
        // Nếu check all thì lưu lại session tất cả lead
        if ($input['is_check_all']) {
            // Lấy danh sách lead
            $list = $this->customerLead->listLeadNotAssignYetNotPaginate($input);
            $arrLead = [];
            if (count($list) > 0) {
                foreach ($list as $v) {
                    $arrLead[$v['customer_lead_code']] = [
                        'customer_lead_id' => $v['customer_lead_id'],
                        'customer_lead_code' => $v['customer_lead_code'],
                        'time_revoke_lead' => $v['time_revoke_lead'],
                    ];
                }
            }
            session()->put('lead_temp', $arrLead);
        } else {
            if (session()->get('lead_temp')) {
                session()->forget('lead_temp');
            }
        }
    }

    /**
     * Check sdt vietnam
     *
     * @param $phone
     * @param $res
     * @return bool
     */
    protected function checkPhoneNumberVN($phone, &$res)
    {
        $regex = "/^(0|\+84)(\s|\.)?((3[2-9])|(5[689])|(7[06-9])|(8[1-689])|(9[0-46-9]))(\d)(\s|\.)?(\d{3})(\s|\.)?(\d{3})$/";
        if (preg_match($regex, $phone, $res)) {
            return true;
        }
        return false;
    }

    /**
     * Validate date
     *
     * @param $date
     * @param string $format
     * @return bool
     */
    private function validateDate($date, $format = 'd/m/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Export excel file lỗi
     *
     * @param $input
     * @return mixed|void
     */
    public function exportError($input)
    {
        $header = [
            __('STT'),
            __('TÊN KHÁCH HÀNG'),
            __('SỐ ĐIỆN THOẠI'),
            __('SDT KÈM THEO'),
            __('NGÀY SINH'),
            __('GIỚI TÍNH'),
            __('EMAIL'),
            __('EMAIL KÈM THEO'),
            __('TỈNH/ THÀNH'),
            __('QUẬN/ HUYỆN'),
            __('ĐỊA CHỈ'),
            __('LOẠI KHÁCH HÀNG'),
            __('PIPELINE'),
            __('NGUỒN KHÁCH HÀNG'),
            __('ĐẦU MỐI DOANH NGHIỆP'),
            __('FANPAGE'),
            __('FANPAGE KÈM THEO'),
            __('ZALO'),
            __('TAG'),
            __('NGƯỜI ĐƯỢC PHÂN BỔ'),
            __('MÃ SỐ THUẾ'),
            __('NGƯỜI ĐẠI DIỆN'),
            __('HOTLINE'),
            __('LỖI')
        ];

        $data = [];

        if (isset($input['full_name']) && count($input['full_name']) > 0) {
            foreach ($input['full_name'] as $k => $v) {
                $data[] = [
                    $k + 1,
                    isset($v) ? $v : '',
                    isset($input['phone'][$k]) ? $input['phone'][$k] : '',
                    isset($input['phone_attack'][$k]) ? $input['phone_attack'][$k] : '',
                    isset($input['birthday'][$k]) ? $input['birthday'][$k] : '',
                    isset($input['gender'][$k]) ? $input['gender'][$k] : '',
                    isset($input['email'][$k]) ? $input['email'][$k] : '',
                    isset($input['email_attach'][$k]) ? $input['email_attach'][$k] : '',
                    isset($input['province_name'][$k]) ? $input['province_name'][$k] : '',
                    isset($input['district_name'][$k]) ? $input['district_name'][$k] : '',
                    isset($input['address'][$k]) ? $input['address'][$k] : '',
                    isset($input['customer_type'][$k]) ? $input['customer_type'][$k] : '',
                    isset($input['pipeline'][$k]) ? $input['pipeline'][$k] : '',
                    isset($input['customer_source'][$k]) ? $input['customer_source'][$k] : '',
                    isset($input['business_clue'][$k]) ? $input['business_clue'][$k] : '',
                    isset($input['fanpage'][$k]) ? $input['fanpage'][$k] : '',
                    isset($input['fanpage_attack'][$k]) ? $input['fanpage_attack'][$k] : '',
                    isset($input['zalo'][$k]) ? $input['zalo'][$k] : '',
                    isset($input['tag'][$k]) ? $input['tag'][$k] : '',
                    isset($input['sale_id'][$k]) ? $input['sale_id'][$k] : '',
                    isset($input['tax_code'][$k]) ? $input['tax_code'][$k] : '',
                    isset($input['representative'][$k]) ? $input['representative'][$k] : '',
                    isset($input['hotline'][$k]) ? $input['hotline'][$k] : '',
                    isset($input['error'][$k]) ? $input['error'][$k] : '',
                ];
            }
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($header, $data), 'error-lead.xlsx');
    }

    /**
     * Tạo deal tự động
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function createDealAuto($input)
    {
        try {
            $mCustomerLead = new CustomerLeadTable();
            $mPipeline = new PipelineTable();
            $mJourney = new JourneyTable();
            $mTag = new TagTable();
            $mStaff = new StaffsTable();
            $mOrderSource = new OrderSourceTable();
            $mCustomerContact = new CustomerContactsTable();

            //Lấy thông tin lead
            $getLead = $mCustomerLead->getInfo($input['customer_lead_id']);
            //Lấy pipeline mặc định của deal
            $getPipeline = $mPipeline->getPipelineDealDefault();

            $optionJourney = [];
            $journeyNew = null;

            if ($getPipeline != null) {
                //Lấy hành trình mới của deal
                $getJourneyNew = $mJourney->getJourneyNew($getPipeline['pipeline_code']);

                if ($getJourneyNew != null) {
                    $journeyNew = $getJourneyNew['journey_code'];
                }

                //Lấy list hành trình theo pipeline
                $optionJourney = $mJourney->getJourneyByPipeline($getPipeline['pipeline_code']);
            }

            //Lấy thông tin tag
            $optionTag = $mTag->getOption();
            //Lấy option pipeline deal
            $optionPipeline = $mPipeline->getOption('DEAL');
            //Lấy option nhân viên
            $optionStaff = $mStaff->getStaffOption();
            //Lấy option nguồn đơn hàng
            $optionOrderSource = $mOrderSource->getOption();
            //Lấy thông tin tag_id
            $getLead['tag_id'] = isset($itemLead['tag_id']) != '' ? explode(',', $getLead['tag_id']) : [];
            //Lấy thông tin liên hệ
            $optionContact = $mCustomerContact->getListContactByCustomerCode($getLead['customer_code']);

            $html = \View::make('customer-lead::customer-lead.popup-create-deal', [
                "optionTag" => $optionTag,
                "optionPipeline" => $optionPipeline,
                "optionJourney" => $optionJourney,
                "optionStaff" => $optionStaff,
                "optionOrderSource" => $optionOrderSource,
                "optionContact" => $optionContact,
                "item" => $getLead,
                "pipelineChoose" => $getPipeline,
                "journeyChoose" => $journeyNew
            ])->render();


            return response()->json([
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Tạo deal tự động thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Show modal gọi on call
     *
     * @param $input
     * @return mixed|void
     */
    public function showModalCall($input)
    {
        $mCustomerPhone = new CustomerPhoneTable();

        //Lấy thông tin KH tiềm năng
        $item = $this->customerLead->getInfo($input['customer_lead_id']);
        //Lấy thông tin sđt kèm theo
        $arrPhone = $mCustomerPhone->getPhone($item["customer_lead_code"]);

        $html = \View::make('customer-lead::customer-lead.popup-call', [
            "item" => $item,
            "arrPhone" => $arrPhone
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Gọi (on call)
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call($input)
    {
        try {
            $mExtension = app()->get(ExtensionTable::class);
            $mCustomerCare = new CustomerCareTable();
            $mHistory = app()->get(HistoryTable::class);

            //Kiểm tra định dạng sđt
            $checkFormatPhone = $this->checkPhoneNumberVN($input['phone'], $resPhone);

            if ($checkFormatPhone == false) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số điện thoại không hợp lệ'),
                ]);
            }

            //Lấy thông tin extension
            $infoExtension = $mExtension->getInfoByStaff(Auth()->id());

            if (empty($infoExtension)) {
                return response()->json([
                    'error' => true,
                    'message' => __('Không tìm thấy extension'),
                ]);
            }

            //Lấy thông tin KH tiềm năng
            $item = $this->customerLead->getInfo($input['customer_lead_id']);

            //Lưu history
            $idHistory = $mHistory->add([
                'object_id_call' => Auth()->id(),
                'extension_number' => $infoExtension['extension_number'],
                'source_code' => 'lead',
                'object_id' => $item['customer_lead_id'],
                'object_code' => $item['customer_lead_code'],
                'object_name' => $item['full_name'],
                'object_phone' => $input['phone'],
                'history_type' => 'out', //Cuộc gọi đi
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            //Call api gọi (on call)
            $call = $this->_apiCall($infoExtension['extension_number'], $input['phone']);

            if ($call->ErrorCode == 1) {
                //Cập nhật lưu lỗi lịch sử
                $mHistory->edit([
                    'error_text' => $call->ErrorDescription
                ], $idHistory);

                return response()->json([
                    'error' => true,
                    'message' => $call->ErrorDescription,
                ]);
            }

            //Call thành công thì update history lại
            $mHistory->edit([
                'uid' => $call->Data->uid,
            ], $idHistory);

            //Lấy lịch sử chăm sóc KH
            $getCare = collect($mCustomerCare->getCustomerCare($item['customer_lead_code'])->toArray());

            $dataCare = $getCare->groupBy('created_group');

            if (count($dataCare) > 0) {
                foreach ($dataCare as $k => $v) {
                    $dataCare[$k] = $v->sortBy('created_at');
                }
            }
            $mManageTypeWork = new TypeWorkTable();
            $listTypeWork = $mManageTypeWork->getListTypeWork(1);
            //Load view chăm sóc
            $html = \View::make('customer-lead::customer-lead.popup-customer-care', [
                'customer_lead_id' => $input['customer_lead_id'],
                'dataCare' => $dataCare,
                'careType' => 'call',
                'listTypeWork' => $listTypeWork,
                'historyId' => $idHistory
            ])->render();

            return response()->json([
                'error' => false,
                'message' => __('Gọi thành công'),
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Gọi thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }


    /**
     * Gọi api thực hiện cuộc gọi
     *
     * @param $extension
     * @param $phone
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _apiCall($extension, $phone)
    {
        $oClient = new Client();

        $mConfig = app()->get(ConfigTable::class);
        //Lấy thông tin cấu hình key + secret
        $key = $mConfig->getInfoByKey('oncall_key')['value'];
        $secret = $mConfig->getInfoByKey('oncall_secret')['value'];


        //Gọi api thực hiện cuộc gọi
        $response = $oClient->request('POST', env('DOMAIN_ONCALL') . '/oncall/extension/call', [
            'headers' => [
                'tenant' => session()->get('brand_code'),
                'key' => $key,
                'secret' => $secret
            ],
            'json' => [
                'src' => $extension,
                'to' => $phone,
            ]
        ]);

        return json_decode($response->getBody());
    }

    protected function _saveLeadLog($cur, $new, $id, $type, $keyData = '')
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
                            'key_table' => 'cpo_customer_lead',
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
                    'key_table' => 'cpo_customer_lead',
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
     * Upload file
     * @param $input
     * @return mixed|void
     */
    public function uploadFile($input)
    {
        try {
            if ($input['file'] != null) {
                $fileName = $this->uploadImageS3($input['file'], $input['link']);

                return [
                    'error' => 0,
                    'file' => $fileName['path'],
                    'type' => $fileName['type']
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => 'Tải file thất bại'
            ];
        }
    }

    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @return string
     */
    private function uploadImageS3($file, $link){
        $time = Carbon::now();
        $idTenant = session()->get('idTenant');

        $to = $idTenant . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $file->getClientOriginalExtension();

        Storage::disk('public')->put($to . $file_name, file_get_contents($file), 'public');
        $s3Disk = new S3UploadsRedirect();

        //Lấy real path trên s3
        return [
            'path' => $s3Disk->getRealPath($to. $file_name),
            'type' => $file->getClientOriginalExtension()
        ];
    }

    /**
     * Kiểm tra nhắc trước
     * @param $data
     * @return string|null
     */
    public function checkRemind($data)
    {
        $messageError = 'Thời gian trước nhắc nhở cho thời gian nhắc đã qua vui lòng chọn thời gian khác';
        if ($data['time_type_remind'] == 'm') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subMinutes($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        } else if ($data['time_type_remind'] == 'h') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subHours($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        } else if ($data['time_type_remind'] == 'd') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subDays($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        } else if ($data['time_type_remind'] == 'w') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subWeeks($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        }

        return null;
    }

    /**
     * Tìm kiếm công việc
     * @param $input
     * @return mixed|void
     */
    public function searchWorkLead($input)
    {
        try {

            $mMangeWork = new ManagerWorkTable();
            $listWork = $mMangeWork->getListWorkByCustomer($input);

            if ($input['type_search'] == 'history') {
                $view = view('customer-lead::append.append-list-history-work-child', [
                    'historyWork' => $listWork
                ])->render();
            } else {
                $view = view('customer-lead::append.append-list-work-child', [
                    'listWork' => $listWork
                ])->render();
            }

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách trạng thái theo công việc
     * @param $manage_work_id
     * @return mixed|void
     */
    public function getListStatusWork($manage_work_id = null)
    {
        $mManageStatus = new ManageStatusTable();
        $mManageStatusConfigMap = new ManageStatusConfigMapTable();
        $mManageWork = new ManagerWorkTable();

        $listStatus = $mManageStatus->getAll();

        if ($manage_work_id != null) {
            $detail = $mManageWork->getDetail($manage_work_id);
            $listStatusConfig = $mManageStatusConfigMap->getListStatusByConfig($detail['manage_status_id']);
            if (count($listStatusConfig) != 0) {
                $listStatusConfig = collect($listStatusConfig)->pluck('manage_status_id')->toArray();
                $listStatusConfig = array_merge($listStatusConfig, [$detail['manage_status_id']]);
                $listStatus = $mManageStatus->getAll($listStatusConfig);
            }
        }

        return $listStatus;
    }

    public function showPopupAddFile($params){
        return View::make('customer-lead::customer-lead.popup-upload-file', [
            'customerLeadId' => $params['customer_lead_id']
        ])->render();
    }

    public function addNote($data){
        $mCustomerLeadNote = new CustomerLeadNoteTable();
        $mCustomerLead = new CustomerLeadTable();
        $dataNote = [
            'content' => $data['content'],
            'customer_lead_id' => $data['customer_lead_id'],
            'created_by' => Auth::id(),
        ];

        $mCustomerLead->updateById($data['customer_lead_id'], ['note' => $data['content']]);

        return $mCustomerLeadNote->createdNote($dataNote);
    }

    public function addFile($data){
        $mCustomerLeadFile = new CustomerLeadFileTable();

        if($data['submit_type'] == 'add'){
            $dataFile = [
                'content' => $data['content'],
                'file_name' => $data['file_name'],
                'path' => $data['full_path'],
                'created_by' => Auth::id(),
                'customer_lead_id' => $data['customer_lead_id']
            ];

            return $mCustomerLeadFile->createFile($dataFile);
        }
        else{
            
            $dataUpdate = [
                'content' => $data['content'],
                'file_name' => $data['file_name'],
                'path' => $data['full_path'],
                'updated_by' => Auth::id(),
            ];
            
            return $mCustomerLeadFile->updateFile($data['customer_lead_file_id'], $dataUpdate);
        }
    }

    public function showEditFile($param){
        $customerLeadFile = CustomerLeadFileTable::where('customer_lead_file_id', $param['fileId'])->first();
        
        $html = View::make('customer-lead::customer-lead.popup-upload-file',[
            'customerLeadFile' => $customerLeadFile,
            'customerLeadId' => $param['customer_lead_id']
        ])->render();

        return $html;
    }

    public function addContact($data){
        $mCustomerLeadContact = new CustomerContactTable();
        $dataContact = [
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'staff_title_id' => $data['staff_title_id'],
            'customer_lead_code' => $data['customer_lead_code']
        ];

        return $mCustomerLeadContact->addContact($dataContact);
    }

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed|void
     */
    public function addComment($data)
    {
        try {
            $mCustomerLeadComment = new CustomerLeadCommentTable();
            $comment = [
                'message' => $data['description'],
                'customer_lead_id' => $data['customer_lead_id'],
                'customer_lead_parent_comment_id' => isset($data['customer_lead_comment_id']) ? $data['customer_lead_comment_id'] : null,
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            //Thêm bình luận ticket
            $idComment = $mCustomerLeadComment->createdComment($comment);

            $detailComment = $mCustomerLeadComment->getDetail($idComment);

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
            $view = $view = view('customer-lead::customer-lead.append.append-form-chat', ['customer_lead_comment_id' => $data['customer_lead_comment_id']])->render();

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
        $mManageComment = new CustomerLeadCommentTable();
        $listComment = $mManageComment->getListCommentCustomer($id);
        foreach ($listComment as $key => $item) {
            $listComment[$key]['child_comment'] = $mManageComment->getListCommentCustomer($id, $item['customer_lead_comment_id']);
        }
        return $listComment;
    }

    /**
     * Data view edit
     *
     * @param $input
     * @return array|mixed
     */
    public function dataDetail($customerLeadId)
    {
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mCustomerSource = new CustomerSourceTable();
        $mMapTag = new MapCustomerTagTable();
        $mJourney = new JourneyTable();
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();
        $mCustomerCare = new CustomerCareTable();
        $mProvince = new ProvinceTable();
        $mDistrict = new DistrictTable();
        $mCustomerDeal = new CustomerDealTable();
        $mMangeWork = new ManagerWorkTable();
        $mNote = new CustomerLeadNoteTable();
        $mManageTypeWork = new TypeWorkTable();
        $mManageStatus = new ManageStatusTable();
        $mStaff = new \Modules\ManagerWork\Models\StaffsTable();
      
        //Lấy thông tin lead
        $item = $this->customerLead->getInfo($customerLeadId);

        $mapTag = $mMapTag->getMapByCustomer($item["customer_lead_code"]);
     
        $arrPhone = $mCustomerPhone->getPhone($item["customer_lead_code"]);
        $arrEmail = $mCustomerEmail->getEmail($item["customer_lead_code"]);
        $arrFanpage = $mCustomerFanpage->getFanpage($item["customer_lead_code"]);
        $arrContact = $mCustomerContact->getContact($item["customer_lead_code"]);
      
        $optionTag = $mTag->getOption();
       
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $optionJourney = $mJourney->getOptionEdit($item["pipeline_code"], $item["journey_position"]);
        $optionSource = $mCustomerSource->getOption();
        //Option đầu mối doanh nghiệp
        $optionBusiness = $this->customerLead->getOptionBusiness();
        //Lấy option tỉnh thành
        $optionProvince = $mProvince->getOptionProvince();
        //Lấy option quận huyện
        $optionDistrict = $mDistrict->getOptionDistrict($item['province_id']);
        //Lất tất cả journey theo pipeline
        $listJourney = $mJourney->getJourneyByPipeline($item["pipeline_code"]);

        //Lấy lịch sử chăm sóc KH
        //        $getCare = collect($mCustomerCare->getCustomerCare($item['customer_lead_code'])->toArray());
        //        $dataCare = $getCare->groupBy('created_group');
        $filterCare['customer_lead_code'] = $item['customer_lead_code'];
        $dataCare = $mCustomerCare->getListCustomerCare($filterCare);

        $dataDeal = $mCustomerDeal->getListDealLeadDetail($filterCare);
        $arrMapTag = [];

        if (count($mapTag) > 0) {
            foreach ($mapTag as $v) {
                $arrMapTag[] = $v["tag_id"];
            }
        }

        //Lấy cấu hình thông tin kèm theo của KH
        $mCustomerDefine = new CustomerLeadCustomDefineTable();
        $customDefine = $mCustomerDefine->getDefine();


        $data = [
            'customer_id' => $customerLeadId,
            'manage_work_customer_type' => 'lead',
            'type_search' => 'support'
        ];

        $listWork = $mMangeWork->getListWorkByCustomer($data);

        $data1 = [
            'customer_id' => $customerLeadId,
            'manage_work_customer_type' => 'lead',
            'type_search' => 'history'
        ];

        //Note
        $listNotes = $mNote->getListNoteCustomer($customerLeadId);

        //Files
        $mCustomerLeadFile = new CustomerLeadFileTable();
        $listFiles = $mCustomerLeadFile->getListFileCustomerLead($customerLeadId);

        $historyWork = $mMangeWork->getListWorkByCustomer($data1);

        $listTypeWork = $mManageTypeWork->getListTypeWork(1);

        $listStatusWork = $mManageStatus->getAll();

        $liststaff = $mStaff->getAll();

        $mStaffTitle = new StaffTitleTable();
        $listStaffTitle = $mStaffTitle->getData();
        

        $dataReturn =  [
            "optionTag" => $optionTag,
            "optionPipeline" => $optionPipeline,
            "optionJourney" => $optionJourney,
            "optionSource" => $optionSource,
            "arrMapTag" => $arrMapTag,
            "item" => $item,
            'arrPhone' => $arrPhone,
            'arrEmail' => $arrEmail,
            'arrFanpage' => $arrFanpage,
            'arrContact' => $arrContact,
            'listStaffTitle' => $listStaffTitle,
            "optionBusiness" => $optionBusiness,
            "listJourney" => $listJourney,
            "dataCare" => $dataCare,
            "dataDeal" => $dataDeal,
            "optionProvince" => $optionProvince,
            "optionDistrict" => $optionDistrict,
            "customDefine" => $customDefine,
            'listWork' => $listWork,
            'listNotes' => $listNotes,
            'listFiles' => $listFiles,
            'historyWork' => $historyWork,
            'listTypeWork' => $listTypeWork,
            'listStatusWork' => $listStatusWork,
            'liststaff' => $liststaff
        ];
        return $dataReturn;
    }

    /**
     * Data view edit
     *
     * @param $input
     * @return array|mixed
     */
    public function dataEdit($customerLeadId)
    {
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mCustomerSource = new CustomerSourceTable();
        $mMapTag = new MapCustomerTagTable();
        $mJourney = new JourneyTable();
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();
        $mCustomerCare = new CustomerCareTable();
        $mProvince = new ProvinceTable();
        $mDistrict = new DistrictTable();
        $mCustomerDeal = new CustomerDealTable();
        $mMangeWork = new ManagerWorkTable();
        $mNote = new CustomerLeadNoteTable();
        $mManageTypeWork = new TypeWorkTable();
        $mManageStatus = new ManageStatusTable();
        $mStaff = new \Modules\ManagerWork\Models\StaffsTable();
      
        //Lấy thông tin lead
        $item = $this->customerLead->getInfo($customerLeadId);

        $mapTag = $mMapTag->getMapByCustomer($item["customer_lead_code"]);
     
        $arrPhone = $mCustomerPhone->getPhone($item["customer_lead_code"]);
        $arrEmail = $mCustomerEmail->getEmail($item["customer_lead_code"]);
        $arrFanpage = $mCustomerFanpage->getFanpage($item["customer_lead_code"]);
        $arrContact = $mCustomerContact->getContact($item["customer_lead_code"]);
      
        $optionTag = $mTag->getOption();
       
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $optionJourney = $mJourney->getOptionEdit($item["pipeline_code"], $item["journey_position"]);
        $optionSource = $mCustomerSource->getOption();
        //Option đầu mối doanh nghiệp
        $optionBusiness = $this->customerLead->getOptionBusiness();
        //Lấy option tỉnh thành
        $optionProvince = $mProvince->getOptionProvince();
        //Lấy option quận huyện
        $optionDistrict = $mDistrict->getOptionDistrict($item['province_id']);
        //Lất tất cả journey theo pipeline
        $listJourney = $mJourney->getJourneyByPipeline($item["pipeline_code"]);

        //Lấy lịch sử chăm sóc KH
        //        $getCare = collect($mCustomerCare->getCustomerCare($item['customer_lead_code'])->toArray());
        //        $dataCare = $getCare->groupBy('created_group');
        $filterCare['customer_lead_code'] = $item['customer_lead_code'];
        $dataCare = $mCustomerCare->getListCustomerCare($filterCare);

        $dataDeal = $mCustomerDeal->getListDealLeadDetail($filterCare);
        $arrMapTag = [];

        if (count($mapTag) > 0) {
            foreach ($mapTag as $v) {
                $arrMapTag[] = $v["tag_id"];
            }
        }

        //Lấy cấu hình thông tin kèm theo của KH
        $mCustomerDefine = new CustomerLeadCustomDefineTable();
        $customDefine = $mCustomerDefine->getDefine();


        $data = [
            'customer_id' => $customerLeadId,
            'manage_work_customer_type' => 'lead',
            'type_search' => 'support'
        ];

        $listWork = $mMangeWork->getListWorkByCustomer($data);

        $data1 = [
            'customer_id' => $customerLeadId,
            'manage_work_customer_type' => 'lead',
            'type_search' => 'history'
        ];

        //Note
        $listNotes = $mNote->getListNoteCustomer($customerLeadId);

        //Files
        $mCustomerLeadFile = new CustomerLeadFileTable();
        $listFiles = $mCustomerLeadFile->getListFileCustomerLead($customerLeadId);

        $historyWork = $mMangeWork->getListWorkByCustomer($data1);

        $listTypeWork = $mManageTypeWork->getListTypeWork(1);

        $listStatusWork = $mManageStatus->getAll();

        $liststaff = $mStaff->getAll();

        $mStaffTitle = new StaffTitleTable();
        $listStaffTitle = $mStaffTitle->getData();

        $mBranch = new BranchTable();
        $listBranch = $mBranch->getLists();

        $mBussiness = new BussinessTable();
        $listBussiness = $mBussiness->getData();
        $dataReturn = [
            "optionTag" => $optionTag,
            "optionPipeline" => $optionPipeline,
            "optionJourney" => $optionJourney,
            "optionSource" => $optionSource,
            "arrMapTag" => $arrMapTag,
            "item" => $item,
            'arrPhone' => $arrPhone,
            'arrEmail' => $arrEmail,
            'arrFanpage' => $arrFanpage,
            'arrContact' => $arrContact,
            'listStaffTitle' => $listStaffTitle,
            'listBranch' => $listBranch,
            'listBussiness' => $listBussiness,
            'load' => false,
            "optionBusiness" => $optionBusiness,
            "optionProvince" => $optionProvince,
            "optionDistrict" => $optionDistrict,
            "customDefine" => $customDefine,
            'listWork' => $listWork,
            'listNotes' => $listNotes,
            'listFiles' => $listFiles,
            'historyWork' => $historyWork,
            'listTypeWork' => $listTypeWork,
            'listStatusWork' => $listStatusWork,
            'liststaff' => $liststaff,

        ];
        return $dataReturn;
    }

    /**
     * Data view thêm KH tiềm năng
     *
     * @param $input
     * @return array|mixed
     */
    public function dataCreate()
    {
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mCustomerSource = new CustomerSourceTable();
        $mStaff = new StaffsTable();
        $mProvince = new ProvinceTable();

        $optionTag = $mTag->getOption();
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $optionSource = $mCustomerSource->getOption();
        //Option đầu mối doanh nghiệp
        $optionBusiness = $this->customerLead->getOptionBusiness();
        //Option nhân viên (người được phân bổ)
        $optionStaff = $mStaff->getStaffOption();
        //Lấy option tỉnh/ thành
        $optionProvince = $mProvince->getOptionProvince();
        //Lấy cấu hình thông tin kèm theo của KH
        $mCustomerDefine = new CustomerLeadCustomDefineTable();
        $customDefine = $mCustomerDefine->getDefine();

        $mBranch = new BranchTable();
        $listBranch = $mBranch->getLists();

        $mBussiness = new BussinessTable();
        $listBussiness = $mBussiness->getData();

        $mStaffTitle = new StaffTitleTable();
        $listStaffTitle = $mStaffTitle->getData();

        return [
            "optionTag" => $optionTag,
            "optionPipeline" => $optionPipeline,
            "optionSource" => $optionSource,
            'load' => false,
            "optionBusiness" => $optionBusiness,
            "optionStaff" => $optionStaff,
            "optionProvince" => $optionProvince,
            'customDefine' => $customDefine,
            'listBranch' => $listBranch,
            "listBussiness" => $listBussiness,
            "listStaffTitle" => $listStaffTitle
        ];
        
    }
}
