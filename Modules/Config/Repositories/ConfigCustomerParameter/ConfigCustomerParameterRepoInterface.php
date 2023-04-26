<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/11/2021
 * Time: 14:07
 */

namespace Modules\Config\Repositories\ConfigCustomerParameter;


interface ConfigCustomerParameterRepoInterface
{
    /**
     * Danh sách tham số
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Thêm tham số
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy data view edit
     *
     * @param $customerParameterId
     * @return mixed
     */
    public function getDataEdit($customerParameterId);

    /**
     * Chỉnh sửa tham số
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá tham số
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}