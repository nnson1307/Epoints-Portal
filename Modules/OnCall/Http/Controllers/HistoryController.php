<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 29/07/2021
 * Time: 10:51
 */

namespace Modules\OnCall\Http\Controllers;


use Illuminate\Http\Request;
use Modules\OnCall\Repositories\History\HistoryRepoInterface;

class HistoryController extends Controller
{
    protected $history;

    public function __construct(
        HistoryRepoInterface $history
    ) {
        $this->history = $history;
    }

    /**
     * Danh sách lịch sử cuộc gọi
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        //Lấy danh sách lịch sử
        $data = $this->history->list();

        return view('on-call::history.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Ajax + filter lịch sử cuộc gọi
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'object_id_call',
            'source_code',
            'history_type',
            'created_at'
        ]);

        $data = $this->history->list($filter);

        return view('on-call::history.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        //Lấy tất cả option ở view
        $getOption = $this->history->getOption();

        //Lấy ds nhân viên
        $listStaff = (['' => __('Chọn người gọi')]) + $getOption['optionStaff'];
        //Lấy ds nguồn cuộc gọi
        $listSource = (['' => __('Chọn nguồn cuộc gọi')]) + $getOption['optionSource'];

        return [
            'object_id_call' => [
                'data' => $listStaff
            ],
            'source_code' => [
                'data' => $listSource
            ],
            'history_type' => [
                'data' => [
                    '' => __('Chọn loại cuộc gọi'),
                    'out' => __('Cuộc gọi đi'),
                    'in' => __('Cuộc gọi đến')
                ]
            ]
        ];
    }

    /**
     * Chi tiết lịch sử cuộc gọi
     *
     * @param $historyId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($historyId)
    {
        //Lấy danh sách lịch sử
        $data = $this->history->dataViewDetail($historyId);

        return view('on-call::history.detail', $data);
    }
}