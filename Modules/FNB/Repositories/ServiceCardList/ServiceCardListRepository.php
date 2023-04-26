<?php


namespace Modules\FNB\Repositories\ServiceCardList;


use Modules\FNB\Models\ServiceCardList;

class ServiceCardListRepository implements ServiceCardListRepositoryInterface
{
    private $service_card_list;

    public function __construct(ServiceCardList $cardList)
    {
        $this->service_card_list = $cardList;
    }

    public function searchCard($code)
    {
        // TODO: Implement searchCard() method.
        return $this->service_card_list->searchCard($code);
    }
}