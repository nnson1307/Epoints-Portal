<?php
namespace Modules\Kpi\Repositories\Note;

/**
 * Interface KpiNoteRepoInterface
 * @author HaoNMN
 * @since Jul 2022
 */
interface KpiNoteRepoInterface
{
    // Danh sách phiếu giao
    public function list($param = []);

    // Lấy danh sách chi nhánh
    public function getBranch();

    // Lấy danh sách phòng ban
    public function getDepartment($branchId);

    // Lấy danh sách nhóm
    public function getTeam($departmentId);

    // Lấy danh sách nhấn viên
    public function getStaff($param);

    // Lấy danh sách tiêu chí
    public function getCriteria($param);

    // Lưu phiếu giao
    public function save($data);

    // Xóa phiếu giao
    public function remove($id);

    // Chi tiết phiếu giao
    public function detail($id);

    // Lưu chỉnh sửa phiếu giao
    public function update($data);

    // Lấy data bảng danh sách tiêu chí cho nhân viên
    public function listCriteriaTable($id);

    // Thêm record chỉ số kpi thực tế cho tiêu chí custom vào bảng calculate_kpi
    public function addKpiCalculate($data);
}