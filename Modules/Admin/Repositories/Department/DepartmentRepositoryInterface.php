<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/24/2018
 * Time: 2:29 PM
 */

namespace Modules\Admin\Repositories\Department;


interface DepartmentRepositoryInterface
{

    public function list(array $filterts = []);

    /**
     * Add  staff department
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Edit staff department
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * Remove staff department
     * @param $id
     * @return number
     */
    public function remove($id);

    /**
     * Get item
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /*
     * getstaffDepartmentOption
     */
    public function getStaffDepartmentOption();

    /*
     * check unique department
     */
    public function check($name);

    /*
   * check unique department edit
   */
    public function checkEdit($id, $name);

    /*
  * test is deleted
  */
    public function testIsDeleted($name);

    /*
    * edit by department name
    */
    public function editByName($name);

    /**
     * Lấy data view tạo
     *
     * @return mixed
     */
    public function getDataCreate();

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $id
     * @return mixed
     */
    public function getDataEdit($id);

    /**
     * Chỉnh sửa phòng ban
     *
     * @param $input
     * @return mixed
     */
    public function update($input);
}