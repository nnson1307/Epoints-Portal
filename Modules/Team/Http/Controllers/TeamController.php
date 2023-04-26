<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/07/2022
 * Time: 13:45
 */

namespace Modules\Team\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Team\Http\Requests\Team\StoreRequest;
use Modules\Team\Http\Requests\Team\UpdateRequest;
use Modules\Team\Repositories\Team\TeamRepoInterface;

class TeamController extends Controller
{
    protected $team;

    public function __construct(
        TeamRepoInterface $team
    ) {
        $this->team = $team;
    }

    /**
     * Danh sách nhóm
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->team->list();

        return view('team::team.index', [
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
            'team$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ],
        ];
    }

    /**
     * Ajax filter, phân trang nhóm
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
            'team$is_actived'
        ]);

        $data = $this->team->list($filter);

        return view('team::team.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View tạo nhóm
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function createAction()
    {
        //Lấy data view tạo
        $data = $this->team->getDataCreate();

        return view('team::team.create', $data);
    }

    /**
     * Đổi chức vụ load nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeTitleAction(Request $request)
    {
        $data = $this->team->changeTitle($request->all());

        return response()->json($data);
    }

    /**
     * Thêm nhóm
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $this->team->store($request->all());

        return response()->json($data);
    }

    /**
     * View chỉnh sửa nhóm
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function editAction($id)
    {
        //Lấy data view tạo
        $data = $this->team->getDataEdit($id);

        return view('team::team.edit', $data);
    }

    /**
     * Chỉnh sửa nhóm
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->team->update($request->all());

        return response()->json($data);
    }

    /**
     * Xoá nhóm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->team->destroy($request->all());

        return response()->json($data);
    }
}