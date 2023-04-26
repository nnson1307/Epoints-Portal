<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ManagerWork\Repositories\Project;


interface ProjectRepositoryInterface
{
    /**
     * Get queue list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Get all
     *
     * @param array $all
     */
    public function getAll(array $filters = []);
    /**
     * Get all
     *
     * @param array $all
     */
    public function getName();

    /**
     * Delete queue
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add queue
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update queue
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    public function testCode($code, $id);

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '');

    /**
     * Lấy tên tiền tố dự án ngẫu nhiên
     * @param $param
     * @return string
     */

    public function getNamePrefix($param);



    /**
     * Thêm dự án 
     * @param array $params
     * @return mixed
     */


    public function store($params);

    /**
     * Lấy record dự án
     * @param $id
     * @return mixed
     */

    public function getItemProject($id);

    /**
     * Cập nhật dự án 
     * @param array $params
     * @return mixed
     */

    public function update($params);

    /**
     * Danh sách hiển thị thông tin cấu hình danh sách dự án
     * @return array
     */

    public function getConfigListProject();

    /**
     * Cấu hình danh sách hiển thị và lọc dự án
     * @param array $params
     * @return mixed
     */

    public function configListProject($params);

    /**
     * Lấy thông tin dự án 
     * @param $idProject
     * @return mixed
     */

    public function getDetail($idProject);

    public function getDetailFix($idProject);
}
