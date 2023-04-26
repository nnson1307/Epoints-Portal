<?php
namespace Modules\Commission\Repositories;

/**
 * Interface CommissionRepoInterface
 * @author HaoNMN
 * @since Jun 2022
 */
interface CommissionRepoInterface
{
    /**
     * Lấy danh sách hoa hồng
     */
    public function listCommission(array $filter = []);

    /**
     * Chi tiết hoa hồng
     */
    public function getDetailCommission($id);

    /**
     * Lấy danh sách hoa hồng thực nhận
     */
    public function listCommissionReceived(array $filter = []);

    /**
     * Thêm hoa hồng
     *
     * @param $data
     * @return mixed
     */
    public function saveCommission($data);

    /**
     * Soft delete hoa hồng
     */
    public function removeCommission($id);

    /**
     * Lấy danh sách loại hợp đồng
     */
    public function getListCategory();

    /**
     * Lấy danh sách tag của hoa hồng
     */
    public function listTag();

    /**
     * Thêm mới tag
     */
    public function addTag($data);

    /**
     * Lấy danh sách nhân viên
     */
    public function getListStaff(array $filter = []);

    /**
     * Lấy danh sách loại nhân viên
     */
    public function getListType();

    /**
     * Lấy danh sách chi nhánh
     */
    public function getListBranch();

    /**
     * Lấy danh sách phòng ban
     */
    public function getListDepartment();

    /**
     * Lấy danh sách chức vụ
     */
    public function getListTitle();

    /**
     * Lấy danh sách loại hoa hồng
     */
    public function getListTypeCommission();

    /**
     * Lưu phân bổ vào database
     */
    public function saveCommissionAllocation($data);

    /**
     * Lấy danh sách nhân viên theo hoa hồng
     */
    public function getStaffByCommission($id);

    /**
     * Lấy data nhóm hàng hoá theo loại hàng hoá
     *
     * @param $input
     * @return mixed
     */
    public function getDataOrderGroupByType($input);

    /**
     * Load option hàng hoá
     *
     * @param $filter
     * @return mixed
     */
    public function listOptionOrderObject($filter);

    /**
     * Lấy option tiêu chí kpi
     *
     * @param $kpiCriteriaType
     * @return mixed
     */
    public function getOptionCriteria($kpiCriteriaType);

    /**
     * Lấy option loại hợp đồng
     *
     * @return mixed
     */
    public function getOptionContractCategory();

    /**
     * Danh sách nhân viên (phân trang)
     *
     * @param array $filter
     * @return mixed
     */
    public function listStaff($filter = []);

    /**
     * Lấy hoa hồng được phân bổ cho nhân viên
     *
     * @param $idStaff
     * @return mixed
     */
    public function getAllocationByStaff($idStaff);

    /**
     * Chỉnh sửa hoa hồng được phân bổ cho nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function editReceived($input);

    /**
     * Lấy data chi tiết hoa hồng nhân viên
     *
     * @param $idStaff
     * @return mixed
     */
    public function getDataDetailReceived($idStaff);

    /**
     * Lấy ds hoa hồng của nhân viên
     *
     * @param $filter
     * @return mixed
     */
    public function listStaffCommission($filter = []);

    /**
     * Thêm nhanh tags
     *
     * @param $input
     * @return mixed
     */
    public function createTag($input);

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);
}