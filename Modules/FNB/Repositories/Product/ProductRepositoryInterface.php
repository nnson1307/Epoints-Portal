<?php


namespace Modules\FNB\Repositories\Product;


interface ProductRepositoryInterface
{
    /**
     * Lấy chi tiết sản phẩm
     * @param $productId
     * @return mixed
     */
    public function getDetail($productId);

    /**
     * Cập nhật tiếng anh
     * @param $data
     * @return mixed
     */
    public function update($data);

    /**
     * Kiểm tra tên tiếng anh
     * @param $data
     * @return mixed
     */
    public function checkNameAction($data);

    /**
     * Cập nhật only update
     * @param $data
     * @return mixed
     */
    public function updateAll($data,$id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);
}