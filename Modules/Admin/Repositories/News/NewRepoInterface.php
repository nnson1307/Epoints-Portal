<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 4:42 PM
 */

namespace Modules\Admin\Repositories\News;


interface NewRepoInterface
{
    /**
     * Danh sách bài viết
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Dữ liệu view thêm bài viết
     *
     * @return mixed
     */
    public function dateViewCreate();

    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return mixed
     */
    public function uploadAction($input);

    /**
     * Thêm bài viết
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Dữ liệu view chỉnh sửa bài viết
     *
     * @param $newId
     * @return mixed
     */
    public function dataViewEdit($newId);

    /**
     * Chỉnh sửa bài viết
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Thay đổi trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);

    /**
     * Xóa bài viết
     *
     * @param $newId
     * @return mixed
     */
    public function remove($newId);
}