<?php

/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 09/04/2022
 * Time: 10:46
 */

namespace Modules\StaffSalary\Http\Controllers;

use App\Exports\ExportFile;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use Modules\StaffSalary\Models\StaffTable;
use Modules\StaffSalary\Models\TimeWorkingStaffRecompenseTable;
use Modules\StaffSalary\Repositories\StaffSalary\StaffSalaryRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryAttribute\StaffSalaryAttributeRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryConfig\StaffSalaryConfigRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryDetail\StaffSalaryDetailRepoInterface;
use Modules\StaffSalary\Repositories\StaffHoliday\StaffHolidayRepoInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PHPUnit\Framework\Constraint\Count;

class StaffSalaryController extends Controller
{
    const salary_weekday = 'salary_weekday';
    const salary_sarturday = 'salary_sarturday';
    const salary_sunday = 'salary_sunday';
    const salary_holiday = 'salary_holiday';
    const salary_contract = 'salary_contract';
    const salary_monthly = 'salary_monthly';
    const pay_month = 'pay_month';
    const pay_week = 'pay_week';
    const array_salary_shift = [
        'salary_weekday',
        'salary_sarturday',
        'salary_sunday',
        'salary_holiday'
    ];
    const array_salary_month = [
        'salary_contract',
        'salary_monthly'
    ];
    protected $staffSalary;
    protected $staffSalaryAttribute;
    protected $staffSalaryConfig;
    protected $staffSalaryDetail;
    protected $staffHoliday;

    public function __construct(
        StaffSalaryRepoInterface          $staffSalary,
        StaffSalaryAttributeRepoInterface $staffSalaryAttribute,
        StaffSalaryConfigRepoInterface    $staffSalaryConfig,
        StaffSalaryDetailRepoInterface    $staffSalaryDetail,
        StaffHolidayRepoInterface         $staffHoliday
    )
    {
        $this->staffSalary = $staffSalary;
        $this->staffSalaryAttribute = $staffSalaryAttribute;
        $this->staffSalaryConfig = $staffSalaryConfig;
        $this->staffSalaryDetail = $staffSalaryDetail;
        $this->staffHoliday = $staffHoliday;
    }

    /**
     * link chạy bảng lương
     */
    public function jobGetSalary(Request $request)
    {
        if (isset($_GET['w'])) {
            $w = $_GET['w'];
            $dto = Carbon::now();
            $dto->setISODate(date('Y'), $w);
            $week_start = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $week_end = $dto->format('Y-m-d');
            $staffSalary = $this->staffSalary->getDetailByDate($week_start, $week_end);
            if ($staffSalary == null) {
                $this->jobSalary($dto->isoWeek, $week_start, $week_end, self::pay_week);
            }
            echo 'Chạy job thành công: ' . self::pay_week;
        } else if (isset($_GET['m'])) {
            $m = $_GET['m'];
            $date = Carbon::parse(date('Y') . '-' . $m . '-' . '01');
            $date->setISODate(date('Y'), $date->isoWeek);
            $week_start = $date->format('Y-m-01');
            $week_end = $date->format('Y-m-t');
            $staffSalary = $this->staffSalary->getDetailByDate($week_start, $week_end);
            if ($staffSalary == null) {
                $this->jobSalary($date->isoWeek, $week_start, $week_end, self::pay_month);
            }
            echo 'Chạy job thành công: ' . self::pay_month;
        }
    }

    /**
     * job chạy lương
     */
    public function cronJobGetSalary()
    {

        $this->jobGetSalaryMonth();
        $this->jobGetSalaryWeek();
        echo 'Chạy job thành công';
    }

    /**
     * Bảng lương
     */
    public function index(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search']);
        $list = $this->staffSalary->getList($filters);
        return view('staff-salary::staff-salary.index', [
            'LIST' => $list,
        ]);
    }


    /**
     * Chi tiết bảng lương
     */
    public function detail($id)
    {
        $staffSalary = $this->staffSalary->getDetail($id);
        $list = $this->staffSalaryDetail->getListByStaffSalary($id);
        return view('staff-salary::staff-salary.detail', [
            'staffSalary' => $staffSalary,
            'LIST' => $list,
        ]);
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search']);
        $list = $this->staffSalary->getList($filters);
        return view('staff-salary::staff-salary.list', [
            'LIST' => $list,
        ]);
    }

    /**
     * Chi tiết bảng lương
     */
    public function detailStaff(Request $request)
    {
        $staff_id = $request->staff_id;
        $staff_salary_id = $request->staff_salary_id;
        $arraySalaryAllowance = $this->staffSalary->getDetailSalaryAllowanceByStaff($staff_id);

        $staffSalaryOvertime = $this->staffSalary->getDetailSalaryOvertimeByStaff($staff_id);
        $staffSalaryAttribute = $this->staffSalaryAttribute->getDetailByStaff($staff_id);

        $arrayStaffSalaryAttribute = [];
        foreach ($staffSalaryAttribute as $key => $itemStaffSalary) {
            $arrayStaffSalaryAttribute += [
                $itemStaffSalary['staff_salary_attribute_code'] => [
                    'staff_salary_attribute_value' => $itemStaffSalary['staff_salary_attribute_value'],
                    'staff_salary_attribute_type' => $itemStaffSalary['staff_salary_attribute_type'],
                ],
            ];
        }
        $staffInfoSalary = $this->staffSalaryDetail->getDetailByStaff($staff_id, $staff_salary_id);
        $staffTimekeepingStaff = $this->staffSalary->getDetailTimeKeepingStaff($staff_id, $staff_salary_id);

        return view('staff-salary::staff-salary.detail-staff', [
            'staffInfoSalary' => $staffInfoSalary,
            'staffTimekeepingStaff' => $staffTimekeepingStaff,
            'arrayStaffSalaryAttribute' => $arrayStaffSalaryAttribute,
            'arraySalaryAllowance' => $arraySalaryAllowance,
            'staffSalaryOvertime' => $staffSalaryOvertime
        ]);
    }

