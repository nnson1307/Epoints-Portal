<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class SalaryFileTable extends Model
{

    protected $table = "salary_file";
    protected $primaryKey = "salary_file_id";
}