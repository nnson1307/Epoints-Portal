<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ManagerWork\Repositories\ManageStatus;


interface ManageStatusRepositoryInterface
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
    public function getName();

    /**
     * Get color
     *
     * @param null
     */
    public function getColorList();

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

    /*
    * check exist
    */
    public function checkExist($name = '',$id = '');

    /**
     * lấy danh sách trạng thái cập nhật
     * @param $workId
     * @return mixed
     */
    public function getListStatus($workDetail);

}