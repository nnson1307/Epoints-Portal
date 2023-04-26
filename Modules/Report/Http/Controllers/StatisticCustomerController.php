<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\StatisticCustomer\StatisticCustomerRepoInterface;

class StatisticCustomerController extends Controller
{
    protected $statisticCustomer;

    public function __construct(StatisticCustomerRepoInterface $statisticCustomer)
    {
        $this->statisticCustomer = $statisticCustomer;
    }

    public function indexAction()
    {
        $data = $this->statisticCustomer->dataViewIndex();
        return view('report::statistics.by-customer.index', [
            'branch' => $data['optionBranch']
        ]);
    }

    /**
     * filter
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $input = $request->all();
        return $this->statisticCustomer->filterAction($input);
    }
    /**
     * Danh sách chi tiết của chart by statistics customer
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->statisticCustomer->listDetail($request->all());

        return view('report::statistics.by-customer.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail statistics customer
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->statisticCustomer->exportExcelTotal($request->all());
    }

    /**
     * Export detail list statistics customer
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetailAction(Request $request)
    {
        return $this->statisticCustomer->exportExcelDetail($request->all());
    }
}