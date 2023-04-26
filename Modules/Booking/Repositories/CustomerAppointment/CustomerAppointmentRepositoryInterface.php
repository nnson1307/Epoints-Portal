<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/12/2018
 * Time: 10:16 AM
 */

namespace Modules\Booking\Repositories\CustomerAppointment;


interface CustomerAppointmentRepositoryInterface
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * @param $status
     * @return mixed
     */
    public function listCalendar($day_now);

    /**
     * @param $status
     * @return mixed
     */
    public function listDayGroupBy($day);

    /**
     * @param $day
     * @return mixed
     */
    public function listDay($day);

    /**
     * @param $day
     * @param $status
     * @return mixed
     */
    public function listDayStatus($day, $status);

    /**
     * @param $time
     * @return mixed
     */
    public function listByTime($time, $day, $id);

    /**
     * @param $time
     * @param $day
     * @return mixed
     */
    public function listTimeSearch($time, $day);

    /**
     * @param $name
     * @param $phone
     * @param $day
     * @return mixed
     */
    public function listNameSearch($search, $day);

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param $date
     * @return mixed
     */
    public function getItemDetail($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getItemServiceDetail($id);

    /**
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function getItemEdit($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getItemRefer($id);

    /**
     * @param $id
     * @return mixed
     */
    public function detailDayCustomer($id);

    /**
     * @param $day
     * @param $id
     * @return mixed
     */
    public function detailCustomer($day, $id);

    /**
     * @param $year
     * @param $month
     * @param $status
     * @param $branch
     * @return mixed
     */
    public function reportMonthYearBranch($year, $month, $status, $branch);

    /**
     * @param $year
     * @param $status
     * @param $branch
     * @return mixed
     */
    public function reportYearAllBranch($year, $status, $branch);

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function reportAppointmentSource($year, $branch);

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function reportGenderBranch($year, $branch);

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function reportCustomerSourceBranch($year, $branch);

    /**
     * @param $time
     * @param $status
     * @param $branch
     * @return mixed
     */
    public function reportTimeAllBranch($time, $status, $branch);

    public function reportTimeAppointmentSource($time, $branch);

    public function reportTimeGenderBranch($time, $branch);

    public function reportTimeCustomerSourceBranch($time, $branch);

    public function reportDateBranch($date, $status, $branch);

    //Lất tất cả lịch hẹn của hôm nay.
    public function getCustomerAppointmentTodays();

    //search dashboard
    public function searchDashboard($keyword);

    public function reportTimeGenderBranch2($time, $branch);

    /**
     * @param array $filters
     * @return mixed
     */
    public function listCancel(array $filters = []);

    public function listLate(array $filters = []);

    public function checkNumberAppointment($customer_id,$date,$type);
}