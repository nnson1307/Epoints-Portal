<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\MenuHorizontal\MenuHorizontalRepoInterface;

class MenuHorizontalController extends Controller
{
    protected $menuHorizontal;

    public function __construct(MenuHorizontalRepoInterface $menuHorizontal)
    {
        $this->menuHorizontal = $menuHorizontal;
    }

    public function index()
    {
        $filter['type'] = 'horizontal';
        $data = $this->menuHorizontal->list($filter);
        return view('admin::menu-horizontal.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    protected function filters()
    {
        return [

        ];
    }
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display'
        ]);
        $filter['type'] = 'horizontal';
        $data = $this->menuHorizontal->list($filter);

        return view('admin::menu-horizontal.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Show popup thêm nhóm chức năng menu
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupAdd()
    {
        $data = $this->menuHorizontal->popupAdd();
        return response()->json($data);
    }

    /**
     * Lấy danh sách menu theo menu category id
     *
     * @param Request $request
     * @return mixed
     */
    public function getListMenuByMenuCategory(Request $request)
    {
        return $this->menuHorizontal->getListMenuByMenuCategory($request->all());
    }

    /**
     * Thêm chức năng cho menu ngang (thanh điều hướng)
     *
     * @param Request $request
     * @return mixed
     */
    public function saveMenuHorizontal(Request $request)
    {
        return $this->menuHorizontal->saveMenuHorizontal($request->all());
    }

    /**
     * Cập nhật trạng thái cho is_active
     *
     * @param Request $request
     * @return mixed
     */
    public function updateStatus(Request $request)
    {
        return $this->menuHorizontal->updateStatus($request->all());
    }

    /**
     * Xoá chức năng menu
     *
     * @param Request $request
     * @return mixed
     */
    public function remove(Request $request)
    {
        return $this->menuHorizontal->remove($request->all());
    }
}