<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/23/2020
 * Time: 10:18 AM
 */

namespace Modules\CustomerLead\Http\Controllers;


use Illuminate\Http\Request;
use Modules\CustomerLead\Http\Requests\PipelineCategory\StoreRequest;
use Modules\CustomerLead\Http\Requests\PipelineCategory\UpdateRequest;
use Modules\CustomerLead\Repositories\PipelineCategory\PipelineCategoryRepoInterface;

class PipelineCategoryController extends Controller
{
    protected $pipelineCategory;

    public function __construct(
        PipelineCategoryRepoInterface $pipelineCategory
    ) {
        $this->pipelineCategory = $pipelineCategory;
    }

    /**
     * Danh sách pipeline category
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->pipelineCategory->list();

        return view('customer-lead::pipeline-category.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {

        return [

        ];
    }

    /**
     * Ajax filter, phân trang ds pipeline category
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
            'created_at',
        ]);

        $data = $this->pipelineCategory->list($filter);

        return view('customer-lead::pipeline-category.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm pipeline category
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create()
    {
        return view('customer-lead::pipeline-category.create');
    }

    /**
     * Thêm pipeline category
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->pipelineCategory->store($request->all());
    }

    /**
     * View chỉnh sửa pipeline category
     *
     * @param $categoryId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($categoryId)
    {
        $dataEdit = $this->pipelineCategory->dataEdit($categoryId);

        return view('customer-lead::pipeline-category.edit', $dataEdit);
    }

    /**
     * Chỉnh sửa pipeline category
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->pipelineCategory->update($request->all());
    }

    /**
     * Thay đổi trạng thái pipeline category
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatusAction(Request $request)
    {
        return $this->pipelineCategory->changeStatus($request->all());
    }

    /**
     * Xóa pipeline category
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->pipelineCategory->destroy($request->all());
    }
}