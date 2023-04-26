<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\StatisticServiceCard\StatisticServiceCardRepoInterface;

class StatisticServiceCardController extends Controller
{
    protected $statisticServiceCard;
    public function __construct(StatisticServiceCardRepoInterface $statisticServiceCard)
    {
        $this->statisticServiceCard = $statisticServiceCard;
    }

    /**
     * view index
     */
    public function indexAction()
    {
        $data = $this->statisticServiceCard->dataViewIndex();
        return view('report::statistics.by-service-card.index', [
            'optionServiceCard' => $data['optionServiceCard']
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
        return $this->statisticServiceCard->filterAction($input);
    }
    /**
     * Danh sách chi tiết của chart by service card
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->statisticServiceCard->listDetail($request->all());

        return view('report::statistics.by-service-card.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail service card
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->statisticServiceCard->exportExcelTotal($request->all());
    }

    /**
     * Export detail list service card
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetailAction(Request $request)
    {
        return $this->statisticServiceCard->exportExcelDetail($request->all());
    }
}