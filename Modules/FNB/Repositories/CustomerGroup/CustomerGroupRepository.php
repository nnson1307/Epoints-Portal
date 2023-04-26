<?php


namespace Modules\FNB\Repositories\CustomerGroup;


use Modules\FNB\Models\CustomerGroupTable;

class CustomerGroupRepository implements CustomerGroupRepositoryInterface
{
    private $customerGroup;

    public function __contruct(CustomerGroupTable $customerGroup){
        $this->customerGroup = $customerGroup;
    }


    public function getOption()
    {
        $customerGroup = app()->get(CustomerGroupTable::class);
        return $customerGroup->getOption();
    }
}