<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/12/2018
 * Time: 10:16 AM
 */

namespace Modules\Admin\Repositories\CustomerAppointment;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\WarrantyCardTable;
use Modules\Admin\Models\WarrantyPackageDetailTable;
use Modules\Admin\Models\WarrantyPackageTable;

class CustomerAppointmentRepository implements CustomerAppointmentRepositoryInterface
{
    protected $customer_appointment;
    protected $timestamps = true;

    /**
     * CustomerAppointmentRepository constructor.
     * @param CustomerAppointmentTable $customer_appointments
     */
    public function __construct(CustomerAppointmentTable $customer_appointments)
    {
        $this->customer_appointment = $customer_appointments;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->customer_appointment->getList($filters);
    }

    public function listCalendar($day_now)
    {
        return $this->customer_appointment->listCalendar($day_now);
    }

    public function listDayGroupBy($day)
    {
        return $this->customer_appointment->listDayGroupBy($day);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->customer_appointment->add($data);
    }

    /**
     * @param $date
     */
    public function getItemDetail($id)
    {
        return $this->customer_appointment->getItemDetail($id);
    }

    /**
     * @param $day
     */
    public function listDay($day)
    {
        return $this->customer_appointment->listDay($day);
    }

    /**
     * @param $day
     * @param $status
     * @return mixed
     */
    public function listDayStatus($day, $status)
    {
        return $this->customer_appointment->listDayStatus($day, $status);
    }

