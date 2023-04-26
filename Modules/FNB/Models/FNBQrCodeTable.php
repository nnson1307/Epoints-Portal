<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FNBQrCodeTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_qr_code';
    protected $primaryKey = 'qr_code_id';
    protected $fillable
        = [
            'qr_code_id',
            'code',
            'url',
            'qr_code_template_id',
            'qr_image',
            'table_id',
            'created_at',
        ];

    /**
     * Tạo danh sách qr theo bàn
     * @param $data
     * @return mixed
     */
    public function insertQrCodeTable($data){
        return $this
            ->insert($data);
    }

    /**
     * Lấy danh sách qr theo template
     */
    public function getListQrCode($idQrTemplate){
        return $this
            ->select(
                $this->table.'.*',
                'fnb_areas.name as areas_name',
                'fnb_table.name as table_name',
                'fnb_qr_template_font.value as font_value',
                'fnb_qr_template_frames.image as frames_image',
                'fnb_qr_template_frames.template_frames_id as frames_frames_id',
                'fnb_qr_code_template.template_color',
                'fnb_qr_code_template.template_logo',
                'fnb_qr_code_template.template_content',
                'fnb_qr_template_frames.transform_qr_code',
                'fnb_qr_template_frames.transform_text'
            )
            ->join('fnb_qr_code_template','fnb_qr_code_template.qr_code_template_id',$this->table.'.qr_code_template_id')
            ->leftJoin('fnb_qr_template_frames','fnb_qr_template_frames.template_frames_id','fnb_qr_code_template.template_frames_id')
            ->leftJoin('fnb_qr_template_font','fnb_qr_template_font.template_font_id','fnb_qr_code_template.template_font_id')
            ->leftJoin('fnb_table','fnb_table.table_id',$this->table.'.table_id')
            ->leftJoin('fnb_areas','fnb_areas.area_id','fnb_table.area_id')
            ->where($this->table.'.qr_code_template_id',$idQrTemplate)
            ->orderBy($this->table.'.qr_code_id','ASC')
            ->get();
    }

    /**
     * Xóa table theo template
     * @param $idQrTemplate
     */
    public function removeTableByTemplate($idQrTemplate){
        return $this
            ->where('qr_code_template_id',$idQrTemplate)
            ->delete();
    }

    /**
     * Lấy danh sách table theo template
     */
    public function getListTableByTemplate($idQrTemplate){
        return $this
            ->select('fnb_table.*')
            ->join('fnb_table','fnb_table.table_id',$this->table.'.table_id')
            ->where($this->table.'.qr_code_template_id',$idQrTemplate)
            ->get();
    }

}