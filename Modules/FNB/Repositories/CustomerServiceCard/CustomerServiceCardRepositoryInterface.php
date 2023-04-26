<?php


namespace Modules\FNB\Repositories\CustomerServiceCard;


interface CustomerServiceCardRepositoryInterface
{
    public function getMemberCard($customerId,$branchId);

    public function loadCardMember($id, $branch);

    public function searchCard($code);
}