<?php

namespace Modules\Dashbroad\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Dashbroad\Http\Requests\Dashboard\StoreRequest;
use Modules\Dashbroad\Http\Requests\Dashboard\UpdateRequest;
use Modules\Dashbroad\Repositories\DashBoardConfig\DashBoardConfigRepoInterface;
use Modules\Dashbroad\Repositories\DashbroadRepositoryInterface;

class DashBroadConfigController extends Controller
{

    protected $dashBroad;


    public function __construct(DashBoardConfigRepoInterface $dashBroad)
    {
        $this->dashBroad = $dashBroad;
    }

    public function indexAction()
    {
        $data = $this->dashBroad->getList();
        return view('dashbroad::dashboard-config.index', [
            'LIST' => $data['list'],
        ]);
    }
    public function listAction(Request $request)
    {
        $filter = $request->all();
        $data = $this->dashBroad->getList($filter);
        return view('dashbroad::dashboard-config.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }
    public function createAction()
    {
        return view('dashbroad::dashboard-config.create', [

        ]);
    }

    /**
     * popup create thông tin cơ bản dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function popCreateAction(Request $request)
    {
        return $this->dashBroad->popCreateDashboardConfig($request->all());
    }

    /**
     * Lưu thông tin cơ bản của dashboard
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function submitPopCreateAction(StoreRequest $request)
    {
        return $this->dashBroad->savePopCreateDashboardConfig($request->all());
    }

    /**
     * ds widget mặc định hệ thống tự define
     *
     * @param Request $request
     * @return mixed
     */
    public function getListWidget(Request $request)
    {
        return $this->dashBroad->getListWidget($request->all());
    }

    /**
     * Tạo bố cục dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function createDashboardAction(Request $request)
    {
        return $this->dashBroad->createDashboardAction($request->all());
    }

    /**
     * soft delete dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function removeDashboardAction(Request $request)
    {
        return $this->dashBroad->removeDashboardAction($request->all());
    }

    /**
     * view detail dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function detailAction(Request $request)
    {
        return $this->dashBroad->getDetail($request->all());
    }

    /**
     * switch update status dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatusAction(Request $request)
    {
        return $this->dashBroad->changeStatusAction($request->all());
    }

    /**
     * trang edit
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function editAction(Request $request)
    {
        return view('dashbroad::dashboard-config.edit', [
            'id' => $request->id
        ]);
    }

    /**
     * popup chủnh sửa tt dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function popEditAction(Request $request)
    {
        return $this->dashBroad->popEditDashboardConfig($request->all());
    }

    /**
     * lưu chỉnh sửa thông tin chung dashboard
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function submitPopEditAction(UpdateRequest $request)
    {
        return $this->dashBroad->savePopEditDashboardConfig($request->all());
    }

    /**
     * chỉnh sửa cấu hình dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function editDashboardAction(Request $request)
    {
        return $this->dashBroad->editDashboardAction($request->all());
    }
}