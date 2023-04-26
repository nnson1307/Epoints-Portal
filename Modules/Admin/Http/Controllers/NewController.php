<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 4:03 PM
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\News\StoreRequest;
use Modules\Admin\Http\Requests\News\UpdateRequest;
use Modules\Admin\Repositories\News\NewRepoInterface;

class NewController extends Controller
{
    protected $new;

    public function __construct(
        NewRepoInterface $new
    ) {
        $this->new = $new;
    }

    /**
     * Danh sách bài viết
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->new->list();

        return view('admin::news.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách bài viết
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'is_actived']);

        $data = $this->new->list($filters);
        return view('admin::news.list', [
            'LIST' => $data['list'],
            'page' => $filters['page']
        ]);
    }

    /**
     * Thêm bài viết
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create()
    {
        $data = $this->new->dateViewCreate();

        return view('admin::news.create', $data);
    }

    /**
     * Upload hình ảnh
     *
     * @param Request $request
     * @return mixed
     */
    public function uploadAction(Request $request)
    {
        return $this->new->uploadAction($request->all());
    }

    /**
     * Thêm bài viết
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->new->store($request->all());
    }

    /**
     * View chỉnh sửa bài viết
     *
     * @param $newId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($newId)
    {
        $data = $this->new->dataViewEdit($newId);

        return view('admin::news.edit', $data);
    }

    /**
     * Chỉnh sửa bài viết
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->new->update($request->all());
    }

    /**
     * Thay đổi trạng thái
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatusAction(Request $request)
    {
        return $this->new->changeStatus($request->all());
    }

    /**
     * Xóa bài viết
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->new->remove($request->new_id);
    }
}