<?php


namespace Modules\FNB\Repositories\ServiceCard;


use Modules\Admin\Models\ServiceCard;

class ServiceCardRepository implements ServiceCardRepositoryInterface
{
    protected $service_card;
    const IS_RESERVE = 1;

    public function __construct(ServiceCard $card)
    {
        $this->service_card = $card;
    }

    public function getServiceCardInfo($id)
    {
        // TODO: Implement getServiceCardInfo() method.
        return $this->service_card->getServiceCardInfo($id);
    }
}