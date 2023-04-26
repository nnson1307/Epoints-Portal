<?php
namespace Modules\Kpi\Repositories\Criteria;

use Illuminate\Support\Facades\Auth;
use Modules\Kpi\Models\CpoJourneyTable;
use Modules\Kpi\Models\CpoPipelineTable;
use Modules\Kpi\Models\KpiCriteriaTable;
use Modules\Kpi\Models\KpiCriteriaUnitTable;
use Modules\Kpi\Models\StaffsTable;

/**
 * class KpiCriteriaRepo
 * @author HaoNMN
 * @since Jun 2022
 */
class KpiCriteriaRepo implements KpiCriteriaRepoInterface
{
    protected $kpiCriteriaTable;


    public function __construct(KpiCriteriaTable $kpiCriteriaTable)
    {
        $this->kpiCriteriaTable = $kpiCriteriaTable;
    }

    /**
     * Danh sách tiêu chí kpi
     * @param $request
     * @return mixed
     */
    public function listAction($param = [])
    {
        $data = $this->kpiCriteriaTable->getList($param);
        
        foreach ($data as $item) {
            $staff = StaffsTable::where('staff_id', $item['created_by'])->first();
            $item['created_by']  = $staff['full_name'];
        }
        return $data;
    }

    /**
     * Lưu dữ liệu tiêu chí kpi
     * @param $data
     * @return true
     */
    public function save($data) 
    {
        // Kiểm tra tên tiêu chí đã tồn tại hay chưa
        $kpiNameCheck = KpiCriteriaTable::where('kpi_criteria_name', 'LIKE', $data['kpi_criteria_name'])
                                        ->where('is_deleted', 0)
                                        ->first();
        if (! empty($kpiNameCheck)) {
            return [
                'error'   => 1,
                'message' => __('Tên tiêu chí đã tồn tại') 
            ];
        }

        // Nếu không tick checkbox chỉ số chặn thì gán = 0
        if (! isset($data['is_blocked'])) {
            $data['is_blocked'] = 0;
        }

        // Gán trạng thái, tiêu chí lead quan tâm, tiêu chí do người dùng tạo, người tạo, trạng thái xóa
        $data['status']            = 1;
        $data['is_lead']           = 0;
        $data['is_customize']      = 1;
        $data['created_by']        = Auth::id();
        $data['is_deleted']        = 0;
        $data['kpi_criteria_type'] = 'A';

        // Lưu tiêu chí
        $this->kpiCriteriaTable->add($data);
        return response()->json([
            'error'   => 0,
            'message' => __('Thêm thành công')
        ]);
    }

    /**
     * Cập nhật dữ liệu tiêu chí kpi
     * @param $data
     * @return true
     */
    public function update($data)
    {
        if ($data) {
            $id = $data['kpi_criteria_id'];
            unset($data['kpi_criteria_id']);
        }
        
        return $this->kpiCriteriaTable->updateCriteria($id, $data);
    }

    /**
     * Xóa tiêu chí kpi
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->kpiCriteriaTable->remove($id);
    }

    /**
     * Lấy dữ liệu option pipeline & hành trình tiêu chí lead quan tâm
     * @return array
     */
    public function getLeadOption($param)
    {
        $pipeline = CpoPipelineTable::where('is_deleted', 0)->pluck('pipeline_name', 'pipeline_id')->toArray();
        if (isset ($param['pipelineId'])) {
            $journey  = CpoJourneyTable::where('is_deleted', 0)->where('is_actived', 1)->where('pipeline_id', $param['pipelineId'])->pluck('journey_name', 'journey_id')->toArray();
        } else {
            $journey  = CpoJourneyTable::where('is_deleted', 0)->where('is_actived', 1)->pluck('journey_name', 'journey_id')->toArray();
        }
        
        return [
            'pipeline' => $pipeline,
            'journey'  => $journey
        ];
    }

    /**
     * Lấy danh sách đơn vị cho tiêu chí người dùng tạo
     */
    public function listUnit() 
    {
        $criteriaUnit = app()->get(KpiCriteriaUnitTable::class);
        return $criteriaUnit->listUnit();
    }
}