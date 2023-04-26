<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/07/2022
 * Time: 15:16
 */

namespace Modules\Kpi\Repositories\CalculateKpi;


use Carbon\Carbon;
use Modules\Kpi\Models\CalculateKpiDetailTable;
use Modules\Kpi\Models\CalculateKpiTable;
use Modules\Kpi\Models\CpoCustomerCareTable;
use Modules\Kpi\Models\CpoDealTable;
use Modules\Kpi\Models\CpoLeadTable;
use Modules\Kpi\Models\CustomerContractTable;
use Modules\Kpi\Models\CustomerTable;
use Modules\Kpi\Models\KpiNoteDetailTable;
use Modules\Kpi\Models\KpiNoteTable;
use Modules\Kpi\Models\OcHistoryTable;
use Modules\Kpi\Models\OrderTable;
use Modules\Kpi\Models\StaffsTable;
use Modules\Kpi\Models\TeamTable;

class CalculateKpiRepo implements CalculateKpiRepoInterface
{
    const ORDER = "order";
    const CONTRACT = "contract";
    const DEAL = "deal";
    const LEAD = "lead";
    const LEAD_CARE = "lead_care";
    const HISTORY_CALL = "history_call";
    const LEAD_WIN = "lead_win";
    const ORDER_SUCCESS = "order_success";

