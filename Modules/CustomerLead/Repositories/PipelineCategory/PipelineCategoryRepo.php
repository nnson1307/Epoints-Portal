<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/23/2020
 * Time: 10:22 AM
 */

namespace Modules\CustomerLead\Repositories\PipelineCategory;


use Modules\CustomerLead\Models\PipelineCategoryTable;

class PipelineCategoryRepo implements PipelineCategoryRepoInterface
{
    protected $pipelineCategory;

    public function __construct(
        PipelineCategoryTable $pipelineCategory
    )
    {
        $this->pipelineCategory = $pipelineCategory;
    }

    /**
     * Danh sách pipeline category
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->pipelineCategory->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Thêm pipeline category
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            //Insert pipeline category
            $categoryId = $this->pipelineCategory->add($input);
            //Update category code
            $this->pipelineCategory->edit([
                'pipeline_category_code' => 'PIPELINE_CATEGORY_' . date('dmY') . sprintf("%02d", $categoryId)
            ], $categoryId);

            return [
                'error' => false,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại')
            ];
        }
    }

    /**
     * Data view chỉnh sửa pipeline category
     *
     * @param $categoryId
     * @return array|mixed
     */
    public function dataEdit($categoryId)
    {
        $getInfo = $this->pipelineCategory->getInfo($categoryId);

        return [
            'item' => $getInfo
        ];
    }

    /**
     * Chỉnh sửa pipeline category
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            //Update pipeline category
            $this->pipelineCategory->edit($input, $input['pipeline_category_id']);

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
     * Thay đổi trạng thái pipeline category
     *
     * @param $input
     * @return array|mixed
     */
    public function changeStatus($input)
    {
        try {
            //Update pipeline category
            $this->pipelineCategory->edit($input, $input['pipeline_category_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }

    /**
     * Xóa pipeline category
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            //Remove pipeline category
            $this->pipelineCategory->edit([
                'is_deleted' => 1
            ], $input['pipeline_category_id']);

            return [
                'error' => false,
                'message' => __('Xóa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xóa thất bại')
            ];
        }
    }
}