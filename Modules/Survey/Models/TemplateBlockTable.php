<?php


namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateBlockTable extends Model
{
    protected $table = 'survey_block_template';
    protected $fillable = [
        'id_survey_block_template',
        'survey_block_id',
        'key_template'
    ];
}
