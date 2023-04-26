<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/09/2021
 * Time: 14:56
 */

namespace Modules\Contract\Repositories\ContractReceipt;


interface ContractReceiptRepoInterface
{
    /**
     * Lấy danh sách đợt thu
     *
     * @param array $filter
     * @return mixed
     */
    public function list(array $filter = []);

    /**
     * Lấy dữ liệu view tạo
     *
     * @param $input
     * @return mixed
     */
    public function getDataCreate($input);

    /**
     * Thêm đợt thu
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy dữ liệu view chỉnh sửa
     *
     * @param $input
     * @return mixed
     */
    public function getDataEdit($input);

    /**
     * Chỉnh sửa đợt thu
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá đợt thu
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}