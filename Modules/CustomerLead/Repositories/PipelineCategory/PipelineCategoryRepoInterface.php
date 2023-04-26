<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/23/2020
 * Time: 10:22 AM
 */

namespace Modules\CustomerLead\Repositories\PipelineCategory;


interface PipelineCategoryRepoInterface
{
    /**
     * Danh sách pipeline category
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Thêm pipeline category
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data view chỉnh sửa pipeline category
     *
     * @param $categoryId
     * @return mixed
     */
    public function dataEdit($categoryId);

    /**
     * Chỉnh sửa pipeline category
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Thay đổi trạng thái pipeline category
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);

    /**
     * Xóa pipeline category
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}