    /**
     * @param $time
     * @return mixed
     */
    public function listByTime($time, $day, $id)
    {
        return $this->customer_appointment->listByTime($time, $day, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemServiceDetail($id)
    {
        return $this->customer_appointment->getItemServiceDetail($id);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->customer_appointment->edit($data, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemEdit($id)
    {
        return $this->customer_appointment->getItemEdit($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemRefer($id)
    {
        return $this->customer_appointment->getItemRefer($id);
    }

    /**
     * @param $time
     * @param $day
     * @return mixed
     */
    public function listTimeSearch($time, $day)
    {
        return $this->customer_appointment->listTimeSearch($time, $day);
    }

    /**
     * @param $search
     * @param $day
     * @return mixed
     */
    public function listNameSearch($search, $day)
    {
        return $this->customer_appointment->listNameSearch($search, $day);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function detailDayCustomer($id)
    {
        return $this->customer_appointment->detailDayCustomer($id);
    }

    /**
     * @param $day
     * @param $id
     * @return mixed
     */
    public function detailCustomer($day, $id)
    {
        return $this->customer_appointment->detailCustomer($day, $id);
    }

    /**
     * @param $year
     * @param $month
     * @param $status
     * @param $branch
     * @return mixed
     */
    public function reportMonthYearBranch($year, $month, $status, $branch)
    {
        // TODO: Implement reportMonthYearBranch() method.
        return $this->customer_appointment->reportMonthYearBranch($year, $month, $status, $branch);
    }

    /**
     * @param $year
     * @param $status
     * @param $branch
     * @return mixed
     */
    public function reportYearAllBranch($year, $status, $branch)
    {
        // TODO: Implement reportYearAllBranch() method.
        return $this->customer_appointment->reportYearAllBranch($year, $status, $branch);
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function reportAppointmentSource($year, $branch)
    {
        // TODO: Implement reportAppointmentSource() method.
        return $this->customer_appointment->reportAppointmentSource($year, $branch);
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function reportGenderBranch($year, $branch)
    {
        // TODO: Implement reportGenderBranch() method.
        return $this->customer_appointment->reportGenderBranch($year, $branch);
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function reportCustomerSourceBranch($year, $branch)
    {
        // TODO: Implement reportCustomerSourceBranch() method.
        return $this->customer_appointment->reportCustomerSourceBranch($year, $branch);
    }

    /**
     * @param $time
     * @param $status
     * @param $branch
     * @return mixed
     */
    public function reportTimeAllBranch($time, $status, $branch)
    {
        // TODO: Implement reportTimeAllBranch() method.
        return $this->customer_appointment->reportTimeAllBranch($time, $status, $branch);
    }

    public function reportTimeAppointmentSource($time, $branch)
    {
        // TODO: Implement reportTimeAppointmentSource() method.
        return $this->customer_appointment->reportTimeAppointmentSource($time, $branch);
    }

    public function reportTimeGenderBranch($time, $branch)
    {
        // TODO: Implement reportTimeGenderBranch() method.
        return $this->customer_appointment->reportTimeGenderBranch($time, $branch);
    }

    public function reportTimeCustomerSourceBranch($time, $branch)
    {
        // TODO: Implement reportTimeCustomerSourceBranch() method.
        return $this->customer_appointment->reportTimeCustomerSourceBranch($time, $branch);
    }

    public function reportDateBranch($date, $status, $branch)
    {
        // TODO: Implement reportDateBranch() method.
        return $this->customer_appointment->reportDateBranch($date, $status, $branch);
    }

    //Lất tất cả lịch hẹn của hôm nay.
    public function getCustomerAppointmentTodays()
    {
        return $this->customer_appointment->getCustomerAppointmentTodays();
    }

    //search dashboard
    public function searchDashboard($keyword)
    {
        return $this->customer_appointment->searchDashboard($keyword);
    }

    public function reportTimeGenderBranch2($time, $branch)
    {
        return $this->customer_appointment->reportTimeGenderBranch2($time, $branch);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function listCancel(array $filters = [])
    {
        // TODO: Implement listCancel() method.
        return $this->customer_appointment->getListCancel($filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function listLate(array $filters = [])
    {
        // TODO: Implement listLate() method.
        return $this->customer_appointment->getListLate($filters);
    }

    public function checkNumberAppointment($customer_id, $date, $type, $branchId)
    {
        // TODO: Implement checkNumberAppointment() method.
        return $this->customer_appointment->checkNumberAppointment($customer_id, $date, $type, $branchId);
    }
    public function checkExistsAppointment($customer_id, $date, $time, $endDate, $endTime, $type, $branchId)
    {
        return $this->customer_appointment->checkExistsAppointment($customer_id, $date, $time, $endDate, $endTime, $type, $branchId);
    }

    /**
     * Thêm phiếu bảo hành điện tử
     *
     * @param $customerCode
     * @param $orderId
     * @param $orderCode
     * @param $dataTableAdd
     */
    public function addWarrantyCard($customerCode, $orderId, $orderCode, $dataTableAdd)
    {
        $mWarrantyDetail = new WarrantyPackageDetailTable();
        $mWarranty = new WarrantyPackageTable();
        $mWarrantyCard = new WarrantyCardTable();

        // get array object
        if ($dataTableAdd != null) {
            $arrObject = array_chunk($dataTableAdd, 14, false);
            if ($arrObject != null && count($arrObject) > 0) {
                foreach ($arrObject as $item) {
                    // value item
                    $objectId = isset($item[0]) ? $item[0] : 0;
                    $objectType = isset($item[2]) ? $item[2] : null;
                    $objectCode = isset($item[3]) ? $item[3] : null;
                    $objectPrice = isset($item[4]) ? $item[4] : 0;
                    $objectQuantity = isset($item[5]) ? $item[5] : 1;
                    if ($objectType == 'product' || $objectType == 'service' || $objectType == 'service_card') {
                        // get object code -> get packed_code -> get info warranty package
                        $warrantyDetail = $mWarrantyDetail->getDetailByObjectCode($objectCode, $objectType);
                        if ($warrantyDetail != null) {
                            $warranty = $mWarranty->getInfoByCode($warrantyDetail['warranty_packed_code']);
                            $dataInsert = [
                                'customer_code' => $customerCode,
                                'warranty_packed_code' => $warrantyDetail['warranty_packed_code'],
                                'quota' => $warranty['quota'],
                                'warranty_percent' => $warranty['percent'],
                                'warranty_value' => $warranty['required_price'],
                                'status' => 'new',
                                'object_type' => $objectType,
                                'object_type_id' => $objectId,
                                'object_code' => $objectCode,
                                'object_price' => $objectPrice,
                                'created_by' => Auth::id(),
                                'order_code' => $orderCode,
                                'description' => $warranty['detail_description']
                            ];
                            if ($objectQuantity > 1) {
                                for ($i = 0; $i < $objectQuantity; $i++) {
                                    $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                    // card code
                                    $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                    $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                                }
                            } else {
                                $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                // card code
                                $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Ds lịch sử đặt lịch
     *
     * @param $filter
     * @return mixed
     */
    public function listHistoryAppointment($filter)
    {
        $lst = $this->customer_appointment->getHistoryAppointmentByPhone($filter);
        return $lst;
    }
}