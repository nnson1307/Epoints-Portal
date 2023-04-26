<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Modules\Survey\Models\SurveyQuestionTable;

class SurveyBlockTable extends Model
{
    use ListTableTrait;

    protected $table = 'survey_block';
    protected $primaryKey = 'survey_block_id';
    protected $fillable = [
        'survey_block_id',
        'survey_id',
        'survey_block_name',
        'survey_block_position',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

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
     * Remove by survey_id
     * @param $id
     */
    public function removeBySurveyId($id)
    {
        $this->where("{$this->table}.survey_id", $id)->delete();
    }

    // ORM //

    /**
     * quan hệ một nhiều với bảng survey_question
     */

    public function questions() {

        return $this->hasMany(SurveyQuestionTable::class, 'survey_block_id');
    }
}
