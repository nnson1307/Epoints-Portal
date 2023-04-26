<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class SurveyQuestionChoiceTable extends Model
{
    use ListTableTrait;

    protected $table = 'survey_question_choice';
    protected $primaryKey = 'survey_question_choice_id';
    public $timestamps = false;
    protected $fillable = [
        'survey_question_choice_id',
        'survey_question_id',
        'survey_id',
        'survey_question_choice_title',
        'survey_question_choice_value',
        'survey_question_choice_position',
        'survey_question_choice_config'
    ];
    const ANSWER_SUCCESS = 1;
    /**
     * Add one record
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $select = $this->create($data);
        return $select->{$this->primaryKey};
    }

    /**
     * Add multi record
     * @param $data
     * @return mixed
     */
    public function addInsert($data)
    {
        $this->insert($data);
    }

    /**
     * Get by survey_id
     * @param $id
     * @return mixed
     */
    public function getBySurveyId($id)
    {
        $select = $this->where("{$this->table}.survey_id", $id)->get();
        return $select;
    }

    /**
     * Get by survey_question_id
     * @param $id
     * @return mixed
     */
    public function getBySurveyQuestionId($id)
    {
        $select = $this->where("{$this->table}.survey_question_id", $id)
            ->orderBy('survey_question_choice_position')
            ->get();
        return $select;
    }

    /**
     * Lấy danh sách câu trả lời đúng của câu hỏi tính điểm
     * @param $idQuestion
     * @return mixed
     */

    public function getByAnswerOfQuestionCountPoint($idQuestion)
    {
        $select = $this->where("survey_question_id", $idQuestion)
            ->where("survey_question_choice_value", 1)
            ->orderBy('survey_question_choice_position')
            ->get();
        return $select;
    }

    /**
     * Remove by survey_id
     * @param $id
     */
    public function removeBySurveyId($id)
    {
        $this->where("{$this->table}.survey_id", $id)->delete();
    }

    /**
     * Lấy danh sách tất cả câu trả lời đúng của câu hỏi signleChoice
     * @param $idQuestion
     * @return mixed
     */

    public function getAnswerSuccessByQuestionSingleChoice($idQuestion)
    {
        $oSelect = $this->where("survey_question_id", $idQuestion)
            ->where("survey_question_choice_value", self::ANSWER_SUCCESS)
            ->first();
        if ($oSelect) {
            $result =  $oSelect->survey_question_choice_id;
        } else {
            $result = "";
        }
        return $result;
    }

    /**
     * Lấy danh sách tất cả câu trả lời đúng của câu hỏi muitplechoide
     * @param $idQuestion
     * @return mixed
     */

    public function getAnswerSuccessByQuestionMuitipleChoice($idQuestion)
    {
        $oSelect =  $this->where("survey_question_id", $idQuestion)
            ->where("survey_question_choice_value", self::ANSWER_SUCCESS)
            ->get();
        if ($oSelect) {
            $result = $oSelect->pluck("survey_question_choice_id")->toArray();
        } else {
            $result = "";
        }
        return $result;
    }
}
