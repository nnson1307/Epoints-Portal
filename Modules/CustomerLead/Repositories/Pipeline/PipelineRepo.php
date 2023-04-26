<?php

namespace Modules\CustomerLead\Repositories\Pipeline;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CustomerLead\Models\PipelineJourneyDefaultTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\PipelineCategoryTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\StaffsTable;

class PipelineRepo implements PipelineRepoInterface
{
    protected $pipeline;
    protected $pipelineCat;
    protected $journey;
    public function __construct(
        PipelineTable $pipeline,
        PipelineCategoryTable $pipelineCat,
        JourneyTable $journey
    )
    {
        $this->pipeline = $pipeline;
        $this->pipelineCat = $pipelineCat;
        $this->journey = $journey;
    }

    public function list(array $filters = [])
    {
        $list = $this->pipeline->getList($filters);

        return [
            'list' => $list
        ];
    }

    public function getListCategory()
    {
        return $list = $this->pipelineCat->getList();
    }

    /**
     * Thêm pipeline
     *
     * @param $data
     * @return array|mixed
     */
    public function store($data)
    {
        DB::beginTransaction();
        try {
            $inputPipeline = [
                'pipeline_name' => $data['pipeline_name'],
                'pipeline_category_code' => $data['pipeline_cat'],
                'time_revoke_lead' => $data['time_revoke_lead'],
                'owner_id' => $data['owner_id'],
                'is_default' => $data['is_default'],
                'created_by' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            // Check is_default -> update
            if($data['is_default'] == 1) {
                // update all is_default -> 0
                $this->pipeline->setDefaultZero();
            }
            // Insert pipeline
            $pipelineId = $this->pipeline->add($inputPipeline);
            // Update pipeline code
            $pipelineCode = 'PIPELINE_' . date('dmY') . sprintf("%02d", $pipelineId);
            $this->pipeline->edit([
                'pipeline_code' => $pipelineCode
            ], $pipelineId);
            // Insert journey
            $count = count($data['arrJourney']);
            if (isset($data['arrJourney']) && $count > 0) {
                foreach ($data['arrJourney'] as $key => $value) {
                    // default system
                    $defaultSystem = null;
                    if(isset($value['journey_code']) != ''){
                        switch ($value['journey_code']){
                            case 'PJD_DEAL_START':
                            case 'PJD_CUSTOMER_NEW': $defaultSystem = 'new'; break;
                            case 'PJD_CUSTOMER_FAIL': $defaultSystem = 'fail'; break;
                            case 'PJD_DEAL_END':
                            case 'PJD_CUSTOMER_SUCCESS': $defaultSystem = 'win'; break;
                        }
                    }

                    $inputJourney = [
                        'pipeline_id' => $pipelineId,
                        'pipeline_code' => $pipelineCode,
                        'journey_name' => $value['journey_name'],
                        'journey_updated' => '',
                        'default_system' => $defaultSystem,
                        'position' => $key + 1,
                        'created_by' => Auth::id(),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    if($data['pipeline_cat'] == 'CUSTOMER'){
                        $inputJourney['is_deal_created'] = $value['is_deal_created'];
                    }
                    else{
                        $inputJourney['is_contract_created'] = $value['is_contract_created'];
                    }
                    $journeyId = $this->journey->add($inputJourney);

                    // Update journey code
                    if (isset($value['journey_code']) && $value['journey_code'] != null) {
                        // Case: journey default
                        $this->journey->edit([
                            'journey_code' => $value['journey_code']
                        ], $journeyId);
                    } else {
                        $this->journey->edit([
                            'journey_code' => 'JOURNEY_' . date('dmY') . sprintf("%02d", $journeyId)
                        ], $journeyId);
                    }

                }
                $listJourney = $this->journey->getListByPipelineId($pipelineId);
                $journey_update = '';
                //duyet tung dong hanh trinh
                foreach ($data['arrJourney'] as $key => $value) {
                    // lay id của journey thông qua name + pipelineId
                    $journeyId = $this->journey->getIdByName($value, $pipelineId);
                    if (isset($value['status']) && count($value['status']) > 0) {
                        // duyet tung dong status cua hanh trinh
                        foreach ($value['status'] as $id => $statusName) {
                            foreach ($listJourney as $journey) {
                                // dò theo tên trong danh sách đã thêm
                                if($statusName == $journey['journey_name']) {
                                    $journey_update = (string)$journey_update . (string)$journey['journey_id'] . ',';
                                    break;
                                }
                            }
                        }
                        if($journey_update != '') {
                            // xoa dau , cuoi cung
                            $journey_update = substr($journey_update, 0, -1);
                        }
                    }
                    // update vao journey_update trong database (chua co journey id)
                    $this->journey->edit([
                        'journey_updated' => $journey_update
                    ], $journeyId['journey_id']);
                    $journey_update = '';
                }

            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật pipeline
     *
     * @param $data
     * @return array|mixed
     */
    public function update($data)
    {
        DB::beginTransaction();
        try {
            $pipelineId = $data['pipeline_id'];
            // update pipeline
            $updatePipeline = [
                'pipeline_name' => $data['pipeline_name'],
                'pipeline_category_code' => $data['pipeline_cat'],
                'time_revoke_lead' => $data['time_revoke_lead'],
                'owner_id' => $data['owner_id'],
                'is_default' => $data['is_default'],
                'updated_by' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Check is_default -> update
            if($data['is_default'] == 1) {
                // update all is_default -> 0
                $this->pipeline->setDefaultZeroCategory($data['pipeline_cat']);
            }
            $this->pipeline->edit($updatePipeline, $pipelineId);
            // Update list journey (delete journey in listRemove -> update list journey)
            // Delete journey in listRemove
            if (isset($data['listRemove']) && count($data['listRemove']) > 0){
                foreach ($data['listRemove'] as $key => $value) {
                    $this->journey->deleteByCode($value);
                }
            }
            $count = count($data['arrJourney']);
            if (isset($data['arrJourney']) && $count > 0) {
                foreach ($data['arrJourney'] as $key => $value) {
                    // default system
                    $defaultSystem = null;
//                    if(isset($value['journey_code']) != ''){
//                        switch ($value['journey_code']){
//                            case 'PJD_DEAL_START':
//                            case 'PJD_CUSTOMER_NEW': $defaultSystem = 'new'; break;
//                            case 'PJD_CUSTOMER_FAIL': $defaultSystem = 'fail'; break;
//                            case 'PJD_DEAL_END':
//                            case 'PJD_CUSTOMER_SUCCESS': $defaultSystem = 'win'; break;
//                        }
//                    }
//                    $defaultSystem = '';
//                    if($key == 0) {
//                        $defaultSystem = 'new';
//                    } else if ($key == $count - 2) {
//                        $defaultSystem = 'fail';
//                    } else if ($key == $count - 1) {
//                        $defaultSystem = 'win';
//                    }
                    // Check $data[journey_code], if != '' -> update, == '' -> insert
                    if ($value['journey_code'] == '') {
                        $inputJourney = [
                            'pipeline_id' => $pipelineId,
                            'pipeline_code' => $data['pipeline_code'],
                            'journey_name' => $value['journey_name'],
                            'journey_updated' => '',
//                            'default_system' => $defaultSystem,
                            'position' => $key + 1,
                            'created_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s'),
                        ];

                        if($data['pipeline_cat'] == 'CUSTOMER'){
                            $inputJourney['is_deal_created'] = $value['is_deal_created'];
                        }
                        else{
                            $inputJourney['is_contract_created'] = $value['is_contract_created'];
                        }
                        $journeyId = $this->journey->add($inputJourney);
                        // Update journey update

                        // Update journey code
                        $this->journey->edit([
                            'journey_code' => 'JOURNEY_' . date('dmY') . sprintf("%02d", $journeyId)
                        ], $journeyId);
                    } else {
                        $updateJourney = [
                          'journey_name' => $value['journey_name'],
                          'journey_updated' => '',
//                          'default_system' => $defaultSystem,
                          'position' => $key + 1,
                          'updated_by' => Auth::id(),
                        ];
                        if($data['pipeline_cat'] == 'CUSTOMER'){
                            $updateJourney['is_deal_created'] = $value['is_deal_created'];
                        }
                        else{
                            $updateJourney['is_contract_created'] = $value['is_contract_created'];
                        }
                        $this->journey->editByCode($updateJourney, $value['journey_code']);
                    }
                }
                $listJourney = $this->journey->getListByPipelineId($pipelineId);
                $journey_update = '';
                // duyet tung dong hanh trinh
                foreach ($data['arrJourney'] as $key => $value) {
                    // lay id của journey thông qua name + pipelineId
                    $journeyId = $this->journey->getIdByName($value, $pipelineId);
//                    dd($journeyId['journey_id']);
                    if (isset($value['status']) && count($value['status']) > 0) {
                        // duyet tung dong status cua hanh trinh
                        foreach ($value['status'] as $id => $statusName) {
                            foreach ($listJourney as $journey) {
                                // dò theo tên trong danh sách đã thêm
                                if($statusName == $journey['journey_name']) {
//                                    dd($journey['journey_id']);
                                    $journey_update = (string)$journey_update . (string)$journey['journey_id'] . ',';
                                    break;
                                }
                            }
                        }
                        if($journey_update != '') {
                            // xoa dau , cuoi cung
                            $journey_update = substr($journey_update, 0, -1);
                        }
                    }
                    // update vao journey_update trong database (chua co journey id)
                    $this->journey->edit([
                        'journey_updated' => $journey_update
                    ], $journeyId['journey_id']);
                    $journey_update = '';
                }
            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
//                '_message' => $e->getMessage()
            ];
        }
    }

    public function getDetail($pipelineId)
    {
        return $this->pipeline->getDetail($pipelineId);
    }

    public function getListJourney($pipelineId)
    {
        return $this->journey->getListByPipelineId($pipelineId);
    }

    public function destroy($pipelineId)
    {
        try {
            $this->pipeline->edit(['is_deleted' => 1], $pipelineId);
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

    public function setDefaultPipeline($pipelineId, $pipelineCategoryCode)
    {
        try {
            // Set all is_default = 0
            $this->pipeline->setDefaultZeroCategory($pipelineCategoryCode);
            // update is_default = 1
            $this->pipeline->edit(['is_default' => 1], $pipelineId);
            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ];
        }
    }

    public function checkJourneyBeUsed($pipelineCode)
    {
        $mCustomerLead = new CustomerLeadTable();
        $kq = $mCustomerLead->checkJourneyBeUsed($pipelineCode);
        if($kq == null) {
            return [
                'error' => false,
            ];
        } else {
            return [
                'error' => true,
                'message' => __('Không thể xoá hành trình đã phát sinh dữ liệu')
            ];
        }

    }

    public function getListJourneyDefault($pipelineCategoryCode)
    {
        $mPipelineJourneyDefault = new PipelineJourneyDefaultTable();
        return $mPipelineJourneyDefault->getListByPipelineCategoryCode($pipelineCategoryCode);
    }

    /**
     * Danh sách nhân viên
     *
     * @return mixed
     */
    public function getOptionStaff()
    {
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getStaffOption();
        return $optionStaff;
    }
}
