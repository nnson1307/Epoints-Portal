<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\Report\Models\OrderCommissionTable;
use Modules\Report\Repository\DealCommission\DealCommissionRepoInterface;

class ReportDealCommissionController extends Controller
{
    protected $dealCommission;
    public function __construct(DealCommissionRepoInterface $dealCommission)
    {
        $this->dealCommission = $dealCommission;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $mCustomerDeal = new CustomerDealTable();
        $optionDeal = $mCustomerDeal->getOptionCpoDeal();
        return view('report::deal-commission.index', [
            'deal' => $optionDeal
        ]);
    }

    /**
     * filter time, number staff cho biểu đồ
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->dealCommission->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart hoa hồng cho deal
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->dealCommission->listDetail($request->all());

        return view('report::deal-commission.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }
    /**
     * Export excel chi tiết hoa hồng cho deal
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->dealCommission->exportDetail($request->all());
    }

    /**
     * Export excel tổng hoa hồng cho deal
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotal(Request $request)
    {
        return $this->dealCommission->exportTotal($request->all());
    }
}