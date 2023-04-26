<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\ConfigTimeResetRank\ConfigTimeResetRankRepoInterface;

class ConfigTimeResetRankController extends Controller
{
    protected $configTimeResetRank;

    public function __construct(
        ConfigTimeResetRankRepoInterface $configTimeResetRank
    ) {
        $this->configTimeResetRank = $configTimeResetRank;
    }

    /**
     * Page Config Time Reset Rank
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction()
    {
        $list = $this->configTimeResetRank->list();
        return view('admin::config-time-reset-rank.index', [
            'LIST' => $list,
            'FILTER' => $this->filters()
        ]);
    }

    //Filter
    protected function filters()
    {
        return [

        ];
    }

    /**
     * Ajax danh sách Config Time Reset Rank
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'is_actived', 'search']);
        $list = $this->configTimeResetRank->list($filter);
        return view('admin::branch.list', [
            'LIST' => $list,
            'page' => $filter['page']
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAction(Request $request)
    {
        $time_reset_rank = $this->configTimeResetRank->getItem($request->id);
        $view = \View::make('admin::config-time-reset-rank.pop.edit', [
            'id' => $request->id,
            'item' => $time_reset_rank
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    public function submitEditAction(Request $request)
    {
        $param = $request->all();
        return $this->configTimeResetRank->edit($param);
    }

}