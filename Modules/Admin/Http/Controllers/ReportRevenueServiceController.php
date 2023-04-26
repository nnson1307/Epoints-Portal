<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/5/2019
 * Time: 1:54 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;

class ReportRevenueServiceController extends Controller
{
    protected $branches;
    protected $service;
    protected $order;
    protected $serviceCategory;
    protected $orderDetail;
    public function __construct(
        BranchRepositoryInterface $branch,
        ServiceRepositoryInterface $service,
        OrderRepositoryInterface $order,
        ServiceCategoryRepositoryInterface $serviceCategory,
        OrderDetailRepositoryInterface $orderDetail
    )
    {
        $this->branches = $branch;
        $this->service = $service;
        $this->order = $order;
        $this->serviceCategory = $serviceCategory;
        $this->orderDetail = $orderDetail;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $service = $this->service->getServiceOption();
        return view('admin::report.report-revenue.report-revenue-service', [
            'branch' => $branch,
            'service' => $service,
        ]);
    }
    public function chartIndex(){
        $data=$this->orderDetail->fetchValueAllServiceByYear(date("Y"), 'service');
        $service=$this->service->getServiceOption();
        dd($service);
        $arrayNameService=[];
        $arrayServiceResult=[];
//        foreach ()
    }
}