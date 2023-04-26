<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Faq\FaqStoreRequest;
use Modules\Admin\Http\Requests\Faq\FaqUpdateRequest;
use Modules\Admin\Repositories\Faq\FaqRepositoryInterface;

class FaqController extends Controller
{
    /**
     * @var FaqRepositoryInterface
     */
    protected $faq;

    public function __construct(FaqRepositoryInterface $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Trang chính
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $filter = request()->all();
        $data = $this->faq->getListNew($filter);
        $parentList = $this->faq->getListAll();

        return view('admin::faq.index', [
            'LIST' => $data['listFaq'],
            'FILTER' => $this->filters(),
            'parentList' => $parentList
        ]);
    }

    // FUNCTION  FILTER LIST ITEM
    protected function filters()
    {
        $parentList = $this->faq->getListAll();
        $arr = [];
        foreach ($parentList as $item) {
            $arr[$item['faq_group_id']] = $item[getValueByLang('faq_group_title_')];
        }
        $selectParentList = (['' => __('Nhóm nội dung hỗ trợ')]) + $arr;

        return [
            'faq$faq_group' => [
                'data' => $selectParentList
            ],
            'faq$is_actived' => [
                'data' => [
                    '' => __('Trạng thái hiển thị'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    /**
     * Danh sách hỗ trợ
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'keyword_faq$faq_title_vi','keyword_faq$faq_title_en', 'faq$is_actived','faq$faq_group']);

        $data = $this->faq->getListNew($filters);
        return view('admin::faq.list', [
                'LIST' => $data['listFaq'],
                'FILTER' => $data['filter'],
                'page' => $filters['page']
            ]
        );
    }

    /**
     * Hiển thị thông tin chi tiết
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $detail = $this->faq->detail($id);
        $parentList = $this->faq->getListAll(['notin' => [$id]]);

        return view('admin::faq.detail', [
            'detail' => $detail,
            'parentList' => $parentList
        ]);
    }

    /**
     * Hiển thị form thêm nội dung
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $parentList = $this->faq->getListAll();
        return view('admin::faq.create', [
            'parentList' => $parentList
        ]);
    }

    /**
     * Xử lý thêm nội dung
     *
     * @param  FaqStoreRequest $request
     * @return Response
     */
    public function store(FaqStoreRequest $request)
    {
        $data = $request->all();

        $result = $this->faq->add($data);

        return response()->json($result);
    }

    /**
     * Hiển thị form chỉnh sửa nội dung
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $detail = $this->faq->detail($id);
        $parentList = $this->faq->getListAll(['notin' => [$id]]);

        return view('admin::faq.edit', [
            'detail' => $detail,
            'parentList' => $parentList
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param  FaqUpdateRequest $request
     * @return Response
     */
    public function update(FaqUpdateRequest $request)
    {
        $data = $request->all();

        $result = $this->faq->edit($data, $data['faq_id']);

        return response()->json($result);
    }

    /**
     * Đánh dấu xóa nội dung
     *
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        $data = $request->only(['faq_id']);

        $result = $this->faq->remove($data['faq_id']);

        if ($result) {
            return response()->json([
                'error' => 0,
                'message' => __('admin::faq.popup.IS_DELETED'),
            ]);
        } else {
            return response()->json([
                'error' => 1,
                'message' => __('admin::faq.popup.ERROR'),
            ]);
        }
    }

    /**
     * Ajax cập nhật trang thái
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $data = $request->only(['faq_id', 'is_actived']);

        if (!isset($data['faq_id'])) {
            return response()->json([
                'error' => 1,
                'message' => __('Cập nhật trạng thái thất bại'),
            ]);
        }

        $result = $this->faq->updateStatus($data['is_actived'], $data['faq_id']);

        if ($result) {
            return response()->json([
                'error' => 0,
                'message' => __('Cập nhật trạng thái thành công'),
            ]);
        } else {
            return response()->json([
                'error' => 1,
                'message' => __('Cập nhật trạng thái thất bại'),
            ]);
        }
    }
}
