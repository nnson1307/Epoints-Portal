<?php


namespace Modules\CustomerLead\Repositories\ConfigSourceLead;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\CustomerLead\Models\ConfigSourceLeadMapTable;
use Modules\CustomerLead\Models\ConfigSourceLeadTable;
use Modules\CustomerLead\Models\DepartmentTable;
use Modules\CustomerLead\Models\TeamTable;

class ConfigSourceLeadRepo implements ConfigSourceLeadRepoInterface
{
    private $configSourceLead;

    public function __construct(ConfigSourceLeadTable $configSourceLead)
    {
        $this->configSourceLead = $configSourceLead;
    }

    /**
     * Danh sách cấu hình có phân trang
     * @param array $filter
     */
    public function getList($filter = []){

        $mConfigSourceMap = app()->get(ConfigSourceLeadMapTable::class);


        $list = $this->configSourceLead->getList($filter);

        if (count($list) != 0) {
            foreach($list as $key => $item){
                $list[$key]['list_department'] = $mConfigSourceMap->getAll($item['cpo_customer_lead_config_source_id']);
            }
        }

        return [
            'list' => $list,
        ];
    }

    public function listDepartment()
    {
        $mDepartment = app()->get(DepartmentTable::class);
        $listDepartment = $mDepartment->getOption();

        return [
            'listDepartment' => $listDepartment
        ];
    }

    public function listTeam()
    {
        $mTeam = app()->get(TeamTable::class);
        return $mTeam->getAll();
    }

    public function showPopup($input)
    {
        try {
            $mConfigSourceMap = app()->get(ConfigSourceLeadMapTable::class);
            $mTeam = app()->get(TeamTable::class);
            $detail = null;
            if (isset($input['id'])){
                $detail = $this->configSourceLead->getItem($input['id']);
                $listMap = $mConfigSourceMap->getAll($input['id']);
                if (count($listMap) != 0){
                    $listMap = collect($listMap)->pluck('department_id')->toArray();
                }
                $detail['list_department'] = $listMap;
            }

            $listDepartment = $this->listDepartment();
            $listTeam = $mTeam->getAll();
            $view = view('customer-lead::config-source-lead.popup.popup-config',[
                'listDepartment' => $listDepartment['listDepartment'],
                'listTeam' => $listTeam,
                'detail' => $detail
            ])->render();

            return [
                'error' => false,
                'message' => __('Hiển thị popup thành công'),
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại'),
                '__message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Lưu cấu hình
     * @param $input
     * @return mixed|void
     */
    public function saveConfig($input)
    {
        try {
            $mConfigSourceMap = app()->get(ConfigSourceLeadMapTable::class);
            $data = [
                'team_marketing_id' => $input['team_marketing_id'],
                'link' => $input['link'],
                'id_google_sheet' => explode('/',str_replace('https://docs.google.com/spreadsheets/d/','',$input['link']))[0] ?? '',
                'is_rotational_allocation' => isset($input['is_rotational_allocation']) ? 1 : 0,
                'is_active' => isset($input['is_active']) ? 1 : 0,
                'is_deleted' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            if (isset($input['cpo_customer_lead_config_source_id'])){
                $mConfigSourceMap->removeItem($input['cpo_customer_lead_config_source_id']);
                $this->configSourceLead->updateItem($data,$input['cpo_customer_lead_config_source_id']);
                $id = $input['cpo_customer_lead_config_source_id'];
            } else {
                $data['created_at'] = Carbon::now();
                $data['created_by'] = Auth::id();
                $id = $this->configSourceLead->addItem($data);
            }

            if (isset($input['department_id']) && count($input['department_id']) != 0){
                $dataDepartment = [];
                foreach ($input['department_id'] as $item){
                    $dataDepartment[] = [
                        'cpo_customer_lead_config_source_id' => $id,
                        'department_id' => $item,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id()
                    ];
                }

                if (count($dataDepartment) != 0){
                    $mConfigSourceMap->addItem($dataDepartment);
                }
            }

            return [
                'error' => false,
                'message' => __('Lưu cấu hình thành công'),
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lưu cấu hình thất bại'),
                '__messsage' => $e->getMessage()
            ];
        }
    }

    /**
     * Xóa cấu hình
     * @param $input
     * @return mixed|void
     */
    public function destroy($input){
        try {

            $mConfigSourceMap = app()->get(ConfigSourceLeadMapTable::class);

            $this->configSourceLead->removeConfig($input);
            $mConfigSourceMap->removeItem($input['cpo_customer_lead_config_source_id']);
            return [
                'error' => false,
                'message' => __('Xóa cấu hình thành công'),
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Xóa cấu hình thất bại'),
                '__messsage' => $e->getMessage()
            ];
        }
    }
}