<?php

/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 09:27
 */

namespace Modules\Dashbroad\Repositories;


interface DashbroadRepositoryInterface
{

    public function getTotalCustomer();

    public function getTotalCustomerOnDay();
    public function getTotalCustomerOnMonth();

    public function getOrders($status);

    public function getAppointment($status);

    public function listOrder($filter = []);

    public function listAppointment($filter = []);
    public function listServices($filter = []);

    public function listBirthday($filter = []);

    public function getAppointmentByDate($date);

    public function getOrderbyMonthYear($month, $year);

    public function getOrderByObjectType($type, $date);

    public function getTopService($date);

    public function getOrderbyDateMonth($date, $month, $year);

    public function dataViewIndex();

    /**
     * Danh sách service đã được đặt/ service
     * @return mixed
     */
    public function getService();

    /**
     * Lấy tổng số tiếp nhận
     */
    public function getTotalCustomerRequest();

    /*
    *Lấy danh sách tiếp nhận
    */
    public function getListCustomerRequestToDay();
}
