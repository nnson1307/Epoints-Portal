<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/1/2019
 * Time: 14:01
 */

namespace Modules\Admin\Repositories\CustomerBranchMoney;


use Modules\Admin\Models\CustomerBranchMoneyTable;

class CustomerBranchMoneyRepository implements CustomerBranchMoneyRepositoryInterface
{
    protected $customer_branch_money;
    protected $timestamps = true;

    /**
     * CustomerBranchMoneyRepository constructor.
     * @param CustomerBranchMoneyTable $customer_branch_money_table
     */
    public function __construct(CustomerBranchMoneyTable $customer_branch_money_table)
    {
        $this->customer_branch_money = $customer_branch_money_table;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->customer_branch_money->add($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id,$branch)
    {
        // TODO: Implement edit() method.
        return $this->customer_branch_money->edit($data, $id,$branch);
    }

    /**
     * @param $id
     * @param $branch
     * @return mixed
     */
    public function getPriceBranch($id, $branch)
    {
        // TODO: Implement getPriceBranch() method.
        return $this->customer_branch_money->getPriceBranch($id, $branch);
    }
}