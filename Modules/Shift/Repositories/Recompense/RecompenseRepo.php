<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/10/2022
 * Time: 15:21
 */

namespace Modules\Shift\Repositories\Recompense;


use Modules\Shift\Models\RecompenseTable;

class RecompenseRepo implements RecompenseRepoInterface
{
    protected $recompense;

    public function __construct(
        RecompenseTable $recompense
    ) {
        $this->recompense = $recompense;
    }

    /**
     * DS thưởng phạt
     *
     * @param array $filter
     * @return array|mixed
     */
    public function getList($filter = [])
    {
        $list = $this->recompense->getList($filter);

        return [
            'list' => $list
        ];
    }

    /**
     * Thêm thưởng phạt
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            //Thêm thưởng - phạt
            $this->recompense->add([
                'type' => $input['type'],
                'recompense_name' => $input['recompense_name'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'error' => false,
                'message' => __('Thêm thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm thất bại')
            ];
        }
    }

    /**
     * Chỉnh sửa thưởng - phạt
     *
     * @param $recompenseId
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataEdit($recompenseId)
    {
        $mRecompense = app()->get(RecompenseTable::class);

        //Lấy thông tin thưởng phạt
        $info = $mRecompense->getInfo($recompenseId);

        return [
            'item' => $info
        ];
    }

    /**
     * Chỉnh sửa thưởng phạt
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            //Thêm thưởng - phạt
            $this->recompense->edit([
                'type' => $input['type'],
                'recompense_name' => $input['recompense_name'],
                'is_actived' => $input['is_actived'],
                'updated_by' => Auth()->id()
            ], $input['recompense_id']);

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
     * Xoá thưởng phạt
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            //Thêm thưởng - phạt
            $this->recompense->edit([
                'is_deleted' => 1
            ], $input['recompense_id']);

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

    /**
     * Cập nhật nhanh trạng thái
     *
     * @param $input
     * @return array|mixed
     */
    public function changeStatus($input)
    {
        try {
            //Thêm thưởng - phạt
            $this->recompense->edit([
                'is_actived' => $input['is_actived']
            ], $input['recompense_id']);

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
}