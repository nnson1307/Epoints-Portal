<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/2/2018
 * Time: 4:06 PM
 */

namespace Modules\Admin\Repositories\Customer;


interface CustomerRepositoryInterface
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);


    /**
     * @param $data
     * @return mixed
     */
    public function getCustomerSearch($data);

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);
    public function getItemLog($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getItemRefer($id);


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id);

    /**
     * @return mixed
     */
    public function getCustomerOption();

    /**
     * @param $phone
     * @param $id
     * @return mixed
     */
    public function testPhone($phone, $id);

    /**
     * @param $phone
     * @return mixed
     */
    public function searchPhone($phone);

    /**
     * @param $phone
     * @return mixed
     */
    public function getCusPhone($phone);

    public function getCustomerIdName();

    /**
     * @param $yearNow
     * @return mixed
     */
    public function totalCustomer($yearNow);

    /**
     * @param $yearNow
     * @return mixed
     */
    public function totalCustomerNow($yearNow);

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function filterCustomerYearBranch($year, $branch);

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function filterNowCustomerBranch($year, $branch);

    /**
     * @param $time
     * @param $branch
     * @return mixed
     */
    public function filterTimeToTime($time, $branch);

    /**
     * @param $time
     * @param $branch
     * @return mixed
     */
    public function filterTimeNow($time, $branch);

    /**
     * @param $data
     * @param $birthday
     * @param $gender
     * @param $branch
     * @return mixed
     */
    public function searchCustomerEmail($data, $birthday, $gender, $branch);

    public function searchCustomerPhoneEmail($data, $birthday, $gender, $branch,$arrPhone = [], $arrEmail = []);

    //Lấy danh sách khách hàng có ngày sinh nhật là hôm nay.
    public function getBirthdays();

    public function searchDashboard($keyword);

    /**
     * @param $id_branch
     * @param $time
     * @param $top
     * @return mixed
     */
    public function reportCustomerDebt($id_branch, $time, $top);


    public function getAllCustomer($filter = []);

    /**
     * lay thong tin khach hang va dia chi mac dinh (neu co)
     * @param $id
     * @return mixed
     */
    public function getCustomerAndDefaultContact($id);

    /**
     * Sử dụng thẻ liệu trình
     *
     * @param $input
     * @return mixed
     */
    public function usingCard($input);

    /**
     * Thêm nhanh loại thông tin
     *
     * @param $input
     * @return mixed
     */
    public function addInfoType($input);

    /**
     * Danh sách khách hàng thuộc nhóm KH (tự định nghĩa hoặc tự động)
     *
     * @param $filterTypeGroup
     * @param $customerGroupFilter
     * @return mixed
     */
    public function searchCustomerGroupFilter($filterTypeGroup, $customerGroupFilter);

    /**
     * Show modal thêm chi nhánh được xem
     *
     * @param $input
     * @return mixed
     */
    public function modalCustomerBranch($input);

    /**
     * Thêm chi nhánh được xem
     *
     * @param $input
     * @return mixed
     */
    public function saveCustomerBranch($input);

    /**
     * Cập nhật thông tin khách hàng
     * @param $input
     * @return mixed
     */
    public function customerUpdateWard($input);

    /**
     * Lấy lịch sử thanh toán của khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function getReceiptCustomer($input);

    /**
     * Load tab trong chi tiết KH
     *
     * @param $input
     * @return mixed
     */
    public function loadTabDetail($input);

    /**
     * Danh sách tích luỹ
     *
     * @param $input
     * @return mixed
     */
    public function listLoyalty($input);

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed
     */
    public function addComment($data);

    /**
     * hiển thị popup comment
     * @param $data
     * @return mixed
     */
    public function showFormComment($data);

    /**
     * Lấy danh sách comment
     * @param $id
     * @return mixed
     */
    public function getListComment($id);

    /**
     * Lấy dữ liệu in bill công nợ
     *
     * @param $input
     * @return mixed
     */
    public function getDataPrintBillDebt($input);

    /**
     * Lấy dữ liệu pop thanh toán nhanh
     *
     * @param $input
     * @return mixed
     */
    public function getDataQuickReceiptDebt($input);

    /**
     * Submit thanh toán nhanh
     *
     * @param $input
     * @return mixed
     */
    public function submitQuickReceiptDebt($input);

    /**
     * Danh sách người liên hệ
     *
     * @param $input
     * @return mixed
     */
    public function listPersonContact($input);

    /**
     * Lấy dữ liệu view tạo người liên hệ
     *
     * @return mixed
     */
    public function getDataCreatePersonContact();

    /**
     * Thêm người liên hệ
     *
     * @return mixed
     */
    public function storePersonContact($input);

    /**
     * Lấy dữ liệu view chỉnh sửa người liên hệ
     *
     * @param $input
     * @return mixed
     */
    public function getDataEditPersonContact($input);

    /**
     * Chỉnh sửa người liên hệ
     *
     * @param $input
     * @return mixed
     */
    public function updatePersonContact($input);

    /**
     * Danh sách ghi chú
     *
     * @param $input
     * @return mixed
     */
    public function listNote($input);

    /**
     * Thêm ghi chú
     *
     * @param $input
     * @return mixed
     */
    public function storeNote($input);

    /**
     * Lấy data view chỉnh sửa ghi chú
     *
     * @param $input
     * @return mixed
     */
    public function getDataEditNote($input);

    /**
     * Chỉnh sửa ghi chú
     *
     * @param $input
     * @return mixed
     */
    public function updateNote($input);

    /**
     * Danh sách tập tin
     *
     * @param $input
     * @return mixed
     */
    public function listFile($input);

    /**
     * Danh sách tập tin
     *
     * @param $input
     * @return mixed
     */
    public function listDeals($input);

    /**
     * Thêm tập tin
     *
     * @param $input
     * @return mixed
     */
    public function storeFile($input);

    /**
     * Lấy data view chỉnh sửa file
     *
     * @param $input
     * @return mixed
     */
    public function getDataEditFile($input);

    /**
     * Chỉnh sửa tập tin
     *
     * @param $input
     * @return mixed
     */
    public function updateFile($input);
}