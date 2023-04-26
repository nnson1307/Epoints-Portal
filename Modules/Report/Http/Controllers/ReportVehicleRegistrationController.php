<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\VehicleRegistration\VehicleRegistrationRepoInterface;

class ReportVehicleRegistrationController extends Controller
{
    protected $vehicleRegistration;

    public function __construct(VehicleRegistrationRepoInterface $vehicleRegistration)
    {
        $this->vehicleRegistration = $vehicleRegistration;
    }

    /**
     * view index
     *
     * @return array
     */
    public function index()
    {
        return view('report::vehicle-registration-date.index');
    }

    /**
     * filter date
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $data = $this->vehicleRegistration->filterAction($request->all());
        return response()->json($data);
    }
}