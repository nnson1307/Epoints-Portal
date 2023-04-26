<?php 
namespace Modules\Estimate\Repositories\EstimateBranchTime;

use Modules\Estimate\Models\EstimateBranchTimeTable;
use Modules\Estimate\Repositories\EstimateBranchTime\EstimateBranchTimeRepoInterface;

/**
 * Class EstimateBranchTimeRepo
 * @author HaoNMN
 * @since May 2022
 */
class EstimateBranchTimeRepo implements EstimateBranchTimeRepoInterface
{
    protected $estimateTable;
    protected $timestamps = true;


    /**
     * Init function
     * @param table
     */
    public function __construct(EstimateBranchTimeTable $estimateTable)
    {
        $this->estimateTable = $estimateTable;
    }

    /**
     * Lấy danh sách cấu hình theo tuần hoặc tháng
     * @param $type, $year
     * @return mixed
     */
    public function getEstimateList($type, $year, $branchId)
    {
        return $this->estimateTable->getEstimateList($type, $year, $branchId);
    }

    /**
     * Update or Create Quota Estimate 
     * @param $condition, $dataUpdate
     * @return mixed
     */
    public function updateOrCreateEstimate($condition, $dataUpdate)
    {
        return EstimateBranchTimeTable::updateOrCreate($condition, $dataUpdate);
    }

    /**
     * Lấy danh sách năm
     * @param $branchId
     * @return mixed
     */
    public function getYearsEstimate($branchId)
    {
        return $this->estimateTable->getYearsEstimate($branchId);
    }

    /**
     * chỉnh sửa cấu hình
     * @param $id $data
     * @return mixed
     */
    public function editEstimate($id, $data){
        return $this->estimateTable->editEstimate($id, $data);
    }
}