    /**
     * Cập nhật bảng lương
     */
    public function salaryDetailSubmitAction(Request $request)
    {

        if ($request->ajax()) {
            $staff_salary_id = $request->staff_salary_id;
            $staffSalary = $this->staffSalary->getDetail($staff_salary_id);

            //Xóa bảng chấm công
            $this->staffSalary->deleteTimeKeepingStaff($staff_salary_id);
            //Xóa bảng lương chi tiết
            $this->staffSalaryDetail->delete($staff_salary_id);

            $listStaff = $this->staffSalaryConfig->getListByPayPeriod($staffSalary['staff_salary_pay_period_code']);
            if (isset($listStaff)) {
                //Tạo bảng lương
                if ($staff_salary_id > 0) {
                    foreach ($listStaff as $key => $tiem) {
                        //Lấy danh sách ca làm của nhân viên
                        $dataStaffSalaryWorking = $this->calculaWorking($staffSalary['start_date'], $staffSalary['end_date'], $tiem['staff_id'], $staff_salary_id);

                        //thêm vào bảo report chấm công
                        $idReturn = $this->staffSalary->addTimeKeepingStaff($dataStaffSalaryWorking);

                        //Tính lương
                        $staff_salary_main = $this->calculaSalary($dataStaffSalaryWorking, $tiem['staff_salary_type_code']);

                        //Tính thưởng phạt
                        $arraySalaryBonusMinus = $this->staffSalary->getDetailSalaryBonusMinusByStaff($tiem['staff_id']);

                        $staff_salary_bonus = 0;
                        $staff_salary_minus = 0;
                        if (isset($arraySalaryBonusMinus)) {
                            foreach ($arraySalaryBonusMinus as $value => $objSalaryBonusMinus) {
                                if ($objSalaryBonusMinus['salary_bonus_minus_type'] == 'bonus') {
                                    $staff_salary_bonus += $objSalaryBonusMinus['staff_salary_allowance_num'];
                                } else {
                                    $staff_salary_minus += $objSalaryBonusMinus['staff_salary_allowance_num'];
                                }
                            }
                        }
                        //Lấy dữ liệu lương phụ cấp
                        $staff_salary_allowance = $this->calculaSalaryAllowance($tiem['staff_id']);

                        //Lấy dữ liệu lương làm thêm giờ
                        $staff_salary_overtime = $this->calculaSalaryOvertime($dataStaffSalaryWorking);

                        if ($idReturn > 0) {
                            //thêm dữ liệu vào bảng lương chi tiết
                            $dataStaffSalaryDetail = [
                                "staff_salary_id" => $staff_salary_id,
                                "staff_id" => $tiem['staff_id'],
                                "staff_salary_type_code" => $tiem['staff_salary_type_code'],
                                "staff_salary_pay_period_code" => $tiem['staff_salary_pay_period_code'],
                                "staff_salary_overtime" => $staff_salary_overtime,
                                "staff_salary_bonus" => $staff_salary_bonus,
                                "staff_salary_allowance" => $staff_salary_allowance,
                                "staff_salary_main" => $staff_salary_main,
                                "staff_salary_received" => $staff_salary_main + $staff_salary_allowance + $staff_salary_bonus + $staff_salary_overtime - $staff_salary_minus,
                                "staff_salary_minus" => $staff_salary_minus,
                                "staff_salary_status" => 0
                            ];
                            $idStaffSalaryDetail = $this->staffSalaryDetail->add($dataStaffSalaryDetail);
                        }
                    }
                }
            }

            return response()->json(
                [
                    'status' => 1,
                    'message' => 'Cấu hình thành công'
                ]
            );
        }
    }

    /**
     * Cập nhật bảng lương
     */
    public function closeSalaryDetailSubmitAction(Request $request)
    {
        //        var_dump(json_encode($staffInfoSalary, JSON_PRETTY_PRINT));die;
        if ($request->ajax()) {
            $staff_salary_id = $request->staff_salary_id;
            $staffSalary = $this->staffSalary->getDetail($staff_salary_id);

            $staffSalaryId = $this->staffSalary->edit([
                'staff_salary_status' => 1,
            ], $staff_salary_id);
            $staffInfoSalary = $this->staffSalaryDetail->getDetail($staff_salary_id);
            if (count($staffInfoSalary) > 0) {
                foreach ($staffInfoSalary as $key => $itemSalary) {
                    $lstData = $this->staffSalary->getListWorkingStaff($staffSalary['start_date'], $staffSalary['end_date'], $itemSalary['staff_id']);
                    if (count($lstData) > 0) {
                        foreach ($lstData as $key => $item) {
                            $idWorkingStaff = $this->staffSalary->editWorkingStaff([
                                'is_close' => 1
                            ], $item['time_working_staff_id']);
                        }
                    }
                }
            }

            return response()->json(
                [
                    'status' => 1,
                    'message' => 'Cấu hình thành công'
                ]
            );
        }
    }

