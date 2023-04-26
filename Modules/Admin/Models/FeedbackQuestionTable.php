<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/17/2018
 * Time: 1:26 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FeedbackQuestionTable extends Model
{
    use ListTableTrait;
    protected $table = 'feedback_question';
    protected $primaryKey = 'feedback_question_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feedback_question_id',
        'feedback_question_type',
        'feedback_question_title',
        'feedback_question_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function _getList($filters = [])
    {
        $oSelect = $this
            ->select(
                'feedback_question.feedback_question_id',
                'feedback_question.feedback_question_type',
                'feedback_question.feedback_question_title',
                'feedback_question.feedback_question_active',
                'feedback_question.created_at',
                'feedback_question.created_by',
                'feedback_answer.feedback_answer_id',
                'customers.customer_id',
                'customers.full_name'
            )
            ->leftJoin("feedback_answer", "feedback_question.feedback_question_id", "=", "feedback_answer.feedback_question_id")
            ->leftJoin("customers", "feedback_question.created_by", "=", "customers.customer_id");
        if (isset($filters['search'])) {
            $oSelect = $oSelect->where('feedback_question.feedback_question_title','like','%'.$filters['search'].'%');
            unset($filters['search']);
        }
        if (isset($filters['feedback_question_type'])) {
            $oSelect = $oSelect->where('feedback_question.feedback_question_type',$filters['feedback_question_type']);
            unset($filters['feedback_question_type']);
        }

        if (isset($filters['created_at'])){
            $arr_explode = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_explode[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_explode[1])->format('Y-m-d');
            $filters['created_at'] = [$startTime . ' 00:00:00', $endTime . ' 23:59:59'];
            $oSelect = $oSelect->whereBetween('feedback_question.created_at',$filters['created_at']);
            unset($filters['created_at']);
        }

        if (isset($filters['feedback_question_active'])) {
            $oSelect = $oSelect->where('feedback_question.feedback_question_active',$filters['feedback_question_active']);
            unset($filters['feedback_question_active']);
        }

        return $oSelect;
    }

    public function getDetail($id) {
        $oSelect = $this->where('feedback_question_id',$id)->first();
        return $oSelect;
    }
}
//