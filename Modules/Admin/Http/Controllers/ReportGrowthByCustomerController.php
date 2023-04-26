<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/9/2019
 * Time: 2:56 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;

class ReportGrowthByCustomerController extends Controller
{
    protected $branches;
    protected $order;
    protected $serviceCategory;
    protected $orderDetail;

    public function __construct(
        BranchRepositoryInterface $branch,
        OrderRepositoryInterface $order,
        ServiceCategoryRepositoryInterface $serviceCategory,
        OrderDetailRepositoryInterface $orderDetail
    )
    {
        $this->branches = $branch;
        $this->order = $order;
        $this->serviceCategory = $serviceCategory;
        $this->orderDetail = $orderDetail;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        return view('admin::report.report-growth.report-growth-by-customer', [
            'branch' => $branch
        ]);
    }

    public function chartIndexAction(Request $request)
    {
        $year = date('Y');
        $time = $request->time;
        $startTime = $endTime = null;
        $branch = $this->branches->getBranch();
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $result = [];
        //Khách hàng mới.
        $dataSelectNewCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, null, null);

        $solutionValueTime = $this->solutionValueTime($dataSelectNewCustomer, $startTime, $endTime);
        $result['newCustomer']['valueDay'] = $solutionValueTime['valueDay'];


        //Khách hàng cũ
        $dataSelectOldCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, '<>', null, null);
        $solutionValueTime = $this->solutionValueTime($dataSelectOldCustomer, $startTime, $endTime);
        $result['oldCustomer']['valueDay'] = $solutionValueTime['valueDay'];

        //Khách vãng lai
        $dataSelectOddCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, 1, null);
        $arrayFormatDay = [];
        foreach ($dataSelectOddCustomer as $key => $value) {
            $timee = date('d/m/Y', strtotime($value['order_created_at']));
            $arrayFormatDay[] = $timee;
        }
        $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
        //Danh sách ngày có giá trị.
        $arrayHaveValue = array_count_values($arrayFormatDay);
        $day = [];
        $valueDay = [];
        $arrayDayValue = [];
        for ($i = 0; $i < $datediff; $i++) {
            $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
            $arrayDayValue[$tomorrow] = 0;
            foreach ($arrayHaveValue as $ii => $jj) {
                if ($ii == $tomorrow) {
                    $arrayDayValue[$tomorrow] = $jj;
                }
            }
        }
        foreach ($arrayDayValue as $key => $value) {

            $day[] = substr($key, 0, -5);
            $valueDay[] = $value;
        }

        $result['oddCustomer']['day'] = $day;
        $result['oddCustomer']['valueDay'] = $valueDay;

        //Tổng khách hàng
        $totalValueDay = [];
        for ($i = 0; $i < $datediff; $i++) {
            $totalValueDay[] = $result['newCustomer']['valueDay'][$i] + $result['oldCustomer']['valueDay'][$i] + $result['oddCustomer']['valueDay'][$i];
        }
        //end

        //Nhóm khách hàng.
        //Biểu đồ theo nhóm khách hàng
        $listCustomerGroup = [];
        $dataSelectCustomerGroup = $this->order->getValueReportGrowthByCustomerCustomerGroupTimeBranch($startTime, $endTime, null);

        $totalQuantity = 0;
        //Tổng số lượng sản phẩm truy vấn được.
        foreach ($dataSelectCustomerGroup as $key => $value) {
            $totalQuantity += $value['totalCustomer'];
        }

        //Mảng gồm tên nhóm khách hàng và %.
        foreach ($dataSelectCustomerGroup as $key => $value) {
            if ($value['group_name'] != null) {
                $listCustomerGroup[] = ['name' => $value['group_name'], 'y' => $value['totalCustomer']];
            }
        }
        //end

        //Biểu đồ theo giới tính
        $dataSelectGender = $this->order->getValueReportGrowthByCustomerCustomerGenderTimeBranch($startTime, $endTime, null);
        $totalQuantity3 = 0;
        $listGender = [];
        foreach ($dataSelectGender as $key => $value) {
            $totalQuantity3 += $value['totalCustomer'];
        }
        //Mảng gồm giới tính khách hàng và %.
        foreach ($dataSelectGender as $key => $value) {
            $listGender[] = ['name' => __($value['gender']), 'y' => $value['totalCustomer']];
        }
        //end

        //Biểu đồ theo nguồn khách hàng.
        $dataSelectCustomerSource = $this->order->getValueReportGrowthByCustomerCustomerSourceTimeBranch($startTime, $endTime, null);
        //Tổng số lượng đơn hàng truy vấn được.
        $totalQuantity2 = 0;
        $listCustomerSource = [];
        foreach ($dataSelectCustomerSource as $key => $value) {
            $totalQuantity2 += $value['totalCustomer'];
        }
        //Mảng gồm tên nguồn khách hàng và %.
        foreach ($dataSelectCustomerSource as $key => $value) {
            if ($value['customer_source_name'] != null) {
                $listCustomerSource[] = ['name' => $value['customer_source_name'], 'y' => $value['totalCustomer']];
            }
        }
        //end

        //Tăng trưởng khách hàng theo tất cả chi nhánh.
        $listBranch = [];
        $listValueBranch = [];
        $branchOption = $this->branches->getBranch();
        foreach ($branchOption as $key2 => $value2) {
            $newCustomer = 0;
            $oldCustomer = 0;
            $oddCustomer = 0;
            $listBranch[] = $value2;
            $arrayIdNewCustomer = [];
            $arrayIdOldCustomer = [];
            //Lấy khách hàng mới của từng chi nhánh theo từ ngày đến ngày.
            $dataNewCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, null, $key2);
            foreach ($dataNewCustomer as $key => $value) {
                if (!in_array($value['customer_id'], $arrayIdNewCustomer)) {
                    $arrayIdNewCustomer[] = $value['customer_id'];
                    $newCustomer++;
                }
            }
            //Lấy khách hàng cũ của từng chi nhánh theo từ ngày đến ngày.
            $dataOldCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, '<>', null, $key2);
            foreach ($dataOldCustomer as $key => $value) {
                if (!in_array($value['customer_id'], $arrayIdOldCustomer)) {
                    $arrayIdOldCustomer[] = $value['customer_id'];
                    $oldCustomer++;
                }
            }
            //Lấy khách vãng lai của từng chi nhánh theo từ ngày đến ngày.
            $dataOddCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, 1, $key2);
            foreach ($dataOddCustomer as $key => $value) {
                $oddCustomer++;
            }
            $totalCustomer = $newCustomer + $oldCustomer + $oddCustomer;
            $listValueBranch[] = [$totalCustomer, $newCustomer, $oldCustomer, $oddCustomer];
        }
        $result2 = [];
        foreach ($listValueBranch as $key => $value) {
            $result2['Tổng số khách hàng'][] = $value[0];
            $result2['Khách hàng mới'][] = $value[1];
            $result2['Khách hàng cũ'][] = $value[2];
            $result2['Khách vãng lai'][] = $value[3];
        }
        //end

        //Tăng trưởng khách hàng theo từng theo chi nhánh.
        $listBranch = [];
        $listValueBranch = [];
        foreach ($branch as $key2 => $value2) {
            $newCustomer = 0;
            $oldCustomer = 0;
            $oddCustomer = 0;
            $listBranch[] = $value2;
            $arrayIdNewCustomer = [];
            $arrayIdOldCustomer = [];
            //Lấy khách hàng mới của từng chi nhánh.
            $dataNewCustomer = $this->order->getDataReportGrowthCustomerByTime($startTime, $endTime, null, null, $key2);
            foreach ($dataNewCustomer as $key => $value) {
                if (!in_array($value['customer_id'], $arrayIdNewCustomer)) {
                    $arrayIdNewCustomer[] = $value['customer_id'];
                    $newCustomer++;
                }
            }
            //Lấy khách hàng cũ của từng chi nhánh.
            $dataOldCustomer = $this->order->getDataReportGrowthCustomerByTime($startTime, $endTime, '<>', null, $key2);
            foreach ($dataOldCustomer as $key => $value) {
                if (!in_array($value['customer_id'], $arrayIdOldCustomer)) {
                    $arrayIdOldCustomer[] = $value['customer_id'];
                    $oldCustomer++;
                }
            }
            //Lấy khách vãng lai của từng chi nhánh.
            $dataOddCustomer = $this->order->getDataReportGrowthCustomerByTime($startTime, $endTime, null, 1, $key2);
            foreach ($dataOddCustomer as $key => $value) {
                $oddCustomer++;
            }
            $totalCustomer = $newCustomer + $oldCustomer + $oddCustomer;
            $listValueBranch[] = [$totalCustomer, $newCustomer, $oldCustomer, $oddCustomer];
        }
        $result3 = [];
        foreach ($listValueBranch as $key => $value) {
            $result3['Tổng số khách hàng'][] = $value[0];
            $result3['Khách hàng mới'][] = $value[1];
            $result3['Khách hàng cũ'][] = $value[2];
            $result3['Khách vãng lai'][] = $value[3];
        }

        //Biểu đồ miền.
        $dataValue = [];
        $dataValue[] = ['day', __('TỔNG SỐ KH'), __('KHÁCH MỚI'), __('KHÁCH CŨ'), __('KH VÃNG LAI')];
        foreach ($result['oddCustomer']['day'] as $key => $value) {
            $dataValue[] = [
                $value,
                $totalValueDay[$key],
                $result['newCustomer']['valueDay'][$key],
                $result['oldCustomer']['valueDay'][$key],
                $result['oddCustomer']['valueDay'][$key]
            ];
        }

        //Biểu đồ nhóm khách hàng
        $dataCustomergroup = [];
        $dataCustomergroup[] = ['Task', 'Hours per Day'];
        foreach ($listCustomerGroup as $item) {
            $dataCustomergroup[] = [$item['name'], $item['y']];
        }
        //Biểu đồ giới tính
        $dataCustomeGender = [];
        $dataCustomeGender[] = ['Task', 'Hours per Day'];
        foreach ($listGender as $item) {
            if ($item['name'] == 'male') {
                $dataCustomeGender[] = [__('Nam'), $item['y']];
            } elseif ($item['name'] == 'female') {
                $dataCustomeGender[] = [__('Nữ'), $item['y']];
            } else {
                $dataCustomeGender[] = [__('Khác'), $item['y']];
            }
        }
        //Biểu đồ nguồn khách hàng
        $dataCustomeSource = [];
        $dataCustomeSource[] = ['Task', 'Hours per Day'];

        foreach ($listCustomerSource as $item) {
            $dataCustomeSource[] = [$item['name'], $item['y']];
        }


        return response()->json([
            'day' => $result['oddCustomer']['day'],
            'data1' => $totalValueDay,
            'data2' => $result['newCustomer']['valueDay'],
            'data3' => $result['oldCustomer']['valueDay'],
            'data4' => $result['oddCustomer']['valueDay'],
            'listCustomerGroup' => $listCustomerGroup,
            'listGender' => $listGender,
            'listCustomerSource' => $listCustomerSource,
            'listBranch' => $listBranch,
            'result' => $result3,
            'dataValue' => $dataValue,
            'dataCustomergroup' => $dataCustomergroup,
            'dataCustomeGender' => $dataCustomeGender,
            'dataCustomeSource' => $dataCustomeSource,
        ]);
    }

    public function filterAction(Request $request)
    {
        $year = date('Y');
        $branch = $request->branch;
        $time = $request->time;
        $valueMonthYearNewCustomer = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0];
        $valueMonthYearOldCustomer = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0];
        $valueMonthYearOddCustomer = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0];
        $valueMonthYearAllCustomer = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0];
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time == null && $branch != null) {
            //Theo chi nhánh.

            //Lấy khách hàng mới.
            for ($i = 1; $i < 13; $i++) {
                $dataSelectNewCustomer = $this->order->getDataReportGrowthByCustomer($year, $i, null, null, 'orders.branch_id', $branch);
                $arrayIdCustomer = [];
                foreach ($dataSelectNewCustomer as $key => $value) {
                    if (!in_array($value['customer_id'], $arrayIdCustomer)) {
                        $arrayIdCustomer[] = $value['customer_id'];
                        $valueMonthYearNewCustomer[$i - 1] += 1;
                    }
                }
            }

            //Lấy khách hàng cũ.
            for ($i = 1; $i < 13; $i++) {
                $dataSelectOldCustomer = $this->order->getDataReportGrowthByCustomer($year, $i, '<>', null, 'orders.branch_id', $branch);
                $arrayIdCustomer = [];
                foreach ($dataSelectOldCustomer as $key => $value) {
                    if (!in_array($value['customer_id'], $arrayIdCustomer)) {
                        $arrayIdCustomer[] = $value['customer_id'];
                        $valueMonthYearOldCustomer[$i - 1] += 1;
                    }
                }
            }

            //Lấy khách vãng lai
            for ($i = 1; $i < 13; $i++) {
                $dataSelectOddCustomer = $this->order->getDataReportGrowthByCustomer($year, $i, null, 1, 'orders.branch_id', $branch);
                foreach ($dataSelectOddCustomer as $key => $value) {
                    $valueMonthYearOddCustomer[$i - 1] += 1;
                }
            }
            //Tổng khách hàng theo nhóm
            foreach ($valueMonthYearAllCustomer as $k => $v) {
                $valueMonthYearAllCustomer[$k] = $valueMonthYearNewCustomer[$k] + $valueMonthYearOldCustomer[$k] + $valueMonthYearOddCustomer[$k];
            }

            //Biểu đồ theo nhóm khách hàng
            $listCustomerGroup = [];
            $dataSelectCustomerGroup = $this->order->getValueReportGrowthByCustomerCustomerGroup($year, $branch);

            $totalQuantity = 0;
            //Tổng số lượng sản phẩm truy vấn được.
            foreach ($dataSelectCustomerGroup as $key => $value) {
                $totalQuantity += $value['totalCustomer'];
            }

            //Mảng gồm tên nhóm khách hàng và %.
            foreach ($dataSelectCustomerGroup as $key => $value) {
                if ($value['group_name'] != null) {
                    $listCustomerGroup[] = ['name' => $value['group_name'], 'y' => $value['totalCustomer']];
                }
            }
            //
            //Biểu đồ theo giới tính
            $dataSelectGender = $this->order->getValueReportGrowthByCustomerCustomerGender($year, $branch);
            $totalQuantity3 = 0;
            $listGender = [];
            foreach ($dataSelectGender as $key => $value) {
                $totalQuantity3 += $value['totalCustomer'];
            }
            //Mảng gồm giới tính khách hàng và %.
            foreach ($dataSelectGender as $key => $value) {
                if ($value['gender'] != null) {
                    $listGender[] = ['name' => $value['gender'], 'y' => $value['totalCustomer']];
                }
            }

            //Biểu đồ theo nguồn khách hàng.
            $dataSelectCustomerSource = $this->order->getValueReportGrowthByCustomerCustomerSource($year, $branch);
            //Tổng số lượng đơn hàng truy vấn được.
            $totalQuantity2 = 0;
            $listCustomerSource = [];
            foreach ($dataSelectCustomerSource as $key => $value) {
                $totalQuantity2 += $value['totalCustomer'];
            }
            //Mảng gồm tên nguồn khách hàng và %.
            foreach ($dataSelectCustomerSource as $key => $value) {
                if ($value['customer_source_name'] != null) {
                    $listCustomerSource[] = ['name' => $value['customer_source_name'], 'y' => $value['totalCustomer']];
                }
            }
            return response()->json([
                'data1' => $valueMonthYearAllCustomer,
                'data2' => $valueMonthYearNewCustomer,
                'data3' => $valueMonthYearOldCustomer,
                'data4' => $valueMonthYearOddCustomer,
                'listCustomerGroup' => $listCustomerGroup,
                'listGender' => $listGender,
                'listCustomerSource' => $listCustomerSource,
            ]);
        } else if ($time != null && $branch == null) {
            //Theo từ ngày đến ngày.

            $result = [];
            //Khách hàng mới.
            $dataSelectNewCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, null, null);

            $solutionValueTime = $this->solutionValueTime($dataSelectNewCustomer, $startTime, $endTime);
            $result['newCustomer']['valueDay'] = $solutionValueTime['valueDay'];


            //Khách hàng cũ
            $dataSelectOldCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, '<>', null, null);
            $solutionValueTime = $this->solutionValueTime($dataSelectOldCustomer, $startTime, $endTime);
            $result['oldCustomer']['valueDay'] = $solutionValueTime['valueDay'];

            //Khách vãng lai
            $dataSelectOddCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, 1, null);
            $arrayFormatDay = [];
            foreach ($dataSelectOddCustomer as $key => $value) {
                $timee = date('d/m/Y', strtotime($value['order_created_at']));
                $arrayFormatDay[] = $timee;
            }
            $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            //Danh sách ngày có giá trị.
            $arrayHaveValue = array_count_values($arrayFormatDay);
            $day = [];
            $valueDay = [];
            $arrayDayValue = [];
            for ($i = 0; $i < $datediff; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arrayDayValue[$tomorrow] = 0;
                foreach ($arrayHaveValue as $ii => $jj) {
                    if ($ii == $tomorrow) {
                        $arrayDayValue[$tomorrow] = $jj;
                    }
                }
            }
            foreach ($arrayDayValue as $key => $value) {

                $day[] = substr($key, 0, -5);
                $valueDay[] = $value;
            }

            $result['oddCustomer']['day'] = $day;
            $result['oddCustomer']['valueDay'] = $valueDay;

            //Tổng khách hàng
            $totalValueDay = [];
            for ($i = 0; $i < $datediff; $i++) {
                $totalValueDay[] = $result['newCustomer']['valueDay'][$i] + $result['oldCustomer']['valueDay'][$i] + $result['oddCustomer']['valueDay'][$i];
            }
            //end

            //Nhóm khách hàng.
            //Biểu đồ theo nhóm khách hàng
            $listCustomerGroup = [];
            $dataSelectCustomerGroup = $this->order->getValueReportGrowthByCustomerCustomerGroupTimeBranch($startTime, $endTime, null);

            $totalQuantity = 0;
            //Tổng số lượng sản phẩm truy vấn được.
            foreach ($dataSelectCustomerGroup as $key => $value) {
                $totalQuantity += $value['totalCustomer'];
            }

            //Mảng gồm tên nhóm khách hàng và %.
            foreach ($dataSelectCustomerGroup as $key => $value) {
                if ($value['group_name'] != null) {
                    $listCustomerGroup[] = ['name' => $value['group_name'], 'y' => $value['totalCustomer']];
                }
            }
            //end

            //Biểu đồ theo giới tính
            $dataSelectGender = $this->order->getValueReportGrowthByCustomerCustomerGenderTimeBranch($startTime, $endTime, null);
            $totalQuantity3 = 0;
            $listGender = [];
            foreach ($dataSelectGender as $key => $value) {
                $totalQuantity3 += $value['totalCustomer'];
            }
            //Mảng gồm giới tính khách hàng và %.
            foreach ($dataSelectGender as $key => $value) {
                if ($value['gender'] != null) {
                    $listGender[] = ['name' => $value['gender'], 'y' => $value['totalCustomer']];
                }
            }
            //end

            //Biểu đồ theo nguồn khách hàng.
            $dataSelectCustomerSource = $this->order->getValueReportGrowthByCustomerCustomerSourceTimeBranch($startTime, $endTime, null);
            //Tổng số lượng đơn hàng truy vấn được.
            $totalQuantity2 = 0;
            $listCustomerSource = [];
            foreach ($dataSelectCustomerSource as $key => $value) {
                $totalQuantity2 += $value['totalCustomer'];
            }
            //Mảng gồm tên nguồn khách hàng và %.
            foreach ($dataSelectCustomerSource as $key => $value) {
                if ($value['customer_source_name'] != null) {
                    $listCustomerSource[] = ['name' => $value['customer_source_name'], 'y' => $value['totalCustomer']];
                }
            }
            //end

            //Tăng trưởng khách hàng theo tất cả chi nhánh.
            $listBranch = [];
            $listValueBranch = [];
            $branchOption = $this->branches->getBranch();
            foreach ($branchOption as $key2 => $value2) {
                $newCustomer = 0;
                $oldCustomer = 0;
                $oddCustomer = 0;
                $listBranch[] = $value2;
                $arrayIdNewCustomer = [];
                $arrayIdOldCustomer = [];
                //Lấy khách hàng mới của từng chi nhánh theo từ ngày đến ngày.
                $dataNewCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, null, $key2);
                foreach ($dataNewCustomer as $key => $value) {
                    if (!in_array($value['customer_id'], $arrayIdNewCustomer)) {
                        $arrayIdNewCustomer[] = $value['customer_id'];
                        $newCustomer++;
                    }
                }
                //Lấy khách hàng cũ của từng chi nhánh theo từ ngày đến ngày.
                $dataOldCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, '<>', null, $key2);
                foreach ($dataOldCustomer as $key => $value) {
                    if (!in_array($value['customer_id'], $arrayIdOldCustomer)) {
                        $arrayIdOldCustomer[] = $value['customer_id'];
                        $oldCustomer++;
                    }
                }
                //Lấy khách vãng lai của từng chi nhánh theo từ ngày đến ngày.
                $dataOddCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, 1, $key2);
                foreach ($dataOddCustomer as $key => $value) {
                    $oddCustomer++;
                }
                $totalCustomer = $newCustomer + $oldCustomer + $oddCustomer;
                $listValueBranch[] = [$totalCustomer, $newCustomer, $oldCustomer, $oddCustomer];
            }
            $result2 = [];
            foreach ($listValueBranch as $key => $value) {
                $result2['Tổng số khách hàng'][] = $value[0];
                $result2['Khách hàng mới'][] = $value[1];
                $result2['Khách hàng cũ'][] = $value[2];
                $result2['Khách vãng lai'][] = $value[3];
            }

            //Biểu đồ miền.
            $dataValue = [];
            $dataValue[] = ['day', __('TỔNG SỐ KH'), __('KHÁCH MỚI'), __('KHÁCH CŨ'), __('KH VÃNG LAI')];
            foreach ($result['oddCustomer']['day'] as $key => $value) {
                $dataValue[] = [
                    $value,
                    $totalValueDay[$key],
                    $result['newCustomer']['valueDay'][$key],
                    $result['oldCustomer']['valueDay'][$key],
                    $result['oddCustomer']['valueDay'][$key]
                ];
            }
            //Biểu đồ nhóm khách hàng
            $dataCustomergroup = [];
            $dataCustomergroup[] = ['Task', 'Hours per Day'];
            foreach ($listCustomerGroup as $item) {
                $dataCustomergroup[] = [$item['name'], $item['y']];
            }
            //Biểu đồ giới tính
            $dataCustomeGender = [];
            $dataCustomeGender[] = ['Task', 'Hours per Day'];
            foreach ($listGender as $item) {
                if ($item['name'] == 'male') {
                    $dataCustomeGender[] = [__('Nam'), $item['y']];
                } elseif ($item['name'] == 'female') {
                    $dataCustomeGender[] = [__('Nữ'), $item['y']];
                } else {
                    $dataCustomeGender[] = [__('Khác'), $item['y']];
                }
            }

            //Biểu đồ nguồn khách hàng
            $dataCustomeSource = [];
            $dataCustomeSource[] = ['Task', 'Hours per Day'];

            foreach ($listCustomerSource as $item) {
                $dataCustomeSource[] = [$item['name'], $item['y']];
            }
            return response()->json([
                'day' => $result['oddCustomer']['day'],
                'data1' => $totalValueDay,
                'data2' => $result['newCustomer']['valueDay'],
                'data3' => $result['oldCustomer']['valueDay'],
                'data4' => $result['oddCustomer']['valueDay'],
                'listCustomerGroup' => $listCustomerGroup,
                'listGender' => $listGender,
                'listBranch' => $listBranch,
                'listValueBranch' => $result2,
                'dataValue' => $dataValue,
                'dataCustomergroup' => $dataCustomergroup,
                'dataCustomeGender' => $dataCustomeGender,
                'dataCustomeSource' => $dataCustomeSource
            ]);
        } else if ($time != null && $branch != null) {
            //Theo từ ngày đến ngày và chi nhánh.

            $result = [];
            //Khách hàng mới.
            $dataSelectNewCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, null, $branch);

            $solutionValueTime = $this->solutionValueTime($dataSelectNewCustomer, $startTime, $endTime);
            $result['newCustomer']['valueDay'] = $solutionValueTime['valueDay'];
            //Khách hàng cũ
            $dataSelectOldCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, '<>', null, $branch);
            $solutionValueTime = $this->solutionValueTime($dataSelectOldCustomer, $startTime, $endTime);
            $result['oldCustomer']['valueDay'] = $solutionValueTime['valueDay'];

            //Khách vãng lai
            $dataSelectOddCustomer = $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, null, 1, $branch);
            $arrayFormatDay = [];
            foreach ($dataSelectOddCustomer as $key => $value) {
                $timee = date('d/m/Y', strtotime($value['order_created_at']));
                $arrayFormatDay[] = $timee;
            }
            $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            //Danh sách ngày có giá trị.
            $arrayHaveValue = array_count_values($arrayFormatDay);
            $day = [];
            $valueDay = [];
            $arrayDayValue = [];
            for ($i = 0; $i < $datediff; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arrayDayValue[$tomorrow] = 0;
                foreach ($arrayHaveValue as $ii => $jj) {
                    if ($ii == $tomorrow) {
                        $arrayDayValue[$tomorrow] = $jj;
                    }
                }
            }
            foreach ($arrayDayValue as $key => $value) {
                $day[] = substr($key, 0, -5);
                $valueDay[] = $value;
            }
            $result['oddCustomer']['day'] = $day;
            $result['oddCustomer']['valueDay'] = $valueDay;

            //Tổng khách hàng
            $totalValueDay = [];
            for ($i = 0; $i < $datediff; $i++) {
                $totalValueDay[] = $result['newCustomer']['valueDay'][$i] + $result['oldCustomer']['valueDay'][$i] + $result['oddCustomer']['valueDay'][$i];
            }
            //end

            //Nhóm khách hàng.
            //Biểu đồ theo nhóm khách hàng
            $listCustomerGroup = [];
            $dataSelectCustomerGroup = $this->order->getValueReportGrowthByCustomerCustomerGroupTimeBranch($startTime, $endTime, $branch);

            $totalQuantity = 0;
            //Tổng số lượng sản phẩm truy vấn được.
            foreach ($dataSelectCustomerGroup as $key => $value) {
                $totalQuantity += $value['totalCustomer'];
            }

            //Mảng gồm tên nhóm khách hàng và %.
            foreach ($dataSelectCustomerGroup as $key => $value) {
                if ($value['group_name'] != null) {
                    $listCustomerGroup[] = ['name' => $value['group_name'], 'y' => $value['totalCustomer']];
                }
            }
            //end

            //Biểu đồ theo giới tính
            $dataSelectGender = $this->order->getValueReportGrowthByCustomerCustomerGenderTimeBranch($startTime, $endTime, $branch);
            $totalQuantity3 = 0;
            $listGender = [];
            foreach ($dataSelectGender as $key => $value) {
                $totalQuantity3 += $value['totalCustomer'];
            }
            //Mảng gồm giới tính khách hàng và %.
            foreach ($dataSelectGender as $key => $value) {
                if ($value['gender'] != null) {
                    $listGender[] = ['name' => $value['gender'], 'y' => $value['totalCustomer']];
                }
            }
            //end

            //Biểu đồ theo nguồn khách hàng.
            $dataSelectCustomerSource = $this->order->getValueReportGrowthByCustomerCustomerSourceTimeBranch($startTime, $endTime, $branch);
            //Tổng số lượng đơn hàng truy vấn được.
            $totalQuantity2 = 0;
            $listCustomerSource = [];
            foreach ($dataSelectCustomerSource as $key => $value) {
                $totalQuantity2 += $value['totalCustomer'];
            }
            //Mảng gồm tên nguồn khách hàng và %.
            foreach ($dataSelectCustomerSource as $key => $value) {
                if ($value['customer_source_name'] != null) {
                    $listCustomerSource[] = ['name' => $value['customer_source_name'], 'y' => $value['totalCustomer']];
                }
            }
            //end

            //Biểu đồ miền.
            $dataValue = [];
            $dataValue[] = ['day', __('TỔNG SỐ KH'), __('KHÁCH MỚI'), __('KHÁCH CŨ'), __('KH VÃNG LAI')];
            foreach ($result['oddCustomer']['day'] as $key => $value) {
                $dataValue[] = [
                    $value,
                    $totalValueDay[$key],
                    $result['newCustomer']['valueDay'][$key],
                    $result['oldCustomer']['valueDay'][$key],
                    $result['oddCustomer']['valueDay'][$key]
                ];
            }
            //Biểu đồ nhóm khách hàng
            $dataCustomergroup = [];
            $dataCustomergroup[] = ['Task', 'Hours per Day'];
            foreach ($listCustomerGroup as $item) {
                $dataCustomergroup[] = [$item['name'], $item['y']];
            }
            //Biểu đồ giới tính
            $dataCustomeGender = [];
            $dataCustomeGender[] = ['Task', 'Hours per Day'];
            foreach ($listGender as $item) {
                if ($item['name'] == 'male') {
                    $dataCustomeGender[] = [__('Nam'), $item['y']];
                } elseif ($item['name'] == 'female') {
                    $dataCustomeGender[] = [__('Nữ'), $item['y']];
                } else {
                    $dataCustomeGender[] = [__('Khác'), $item['y']];
                }
            }

            //Biểu đồ nguồn khách hàng
            $dataCustomeSource = [];
            $dataCustomeSource[] = ['Task', 'Hours per Day'];

            foreach ($listCustomerSource as $item) {
                $dataCustomeSource[] = [$item['name'], $item['y']];
            }
            return response()->json([
                'day' => $result['oddCustomer']['day'],
                'data1' => $totalValueDay,
                'data2' => $result['newCustomer']['valueDay'],
                'data3' => $result['oldCustomer']['valueDay'],
                'data4' => $result['oddCustomer']['valueDay'],
                'listCustomerGroup' => $listCustomerGroup,
                'listGender' => $listGender,
                'dataValue' => $dataValue,
                'dataCustomergroup' => $dataCustomergroup,
                'dataCustomeGender' => $dataCustomeGender,
                'dataCustomeSource' => $dataCustomeSource
            ]);
        }
    }

    //Hàm chung tính và trả về kết quả cho từ ngày đến ngày.
    public function solutionValueTime($data, $startTime, $endTime)
    {
        $result = [];
        //Số ngày.
        $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
        //Danh sách ngày có giá trị.
        $arrayFormat = [];
        foreach ($data as $key => $value) {
            $timee = date('d/m/Y', strtotime($value['order_created_at']));
            $arrayFormat[] = [$value['customer_id'], $timee];
        }
        $arrayDayValue = [];

        $valueDay = [];
        for ($i = 0; $i < $datediff; $i++) {
            $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
            $arrayDayValue[$tomorrow] = 0;
            foreach ($arrayFormat as $ii => $jj) {
                if ($jj[1] == $tomorrow) {
                    $arrayDayValue[$tomorrow] += 1;
                }
            }
        }

        foreach ($arrayDayValue as $key => $value) {
            $valueDay[] = $value;
        }
        $result['valueDay'] = $valueDay;
        return $result;
    }
}