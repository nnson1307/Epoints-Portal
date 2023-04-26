<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\StatisticCustomerAppointment\StatisticCustomerAppointmentRepoInterface;

class StatisticCustomerAppointmentController extends Controller
{
    protected $statisticCusAppointment;
    public function __construct(StatisticCustomerAppointmentRepoInterface $statisticCusAppointment)
    {
        $this->statisticCusAppointment = $statisticCusAppointment;
    }

    public function indexAction()
    {
        $data = $this->statisticCusAppointment->dataViewIndex();
        return view('report::statistics.customer-appointment.index', [
            'optionBranch' => $data['optionBranch']
        ]);
    }

    public function filterAction(Request $request)
    {
        $input = $request->all();
        return $this->statisticCusAppointment->filterAction($input);
    }
    /**
     * Danh sách chi tiết của chart by customer appointment
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->statisticCusAppointment->listDetail($request->all());

        return view('report::statistics.customer-appointment.list-detail', [
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
        return $this->statisticCusAppointment->exportExcelTotal($request->all());
    }

    /**
     * Export detail list customer appointment
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetailAction(Request $request)
    {
        return $this->statisticCusAppointment->exportExcelDetail($request->all());
    }
}