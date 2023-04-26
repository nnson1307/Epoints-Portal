<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\RevenueBySurchargeService\RevenueBySurchargeServiceRepoInterface;

class ReportRevenueBySurchargeServiceController extends Controller
{
    protected $revenueBySurService;
    public function __construct(RevenueBySurchargeServiceRepoInterface $revenueBySurService)
    {
        $this->revenueBySurService = $revenueBySurService;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $data = $this->revenueBySurService->dataViewIndex();
        return view('report::revenue-by-surcharge-service.index', [
            'branch' => $data['optionBranch'],
            'surchargeService' => $data['optionSurchargeService'],
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
        return $this->revenueBySurService->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart by revenue By SurService
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->revenueBySurService->listDetail($request->all());

        return view('report::revenue-by-surcharge-service.list-detail', [
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
        return $this->revenueBySurService->exportExcelTotal($request->all());
    }

    /**
     * Export detail list service
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->revenueBySurService->exportExcelDetail($request->all());
    }
}