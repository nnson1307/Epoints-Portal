<?php


namespace Modules\StaffSalary\Repositories\SalaryAllowance;


interface SalaryAllowanceRepoInterface
{
    public function getList();

    public function getDetail($id);
    
    public function add($input);

    public function edit($input, $id);
}