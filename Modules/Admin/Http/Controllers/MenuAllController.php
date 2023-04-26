<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Repositories\MenuAll\MenuAllRepoInterface;

class MenuAllController extends Controller
{
    protected $menuAll;

    public function __construct(MenuAllRepoInterface $menuAll)
    {
        $this->menuAll = $menuAll;
    }

    /**
     * Page menu all
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        //Lấy data menu
        $data = $this->menuAll->getMenuByGroup();

        return view('admin::menu-all.index', [
            'data' => $data
        ]);
    }

    /**
     * Search menu all
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAction(Request $request)
    {
        $data = $this->menuAll->dataSearchMenuAll($request->all());

        return response()->json($data);
    }
}