    /**
     *export
     * @param Request $request
     * @return void
     */
    public function exportExcelSubmitAction(Request $request)
    {
        $heading = [
            __('TÊN NHÂN VIÊN'),
            __('CHI NHÁNH'),
            __('PHÒNG BAN'),
            __('LƯƠNG CHÍNH'),
            __('LÀM THÊM'),
            __('PHỤ CẤP'),
            __('THƯỞNG'),
            __('PHẠT'),
            __('THỰC NHẬN')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        $data = [];

        $lstData = $this->staffSalaryDetail->getListByStaffSalary($request->staff_salary_id);
        if (count($lstData) > 0) {
            foreach ($lstData as $v) {
                $staff_salary_main = $v['staff_salary_main'] ?? 0;
                $staff_salary_allowance = $v['staff_salary_allowance'] ?? 0;
                $staff_salary_overtime = $v['staff_salary_overtime'] ?? 0;
                $staff_salary_bonus = $v['staff_salary_bonus'] ?? 0;
                $staff_salary_minus = $v['staff_salary_minus'] ?? 0;

                $data[] = [
                    $v['staff_name'],
                    $v['branch_name'],
                    $v['department_name'],
                    number_format($staff_salary_main),
                    number_format($staff_salary_overtime),
                    number_format($staff_salary_allowance),
                    number_format($staff_salary_bonus),
                    number_format($staff_salary_minus),
                    number_format($staff_salary_main + $staff_salary_allowance + $staff_salary_bonus - $staff_salary_minus)
                ];
            }
        }
        return Excel::download(new ExportFile($heading, $data), 'export-staff-salary.xlsx');
    }


    /**
     * add holiday
     *
     * @return mixed
     */
    public function addAction(Request $request)
    {


        if ($request->ajax()) {
            $staff_salary_config_id = $request->staff_salary_config_id;
            $staff_salary_type_code = $request->staff_salary_type_code;
            $staff_salary_unit_code = $request->staff_salary_unit_code;
            $payment_type = $request->payment_type;
            //$staff_salary_template_id = $request->staff_salary_template_id;
            $staff_salary_pay_period_code = $request->staff_salary_pay_period_code;
            $staff_salary_attribute_value = 0;
            $staff_salary_attribute_type = "money";

            $dataConfig = [
                "staff_id" => $request->staff_id,
                "staff_salary_type_code" => $staff_salary_type_code,
                "staff_salary_pay_period_code" => $staff_salary_pay_period_code,
                //"staff_salary_template_id" => $staff_salary_template_id,
                "staff_salary_unit_code" => $staff_salary_unit_code,
                "payment_type" => $payment_type,
                "updated_at" => Carbon::now(),
            ];

            if ($staff_salary_config_id != "") {

                $staffSalaryConfigId = $this->staffSalaryConfig->edit($dataConfig, (int)$staff_salary_config_id);
            } else {
                $staffSalaryConfigId = $this->staffSalaryConfig->add($dataConfig);
            }

            //xoá data cũ
            $idSalaryAttribute = $this->staffSalaryAttribute->deleteByStaff($request->staff_id);

            //Thêm data mới
            if ($staff_salary_type_code == "shift" || $staff_salary_type_code == "hourly") {
                foreach (self::array_salary_shift as $key => $item) {
                    switch ($item) {
                        case self::salary_weekday:
                            $staff_salary_attribute_value = str_replace(',', '', $request->staff_salary_weekday);
                            break;
                        case self::salary_sarturday:
                            $staff_salary_attribute_value = str_replace(',', '', $request->staff_salary_saturday);
                            $staff_salary_attribute_type = $request->staff_salary_saturday_type;
                            break;
                        case self::salary_sunday:
                            $staff_salary_attribute_value = str_replace(',', '', $request->staff_salary_sunday);
                            $staff_salary_attribute_type = $request->staff_salary_sunday_type;
                            break;
                        case self::salary_holiday:
                            $staff_salary_attribute_value = str_replace(',', '', $request->staff_salary_holiday);
                            $staff_salary_attribute_type = $request->staff_salary_holiday_type;
                            break;
                    }
                    $obj = [
                        "staff_id" => $request->staff_id,
                        "branch_id" => $request->branch_id,
                        "staff_salary_attribute_code" => $item,
                        "staff_salary_attribute_value" => $staff_salary_attribute_value,
                        "staff_salary_attribute_type" => $staff_salary_attribute_type,
                    ];
                    $idSalaryAttribute = $this->staffSalaryAttribute->add($obj);
                }
            } else if ($staff_salary_type_code == "monthly") {
                foreach (self::array_salary_month as $key => $item) {
                    switch ($item) {
                        case self::salary_contract:
                            $staff_salary_attribute_value = str_replace(',', '', $request->staff_salary_contract);
                            break;
                        case self::salary_monthly:
                            $staff_salary_attribute_value = str_replace(',', '', $request->staff_salary_monthly);
                            break;
                    }
                    $obj = [
                        "staff_id" => $request->staff_id,
                        "branch_id" => $request->branch_id,
                        "staff_salary_attribute_code" => $item,
                        "staff_salary_attribute_value" => $staff_salary_attribute_value,
                        "staff_salary_attribute_type" => $staff_salary_attribute_type,
                    ];
                    $idSalaryAttribute = $this->staffSalaryAttribute->add($obj);
                }
            }


            $array_allowance = $request->array_allowance;
            $array_bonus_minus = $request->array_bonus_minus;
            $array_overtime = $request->array_overtime;

            //Add salary overtime
            $idOvertime = $this->staffSalary->deleteSalaryOvertimeByStaff($request->staff_id);
            if (isset($array_overtime)) {
                foreach ($array_overtime as $item) {
                    $dataOvertime = [
                        "staff_id" => $request->staff_id,
                        "branch_id" => $item['branch_id'],
                        "staff_salary_overtime_weekday" => $item['staff_salary_overtime_weekday'],
                        "staff_salary_overtime_holiday" => $item['staff_salary_overtime_holiday'],
                        "staff_salary_overtime_holiday_type" => $item['staff_salary_overtime_holiday_type'],
                        "staff_salary_overtime_saturday" => $item['staff_salary_overtime_saturday'],
                        "staff_salary_overtime_saturday_type" => $item['staff_salary_overtime_saturday_type'],
                        "staff_salary_overtime_sunday" => $item['staff_salary_overtime_sunday'],
                        "staff_salary_overtime_sunday_type" => $item['staff_salary_overtime_sunday_type'],
                    ];
                    $idOvertime = $this->staffSalary->addSalaryOvertime($dataOvertime);
                }
            }


            //Add salary allowance
            $idAllowance = $this->staffSalary->deleteSalaryAllowanceByStaff($request->staff_id);
            if (isset($array_allowance)) {
                foreach ($array_allowance as $item) {
                    $dataAllowance = [
                        "staff_id" => $request->staff_id,
                        "salary_allowance_id" => $item['salary_allowance_id'],
                        "staff_salary_allowance_num" => str_replace(',', '', $item['staff_salary_allowance_num'])
                    ];
                    $idAllowance = $this->staffSalary->addSalaryAllowance($dataAllowance);
                }
            }

            //Add salary bonus minus
            $idBonusMinus = $this->staffSalary->deleteSalaryBonusMinusByStaff($request->staff_id);
            if (isset($array_bonus_minus)) {
                foreach ($array_bonus_minus as $itemBonusMinus) {
                    $dataBonusMinus = [
                        "staff_id" => $request->staff_id,
                        "salary_bonus_minus_id" => $itemBonusMinus['salary_bonus_minus_id'],
                        "staff_salary_bonus_minus_num" => str_replace(',', '', $itemBonusMinus['staff_salary_bonus_minus_num'])
                    ];
                    $idBonusMinus = $this->staffSalary->addSalaryBonusMinus($dataBonusMinus);
                }
            }
            return response()->json(
                [
                    'status' => 1,
                    'message' => __('Cấu hình thành công')
                ]
            );
        }
    }

    /***Tính lương theo tuần
     * @return void
     */
    public function jobGetSalaryWeek()
    {
        $weekday = Carbon::now();
        $weekday = $weekday->dayOfWeek;
        $weekday = 1;
        if ($weekday == 1) {
            $dto = Carbon::now();
            $dto = $dto->addDay(-1);
            $dto->setISODate(date('Y'), $dto->isoWeek);
            $week_start = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $week_end = $dto->format('Y-m-d');
            $staffSalary = $this->staffSalary->getDetailByDate($week_start, $week_end);
            if ($staffSalary == null) {
                $this->jobSalary($dto->isoWeek, $week_start, $week_end, self::pay_week);
            }
        }
    }

    /***Tính lương theo tháng
     * @return void
     */
    public function jobGetSalaryMonth()
    {
        $daymonth = date('d');
        if ($daymonth == '01') {
            $dto = Carbon::now();
            $dto->setISODate(date('Y'), $dto->isoWeek);
            $date = Carbon::now();
            $date = $date->addMonth(-1);
            $week_start = $date->format('Y-m-01');
            $week_end = $date->format('Y-m-t');
            $staffSalary = $this->staffSalary->getDetailByDate($week_start, $week_end);
            if ($staffSalary == null) {
                $this->jobSalary($dto->isoWeek, $week_start, $week_end, self::pay_month);
            }
        }
    }

    /**
     * report lương nhân viên
     * @param $week
     * @param $week_start
     * @param $week_end
     * @param $pay_type
     * @return void
     */
    public function jobSalary($week, $week_start, $week_end, $pay_type)
    {
        $listStaff = $this->staffSalaryConfig->getListByPayPeriod($pay_type);

        if (isset($listStaff) && count($listStaff) > 0) {
            //Tạo bảng lương
            $dataStaffSalary = [
                "staff_salary_type_code" => $listStaff[0]['staff_salary_type_code'],
                "staff_salary_pay_period_code" => $listStaff[0]['staff_salary_pay_period_code'],
                "staff_salary_days" => date('d'),
                "staff_salary_months" => date('m'),
                "staff_salary_years" => date('Y'),
                "staff_salary_weeks" => $week,
                "start_date" => $week_start,
                "end_date" => $week_end,
            ];

            $staff_salary_id = $this->staffSalary->add($dataStaffSalary);

            if ($staff_salary_id > 0) {

                $mTimeWorkingStaffRecompense = app()->get(TimeWorkingStaffRecompenseTable::class);

                foreach ($listStaff as $key => $tiem) {
                    //Lấy danh sách ca làm của nhân viên
                    $dataStaffSalaryWorking = $this->calculaWorking($week_start, $week_end, $tiem['staff_id'], $staff_salary_id);
                    //thêm vào bảo report chấm công
                    $idReturn = $this->staffSalary->addTimeKeepingStaff($dataStaffSalaryWorking);

                    //Tính lương
                    $staff_salary_main = $this->calculaSalary($dataStaffSalaryWorking, $tiem['staff_salary_type_code']);

                    //Tính thưởng phạt
                    $arraySalaryBonusMinus = $mTimeWorkingStaffRecompense->getRecompenseByStaff($tiem['staff_id'], $week_start, $week_end);
//                $arraySalaryBonusMinus = $this->staffSalary->getDetailSalaryBonusMinusByStaff($tiem['staff_id']);

                    $staff_salary_bonus = 0;
                    $staff_salary_minus = 0;
                    if (isset($arraySalaryBonusMinus)) {
                        foreach ($arraySalaryBonusMinus as $value => $objSalaryBonusMinus) {
//                            if ($objSalaryBonusMinus['salary_bonus_minus_type'] == 'bonus') {
//                                $staff_salary_bonus += $objSalaryBonusMinus['staff_salary_allowance_num'];
//                            } else {
//                                $staff_salary_minus += $objSalaryBonusMinus['staff_salary_allowance_num'];
//                            }

                            if ($objSalaryBonusMinus['type'] == 'R') {
                                //Thưởng
                                $staff_salary_bonus += $objSalaryBonusMinus['money'];
                            } else {
                                //Phạt
                                $staff_salary_minus += $objSalaryBonusMinus['money'];
                            }
                        }
                    }
                    //Lấy dữ liệu lương phụ cấp
                    $staff_salary_allowance = $this->calculaSalaryAllowance($tiem['staff_id']);

                    //Lấy dữ liệu lương làm thêm giờ
                    $staff_salary_overtime = $this->calculaSalaryOvertime($dataStaffSalaryWorking);

                    if ($idReturn > 0) {
                        //thêm dữ liệu vào bảng lương chi tiết
                        $dataStaffSalaryDetail = [
                            "staff_salary_id" => $staff_salary_id,
                            "staff_id" => $tiem['staff_id'],
                            "staff_salary_type_code" => $tiem['staff_salary_type_code'],
                            "staff_salary_pay_period_code" => $tiem['staff_salary_pay_period_code'],
                            "staff_salary_overtime" => $staff_salary_overtime,
                            "staff_salary_bonus" => $staff_salary_bonus,
                            "staff_salary_allowance" => $staff_salary_allowance,
                            "staff_salary_main" => $staff_salary_main,
                            "staff_salary_received" => $staff_salary_main + $staff_salary_allowance + $staff_salary_bonus + $staff_salary_overtime - $staff_salary_minus,
                            "staff_salary_minus" => $staff_salary_minus,
                            "staff_salary_status" => 0
                        ];
                        $idStaffSalaryDetail = $this->staffSalaryDetail->add($dataStaffSalaryDetail);
                    }
                }
            }
        }
    }

    /**
     * Tính công nhân viên
     * @param $week_start
     * @param $week_end
     * @param $staff_id
     * @param $staff_salary_id
     * @return array
     */
    public function calculaWorking($week_start, $week_end, $staff_id, $staff_salary_id)
    {
        $total_day_late = 0;
        $total_late_time = 0;
        $total_day_back_soon = 0;
        $total_time_back_soon = 0;
        $total_working_day = 0;
        $total_day_saturday = 0;
        $total_day_sunday = 0;
        $total_day_holiday = 0;
        $total_working_ot_day = 0;
        $total_working_ot_saturday = 0;
        $total_working_ot_sunday = 0;
        $total_working_ot_holiday = 0;
        $total_shift_off = 0;
        $total_day_paid_leave = 0;
        $total_saturday_paid_leave = 0;
        $total_sunday_paid_leave = 0;
        $total_holiday_paid_leave = 0;
        $total_day_unpaid_leave = 0;
        $total_saturday_unpaid_leave = 0;
        $total_sunday_unpaid_leave = 0;
        $total_holiday_unpaid_leave = 0;
        $total_working_ot_time = 0;
        $total_time_ot_saturday = 0;
        $total_time_ot_sunday = 0;
        $total_time_ot_holiday = 0;
        $total_working_time = 0;
        $total_time_saturday = 0;
        $total_time_sunday = 0;
        $total_time_holiday = 0;
        $total_time_paid_leave = 0;
        $total_saturday_time_paid_leave = 0;
        $total_sunday_time_paid_leave = 0;
        $total_holiday_time_paid_leave = 0;
        $total_time_unpaid_leave = 0;
        $total_saturday_time_unpaid_leave = 0;
        $total_sunday_time_unpaid_leave = 0;
        $total_holiday_time_unpaid_leave = 0;
        $total_timekeeping_coefficient = 0;
        $total_timekeeping_coefficient_saturday = 0;
        $total_timekeeping_coefficient_sunday = 0;
        $total_timekeeping_coefficient_holiday = 0;
        $total_timekeeping_coefficient_ot = 0;
        $total_timekeeping_coefficient_saturday_ot = 0;
        $total_timekeeping_coefficient_sunday_ot = 0;
        $total_timekeeping_coefficient_holiday_ot = 0;
        //Lấy dữ liệu đi làm

        $lstData = $this->staffSalary->getListWorkingStaff($week_start, $week_end, $staff_id);

        foreach ($lstData as $key => $item) {
            $dateWeek = Carbon::parse($item['working_day']);

            $holiday = $this->staffHoliday->getHolidayByDate($item['working_day']);


            if ($item['is_check_in'] === 0 && $item['is_check_out'] === 0) {

                if (Count($holiday) > 0) {
                    //Ngày lễ
                    if ($item['is_deducted'] === 0) {
                        //Nghĩ có lương
                        $total_holiday_paid_leave = $total_holiday_paid_leave + 1;
                        $total_holiday_time_paid_leave = $total_holiday_time_paid_leave + $item['time_work'];
                        $total_timekeeping_coefficient_holiday += $item['timekeeping_coefficient'];
                    } else {
                        $total_holiday_unpaid_leave = $total_holiday_unpaid_leave + 1;
                        $total_holiday_time_unpaid_leave = $total_holiday_time_unpaid_leave + $item['time_work'];
                    }
                } else {
                    switch ($dateWeek->dayOfWeek) {
                        case 6:
                            if ($item['is_deducted'] === 0) {
                                //Nghĩ có lương
                                $total_saturday_paid_leave = $total_saturday_paid_leave + 1;
                                $total_saturday_time_paid_leave = $total_saturday_time_paid_leave + $item['actual_time_work'];
                                $total_timekeeping_coefficient_saturday += $item['timekeeping_coefficient'];
                            } else {
                                $total_saturday_unpaid_leave = $total_saturday_unpaid_leave + 1;
                                $total_saturday_time_unpaid_leave = $total_saturday_time_unpaid_leave + $item['actual_time_work'];
                            }
                            break;
                        case 0:
                            if ($item['is_deducted'] === 0) {
                                //Nghĩ có lương
                                $total_sunday_paid_leave = $total_sunday_paid_leave + 1;
                                $total_sunday_time_paid_leave = $total_sunday_time_paid_leave + $item['time_work'];
                                $total_timekeeping_coefficient_sunday += $item['timekeeping_coefficient'];
                            } else {
                                $total_sunday_unpaid_leave = $total_sunday_unpaid_leave + 1;
                                $total_sunday_time_unpaid_leave = $total_sunday_time_unpaid_leave + $item['time_work'];
                            }
                            break;
                        default:
                            //Ngày thường
                            if ($item['is_deducted'] === 0) {
                                //Nghĩ có lương
                                $total_day_paid_leave = $total_day_paid_leave + 1;
                                $total_time_paid_leave = $total_time_paid_leave + $item['time_work'];
                                $total_timekeeping_coefficient += $item['timekeeping_coefficient'];
                            } else {
                                $total_day_unpaid_leave = $total_day_unpaid_leave + 1;
                                $total_time_unpaid_leave = $total_time_unpaid_leave + $item['time_work'];
                            }
                    }
                }
            } else {

                if ($item['number_late_time'] > 0 && ($item['is_approve_late'] == null || $item['is_approve_late'] == 0)) {
                    $total_day_late = $total_day_late + 1;
                    $total_late_time = $total_late_time + $item['number_late_time'];
                }
                if ($item['number_time_back_soon'] > 0 && ($item['is_approve_soon'] == null || $item['is_approve_soon'] == 0)) {
                    $total_day_back_soon = $total_day_back_soon + 1;
                    $total_time_back_soon = $total_time_back_soon + $item['number_time_back_soon'];
                }

                if (Count($holiday) > 0) {
                    //Ngày lễ

                    if ($item['is_ot'] === 1) {
                        $total_working_ot_holiday = $total_working_ot_holiday + 1;
                        $total_time_ot_holiday = $total_time_ot_holiday + ($item['actual_time_work'] * $item['timekeeping_coefficient']);
                        $total_timekeeping_coefficient_holiday_ot += $item['timekeeping_coefficient'];
                    } else {
                        $total_day_holiday = $total_day_holiday + 1;
                        $total_time_holiday = $total_time_holiday + $item['actual_time_work'];
                        $total_timekeeping_coefficient_holiday += $item['timekeeping_coefficient'];
                    }
                } else {

                    //Ngày thường
                    switch ($dateWeek->dayOfWeek) {
                        case 6:
                            if ($item['is_ot'] === 1) {
                                $total_working_ot_saturday = $total_working_ot_saturday + 1;
                                $total_time_ot_saturday = $total_time_ot_saturday + ($item['actual_time_work'] * $item['timekeeping_coefficient']);
                                $total_timekeeping_coefficient_saturday_ot += $item['timekeeping_coefficient'];
                            } else {
                                $total_day_saturday = $total_day_saturday + 1;
                                $total_time_saturday = $total_time_saturday + $item['actual_time_work'];
                                $total_timekeeping_coefficient_saturday += $item['timekeeping_coefficient'];
                            }

                            break;
                        case 0:
                            if ($item['is_ot'] === 1) {
                                $total_working_ot_sunday = $total_working_ot_sunday + 1;
                                $total_time_ot_sunday = $total_time_ot_sunday + ($item['actual_time_work'] * $item['timekeeping_coefficient']);
                                $total_timekeeping_coefficient_sunday_ot += $item['timekeeping_coefficient'];
                            } else {
                                $total_day_sunday = $total_day_sunday + 1;
                                $total_time_sunday = $total_time_sunday + $item['actual_time_work'];
                                $total_timekeeping_coefficient_sunday += $item['timekeeping_coefficient'];
                            }

                            break;
                        default:
                            if ($item['is_ot'] === 1) {
                                $total_working_ot_day = $total_working_ot_day + 1;
                                $total_working_ot_time = $total_working_ot_time + ($item['actual_time_work'] * $item['timekeeping_coefficient']);
                                $total_timekeeping_coefficient_ot += $item['timekeeping_coefficient'];
                            } else {
                                $total_working_day = $total_working_day + 1;
                                $total_working_time = $total_working_time + $item['actual_time_work'];
                                $total_timekeeping_coefficient += $item['timekeeping_coefficient'];
                            }
                    }
                }
            }
        }

        return [
            "staff_id" => $staff_id,
            "staff_salary_id" => $staff_salary_id,
            "total_working_day" => $total_working_day,
            "total_day_saturday" => $total_day_saturday,
            "total_day_sunday" => $total_day_sunday,
            "total_day_holiday" => $total_day_holiday,
            "total_working_ot_day" => $total_working_ot_day,
            "total_working_ot_saturday" => $total_working_ot_saturday,
            "total_working_ot_sunday" => $total_working_ot_sunday,
            "total_working_ot_holiday" => $total_working_ot_holiday,
            "total_working_ot_time" => $total_working_ot_time,
            "total_time_ot_saturday" => $total_time_ot_saturday,
            "total_time_ot_sunday" => $total_time_ot_sunday,
            "total_time_ot_holiday" => $total_time_ot_holiday,
            "total_working_time" => $total_working_time,
            "total_time_saturday" => $total_time_saturday,
            "total_time_sunday" => $total_time_sunday,
            "total_time_holiday" => $total_time_holiday,
            "total_day_late" => $total_day_late,
            "total_late_time" => $total_late_time,
            "total_day_back_soon" => $total_day_back_soon,
            "total_time_back_soon" => $total_time_back_soon,
            "total_shift_off" => $total_shift_off,
            "total_day_paid_leave" => $total_day_paid_leave,
            "total_saturday_paid_leave" => $total_saturday_paid_leave,
            "total_sunday_paid_leave" => $total_sunday_paid_leave,
            "total_holiday_paid_leave" => $total_holiday_paid_leave,
            "total_day_unpaid_leave" => $total_day_unpaid_leave,
            "total_saturday_unpaid_leave" => $total_saturday_unpaid_leave,
            "total_sunday_unpaid_leave" => $total_sunday_unpaid_leave,
            "total_holiday_unpaid_leave" => $total_holiday_unpaid_leave,
            "total_time_paid_leave" => $total_time_paid_leave,
            "total_saturday_time_paid_leave" => $total_saturday_time_paid_leave,
            "total_sunday_time_paid_leave" => $total_sunday_time_paid_leave,
            "total_holiday_time_paid_leave" => $total_holiday_time_paid_leave,
            "total_time_unpaid_leave" => $total_time_unpaid_leave,
            "total_saturday_time_unpaid_leave" => $total_saturday_time_unpaid_leave,
            "total_sunday_time_unpaid_leave" => $total_sunday_time_unpaid_leave,
            "total_holiday_time_unpaid_leave" => $total_holiday_time_unpaid_leave,
            "total_timekeeping_coefficient" => $total_timekeeping_coefficient,
            "total_timekeeping_coefficient_saturday" => $total_timekeeping_coefficient_saturday,
            "total_timekeeping_coefficient_sunday" => $total_timekeeping_coefficient_sunday,
            "total_timekeeping_coefficient_holiday" => $total_timekeeping_coefficient_holiday,
            "total_timekeeping_coefficient_ot" => $total_timekeeping_coefficient_ot,
            "total_timekeeping_coefficient_saturday_ot" => $total_timekeeping_coefficient_saturday_ot,
            "total_timekeeping_coefficient_sunday_ot" => $total_timekeeping_coefficient_sunday_ot,
            "total_timekeeping_coefficient_holiday_ot" => $total_timekeeping_coefficient_holiday_ot,
            "total_day_not_check_in" => 0,
            "total_day_not_check_out" => 0,
            "start_date" => $week_start,
            "end_date" => $week_end,
        ];
    }

    /**
     * Tính lương nhân viên
     * @param $dataStaffSalaryWorking
     * @param $typeSalary
     * @return float|int|mixed
     */
    public function calculaSalary($dataStaffSalaryWorking, $typeSalary)
    {

        $staffSalaryAttribute = $this->staffSalaryAttribute->getDetailByStaff($dataStaffSalaryWorking['staff_id']);
        $arrayStaffSalaryAttribute = [];
        $staff_salary_main = 0;
        foreach ($staffSalaryAttribute as $key => $itemStaffSalary) {
            $arrayStaffSalaryAttribute += [
                $itemStaffSalary['staff_salary_attribute_code'] => [
                    'staff_salary_attribute_value' => $itemStaffSalary['staff_salary_attribute_value'],
                    'staff_salary_attribute_type' => $itemStaffSalary['staff_salary_attribute_type'],
                ],
            ];
        }

        if ($typeSalary == 'monthly') {
            //Trường hợp làm lương tháng
            if (isset($arrayStaffSalaryAttribute['salary_monthly'])) {
                $staff_salary_main = $arrayStaffSalaryAttribute['salary_monthly']['staff_salary_attribute_value'];
                //trương hợp nghĩ không lương
                if ($dataStaffSalaryWorking['total_day_unpaid_leave'] > 0) {

                    $totalDay = $dataStaffSalaryWorking['total_working_day'] + $dataStaffSalaryWorking['total_day_saturday'] + $dataStaffSalaryWorking['total_day_sunday'] + $dataStaffSalaryWorking['total_day_holiday'];
                    $totalDayPaid = $dataStaffSalaryWorking['total_day_paid_leave'] + $dataStaffSalaryWorking['total_saturday_paid_leave'] + $dataStaffSalaryWorking['total_sunday_paid_leave'] + $dataStaffSalaryWorking['total_holiday_paid_leave'];
                    $totalDayUnPaid = $dataStaffSalaryWorking['total_day_unpaid_leave'] + $dataStaffSalaryWorking['total_saturday_unpaid_leave'] + $dataStaffSalaryWorking['total_sunday_unpaid_leave'] + $dataStaffSalaryWorking['total_holiday_unpaid_leave'];
                    $salaryOneday = $staff_salary_main / ($totalDay + $totalDayPaid + $totalDayUnPaid);
                    // $totalCoefficientDay = $dataStaffSalaryWorking['total_timekeeping_coefficient'] + $dataStaffSalaryWorking['total_timekeeping_coefficient_saturday'] + $dataStaffSalaryWorking['total_timekeeping_coefficient_sunday']  + $dataStaffSalaryWorking['total_timekeeping_coefficient_holiday'];
                    // $salaryOneday = $staff_salary_main / ($totalCoefficientDay + $totalDayPaid + $totalDayUnPaid);
                    $staff_salary_main = $staff_salary_main - ($salaryOneday * $totalDayUnPaid);
                }
            }
        } else if ($typeSalary == 'hourly') {
            //Trường hợp làm lương theo giờ
            if (isset($arrayStaffSalaryAttribute['salary_weekday'])) {
                $staff_salary_weekday = $arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value'];
                $staff_salary_sarturday = $arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_value'];
                $staff_salary_sunday = $arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_value'];
                $staff_salary_holiday = $arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_value'];

                $totaltime = ($dataStaffSalaryWorking['total_working_time'] ?? 0) + ($dataStaffSalaryWorking['total_time_paid_leave'] ?? 0);
                $totalTimeSaturday = ($dataStaffSalaryWorking['total_time_saturday'] ?? 0) + ($dataStaffSalaryWorking['total_saturday_time_paid_leave'] ?? 0);
                $totalTimeSunday = ($dataStaffSalaryWorking['total_time_sunday'] ?? 0) + ($dataStaffSalaryWorking['total_sunday_time_paid_leave'] ?? 0);
                $totalTimeHoliday = ($dataStaffSalaryWorking['total_time_holiday'] ?? 0) + ($dataStaffSalaryWorking['total_holiday_time_paid_leave'] ?? 0);

                $staff_salary_main = $staff_salary_weekday * $totaltime;


                //Lương thêm giờ thứ 7
                if ($arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_type'] == 'money') {
                    $staff_salary_main += $staff_salary_sarturday * $totalTimeSaturday;
                } else {
                    $staff_salary_main += ($staff_salary_weekday * $staff_salary_sarturday / 100) * $totalTimeSaturday;
                }

                //Lương thêm giờ chủ nhật
                if ($arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_type'] == 'money') {
                    $staff_salary_main += $staff_salary_sunday * $totalTimeSunday;
                } else {
                    $staff_salary_main += ($staff_salary_weekday * $staff_salary_sunday / 100) * $totalTimeSunday;
                }

                //Lương thêm giờ ngày lễ
                if ($arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_type'] == 'money') {
                    $staff_salary_main += $staff_salary_holiday * $totalTimeHoliday;
                } else {
                    $staff_salary_main += ($staff_salary_weekday * $staff_salary_holiday / 100) * $totalTimeHoliday;
                }
            }
        } else {
            //Lương theo ca
            if (isset($arrayStaffSalaryAttribute['salary_weekday'])) {
                $staff_salary_weekday = $arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value'];
                $staff_salary_sarturday = $arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_value'];
                $staff_salary_sunday = $arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_value'];
                $staff_salary_holiday = $arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_value'];

                $totalTimekeepingCoefficient = $dataStaffSalaryWorking['total_timekeeping_coefficient'] ?? 0; //hệ số công
                // $totalDay = ($dataStaffSalaryWorking['total_working_day'] ?? 0) + ($dataStaffSalaryWorking['total_day_paid_leave'] ?? 0);//số ngày làm việc
                // $totalSaturDay = ($dataStaffSalaryWorking['total_day_saturday'] ?? 0) + ($dataStaffSalaryWorking['total_saturday_paid_leave']  ?? 0);
                // $totalSunDay = ($dataStaffSalaryWorking['total_day_sunday'] ?? 0) + ($dataStaffSalaryWorking['total_sunday_paid_leave'] ?? 0);
                // $totalHoliday = ($dataStaffSalaryWorking['total_day_holiday'] ?? 0) + ($dataStaffSalaryWorking['total_holiday_paid_leave'] ?? 0);
                $totalCoefficientSaturDay = ($dataStaffSalaryWorking['total_timekeeping_coefficient_saturday'] ?? 0);
                $totalCoefficientSunDay = ($dataStaffSalaryWorking['total_timekeeping_coefficient_sunday'] ?? 0);
                $totalCoefficientHoliday = ($dataStaffSalaryWorking['total_timekeeping_coefficient_holiday'] ?? 0);

                $staff_salary_main = $staff_salary_weekday * $totalTimekeepingCoefficient;

                //Lương thứ 7
                if ($arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_type'] == 'money') {
                    $staff_salary_main += $staff_salary_sarturday * $totalCoefficientSaturDay;
                } else {
                    $staff_salary_main += ($staff_salary_weekday * $staff_salary_sarturday / 100) * $totalCoefficientSaturDay;
                }
                //Lương chủ nhật
                if ($arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_type'] == 'money') {
                    $staff_salary_main += $staff_salary_sunday * $totalCoefficientSunDay;
                } else {
                    $staff_salary_main += ($staff_salary_weekday * $staff_salary_sunday / 100) * $totalCoefficientSunDay;
                }
                //Lương ngày lễ
                if ($arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_type'] == 'money') {
                    $staff_salary_main += $staff_salary_holiday * $totalCoefficientHoliday;
                } else {
                    $staff_salary_main += ($staff_salary_weekday * $staff_salary_holiday / 100) * $totalCoefficientHoliday;
                }
            }
        }
        return $staff_salary_main;
    }

    /**
     * Tính lương phụ cấp
     * @param $staff_id
     * @return int|mixed
     */
    public function calculaSalaryAllowance($staff_id)
    {
        $arraySalaryAllowance = $this->staffSalary->getDetailSalaryAllowanceByStaff($staff_id);
        $staff_salary_allowance = 0;
        if (isset($arraySalaryAllowance)) {
            foreach ($arraySalaryAllowance as $value => $objSalaryAllowance) {
                $staff_salary_allowance += $objSalaryAllowance['staff_salary_allowance_num'];
            }
        }
        return $staff_salary_allowance;
    }

    /**
     * Tính lương thêm giờ
     * @param $dataStaffSalaryWorking
     * @return float|int
     */
    public function calculaSalaryOvertime($dataStaffSalaryWorking)
    {
        $staffSalaryOvertime = $this->staffSalary->getDetailSalaryOvertimeByStaff($dataStaffSalaryWorking['staff_id']);
        $staff_salary_overtime = 0;
        $staff_salary_saturday_overtime = 0;
        $staff_salary_sunday_overtime = 0;
        $staff_salary_holiday_overtime = 0;
        if (isset($staffSalaryOvertime)) {
            $staffSalary = $staffSalaryOvertime['staff_salary_overtime_weekday'];
            $staff_salary_overtime = $staffSalary * $dataStaffSalaryWorking['total_working_ot_time'];
            if ($staffSalaryOvertime['staff_salary_overtime_saturday_type'] == 'money') {
                $staff_salary_saturday_overtime = $staffSalaryOvertime['staff_salary_overtime_saturday'] * $dataStaffSalaryWorking['total_time_ot_saturday'];
            } else {
                $staff_salary_saturday_overtime = ($staffSalary * $staffSalaryOvertime['staff_salary_overtime_saturday'] / 100) * $dataStaffSalaryWorking['total_time_ot_saturday'];
            }
            if ($staffSalaryOvertime['staff_salary_overtime_sunday_type'] == 'money') {
                $staff_salary_sunday_overtime = $staffSalaryOvertime['staff_salary_overtime_sunday'] * $dataStaffSalaryWorking['total_time_ot_sunday'];
            } else {
                $staff_salary_sunday_overtime = ($staffSalary * $staffSalaryOvertime['staff_salary_overtime_sunday'] / 100) * $dataStaffSalaryWorking['total_time_ot_sunday'];
            }
            if ($staffSalaryOvertime['staff_salary_overtime_holiday_type'] == 'money') {
                $staff_salary_holiday_overtime = $staffSalaryOvertime['staff_salary_overtime_holiday'] * $dataStaffSalaryWorking['total_time_ot_holiday'];
            } else {
                $staff_salary_holiday_overtime = ($staffSalary * $staffSalaryOvertime['staff_salary_overtime_holiday'] / 100) * $dataStaffSalaryWorking['total_time_ot_holiday'];
            }
        }
        return $staff_salary_overtime + $staff_salary_saturday_overtime + $staff_salary_sunday_overtime + $staff_salary_holiday_overtime;
    }

    /**
     * Tính thưởng phạt
     * @param $staff_id
     * @return int[]
     */
    public function calculaSalaryBonusMinus($staff_id)
    {
        $arraySalaryBonusMinus = $this->staffSalary->getDetailSalaryBonusMinusByStaff($staff_id);

        $staff_salary_bonus = 0;
        $staff_salary_minus = 0;
        if (isset($arraySalaryBonusMinus)) {
            foreach ($arraySalaryBonusMinus as $value => $objSalaryBonusMinus) {
                if ($objSalaryBonusMinus['salary_bonus_minus_type'] == 'bonus') {
                    $staff_salary_bonus += $objSalaryBonusMinus['staff_salary_allowance_num'];
                } else {
                    $staff_salary_minus += $objSalaryBonusMinus['staff_salary_allowance_num'];
                }
            }
        }
        return [
            'staff_salary_bonus' => $staff_salary_bonus,
            'staff_salary_minus' => $staff_salary_minus
        ];
    }

    /**
     * Export phiếu lương
     *
     * @param Request $request
     * @return mixed
     */
    public function exportDetailStaff(Request $request)
    {   
        $staff_id = $request->staff_id;
        $staff_salary_id = $request->staff_salary_id;
        $arraySalaryAllowance = $this->staffSalary->getDetailSalaryAllowanceByStaff($staff_id);

        $staffSalaryOvertime = $this->staffSalary->getDetailSalaryOvertimeByStaff($staff_id);
        $staffSalaryAttribute = $this->staffSalaryAttribute->getDetailByStaff($staff_id);
        $arrayStaffSalaryAttribute = [];
        foreach ($staffSalaryAttribute as $key => $itemStaffSalary) {
            $arrayStaffSalaryAttribute += [
                $itemStaffSalary['staff_salary_attribute_code'] => [
                    'staff_salary_attribute_value' => $itemStaffSalary['staff_salary_attribute_value'],
                    'staff_salary_attribute_type' => $itemStaffSalary['staff_salary_attribute_type'],
                ],
            ];
        }
        $staffInfoSalary = $this->staffSalaryDetail->getDetailByStaff($staff_id, $staff_salary_id);
        $staffTimekeepingStaff = $this->staffSalary->getDetailTimeKeepingStaff($staff_id, $staff_salary_id);

        $mStaff = app()->get(StaffTable::class);
        $staffInfo = $mStaff->select('staffs.*', 'staff_title.staff_title_name as staff_title', 'departments.department_name as department_name')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', 'staffs.staff_title_id')
            ->leftJoin('departments', 'departments.department_id', 'staffs.department_id')
            ->where('staffs.staff_id', $staff_id)->first();

        //dd($request->all());

        //Xử lý download pdf
        $html = \View::make('staff-salary::staff-salary.pdf.detail-staff', [
            'staffInfo' => $staffInfo,
            'staffInfoSalary' => $staffInfoSalary,
            'staffTimekeepingStaff' => $staffTimekeepingStaff,
            'arrayStaffSalaryAttribute' => $arrayStaffSalaryAttribute,
            'arraySalaryAllowance' => $arraySalaryAllowance,
            'staffSalaryOvertime' => $staffSalaryOvertime
        ])->render();


        $dompdf = new Dompdf();
        // var_dump($html);die;
        $dompdf->loadHTML($html);
        $dompdf->render();

        //return $dompdf->stream('salary');
        $dompdf->stream("salary", array("Attachment" => false));
        exit(0);
    }
}
