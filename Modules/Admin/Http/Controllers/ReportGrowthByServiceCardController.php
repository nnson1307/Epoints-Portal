<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/15/2019
 * Time: 9:20 AM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardGroup\ServiceCardGroupRepositoryInterface;

class ReportGrowthByServiceCardController extends Controller
{
    protected $serviceCardGroup;
    protected $serviceCard;
    protected $orderDetail;
    protected $branch;

    public function __construct(
        ServiceCardGroupRepositoryInterface $serviceCardGroup,
        ServiceCardRepositoryInterface $serviceCard,
        OrderDetailRepositoryInterface $orderDetail,
        BranchRepositoryInterface $branch
    )
    {
        $this->serviceCardGroup = $serviceCardGroup;
        $this->serviceCard = $serviceCard;
        $this->orderDetail = $orderDetail;
        $this->branch = $branch;
    }

    public function indexAction()
    {
        $serviceCard = $this->serviceCard->getOption();
        return view('admin::report.report-growth.report-growth-by-service-card', [
            'serviceCard' => $serviceCard
        ]);
    }

    public function chartIndexAction(Request $request)
    {
        //Thống kê sử dụng thẻ dịch vụ trong năm hiện tại.
        $year = date('Y');
        $time = $request->time;
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        //Danh sách thẻ dịch vụ.
        $serviceCardOption = $this->serviceCard->getOption();
        $arrayServiceCard = [];
        foreach ($serviceCardOption as $key => $value) {
            $arrayServiceCard[$key] = 0;
        }
        $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('service_card', null, $startTime, $endTime);
        foreach ($arrayServiceCard as $key => $value) {
            foreach ($dataSelect as $key1 => $value1) {
                if ($value1['object_id'] == $key) {
                    $arrayServiceCard[$key] += $value1['quantity'];
                }
            }
        }
        //Sắp xếp mảng theo giá trị giảm dần.
        arsort($arrayServiceCard);
        $arrayServiceCardName = [];
        $arrayServiceCardQuantity = [];
        foreach ($arrayServiceCard as $key => $value) {
            $serviceCardDetail = $this->serviceCard->getItemDetail($key);
            if ($serviceCardDetail != null) {
                $arrayServiceCardName[] = $serviceCardDetail->name;
            } else {
                $arrayServiceCardName[] = '';
            }
            $arrayServiceCardQuantity[] = $value;
        }

        //Lấy dữ liệu cho biểu đồ khách hàng.
        $totalQuantity = array_sum($arrayServiceCardQuantity);
        $totalQuantityOddCustomer = 0;
        $arrayBranch = [];
        foreach ($dataSelect as $key => $value) {
            if ($value['customer_id'] == 1) {
                $totalQuantityOddCustomer += $value['quantity'];
            }
            if (!in_array($value['branch_id'], $arrayBranch)) {
                $arrayBranch[$value['branch_id']] = 0;
            }
        }

        //Lấy dữ liệu cho nhóm thẻ dịch vụ
        $arrayServiceCardGroup = [];
        foreach ($arrayServiceCard as $key => $value) {
            $serviceDetail = $this->serviceCard->getItemDetail($key);
            if ($serviceDetail != null) {
                $arrayServiceCardGroup[] = $serviceDetail->service_card_group_id;
            } else {
                $arrayServiceCardGroup[] = '';
            }
        }
        //Gộp các nhóm thẻ dịch vụ giống nhau.
        $arrayServiceCardCategoryValue = [];
        $arrayServiceCardGroupUnique = array_unique($arrayServiceCardGroup);

        foreach ($arrayServiceCardGroupUnique as $key => $value) {
            $arrayServiceCardCategoryValue[$value] = 0;
        }
        foreach ($arrayServiceCardGroupUnique as $key => $value) {
            if ($value != '') {
                foreach ($arrayServiceCard as $key1 => $value2) {
                    $categoryId = $this->serviceCard->getItemDetail($key1);
                    if ($categoryId != null) {
                        if ($value == $categoryId->service_card_group_id) {
                            $arrayServiceCardCategoryValue[$value] += $value2;
                        }
                    }
                }
            }
        }

        $arrayCategory = [];
        foreach ($arrayServiceCardCategoryValue as $key => $value) {
            $serviceCardGroupDetail = $this->serviceCardGroup->getItem($key);
            if ($serviceCardGroupDetail != null) {
                $arrayCategory[] = ['name' => $serviceCardGroupDetail['name'], 'value' => $value];
            }
        }
        //Biểu đồ chi nhánh
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

        //Biểu đồ thống kê số lượng sử dụng thẻ dịch vụ.
        $dataQuantity = [];
        $dataQuantity[] = ['', ''];
        $count=0;
        foreach ($arrayServiceCardName as $key => $value) {
            $dataQuantity[] = [$value, $arrayServiceCardQuantity[$key]];
            $count++;
            if ($count==8){
                break;
            }
        }
        if (count($arrayServiceCardName) > 8) {
            $temp = 0;
            foreach ($arrayServiceCardQuantity as $key => $value) {
                if ($key > 7) {
                    $temp += $value;
                }
            }
            $dataQuantity[] = ['Khác', $temp];
        }

        //Biểu đồ nhóm dịch vụ
        $dataServiceCardGroup = [];
        $dataServiceCardGroup[] = ['', ''];

        foreach ($arrayCategory as $item) {
            $dataServiceCardGroup[] = [$item['name'], $item['value']];
        }

        //Biểu đồ chi nhánh
        $dataBranchChart = [];
        $dataBranchChart[] = ['', ''];
        foreach ($arrayBranchChart as $item) {
            $dataBranchChart[] = [$item['name'], $item['value']];
        }

        return response()->json([
            'totalQuantity' => $totalQuantity,
            'totalQuantityOddCustomer' => $totalQuantityOddCustomer,
            'arrayCategory' => $arrayCategory,
            'arrayBranchChart' => $arrayBranchChart,
            'dataQuantity' => $dataQuantity,
            'dataServiceCardGroup'=>$dataServiceCardGroup,
            'dataBranchChart'=>$dataBranchChart,
        ]);

    }

