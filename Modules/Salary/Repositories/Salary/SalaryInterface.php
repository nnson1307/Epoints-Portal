<?php

namespace Modules\Salary\Repositories\Salary;

interface SalaryInterface
{

    public function createSalary($salaryId);
    /**
     * export excel bảng lương
     * @param $data
     * @return mixed
     */
    public function exportExcelSalary();

    /**
     * Get Salary Config list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete Salary Config
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Salary Config
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Salary Config
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    /**
     * Import execel
     * @param $data
     * @return mixed
     */
    public function importExcelSalary($data);

    /**
     * Khoá lương
     * @param $data
     * @return mixed
     */
    public function lockSalary($data);

    /**
     * Hiển thị popup cập nhật lương
     * @param $data
     * @return mixed
     */
    public function showModalEditSalary($data);

    /**
     * Cập nhật tên bảng lương
     * @param $data
     * @return mixed
     */
    public function editSalary($data);

    /**
     * Lấy thông tin chi tiết salary staff
     * @param $data
     * @return mixed
     */
    public function getDetailSalaryStaff($id);

    /**
     * Lưu bảng lương
     * @param $data
     * @return mixed
     */
    public function editSalarySave($data);

    /**
     * hiển thị table hoa hồng
     * @param $data
     * @return mixed
     */
    public function showTableCommission($data);
}