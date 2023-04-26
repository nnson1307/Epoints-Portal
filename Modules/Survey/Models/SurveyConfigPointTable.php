<?php

namespace Modules\Survey\Models;

use Modules\Survey\Models\SurveyTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SurveyConfigPointTable extends Model
{

    protected $table = 'survey_config_point';
    protected $primaryKey = 'id_config_point';
    protected $fillable = [
        'id_config_point',
        'survey_id',
        'show_answer',
        'time_start',
        'time_end',
        'show_answer_wrong',
        'show_answer_success',
        'show_point',
        'count_point_text',
        'point_default'
    ];


    // ORM //

    public function survey(): HasOne
    {
        return $this->Hasone(SurveyTable::class, 'survey_id');
    }

    // Query //
    /**
     * Lấy item config theo khảo khảo
     * @param [int] $idSurvey
     * @return void
     */
    public function getConfigBySurvey($idSurvey) {

        return $this->where("survey_id", $idSurvey)->first();
    }
    
}
