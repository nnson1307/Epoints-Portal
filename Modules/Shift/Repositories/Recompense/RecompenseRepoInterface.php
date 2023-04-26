<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/10/2022
 * Time: 15:21
 */

namespace Modules\Shift\Repositories\Recompense;


interface RecompenseRepoInterface
{
    /**
     * Danh sách thưởng phạt
     *
     * @param array $filter
     * @return mixed
     */
    public function getList($filter = []);

    /**
     * Thêm thưởng phạt
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $recompenseId
     * @return mixed
     */
    public function getDataEdit($recompenseId);

    /**
     * Chỉnh sửa thưởng phạt
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá thưởng phạt
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Cập nhật nhanh trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);
}