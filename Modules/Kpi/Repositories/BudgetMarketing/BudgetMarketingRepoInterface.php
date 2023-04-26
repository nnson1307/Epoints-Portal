<?php
namespace Modules\Kpi\Repositories\BudgetMarketing;

/**
 * Interface BudgetMarketingRepoInterface
 * @author HaoNMN
 * @since Aug 2022
 */
interface BudgetMarketingRepoInterface
{
    // Lay ngan sach
    public function list($param = [], $type);

    // Lấy danh sách phòng ban
    public function getDepartment($branchId);

    // Lấy danh sách nhóm
    public function getTeam($departmentId);

    // Thêm ngân sách
    public function add($data);

    // Thêm ngân sách
    public function addDay($data);

    // Cập nhật ngân sách
    public function update($data);

    // Cập nhật ngân sách
    public function updatebyDay($data);

    public function remove($id);
}