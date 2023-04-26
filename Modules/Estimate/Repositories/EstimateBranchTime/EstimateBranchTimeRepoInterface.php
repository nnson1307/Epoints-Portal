<?php
namespace Modules\Estimate\Repositories\EstimateBranchTime;

/**
 * Interface EstimateBranchTimeRepoInterface
 * @author HaoNMN
 * @since May 2022
 */
interface EstimateBranchTimeRepoInterface
{
    /**
     * Lấy danh sách cấu hình theo tuần hoặc tháng
     * @param $type, $year
     */
    public function getEstimateList($type, $year, $branchId);

    /**
     * Update or Create Quota Estimate 
     * @param $condition, $dataUpdate
     */
    public function updateOrCreateEstimate($condition, $dataUpdate);

    /**
     * Lấy danh sách năm
     * @param $branchId
     */
    public function getYearsEstimate($branchId);

    /**
     * chỉnh sửa cấu hình
     * @param $id $data
     */
    public function editEstimate($id, $data);
}