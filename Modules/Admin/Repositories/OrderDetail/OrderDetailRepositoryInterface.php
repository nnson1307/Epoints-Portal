<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/29/2018
 * Time: 10:11 AM
 */

namespace Modules\Admin\Repositories\OrderDetail;


interface OrderDetailRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * @param $id_order
     * @return mixed
     */
    public function remove($id_order);

    //Lấy dữ liệu theo năm và objectType
    public function getValueByYearAndObjectType($year, $objectType);

    //Lấy dữ liệu theo thời gian và objectType
    public function getValueByDateAndObjectType($startTime, $endTime, $objectType);

    //Lấy ra số tiền của ngày theo objectType
    public function getAmountByDateAndObjectType($date, $objectType);

    //Lấy dịch vụ theo chi nhánh theo năm hoặc từng tháng
    public function fetchServiceByBranch($branch, $year, $month = null);

    //Truy vấn để lấy tổng số lượng báo cáo doanh thu theo dịch vụ và năm.
    public function fetchTotalBranchService($branch, $year);

    //Lấy dịch vụ theo nhóm dịch vụ theo năm hoặc từng tháng
    public function fetchServiceByServiceCategory($serviceCategory, $year, $month = null);

    //Truy vấn để lấy tổng số lượng báo cáo doanh thu theo nhóm dịch vụ và năm.
    public function fetchTotalServiceCategory($serviceCategory, $year);

    //Lấy dữ liệu theo năm, objectType và chi nhánh.
    public function getValueByDateObjectTypeBranch($startTime, $endTime, $objectType, $branch, $processStatus = null);

    //Lấy ra số tiền của ngày theo objectType và chi nhánh
    public function getAmountByDateObjectTypeBranch($date, $objectType, $branch);

    //Báo cáo doanh thu dịch vụ, sản phẩm, thẻ dịch vụ: Lấy tất cả $objectType theo năm.
    public function fetchValueAllServiceByYear($year, $objectType);

    //Lấy ra dữ liệu theo năm và $objectType và $objectId.
    public function fetchValueYearObjTypeObjId($year, $objectType, $objectId);

    //Lấy ra dữ liệu theo năm, chi nhánh, $objectType và $objectId.
    public function fetchValueYearBranchObjTypeObjId($year, $branch, $objectType, $objectId);

    //Lấy dữ liệu theo từ ngày đến ngày, $objectType và $objectId.
    public function getValueByDateObjectTypeObjectId($startTime, $endTime, $objectType, $objectId);

    //Lấy giá trị theo ngày, chi nhánh, $objectType, $objectId.
    public function getValueByDate($date, $branch, $objectType, $objectId);

    //Lấy dữ liệu theo từ ngày đến ngày, chi nhánh, $objectType, $objectId.
    public function fetchValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType, $objectId);

    //Lấy ra dữ liệu theo năm, chi nhánh, $objectType.
    public function fetchValueYearBranchObjType($year, $branch, $objectType);

    //Lấy ra số lượng của năm theo objectType và/hoặc chi nhánh.
    public function getQuantityByYearObjectTypeBranch($year, $objectType, $branch);

    //Thống kê: Lấy ra số lượng của objectType theo từ ngày đến ngày.
    public function getQuantityByObjectTypeTime($objectType, $objectId, $startTime, $endTime, $branch = null);

    //Thống kê: Lấy ra số lượng của objectType và $objectId theo tháng.
    public function getQuantityByObjectTypeObjectIdMonth($objectType, $objectId, $year, $month);

    //Lấy ra dữ liệu theo năm và $objectType và $objectId (process_status = paysuccess).
    public function getValueYearObjTypeObjId($year, $objectType, $objectId);

    //Lấy dữ liệu theo từ ngày đến ngày, chi nhánh, $objectType,$objectId.
    public function getValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType);

    //Lấy objectType theo chi nhánh và từng tháng của năm
    public function getValueObjTypeByBranchMonth($objectType, $branch, $year, $month);

    //Lấy số lượng thẻ dịch vụ đã bán, chi nhánh.
    public function getServiceCardByAllBranch($keyWord = null, $status = null, $cardType = null, $cardGroup = null, $detail = null);

    //Lấy dữ liệu chi tiết hóa đơn theo order_id và object_type
    public function getValueByOrderIdAndObjectType($orderId, $objectType);

    public function getAll($startTime, $endTime, $branch);

    public function getObjectByCustomer($customerId, $objectType);
}