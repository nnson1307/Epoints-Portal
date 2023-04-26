<?php

namespace Modules\CustomerLead\Repositories\Tag;

use Modules\CustomerLead\Models\TagTable;

class TagRepo implements TagRepoInterface
{
    protected $tag;

    public function __construct(
        TagTable $tag
    )
    {
        $this->tag = $tag;
    }

    public function list(array $filters = [])
    {
        $list = $this->tag->getList($filters);

        return [
            'list' => $list
        ];
    }

    public function store($input)
    {
        try {
            //Insert tag
            $data = [
                'name' => $input,
                'keyword' => str_slug($input)
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
                'message' => __('Thêm mới thất bại')
            ];
        }
    }

    public function update($input)
    {
        try {
            //update tag
            $data = [
                'name' => $input['tag_name'],
                'keyword' => str_slug($input['tag_name'])
            ];
            $tagId = $this->tag->edit($data, $input['tag_id']);

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

    public function getDetail($tagId)
    {
        return $this->tag->getInfo($tagId);
    }

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