<?php


namespace Modules\Survey\Repositories\Branch;


interface ApplyRepositoryInterface
{
    /**
     * Tìm kiếm chi nhánh 
     * @param $params
     * @return mixed
     */
    public function searchBranch($params);

    /**
     * Tìm kiếm khách hàng 
     * @param $params
     * @return mixed
     */

    public function searchCustomer($params);

    /**
     * Tìm kiếm nhân viên
     * @param $params
     * @return mixed
     */

    public function searchStaff($params);

    /**
     * Tìm kiếm khách hàng 
     * @param $params
     * @return mixed
     */
    public function searchCustomerAuto($params);

    /**
     * Checked item (chi nhánh) - tạm
     * @param $params
     * @return mixed
     */
    public function checkedItemTemp($params);

    /**
     * Checked item (Khách hàng) - tạm
     * @param $params
     * @return mixed
     */
    public function checkedItemTempCustomer($params);

    /**
     * Checked item (Nhân viên) - tạm
     * @param $params
     * @return mixed
     */
    public function checkedItemTempStaff($params);

    /**
     * Submit thêm item (khách hàng) tạm vào chính
     * @param $params
     * @return mixed
     */
    public function submitAddItemTemp($params);

    /**
     * Submit thêm item (Nhân viên) tạm vào chính
     * @param $params
     * @return mixed
     */
    public function submitAddItemTempStaff($params);

    /**
     * Submit thêm item (khách hàng) tạm vào chính tự động
     * @param $params
     * @return mixed
     */
    public function submitAddItemTempAuto($params);


    /**
     * Load danh sách outlet đã chọn
     * @param $params
     * @return mixed
     */
    public function loadItemSelect($params);

    /**
     * Load danh sách khách hàng đã chọn
     * @param $params
     * @return mixed
     */
    public function loadItemSelectCustomer($params);

    /**
     * Load danh sách khách hàng đã chọn
     * @param $params
     * @return mixed
     */
    public function loadItemSelectStaff($params);

    /**
     * Remove outlet trong danh sách outlet đã chọn
     * @param $params
     * @return mixed
     */
    public function removeItemSelected($params);

    /**
     * Xoá khách hàng  trong danh sách khách hàng đã chọn
     * @param $params
     * @return mixed
     */
    public function removeItemSelectedCustomer($params);

    /**
     * Xoá nhân viên  trong danh sách khách hàng đã chọn
     * @param $params
     * @return mixed
     */
    public function removeItemSelectedStaff($params);

    /**
     * Tìm kiếm danh sách nhóm cửa hàng
     * @param $params
     * @return mixed
     */
    public function searchAllOutletGroup($params);

    /**
     * lưu áp dụng khảo sát
     * @param $params
     * @return mixed
     */
    public function update($params);

    /**
     * Lấy tât cả điều kiện mặc đinh của nhân viên 
     * @param $params
     * @return mixed
     */

    public function getConditionStaff($params);

    /**
     * Lấy danh sách điều kiện nhóm nhân viên động seleted
     * @param $params
     * @return mixed
     */
    public function getConditionStaffSeleted($params);
}
