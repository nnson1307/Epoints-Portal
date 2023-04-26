<?php


namespace Modules\Admin\Repositories\Log;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\FeedbackAnswerTable;
use Modules\Admin\Models\FeedbackQuestionTable;

class LogRepository implements LogRepositoryInterface
{
    protected $feedbackQuestion;

    public function __construct(FeedbackQuestionTable $feedbackQuestion)
    {
        $this->feedbackQuestion = $feedbackQuestion;
    }

    public function list(array $filters = [])
    {
        return $this->feedbackQuestion->getList($filters);
    }

    public function getDetail($id)
    {
        return $this->feedbackQuestion->getDetail($id);
    }

    /**
     * Show popup trả lời câu hỏi khách hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function popupAnswer($input)
    {
        $html = \View::make('admin::log-customer.question-customer.popup-answer', [
            'feedback_question_id' => $input['feedback_question_id'],
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Trả lời câu hỏi
     *
     * @param $input
     * @return array|mixed
     */
    public function saveAnswer($input)
    {
        try {
            $mFeedbackAnswer = new FeedbackAnswerTable();
            $data = [
                'feedback_question_id' => $input['feedback_question_id'],
                'user_id' => Auth::id(),
                'feedback_answer_value' => $input['content'],
                'created_at' => date('Y-m-d')
            ];

            $mFeedbackAnswer->add($data);

            return [
                'error' => 0,
                'message' => __('Thêm thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Xoá câu trả lời
     *
     * @param $input
     * @return array
     */
    public function removeAnswer($input)
    {
        try {
            $mFeedbackAnswer = new FeedbackAnswerTable();
            $mFeedbackAnswer->remove($input['feedback_answer_id']);
            return [
                'error' => 0,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('Xoá thất bại')
            ];
        }
    }

    /**
     * Show popup chỉnh sửa câu trả lời
     *
     * @param $input
     * @return array|mixed
     */
    public function popupEditAnswer($input)
    {
        $mFeedbackAnswer = new FeedbackAnswerTable();
        $data = $mFeedbackAnswer->getDetail($input['feedback_answer_id']);
        $html = \View::make('admin::log-customer.question-customer.popup-edit-answer', $data)->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Cập nhật câu trả lời
     *
     * @param $input
     * @return array
     */
    public function updateAnswer($input)
    {
        try {
            $mFeedbackAnswer = new FeedbackAnswerTable();
            $mFeedbackAnswer->edit(['feedback_answer_value' => $input['content']], $input['feedback_answer_id']);

            return [
                'error' => 0,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('Chỉnh sửa thất bại')
            ];
        }
    }
}