<?php


namespace Modules\FNB\Repositories\CustomerServiceCard;

use Modules\FNB\Models\CustomerServiceCardTable;

class CustomerServiceCardRepository implements CustomerServiceCardRepositoryInterface
{
    private $customerServiceCard;

    public function __contruct(CustomerServiceCardTable $customerServiceCard){
        $this->customerServiceCard = $customerServiceCard;
    }

    public function getMemberCard($customerId, $branchId)
    {
        $mCustomerServiceCardTable = app()->get(CustomerServiceCardTable::class);
        return $mCustomerServiceCardTable->getMemberCard($customerId, $branchId);
    }

    public function loadCardMember($id, $branch){
        $mCustomerServiceCardTable = app()->get(CustomerServiceCardTable::class);
        return $mCustomerServiceCardTable->loadCardMember($id, $branch);
    }

    public function searchCard($code) {
        $mCustomerServiceCardTable = app()->get(CustomerServiceCardTable::class);
        return $mCustomerServiceCardTable->searchCard($code);
    }
}