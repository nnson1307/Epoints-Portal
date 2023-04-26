<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/9/2019
 * Time: 11:35 AM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;

class ReportGrowthByServiceController extends Controller
{
    protected $service;
    protected $order;
    protected $orderDetail;
    protected $branch;
    protected $serviceCategory;

    public function __construct(
        ServiceRepositoryInterface $service,
        OrderRepositoryInterface $order,
        OrderDetailRepositoryInterface $orderDetail,
        BranchRepositoryInterface $branch,
        ServiceCategoryRepositoryInterface $serviceCategory

    )
    {
        $this->service = $service;
        $this->order = $order;
        $this->orderDetail = $orderDetail;
        $this->branch = $branch;
        $this->serviceCategory = $serviceCategory;
    }

    public function indexAction()
    {
        $service = $this->service->getServiceOption();
        return view('admin::report.report-growth.report-growth-by-service', [
            'service' => $service
        ]);
    }

    public function chartIndexAction(Request $request)
    {
        $year = date('Y');

        $time = $request->time;
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        //Danh sách dịch vụ
        $service = $this->service->getServiceOption();
        $arrayService = [];
        foreach ($service as $key => $value) {
            $arrayService[$key] = 0;
        }
        $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('service', null, $startTime, $endTime);
        foreach ($arrayService as $key => $val) {
            foreach ($dataSelect as $key1 => $val1) {
                if ($val1['object_id'] == $key) {
                    $arrayService[$key] += $val1['quantity'];
                }
            }
        }
        //Sắp xếp mảng theo giá trị giảm dần.
        arsort($arrayService);
        $arrayServiceName = [];
        $arrayServiceQuantity = [];

        foreach ($arrayService as $key => $value) {
            $ser = $this->service->getItem($key);
            if ($ser != null) {
                $arrayServiceName[] = $ser->service_name;
            } else {
                $arrayServiceName[] = '';
            }
            $arrayServiceQuantity[] = $value;
        }

        //Lấy dữ liệu cho biểu đồ khách hàng.
        $totalQuantity = array_sum($arrayServiceQuantity);
        $quantityOddCustomer = 0;
        $arrayBranch = [];
        foreach ($dataSelect as $key => $value) {
            if ($value['customer_id'] == 1) {
                $quantityOddCustomer += $value['quantity'];
            }
            if (!in_array($value['branch_id'], $arrayBranch)) {
                $arrayBranch[$value['branch_id']] = 0;
            }
        }
        foreach ($arrayBranch as $key => $value) {
            foreach ($dataSelect as $key2 => $value2) {
                if ($key == $value2['branch_id']) {
                    $arrayBranch[$key] += $value2['quantity'];
                }
            }
        }
        $arrayBranchChar = [];
        foreach ($arrayBranch as $key => $value) {
            $branchDetail = $this->branch->getItem($key);
            if ($branchDetail != null) {
                $arrayBranchChar[] = ['name' => $branchDetail->branch_name, 'value' => $value];
            }
        }

        //Lấy dữ liệu cho nhóm dịch vụ
        $arrayServiceCategory = [];
        foreach ($arrayService as $key => $value) {
            $serviceDetail = $this->service->getItem($key);
            if ($serviceDetail != null) {
                $arrayServiceCategory[] = $serviceDetail->service_category_id;
            } else {
                $arrayServiceCategory[] = '';
            }

        }
        //Gộp các nhóm dịch vụ giống nhau.
        $arrayServiceCategoryValue = [];
        $arrayServiceCategory = array_unique($arrayServiceCategory);
        foreach ($arrayServiceCategory as $key => $value) {
            $arrayServiceCategoryValue[$value] = 0;
        }
        foreach ($arrayServiceCategory as $key => $value) {
            foreach ($arrayService as $key1 => $value2) {
                $categoryId = $this->service->getItem($key1);
                if ($categoryId != null) {
                    if ($value == $categoryId->service_category_id) {
                        $arrayServiceCategoryValue[$value] += $value2;
                    }
                }
            }
        }
        $arrayCategory = [];

        foreach ($arrayServiceCategoryValue as $key => $value) {
            $categoryDetail = $this->serviceCategory->getItem($key);
            if ($categoryDetail != null) {
                $arrayCategory[] = ['name' => $categoryDetail->name, 'value' => $value];
            }
        }
        //Biểu đồ thống kê số lượng sử dụng dịch vụ.
        $dataQuantity = [];
        $dataQuantity[] = ['', ''];
        $count=0;
        foreach ($arrayServiceName as $key => $value) {
            $dataQuantity[] = [$value, $arrayServiceQuantity[$key]];
            $count++;
            if ($count==7){
                break;
            }
        }
        if (count($arrayServiceName) > 7) {
            $temp = 0;
            foreach ($arrayServiceQuantity as $key => $value) {
                if ($key > 7) {
                    $temp += $value;
                }
            }
            $dataQuantity[] = ['Khác', $temp];
        }

        //Biểu đồ nhóm dịch vụ
        $dataServiceGroup = [];
        $dataServiceGroup[] = ['', ''];

        foreach ($arrayCategory as $item) {
            $dataServiceGroup[] = [$item['name'], $item['value']];
        }

        //Biểu đồ chi nhánh
        $dataBranchChart = [];
        $dataBranchChart[] = ['', ''];
        foreach ($arrayBranchChar as $item) {
            $dataBranchChart[] = [$item['name'], $item['value']];
        }

        return response()->json([
            'totalQuantity' => $totalQuantity,
            'quantityOddCustomer' => $quantityOddCustomer,
            'dataBranchChart' => $dataBranchChart,
            'dataQuantity' => $dataQuantity,
            'dataServiceGroup' => $dataServiceGroup,
        ]);


    }

