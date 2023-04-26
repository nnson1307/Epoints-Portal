<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/8/2019
 * Time: 1:51 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;

class ReportRevenueByProductController extends Controller
{
    protected $branches;
    protected $orderDetail;
    protected $productChild;

    public function __construct(
        BranchRepositoryInterface $branch,
        OrderDetailRepositoryInterface $orderDetail,
        ProductChildRepositoryInterface $productChild
    )
    {
        $this->branches = $branch;
        $this->orderDetail = $orderDetail;
        $this->productChild = $productChild;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $productChild = $this->productChild->getProductChildOptionIdName();
        return view('admin::report.report-revenue.report-revenue-by-product', [
            'branch' => $branch,
            'productChild' => $productChild,
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
        $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'product');

        $productChild = $this->productChild->getProductChildOptionIdName();

        $arrayProductChild = [];
        $arrayNameProductChild = [];
        $seriesData = [];
        foreach ($productChild as $key => $value) {
            $arrayProductChild[$key] = 0;
        }
        foreach ($arrayProductChild as $key2 => $val2) {
            foreach ($dataSelect as $key3 => $val3) {
                if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                    $arrayProductChild[$key2] += $dataSelect[$key3]['amount'];
                }
            }
        }
        arsort($arrayProductChild);

        $totalChart = 0;
        foreach ($arrayProductChild as $key4 => $value4) {
            $arrayNameProductChild[] = $this->productChild->getItem($key4)->product_child_name;
            $seriesData[] = $value4;
            $totalChart += $value4;
        }

        return response()->json([
            'list' => $arrayNameProductChild,
            'seriesData' => $seriesData,
            'totalChart' => $totalChart,
            'countList' => count($arrayProductChild)
        ]);
    }

    public function filterAction(Request $request)
    {
        $time = $request->time;
        $branch = $request->branch;
        $productChild = null;
        $numberProduct = $request->numberProduct;
        $productChildOption = $this->productChild->getProductChildOptionIdName();
        $arrayProductChild = [];
        $arrayNameProductChild = [];
        $seriesData = [];
        $totalChart = 0;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time != null && $branch != null && $numberProduct == null) {
            //1. Từ ngày đến ngày và chi nhánh.

            //Lấy dữ liệu từ ngày đến ngày, kiểu sản phẩm và chi nhánh.
            $dataSelect = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'product', $branch, 'notProcessStatus');
            foreach ($productChildOption as $key => $value) {
                $arrayProductChild[$key] = 0;
            }

            foreach ($arrayProductChild as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayProductChild[$key2] += $dataSelect[$key3]['amount'];
                    }
                }
            }
            arsort($arrayProductChild);
            $totalChart = 0;
            foreach ($arrayProductChild as $key4 => $value4) {
                $arrayNameProductChild[] = $this->productChild->getItem($key4)->product_child_name;
                $seriesData[] = $value4;
                $totalChart += $value4;
            }
            return response()->json([
                'list' => $arrayNameProductChild,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($arrayProductChild)
            ]);
        } else if ($time != null && $branch == null && $numberProduct == null) {
            //2. Từ ngày đến ngày.

            $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'product');

            //Mảng có key là id dịch vụ và giá trị mặc định là 0;
            foreach ($productChildOption as $key => $value) {
                $arrayProductChild[$key] = 0;
            }
            foreach ($arrayProductChild as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    //Gán giá trị cho sản phẩm.
                    if ($val3['object_id'] == $key2 && in_array($val3['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayProductChild[$key2] += $val3['amount'];
                    }
                }
            }
            //Sắp xếp số tiền của mảng theo chiều giảm dần.
            arsort($arrayProductChild);
            foreach ($arrayProductChild as $key4 => $value4) {
                $arrayNameProductChild[] = $this->productChild->getItem($key4)->product_child_name;
                $seriesData[] = $value4;
                $totalChart += $value4;
            }
            return response()->json([
                'list' => $arrayNameProductChild,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($arrayProductChild)
            ]);
        } else if ($time != null && $branch == null && $numberProduct != null) {
            //3. Từ ngày đến ngày và số lượng sp.

            $dataSelect = $this->orderDetail->getValueByDateAndObjectType($startTime, $endTime, 'product');

            //Mảng có key là id dịch vụ và giá trị mặc định là 0;
            foreach ($productChildOption as $key => $value) {
                $arrayProductChild[$key] = 0;
            }
            foreach ($arrayProductChild as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    //Gán giá trị cho sản phẩm.
                    if ($val3['object_id'] == $key2 && in_array($val3['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayProductChild[$key2] += $val3['amount'];
                    }
                }
            }
            //Sắp xếp số tiền của mảng theo chiều giảm dần.
            arsort($arrayProductChild);
            $count = 0;
            foreach ($arrayProductChild as $key4 => $value4) {
                $count++;
                if ($count <= $numberProduct) {
                    $arrayNameProductChild[] = $this->productChild->getItem($key4)->product_child_name;
                    $seriesData[] = $value4;
                    $totalChart += $value4;
                }
            }
            return response()->json([
                'list' => $arrayNameProductChild,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        } else if ($time != null && $branch != null && $numberProduct != null) {
            //4. Từ ngày đến ngày và chi nhánh và số lượng sp.

            //Lấy dữ liệu từ ngày đến ngày, kiểu sản phẩm và chi nhánh.
            $dataSelect = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'product', $branch, 'notProcessStatus');
            foreach ($productChildOption as $key => $value) {
                $arrayProductChild[$key] = 0;
            }

            foreach ($arrayProductChild as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    if ($dataSelect[$key3]['object_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayProductChild[$key2] += $dataSelect[$key3]['amount'];
                    }
                }
            }
            arsort($arrayProductChild);
            $totalChart = 0;
            $count = 0;
            foreach ($arrayProductChild as $key4 => $value4) {
                $count++;
                if ($count <= $numberProduct) {
                    $arrayNameProductChild[] = $this->productChild->getItem($key4)->product_child_name;
                    $seriesData[] = $value4;
                    $totalChart += $value4;
                }
            }
            return response()->json([
                'list' => $arrayNameProductChild,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        }
    }
}