<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/11/2018
 * Time: 10:31 AM
 */

namespace Modules\Booking\Repositories\ServiceCard;


use Modules\Booking\Models\ServiceCard;

class ServiceCardRepository implements ServiceCardRepositoryInterface
{
    protected $service_card;

    public function __construct(ServiceCard $card)
    {
        $this->service_card = $card;
    }

    public function list(array $filters = [])
    {
        // TODO: Implement list() method.
        return $this->service_card->getList($filters);
    }

    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->service_card->add($data);
    }

    public function getServiceCardInfo($id)
    {
        // TODO: Implement getServiceCardInfo() method.
        return $this->service_card->getServiceCardInfo($id);
    }

    public function edit($id, array $data)
    {
        // TODO: Implement edit() method.
        return $this->service_card->edit($id, $data);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
        return $this->service_card->remove($id);
    }

    public function getServiceCardDetail($id)
    {
        // TODO: Implement getServiceCardDetail() method.
        return $this->service_card->getServiceCardDetail($id);
    }

    public function searchServiceCard($data)
    {
        // TODO: Implement searchServiceCard() method.
        return $this->service_card->getServiceCardSearch($data);
    }

    public function getListAdd()
    {
        // TODO: Implement getListAdd() method.
        return $this->service_card->getListAdd();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id)
    {
        return $this->service_card->getItemDetail($id);
    }

    public function getServiceCardOrder($code)
    {
        // TODO: Implement getServiceCardOrder() method.
        return $this->service_card->getServiceCardOrder($code);
    }

    /*
     *  get option
     */
    public function getOption()
    {
        $array = [];
        $data = $this->service_card->getOption();
        foreach ($data as $item) {
            $array[$item['service_card_id']] = $item['name'];
        }
        return $array;
    }

    public function getAllServiceCard()
    {
        return $this->service_card->getAllServiceCard();
    }

    //Chi tiết thẻ dịch vụ
    public function detail($id)
    {
        return $this->service_card->detail($id);
    }

    public function filter($keyWord, $status, $cardType, $cardGroup)
    {
        return $this->service_card->filter($keyWord, $status, $cardType, $cardGroup);
    }

    //Kiểm tra tên thẻ.
    public function checkName($name, $id, $groupId)
    {
        return $this->service_card->checkName($name, $id, $groupId);
    }

    //Lấy danh sách thẻ đã bán.
    public function getServiceCardSold($cardType)
    {
        return $this->service_card->getServiceCardSold($cardType);
    }

    //Lấy danh sách thẻ hết hạn theo ngày truyền vào.
    public function serviceCardNearlyExpireds($datetime)
    {
        return $this->service_card->serviceCardNearlyExpireds($datetime);
    }

    //Lấy danh sách các thẻ hết số lần sử dụng
    public function serviceCardOverNumberUseds($id)
    {
        return $this->service_card->serviceCardOverNumberUseds($id);
    }

    //Lấy danh sách thẻ hết hạn hôm nay.
    public function serviceCardExpireds()
    {
        return $this->service_card->serviceCardExpireds();
    }

    //Lấy nhóm thẻ thông qua id thẻ.
    public function getServiceGroup($id)
    {
        return $this->service_card->getServiceGroup($id);
    }
}