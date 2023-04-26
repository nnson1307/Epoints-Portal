<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KpiCriteriaUnitTable
 * @author HaoNMN
 * @since Jul 2022
 */
class KpiCriteriaUnitTable extends Model
{
    protected $table      = 'kpi_criteria_unit';
    protected $primaryKey = 'kpi_criteria_unit_id';
    protected $fillable = [
        'kpi_criteria_unit_id',
        'unit_name',
        'is_customize'
    ];


    /**
     * Lấy danh sách đơn vị tính
     * @return mixed 
     */
    public function listUnit()
    {
        return $this->get();
    }
}
