<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 2:30 PM
 */

namespace Modules\Admin\Repositories\CustomerServiceCard;


interface CustomerServiceCardRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * @param $code
     * @return mixed
     */
    public function searchCard($code);

    /**
     * @param $code
     * @param $id
     * @return mixed
     */
    public function searchCardReceipt($code, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function getCodeOrder($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getItemCard($id);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * @param $code
     * @param $branch
     * @return mixed
     */
    public function searchActiveCard($code, $branch);

    /**
     * @param array $data
     * @param $code
     * @return mixed
     */
    public function editByCode(array $data, $code);

    /**
     * @param $id
     * @param $branch
     * @return mixed
     */
    public function loadCardMember($id, $branch);

    /**
     * @param $search
     * @param $id
     * @param $branch
     * @return mixed
     */
    public function searchCardMember($search, $id, $branch, $page);

    //Lấy các thẻ dịch vụ đã sử dụng.
    public function getServiceCardUsed($objectId, array $filter = []);

    //Lấy thẻ đã kích hoạt theo mã thẻ
    public function getCardActiveByCode($code, $keyWord = null, $branch = null, $staffActived = null, $startTime = null, $endTime = null);

    public function filterCardSold($cardType, $keyWord, $branch, $staffActived, $startTime, $endTime);

    //Lấy chi tiết của thẻ đã bán
    public function getDetailCardSold($code, array $filter = []);

    //Lấy chi tiết thẻ theo mã.
    public function getCardByCode($code);

    public function memberCardDetail($id, $branch);

    /**
     * Cập nhật thông tin thẻ dịch vụ đã bán
     *
     * @param array $data
     * @return mixed
     */
    public function editCardSold(array $data);


    /**
     * @param $customer_id
     * @return mixed
     */
    public function getCustomerCardAll($customer_id);
}