    public function filterAction(Request $request)
    {
        $year = date('Y');
        $service = $request->service;
        $time = $request->time;
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time != null && $service == null) {
            //Từ ngày đến ngày.
            $serviceOption = $this->service->getServiceOption();
            $arrayService = [];
            foreach ($serviceOption as $key => $value) {
                $arrayService[$key] = 0;
            }

            $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('service', null, $startTime, $endTime);
            foreach ($arrayService as $key => $val) {
                foreach ($dataSelect as $key1 => $val1) {
                    if ($val1['object_id'] == $key) {
                        $arrayService[$key] += $val1['quantity'];
                    }
                }
            }

            //Sắp xếp mảng theo giá trị giảm dần.
            arsort($arrayService);
            $arrayServiceName = [];
            $arrayServiceQuantity = [];

            foreach ($arrayService as $key => $value) {
                $serviceDetail = $this->service->getItem($key);
                if ($serviceDetail != null) {
                    $arrayServiceName[] = $serviceDetail->service_name;
                    $arrayServiceQuantity[] = $value;
                }
            }
            //Lấy dữ liệu cho biểu đồ khách hàng.
            $totalQuantity = array_sum($arrayServiceQuantity);
            $quantityOddCustomer = 0;
            foreach ($dataSelect as $key => $value) {
                if ($value['customer_id'] == 1) {
                    $quantityOddCustomer += $value['quantity'];
                }
            }
            //Lấy dữ liệu cho nhóm dịch vụ
            $arrayServiceCategory = [];
            foreach ($arrayService as $key => $value) {
                if ($value != 0 && $this->service->getItem($key) != null) {
                    $arrayServiceCategory[] = $this->service->getItem($key)->service_category_id;
                }
            }

            //Loại bỏ các nhóm dịch vụ giống nhau.
            $arrayServiceCategoryValue = [];
            $arrayServiceCategory = array_unique($arrayServiceCategory);
            foreach ($arrayServiceCategory as $key => $value) {
                $arrayServiceCategoryValue[$value] = 0;
            }
            foreach ($arrayServiceCategory as $key => $value) {
                foreach ($arrayService as $key1 => $value2) {
                    $categoryDetail = $this->service->getItem($key1);
                    if ($categoryDetail != null) {
                        $categoryId = $categoryDetail->service_category_id;
                        if ($value == $categoryId) {
                            $arrayServiceCategoryValue[$value] += $value2;
                        }
                    }
                }
            }
            $arrayCategory = [];

            foreach ($arrayServiceCategoryValue as $key => $value) {
                if ($this->serviceCategory->getItem($key) != null) {
                    $arrayCategory[] = ['name' => $this->serviceCategory->getItem($key)->name, 'value' => $value];
                }
            }

            //Lấy dữ liệu cho biểu đồ chi nhánh.
            $arrayBranch = [];
            foreach ($dataSelect as $key => $value) {
                if (!in_array($value['branch_id'], $arrayBranch)) {
                    $arrayBranch[$value['branch_id']] = 0;
                }
            }

            foreach ($arrayBranch as $key => $value) {
                foreach ($dataSelect as $key2 => $value2) {
                    if ($key == $value2['branch_id']) {
                        $arrayBranch[$key] += $value2['quantity'];
                    }
                }
            }
            $arrayBranchChar = [];
            foreach ($arrayBranch as $key => $value) {
                $branchDetail = $this->branch->getItem($key);
                if ($branchDetail != null) {
                    $arrayBranchChar[] = ['name' => $branchDetail->branch_name, 'value' => $value];
                }
            }

            //Biểu đồ thống kê số lượng sử dụng dịch vụ.
            $dataQuantity = [];
            $dataQuantity[] = ['', ''];
            $count=0;
            foreach ($arrayServiceName as $key => $value) {
                $dataQuantity[] = [$value, $arrayServiceQuantity[$key]];
                $count++;
                if ($count==8){
                    break;
                }
            }
            if (count($arrayServiceName) > 8) {
                $temp = 0;
                foreach ($arrayServiceQuantity as $key => $value) {
                    if ($key > 7) {
                        $temp += $value;
                    }
                }
                $dataQuantity[] = ['Khác', $temp];
            }

            //Biểu đồ nhóm dịch vụ
            $dataServiceGroup = [];
            $dataServiceGroup[] = ['', ''];

            foreach ($arrayCategory as $item) {
                $dataServiceGroup[] = [$item['name'], $item['value']];
            }

            //Biểu đồ chi nhánh
            $dataBranchChart = [];
            $dataBranchChart[] = ['', ''];

            foreach ($arrayBranchChar as $item) {
                $dataBranchChart[] = [$item['name'], $item['value']];
            }

            return response()->json([
                'totalQuantity' => $totalQuantity,
                'quantityOddCustomer' => $quantityOddCustomer,
                'dataQuantity'=>$dataQuantity,
                'dataServiceGroup'=>$dataServiceGroup,
                'dataBranchChart'=>$dataBranchChart,
            ]);
        } else if ($time != null && $service != null) {
            //Từ ngày đến ngày và dịch vụ.
            $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('service', $service, $startTime, $endTime);

            //Số ngày.
            $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            $day = [];
            $valueDay = [];
            $arrayDayValue = [];

            for ($i = 0; $i < $datediff; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arrayDayValue[$tomorrow] = 0;
            }

            foreach ($arrayDayValue as $key => $value) {
                foreach ($dataSelect as $key2 => $value2) {
                    $days = date('d/m/Y', strtotime($value2['created_at']));
                    if ($key == $days) {
                        $arrayDayValue[$key] += $value2['quantity'];
                    }
                }
            }
            foreach ($arrayDayValue as $key => $value) {
                $day[] = substr($key, 0, -5);
                $valueDay[] = $value;
            }

            //Lấy dữ liệu cho biểu đồ khách hàng.
            $quantityOddCustomer = 0;
            $quantityAllCustomer = 0;
            foreach ($dataSelect as $key => $value) {
                $quantityAllCustomer += $value['quantity'];
                if ($value['customer_id'] == 1) {
                    $quantityOddCustomer += $value['quantity'];
                }
            }
            //Lấy dữ liệu cho nhóm dịch vụ.
            $arrayServiceCategory = [];
            if ($this->service->getItem($service) != null) {
                $serviceCategoryId = $this->service->getItem($service)->service_category_id;
                if ($this->serviceCategory->getItem($serviceCategoryId) != null) {
                    $arrayServiceCategory[] = $this->serviceCategory->getItem($serviceCategoryId)->name;
                }
            }

            //Lấy dữ liệu cho biểu đồ chi nhánh.
            $arrayBranch = [];
            foreach ($dataSelect as $key => $value) {
                if (!in_array($value['branch_id'], $arrayBranch)) {
                    $arrayBranch[$value['branch_id']] = 0;
                }
            }
            foreach ($arrayBranch as $key => $value) {
                foreach ($dataSelect as $key2 => $value2) {
                    if ($key == $value2['branch_id']) {
                        $arrayBranch[$key] += $value2['quantity'];
                    }
                }
            }
            $arrayBranchChart = [];
            foreach ($arrayBranch as $key => $value) {
                $branchDetail = $this->branch->getItem($key);
                if ($branchDetail != null) {
                    $arrayBranchChart[] = ['name' => $branchDetail->branch_name, 'value' => $value];
                }
            }

            //Biểu đồ thống kê số lượng sử dụng dịch vụ.
            $dataQuantity = [];
            $dataQuantity[] = ['', ''];

            foreach ($day as $key => $value) {
                $dataQuantity[] = [$value, $valueDay[$key]];
            }

            //Biểu đồ nhóm dịch vụ
            $dataServiceGroup = [['', ''],[$arrayServiceCategory[0],array_sum($valueDay)]];

            //Biểu đồ chi nhánh
            $dataBranchChart = [];
            $dataBranchChart[] = ['', ''];

            foreach ($arrayBranchChart as $item) {
                $dataBranchChart[] = [$item['name'], $item['value']];
            }

            return response()->json([
                'quantityOddCustomer' => $quantityOddCustomer,
                'quantityAllCustomer' => $quantityAllCustomer,
                'dataBranchChart' => $dataBranchChart,
                'dataQuantity'=>$dataQuantity,
                'dataServiceGroup'=>$dataServiceGroup
            ]);
        }
    }
}