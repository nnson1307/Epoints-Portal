<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\RevenueByService\RevenueByServiceRepoInterface;

class ReportRevenueByServiceController extends Controller
{
    protected $revenueByService;
    public function __construct(RevenueByServiceRepoInterface $revenueByService)
    {
        $this->revenueByService = $revenueByService;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $data = $this->revenueByService->dataViewIndex();
        return view('report::revenue-by-service.index', [
            'branch' => $data['optionBranch'],
            'service' => $data['optionService'],
            'optionServiceCategories' => $data['optionServiceCategories']
        ]);
    }

    /**
     * View index serviece group
     */
    public function indexServiceGroupAction()
    {
        $data = $this->revenueByService->dataViewGroupIndex();
        return view('report::revenue-by-service-group.index', [
            'branch' => $data['optionBranch'],
            'service' => $data['optionServiceCategories']
        ]);
    }

    /**
     * filter time, branch, number service
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->revenueByService->filterAction($request->all());
    }

    /**
     * filter time, branch, number service
     *
     * @param Request $request
     * @return mixed
     */
    public function filterGroupAction(Request $request)
    {
        return $this->revenueByService->filterGroupAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart by service
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->revenueByService->listDetail($request->all());

        return view('report::revenue-by-service.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Danh sách chi tiết của chart by service categories
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailGroupAction(Request $request)
    {
        $data = $this->revenueByService->listDetailGroupAction($request->all());

        return view('report::revenue-by-service-group.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail service
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->revenueByService->exportExcelTotal($request->all());
    }

    /**
     * Export total list detail service
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalGroup(Request $request)
    {
        return $this->revenueByService->exportExcelTotalGroup($request->all());
    }

    /**
     * Export detail list service
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelGroupDetail(Request $request)
    {
        return $this->revenueByService->exportExcelGroupDetail($request->all());
    }

    /**
     * Export detail list service
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->revenueByService->exportExcelDetail($request->all());
    }
}