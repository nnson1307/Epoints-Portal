<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ManageProjectPhareTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_project_phase';
    protected $primaryKey = 'manage_project_phase_id';
    protected $fillable = [
        'manage_project_phase_id',
        'manage_project_id',
        'name',
        'date_start',
        'date_end',
        'pic',
        'is_deleted',
        'status',
        'is_default',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    const IS_DELETE = 0;
    const IS_DEFAULT = 0;

    /**
     * lấy tất cả trong danh sách
     * @return mixed
     */
    public function getAllGroup(){
        $oSelect = $this
            ->where('is_deleted',0)
            ->orderBy('manage_project_phase_id','desc')
            ->get();

        return $oSelect;
    }

    /**
     * lấy tất cả trong danh sách
     * @return mixed
     */
    public function getAllByCode($code){
        $oSelect = $this
            ->where('manage_phase_group_code',$code)
            ->where('is_deleted',0)
            ->orderBy('manage_project_phase_id','asc')
            ->get();

        return $oSelect;
    }

    /**
     * Lấy danh sách giai đoạn theo dự án
     * @param $projectId
     * @return mixed
     */
    public function getAllPhareByProject($projectId){
        return $this
            ->where('manage_project_id',$projectId)
            ->where('is_deleted',self::IS_DELETE)
//            ->where('is_default',self::IS_DEFAULT)
            ->orderBy('manage_project_phase_id','ASC')
            ->get();
    }

    public function getDetail($manage_project_phase_id){
        return $this
            ->select(
                $this->table.'.*',
                'manage_project.manage_project_name'
            )
            ->join('manage_project','manage_project.manage_project_id',$this->table.'.manage_project_id')
            ->where('manage_project_phase_id',$manage_project_phase_id)
            ->first();
    }

    public function deletePhase($manage_project_phase_id){
        return $this
            ->where('manage_project_phase_id',$manage_project_phase_id)
            ->update(['is_deleted' => 1]);
    }

    /**
     * Cập nhật phase
     * @param $data
     * @param $manage_project_phase_id
     * @return mixed
     */
    public function updatePhase($data,$manage_project_phase_id){
        return $this
            ->where('manage_project_phase_id',$manage_project_phase_id)
            ->update($data);
    }

    public function getDefault($manage_project_id){
        return $this
            ->where('manage_project_id',$manage_project_id)
//            ->where('is_default',1)
            ->first();
    }

    /**
     * Job
     * Lấy danh sách mặc định
     */
    public function getListDefault(){
        return $this
//            ->where('is_default',1)
            ->groupBy('manage_project_id')
            ->get();
    }

    /**
     * Job
     * Thêm phase
     * @param $data
     * @return mixed
     */
    public function addPhase($data){
        return $this
            ->insert($data);
    }

    /**
     * Xóa theo nhóm
     * @param $manage_phase_group_code
     * @return mixed
     */
    public function removeTemplate($manage_phase_group_code){
        return $this
            ->where('manage_phase_group_code',$manage_phase_group_code)
            ->delete();
    }

}
