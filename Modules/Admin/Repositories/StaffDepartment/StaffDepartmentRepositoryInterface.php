<?php

namespace Modules\Admin\Repositories\StaffDepartment;
/**
 * Staff department repository interface
 *
 * @author ledangsinh
 * @since march 17, 2018
 */

interface StaffDepartmentRepositoryInterface
{
    /**
     * Get staff department list
     *
     * @param array $filters
     */
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
}