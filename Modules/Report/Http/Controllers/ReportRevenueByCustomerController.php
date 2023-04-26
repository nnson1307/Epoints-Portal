<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\RevenueByCustomer\RevenueByCustomerRepoInterface;

class ReportRevenueByCustomerController extends Controller
{
    protected $revenueByCustomer;
    public function __construct(RevenueByCustomerRepoInterface $revenueByCustomer)
    {
        $this->revenueByCustomer = $revenueByCustomer;
    }

    /**
     * View index
     *
     * @return array
     */
    public function indexAction()
    {
        $data = $this->revenueByCustomer->dataViewIndex();
        return view('report::revenue-by-customer.index', [
            'branch' => $data['optionBranch'],
            'customer' => $data['optionCustomer']
        ]);
    }

    /**
     * filter time, branch, number customer
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->revenueByCustomer->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart by branch
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->revenueByCustomer->listDetail($request->all());

        return view('report::revenue-by-customer.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->revenueByCustomer->exportExcelTotal($request->all());
    }

    /**
     * Export detail list branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->revenueByCustomer->exportExcelDetail($request->all());
    }
}