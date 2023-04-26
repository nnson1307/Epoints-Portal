<?php

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Http\Request;
use MyCore\Models\Traits\ListTableTrait;

class FNBQrCodeTemplateTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_qr_code_template';
    protected $primaryKey = 'qr_code_template_id';
    protected $fillable
        = [
            'qr_code_template_id',
            'type',
            'qr_code',
            'qr_link',
            'qr_type',
            'apply_for',
            'apply_branch_id',
            'apply_arear_id',
            'apply_table_id',
            'expire_type',
            'expire_start',
            'expire_end',
            'status',
            'is_request_location',
            'location_lat',
            'location_lng',
            'location_radius',
            'is_request_wifi',
            'wifi_name',
            'wifi_ip',
            'template_frames_id',
            'template_font_id',
            'template_location',
            'template_content',
            'template_color',
            'template_logo',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];

    public function _getList(&$filters = []) {
        $oSelect = $this->getListFunction($filters);
        $filters = [];
        return $oSelect;
    }

    public function getListExport($filters = []){
        $oSelect = $this->getListFunction($filters);

        return $oSelect->get();
    }

    public function getListFunction($filters){
        $oSelect = $this
            ->select(
                $this->table.'.qr_code_template_id',
                $this->table.'.type',
                $this->table.'.qr_code',
                $this->table.'.qr_link',
                $this->table.'.qr_type',
                $this->table.'.apply_for',
                $this->table.'.apply_branch_id',
                $this->table.'.apply_arear_id',
                $this->table.'.apply_table_id',
                $this->table.'.expire_type',
                $this->table.'.expire_start',
                $this->table.'.expire_end',
                $this->table.'.status',
                $this->table.'.is_request_location',
                $this->table.'.location_lat',
                $this->table.'.location_lng',
                $this->table.'.location_radius',
                $this->table.'.is_request_wifi',
                $this->table.'.wifi_name',
                $this->table.'.wifi_ip',
                $this->table.'.template_frames_id',
                $this->table.'.template_font_id',
                $this->table.'.is_request_location',
                $this->table.'.template_location',
                $this->table.'.template_color',
                $this->table.'.template_logo',
                $this->table.'.created_at',
                $this->table.'.updated_at',
                'created.full_name as created_name',
                'updated.full_name as updated_name',
                DB::raw("(SELECT COUNT(*) FROM fnb_qr_code_scan where fnb_qr_code_scan.qr_code_template_id = {$this->table}.qr_code_template_id) as total_scan")
            )
            ->leftJoin('staffs as created','created.staff_id',$this->table.'.created_by')
            ->leftJoin('staffs as updated','updated.staff_id',$this->table.'.updated_by');

        if(isset($filters['qr_code'])){
            $oSelect = $oSelect->where($this->table.'.qr_code',$filters['qr_code']);
            unset($filters['qr_code']);
        }

        if(isset($filters['qr_type'])){
            $oSelect = $oSelect->where($this->table.'.qr_type',$filters['qr_type']);
            unset($filters['qr_type']);
        }

        if(isset($filters['created_by'])){
            $oSelect = $oSelect->where($this->table.'.created_by',$filters['created_by']);
            unset($filters['created_by']);
        }

        if(isset($filters['updated_by'])){
            $oSelect = $oSelect->where($this->table.'.updated_by',$filters['updated_by']);
            unset($filters['updated_by']);
        }

        if(isset($filters['created_at'])){
            $time = explode(' - ',$filters['created_at']);

            $start = Carbon::createFromFormat('d/m/Y',$time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$time[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
            unset($filters['created_at']);
        }

        if(isset($filters['updated_at'])){
            $time = explode(' - ',$filters['updated_at']);
            $start = Carbon::createFromFormat('d/m/Y',$time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$time[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.updated_at',[$start,$end]);
            unset($filters['updated_at']);
        }

        if(isset($filters['expire_date'])){
            $expire = Carbon::parse($filters['expire_date'])->format('Y-m-d');

            $oSelect = $oSelect
                ->whereDate($this->table.'.expire_start','<=',$expire)
                ->whereDate($this->table.'.expire_end','>=',$expire);
            unset($filters['expire_date']);
        }

        if (isset($filters['is_request_wifi'])){
            $oSelect = $oSelect->where($this->table.'.is_request_wifi',$filters['is_request_wifi']);
            unset($filters['is_request_wifi']);
        }

        if (isset($filters['is_request_location'])){
            $oSelect = $oSelect->where($this->table.'.is_request_location',$filters['is_request_location']);
            unset($filters['is_request_location']);
        }

        if (isset($filters['apply_for'])){
            $oSelect = $oSelect->where($this->table.'.apply_for',$filters['apply_for']);
            unset($filters['apply_for']);
        }

        if (isset($filters['status'])){
            $oSelect = $oSelect->where($this->table.'.status',$filters['status']);
            unset($filters['status']);
        }

        return $oSelect->orderBy($this->table.'.qr_code_template_id','DESC');
    }

    public function insertTemplate($data){
        return $this->insertGetId($data);
    }

    /**
     * lấy chi tiết qr code
     * @param $id
     */
    public function getDetail($id){
        return $this
            ->select(
                $this->table.'.*',
                'branches.branch_name',
                'fnb_areas.name as area_name',
                'fnb_table.name as table_name',
                'fnb_qr_template_font.name as font_name',
                'fnb_qr_template_font.value as font_value',
            )
            ->leftJoin('branches','branches.branch_id',$this->table.'.apply_branch_id')
            ->leftJoin('fnb_areas','fnb_areas.area_id',$this->table.'.apply_arear_id')
            ->leftJoin('fnb_table','fnb_table.table_id',$this->table.'.apply_table_id')
            ->leftJoin('fnb_qr_template_font','fnb_qr_template_font.template_font_id',$this->table.'.template_font_id')
            ->where($this->table.'.qr_code_template_id',$id)
            ->first();
    }

    /**
     * Xóa template
     * @param $idQrTemplate
     */
    public function removeTemplate($idQrTemplate){
        return $this
            ->where('qr_code_template_id',$idQrTemplate)
            ->delete();
    }

    /**
     * Cập nhật trạng thái
     * @param $data
     * @param $idTemplate
     */
    public function updateTemplate($data,$idTemplate){
        return $this
            ->where('qr_code_template_id',$idTemplate)
            ->update($data);
    }

}