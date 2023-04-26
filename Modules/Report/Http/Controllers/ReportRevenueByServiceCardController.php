<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\RevenueByServiceCard\RevenueByServiceCardRepoInterface;

class ReportRevenueByServiceCardController extends Controller
{
    protected $revenueByServiceCard;
    public function __construct(RevenueByServiceCardRepoInterface $revenueByServiceCard)
    {
        $this->revenueByServiceCard = $revenueByServiceCard;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $data = $this->revenueByServiceCard->dataViewIndex();
        return view('report::revenue-by-service-card.index', [
            'branch' => $data['optionBranch'],
            'serviceCard' => $data['optionServiceCard'],
        ]);
    }

    /**
     * filter time, branch, number service card
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->revenueByServiceCard->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart by service-card
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->revenueByServiceCard->listDetail($request->all());

        return view('report::revenue-by-service-card.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail service-card
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->revenueByServiceCard->exportExcelTotal($request->all());
    }

    /**
     * Export detail list service-card
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->revenueByServiceCard->exportExcelDetail($request->all());
    }
}