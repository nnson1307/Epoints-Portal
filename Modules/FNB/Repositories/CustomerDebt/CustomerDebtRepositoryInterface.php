<?php


namespace Modules\FNB\Repositories\CustomerDebt;


interface CustomerDebtRepositoryInterface
{

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    public function getCustomerDebtByOrder($order_id);

    public function getItemDebt($id_customer);
}