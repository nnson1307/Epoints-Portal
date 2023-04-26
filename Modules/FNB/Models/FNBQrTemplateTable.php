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

class FNBQrTemplateTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_qr_template';
    protected $primaryKey = 'qr_template_id';
    protected $fillable
        = [
            'qr_template_id',
            'template_frames_id',
            'template_font_id',
            'template_location',
            'template_content',
            'template_color',
            'template_logo',
            'created_at',
            'created_by',
        ];

}