    /**
     * Chạy job tính kpi
     *
     * @return mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function jobCalculateKpi()
    {
        try {
            $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);

            $day = Carbon::now()->subDays(2)->format('d');
            $month = Carbon::now()->subDays(2)->format('m');
            $year = Carbon::now()->subDays(2)->format('Y');

            //Lấy chi tiết phiếu giao kpi của nhân viên theo từng tiêu chí kpi
            $getKpiDetail = $mKpiNoteDetail->getKpiByMonth($month, $year);

            if (count($getKpiDetail) > 0) {
                $mStaff = app()->get(StaffsTable::class);
                $mTeam = app()->get(TeamTable::class);

                foreach ($getKpiDetail as $v) {
                    if ($v['by_group'] == 0) {
                        //Lấy thông tin nhân viên
                        $infoStaff = $mStaff->getInfo($v['staff_id']);

                        if ($infoStaff['branch_id'] != $v['branch_id']
                            || $infoStaff['department_id'] != $v['department_id']
                            || $infoStaff['team_id'] != $v['team_id']
                        ) {
                            continue;
                        }
                    } else {
                        //Lấy thông tin nhóm
                        $infoTeam = $mTeam->getInfoTeam($v['team_id']);

                        if ($infoTeam == null) {
                            continue;
                        }
                    }

                    switch ($v['kpi_criteria_code']) {
                        case 'kpi_order_new':
                            //Tính số lượng đơn hàng mới
                            $this->calculateOrderConfirm([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_order_cancel':
                            //Tính số lượng đơn hàng bị huỷ
                            $this->calculateOrderCancel([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_contract':
                            //Tính số lượng hợp đồng đang thực hiện
                            $this->calculateContractProcessing([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_revenue_contract':
                            //Tính doanh thu hợp đồng
                            $this->calculateRevenueContract([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_revenue_contract_first':
                            //Tính doanh thu hợp đồng đầu tiên
                            $this->calculateRevenueContractFirst([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_revenue_customer_new':
                            //Tính doanh thu khách hàng mới
                            $this->calculateRevenueContractByFirstCustomer([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_revenue_customer_old':
                            //Tính doanh thu khách hàng cũ
                            $this->calculateRevenueContractByOldCustomer([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_revenue_contract_renew':
                            //Tính doanh thu hợp đồng tái kí
                            $this->calculateRevenueContractReNew([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_revenue_overall_department':
                            //Tính doanh thu hợp đồng theo phòng ban
                            $this->calculateRevenueContractByDepartment([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_ratio_deal_convert_order':
                            //Tính tỉ lệ chuyển đổi deal lên đơn hàng
                            $this->calculateRatioDealConvertOrder([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_customer_back':
                            //Tính khách hàng quay lại
                            $this->calculateCustomerBack([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_lead_first_care':
                            //Tính thời gian chăm sóc lead lần đầu
                            $this->calculateLeadFirstCare([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_avg_call':
                            //Tình thời gian gọi trung bình
                            $this->calculateAvgLeadCare([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_ratio_lead_win':
                            //Tính tỉ lệ chăm sóc KHTN thành công
                            $this->calculateRatioLeadWin([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_lead_convert_deal':
                            //Tính lead chuyển đổi thành deal
                            $this->calculateConvertLeadIntoDeal([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_deal_convert_order':
                            //Tính dead thành công
                            $this->calculateDealWin([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_lead_team':
                            //Tính số lượng lead được tạo cho nhóm
                            $this->calculateLeadCreateByTeam([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_lead_follow_team':
                            //TÍnh số lượng lead quan tâm theo nhóm
                            $this->calculateLeadFollowByTeam([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_avg_revenue_order_team':
                            //Tính giá trị trung bình mỗi đơn hàng theo nhóm
                            $this->calculateAvgRevenueOrderByTeam([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                        case 'kpi_ctr_sale':
                            //Tính CTR Sales
                            $this->calculateCtrSale([
                                'kpi_criteria_id' => $v['kpi_criteria_id'],
                                'kpi_note_detail_id' => $v['kpi_note_detail_id'],
                                'staff_id' => $v['staff_id'],
                                'branch_id' => $v['branch_id'],
                                'department_id' => $v['department_id'],
                                'team_id' => $v['team_id'],
                                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                                'day' => $day,
                                'week' => Carbon::now()->subDays(1)->isoWeek,
                                'month' => $month,
                                'year' => $year,
                                'full_time' => $year.'-'.$month.'-'.$day,
                                'kpi_criteria_unit_id' => $v['kpi_criteria_unit_id']
                            ]);
                            break;
                    }
                }
            }

            echo 'Tính thành công';
        } catch (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
        }
    }

    /**
     * Tính số lượng đơn hàng có trạng thái đã xác nhận
     *
     * @param $input
     * @return bool|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateOrderConfirm($input)
    {
        $mOrder = app()->get(OrderTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy đơn hàng đã xác nhận
        $getOrderConfirm = $mOrder->getDataOrderConfirmByDay($input['staff_id'], $input['date']);

        $total = count($getOrderConfirm);

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getOrderConfirm) > 0) {
                foreach ($getOrderConfirm as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::ORDER,
                        'object_id' => $v['order_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }


        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính số lượng đơn hàng có trạng thái đã xoá
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateOrderCancel($input)
    {
        $mOrder = app()->get(OrderTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Đếm số đơn hàng đã xác nhận
        $getOrderConfirm = $mOrder->getDataOrderCancelByDay($input['staff_id'], $input['date']);

        $total = count($getOrderConfirm);

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getOrderConfirm) > 0) {
                foreach ($getOrderConfirm as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::ORDER,
                        'object_id' => $v['order_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính số lượng hợp đồng đang thực hiện
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateContractProcessing($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Đếm số hợp đồng đăng thực hiện
        $getContract = $mCustomerContract->getDateContractProcessingByDay($input['staff_id'], $input['date']);

        $total = count($getContract);

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getContract) > 0) {
                foreach ($getContract as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::CONTRACT,
                        'object_id' => $v['customer_contract_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính tổng doanh thu hợp đồng mới
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRevenueContract($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy hợp đồng đang thực hiện
        $getContract = $mCustomerContract->getDataContractByDay($input['staff_id'], $input['date']);

        $total = 0;

        if (count($getContract) > 0) {
            foreach ($getContract as $v) {
                $total += $v['total'];
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getContract) > 0) {
                foreach ($getContract as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::CONTRACT,
                        'object_id' => $v['customer_contract_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính tổng doanh thu hợp đồng kí lần đầu tiên
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRevenueContractFirst($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy hợp đồng kí lần đầu tiên
        $getContract = $mCustomerContract->getDataContractFirstByDay($input['staff_id'], $input['date']);

        $total = 0;

        if (count($getContract) > 0) {
            foreach ($getContract as $v) {
                $total += $v['total'];
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getContract) > 0) {
                foreach ($getContract as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::CONTRACT,
                        'object_id' => $v['customer_contract_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính tổng doanh thu hợp đồng của KH mới (lần đầu mua hàng)
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRevenueContractByFirstCustomer($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mOrder = app()->get(OrderTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $total = 0;

        $detail = [];

        //Lấy dữ liệu hợp đồng của nhân viên
        $getContract = $mCustomerContract->getDataContractByDay($input['staff_id'], $input['date']);

        if (count($getContract) > 0) {
            foreach ($getContract as $v) {
                //Kiểm tra có phải đơn hàng đầu tiên của khách hàng không
                $checkFirstOrder = $mOrder->getOrderByCustomer($v['customer_id'], $v['order_id'], $v['created_at']);

                if (count($checkFirstOrder) == 0) {
                    $total += $v['total'];

                    $detail [] = $v;
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($detail) > 0) {
                foreach ($detail as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::CONTRACT,
                        'object_id' => $v['customer_contract_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính tổng giá trị hợp đồng của KH cũ
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRevenueContractByOldCustomer($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mOrder = app()->get(OrderTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $total = 0;

        $detail = [];

        //Lấy dữ liệu hợp đồng của nhân viên
        $getContract = $mCustomerContract->getDataContractByDay($input['staff_id'], $input['date']);

        if (count($getContract) > 0) {
            foreach ($getContract as $v) {
                //Kiểm tra có phải đơn hàng đầu tiên của khách hàng không
                $checkFirstOrder = $mOrder->getOrderByCustomer($v['customer_id'], $v['order_id'], $v['created_at']);

                if (count($checkFirstOrder) > 0) {
                    $total += $v['total'];

                    $detail [] = $v;
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($detail) > 0) {
                foreach ($detail as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::CONTRACT,
                        'object_id' => $v['customer_contract_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính tổng giá trị hợp đồng tái kí
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRevenueContractReNew($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy data hợp đồng tái ký
        $getContract = $mCustomerContract->getDataContractReNew($input['staff_id'], $input['date']);

        $total = 0;

        if (count($getContract) > 0) {
            foreach ($getContract as $v) {
                $total += $v['total'];
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getContract) > 0) {
                foreach ($getContract as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::CONTRACT,
                        'object_id' => $v['customer_contract_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính doanh số chung của NV trong phòng ban
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRevenueContractByDepartment($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mStaff = app()->get(StaffsTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $total = 0;

        //Lấy thông tin nhân viên
        $infoStaff = $mStaff->getInfo($input['staff_id']);

        if ($infoStaff != null) {
            //Đếm số hợp đồng đăng thực hiện
            $getContract = $mCustomerContract->getDataContractByDepartment($infoStaff['department_id'], $input['date']);

            if (count($getContract) > 0) {
                foreach ($getContract as $v) {
                    $total += $v['total'];
                }
            }

            //Kiểm tra log data theo ngày của NV đã có chưa
            $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
                $input['kpi_criteria_id'],
                $input['branch_id'],
                $input['department_id'],
                $input['team_id'],
                $input['staff_id'],
                $input['day'],
                $input['year']
            );

            if ($getCalculateKpi == null) {
                //Insert data tính kpi
                $calculateId = $mCalculateKpi->add([
                    'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                    'kpi_criteria_id' => $input['kpi_criteria_id'],
                    'branch_id' => $input['branch_id'],
                    'department_id' => $input['department_id'],
                    'staff_id' => $input['staff_id'],
                    'team_id' => $input['team_id'],
                    'day' => $input['day'],
                    'week' => $input['week'],
                    'month' => $input['month'],
                    'year' => $input['year'],
                    'total' => $total,
                    'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
                ]);

                $dataDetail = [];

                if (count($getContract) > 0) {
                    foreach ($getContract as $v) {
                        $dataDetail [] = [
                            'calculate_kpi_id' => $calculateId,
                            'object_type' => self::CONTRACT,
                            'object_id' => $v['customer_contract_id'],
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }

                //Insert data detail
                $mCalculateKpiDetail->insert($dataDetail);
            }
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính tỉ lệ chuyển đổi deal thành công
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRatioDealConvertOrder($input)
    {
        $mDeal = app()->get(CpoDealTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $startDate = $input['year'] . '-' . $input['month'] . '-' . "01";
        $endDate = $input['date'];

        //Lấy thông tin deal được nv chăm sóc
        $getDeal = $mDeal->getDealBySale($input['staff_id'], $startDate, $endDate);

        $totalDeal = count($getDeal);

        $totalSuccess = 0;

        $detailOrderSuccess = [];

        if (count($getDeal) > 0) {
            $mOrder = app()->get(OrderTable::class);

            foreach ($getDeal as $v) {
                if ($v['customer_id'] != null && $v['order_id'] != null) {
                    //Lấy thông tin đơn hàng thành công
                    $infoOrderSuccess = $mOrder->getInfoOrderSuccess($v['customer_id'], $v['order_id'], $input['date']);

                    if ($infoOrderSuccess != null) {
                        $totalSuccess++;

                        $detailOrderSuccess [] = $infoOrderSuccess;
                    }
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        $total = $totalDeal > 0 ? floatval($totalSuccess / $totalDeal * 100) : 0;

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getDeal) > 0) {
                foreach ($getDeal as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::DEAL,
                        'object_id' => $v['deal_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            if (count($detailOrderSuccess) > 0) {
                foreach ($detailOrderSuccess as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::ORDER,
                        'object_id' => $v['order_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính khách hàng quay lại
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateCustomerBack($input)
    {
        $mCustomerContract = app()->get(CustomerContractTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $total = 0;

        $detail = [];

        //Lấy dữ liệu hợp đồng của nhân viên
        $getContract = $mCustomerContract->getContractNewGroupByCustomerInDay($input['staff_id'], $input['date']);

        if (count($getContract) > 0) {
            foreach ($getContract as $v) {
                //Kiểm tra hợp đồng của KH trước đó đã phát sinh chưa
                $getContractPast = $mCustomerContract->getContractPast($v['customer_id'], $v['customer_contract_id'], $input['date']);

                if (count($getContractPast) > 0) {
                    $total++;

                    $detail [] = $v;
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($detail) > 0) {
                foreach ($detail as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::CONTRACT,
                        'object_id' => $v['customer_contract_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Thời gian chăm sóc lead lần đầu
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateLeadFirstCare($input)
    {
        $mLead = app()->get(CpoLeadTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $total = 0;

        $startDate = $input['year'] . '-' . $input['month'] . '-' . "01";
        $endDate = $input['date'];

        //Lấy lead được sale chăm sóc trong ngày
        $getLead = $mLead->getLeadBySale($input['staff_id'], $startDate, $endDate);

        $detail = [];

        if (count($getLead) > 0) {
            $mCustomerCare = app()->get(CpoCustomerCareTable::class);
            $mOcHistory = app()->get(OcHistoryTable::class);

            foreach ($getLead as $v) {
                //Lấy lần đầu chăm sóc của KHTN
                $getFirstCare = $mCustomerCare->getFirstCall($v['customer_lead_code']);

                if ($getFirstCare != null
                    && $getFirstCare['care_type'] == 'call'
                    && $getFirstCare['object_id'] != null
                    && Carbon::parse($getFirstCare['created_at'])->format('Y-m-d') == $input['date']
                ) {
                    //Lấy lịch sử cuộc gọi
                    $historyCall = $mOcHistory->getHistoryCall($getFirstCare['object_id']);

                    if ($historyCall != null) {
                        $total += $historyCall['total_reply_time'];

                        $detail [] = $getFirstCare;
                    }
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => floatval($total / 60),
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($detail) > 0) {
                foreach ($detail as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::LEAD_CARE,
                        'object_id' => $v['customer_care_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => floatval($total / 60)
        ];
    }

    /**
     * Thời gian gọi trung bình chăm sóc lead
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateAvgLeadCare($input)
    {
        $mOcHistory = app()->get(OcHistoryTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy lịch sử gọi chăm sóc KHTN của sale trong thời gian áp dụng kpi
        $historyCall = $mOcHistory->getHistoryCallByLeadInDay($input['staff_id'], $input['date']);

        $totalCall = count($historyCall);
        $totalReplyTime = 0;

        if (count($historyCall) > 0) {
            foreach ($historyCall as $v) {
                $totalReplyTime += $v['total_reply_time'] / 60;
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => floatval($totalReplyTime / $totalCall),
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($historyCall) > 0) {
                foreach ($historyCall as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::HISTORY_CALL,
                        'object_id' => $v['history_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => floatval($totalReplyTime / $totalCall)
        ];
    }

    /**
     * Tính tỉ lệ chăm sóc KHTN thành công
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateRatioLeadWin($input)
    {
        $mLead = app()->get(CpoLeadTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $startDate = $input['year'] . '-' . $input['month'] . '-' . "01";
        $endDate = $input['date'];

        //Lấy lead được sale chăm sóc trong thời gian áp dụng kpi
        $getLead = $mLead->getLeadBySale($input['staff_id'], $startDate, $endDate);

        $totalLead = count($getLead);
        $totalLeadWin = 0;

        $detailWin = [];

        if (count($getLead) > 0) {
            $mDeal = app()->get(CpoDealTable::class);
            $mOrder = app()->get(OrderTable::class);

            foreach ($getLead as $v) {
                if ($v['is_convert'] == 1) {
                    $customerId = null;

                    switch ($v['convert_object_type']) {
                        case 'deal':
                            //Chuyển đổi thành deal
                            $infoDeal = $mDeal->getInfoByCode($v['convert_object_code']);

                            if ($infoDeal != null && $infoDeal['customer_id'] != null && $infoDeal['order_id'] != null) {
                                //Lấy thông tin đơn hàng thành công
                                $infoOrderSuccess = $mOrder->getInfoOrderSuccess($infoDeal['customer_id'], $infoDeal['order_id'], $input['date']);

                                if ($infoOrderSuccess != null) {
                                    $totalLeadWin++;
                                    $detailWin [] = $v;
                                }
                            }
                            break;
                    }
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => floatval($totalLeadWin / $totalLead * 100),
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getLead) > 0) {
                foreach ($getLead as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::LEAD,
                        'object_id' => $v['customer_lead_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            if (count($detailWin) > 0) {
                foreach ($detailWin as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::LEAD_WIN,
                        'object_id' => $v['customer_lead_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => floatval($totalLeadWin / $totalLead * 100)
        ];
    }

    /**
     * Tính số lượng lead chuyển đổi thành deal
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateConvertLeadIntoDeal($input)
    {
        $mLead = app()->get(CpoLeadTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $startDate = $input['year'] . '-' . $input['month'] . '-' . "01";
        $endDate = $input['date'];

        //Đếm số lead chuyển đổi thành deal
        $getLeadConvertDeal = $mLead->getDataLeadConvertIntoDealBySale($input['staff_id'], $startDate, $endDate);

        $total = 0;

        $detail = [];

        if (count($getLeadConvertDeal) > 0) {
            $mDeal = app()->get(CpoDealTable::class);

            foreach ($getLeadConvertDeal as $v) {
                //Lấy thông tin deal
                $infoDeal = $mDeal->getInfoByCode($v['convert_object_code']);

                if ($infoDeal != null && Carbon::parse($infoDeal['created_at'])->format('Y-m-d') == $input['date']) {
                    $total ++;

                    $detail [] = $v;
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($detail) > 0) {
                foreach ($detail as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::LEAD,
                        'object_id' => $v['customer_lead_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính số lượng deal win
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateDealWin($input)
    {
        $mDeal = app()->get(CpoDealTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Đếm số deal win
        $getDealWin = $mDeal->getDealWinInDay($input['staff_id'], $input['date']);

        $total = count($getDealWin);

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getDealWin) > 0) {
                foreach ($getDealWin as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::DEAL,
                        'object_id' => $v['deal_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính khách hàng tiềm năng được phân bổ cho nhóm
     *
     * @param $input
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateLeadCreateByTeam($input)
    {
        $mLead = app()->get(CpoLeadTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy lead được phân bổ cho team trong ngày
        $getLead = $mLead->getLeadCreateInDayByTeam($input['team_id'], $input['date']);

        $total = count($getLead);

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getLead) > 0) {
                foreach ($getLead as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::LEAD,
                        'object_id' => $v['customer_lead_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính lead được quan tâm cho nhóm
     *
     * @param $input
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateLeadFollowByTeam($input)
    {
        $mLead = app()->get(CpoLeadTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy lead được quan tâm cho team trong ngày
        $getLead = $mLead->getDataLeadConvertIntoDealByTeam($input['team_id'], $input['date']);

        $total = count($getLead);

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getLead) > 0) {
                foreach ($getLead as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::LEAD,
                        'object_id' => $v['customer_lead_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total
        ];
    }

    /**
     * Tính giá trị trung bình mỗi đơn hàng theo nhóm
     *
     * @param $input
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateAvgRevenueOrderByTeam($input)
    {
        $mOrder = app()->get(OrderTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        //Lấy đơn hàng của team được tạo trong ngày
        $getOrder = $mOrder->getDataOrderByTeamInDay($input['team_id'], $input['date']);

        $total = count($getOrder);

        $amount = 0;

        $detailSuccess = [];

        if (count($getOrder) > 0) {
            foreach ($getOrder as $v) {
                if (in_array($v['process_status'], ['paysuccess', 'pay-half'])) {
                    $amount += $v['total'];

                    $detailSuccess [] = $v;
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => $total > 0 ? floatval($amount / $total) : 0,
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getOrder) > 0) {
                foreach ($getOrder as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::ORDER,
                        'object_id' => $v['order_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            if (count($detailSuccess) > 0) {
                foreach ($detailSuccess as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::ORDER_SUCCESS,
                        'object_id' => $v['order_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => $total > 0 ? floatval($amount / $total) : 0
        ];
    }

    /**
     * Tính CTR Sales
     *
     * @param $input
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function calculateCtrSale($input)
    {
        $mLead = app()->get(CpoLeadTable::class);
        $mOrder = app()->get(OrderTable::class);
        $mCalculateKpi = app()->get(CalculateKpiTable::class);
        $mCalculateKpiDetail = app()->get(CalculateKpiDetailTable::class);

        $startDate = $input['year'] . '-' . $input['month'] . '-' . "01";
        $endDate = $input['date'];

        //Lấy data lead được phân bổ cho team trong khoảng thời gian
        $getLead = $mLead->getDataLeadByTeamRangeTime($input['team_id'], $startDate, $endDate);

        $total = count($getLead);
        $amount = 0;

        $detailWin = [];

        if (count($getLead) > 0) {
            foreach ($getLead as $v) {
                //Lấy thông tin đơn hàng thành công
                $infoOrderSuccess = $mOrder->getInfoOrderSuccess($v['customer_id'], $v['order_id'], $input['date']);

                if ($infoOrderSuccess != null) {
                    $amount += $infoOrderSuccess['total'];

                    $detailWin [] = $infoOrderSuccess;
                }
            }
        }

        //Kiểm tra log data theo ngày của NV đã có chưa
        $getCalculateKpi = $mCalculateKpi->getCalculateKpiByDate(
            $input['kpi_criteria_id'],
            $input['branch_id'],
            $input['department_id'],
            $input['team_id'],
            $input['staff_id'],
            $input['day'],
            $input['year']
        );

        if ($getCalculateKpi == null) {
            //Insert data tính kpi
            $calculateId = $mCalculateKpi->add([
                'kpi_note_detail_id' => $input['kpi_note_detail_id'],
                'kpi_criteria_id' => $input['kpi_criteria_id'],
                'branch_id' => $input['branch_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'team_id' => $input['team_id'],
                'day' => $input['day'],
                'week' => $input['week'],
                'month' => $input['month'],
                'year' => $input['year'],
                'total' => floatval($amount / $total),
                'kpi_criteria_unit_id' => $input['kpi_criteria_unit_id']
            ]);

            $dataDetail = [];

            if (count($getLead) > 0) {
                foreach ($getLead as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::LEAD,
                        'object_id' => $v['customer_lead_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            if (count($detailWin) > 0) {
                foreach ($detailWin as $v) {
                    $dataDetail [] = [
                        'calculate_kpi_id' => $calculateId,
                        'object_type' => self::ORDER,
                        'object_id' => $v['order_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert data detail
            $mCalculateKpiDetail->insert($dataDetail);
        }

        return [
            'date' => $input['date'],
            'total' => floatval($amount / $total)
        ];
    }
}