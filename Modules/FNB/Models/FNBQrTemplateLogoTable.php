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

class FNBQrTemplateLogoTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_qr_template_logo';
    protected $primaryKey = 'template_logo_id';
    protected $fillable
        = [
            'template_logo_id',
            'name',
            'image',
            'is_active',
        ];

    /**
     * Lấy danh sách
     */
    public function getAll(){
        return $this
            ->where('is_active',1)
            ->orderBy('template_logo_id','ASC')
            ->get();
    }

    /**
     * Thêm logo
     * @param $data
     */
    public function insertLogo($data){
        return $this
            ->insertGetId($data);
    }
}