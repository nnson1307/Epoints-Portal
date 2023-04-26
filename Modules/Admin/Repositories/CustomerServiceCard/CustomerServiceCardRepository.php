<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 2:30 PM
 */

namespace Modules\Admin\Repositories\CustomerServiceCard;


use Modules\Admin\Models\CustomerServiceCardTable;

class CustomerServiceCardRepository implements CustomerServiceCardRepositoryInterface
{
    protected $customer_service_card;
    protected $timestamps = true;

    public function __construct(CustomerServiceCardTable $customer_service_cards)
    {
        $this->customer_service_card = $customer_service_cards;
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function add(array $data)
    {
        return $this->customer_service_card->add($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->customer_service_card->getItem($id);

    }

    /**
     * @param $code
     * @return mixed
     */
    public function searchCard($code)
    {
        // TODO: Implement searchCard() method.
        return $this->customer_service_card->searchCard($code);
    }

    /**
     * @param $code
     * @param $id
     * @return mixed
     */
    public function searchCardReceipt($code, $id)
    {
        // TODO: Implement searchCardReceipt() method.
        return $this->customer_service_card->searchCardReceipt($code, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCodeOrder($id)
    {
        // TODO: Implement getCodeOrder() method.
        return $this->customer_service_card->getCodeOrder($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemCard($id)
    {
        // TODO: Implement getItemCard() method.
        return $this->customer_service_card->getItemCard($id);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->customer_service_card->edit($data, $id);
    }

    /**
     * @param $code
     * @param $branch
     * @return mixed
     */
    public function searchActiveCard($code, $branch)
    {
        // TODO: Implement searchActiveCard() method.
        return $this->customer_service_card->searchActiveCard($code, $branch);
    }

    /**
     * @param array $data
     * @param $code
     * @return mixed
     */
    public function editByCode(array $data, $code)
    {
        return $this->customer_service_card->editByCode($data, $code);
    }

    /**
     * @param $id
     * @param $branch
     * @return mixed|void
     */
    public function loadCardMember($id, $branch)
    {
        return $this->customer_service_card->loadCardMember($id, $branch);
    }

    /**
     * @param $search
     * @param $id
     * @param $branch
     * @return mixed
     */
    public function searchCardMember($search, $id, $branch, $page)
    {
        // TODO: Implement searchCardMember() method.
        return $this->customer_service_card->searchCardMember($search, $id, $branch, $page);
    }

    //Lấy các thẻ dịch vụ đã sử dụng.
    public function getServiceCardUsed($objectId, array $filter = [])
    {
        return $this->customer_service_card->getServiceCardUsed($objectId, $filter);
    }

    //Lấy thẻ đã kích hoạt theo mã thẻ
    public function getCardActiveByCode($code, $keyWord = null, $branch = null, $staffActived = null, $startTime = null, $endTime = null)
    {
        return $this->customer_service_card->getCardActiveByCode($code, $keyWord, $branch, $staffActived, $startTime, $endTime);
    }

    public function filterCardSold($cardType, $keyWord, $branch, $staffActived, $startTime, $endTime)
    {
        return $this->customer_service_card->filterCardSold($cardType, $keyWord, $branch, $staffActived, $startTime, $endTime);
    }

    //Lấy chi tiết của thẻ đã bán
    public function getDetailCardSold($code, array $filter = [])
    {
        return $this->customer_service_card->getDetailCardSold($code, $filter);
    }

    //Lấy chi tiết thẻ theo mã.
    public function getCardByCode($code)
    {
        return $this->customer_service_card->getCardByCode($code);
    }

    public function memberCardDetail($id, $branch)
    {
        // TODO: Implement memberCardDetail() method.
        return $this->customer_service_card->memberCardDetail($id, $branch);
    }

    /**
     * Cập nhật thông tin thẻ dịch vụ đã bán
     *
     * @param array $data
     * @return mixed
     */
    public function editCardSold(array $data)
    {
        try {
            $card_code = $data['card_code'];
            $expired_date = ($data['expired_date']) ?? null;
            $expired_date = ($expired_date != null) ? str_replace('/', '-', $expired_date) : null;
            $expired_date = ($expired_date != null) ? date('Y-m-d', strtotime($expired_date)) : null;
            $data['number_using'] = ($data['number_using']) ?? 0;
            $dataEdit = [
                'expired_date' => isset($data['not_limit']) ? null : $expired_date,
                'note' => strip_tags($data['note']),
                'number_using' => strip_tags($data['number_using']),
                'is_deleted' => isset($data['is_deleted']) ? 1 : 0,
            ];

            if (isset($data['count_using'])) {
                $dataEdit['count_using'] = strip_tags($data['count_using']);
            }

            $this->customer_service_card->editByCode($dataEdit, $card_code);

            return [
                'error' => 0
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1
            ];
        }
    }

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getCustomerCardAll($customer_id)
    {
        return $this->customer_service_card->getCustomerCardAll($customer_id);
    }
}
