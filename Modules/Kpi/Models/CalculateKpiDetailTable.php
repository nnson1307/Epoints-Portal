<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/07/2022
 * Time: 17:28
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;

class CalculateKpiDetailTable extends Model
{
    protected $table = "calculate_kpi_detail";
    protected $primaryKey = "calculate_kpi_detail_id";
}