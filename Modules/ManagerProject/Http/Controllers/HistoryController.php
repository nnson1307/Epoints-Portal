<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerProject\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ManagerProject\Repositories\ManageHistory\ManageHistoryRepoInterface;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;


class HistoryController extends Controller
{
    /**
     * Hiẻn thị view lịch sử
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function indexAction(Request $request)
    {
        $rManageHistory = app()->get(ManageHistoryRepoInterface::class);
        $param = $request->all();
        $data = $rManageHistory->getListStaff($param);

        $rProject = app()->get(ProjectRepositoryInterface::class);
        $info = $rProject->projectInfoWork($param['manage_project_id']);

        return view('manager-project::history.index', [
            'project' => $data['project'],
            'listStaff' => $data['listStaffInfo'],
            'info' => $info
        ]);
    }

    /**
     * Tìm kiếm lịch sử
     * @param Request $request
     */
    public function searchAction(Request $request){
        $rManageHistory = app()->get(ManageHistoryRepoInterface::class);
        $param = $request->all();
        $data = $rManageHistory->searchHistory($param);
        return response()->json($data);
    }


}