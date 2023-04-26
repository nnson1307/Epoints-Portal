<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/2/2018
 * Time: 4:06 PM
 */

namespace Modules\Booking\Repositories\Customer;


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

    /**
     * @return mixed
     */
    public function getAllCustomer();
}