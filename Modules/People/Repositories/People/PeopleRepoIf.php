<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\People\Repositories\People;


interface PeopleRepoIf
{
    /**
     * Danh sách nhóm đối tượng có phân trang
     *
     * @param array $param
     * @return mixed
     */
    public function objectGroupPaginate(array $param = []);

    /**
     * Lấy 1 nhóm đối tượng
     *
     * @param array $param
     * @return mixed
     */
    public function objectGroup(array $param = []);

    /**
     * Thêm nhóm đối tượng
     *
     * @param array $param
     * @return mixed
     */
    public function objectGroupAdd(array $param = []);

    /**
     * Sửa nhóm đối tượng
     *
     * @param array $param
     * @return mixed
     */
    public function objectGroupEdit(array $param = []);

    /**
     * Xóa nhóm đối tượng
     *
     * @param array $param
     * @return mixed
     */
    public function objectGroupDelete(array $param = []);

    /**
     * Import excel
     *
     * @param $file
     * @return mixed
     */
    public function importExcel($file);

    /**
     * Export excel file error
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelError($input);

    /**
     * Chọn công dân
     *
     * @param $input
     * @return mixed
     */
    public function choosePeople($input);

    /**
     * Bỏ chọn công dân
     *
     * @param $input
     * @return mixed
     */
    public function unChoosePeople($input);

    public function people(array $param = []);

}