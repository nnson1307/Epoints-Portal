<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KpiNoteDetailTable
 * @author HaoNMN
 * @since Jul 2022
 */
class KpiNoteDetailTable extends Model
{
    protected $table    = 'kpi_note_detail';
    protected $primaryKey = 'kpi_note_detail_id';
    protected $fillable = [
        'kpi_note_detail_id',
        'kpi_note_id',
        'staff_id',
        'kpi_criteria_id',
        'priority',
        'kpi_value',
        'created_at',
        'updated_at'
    ];

    const IS_CLOSE = "D";
    const IS_ACTIVE = "A";
    const NOT_DELETED = 0;

    /**
     * Lấy chi tiết phiếu giao kpi theo tháng
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    public function getKpiByMonth($month, $year)
    {
        return $this
            ->select(
                "{$this->table}.kpi_note_id",
                "{$this->table}.kpi_note_detail_id",
                "{$this->table}.staff_id",
                "{$this->table}.kpi_criteria_id",
                "{$this->table}.priority",
                "{$this->table}.kpi_value",
                "nt.kpi_note_name",
                "nt.effect_year",
                "nt.effect_month",
                "nt.branch_id",
                "nt.department_id",
                "nt.team_id",
                "cr.kpi_criteria_code",
                "cr.kpi_criteria_unit_id",
                "cr.kpi_criteria_type"
            )
            ->join("kpi_note as nt", "nt.kpi_note_id", "=", "{$this->table}.kpi_note_id")
            ->join("kpi_criteria as cr", "cr.kpi_criteria_id", "=", "{$this->table}.kpi_criteria_id")
            ->where("nt.status", self::IS_ACTIVE)
            ->where("nt.effect_year", $year)
            ->where("nt.effect_month", $month)
            ->where("nt.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy danh sách chi tiết theo id phiếu giao
     * @param $id
     */
    public function listById($id)
    {
        return $this->select(
                        "{$this->table}.kpi_note_detail_id",
                        "{$this->table}.staff_id",
                        's.full_name',
                        "{$this->table}.kpi_criteria_id",
                        'kc.kpi_criteria_name',
                        'kc.kpi_criteria_trend',
                        'kc.is_blocked',
                        'kc.is_customize',
                        'kcu.kpi_criteria_unit_id',
                        'kcu.unit_name',
                        "{$this->table}.priority",
                        "{$this->table}.kpi_value"
                    )
                    ->leftJoin('kpi_criteria as kc', 'kc.kpi_criteria_id', '=', "{$this->table}.kpi_criteria_id")
                    ->leftJoin('kpi_criteria_unit as kcu', 'kcu.kpi_criteria_unit_id', '=', 'kc.kpi_criteria_unit_id')
                    ->leftJoin('staffs as s', 's.staff_id', '=', "{$this->table}.staff_id")
                    ->where("{$this->table}.kpi_note_id", $id)
                    ->get()
                    ->toArray();
    }

    /**
     * Thêm chi tiết phiếu giao
     * @param $data
     */
    public function add($data)
    {
        return $this->insert($data);
    }

    /**
     * Chỉnh sửa chi tiết phiếu giao
     * @param $data
     */
    public function updateData($param, $data)
    {
        return $this->updateOrCreate([
            "{$this->table}.kpi_note_id"     => $param['kpi_note_id'], 
            "{$this->table}.staff_id"        => $param['staff_id'], 
            "{$this->table}.kpi_criteria_id" => $param['kpi_criteria_id']
        ], $data);
    }

    /**
     * Lấy danh sách tiêu chí theo tháng và năm
     */
    public function getListCriteria($arrMonth,$year){
        $oSelect = $this
            ->join('kpi_note','kpi_note.kpi_note_id',$this->table.'.kpi_note_id')
            ->join('kpi_criteria','kpi_criteria.kpi_criteria_id',$this->table.'.kpi_criteria_id')
            ->where('kpi_note.effect_year',$year)
            ->where('kpi_note.is_deleted',0)
            ->orderBy('kpi_note.effect_month')
            ->groupBy($this->table.'.kpi_criteria_id');

        if (count($arrMonth) != 0){
            $oSelect = $oSelect->whereIn('kpi_note.effect_month',$arrMonth);
        }

        return $oSelect->get();
    }

    /**
     * Lấy kpi đã chốt
     *
     * @param $noteType
     * @param $noteTypeId
     * @param $month
     * @param $year
     * @return mixed
     */
    public function getKpiClosing($noteType, $noteTypeId, $month, $year)
    {
        $ds = $this
            ->select(
                "{$this->table}.kpi_note_detail_id",
                "n.kpi_note_name",
                "{$this->table}.kpi_criteria_id",
                "cr.kpi_criteria_name",
                "{$this->table}.kpi_value",
                "{$this->table}.staff_id",
                "n.team_id",
                "n.branch_id",
                "n.department_id",
                "n.kpi_note_type",
                "n.status",
                "c.calculate_kpi_total_id",
                "c.original_total_percent",
                "c.weighted_total_percent",
                "c.total",
                "{$this->table}.priority",
                "n.effect_month",
                "n.effect_year"
            )
            ->join("kpi_note as n", "n.kpi_note_id", "=", "{$this->table}.kpi_note_id")
            ->join("calculate_kpi_total as c", "c.kpi_note_detail_id", "=", "{$this->table}.kpi_note_detail_id")
            ->join("kpi_criteria as cr", "cr.kpi_criteria_id", "=", "{$this->table}.kpi_criteria_id")
            ->where("n.kpi_note_type", $noteType)
            ->where("n.status", self::IS_CLOSE)
            ->where("n.effect_month", $month)
            ->where("n.effect_year", $year);

        //Lấy theo giá trị của (cá nhân - nhóm - phòng ban - chi nhánh)
        if ($noteType != null) {
            switch ($noteType) {
                case 'S':
                    $ds->where("{$this->table}.staff_id", $noteTypeId);
                    break;
                case 'T':
                    $ds->where("n.team_id", $noteTypeId);
                    break;
                case 'B':
                    $ds->where("n.branch_id", $noteTypeId);
                    break;
                case 'D':
                    $ds->where("n.department_id", $noteTypeId);
                    break;
            }
        }

        return $ds->get();
    }
}