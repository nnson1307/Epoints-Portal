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

class ManageProjectStatusConfigTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_project_status_config';
    protected $primaryKey = 'manage_project_status_config_id';

    protected $fillable = [
        'manage_project_status_config_id',
        'manage_project_status_group_config_id',
        'manage_project_status_config_title',
        'manage_project_status_id',
        'manage_project_color_code',
        'position',
        'is_edit',
        'is_active',
        'is_deleted',
        'is_default',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    /**
     * Lấy danh sách trạng thái cấu hình
     */
    public function getListStatusConfig(){
        return $this
            ->select(
                $this->table.'.manage_project_status_group_config_id',
                'manage_project_status_group_config.manage_project_status_group_config_title',
                "manage_project_status_group_config.note_vi",
                "manage_project_status_group_config.note_en",
                $this->table.'.manage_project_status_id',
                $this->table.'.manage_project_status_config_id',
                $this->table.'.manage_project_status_config_title',
                $this->table.'.manage_project_color_code',
                $this->table.'.position',
                $this->table.'.is_edit',
                $this->table.'.is_deleted',
                $this->table.'.is_active',
                $this->table.'.is_default'
            )
            ->leftJoin('manage_project_status_group_config','manage_project_status_group_config.manage_project_status_group_config_id',$this->table.'.manage_project_status_group_config_id')
            ->orderBy('manage_project_status_group_config.position','ASC')
            ->orderBy($this->table.'.position','ASC')
            ->get();
    }

    /**
     * Tạo cấu hình trạng thái
     * @param $data
     * @return mixed
     */
    public function createdConfig($data){
        return $this->insertGetId($data);
    }

    /**
     * Xoá cấu hình theo nhóm
     */
    public function deleteConfig(){
        return $this->whereNotNull('manage_project_status_config_id')->delete();
    }

    /**
     * lấy vị trí lớn nhất
     * @param $groupId
     */
    public function getPosition($groupId){
        return $this
            ->select(
                'position'
            )
            ->where('manage_project_status_group_config_id',$groupId)->orderBy('position','DESC')->first();
    }


    /**
     * Lấy chi tiết trạng thái cấu hình
     */
    public function getDetailStatusConfig($id){
        return $this
            ->select(
                $this->table.'.manage_project_status_group_config_id',
                'manage_project_status_group_config.manage_project_status_group_config_title',
                getValueByLang('manage_project_status_group_config.note_'),
                $this->table.'.manage_project_status_id',
                $this->table.'.manage_project_status_config_id',
                $this->table.'.manage_project_status_config_title',
                $this->table.'.manage_project_color_code',
                $this->table.'.position',
                $this->table.'.is_edit',
                $this->table.'.is_active',
                $this->table.'.is_deleted',
                $this->table.'.is_default'
            )
            ->leftJoin('manage_project_status_group_config','manage_project_status_group_config.manage_project_status_group_config_id',$this->table.'.manage_project_status_group_config_id')
            ->where($this->table.'.manage_project_status_config_id',$id)
            ->orderBy('manage_project_status_group_config.position','ASC')
            ->orderBy($this->table.'.position','ASC')
            ->first();
    }

    /**
     * lấy danh sách cấu hình theo trạng thái
     * @param $manage_project_status_id
     */
    public function getConfigByStatusId($manage_project_status_id){
        return $this
            ->select(
                'manage_project_status_group_config_id',
                'manage_project_status_id'
            )
            ->where('manage_project_status_id',$manage_project_status_id)
            ->get();
    }

    /**
     * Xoá cấu hình trạng thái theo trạng thái
     * @param $manage_project_status_id
     */
    public function deleteConfigByStatusId($manage_project_status_id){
        return $this
            ->where('manage_project_status_id',$manage_project_status_id)
            ->delete();
    }

    /**
     * Cập nhật cấu hình
     * @param $data
     * @param $manage_project_status_config_id
     */
    public function updateConfig($data,$manage_project_status_config_id){
        return $this
            ->where('manage_project_status_config_id',$manage_project_status_config_id)
            ->update($data);
    }

    /**
     * Check trạng thái hiện tại có phải là trạng thái next step
     * @param $manage_project_status_config_id
     */
    public function checkStatusNextStep($manage_project_status_config_id){
        return $this
            ->join('manage_project_status_config_map','manage_project_status_config_map.manage_project_status_id',$this->table.'.manage_project_status_id')
            ->where($this->table.'.manage_project_status_config_id',$manage_project_status_config_id)
            ->get();
    }

}