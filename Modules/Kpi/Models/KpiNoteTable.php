<?php

namespace Modules\Kpi\Models;

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
        'created_by',
        'created_at',
        'updated_at'
    ];

    /**
     * Lấy danh sách phiếu giao
     */
    public function list($param)
    {
        $oSelect = $this->where("{$this->table}.is_deleted", 0);

        if (isset($param['kpi_note_name'])) {
            $oSelect->where("{$this->table}.kpi_note_name", 'LIKE', '%'.$param['kpi_note_name'].'%');
        }

        if (isset($param['effect_month'])) {
            $oSelect->where("{$this->table}.effect_month", $param['effect_month']);
        }

        if (isset($param['department_id'])) {
            $oSelect->where("{$this->table}.department_id", $param['department_id']);
        }

        if (isset($param['status'])) {
            $oSelect->where("{$this->table}.status", $param['status']);
        }

        return $oSelect->orderBy("{$this->table}.created_at", "DESC")->paginate(10);
    }

    /**
     * Lưu phiếu giao
     * @param $data
     */
    public function add($data) 
    {
        return $this->insertGetId($data);
    }

    /**
     * Xóa phiếu giao
     * @param $id
     */
    public function remove($id) 
    {
        return $this->where("{$this->table}.kpi_note_id", $id)
                    ->update(["{$this->table}.is_deleted" => 1]);
    }

    /**
     * Chi tiết phiếu giao
     * @param $id
     */
    public function detail($id)
    {
        return $this->select(
                        "{$this->table}.kpi_note_id",
                        "{$this->table}.kpi_note_name",
                        "{$this->table}.effect_year",
                        "{$this->table}.effect_month",
                        "{$this->table}.is_loop",
                        "{$this->table}.branch_id",
                        "{$this->table}.department_id",
                        "{$this->table}.team_id",
                        "{$this->table}.kpi_note_type",
                        "{$this->table}.status"
                    )
                    ->where("{$this->table}.kpi_note_id", $id)
                    ->first()
                    ->toArray();
    }

    /**
     * Lưu chỉnh sửa phiếu giao
     * @param $data
     */
    public function updateData($id, $data)
    {   
        return $this->where("{$this->table}.kpi_note_id", $id)
                    ->update($data);
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

    /**
     * Kiểm tra phiếu giao có tồn tại trong hệ thống chưa
     * @param $branch_id, $department_id, $team_id
     * @return mixed
     */
    public function checkKpiNoteExist($month, $data)
    {

        $oSelect = $this->leftJoin('kpi_note_detail as nd', 'nd.kpi_note_id', '=', "{$this->table}.kpi_note_id")
                        ->where('kpi_note_type', $data['kpi_note_type'])
                        ->where('effect_year', $data['effect_year'])
                        ->where('branch_id', $data['branch_id'])
                        ->where('is_deleted', 0);
        
        if (! empty($month)) {
            $oSelect->where('effect_month', $month);
        } else {
            $oSelect->where('effect_month', $data['effect_month']);
        }
        
        if (isset($data['department_id'])) {
            $oSelect->where('department_id', $data['department_id']);
        }

        if (isset($data['team_id'])) {
            $oSelect->where('team_id', $data['team_id']);
        }

        if (isset($data['staff_id'])) {
            $oSelect->whereIn('nd.staff_id', $data['staff_id']);
        }

        return $oSelect->first();
    }
}
