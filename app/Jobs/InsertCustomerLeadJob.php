<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Models\StaffsTable;
use Illuminate\Queue\SerializesModels;
use Modules\Admin\Models\CustomerTable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\ProductTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerEmailTable;
use Modules\CustomerLead\Models\CustomerPhoneTable;
use Modules\CustomerLead\Models\CpoCustomerLogTable;
use Modules\CustomerLead\Models\MapCustomerTagTable;
use Modules\CustomerLead\Models\CustomerContactTable;
use Modules\CustomerLead\Models\CustomerFanpageTable;
use Modules\CustomerLead\Models\ConfigSourceLeadTable;
use Modules\CustomerLead\Models\CustomerDealDetailTable;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepo;

class InsertCustomerLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    protected $idGoogleSheet;
    public $tries = 5;
    public function __construct($data, $idGoogleSheet)
    {
        $this->data = $data;
        $this->idGoogleSheet = $idGoogleSheet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // leads //
            $data = $this->data;
            // idGoogleSheet //
            $idGoogleSheet = $this->idGoogleSheet;
            // băm nhỏ dữ liệu đổ insert //
            if (count($data) > 0) {
                $data = collect($data)->chunk(50);
                foreach ($data as $leads) {
                    foreach ($leads as $lead) {
                        $this->storeCustomerLead($lead, $idGoogleSheet);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }

    /**
     * Thêm KH tiềm năng
     *
     * @param $input
     * @param $idGoogleSheet
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function storeCustomerLead($input, $idGoogleSheet)
    {
        $mMapCustomerTag = new MapCustomerTagTable();
        $mJourney = new JourneyTable();
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();
        $mPipeline = new PipelineTable();
        $mCustomerLead = new CustomerLeadTable();
        $mCustomer = new CustomerTable();
        $mConfigSourceLead = new ConfigSourceLeadTable();
        // kiểm tra phone có trùng customer lead //
        $checkPhoneUnique = $mCustomerLead->checkPhoneUnique($input["phone"]);
        // kiểm tra phone có trùng table customers
        $checkPhoneCustomerUnique = $mCustomer->getCustomerByPhone($input["phone"]);
        // item config googleSheet //
        $itemConfig = $mConfigSourceLead->getItemByIdGoogleSheet($idGoogleSheet);
        // trạng thái tự động phân bổ //
        $statusConfig = $itemConfig->is_rotational_allocation;
        $startDate = $input['date_data'] ? Carbon::parse($input['date_data'])->addDays(1)->format('Y-m-d') : "";
        if ($statusConfig > 0) {
            if (!empty($checkPhoneCustomerUnique) || $checkPhoneUnique) {
                return true;
            } else {
                try {
                    DB::beginTransaction();
                    $arrInsertPhone = [];
                    $arrInsertEmail = [];
                    $arrInsertFanpage = [];
                    $arrInsertContact = [];
                    //Kiểm tra phone + phone attack có trùng nhau ko
                    $arrPhone = [$input["phone"]];
                    if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
                        foreach ($input['arrPhoneAttack'] as $v) {
                            $arrPhone[] = $v['phone'];
                        }
                    }
                    //Check unique phone
                    if ($this->array_has_dupes($arrPhone) == true) {
                        return true;
                    }
                    //Kiểm tra email + email attack có trùng nhau ko
                    $email = $input["email"] ?? "";
                    $arrEmail = [$email];
                    if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
                        foreach ($input['arrEmailAttack'] as $v) {
                            $arrEmail[] = $v['email'];
                        }
                        //Check unique email
                        if ($this->array_has_dupes($arrEmail) == true) {
                            return true;
                        }
                    }
                    //Kiểm tra fanpage + fanpage attack có trùng nhau ko
                    $fanpage = $input["fanpage"] ?? "";
                    $arrFanpage = [$fanpage];
                    if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
                        foreach ($input['arrFanpageAttack'] as $v) {
                            $arrEmail[] = $v['fanpage'];
                        }
                        //Check unique email
                        if ($this->array_has_dupes($arrFanpage) == true) {
                            return true;
                        }
                    }
                    $getPipeLine = $mPipeline->getDetailPipelineByCategoryCode('CUSTOMER');

                    // Thời gian giữ tối đa
                    $pipelineInfo = $mPipeline->getDetailByCode($input['pipeline_code']);
                    $timeRevokeLead = 0;
                    $timeNow = Carbon::now();
                    if ($pipelineInfo != null && $pipelineInfo['time_revoke_lead']) {
                        $timeRevokeLead = $pipelineInfo['time_revoke_lead'];
                    }
                    $IdCustomerSource = "";
                    $inputCustomerSourceCode = $input['customer_source'];
                    $mcustomerSource = new CustomerSourceTable();
                    $customerSourceCode = $mcustomerSource->getSourceByCode($inputCustomerSourceCode);
                    if ($customerSourceCode) {
                        $IdCustomerSource = $customerSourceCode->customer_source_id;
                    }
                    $data = [
                        "full_name" => $input["full_name"],
                        "note" => isset($input["note"]) ? $input["note"] : "",
                        "email" => $input["email"] ?? "",
                        "phone" => $input["phone"] ?? "",
                        "gender" => $input["gender"] ?? "",
                        "address" => $input["address"] ?? "",
                        "pipeline_code" => $input["pipeline_code"] ?? "",
                        "journey_code" => $input["journey_code"] ?? "",
                        "customer_type" => $input["customer_type"] ?? "",
                        "hotline" => isset($input["hotline"]) ? $input["hotline"] : null,
                        "fanpage" => $input["fanpage"] ?? "",
                        "zalo" => $input["zalo"] ?? "",
                        "tax_code" => isset($input["tax_code"]) ? $input["tax_code"] : null,
                        "representative" => isset($input["representative"]) ? $input["representative"] : null,
                        "created_by" => $input['user_created'] ?? "",
                        "updated_by" => $input['user_created'] ?? "",
                        "customer_source" => $IdCustomerSource,
                        "business_clue" => $input['business_clue'] ?? "",
                        "assign_by" => $input['user_created'] ?? "",
                        "sale_id" => $getPipeLine->owner_id,
                        "created_at" => $startDate,
                        "date_revoke" => $timeNow->addDay($timeRevokeLead),
                        "province_id" => $input['province_id'] ?? "",
                        "district_id" => $input['district_id'] ?? "",
                        "number_row" => $input['number_row'] ?? "",
                        "id_google_sheet" => $input['id_google_sheet'] ?? "",
                        "allocation_date" => $startDate
                    ];
                    // data insert log //
                    $dataInsertLog = [
                        "pipeline_code" => $input["pipeline_code"] ?? "",
                        "journey_code" => $input["journey_code"] ?? ""
                    ];
                    if (isset($input['avatar']) &&  $input["avatar"] != null) {
                        $data["avatar"] = $input["avatar"];
                    }

                    if (isset($input['avatar']) && $input['customer_type'] == "business") {
                        $data['business_clue'] = null;
                    }

                    //Define sẵn 10 trường thông tin kèm theo
                    for ($i = 1; $i <= 10; $i++) {
                        $custom = "custom_$i";
                        $data["custom_$i"] = isset($input[$custom]) ? $input[$custom] : null;
                    }

                    //Insert customer lead
                    $customerLeadId = $mCustomerLead->add($data);
                    //Update customer_lead_code
                    $leadCode = "LEAD_" . date("dmY") . sprintf("%02d", $customerLeadId);
                    $mCustomerLead->edit([
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
                                'address' => $v['address'],
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                            ];
                        }
                    }
                    //Insert customer contact
                    $mCustomerContact->insert($arrInsertContact);
                    // Insert log //
                    $this->insertLogLead($dataInsertLog, $customerLeadId);
                    DB::commit();
                } catch (\Exception $e) {
                    Log::info('error :' . $e->getMessage());
                }
            };
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
     * Kiểm tra hành trình có tạo deal không
     *
     * @param $journeyCode
     * @param $leadId
     * @return int
     */
    public function checkJourneyHaveDeal($journeyCode, $leadId)
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
     * data['pipeline_code']
     * data['journey_code']
     * @param $data
     * @param $customerLeadId
     */
    public function insertLogLead($data, $customerLeadId)
    {
        $mCpoCustomerLog = app()->get(CpoCustomerLogTable::class);
        $mJourney = app()->get(JourneyTable::class);
        $dataCustomerLog = [];
        $getListJourney = $mJourney->getJourneyByPipeline($data["pipeline_code"]);

        foreach ($getListJourney as $key => $item) {

            $dataCustomerLog[] = [
                'object_type' => 'customer_lead',
                'object_id' => $customerLeadId,
                'type' => 'lead',
                'key_table' => 'cpo_customer_lead',
                'value_old' => $key == 0 ? '' : $getListJourney[$key - 1]['journey_code'],
                'value_new' => $item['journey_code'],
                'title' => __('Tạo khách hàng tiềm năng'),
                'day' => (int)Carbon::now()->format('d'),
                'month' => (int)Carbon::now()->format('m'),
                'year' => (int)Carbon::now()->format('Y'),
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_deal_created' => $this->checkJourneyHaveDeal($item['journey_code'], $customerLeadId)
            ];

            if ($item['journey_code'] == $data['journey_code']) {
                break 1;
            }
        }

        if (count($dataCustomerLog) != 0) {
            $mCpoCustomerLog->insertArrData($dataCustomerLog);
        }
    }
}
