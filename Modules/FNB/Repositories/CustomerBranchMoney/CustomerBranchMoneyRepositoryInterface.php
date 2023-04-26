<?php


namespace Modules\FNB\Repositories\CustomerBranchMoney;


interface CustomerBranchMoneyRepositoryInterface
{
    public function getPriceBranch($customerId, $branchId);
}