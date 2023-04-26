<?php

namespace Modules\Salary\Repositories\SalaryCommissionConfig;

interface SalaryCommissionConfigInterface
{

    /**
     * Get Salary Commission Config list
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    
    /**
     * Delete Salary Commission Config
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Salary Commission Config
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Salary Commission Config
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
}