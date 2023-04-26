<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/10/2022
 * Time: 15:18
 */

namespace Modules\Shift\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Shift\Http\Requests\Recompense\StoreRequest;
use Modules\Shift\Http\Requests\Recompense\UpdateRequest;
use Modules\Shift\Repositories\Recompense\RecompenseRepoInterface;

class RecompenseController extends Controller
{
    protected $recompense;

    public function __construct(
        RecompenseRepoInterface $recompense
    ) {
        $this->recompense = $recompense;
    }

    /**
     * View ds thưởng phạt
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //Lấy data ds thưởng phạt
        $data = $this->recompense->getList();

        return view('shift::recompense.index', [
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

        return [];
    }

    /**
     * Ajax filter, phân trang mẫu lương
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search'
        ]);

        //Lấy data ds thưởng phạt
        $data = $this->recompense->getList($request->all());

        return view('shift::recompense.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Show pop thêm thưởng phạt
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopCreateAction()
    {
        $html = \View::make('shift::recompense.create')->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm thưởng phạt
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $this->recompense->store($request->all());

        return response()->json($data);
    }

    /**
     * Show pop chỉnh sửa thưởng phạt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopEditAction(Request $request)
    {
        //Lấy data chỉnh sửa
        $data = $this->recompense->getDataEdit($request->recompense_id);

        $html = \View::make('shift::recompense.edit', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa thưởng phạt
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->recompense->update($request->all());

        return response()->json($data);
    }

    /**
     * Xoá thưởng phạt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->recompense->destroy($request->all());

        return response()->json($data);
    }

    /**
     * Cập nhật nhanh trạng thái
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $data = $this->recompense->changeStatus($request->all());

        return response()->json($data);
    }
}