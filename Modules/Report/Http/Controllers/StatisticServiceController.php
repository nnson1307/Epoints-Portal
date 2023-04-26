<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\StatisticService\StatisticServiceRepoInterface;

class StatisticServiceController extends Controller
{
    protected $statisticService;
    public function __construct(StatisticServiceRepoInterface $statisticService)
    {
        $this->statisticService = $statisticService;
    }

    /**
     * view index
     */
    public function indexAction()
    {
        $data = $this->statisticService->dataViewIndex();
        return view('report::statistics.by-service.index', [
            'optionService' => $data['optionService']
        ]);
    }

    /**
     * filter theo ngày, thẻ dịch vụ
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $input = $request->all();
        return $this->statisticService->filterAction($input);
    }
    /**
     * Danh sách chi tiết của chart by service
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->statisticService->listDetail($request->all());

        return view('report::statistics.by-service.list-detail', [
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
        return $this->statisticService->exportExcelTotal($request->all());
    }

    /**
     * Export detail list service
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetailAction(Request $request)
    {
        return $this->statisticService->exportExcelDetail($request->all());
    }
}