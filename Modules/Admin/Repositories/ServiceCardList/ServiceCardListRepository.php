<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/16/2018
 * Time: 11:45 AM
 */

namespace Modules\Admin\Repositories\ServiceCardList;


use Modules\Admin\Models\ServiceCardList;

class ServiceCardListRepository implements ServiceCardListRepositoryInterface
{
    private $service_card_list;

    public function __construct(ServiceCardList $cardList)
    {
        $this->service_card_list = $cardList;
    }

    public function getAllByServiceCard($service_card_id, $filter = [])
    {
        // TODO: Implement getAllByServiceCard() method.
        return $this->service_card_list->getAllByServiceCard($service_card_id, $filter);
    }

    public function list($filter = [])
    {
        // TODO: Implement list() method.
        return $this->service_card_list->getList($filter);
    }

    public function getServiceCardListDetail($id)
    {
        // TODO: Implement getServiceCardListDetail() method.
        return $this->service_card_list->getServiceCardListDetail($id);
    }

    public function getUnuseCard($service_card_list_id, $branch_id)
    {
        //
        return $this->service_card_list->getUnuseCard($service_card_list_id, $branch_id);
    }

    public function getInUseCard($filter = [])
    {
        // TODO: Implement getInUseCard() method.
        return $this->service_card_list->getInUseCard($filter);
    }

    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->service_card_list->add($data);
    }

    public function edit(array $data, $id)
    {
        return $this->service_card_list->edit($data, $id);
    }

    public function getCodeOrder($branch_id, $id)
    {
        // TODO: Implement getCodeOrder() method.
        return $this->service_card_list->getCodeOrder($branch_id, $id);
    }

    public function getAllServiceCardList()
    {
        return $this->service_card_list->getAllServiceCardList();
    }

    public function getAll()
    {
        return $this->service_card_list->getAll();
    }

    public function getByNameType($name, $type)
    {
        return $this->service_card_list->getByNameType($name, $type);
    }

    public function searchCard($code)
    {
        // TODO: Implement searchCard() method.
        return $this->service_card_list->searchCard($code);
    }

    public function searchActiveCard($code, $branch)
    {
        return $this->service_card_list->searchActiveCard($code, $branch);
    }

    public function filterCardSold($cardType, $keyWord, $status, $branch, $staffActived, $startTime, $endTime)
    {
        return $this->service_card_list->filterCardSold($cardType, $keyWord, $status, $branch, $staffActived, $startTime, $endTime);
    }

    public function getDetailByCode($code)
    {
        return $this->service_card_list->getDetailByCode($code);
    }

    public function getItemDetailCustomer($id)
    {
        // TODO: Implement getItemDetailCustomer() method.
        return $this->service_card_list->getItemDetailCustomer($id);
    }

    public function getServiceCardListByOrderCode($orderCode)
    {
        return $this->service_card_list->getServiceCardListByOrderCode($orderCode);
    }
}