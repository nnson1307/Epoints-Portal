<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 10:17
 */

namespace Modules\Admin\Repositories\ProductTag;


use Modules\Admin\Models\ProductTagTable;

class ProductTagRepo implements ProductTagRepoInterface
{
    protected $tag;

    public function __construct(
        ProductTagTable $tag
    ) {
        $this->tag = $tag;
    }

    /**
     * Thêm tag
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            //Insert tag
            $data = [
                'name' => $input['tag_name'],
                'keyword' => str_slug($input['tag_name'])
            ];

            $tagId = $this->tag->add($data);

            return [
                'error' => false,
                'tag_id' => $tagId,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ];
        }

    }

    /**
     * Danh sách tag
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->tag->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Chỉnh sửa tag
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            //update tag
            $data = [
                'name' => $input['tag_name'],
                'keyword' => str_slug($input['tag_name'])
            ];
            $tagId = $this->tag->edit($data, $input['product_tag_id']);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ];
        }
    }

    /**
     * Lấy chi tiết tag
     *
     * @param $tagId
     * @return mixed
     */
    public function getDetail($tagId)
    {
        return $this->tag->getInfo($tagId);
    }

    /**
     * Xoá tag
     *
     * @param $tagId
     * @return array|mixed
     */
    public function deleteTag($tagId)
    {
        try {
            //delete tag
            $this->tag->deleteTag($tagId);

            return [
                'error' => false,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá thất bại')
            ];
        }
    }
}