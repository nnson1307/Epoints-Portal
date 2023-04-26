<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ManagerProject\Repositories\Member;


interface MemberRepositoryInterface
{
    /**
     * Thêm thành Viên
     * @param $params
     * @return mixed
     */

    public function store($params);

    /**
     * Danh sách thành viên 
     * @param $params
     * @return mixed
     */

    public function getList($params);

    /**
     * Hiển thị thông tin chi tiết thành viên
     * @param $idMemberProject
     * @return mixed
     */

    public function show($idMemberProject);

    /**
     * Hiển thị thông tin cập nhật thành viên
     * @param $idMemberProject
     * @return mixed
     */

    public function edit($idMemberProject);

    /**
     * cập nhật thành viên
     * @param $params
     * @return mixed
     */

    public function update($params);

     /**
     * xoá thành viên
     * @param $idMemberProject
     * @return mixed
     */

    public function remove($params);

    /**
     * Hiển thị popup thêm thành viên
     * @param $data
     * @return mixed
     */
    public function showPopupAddStaff($data);
}
