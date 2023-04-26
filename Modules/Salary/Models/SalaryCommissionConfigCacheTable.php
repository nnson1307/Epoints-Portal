<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class SalaryCommissionConfigCacheTable extends Model
{

    protected $table = "salary_commission_config_cache";
    protected $primaryKey = "salary_commission_config_cache_id";
}