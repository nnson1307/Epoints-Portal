<?php


namespace Modules\ManagerProject\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ManageProjectStaffTable extends Model
{
    use ListTableTrait;

    protected $table = "manage_project_staff";
    protected $primaryKey = "manage_project_staff_id";
    protected $fillable = [
        'manage_project_staff_id',
        'manage_project_id',
        'staff_id',
        'manage_project_role_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function getListCore(&$filters = [])
    {

        $oSelect = $this
            ->select(
                "{$this->table}.{$this->primaryKey}",
                "{$this->table}.manage_project_id",
                "st.*",
                "dpm.department_name",
                "mpr.manage_project_role_name"
            )
            ->where($this->table.".manage_project_id", $filters['project'])
            ->join("staffs as st", function ($q) {
                $q->on("st.staff_id", "{$this->table}.staff_id");
                $q->join("departments as dpm", "dpm.department_id", "st.department_id");
            })
            ->join("manage_project_role as mpr", "mpr.manage_project_role_id", "{$this->table}.manage_project_role_id");
        if (!empty($filters['department'])) {
            $oSelect->where("dpm.department_id", $filters['department']);
            unset($filters['department']);
        }
        if (!empty($filters['staff'])) {
            $oSelect->where("{$this->table}.staff_id", $filters['staff']);
            unset($filters['staff']);
        }
        if (!empty($filters['role'])) {
            $oSelect->where("{$this->table}.manage_project_role_id", $filters['role']);
            unset($filters['role']);
        }
        unset($filters['project']);

        return $oSelect;
    }

    /**
     * Chi tiết thành viên dự án
     * @param $idMemberProject
     * @return mixed
     */

    public function detail($idMemberProject)
    {
        return $this->select("{$this->table}.*", "st.full_name")
            ->where("{$this->table}.{$this->primaryKey}", $idMemberProject)
            ->join("staffs as st", "st.staff_id", "{$this->table}.staff_id")
            ->first();
    }

    /**
     * Lấy danh sách nhân viên theo id dự án
     * @param $idProject
     */
    public function getListStaffByProject($idProject){
        return $this
            ->where('manage_project_id',$idProject)
            ->get();
    }

    /**
     * Tìm thành viên
     * @param $idMemberProject
     */
    public function findStaff($idMemberProject){
        return $this
            ->join('manage_project_role','manage_project_role.manage_project_role_id',$this->table.'.manage_project_role_id')
            ->where($this->table.'.manage_project_staff_id',$idMemberProject)
            ->first();
    }

    /**
     * Lấy danh sách nhân viên quản trị theo dự án
     */
    public function getListAdmin($idProject,$manage_project_role_code = null,$param = []){
        $oSelect = $this
            ->join('manage_project_role','manage_project_role.manage_project_role_id',$this->table.'.manage_project_role_id')
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_project_id',$idProject);
            if ($manage_project_role_code != null){
                $oSelect = $oSelect->where('manage_project_role.manage_project_role_code',$manage_project_role_code);
            }

            if (isset($param['department_id']) && $param['department_id'] != 0){
                $oSelect = $oSelect->where('staffs.department_id',$param['department_id']);
            }

        return $oSelect->get();
    }

    public function insertStaff($data){
        return $this->insertGetId($data);
    }

    /**
     * Kiểm tra nhân viên có thuộc dự án
     * @param $staffId
     * @param $projectId
     * @return mixed
     */
    public function checkStaffProject($staffId,$projectId){
        return $this
            ->where('manage_project_id',$projectId)
            ->where('staff_id',$staffId)
            ->get();
    }

    public function getAllByProjectId($projectId)
    {
        return $this->select("{$this->table}.*", "st.full_name as staff_name")
            ->where("{$this->table}.manage_project_id", $projectId)
            ->join("staffs as st", "st.staff_id", "{$this->table}.staff_id")
            ->get();
    }


    public function deleteStaff($projectId, $staffId){
        $this->where( "{$this->table}.manage_project_id" , $projectId)
            ->where( "{$this->table}.staff_id" , $staffId)
            ->delete();
    }
}
