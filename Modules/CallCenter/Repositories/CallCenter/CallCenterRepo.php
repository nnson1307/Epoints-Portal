<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\CallCenter\Repositories\CallCenter;



use Carbon\Carbon;
use DateTime;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CallCenter\Models\CustomerLeadTable;
use Modules\CallCenter\Models\CustomerTable;
use Modules\CallCenter\Models\ProvinceTable;
use Modules\CallCenter\Models\PipelineTable;
use Modules\CallCenter\Models\StaffsTable;
use Modules\CallCenter\Models\JourneyTable;
use Modules\CallCenter\Models\CustomerSourceTable;
use Modules\CallCenter\Models\CustomerRequestTable;
use Modules\CallCenter\Models\CustomerDealTable;
use Modules\CallCenter\Models\ContractsTable;
use Modules\CallCenter\Models\CustomerRequestAttributeTable;

class CallCenterRepo implements CallCenterRepoInterface
{
    protected $mCutomerLead;
    protected $mCustomer;
    protected $mCustomerRequest;
    public function __construct(CustomerLeadTable $mCutomerLead, CustomerTable $mCustomer, CustomerRequestTable $mCustomerRequest) {
        $this->mCutomerLead = $mCutomerLead;
        $this->mCustomer = $mCustomer;
        $this->mCustomerRequest = $mCustomerRequest;
    }

    public function searchCustomer($keyWord){
        $data = $this->mCutomerLead->search($keyWord)->toArray();
        $dataCustomer = $this->mCustomer->getCustomer($keyWord)->toArray();
       
        return [
            'LIST' => array_merge($data,$dataCustomer),
            'optionConfigShow' => $this->mCustomerRequest->getConfigShowInfo(),
        ];
    }

     /**
     * Lấy danh sách yêu cầu khách hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getListCustomerRequest(array $filters = []){
        return [
            'LIST' => $this->mCustomerRequest->getList($filters),
            'optionConfigShow' => $this->mCustomerRequest->getConfigShowInfo(),
        ];
    }

    /*
    *Lấy thông tin tiếp nhận
    */
    public function getInfoCustomerRequest($id){
        return $this->mCustomerRequest->getInfo($id);
    }

