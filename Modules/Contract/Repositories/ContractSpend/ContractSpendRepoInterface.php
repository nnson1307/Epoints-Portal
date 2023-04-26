<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 17:13
 */

namespace Modules\Contract\Repositories\ContractSpend;


interface ContractSpendRepoInterface
{
    /**
     * Lấy danh sách đợt chi
     *
     * @param array $filter
     * @return mixed
     */
    public function list(array $filter = []);

    /**
     * Lấy data view tạo
     *
     * @param $input
     * @return mixed
     */
    public function getDataCreate($input);

    /**
     * Thêm đợt chi
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $input
     * @return mixed
     */
    public function getDataEdit($input);

    /**
     * Chỉnh sửa đợt chi
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá đợt chi
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}