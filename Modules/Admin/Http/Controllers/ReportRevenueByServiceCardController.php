<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/8/2019
 * Time: 4:08 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;

class ReportRevenueByServiceCardController extends Controller
{
    protected $branches;
    protected $orderDetail;
    protected $serviceCard;

    public function __construct(
        BranchRepositoryInterface $branch,
        OrderDetailRepositoryInterface $orderDetail,
        ServiceCardRepositoryInterface $serviceCard
    )
    {
        $this->branches = $branch;
        $this->orderDetail = $orderDetail;
        $this->serviceCard = $serviceCard;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $serviceCard = $this->serviceCard->getOption();
        return view('admin::report.report-revenue.report-revenue-by-service-card', [
            'branch' => $branch,
            'serviceCard' => $serviceCard,
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
        $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'service_card');
        $serviceCardOption = $this->serviceCard->getOption();
        $arrayServiceCard = [];
        $arrayNameServiceCard = [];
        $seriesData = [];

        foreach ($serviceCardOption as $key => $value) {
            $arrayServiceCard[$key] = 0;
        }

        foreach ($arrayServiceCard as $key2 => $val2) {
            foreach ($dataSelect as $key3 => $val3) {
                if ($val3['object_id'] == $key2 && in_array($val3['process_status'], ["paysuccess", "pay-half"])) {
                    $arrayServiceCard[$key2] += $dataSelect[$key3]['amount'];
                }
            }
        }
        arsort($arrayServiceCard);

        $totalChart = 0;
        foreach ($arrayServiceCard as $key4 => $value4) {
            $arrayNameServiceCard[] = $this->serviceCard->getItemDetail($key4)->name;
            $seriesData[] = $value4;
            $totalChart += $value4;
        }
        return response()->json([
            'list' => $arrayNameServiceCard,
            'seriesData' => $seriesData,
            'totalChart' => $totalChart,
            'countList' => count($seriesData)
        ]);

    }

    public function filterAction(Request $request)
    {
        $time = $request->time;
        $branch = $request->branch;
        $serviceCard = null;
        $numberServiceCard = $request->numberServiceCard;

        $serviceCardOption = $this->serviceCard->getOption();
        $arrayServiceCard = [];
        $arrayNameServiceCard = [];
        $seriesData = [];
        $totalChart = 0;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        if ($time != null && $branch != null && $numberServiceCard == null) {
            //1. Từ ngày đến ngày và chi nhánh.

            //Lấy dữ liệu từ ngày đến ngày, kiểu đối tượng và chi nhánh.
            $dataSelect = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service_card', $branch, 'notProcessStatus');

            foreach ($serviceCardOption as $key => $value) {
                $arrayServiceCard[$key] = 0;
            }

            foreach ($arrayServiceCard as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayServiceCard[$key2] += $dataSelect[$key3]['amount'];
                    }
                }
            }
            arsort($arrayServiceCard);
            $totalChart = 0;
            foreach ($arrayServiceCard as $key4 => $value4) {
                $arrayNameServiceCard[] = $this->serviceCard->getItemDetail($key4)->name;
                $seriesData[] = $value4;
                $totalChart += $value4;
            }
            return response()->json([
                'list' => $arrayNameServiceCard,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        } else if ($time != null && $branch == null && $numberServiceCard == null) {
            //2. Từ ngày đến ngày.

            //Lấy dữ liệu thẻ dịch vụ từ ngày đến ngày.
            $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'service_card');

            //Mảng có key là id dịch vụ và giá trị mặc định là 0;
            foreach ($serviceCardOption as $key => $value) {
                $arrayServiceCard[$key] = 0;
            }

            foreach ($arrayServiceCard as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    //Gán giá trị cho thẻ dịch vụ.
                    if ($val3['object_id'] == $key2 && in_array($val3['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayServiceCard[$key2] += $val3['amount'];
                    }
                }
            }

            //Sắp xếp số tiền của mảng theo chiều giảm dần.
            arsort($arrayServiceCard);
            foreach ($arrayServiceCard as $key4 => $value4) {
                $arrayNameServiceCard[] = $this->serviceCard->getItemDetail($key4)->name;
                $seriesData[] = $value4;
                $totalChart += $value4;
            }
            return response()->json([
                'list' => $arrayNameServiceCard,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        } else if ($time != null && $branch == null && $numberServiceCard != null) {
            //3. Từ ngày đến ngày và số thẻ.

            //Lấy dữ liệu thẻ dịch vụ từ ngày đến ngày.
            $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'service_card');

            //Mảng có key là id dịch vụ và giá trị mặc định là 0;
            foreach ($serviceCardOption as $key => $value) {
                $arrayServiceCard[$key] = 0;
            }

            foreach ($arrayServiceCard as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    //Gán giá trị cho thẻ dịch vụ.
                    if ($val3['object_id'] == $key2 && in_array($val3['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayServiceCard[$key2] += $val3['amount'];
                    }
                }
            }

            //Sắp xếp số tiền của mảng theo chiều giảm dần.
            arsort($arrayServiceCard);
            $count = 0;
            foreach ($arrayServiceCard as $key4 => $value4) {
                $count++;
                if ($count <= $numberServiceCard) {
                    $arrayNameServiceCard[] = $this->serviceCard->getItemDetail($key4)->name;
                    $seriesData[] = $value4;
                    $totalChart += $value4;
                }
            }
            return response()->json([
                'list' => $arrayNameServiceCard,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        } else if ($time != null && $branch != null && $numberServiceCard != null) {
            //4. Từ ngày đến ngày và chi nhánh.

            //Lấy dữ liệu từ ngày đến ngày, kiểu đối tượng và chi nhánh.
            $dataSelect = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service_card', $branch, 'notProcessStatus');

            foreach ($serviceCardOption as $key => $value) {
                $arrayServiceCard[$key] = 0;
            }

            foreach ($arrayServiceCard as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayServiceCard[$key2] += $dataSelect[$key3]['amount'];
                    }
                }
            }
            arsort($arrayServiceCard);
            $totalChart = 0;
            $count = 0;
            foreach ($arrayServiceCard as $key4 => $value4) {
                $count++;
                if ($count <= $numberServiceCard) {
                    $arrayNameServiceCard[] = $this->serviceCard->getItemDetail($key4)->name;
                    $seriesData[] = $value4;
                    $totalChart += $value4;
                }
            }
            return response()->json([
                'list' => $arrayNameServiceCard,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        }
    }
}