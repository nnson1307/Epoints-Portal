<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KpiNoteTable
 * @author HaoNMN
 * @since Jul 2022
 */
class KpiNoteTable extends Model
{
    protected $table      = 'kpi_note';
    protected $primaryKey = 'kpi_note_id';
    protected $fillable   = [
        'kpi_note_id',
        'kpi_note_name',
        'effect_year',
        'effect_month',
        'is_loop',
        'branch_id',
        'department_id',
        'team_id',
        'status',
        'is_deleted',
        'created_by',
        'created_at',
        'updated_at'
    ];


    /**
     * Danh sách các phiếu giao KPI
     * @return array
     */
    public function list() 
    {
        return $this->where("{$this->table}.is_deleted", 0)
                    ->get()
                    ->toArray();
    }

    /**
     * Cập nhật trạng thái phiếu giao
     * nếu type = 0, cập nhật trạng thái là đang áp dụng
     * nếu type = 1, cập nhật trạng thái là đã chốt
     */
    public function updateStatus($id, $type)
    {
        if ($type == 0) {
            $oSelect = $this->where("{$this->table}.kpi_note_id", $id)
                    ->update(["{$this->table}.status" => "A"]);
        } elseif ($type == 1) {
            $oSelect = $this->where("{$this->table}.kpi_note_id", $id)
                    ->update(["{$this->table}.status" => "D"]);
        }

        return $oSelect;
    }
}