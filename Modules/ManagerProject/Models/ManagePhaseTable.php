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

class ManagePhaseTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_phase';
    protected $primaryKey = 'manage_phase_id';

    /**
     * lấy tất cả trong danh sách
     * @return mixed
     */
    public function getAllGroup(){
        $oSelect = $this
            ->where('is_deleted',0)
            ->groupBy('manage_phase_group_code')
            ->orderBy('manage_phase_id','desc')
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
            ->orderBy('manage_phase_id','asc')
            ->get();

        return $oSelect;
    }

//    Thêm danh sách template
    public function insertSample($data){
        return $this->insert($data);
    }

    /**
     * Lấy chi tiết
     * @param $phaseId
     * @return mixed
     */
    public function getDetail($phaseId){
        return $this
            ->where('manage_phase_id',$phaseId)
            ->first();
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