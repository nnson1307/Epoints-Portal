<?php

namespace Modules\Salary\Jobs;

use Modules\Salary\Repositories\Salary\SalaryInterface;

/**
 * Class SalaryJob
 * @package Modules\Salary\Jobs
 * @author VuND
 * @since 02/12/2021
 */
class SalaryJob extends BaseJob
{
    public $queue = 'salary';
    public $tries = 1;

    protected $id;

    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     * @param SalaryInterface $salary
     */
    public function handle(SalaryInterface $salary)
    {
        try {
            // Xử lý kiểm tra giao dịch
            $salary->createSalary($this->id);
        }
        catch (\Exception $ex) {
            // bỏ qua
            echo $ex->getMessage();
        }
    }
}