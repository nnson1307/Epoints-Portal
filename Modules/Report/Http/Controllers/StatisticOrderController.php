<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\StatisticOrder\StatisticOrderRepoInterface;

class StatisticOrderController extends Controller
{
    protected $statisticOrder;
    public function __construct(StatisticOrderRepoInterface $statisticOrder)
    {
        $this->statisticOrder = $statisticOrder;
    }

    /**
     * View thống kê đơn hàng
     */
    public function indexAction()
    {
        $data = $this->statisticOrder->dataViewIndex();
        return view('report::statistics.order.index', [
            'branch' => $data['optionBranch']
        ]);
    }

    /**
     * Thống kê đơn hàng theo filter
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $input = $request->all();
        return $this->statisticOrder->filterAction($input);
    }
    /**
     * Danh sách chi tiết của chart by customer appointment
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->statisticOrder->listDetail($request->all());

        return view('report::statistics.order.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail customer appointment
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->statisticOrder->exportExcelTotal($request->all());
    }

    /**
     * Export detail list customer appointment
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetailAction(Request $request)
    {
        return $this->statisticOrder->exportExcelDetail($request->all());
    }
}