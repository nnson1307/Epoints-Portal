<?php


namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TemplateQuestionTable extends Model
{
    use ListTableTrait;
    protected $table = 'survey_template_questions';
    protected $fillable = [
        'key_template',
        'name'
    ];
}
