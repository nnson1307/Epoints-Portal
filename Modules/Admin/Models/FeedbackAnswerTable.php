<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class FeedbackAnswerTable extends Model
{
    protected $table = 'feedback_answer';
    protected $primaryKey = 'feedback_answer_id';

    protected $fillable = [
        'feedback_answer_id',
        'feedback_question_id',
        'user_id',
        'feedback_answer_value',
        'created_at',
        'updated_at'
    ];

    /**
     * Insert câu trả lời
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Xoá câu trả lời
     *
     * @param $answerId
     * @return mixed
     */
    public function remove($answerId)
    {
        return $this->where('feedback_answer_id', $answerId)->delete();
    }

    /**
     * Chi tiết câu trả lời
     *
     * @param $answerId
     * @return mixed
     */
    public function getDetail($answerId)
    {
        return $this->where('feedback_answer_id',$answerId)->first();
    }

    /**
     * Cập nhật câu trả lời
     *
     * @param array $data
     * @param $answerId
     * @return mixed
     */
    public function edit(array $data, $answerId)
    {
        return $this->where('feedback_answer_id', $answerId)
            ->update($data);
    }
}