<?php


namespace Modules\ConfigDisplay\Repositories;


interface ConfigDisplayDetailRepoInterface
{
    /**
     * Lấy tất cả cấu hình hiển thị 
     * @param array $fillters
     * @return mixed
     */

    public function getAll(array $params);

    /**
     * get danh mục cấu hình hiển thị
     * @return mixed
     */

    public function getCategoryConfigDetail();

    /**
     * lấy tất cả khảo sát
     * @return mixed
     */

    public function getAllSurvey();

    /**
     * lấy vị trí hiển thị lớn nhất của item cấu hình hiển thị
     * @param int $id
     * @return mixed
     */

    public function getPositionMax($id);

    /**
     * Tạo mới banner
     * @param array $params
     * @return mixed
     */

    public function storeConfigDetail($params);

    /**
     * get item cấu hình hiển thị chi tiết
     * @param $id
     * @return mixed
     */

    public function getItem($id);

    /**
     * Cập nhật banner
     * @param array $params
     * @return mixed
     */

    public function updateConfigDetail($params);

    /**
     * Xoá banner 
     * @param array $params
     * @return mixed
     */

    public function destroy($params);

    /**
     * Load all promition 
     * @return mixed
     */

    public function getAllPromotion();

    /**
     * Danh sách sản phẩm
     * @param Request $request
     * @return mixed 
     */

    public function getAllProduct();

    /**
     * Danh sách bài viết
     * @param Request $request
     * @return mixed 
     */
    
    public function getAllPost();
}
