<?php


namespace Modules\FNB\Repositories\ProductChild;


interface ProductChildRepositoryInterface
{
    /**
     * Cập nhật tên tiếng anh product child
     * @param $productId
     * @return mixed
     */
    public function updateNameProductChild($productId);

    /**
     * Lấy danh sách sản phẩm con có phân trang
     * @param $data
     * @return mixed
     */
    public function getListProductChild(array $filters = []);

    /**
     * Lấy thông tin product cha
     * @param $productChildId
     * @return mixed
     */
    public function getParentProduct($productChildId);

    /**
     * Lấy product child master
     * @param $productChildId
     * @return mixed
     */
    public function getParentProductMaster($productId);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    /*
     * get product child by code
     */
    public function getProductChildByCode($code);
}