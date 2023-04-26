<?php


namespace Modules\FNB\Repositories\ProductTopping;


interface ProductToppingRepositoryInterface
{
    /**
     * lấy danh sách topping đã được thêm
     * @param $productId
     * @return mixed
     */
    public function getAllTopping($productId);

    /**
     * Thêm topping vào session
     * @param $data
     * @return mixed
     */
    public function storeTopping($data);

    /**
     * Thêm product_child_id vào session
     * @param $data
     * @return mixed
     */
    public function addToppingSession($data);

    /**
     * Xóa product child ra khỏi session
     * @param $data
     * @return mixed
     */
    public function removeToppingSession($data);

    /**
     * Lấy danh sách topping theo productId
     * @param $productId
     * @return mixed
     */
    public function getListTopping($productId);
}