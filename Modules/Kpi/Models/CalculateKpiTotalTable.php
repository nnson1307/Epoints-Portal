<?php

namespace Modules\Kpi\Models;

use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalculateKpiTotalTable extends Model
{
    protected $table      = 'calculate_kpi_total';
    protected $primaryKey = 'calculate_kpi_total_id';
    protected $fillable   = [
        'calculate_kpi_total_id',
        'kpi_note_detail_id',
        'kpi_criteria_id',
        'branch_id',
        'department_id',
        'staff_id',
        'team_id',
        'day',
        'week',
        'month',
        'year',
        'full_time',
        'total',
        'kpi_criteria_unit_id',
        'created_at',
        'updated_at',
        'original_total_percent',
        'weighted_total_percent'
    ];

    public function getTotalByStaffInMonth($param)
    {
        return $this->select("{$this->table}.total")
                    ->where("{$this->table}.kpi_criteria_id", $param['kpi_criteria_id'])
                    ->where("{$this->table}.staff_id", $param['staff_id'])
                    ->where("{$this->table}.kpi_note_detail_id", $param['kpi_note_detail_id'])
                    ->where("{$this->table}.month", $param['effect_month'])
                    ->where("{$this->table}.year", $param['effect_year'])
                    ->first();
    }

    public function getTotalByGroupInMonth($param)
    {
        return $this->select("{$this->table}.total")
                    ->where("{$this->table}.kpi_criteria_id", $param['kpi_criteria_id'])
                    ->where("{$this->table}.branch_id", $param['branch_id'])
                    ->where("{$this->table}.department_id", $param['department_id'])
                    ->where("{$this->table}.team_id", $param['team_id'])
                    ->where("{$this->table}.month", $param['effect_month'])
                    ->where("{$this->table}.year", $param['effect_year'])
                    ->first();
    }

    /**
     * Thêm bảng tính kpi
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->calculate_kpi_total_id;
    }

    /**
     * Tạo mới hoặc update bảng tính kpi
     * 
     * @param $data
     */
    public function addOrUpdate($data)
    {
        return $this->updateOrInsert(
            [
                'kpi_note_detail_id'     => $data['kpi_note_detail_id'], 
                'kpi_criteria_id'        => $data['kpi_criteria_id'], 
                'branch_id'              => $data['branch_id'],
                'department_id'          => $data['department_id'],
                'team_id'                => $data['team_id'],
                'staff_id'               => $data['staff_id'],
                'month'                  => $data['month'],
                'year'                   => $data['year'],
                'kpi_criteria_unit_id'   => $data['kpi_criteria_unit_id']
            ],
            [
                'total'                  => $data['total'],
                'original_total_percent' => $data['percent'],
                'weighted_total_percent' => $data['percentWithPriority']
            ]
        );
    }

    /**
     * Chỉnh sửa bảng tính kpi
     *
     * @param array $data
     * @param $calculateId
     * @return mixed
     */
    public function edit(array $data, $calculateId)
    {
        return $this->where("calculate_kpi_total_id", $calculateId)->update($data);
    }

    /**
     * Lấy thông tin bảng tính kpi của nhân viên theo tiêu chí
     *
     * @param $criteriaId
     * @param $branchId
     * @param $departmentId
     * @param $teamId
     * @param $staffId
     * @param $day
     * @param $year
     * @return mixed
     */
    public function getCalculateKpiByDate($criteriaId, $branchId, $departmentId, $teamId, $staffId, $day, $year)
    {
        return $this
            ->select(
                "{$this->table}.calculate_kpi_id",
                "{$this->table}.kpi_note_detail_id",
                "{$this->table}.kpi_criteria_id",
                "{$this->table}.branch_id",
                "{$this->table}.department_id",
                "{$this->table}.staff_id",
                "{$this->table}.team_id",
                "{$this->table}.day",
                "{$this->table}.week",
                "{$this->table}.month",
                "{$this->table}.year",
                "{$this->table}.total",
                "{$this->table}.kpi_criteria_unit_id"
            )
            ->where("{$this->table}.kpi_criteria_id", $criteriaId)
            ->where("{$this->table}.branch_id", $branchId)
            ->where("{$this->table}.department_id", $departmentId)
            ->where("{$this->table}.team_id", $teamId)
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.day", $day)
            ->where("{$this->table}.year", $year)
            ->first();
    }

    /**
     * Lấy tổng số KPI trong tháng hoặc tháng trước
     * @param $typeDate
     */
    public function getTotalKpi($month,$year,$filter = []){
        $oSelect = $this
            ->select(
                DB::raw("SUM(IF(kpi_criteria.kpi_criteria_trend = 1 , (kpi_note_detail.priority*({$this->table}.total/kpi_note_detail.kpi_value)) ,(kpi_note_detail.priority*(kpi_note_detail.kpi_value/{$this->table}.total)))) as total_kpi"),
                $this->table.'.branch_id',
                $this->table.'.department_id',
                $this->table.'.staff_id'
            )
            ->leftJoin('kpi_note_detail','kpi_note_detail.kpi_note_detail_id',$this->table.'.kpi_note_detail_id')
            ->leftJoin('kpi_criteria','kpi_criteria.kpi_criteria_id',$this->table.'.kpi_criteria_id')
            ->where($this->table.'.month',$month)
            ->where($this->table.'.year',$year);

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where($this->table.'.staff_id',$filter['staff_id']);
        }


        return $oSelect->get();
    }

    /**
     * Lấy danh sách tiêu chí
     * @param $typeDate
     */
    public function getDataCriteria($month,$year,$filter = []){
        $oSelect = $this
            ->select(
                DB::raw("SUM({$this->table}.total) as total_kpi"),
                $this->table.'.branch_id',
                $this->table.'.department_id',
                $this->table.'.staff_id',
                'kpi_criteria_unit.unit_name',
                'kpi_note_detail.kpi_criteria_id',
                'kpi_note_detail.priority',
                'kpi_note_detail.kpi_value',
                'kpi_criteria.kpi_criteria_trend'
            )
            ->leftJoin('kpi_note_detail','kpi_note_detail.kpi_note_detail_id',$this->table.'.kpi_note_detail_id')
            ->leftJoin('kpi_criteria','kpi_criteria.kpi_criteria_id',$this->table.'.kpi_criteria_id')
            ->leftJoin('kpi_criteria_unit','kpi_criteria_unit.kpi_criteria_unit_id',$this->table.'.kpi_criteria_unit_id')
            ->where($this->table.'.month',$month)
            ->where($this->table.'.year',$year)
            ->groupBy($this->table.'.kpi_criteria_unit_id');

        if (isset($filter['branch_id'])){
            $oSelect = $oSelect->where($this->table.'.branch_id',$filter['branch_id']);
        }

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where($this->table.'.department_id',$filter['department_id']);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where($this->table.'.staff_id',$filter['staff_id']);
        }


        return $oSelect->get();
    }

    /**
     * Lấy tổng số KPI theo quý
     * @param $typeDate
     */
    public function getTotalKpiArrMonth($arrMonth = [],$year,$filter = []){


        $oSelect = $this
            ->select(
                DB::raw("SUM(IF(kpi_criteria.kpi_criteria_trend = 1 , (kpi_note_detail.priority*({$this->table}.total/kpi_note_detail.kpi_value)) ,(kpi_note_detail.priority*(kpi_note_detail.kpi_value/{$this->table}.total)))) as total_kpi"),
                $this->table.'.branch_id',
                $this->table.'.department_id',
                $this->table.'.staff_id',
                $this->table.'.month',
                'kpi_note_detail.kpi_criteria_id'
            )
            ->leftJoin('kpi_note_detail','kpi_note_detail.kpi_note_detail_id',$this->table.'.kpi_note_detail_id')
            ->leftJoin('kpi_criteria','kpi_criteria.kpi_criteria_id',$this->table.'.kpi_criteria_id')

            ->where($this->table.'.year',$year)
            ->groupBy($this->table.'.month');

        if (count($arrMonth) != 0){
            $oSelect = $oSelect->whereIn($this->table.'.month',$arrMonth);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where($this->table.'.staff_id',$filter['staff_id']);
        }

        return $oSelect->get();
    }

    /**
     * Lấy danh sách tiêu chí theo quý
     * @param $typeDate
     */
    public function getDataCriteriaArrMonth($arrMonth = [],$year,$filter = []){
        $oSelect = $this
            ->select(
                DB::raw("SUM({$this->table}.total) as total_kpi"),
                $this->table.'.branch_id',
                $this->table.'.department_id',
                $this->table.'.staff_id',
                $this->table.'.month',
                'kpi_criteria_unit.unit_name',
                'kpi_note_detail.kpi_criteria_id',
                'kpi_note_detail.priority',
                'kpi_note_detail.kpi_value',
                'kpi_criteria.kpi_criteria_trend',
                'kpi_note_detail.kpi_note_id'
            )
            ->leftJoin('kpi_note_detail','kpi_note_detail.kpi_note_detail_id',$this->table.'.kpi_note_detail_id')
            ->leftJoin('kpi_criteria','kpi_criteria.kpi_criteria_id',$this->table.'.kpi_criteria_id')
            ->leftJoin('kpi_criteria_unit','kpi_criteria_unit.kpi_criteria_unit_id',$this->table.'.kpi_criteria_unit_id')
            ->where($this->table.'.year',$year)
            ->groupBy($this->table.'.kpi_criteria_unit_id')
            ->get();

        if (count($arrMonth) != 0){
            $oSelect = $oSelect->whereIn($this->table.'.month',$arrMonth);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where($this->table.'.staff_id',$filter['staff_id']);
        }

        return $oSelect;
    }

    /**
     *
     * @param $filter
     */
    public function getListCriteria($filter = []){
        $oSelect = $this
            ->join('kpi_criteria','kpi_criteria.kpi_criteria_id',$this->table.'.kpi_criteria_id')
            ->join('departments','departments.department_id',$this->table.'.department_id')
            ->join('team','team.team_id',$this->table.'.team_id')
            ->select(
                $this->table.'.department_id',
                'departments.department_name',
                $this->table.'.team_id',
                'team.team_name',
                $this->table.'.kpi_criteria_id',
                $this->table.'.year',
                $this->table.'.month',
                $this->table.'.week',
                $this->table.'.day',
                $this->table.'.full_time',
                $this->table.'.total'
            );

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where($this->table.'.department_id',$filter['department_id']);
        }

        if (isset($filter['year'])){
            $oSelect = $oSelect->where($this->table.'.year',$filter['year']);
        }

        if (isset($filter['month'])){
            $oSelect = $oSelect->where($this->table.'.month',$filter['month']);
        }

        if (isset($filter['week'])){
            $oSelect = $oSelect->where($this->table.'.week',$filter['week']);
        }

        if (isset($filter['day'])){
            $oSelect = $oSelect->where($this->table.'.day',$filter['day']);
        }

        if (isset($filter['arrStartDate']) && isset($filter['arrEndDate'])) {
            $oSelect = $oSelect
                ->where($this->table.'.full_time','>=',$filter['arrStartDate'])
                ->where($this->table.'.full_time','<=',$filter['arrEndDate']);
        }

        return $oSelect->get();
    }
}
