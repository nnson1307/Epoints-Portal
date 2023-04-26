<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/07/2022
 * Time: 13:52
 */

namespace Modules\Team\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Team\Http\Requests\Company\StoreRequest;
use Modules\Team\Http\Requests\Company\UpdateRequest;
use Modules\Team\Repositories\Company\CompanyRepoInterface;

class CompanyController extends Controller
{
    protected $company;

    public function __construct(
        CompanyRepoInterface $company
    ) {
        $this->company = $company;
    }

    /**
     * Danh sách công ty
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->company->list();

        return view('team::company.index', [
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
            'company$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ],
        ];
    }

    /**
     * Ajax filter, phân trang công ty
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
            'company$is_actived'
        ]);

        $data = $this->company->list($filter);

        return view('team::company.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm công ty
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create()
    {
        return view('team::company.create');
    }

    /**
     * Thêm công ty
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $this->company->store($request->all());

        return response()->json($data);
    }

    /**
     * View chỉnh sửa công ty
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($id)
    {
        $data = $this->company->getDataEdit($id);

        return view('team::company.edit', $data);
    }

    /**
     * Chỉnh sửa công ty
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->company->update($request->all());

        return response()->json($data);
    }

    /**
     * Xoá công ty
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->company->destroy($request->all());

        return response()->json($data);
    }

    /**
     * Chỉnh sửa trạng thái
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $data = $this->company->changeStatus($request->all());

        return response()->json($data);
    }
}