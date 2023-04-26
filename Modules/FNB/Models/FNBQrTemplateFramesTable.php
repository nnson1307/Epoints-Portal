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

class FNBQrTemplateFramesTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_qr_template_frames';
    protected $primaryKey = 'template_frames_id';
    protected $fillable
        = [
            'template_frames_id',
            'name',
            'image',
            'template_frames_id',
            'transform_qr_code',
            'transform_text',
            'is_active',
        ];

    /**
     *
     */
    public function getAll(){
        return $this
            ->where('is_active',1)
            ->orderBy('template_frames_id','ASC')
            ->get();
    }

    public function getDetail($id){
        return $this->where('template_frames_id',$id)
            ->first();
    }
}