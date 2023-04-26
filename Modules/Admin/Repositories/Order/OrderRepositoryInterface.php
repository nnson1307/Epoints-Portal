<?php

/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/20/2018
 * Time: 10:20 PM
 */

namespace Modules\Admin\Repositories\Order;


interface OrderRepositoryInterface
{
    public function calculatedCommission($quantity, $refer_id, $check_commission = null, $id_detail, $object_id, $item4 = null, $item10, $item11, $refer_money = 0, $staff_money = 0, $type = "");
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
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id);

    /**
     * @param $id
     * @return mixed|void
     */
    public function getItemDetailPrint($id);

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

    public function detailDayCustomer($id);

    public function detailCustomer($id);

    public function getIndexReportRevenue();

    public function getValueByYear($year, $startTime = null, $endTime = null);

    public function getValueByDate($date, $field = null, $valueField = null, $field2 = null, $valueField2 = null);

    //Lấy dữ liệu với tham số truyền vào(thời gian, cột)
    public function getValueByParameter($date, $filer, $valueFilter);

    //Lấy giá trị từ ngày - đến ngày.
    public function getValueByDay($startTime, $endTime);

    //Lấy dữ liệu với tham số truyền vào(thời gian, cột) 2
    public function getValueByParameter2($startTime, $endTime, $filer, $valueFilter);

    //Lấy giá trị theo năm, cột và giá trị cột truyền vào
    public function fetchValueByParameter($year, $startTime, $endTime, $field, $fieldValue);

    //Lấy giá trị theo năm, cột và giá trị 2 cột truyền vào
    public function fetchValueByParameter2($year, $startTime, $endTime, $field, $fieldValue, $field2, $fieldValue2);

    public function getValueByDate2($date, $branch, $customer);

    //Lấy các giá trị theo created_at, branch_id và created_by
    public function getValueByDate3($date, $branch, $staff);

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng.
    public function getDataReportGrowthByCustomer($year, $month, $operator, $customerOdd, $field = null, $valueField = null);

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng theo năm.
    public function getDataReportGrowthCustomerByYear($year, $operator, $customerOdd, $branch);

    //Thống kê tăng trưởng khách hàng(theo nhóm khách hàng).
    public function getValueReportGrowthByCustomerCustomerGroup($year, $branch = null);

    //Thống kê tăng trưởng khách hàng(theo nguồn khách hàng).
    public function getValueReportGrowthByCustomerCustomerSource($year, $branch = null);

    //Thống kê tăng trưởng khách hàng(theo giới tính).
    public function getValueReportGrowthByCustomerCustomerGender($year, $branch = null);

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng(từ ngày đến ngày và/hoặc chi nhánh).
    public function getDataReportGrowthByCustomerDataBranch($startTime, $endTime, $operator, $customerOdd, $branch);

    //Thống kê tăng trưởng khách hàng(theo nhóm khách hàng) theo từ ngày đến ngày và/hoặc chi nhánh.
    public function getValueReportGrowthByCustomerCustomerGroupTimeBranch($startTime, $endTime, $branch = null);

    //Thống kê tăng trưởng khách hàng(theo giới tính) theo từ ngày đến ngày và/hoặc chi nhánh.
    public function getValueReportGrowthByCustomerCustomerGenderTimeBranch($startTime, $endTime, $branch);

    //Thống kê tăng trưởng khách hàng(theo nguồn khách hàng) theo từ ngày tới ngày và/hoặc chi nhánh.
    public function getValueReportGrowthByCustomerCustomerSourceTimeBranch($startTime, $endTime, $branch);

    //Lấy dữ liệu theo năm/từ ngày đến ngày và chi nhánh
    public function getValueByYearAndBranch($year, $branch, $startTime = null, $endTime = null);

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng theo năm cho từng chi nhánh.
    public function getDataReportGrowthCustomerByTime($startTime, $endTime, $operator, $customerOdd, $branch);


    public function searchDashboard($keyword);

    public function getAllByCondition($startTime, $endTime, $branch);

    public function getCustomerDetail($id);

    public function getValueByParameter3($startTime, $endTime, $filer, $valueFilter);

    public function getValueByParameter4($startTime, $endTime, $filer, $valueFilter, $customerGroup = null);

    public function getValueByYear2($year, $startTime = null, $endTime = null);

    public function fetchValueByParameter3($year, $startTime, $endTime, $field, $fieldValue);

    /**
     * Chuyển tiếp chi nhánh khi đặt đơn hàng từ app
     *
     * @param $input
     * @return mixed
     */
    public function applyBranch($input);

    /**
     * Xóa đơn giao hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function removeDelivery($orderId);

    /**
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @param $customerId
     * @param $orderSource
     * @param $promotionType
     * @param $quantity
     * @param $date
     * @return mixed
     */
    public function getPromotionDetail($objectType, $objectCode, $customerId, $orderSource, $promotionType, $quantity = null, $date = null);

    /**
     * Thêm phiếu bảo hành điện tử
     *
     * @param $customerCode
     * @param $orderId
     * @param $orderCode
     * @param $dataTableAdd
     * @param $dataTableEdit
     */
    public function addWarrantyCard($customerCode, $orderId, $orderCode, $dataTableAdd, $dataTableEdit = null);

    /**
     * Export danh sách đơn hàng
     * @param array $params
     * @return mixed
     */
    public function exportList($params = []);

    /**
     * Lưu ảnh trước/sau khi sử dụng
     *
     * @param $input
     * @return mixed
     */
    public function saveImage($input);

    /**
     * Lưu log dự kiến nhắc sử dụng
     *
     * @param $orderId
     * @param $customerId
     * @param $arrObject
     * @return mixed
     */
    public function insertRemindUse($orderId, $customerId, $arrObject);

    /**
     * Lưu thông tin đơn hàng
     *
     * @param $orderId
     * @param int $isPayment
     * @return mixed
     */
    public function updateContractGoods($orderId, $isPayment = 0);

    /**
     * Hiển thị popup địa chỉ
     * @param $data
     * @return mixed
     */
    public function showPopupAddress($data);

    /**
     * hiển thị popup thêm địa chỉ
     * @param $data
     * @return mixed
     */
    public function showPopupAddAddress($data);

    /**
     * Thay đổi tỉnh thành
     * @param $data
     * @return mixed
     */
    public function changeProvince($data);

    /**
     * Thay đổi quận huyện
     * @param $data
     * @return mixed
     */
    public function changeDistrict($data);

    /**
     * Tạo địa chỉ nhận hàng
     * @param $data
     * @return mixed
     */
    public function submitAddress($data);

    /**
     * Xoá địa chỉ khách hàng
     * @param $data
     * @return mixed
     */
    public function removeAddressCustomer($data);

    /**
     * Thay đổi thông tin địa chỉ giao hàng
     * @param $data
     * @return mixed
     */
    public function changeInfoAddress($data);

    /**
     * Kiểm tra số serial được enter
     * @param $data
     * @return mixed
     */
    public function checkSerialEnter($data);

    /**
     * Xoá số serial
     * @param $data
     * @return mixed
     */
    public function removeSerial($data);

    /**
     * Hiển thị popup serial
     * @param $data
     * @return mixed
     */
    public function showPopupSerial($data);

    /**
     * Tìm kiếm serial
     * @param $data
     * @return mixed
     */
    public function searchSerial($data);

    /**
     * Lấy danh sách serial theo từng sản phẩm
     * @param $data
     * @return mixed
     */
    public function getListSerial($data);

    /**
     * Lấy danh sách serial theo id đơn hàng
     * @param $data
     * @return mixed
     */
    public function getListSerialOrder($orderId, $session);

    public function saveOrderWithoutReceipt($request);

    public function createQrCodeVnPay($input);

    /**
     * Chọn sản phẩm/ dịch vụ/ thẻ dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function chooseType($input);

    /**
     * Lấy thông tin sản phẩm/dịch vụ kèm theo
     *
     * @param $input
     * @return mixed
     */
    public function getDataAttach($input);
}