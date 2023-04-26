<?php


namespace Modules\Kpi\Repositories\Report;


interface _ReportRepoInterface
{
    /**
     * Lấy danh sách chi nhánh
     * @return mixed
     */
    public function getlistBranch();

    /**
     * Lấy danh sách phòng ban
     * @return mixed
     */
    public function getListDepartment($brand_id = null);

    /**
     * Thay đổi chi nhánh
     * @param $data
     * @return mixed
     */
    public function changeBranch($data);

    /**
     * Thay đổi phòng ban
     * @param $data
     * @return mixed
     */
    public function changeDepartment($data);

    /**
     * Lấy giao diện chart và table
     * @param $data
     * @return mixed
     */
    public function showChartTable($data);

    /**
     * Search dữ kiệu theo tháng
     * @param $data
     * @return mixed
     */
    public function searchMonth($data);

    /**
     * Tìm kiếm theo tuần
     * @param $data
     * @return mixed
     */
    public function searchWeek($data);

    /**
     * Tìm kiếm theo ngày
     * @param $data
     * @return mixed
     */
    public function searchDay($data);
}