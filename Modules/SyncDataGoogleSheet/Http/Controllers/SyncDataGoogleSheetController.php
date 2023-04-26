<?php

namespace Modules\SyncDataGoogleSheet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Jobs\InsertCustomerLeadJob;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Models\StaffsTable;
use App\Jobs\SyncDataGoogleSheetLeadJob;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\ConfigSourceLeadTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\SyncDataGoogleSheet\Models\RowLastGoogleSheetTable;
use Modules\SyncDataGoogleSheet\Http\Requests\DataGoogleSheetRequest;
use Modules\SyncDataGoogleSheet\Repositories\SyncDataGoogleSheetRepoInterface;

class SyncDataGoogleSheetController extends Controller
{

    protected $rowLastGoogleSheet;
    protected $syncDataGoogleSheet;
    protected $configSource;
    protected $customerResouce;
    protected $pipeline;

    public function __construct(
        RowLastGoogleSheetTable $rowLastGoogleSheet,
        SyncDataGoogleSheetRepoInterface $syncDataGoogleSheet,
        ConfigSourceLeadTable $configSource,
        CustomerSourceTable $customerResouce,
        PipelineTable $pipeline
    ) {
        $this->rowLastGoogleSheet = $rowLastGoogleSheet;
        $this->syncDataGoogleSheet = $syncDataGoogleSheet;
        $this->configSource = $configSource;
        $this->customerResouce = $customerResouce;
        $this->pipeline = $pipeline;
    }
    /**
     * Lấy số hàng insert cuối cùng lần trước googleSheet
     * @param Request $request
     * @return int
     */
    public function getLastRowInsert($id_google_sheet)
    {
        try {
            // id google sheet //
            $idGoogleSheet = $id_google_sheet ?? "";
            // số hàng mặc định khi chưa insert //
            $rowDefault = $this->rowLastGoogleSheet->getRowDefault();
            // lấy số hàng cuối cùng insert lần trước //
            $getRowLastInsert = $this->rowLastGoogleSheet->getRowLastInsertByIdGoogleSheet($idGoogleSheet);
            $result = $rowDefault;
            if ($getRowLastInsert) {
                $result = $getRowLastInsert->number_row_last;
            }
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }
    /**
     * đồng bộ dữ liệu googleSheet Vset với server 
     *
     * @param Request $request
     * @return void
     */
    public function syncDataGoogleSheet(DataGoogleSheetRequest $request)
    {

        try {
            // lấy data từ googleSheet
            $data = $request->all();
            // thực hiện job  insert lead khách hàng //
            $this->hanleInserCustomerLead($data);
            // thực hiện job insert data  googleSheet và cập nhật lại số hàng cuối cùng //
            $this->handleInsertCustomerOnline($data);
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }

    /**
     * Kiểm tra tình trạng thái phân bổ (tự động hay mặc định)
     * @param [string] $id_google_sheet
     * @return mixed
     */
    public function getStatusAllotment($id_google_sheet)
    {
        try {
            // trạng thái phân bổ mặc định //
            $statusAlloment = 0;
            // lấy cấu hình phân bổ theo googleSheet // 
            $leadConfigResource = $this->configSource->getItemByIdGoogleSheet($id_google_sheet);
            // kiểm tra có cấu hình phân bổ //
            if ($leadConfigResource) {
                $statusAlloment = $leadConfigResource->is_rotational_allocation;
            }
            return response()->json(['data' => $statusAlloment]);
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }

    /**
     * Lấy trạng thái cấu hình phân bổ 
     * @param [string] $id_google_sheet
     * @return mixed
     */

    public function getConfigStatusAllotment($id_google_sheet)
    {
        try {
            // trạng thái phân bổ mặc định //
            $statusConfigAlloment = 0;
            // lấy cấu hình phân bổ theo googleSheet // 
            $leadConfigResource = $this->configSource->getItemByIdGoogleSheet($id_google_sheet);
            // kiểm tra có cấu hình phân bổ //
            if ($leadConfigResource) {
                if ($leadConfigResource->is_active == 1 && $leadConfigResource->is_deleted == 0) {
                    $statusConfigAlloment = 1;
                }
            }
            return response()->json(['data' => $statusConfigAlloment]);
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }

    /**
     * lấy id cấu hình phân bổ
     * @param [string] $id_google_sheet
     * @return int
     */

    public function getConfigAllotment($id_google_sheet)
    {
        try {
            $idConfigAllotment = 0;
            // kiểm tra có id hình phân bổ //
            $configAllotment = $this->configSource->getItemByIdGoogleSheet($id_google_sheet);
            if ($configAllotment) {
                $idConfigAllotment = $configAllotment->cpo_customer_lead_config_source_id;
            }
            return response()->json(['data' => $idConfigAllotment]);
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }

    /**
     * lấy id người tạo googleSheet
     * @param [string] $id_google_sheet
     * @return int
     */
    public function getIdUserCreatePipelines()
    {
        try {
            $itemPipeline = $this->pipeline->getPipelineDefault();
            $data = [
                "pipeline_code" => $itemPipeline->pipeline_code,
                "user_create" => $itemPipeline->owner_id
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }

    /**
     * hàm sử lý insert lead 
     * @param [array] $data
     * @return mixed
     */

    public function hanleInserCustomerLead($data)
    {
        if (isset($data['dataLeadInsert']) && isset($data['idGoogleSheet'])) {
            InsertCustomerLeadJob::dispatch($data['dataLeadInsert'], $data['idGoogleSheet']);
        }
    }

    /**
     * hàm sử lý insert customer lead online 
     * @param [array] $data
     * @return mixed
     */
    public function handleInsertCustomerOnline($data)
    {
        if (isset($data['dataOnlineLead'])) {
            SyncDataGoogleSheetLeadJob::dispatch($data);
        }
    }

    /**
     * lấy id nguồn khách hàng 
     * @param $code_customer_source
     * @return int 
     */

    public function getCustomerSource($code_customer_source)
    {
        try {
            $result = "";
            $customerSource =  $this->customerResouce->getSourceByCode($code_customer_source);
            if ($customerSource) {
                $result = $customerSource->customer_source_id;
            }
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
