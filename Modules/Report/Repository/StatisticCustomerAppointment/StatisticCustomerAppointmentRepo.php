<?php

namespace Modules\Report\Repository\StatisticCustomerAppointment;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\CustomerAppointmentTable;

class StatisticCustomerAppointmentRepo implements StatisticCustomerAppointmentRepoInterface
{
    /**
     * Data cho View thống kê theo chi nhánh
     *
     * @return array
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $optionBranch = $mBranch->getOption();
        return [
            'optionBranch' => $optionBranch
        ];
    }

    /**
     * Data thống kê sau khi filter
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function filterAction($input)
    {
        $branchId = $input['branch'];
        $time = $input['time'];
        $startTime = $endTime = null;
        $mBranch = new BranchTable();
        $mCustomerAppointment = new CustomerAppointmentTable();
        $arrayCategory = [];

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $getAllAppointment = $mCustomerAppointment->getAllAppointment($startTime, $endTime, $branchId)->toArray();
        // Biểu đồ cột
        if ($branchId != null) {
            // array days
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $timeTmp = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
                $arrayCategory [$timeTmp] = [
                    'category_name' => $timeTmp,
                    'new' => 0,
                    'confirm' => 0,
                    'cancel' => 0,
                    'finish' => 0,
                    'wait' => 0,
                ];
            }
            $chartArea = $this->dataChartArea($arrayCategory, $getAllAppointment, false);
        } else {
            // array branch
            $optionBranch = $mBranch->getOption();
            foreach ($optionBranch as $key => $value) {
                $arrayCategory [$value['branch_id']] = [
                    'category_name' => $value['branch_name'],
                    'new' => 0,
                    'confirm' => 0,
                    'cancel' => 0,
                    'finish' => 0,
                    'wait' => 0,
                ];
            }
            $chartArea = $this->dataChartArea($arrayCategory, $getAllAppointment);
        }
        // Biểu đồ tròn: Nguồn lịch hẹn
        $dataAppointmentSource = $this->dataChartAppointmentSource($startTime, $endTime, $branchId);
        // Biểu đồ tròn: Giới tính
        $dataGender = $this->dataChartGender($startTime, $endTime, $branchId);
        // Biểu đồ tròn: Nhóm khách hàng (update ngày 02/02/2021, ban đầu là nguồn khách hàng)
        $dataCustomerGroup = $this->dataChartCustomerGroup($startTime, $endTime, $branchId);
        // return
        return response()->json([
            'dataChartArea' => $chartArea,
            'dataAppointmentSource' => $dataAppointmentSource,
            'dataGender' => $dataGender,
            'dataCustomerGroup' => $dataCustomerGroup
        ]);
    }

    // Xử lý data cho biểu đồ miền
    private function dataChartArea($arrayCategory, $getAllAppointment, $isAllBranch = true)
    {
        // Gộp appointment theo status
        $dataGroup = collect($getAllAppointment)->groupBy('status');
        $arrNew = $arrConfirm = $arrCancel = $arrFinish = $arrWait = [];
        foreach ($dataGroup as $key => $value) {
            switch ($key) {
                case 'new':
                    $arrNew = $value;
                    break;
                case 'confirm':
                    $arrConfirm = $value;
                    break;
                case 'cancel':
                    $arrCancel = $value;
                    break;
                case 'finish':
                    $arrFinish = $value;
                    break;
                case 'wait':
                    $arrWait = $value;
                    break;
            }
        }
        if ($isAllBranch) {
            foreach ($arrNew as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['new'] += 1;
                }
            }
            foreach ($arrConfirm as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['confirm'] += 1;
                }
            }
            foreach ($arrCancel as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['cancel'] += 1;
                }
            }
            foreach ($arrFinish as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['finish'] += 1;
                }
            }
            foreach ($arrWait as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['wait'] += 1;
                }
            }
        } else {
            foreach ($arrNew as $key => $value) {
                $timeTemp = Carbon::parse($value['date'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['new'] += 1;
                }
            }
            foreach ($arrConfirm as $key => $value) {
                $timeTemp = Carbon::parse($value['date'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['confirm'] += 1;
                }
            }
            foreach ($arrCancel as $key => $value) {
                $timeTemp = Carbon::parse($value['date'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['cancel'] += 1;
                }
            }
            foreach ($arrFinish as $key => $value) {
                $timeTemp = Carbon::parse($value['date'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['finish'] += 1;
                }
            }
            foreach ($arrWait as $key => $value) {
                $timeTemp = Carbon::parse($value['date'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['wait'] += 1;
                }
            }
        }
        // Đưa data về đúng dạng biểu đồ
        $dataReturn = [['', __('TỔNG LỊCH HẸN'), __('LỊCH HẸN MỚI'), __('ĐÃ XÁC NHẬN'),
            __('CHỜ PHỤC VỤ'), __('HUỶ'), __('HOÀN THÀNH')]];
        foreach ($arrayCategory as $value) {
            $total = $value['new'] + $value['confirm'] + $value['wait'] + $value['cancel'] + $value['finish'];
            $dataReturn [] = [
                $value['category_name'], $total, $value['new'], $value['confirm'], $value['wait'],
                $value['cancel'], $value['finish']
            ];
            $total = 0;
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ tròn: nguồn lịch hẹn
    private function dataChartAppointmentSource($startTime, $endTime, $branchId)
    {
        $dataReturn = [['Appointment source', 'Amount']];
        $mCustomerAppointment = new CustomerAppointmentTable();
        // Lấy dữ liệu
        $appointmentSrc = $mCustomerAppointment->getDataStatisticAppointmentSource($startTime, $endTime, $branchId)->toArray();
        // Xử lý dữ liệu đưa về đúng dạng biểu đồ
        foreach ($appointmentSrc as $value) {
            $dataReturn [] = [$value['appointment_source_name'], $value['number']];
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ tròn: giới tính
    private function dataChartGender($startTime, $endTime, $branchId)
    {
        $dataReturn = [['Gender', 'Amount']];
        $mCustomerAppointment = new CustomerAppointmentTable();
        // Lấy dữ liệu
        $dataGender = $mCustomerAppointment->getDataStatisticGender($startTime, $endTime, $branchId)->toArray();
        // Xử lý dữ liệu đưa về đúng dạng biểu đồ
        foreach ($dataGender as $value) {
            if ($value['gender'] == 'male') {
                $dataReturn [] = [__('Nam'), $value['number']];
            } elseif ($value['gender'] == 'female') {
                $dataReturn [] = [__('Nữ'), $value['number']];
            } else {
                $dataReturn [] = [__('Khác'), $value['number']];
            }
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ tròn: nhóm khách hàng
    private function dataChartCustomerGroup($startTime, $endTime, $branchId)
    {
        $dataReturn = [['Customer group', 'Amount']];
        $mCustomerAppointment = new CustomerAppointmentTable();
        // Lấy dữ liệu khách hàng vãng lai
        $customerSrcCurrent = $mCustomerAppointment->getDataStatisticCustomerGroup($startTime, $endTime, $branchId);
        if ($customerSrcCurrent != null) {
            $dataReturn [] = [__('Khách hàng khác'), $customerSrcCurrent['number']];
        }

        // Lấy dữ liệu theo nhóm khách hàng
        $customerSrc = $mCustomerAppointment->getDataStatisticCustomerGroup($startTime, $endTime, $branchId, false)->toArray();
        // Xử lý dữ liệu đưa về đúng dạng biểu đồ
        foreach ($customerSrc as $value) {
            $dataReturn [] = [$value['group_name'], $value['number']];
        }

        return $dataReturn;
    }
    /**
     * Ds chi tiết của chart
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetail($input)
    {
        $mCustomerAppointment = new CustomerAppointmentTable();
        $list = $mCustomerAppointment->getListDetailStatisticsCustomerAppointment($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Export excel tổng
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelTotal($input)
    {
        $heading = [
            __('TÊN CHI NHÁNH'),
            __('TỔNG LỊCH'),
            __('SỐ LỊCH MỚI'),
            __('SỐ LỊCH ĐÃ XÁC NHẬN'),
            __('SỐ LỊCH CHỜ PHỤC VỤ'),
            __('SỐ LỊCH HUỶ'),
            __('SỐ LỊCH HOÀN THÀNH'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrderDetails = new CustomerAppointmentTable();
        $allData = $mOrderDetails->getListExportTotalStatisticsCustomerAppointment($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['total'] == '' ? '0' : $item['total'],
                    $item['new'] == '' ? '0' : $item['new'],
                    $item['confirm'] == '' ? '0' : $item['confirm'],
                    $item['wait'] == '' ? '0' : $item['wait'],
                    $item['cancel'] == '' ? '0' : $item['cancel'],
                    $item['finish'] == '' ? '0' : $item['finish']
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-total.xlsx');
    }

    /**
     * Export excel chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelDetail($input)
    {
        $heading = [
            __('MÃ LỊCH HẸN'),
            __('TÊN KHÁCH HÀNG'),
            __('TÊN CHI NHÁNH'),
            __('TRẠNG THÁI'),
            __('NGÀY HẸN'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrders = new CustomerAppointmentTable();
        $allData = $mOrders->getListExportDetailStatisticsCustomerAppointment($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['customer_appointment_code'],
                    $item['full_name'],
                    $item['branch_name'],
                    $item['status'],
                    date("d/m/Y",strtotime($item['date'])) . ' ' . date("h:i",strtotime($item['time']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}