<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 10:58
 */

namespace Modules\Contract\Repositories\ContractFile;


interface ContractFileRepoInterface
{
    /**
     * Lấy danh sách file đính kèm
     *
     * @param array $filter
     * @return mixed
     */
    public function list(array $filter = []);

    /**
     * Thêm file HĐ
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
     * Chỉnh sửa file HĐ
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá file HĐ
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}