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

class FNBQrTemplateFontTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_qr_template_font';
    protected $primaryKey = 'template_font_id';
    protected $fillable
        = [
            'template_font_id',
            'name',
            'value',
            'is_active',
            'created_at',
        ];

    const IS_ACTIVE = 1;

    public function getListFont(){
        return $this
            ->where('is_active',self::IS_ACTIVE)
            ->get();
    }
}