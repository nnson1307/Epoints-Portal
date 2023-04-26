<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/07/2022
 * Time: 14:01
 */

namespace Modules\Team\Repositories\Company;


interface CompanyRepoInterface
{
    /**
     * Danh sách công ty
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Thêm công ty
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $id
     * @return mixed
     */
    public function getDataEdit($id);

    /**
     * Chỉnh sửa công ty
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá công ty
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Chỉnh sửa trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);
}