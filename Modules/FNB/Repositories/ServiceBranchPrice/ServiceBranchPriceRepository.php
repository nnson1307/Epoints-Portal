<?php


namespace Modules\FNB\Repositories\ServiceBranchPrice;


use Modules\FNB\Models\ServiceBranchPriceTable;

class ServiceBranchPriceRepository implements ServiceBranchPriceRepositoryInterface
{
    private $serviceBranchPrice;

    public function contruct__(ServiceBranchPriceTable $serviceBranchPrice){
        $this->serviceBranchPrice = $serviceBranchPrice;
    }

    public function getOptionService($branchId)
    {
        $serviceBranchPrice = app()->get(ServiceBranchPriceTable::class);
        return $serviceBranchPrice->getOptionService($branchId);
    }


}