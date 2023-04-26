<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/29/2018
 * Time: 10:11 AM
 */

namespace Modules\Admin\Repositories\OrderDetail;


use Modules\Admin\Models\OrderDetailTable;

class OrderDetailRepository implements OrderDetailRepositoryInterface
{
    private $order_detail;

    /**
     * OrderDetailRepository constructor.
     * @param OrderDetailTable $order_details
     */
    public function __construct(OrderDetailTable $order_details)
    {
        $this->order_detail = $order_details;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->order_detail->add($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->order_detail->getItem($id);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->order_detail->edit($data, $id);
    }

    /**
     * @param $id_order
     * @return mixed
     */
    public function remove($id_order)
    {
        return $this->order_detail->remove($id_order);
    }

    //Lấy dữ liệu theo năm và objectType
    public function getValueByYearAndObjectType($year, $objectType)
    {
        return $this->order_detail->getValueByYearAndObjectType($year, $objectType);
    }

    //Lấy dữ liệu theo thời gian và objectType
    public function getValueByDateAndObjectType($startTime, $endTime, $objectType)
    {
        return $this->order_detail->getValueByDateAndObjectType($startTime, $endTime, $objectType);
    }

    //Lấy ra số tiền của ngày theo objectType
    public function getAmountByDateAndObjectType($date, $objectType)
    {
        $result = 0;
        $select = $this->order_detail->getAmountByDateAndObjectType($date, $objectType);
        if ($select[0]['amount'] != null) {
            $result = $select[0]['amount'];
        }
        return $result;
    }

    //Lấy dịch vụ theo chi nhánh theo năm hoặc từng tháng
    public function fetchServiceByBranch($branch, $year, $month = null)
    {
        return $this->order_detail->fetchServiceByBranch($branch, $year, $month);
    }

    //Truy vấn để lấy tổng số lượng báo cáo doanh thu theo dịch vụ và năm.
    public function fetchTotalBranchService($branch, $year)
    {
        return $this->order_detail->fetchTotalBranchService($branch, $year);
    }

    //Lấy dịch vụ theo nhóm dịch vụ theo năm hoặc từng tháng
    public function fetchServiceByServiceCategory($serviceCategory, $year, $month = null)
    {
        return $this->order_detail->fetchServiceByServiceCategory($serviceCategory, $year, $month);
    }

    //Truy vấn để lấy tổng số lượng báo cáo doanh thu theo nhóm dịch vụ và năm.
    public function fetchTotalServiceCategory($serviceCategory, $year)
    {
        return $this->order_detail->fetchTotalServiceCategory($serviceCategory, $year);
    }

    //Lấy dữ liệu theo năm, objectType và chi nhánh.
    public function getValueByDateObjectTypeBranch($startTime, $endTime, $objectType, $branch, $processStatus = null)
    {
        return $this->order_detail->getValueByDateObjectTypeBranch($startTime, $endTime, $objectType, $branch, $processStatus);
    }

    //Lấy ra số tiền của ngày theo objectType
    public function getAmountByDateObjectTypeBranch($date, $objectType, $branch)
    {
        $result = 0;
        $select = $this->order_detail->getAmountByDateObjectTypeBranch($date, $objectType, $branch);
        if ($select[0]['amount'] != null) {
            $result = $select[0]['amount'];
        }
        return $result;
    }

    //Báo cáo doanh thu dịch vụ, sản phẩm, thẻ dịch vụ: Lấy tất cả $objectType theo năm.
    public function fetchValueAllServiceByYear($year, $objectType)
    {
        $select = $this->order_detail->fetchValueAllServiceByYear($year, $objectType);
        return $select;
    }

    //Lấy ra dữ liệu theo năm và $objectType và $objectId.
    public function fetchValueYearObjTypeObjId($year, $objectType, $objectId)
    {
        $select = $this->order_detail->fetchValueYearObjTypeObjId($year, $objectType, $objectId);
        return $select;
    }

    //Lấy ra dữ liệu theo năm, chi nhánh, $objectType và $objectId.
    public function fetchValueYearBranchObjTypeObjId($year, $branch, $objectType, $objectId)
    {
        $select = $this->order_detail->fetchValueYearBranchObjTypeObjId($year, $branch, $objectType, $objectId);
        return $select;
    }

    //Lấy dữ liệu theo từ ngày đến ngày, $objectType và $objectId.
    public function getValueByDateObjectTypeObjectId($startTime, $endTime, $objectType, $objectId)
    {
        $select = $this->order_detail->getValueByDateObjectTypeObjectId($startTime, $endTime, $objectType, $objectId);
        return $select;
    }

    //Lấy giá trị theo ngày, chi nhánh, $objectType, $objectId.
    public function getValueByDate($date, $branch, $objectType, $objectId)
    {
        $result = 0;
        $select = $this->order_detail->getValueByDate($date, $branch, $objectType, $objectId);
        if ($select != null) {
            foreach ($select as $key => $value) {
                $result += $value['amount'];
            }
        }
        return $result;
    }

    //Lấy dữ liệu theo từ ngày đến ngày, chi nhánh, $objectType, $objectId.
    public function fetchValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType, $objectId)
    {
        $select = $this->order_detail->fetchValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType, $objectId);
        return $select;
    }

    //Lấy ra dữ liệu theo năm, chi nhánh, $objectType.
    public function fetchValueYearBranchObjType($year, $branch, $objectType)
    {
        $select = $this->order_detail->fetchValueYearBranchObjType($year, $branch, $objectType);
        return $select;
    }

    //Lấy ra số lượng của năm theo objectType và/hoặc chi nhánh.
    public function getQuantityByYearObjectTypeBranch($year, $objectType, $branch)
    {
        $select = $this->order_detail->getQuantityByYearObjectTypeBranch($year, $objectType, $branch);
        return $select;
    }

    //Thống kê: Lấy ra số lượng của objectType theo từ ngày đến ngày.
    public function getQuantityByObjectTypeTime($objectType, $objectId, $startTime, $endTime, $branch = null)
    {
        $select = $this->order_detail->getQuantityByObjectTypeTime($objectType, $objectId, $startTime, $endTime, $branch);
        return $select;
    }

    //Thống kê: Lấy ra số lượng của objectType và $objectId theo tháng.
    public function getQuantityByObjectTypeObjectIdMonth($objectType, $objectId, $year, $month)
    {
        $select = $this->order_detail->getQuantityByObjectTypeObjectIdMonth($objectType, $objectId, $year, $month);
        return $select;
    }

    //Lấy ra dữ liệu theo năm và $objectType và $objectId (process_status = paysuccess).
    public function getValueYearObjTypeObjId($year, $objectType, $objectId)
    {
        $select = $this->order_detail->getValueYearObjTypeObjId($year, $objectType, $objectId);
        return $select;
    }

    //Lấy dữ liệu theo từ ngày đến ngày, chi nhánh, $objectType,$objectId.
    public function getValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType)
    {
        $select = $this->order_detail->getValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType);
        return $select;
    }

    //Lấy objectType theo chi nhánh và từng tháng của năm
    public function getValueObjTypeByBranchMonth($objectType, $branch, $year, $month)
    {
        $select = $this->order_detail->getValueObjTypeByBranchMonth($objectType, $branch, $year, $month);
        return $select;
    }

    //Lấy số lượng thẻ dịch vụ đã bán, chi nhánh.
    public function getServiceCardByAllBranch($keyWord = null, $status = null, $cardType = null, $cardGroup = null, $detail = null)
    {
        $select = $this->order_detail->getServiceCardByAllBranch($keyWord, $status, $cardType, $cardGroup, $detail);
        return $select;
    }

    //Lấy dữ liệu chi tiết hóa đơn theo order_id và object_type
    public function getValueByOrderIdAndObjectType($orderId, $objectType)
    {
        $select = $this->order_detail->getValueByOrderIdAndObjectType($orderId, $objectType);
        return $select;
    }

    public function getAll($startTime, $endTime, $branch)
    {
        return $this->order_detail->getAll($startTime, $endTime, $branch);
    }

    public function getObjectByCustomer($customerId, $objectType)
    {
        return $this->order_detail->getObjectByCustomer($customerId, $objectType);
    }
}