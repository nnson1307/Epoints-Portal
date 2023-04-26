<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\Log\LogRepositoryInterface;

class LogController extends Controller
{
    protected $log;
    protected $request;

    public function __construct(LogRepositoryInterface $log, Request $request)
    {
        $this->log = $log;
        $this->request = $request;
    }

    public function questionCustomer() {
        $list = $this->log->list();
        return view('admin::log-customer.question-customer.index', [
            'LIST' => $list,
        ]);
    }

    protected function filters()
    {
        return [
        ];
    }

    /**
     * Ajax danh sách user
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listLogCustomerAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'feedback_question_type', 'search', 'created_at','feedback_question_active']);
        $list = $this->log->list($filters);
        return view('admin::log-customer.question-customer.list', [
            'LIST' => $list,
            'page' => $filters['page']
        ]);
    }

    public function questionDetailCustomer($id) {
        $detail = $this->log->getDetail($id);
        return view('admin::log-customer.question-customer.detail', [
            'item' => $detail,
        ]);
    }

    /**
     * Show popup trả lời câu hỏi khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupAnswer(Request $request)
    {
        $data = $this->log->popupAnswer($request->all());
        return response()->json($data);
    }

    /**
     * Lưu câu trả lời
     *
     * @param Request $request
     * @return mixed
     */
    public function saveAnswer(Request $request)
    {
        return $this->log->saveAnswer($request->all());
    }

    /**
     * Xoá câu trả lời
     *
     * @param Request $request
     * @return mixed
     */
    public function removeAnswer(Request $request)
    {
        return $this->log->removeAnswer($request->all());
    }

    /**
     * Chỉnh sửa câu trả lời
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupEditAnswer(Request $request)
    {
        $data = $this->log->popupEditAnswer($request->all());
        return response()->json($data);
    }

    /**
     * Cập nhật câu trả lời
     *
     * @param Request $request
     * @return mixed
     */
    public function updateAnswer(Request $request)
    {
        return $this->log->updateAnswer($request->all());
    }
}