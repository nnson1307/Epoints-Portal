<?php

namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class SurveyTemplateNotificationTable extends Model
{
    use ListTableTrait;
    protected $table = 'survey_template_notification';
    protected $primaryKey = 'id';
    protected $fillable =
        [
            'id',
            'survey_id',
            'title',
            'message',
            'avatar',
            'has_detail',
            'detail_background',
            'detail_content',
            'detail_action_name',
            'detail_action',
            'detail_action_params',
            'notification_type',
            'params_show',
            'show_point',
            'created_at',
            'updated_at'
        ];

    public function add($data)
    {
        $this->create($data);
    }

    public function  getDetailBySurveyId($id)
    {
        $select = $this->select('*')
            ->where($this->table.'.survey_id', $id)
            ->first();
        return $select;
    }

    public function updateBySurveyId($data, $id)
    {
        return $this->where($this->table.'.survey_id', $id)->update($data);
    }
}