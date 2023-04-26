<?php


namespace Modules\FNB\Repositories\CustomerDebt;


use Modules\FNB\Models\CustomerDebtTable;

class CustomerDebtRepository implements CustomerDebtRepositoryInterface
{
    protected $customer_debt;
    protected $timestamps = true;

    public function __construct(CustomerDebtTable $customer_debt)
    {
        $this->customer_debt = $customer_debt;
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

    public function getCustomerDebtByOrder($order_id) {
        return $this->customer_debt->getCustomerDebtByOrder($order_id);
    }

    public function getItemDebt($id_customer) {
        return $this->customer_debt->getItemDebt($id_customer);
    }
}