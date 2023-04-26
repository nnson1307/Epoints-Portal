<?php


namespace Modules\Report\Repository\StaffCommission;


interface StaffCommissionRepoInterface
{
    /**
     * filter time, number staff cho biểu đồ
     *
     * @param $input
     * @return mixed
     */
    public function filterAction($input);

    /**
     * Danh sách chi tiết hoa hồng nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function listDetail($input);
    /**
     * Export excel chi tiết hoa hồng nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function exportDetail($input);

    /**
     * Export excel tổng hoa hồng nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function exportTotal($input);
}