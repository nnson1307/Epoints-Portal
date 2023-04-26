<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/11/2018
 * Time: 10:30 AM
 */

namespace Modules\Admin\Repositories\ServiceCard;


interface ServiceCardRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function edit($id, array $data);

    public function getServiceCardInfo($id);

    public function getServiceCardDetail($id);

    public function delete($id);

    public function searchServiceCard($data);

    public function getListAdd($categoryId, $search, $page);

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

    /**
     * Lưu ảnh trước khi điều trị, sau khi điều trị (thẻ dịch vụ đã bán)
     *
     * @param $input
     * @return mixed
     */
    public function saveImageServiceCardSold($input);

    /**
     * Lấy danh sách hình ảnh theo card code và order code
     *
     * @param $cardCode
     * @param $orderCode
     * @return mixed
     */
    public function getImageServiceCardSold($cardCode, $orderCode);

    /**
     * Lấy hình ảnh theo input cho view carousel
     *
     * @param $input
     * @return mixed
     */
    public function getImageForCarousel($input);

    /**
     * Bảo lưu thẻ dịch vụ đã bán
     *
     * @param $input
     * @return mixed
     */
    public function reserveServiceCard($input);

    /**
     * Mở bảo lưu thẻ dịch vụ đã bán (thẻ liệu trình)
     *
     * @param $input
     * @return mixed
     */
    public function openReserveServiceCard($input);

    /**
     * Modal cộng dồn thẻ liệu trình
     *
     * @param $input
     * @return mixed
     */
    public function modalAccrualSCSold($input);

    /**
     * submit cộng dồn thẻ liệu trình
     *
     * @param $input
     * @return mixed
     */
    public function submitAccrualSCSold($input);
}