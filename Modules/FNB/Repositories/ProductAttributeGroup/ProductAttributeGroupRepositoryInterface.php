<?php


namespace Modules\FNB\Repositories\ProductAttributeGroup;


interface ProductAttributeGroupRepositoryInterface
{
    /**
     * Lấy chi tiết attribute
     * @param $id
     * @return mixed
     */
    public function getDetail($id);

    /**
     * Lưu thông tin tiếng anh
     * @param $data
     * @return mixed
     */
    public function update($data);
}