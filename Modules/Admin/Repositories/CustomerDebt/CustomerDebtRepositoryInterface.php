<?php


namespace Modules\Admin\Repositories\CustomerDebt;


interface CustomerDebtRepositoryInterface
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * @param array $filters
     * @return mixed
     */
    public function listCustomerDept(array $filters = []);

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

    /**
     * @param $id
     * @return mixed
     */
    public function getCustomerDebt($id);

    /**
     * @param $id_customer
     * @return mixed
     */
    public function getItemDebt($id_customer);

    /**
     * @param $id_branch
     * @param $time
     * @return mixed
     */
    public function reportDebtAll($id_branch, $time);

    /**
     * @param $order_id
     * @return mixed
     */
    public function getCustomerDebtByOrder($order_id);

    public function cancleReceipt($id);

    public function getItem($id);
}
