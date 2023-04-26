<?php


namespace Modules\Admin\Repositories\CustomerDebt;


use Modules\Admin\Models\CustomerDebtTable;

class CustomerDebtRepository implements CustomerDebtRepositoryInterface
{
    protected $customer_debt;
    protected $timestamps = true;

    public function __construct(CustomerDebtTable $customer_debt)
    {
        $this->customer_debt = $customer_debt;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->customer_debt->getList($filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function listCustomerDept(array $filters = [])
    {
        return $this->customer_debt->getListByCustomer($filters);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->customer_debt->add($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->customer_debt->edit($data, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCustomerDebt($id)
    {
        return $this->customer_debt->getCustomerDebt($id);
    }

    /**
     * @param $id_customer
     * @return mixed
     */
    public function getItemDebt($id_customer)
    {
        return $this->customer_debt->getItemDebt($id_customer);
    }

    /**
     * @param $id_branch
     * @param $time
     * @return mixed
     */
    public function reportDebtAll($id_branch, $time)
    {
        return $this->customer_debt->reportDebtAll($id_branch, $time);
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function getCustomerDebtByOrder($order_id)
    {
        return $this->customer_debt->getCustomerDebtByOrder($order_id);
    }
    public function cancleReceipt($id)
    {
        return $this->customer_debt->cancleReceipt($id);
    }

    public function getItem($id)
    {
        return $this->customer_debt->getItem($id);
    }
}
