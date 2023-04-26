<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2022
 * Time: 10:03
 */

namespace Modules\StaffSalary\Repositories\ReportBudgetBranch;


interface ReportBudgetBranchRepoInterface
{
    /**
     * Lấy data filter
     *
     * @return mixed
     */
    public function getDataFilter();

    /**
     * Lấy ds ngân sách theo chi nhánh
     *
     * @param $filter
     * @return mixed
     */
    public function getList($filter = []);

     /**
     * Lấy ds ngân sách theo chi nhánh biểu đồ
     *
     * @param array $filter
     * @return mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getListChart($filter = []);
}