<?php

namespace Modules\Kpi\Repositories\Report;

interface ReportRepoInterface
{
    /**
     * Lấy option chi nhánh
     *
     * @return mixed
     */
    public function getOptionBranch();

    /**
     * Lấy option phòng ban
     *
     * @param $branchId
     * @return mixed
     */
    public function getOptionDepartment($branchId = null);

    /**
     * Lấy option nhóm
     *
     * @param $departmentId
     * @return mixed
     */
    public function getOptionTeam($departmentId = null);

    /**
     * Load dữ liệu báo cáo kpi
     *
     * @param $input
     * @return mixed
     */
    public function loadData($input);
}