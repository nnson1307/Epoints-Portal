<?php


namespace Modules\FNB\Repositories\CustomerBranchMoney;


use Modules\FNB\Models\CustomerBranchMoneyTable;

class CustomerBranchMoneyRepository implements CustomerBranchMoneyRepositoryInterface
{
    private $customerBranchMoney;

    public function __contruct(CustomerBranchMoneyTable $customerBranchMoney){
        $this->customerBranchMoney = $customerBranchMoney;
    }

    public function getPriceBranch($customerId, $branchId)
    {
        return $this->customerBranchMoney->getPriceBranch($customerId, $branchId);
    }
}