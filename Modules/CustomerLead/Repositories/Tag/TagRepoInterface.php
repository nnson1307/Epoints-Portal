<?php

namespace Modules\CustomerLead\Repositories\Tag;

interface TagRepoInterface
{
    /**
     * Danh sach tag
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Them tag moi
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

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