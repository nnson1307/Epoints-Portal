<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/7/2019
 * Time: 5:36 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;

class ReportRevenueByServiceController extends Controller
{
    protected $branches;
    protected $service;
    protected $order;
    protected $orderDetail;

    public function __construct(
        BranchRepositoryInterface $branch,
        ServiceRepositoryInterface $service,
        OrderRepositoryInterface $order,
        OrderDetailRepositoryInterface $orderDetail
    )
    {
        $this->branches = $branch;
        $this->service = $service;
        $this->order = $order;
        $this->orderDetail = $orderDetail;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $service = $this->service->getServiceOption();
        return view('admin::report.report-revenue.report-revenue-by-service', [
            'branch' => $branch,
            'service' => $service,
        ]);
    }

    public function chartIndexAction(Request $request)
    {
        $time = $request->time;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'service');

        $service = $this->service->getServiceOption();
        $arrayService = [];
        $arrayNameService = [];
        $seriesData = [];
        foreach ($service as $key => $value) {
            $arrayService[$key] = 0;
        }
        foreach ($arrayService as $key2 => $val2) {
            foreach ($dataSelect as $key3 => $val3) {
                if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                    $arrayService[$key2] += $dataSelect[$key3]['amount'];
                }
            }
        }
        arsort($arrayService);
        $totalChart = 0;
        foreach ($arrayService as $key4 => $value4) {
            $arrayNameService[] = $this->service->getItem($key4)->service_name;
            $seriesData[] = $value4;
            $totalChart += $value4;
        }
        return response()->json([
            'list' => $arrayNameService,
            'seriesData' => $seriesData,
            'totalChart' => $totalChart,
            'countList' => count($arrayService)
        ]);
    }

    public function filterAction(Request $request)
    {
        $time = $request->time;
        $branch = $request->branch;
        $numberService = $request->numberService;
        $service = null;
        $serviceOption = $this->service->getServiceOption();
        $arrayService = [];
        $arrayNameService = [];
        $seriesData = [];
        $totalChart = 0;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time != null && $branch != null && $numberService == null) {
            //1. Từ ngày đến ngày và chi nhánh.

            //Lấy dữ liệu từ ngày đến ngày, kiểu dịch vụ và chi nhánh.
            $dataSelect = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service', $branch, 'notProcessStatus');

            foreach ($serviceOption as $key => $value) {
                $arrayService[$key] = 0;
            }
            foreach ($arrayService as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayService[$key2] += $dataSelect[$key3]['amount'];
                    }
                }
            }
            arsort($arrayService);
            $totalChart = 0;
            foreach ($arrayService as $key4 => $value4) {
                $arrayNameService[] = $this->service->getItem($key4)->service_name;
                $seriesData[] = $value4;
                $totalChart += $value4;
            }
            return response()->json([
                'list' => $arrayNameService,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($arrayService)
            ]);
        } else if ($time != null && $branch == null && $numberService == null) {
            //2. Từ ngày đến ngày.

            $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'service');

            //Mảng có key là id dịch vụ và giá trị mặc định là 0;
            foreach ($serviceOption as $key => $value) {
                $arrayService[$key] = 0;
            }

            foreach ($arrayService as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    //Gán giá trị cho dịch vụ.
                    if ($val3['object_id'] == $key2 && in_array($val3['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayService[$key2] += $val3['amount'];
                    }
                }
            }
            //Sắp xếp số tiền của mảng theo chiều giảm dần.
            arsort($arrayService);
            foreach ($arrayService as $key4 => $value4) {
                $arrayNameService[] = $this->service->getItem($key4)->service_name;
                $seriesData[] = $value4;
                $totalChart += $value4;
            }
            return response()->json(['list' => $arrayNameService, 'seriesData' => $seriesData, 'totalChart' => $totalChart]);
        } else if ($time != null && $branch == null && $numberService != null) {
            //3. Từ ngày đến ngày.

            $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'service');

            //Mảng có key là id dịch vụ và giá trị mặc định là 0;
            foreach ($serviceOption as $key => $value) {
                $arrayService[$key] = 0;
            }

            foreach ($arrayService as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    //Gán giá trị cho dịch vụ.
                    if ($val3['object_id'] == $key2 && in_array($val3['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayService[$key2] += $val3['amount'];
                    }
                }
            }
            //Sắp xếp số tiền của mảng theo chiều giảm dần.
            arsort($arrayService);
            $count = 0;
            foreach ($arrayService as $key4 => $value4) {
                $count++;
                if ($count <= $numberService) {
                    $arrayNameService[] = $this->service->getItem($key4)->service_name;
                    $seriesData[] = $value4;
                    $totalChart += $value4;
                }
            }
            return response()->json([
                'list' => $arrayNameService,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        } else if ($time != null && $branch != null && $numberService != null) {
            //1. Từ ngày đến ngày và chi nhánh.

            //Lấy dữ liệu từ ngày đến ngày, kiểu dịch vụ và chi nhánh.
            $dataSelect = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service', $branch, 'notProcessStatus');

            foreach ($serviceOption as $key => $value) {
                $arrayService[$key] = 0;
            }
            foreach ($arrayService as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayService[$key2] += $dataSelect[$key3]['amount'];
                    }
                }
            }
            arsort($arrayService);
            $totalChart = 0;
            foreach ($arrayService as $key4 => $value4) {
                $arrayNameService[] = $this->service->getItem($key4)->service_name;
                $seriesData[] = $value4;
                $totalChart += $value4;
            }
            return response()->json([
                'list' => $arrayNameService,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        }

    }
}