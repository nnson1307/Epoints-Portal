<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/2/2018
 * Time: 4:06 PM
 */

namespace Modules\Booking\Repositories\Customer;


use Modules\Booking\Models\CustomerTable;

class CustomerRepository implements CustomerRepositoryInterface
{
    protected $customer;
    protected $timestamps = true;

    /**
     * CustomerRepository constructor.
     * @param CustomerTable $customers
     */
    public function __construct(CustomerTable $customers)
    {
        $this->customer = $customers;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->customer->getList($filters);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->customer->add($data);
    }


    /**
     * @param $data
     * @return mixed
     */
    public function getCustomerSearch($data)
    {
        return $this->customer->getCustomerSearch($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->customer->getItem($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemRefer($id)
    {
        return $this->customer->getItemRefer($id);
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->customer->edit($data, $id);
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function remove($id)
    {
        $this->customer->remove($id);
    }

    /**
     * @return array
     */
    public function getCustomerOption()
    {
        $array = array();
        foreach ($this->customer->getCustomerOption() as $item) {
            $array[$item['customer_id']] = $item['full_name'] . ' - ' . $item['phone1'];

        }
        return $array;
    }

    /**
     * @param $phone
     * @param $id
     * @return mixed
     */
    public function testPhone($phone, $id)
    {
        return $this->customer->testPhone($phone, $id);
    }

    /**
     * @param $phone
     * @return mixed
     */
    public function searchPhone($phone)
    {
        // TODO: Implement searchPhone() method.
        return $this->customer->searchPhone($phone);
    }

    /**
     * @param $phone
     * @return mixed
     */
    public function getCusPhone($phone)
    {
        // TODO: Implement getCusPhone() method.
        return $this->customer->getCusPhone($phone);
    }

    public function getCustomerIdName()
    {
        $array = array();
        foreach ($this->customer->getCustomerOption() as $item) {
            $array[$item['customer_id']] = $item['full_name'];
        }
        return $array;
    }

    /**
     * @return mixed
     */
    public function totalCustomer($yearNow)
    {

        return $this->customer->totalCustomer($yearNow);
    }

    /**
     * @param $yearNow
     * @return mixed
     */
    public function totalCustomerNow($yearNow)
    {
        return $this->customer->totalCustomerNow($yearNow);
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     */
    public function filterCustomerYearBranch($year, $branch)
    {
        return $this->customer->filterCustomerYearBranch($year, $branch);
    }

    /**
     * @param $year
     * @param $branch
     */
    public function filterNowCustomerBranch($year, $branch)
    {
        return $this->customer->filterNowCustomerBranch($year, $branch);
    }

    /**
     * @param $time
     * @param $branch
     * @return mixed
     */
    public function filterTimeToTime($time, $branch)
    {
        return $this->customer->filterTimeToTime($time, $branch);
    }

    /**
     * @param $time
     * @param $branch
     * @return mixed|void
     */
    public function filterTimeNow($time, $branch)
    {
        return $this->customer->filterTimeNow($time, $branch);
    }

    public function searchCustomerEmail($data, $birthday, $gender, $branch)
    {
        // TODO: Implement searchCustomerEmail() method.
        return $this->customer->searchCustomerEmail($data, $birthday, $gender, $branch);
    }

    //Lấy danh sách khách hàng có ngày sinh nhật là hôm nay.
    public function getBirthdays()
    {
        return $this->customer->getBirthdays();
    }

    public function searchDashboard($keyword)
    {
        return $this->customer->searchDashboard($keyword);
    }

    /**
     * @param $id_branch
     * @param $time
     * @param $top
     * @return mixed
     */
    public function reportCustomerDebt($id_branch, $time, $top)
    {
        return $this->customer->reportCustomerDebt($id_branch, $time, $top);
    }

    /**
     * @return mixed
     */
    public function getAllCustomer()
    {
        return $this->customer->getAllCustomer();
    }
}