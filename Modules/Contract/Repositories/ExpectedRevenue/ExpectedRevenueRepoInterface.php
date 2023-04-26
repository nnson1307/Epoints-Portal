<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2021
 * Time: 16:32
 */

namespace Modules\Contract\Repositories\ExpectedRevenue;


interface ExpectedRevenueRepoInterface
{
    /**
     * Lấy data view tạo dự kiến thu - chi
     *
     * @param $input
     * @return mixed
     */
    public function getDataViewCreate($input);

    /**
     * Thêm dự kiến thu - chi
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy danh sách thu - chi
     *
     * @param array $filter
     * @return mixed
     */
    public function listRevenue(array $filter = []);

    /**
     * Lấy data view chỉnh sửa dự kiến thu - chi
     *
     * @param $input
     * @return mixed
     */
    public function getDataViewEdit($input);

    /**
     * Chỉnh sửa dự kiến thu - chi
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá dự kiến thu - chi
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}