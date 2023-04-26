<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ManagerProject\Repositories\Project;


interface ProjectRepositoryInterface
{
    /**
     * Get queue list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Get all
     *
     * @param array $all
     */
    public function getAll(array $filters = []);

    /**
     * Get all
     *
     * @param array $all
     */
    public function getName();

    /**
     * Delete queue
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add queue
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update queue
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

    public function testCode($code, $id);

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '');

    /**
     * Lấy tên tiền tố dự án ngẫu nhiên
     * @param $param
     * @return string
     */

    public function getNamePrefix($param);


    /**
     * Thêm dự án
     * @param array $params
     * @return mixed
     */


    public function store($params);

    /**
     * Lấy record dự án
     * @param $id
     * @return mixed
     */

    public function getItemProject($id);

    /**
     * Cập nhật dự án
     * @param array $params
     * @return mixed
     */

    public function update($params);

    /**
     * Danh sách hiển thị thông tin cấu hình danh sách dự án
     * @return array
     */

    public function getConfigListProject();

    /**
     * Cấu hình danh sách hiển thị và lọc dự án
     * @param array $params
     * @return mixed
     */

    public function configListProject($params);

    /**
     * Lấy thông tin dự án
     * @param $idProject
     * @return mixed
     */

    public function getDetail($idProject);

    public function getDetailFix($idProject);

    /**
     * danh sach phong ban
     * @return mixed
     */
    public function getDepartment();

    /**
     * danh sach chi nhanh
     * @return mixed
     */
    public function getBranch();

    /**
     * Thông tin dự án
     * @param $input
     * @return mixed
     */
    public function getProjectInfo($id);

    /**
     * lay tat ca van de du an
     * @param $id
     * @return mixed
     */
    public function getAllIssueProject($id);

    /**
     * thông tin report dự án
     * @param $id
     * @return mixed
     */
    public function projectInfoReport($id);

    /**
     * thông tin công việc
     * @param $id
     * @return mixed
     */
    public function projectInfoWork($id);

    /**
     * dnah sách trạng thái
     * @return mixed
     */
    public function getStatus();

    /**
     * danh sách kiểu công việc
     * @return mixed
     */
    public function getTypeWork();

    /**
     * danh sách nhân viên
     * @return mixed
     */
    public function listStaff();

    /**
     * xóa nhắc nhở
     * @param $input
     * @return mixed
     */
    public function deleteRemind($input);

    /**
     * thong tin giai doan
     * @param $id
     * @return mixed
     */
    public function getInfoPhase($id, $param);

    /**
     * thong tin van de
     * @param $id
     * @param $param
     * @return mixed
     */
    public function getInfoIssue($id, $param);

    /**
     * them van de
     * @param $input
     * @return mixed
     */
    public function addIssue($input);

    /**
     * chinh sua van de
     * @param $input
     * @return mixed
     */
    public function editIssue($input);


    /**
     * xoa van de
     * @param $id
     * @return mixed
     */
    public function deleteIssue($id);

    /**
     * lay data popup edit
     * @param $id
     * @return mixed
     */
    public function popupEditIssue($id);

    /**
     * thong tin phieu thu-chi
     * @param $id
     * @param $param
     * @return mixed
     */
    public function getInfoExpenditure($id = null, $param);

    /**
     * popup hien thi them phieu thu
     * @param $param
     * @return mixed
     */
    public function popupAddPayment($param);

    /**
     * popup hien thi them phieu chi
     * @param $param
     * @return mixed
     */
    public function popupAddReceipt($param);

    /**
     * them phieu chi
     * @param $input
     * @return mixed
     */
    public function addNewPayment($input);

    /**
     * Load option các đối tượng theo loại
     * @param $data
     * @return mixed
     */
    public function loadOptionObjectAccounting($data);
    /**
     * them phieu thu
     * @param $input
     * @return mixed
     */
    public function addNewReceipt($input);
}
