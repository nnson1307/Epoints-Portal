<?php


namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Modules\Api\Models\AdminServiceBrandTable;
use Illuminate\Console\Scheduling\Schedule;

use Modules\Api\Repositories\ServiceBrand\ServiceBrandRepositoryInterface;

class AdminServiceBrandController extends Controller
{
    protected $serviceBrand;
    public function __construct(
        ServiceBrandRepositoryInterface $serviceBrand
    ) {
        $this->serviceBrand = $serviceBrand;
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeService(Request $request)
    {
        try {
            $data = $request->all();

            $store = $this->serviceBrand->createServiceBrand($data);

            return $this->responseJson(CODE_SUCCESS, null, 1);
        } catch (\Exception $exception) {
            return $this->responseJson(CODE_SUCCESS, null, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     */
    public function deleteService(Request $request)
    {
        try {
            $data = $request->all();
            $delete = $this->serviceBrand->deleteServiceBrand($data);
            return $this->responseJson(CODE_SUCCESS, null, 1);
        } catch (\Exception $exception) {
            return $this->responseJson(CODE_SUCCESS, null, $exception->getMessage());
        }
    }
}
