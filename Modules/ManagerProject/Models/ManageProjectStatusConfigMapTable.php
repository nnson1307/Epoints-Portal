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

class ManageProjectStatusConfigMapTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_project_status_config_map';
    protected $primaryKey = 'manage_project_status_config_map_id';

    protected $fillable = [
        'manage_project_status_config_map_id',
        'manage_project_status_config_id',
        'manage_project_status_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    /**
     * lấy danh sách trạng thái được map
     * @param $manage_project_status_config_id
     */
    public function getListMapStatus($manage_project_status_config_id){
        return $this
            ->select(
                'manage_project_status_config_map_id',
                'manage_project_status_config_id',
                'manage_project_status_id'
            )
            ->where('manage_project_status_config_id',$manage_project_status_config_id)
            ->get();
    }

    /**
     * Tạo map cấu hình trạng thái
     * @param $data
     * @return mixed
     */
    public function createdConfigMap($data){
        return $this->insert($data);
    }

    /**
     * Xoá cấu hình map
     */
    public function deleteConfigMap(){
        return $this->whereNotNull('manage_project_status_config_map_id')->delete();
    }

    public function deleteConfigMapByConfig($arrId){
        return $this->whereIn('manage_project_status_config_id',$arrId)->delete();
    }

//    Lấy danh sách trạng thái kế tiếp
    public function getListStatusByConfig($manage_project_status_id){
        return $this
            ->select(
                $this->table.'.manage_project_status_id'
            )
            ->join('manage_project_status_config','manage_project_status_config.manage_project_status_config_id',$this->table.'.manage_project_status_config_id')
            ->where('manage_project_status_config.manage_project_status_id',$manage_project_status_id)
            ->where('manage_project_status_config.is_active',1)
            ->get();
    }

    /**
     * Xóa trạng thái kế tiếp
     * @param $arrId
     * @return mixed
     */
    public function deleteStatusByIdStatus($manage_project_status_id){
        return $this->where('manage_project_status_id',$manage_project_status_id)->delete();
    }

}