    /**
     * Thêm KH tiềm năng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function createCustomerRequestNotInfo($input)
    {
        $mJourney = new JourneyTable();
        $mPipeline = new PipelineTable();

        DB::beginTransaction();
        try {
            //Kiểm tra phone
            if($input["call_center_phone"] != null && $input["call_center_phone"] != ""){
                $customerLeadInfo = $this->mCutomerLead->checkPhoneIsExist($input["call_center_phone"]);
                if($customerLeadInfo != null){
                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại đã tồn tại'),
                    ]);
                }
               
            }
           
            // Nhân viên được phân bổ
            $saleId = null;
            if (isset($input['call_center_staff'])) {
                $saleId = $input['call_center_staff'];
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
                "full_name" => $input["call_center_full_name"],
                "phone" => $input["call_center_phone"],
                "gender" => $input["call_center_gender"],
                "address" => $input["call_center_address"],
                "pipeline_code" => $input["call_center_pipeline"],
                "journey_code" => $input["call_center_journey"],
                "customer_type" => $input["call_center_customer_type"],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id(),
                "customer_source" => $input['call_center_customer_source'],
                "assign_by" => Auth()->id(),
                "sale_id" => $saleId,
                "date_revoke" => $timeNow->addDay($timeRevokeLead),
                "province_id" => $input['call_center_province'],
                "district_id" => $input['call_center_district'],
                "ward_id" => $input['call_center_ward'],
                'allocation_date' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            //Define sẵn 10 trường thông tin kèm theo
            for ($i = 1; $i <= 10; $i++) {
                $custom = "custom_$i";
                $data["custom_$i"] = isset($input[$custom]) ? $input[$custom] : null;
            }

            //Insert customer lead
            $customerLeadId = $this->mCutomerLead->add($data);
            //Update customer_lead_code
            $leadCode = "LEAD_" . date("dmY") . sprintf("%02d", $customerLeadId);
            $this->mCutomerLead->edit([
                "customer_lead_code" => $leadCode
            ], $customerLeadId);

            $custom_column_value_1 = $input["custom_column_value_1"] ? $input["custom_column_value_1"] : null;
            $custom_column_value_2 = $input["custom_column_value_2"] ? $input["custom_column_value_2"] : null;
            $custom_column_value_3 = $input["custom_column_value_3"] ? $input["custom_column_value_3"] : null;
            $custom_column_value_4 = $input["custom_column_value_4"] ? $input["custom_column_value_4"] : null;
            $custom_column_value_5 = $input["custom_column_value_5"] ? $input["custom_column_value_5"] : null;
            $custom_column_value_6 = $input["custom_column_value_6"] ? $input["custom_column_value_6"] : null;
            $custom_column_value_7 = $input["custom_column_value_7"] ? $input["custom_column_value_7"] : null;
            $custom_column_value_8 = $input["custom_column_value_8"] ? $input["custom_column_value_8"] : null;
            $custom_column_value_9 = $input["custom_column_value_9"] ? $input["custom_column_value_9"] : null;
            $custom_column_value_10 = $input["custom_column_value_10"] ? $input["custom_column_value_10"] : null;

            // if(isset($input["object_data_type_1"]) && $input["object_data_type_1"] == 'int'){
            //     $custom_column_value_1 = str_replace(',','', $custom_column_value_1);
            // }
            // if(isset($input["object_data_type_2"]) && $input["object_data_type_2"] == 'int'){
            //     $custom_column_value_2 = str_replace(',','', $custom_column_value_2);
            // }
            // if(isset($input["object_data_type_3"]) && $input["object_data_type_3"] == 'int'){
            //     $custom_column_value_3 = str_replace(',','', $custom_column_value_3);
            // }
            // if(isset($input["object_data_type_4"]) && $input["object_data_type_4"] == 'int'){
            //     $custom_column_value_4 = str_replace(',','', $custom_column_value_4);
            // }
            // if(isset($input["object_data_type_5"]) && $input["object_data_type_5"] == 'int'){
            //     $custom_column_value_5 = str_replace(',','', $custom_column_value_5);
            // }
            // if(isset($input["object_data_type_6"]) && $input["object_data_type_6"] == 'int'){
            //     $custom_column_value_6 = str_replace(',','', $custom_column_value_6);
            // }
            // if(isset($input["object_data_type_7"]) && $input["object_data_type_7"] == 'int'){
            //     $custom_column_value_7 = str_replace(',','', $custom_column_value_7);
            // }
            // if(isset($input["object_data_type_8"]) && $input["object_data_type_8"] == 'int'){
            //     $custom_column_value_8 = str_replace(',','', $custom_column_value_8);
            // }
            // if(isset($input["object_data_type_9"]) && $input["object_data_type_9"] == 'int'){
            //     $custom_column_value_9 = str_replace(',','', $custom_column_value_9);
            // }
            // if(isset($input["object_data_type_10"]) && $input["object_data_type_10"] == 'int'){
            //     $custom_column_value_10 = str_replace(',','', $custom_column_value_10);
            // }
            $dataRequest = [
                'object_id' => $customerLeadId,
                'object_type' => 'customer_lead',
                'customer_request_type' => $input["call_center_customer_request_type"],
                'customer_request_note' => $input["call_center_note"],
                'customer_request_phone' => $input['call_center_phone'],
                'customer_request_name' => $input['call_center_full_name'],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                "custom_column_name_1" => $input["custom_column_name_1"] ? $input["custom_column_name_1"] : null,
                "custom_column_value_1" => $custom_column_value_1,
                "custom_column_name_2" => $input["custom_column_name_2"] ? $input["custom_column_name_2"] : null,
                "custom_column_value_2" => $custom_column_value_2,
                "custom_column_name_3" => $input["custom_column_name_3"] ? $input["custom_column_name_3"] : null,
                "custom_column_value_3" => $custom_column_value_3,
                "custom_column_name_4" => $input["custom_column_name_4"] ? $input["custom_column_name_4"] : null,
                "custom_column_value_4" => $custom_column_value_4,
                "custom_column_name_5" => $input["custom_column_name_5"] ? $input["custom_column_name_5"] : null,
                "custom_column_value_5" => $custom_column_value_5,
                "custom_column_name_6" => $input["custom_column_name_6"] ? $input["custom_column_name_6"] : null,
                "custom_column_value_6" => $custom_column_value_6,
                "custom_column_name_7" => $input["custom_column_name_7"] ? $input["custom_column_name_7"] : null,
                "custom_column_value_7" => $custom_column_value_7,
                "custom_column_name_8" => $input["custom_column_name_8"] ? $input["custom_column_name_8"] : null,
                "custom_column_value_8" => $custom_column_value_8,
                "custom_column_name_9" => $input["custom_column_name_9"] ? $input["custom_column_name_9"] : null,
                "custom_column_value_9" => $custom_column_value_9,
                "custom_column_name_10" => $input["custom_column_name_10"] ? $input["custom_column_name_10"] : null,
                "custom_column_value_10" => $custom_column_value_10,
            ];
            $customerRequestId = $this->mCustomerRequest->add($dataRequest);
            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công"),
                "object_id" => $customerLeadId,
                "id" => $customerRequestId,
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
     * Thêm KH tiềm năng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function createCustomerRequest($input)
    {
        DB::beginTransaction();
        try {
            $custom_column_value_1 = $input["custom_column_value_1"] ? $input["custom_column_value_1"] : null;
            $custom_column_value_2 = $input["custom_column_value_2"] ? $input["custom_column_value_2"] : null;
            $custom_column_value_3 = $input["custom_column_value_3"] ? $input["custom_column_value_3"] : null;
            $custom_column_value_4 = $input["custom_column_value_4"] ? $input["custom_column_value_4"] : null;
            $custom_column_value_5 = $input["custom_column_value_5"] ? $input["custom_column_value_5"] : null;
            $custom_column_value_6 = $input["custom_column_value_6"] ? $input["custom_column_value_6"] : null;
            $custom_column_value_7 = $input["custom_column_value_7"] ? $input["custom_column_value_7"] : null;
            $custom_column_value_8 = $input["custom_column_value_8"] ? $input["custom_column_value_8"] : null;
            $custom_column_value_9 = $input["custom_column_value_9"] ? $input["custom_column_value_9"] : null;
            $custom_column_value_10 = $input["custom_column_value_10"] ? $input["custom_column_value_10"] : null;

            // if(isset($input["object_data_type_1"]) && $input["object_data_type_1"] == 'int'){
            //     $custom_column_value_1 = str_replace(',','', $custom_column_value_1);
            // }
            // if(isset($input["object_data_type_2"]) && $input["object_data_type_2"] == 'int'){
            //     $custom_column_value_2 = str_replace(',','', $custom_column_value_2);
            // }
            // if(isset($input["object_data_type_3"]) && $input["object_data_type_3"] == 'int'){
            //     $custom_column_value_3 = str_replace(',','', $custom_column_value_3);
            // }
            // if(isset($input["object_data_type_4"]) && $input["object_data_type_4"] == 'int'){
            //     $custom_column_value_4 = str_replace(',','', $custom_column_value_4);
            // }
            // if(isset($input["object_data_type_5"]) && $input["object_data_type_5"] == 'int'){
            //     $custom_column_value_5 = str_replace(',','', $custom_column_value_5);
            // }
            // if(isset($input["object_data_type_6"]) && $input["object_data_type_6"] == 'int'){
            //     $custom_column_value_6 = str_replace(',','', $custom_column_value_6);
            // }
            // if(isset($input["object_data_type_7"]) && $input["object_data_type_7"] == 'int'){
            //     $custom_column_value_7 = str_replace(',','', $custom_column_value_7);
            // }
            // if(isset($input["object_data_type_8"]) && $input["object_data_type_8"] == 'int'){
            //     $custom_column_value_8 = str_replace(',','', $custom_column_value_8);
            // }
            // if(isset($input["object_data_type_9"]) && $input["object_data_type_9"] == 'int'){
            //     $custom_column_value_9 = str_replace(',','', $custom_column_value_9);
            // }
            // if(isset($input["object_data_type_10"]) && $input["object_data_type_10"] == 'int'){
            //     $custom_column_value_10 = str_replace(',','', $custom_column_value_10);
            // }
            $dataRequest = [
                'object_id' => $input["object_id"],
                'object_type' => $input["object_type"],
                'customer_request_phone' => $input['call_center_phone'],
                'customer_request_name' => $input['call_center_full_name'],
                'customer_request_type' => $input["call_center_customer_request_type"] ? $input["call_center_customer_request_type"] : 'other',
                'customer_request_note' => $input["call_center_note"],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                "custom_column_name_1" => $input["custom_column_name_1"] ? $input["custom_column_name_1"] : null,
                "custom_column_value_1" => $custom_column_value_1,
                "custom_column_name_2" => $input["custom_column_name_2"] ? $input["custom_column_name_2"] : null,
                "custom_column_value_2" => $custom_column_value_2,
                "custom_column_name_3" => $input["custom_column_name_3"] ? $input["custom_column_name_3"] : null,
                "custom_column_value_3" => $custom_column_value_3,
                "custom_column_name_4" => $input["custom_column_name_4"] ? $input["custom_column_name_4"] : null,
                "custom_column_value_4" => $custom_column_value_4,
                "custom_column_name_5" => $input["custom_column_name_5"] ? $input["custom_column_name_5"] : null,
                "custom_column_value_5" => $custom_column_value_5,
                "custom_column_name_6" => $input["custom_column_name_6"] ? $input["custom_column_name_6"] : null,
                "custom_column_value_6" => $custom_column_value_6,
                "custom_column_name_7" => $input["custom_column_name_7"] ? $input["custom_column_name_7"] : null,
                "custom_column_value_7" => $custom_column_value_7,
                "custom_column_name_8" => $input["custom_column_name_8"] ? $input["custom_column_name_8"] : null,
                "custom_column_value_8" => $custom_column_value_8,
                "custom_column_name_9" => $input["custom_column_name_9"] ? $input["custom_column_name_9"] : null,
                "custom_column_value_9" => $custom_column_value_9,
                "custom_column_name_10" => $input["custom_column_name_10"] ? $input["custom_column_name_10"] : null,
                "custom_column_value_10" => $custom_column_value_10,
            ];
            $customerRequestId = $this->mCustomerRequest->add($dataRequest);
            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công"),
                "id" => $customerRequestId,
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

    public function getOptionProvince()
    {
        $mProvince = new ProvinceTable();
        $listData=array();
        foreach ($mProvince->getOptionProvince() as $value){
            $listData[$value['provinceid']]=$value['name'];
        }
        return $listData;
    }

    public function getOptionPipeline()
    {
        $mPipeline = new PipelineTable();
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        return $optionPipeline;
    }

    public function getOptionStaff()
    {
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getStaffOption();
        return $optionStaff;
    }

    public function loadOptionJourney($pipelineCode){
        $mJourney = new JourneyTable();
        $optionJourney = $mJourney->getJourneyByPipeline($pipelineCode);
        return [
            'optionJourney' => $optionJourney,
        ];
    }

    public function loadCustomerSource(){
        $mCustomerSource = new CustomerSourceTable();
        $optionSource = $mCustomerSource->getOption();
        return $optionSource;
    }

    public function getInfoCustomerLead($id){
        return $this->mCutomerLead->getInfo($id);
    }

    public function getInfoCustomer($id){
        return $this->mCustomer->getInfoById($id);
    }

      /**
     * Lấy danh deal
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getListDealLeadDetail(array $filters = []){
        $mCustomerDeal = new CustomerDealTable();
        return $mCustomerDeal->getListDealLeadDetail($filters);
    }

    /**
     * Lấy danh sách hợp đồng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getListContract($customerId){
        $mContracts = new ContractsTable();
        return $mContracts->getListContractByCustomer($customerId);
    }

    /**
     * Lấy tổng tiêp nhận theo tháng
     */
    public function getTotalByMonth($month, $years){
        $date =  Carbon::parse($years . '-' . $month . '-' . '01');
        $day_start = $date->format('Y-m-01');
        $day_end = $date->format('Y-m-t');
        $data = $this->mCustomerRequest->getTotalByMonth($day_start, $day_end);
        $collectionData = collect($data->toArray());
        $arrData = [];
        $arrCate = [];
        
        for ($i=1; $i <= $date->format('t'); $i++) { 
            $obj = $collectionData->where('days', $i < 10 ? '0'.$i : $i)->first();
            $arrData[] = [
                $obj != null ? $obj['Total'] : 0
            ];
            $arrCate[] = [
                $i < 10 ? '0'.$i.'/'.Carbon::now()->format('m') : $i.'/'.Carbon::now()->format('m')
            ];
        }
        return [
            "data" => $arrData,
            "categories" => $arrCate
        ];
    }

    /**
     * Lấy tổng tiêp nhận nhân viên theo tháng
     */
    public function getTotalStaffByMonth($month, $years){
        $date =  Carbon::parse($years . '-' . $month . '-' . '01');
        $day_start = $date->format('Y-m-01');
        $day_end = $date->format('Y-m-t');
        $data = $this->mCustomerRequest->getTotalStaffByMonth($day_start, $day_end);
     
        $arrData = [];
        foreach ($data as $object) {
            $arrData[]=[$object['sale_name'], $object['Total']];
        }
        return [
            "data" => $arrData
        ];
    }
}