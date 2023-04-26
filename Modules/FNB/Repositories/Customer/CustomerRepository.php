<?php


namespace Modules\FNB\Repositories\Customer;


use Modules\FNB\Models\CustomerTable;

class CustomerRepository implements CustomerRepositoryInterface
{
    private $customer;

    public function __contruct(CustomerTable $customer){
        $this->customer = $customer;
    }

    /**
     * Lấy thông tin chi tiết
     * @param $customerId
     */
    public function getItem($customerId)
    {
        $mCustomer = app()->get(CustomerTable::class);
        return $mCustomer->getItem($customerId);
    }

    /**
     * @return array
     */
    public function getCustomerOption()
    {
        $customer = app()->get(CustomerTable::class);
        $array = array();
        foreach ($customer->getCustomerOption() as $item) {
            $array[$item['customer_id']] = $item['full_name'] . ' - ' . $item['phone1'];

        }
        return $array;
    }

    public function edit($data,$id){
        $mCustomer = app()->get(CustomerTable::class);
        return $mCustomer->edit($data, $id);
    }
}