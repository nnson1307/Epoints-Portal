<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/11/2018
 * Time: 10:30 AM
 */

namespace Modules\Booking\Repositories\ServiceCard;


interface ServiceCardRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function edit($id, array $data);

    public function getServiceCardInfo($id);

    public function getServiceCardDetail($id);

    public function delete($id);

    public function searchServiceCard($data);

    public function getListAdd();

    /**
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id);

    public function getServiceCardOrder($code);

    public function getOption();

    public function getAllServiceCard();

    //Chi tiết thẻ dịch vụ
    public function detail($id);

    public function filter($keyWord, $status, $cardType, $cardGroup);

    //Kiểm tra tên thẻ.
    public function checkName($name, $id, $groupId);

    //Lấy danh sách thẻ đã bán.
    public function getServiceCardSold($cardType);

    //Lấy danh sách thẻ hết hạn theo ngày truyền vào.
    public function serviceCardNearlyExpireds($datetime);

    //Lấy danh sách các thẻ hết số lần sử dụng
    public function serviceCardOverNumberUseds($id);

    //Lấy danh sách thẻ hết hạn hôm nay.
    public function serviceCardExpireds();

    //Lấy nhóm thẻ thông qua id thẻ.
    public function getServiceGroup($id);
}