    public function filterAction(Request $request)
    {
        $year = date('Y');
        $serviceCard = $request->serviceCard;
        $time = $request->time;
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        if ($time != null && $serviceCard == null) {
            //Từ ngày đến ngày.
            $serviceCardOption = $this->serviceCard->getOption();
            $arrayServiceCard = [];
            foreach ($serviceCardOption as $key => $value) {
                $arrayServiceCard[$key] = 0;
            }

            $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('service_card', null, $startTime, $endTime);
            foreach ($arrayServiceCard as $key => $val) {
                foreach ($dataSelect as $key1 => $val1) {
                    if ($val1['object_id'] == $key) {
                        $arrayServiceCard[$key] += $val1['quantity'];
                    }
                }
            }
            //Sắp xếp mảng theo giá trị giảm dần.
            arsort($arrayServiceCard);
            $arrayServiceCardName = [];
            $arrayServiceCardQuantity = [];

            foreach ($arrayServiceCard as $key => $value) {
                $serviceCardDetail = $this->serviceCard->getItemDetail($key);
                if ($serviceCardDetail != null) {
                    $arrayServiceCardName[] = $serviceCardDetail->name;
                    $arrayServiceCardQuantity[] = $value;
                }
            }

            //Lấy dữ liệu cho biểu đồ khách hàng.
            $totalQuantity = array_sum($arrayServiceCardQuantity);
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

            //Lấy dữ liệu cho nhóm thẻ dịch vụ
            $arrayServiceCardGroup = [];
            foreach ($arrayServiceCard as $key => $value) {
                $serviceDetail = $this->serviceCard->getItemDetail($key);
                if ($serviceDetail != null) {
                    $arrayServiceCardGroup[] = $serviceDetail->service_card_group_id;
                } else {
                    $arrayServiceCardGroup[] = '';
                }
            }
            //Gộp các nhóm thẻ dịch vụ giống nhau.
            $arrayServiceCardCategoryValue = [];
            $arrayServiceCardGroupUnique = array_unique($arrayServiceCardGroup);

            foreach ($arrayServiceCardGroupUnique as $key => $value) {
                $arrayServiceCardCategoryValue[$value] = 0;
            }
            foreach ($arrayServiceCardGroupUnique as $key => $value) {
                if ($value != '') {
                    foreach ($arrayServiceCard as $key1 => $value2) {
                        $categoryId = $this->serviceCard->getItemDetail($key1);
                        if ($categoryId != null) {
                            if ($value == $categoryId->service_card_group_id) {
                                $arrayServiceCardCategoryValue[$value] += $value2;
                            }
                        }
                    }
                }
            }

            $arrayCategory = [];
            foreach ($arrayServiceCardCategoryValue as $key => $value) {
                $serviceCardGroupDetail = $this->serviceCardGroup->getItem($key);
                if ($serviceCardGroupDetail != null) {
                    $arrayCategory[] = ['name' => $serviceCardGroupDetail['name'], 'value' => $value];
                }
            }

            //Biểu đồ chi nhánh
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


            //Biểu đồ thống kê số lượng sử dụng thẻ dịch vụ.
            $dataQuantity = [];
            $dataQuantity[] = ['', ''];
            $count=0;
            foreach ($arrayServiceCardName as $key => $value) {
                $dataQuantity[] = [$value, $arrayServiceCardQuantity[$key]];
                $count++;
                if ($count==8){
                    break;
                }
            }
            if (count($arrayServiceCardName) > 8) {
                $temp = 0;
                foreach ($arrayServiceCardQuantity as $key => $value) {
                    if ($key > 7) {
                        $temp += $value;
                    }
                }
                $dataQuantity[] = ['Khác', $temp];
            }

            //Biểu đồ nhóm dịch vụ
            $dataServiceCardGroup = [];
            $dataServiceCardGroup[] = ['', ''];

            foreach ($arrayCategory as $item) {
                $dataServiceCardGroup[] = [$item['name'], $item['value']];
            }

            //Biểu đồ chi nhánh
            $dataBranchChart = [];
            $dataBranchChart[] = ['', ''];
            foreach ($arrayBranchChart as $item) {
                $dataBranchChart[] = [$item['name'], $item['value']];
            }
            return response()->json([
                'totalQuantity' => $totalQuantity,
                'quantityOddCustomer' => $quantityOddCustomer,
                'arrayCategory' => $arrayCategory,
                'arrayBranchChart' => $arrayBranchChart,
                'dataQuantity'=>$dataQuantity,
                'dataServiceCardGroup'=>$dataServiceCardGroup,
                'dataBranchChart'=>$dataBranchChart,
            ]);
        } else if ($time != null && $serviceCard != null) {
            //Từ ngày đến ngày và thẻ dịch vụ.
            $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('service_card', $serviceCard, $startTime, $endTime);
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
            //Lấy dữ liệu cho nhóm thẻ dịch vụ.
            $arrayServiceCardGroup = [];
            $serviceCardDetail = $this->serviceCard->getItemDetail($serviceCard);

            if ($serviceCardDetail != null) {
                $serviceCardGroupDetail = $this->serviceCardGroup->getItem($serviceCardDetail['service_card_group_id']);
                if ($serviceCardGroupDetail != null) {
                    $arrayServiceCardGroup[] = $serviceCardGroupDetail['name'];
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
                $branchDetail=$this->branch->getItem($key);
                if ($branchDetail!=null){
                    $arrayBranchChart[] = ['name' => $branchDetail->branch_name, 'value' => $value];
                }
            }

            //Biểu đồ thống kê số lượng sử dụng thẻ dịch vụ.
            $dataQuantity = [];
            $dataQuantity[] = ['', ''];
            foreach ($day as $key => $value) {
                $dataQuantity[] = [$value, $valueDay[$key]];
            }

            //Biểu đồ nhóm dịch vụ
            $dataServiceCardGroup = [['', ''],[$arrayServiceCardGroup[0],array_sum($valueDay)]];

            //Biểu đồ chi nhánh
            $dataBranchChart = [];
            $dataBranchChart[] = ['', ''];
            foreach ($arrayBranchChart as $item) {
                $dataBranchChart[] = [$item['name'], $item['value']];
            }

            return response()->json([
                'quantityOddCustomer' => $quantityOddCustomer,
                'quantityAllCustomer' => $quantityAllCustomer,
                'arrayServiceCardGroup' => $arrayServiceCardGroup,
                'dataQuantity'=>$dataQuantity,
                'dataServiceCardGroup'=>$dataServiceCardGroup,
                'dataBranchChart'=>$dataBranchChart,
            ]);
        }
    }
}