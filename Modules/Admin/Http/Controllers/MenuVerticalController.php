<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\MenuVertical\MenuVerticalRepoInterface;

class MenuVerticalController extends Controller
{
    protected $menuVertical;

    public function __construct(MenuVerticalRepoInterface $menuVertical) {
        $this->menuVertical = $menuVertical;
    }

    public function index()
    {
        $filter['type'] = 'vertical';
        $data = $this->menuVertical->list($filter);
        return view('admin::menu-vertical.index', [
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
        $filter['type'] = 'vertical';
        $data = $this->menuVertical->list($filter);

        return view('admin::menu-vertical.list', [
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
        $data = $this->menuVertical->popupAdd();
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
        return $this->menuVertical->getListMenuByMenuCategory($request->all());
    }

    /**
     * Thêm chức năng cho menu doc (truy cập nhanh)
     *
     * @param Request $request
     * @return mixed
     */
    public function saveMenuVertical(Request $request)
    {
        return $this->menuVertical->saveMenuVertical($request->all());
    }

    /**
     * Cập nhật trạng thái cho is_active
     *
     * @param Request $request
     * @return mixed
     */
    public function updateStatus(Request $request)
    {
        return $this->menuVertical->updateStatus($request->all());
    }

    /**
     * Xoá chức năng menu
     *
     * @param Request $request
     * @return mixed
     */
    public function remove(Request $request)
    {
        return $this->menuVertical->remove($request->all());
    }
}