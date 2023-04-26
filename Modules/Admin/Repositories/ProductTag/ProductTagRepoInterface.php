<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 10:16
 */

namespace Modules\Admin\Repositories\ProductTag;


interface ProductTagRepoInterface
{
    /**
     * Thêm tag
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Danh sach tag
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Cap nhat tag
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Lay chi tiet tag
     *
     * @param $tagId
     * @return mixed
     */
    public function getDetail($tagId);

    /**
     * Xoa tag
     *
     * @param $tagId
     * @return mixed
     */
    public function deleteTag($tagId);

}