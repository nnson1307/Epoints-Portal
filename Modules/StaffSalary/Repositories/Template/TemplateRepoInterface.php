<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/10/2022
 * Time: 14:23
 */

namespace Modules\StaffSalary\Repositories\Template;


interface TemplateRepoInterface
{
    /**
     * Lấy data danh sách mẫu lương
     *
     * @param $filter
     * @return mixed
     */
    public function getList($filter = []);

    /**
     * Lấy danh sách option mẫu lương
     *
     * @param array $filter
     * @return array|mixed
     */
    public function getOption();

    /**
     * Lấy data view tạo
     *
     * @return mixed
     */
    public function getDataCreate();

    /**
     * Lấy data view thêm phụ cấp
     *
     * @return mixed
     */
    public function getDataPopCreateAllowance();

    /**
     * Thêm mẫu lương
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $templateId
     * @return mixed
     */
    public function getDataEdit($templateId);

    /**
     * Chỉnh sửa mẫu lương
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    /**
     * Xoá mẫu lương
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}