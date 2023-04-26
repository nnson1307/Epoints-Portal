<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 14/1/2019
 * Time: 11:37
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;

class ReportCustomerAppointmentController extends Controller
{
    protected $branch;
    protected $customer_appointment;

    public function __construct(BranchRepositoryInterface $branches,
                                CustomerAppointmentRepositoryInterface $customer_appointments)
    {
        $this->customer_appointment = $customer_appointments;
        $this->branch = $branches;
    }

    public function indexAction()
    {
        $branch = $this->branch->getBranch();
        return view('admin::report.report-customer-appointment.index', [
            'optionBranch' => $branch
        ]);
    }

    public function loadIndexAction(Request $request)
    {
        $time = $request->time;
        $branch_id = $request->branch_id;
        $branch = $this->branch->getBranch();
        $year = date('Y');
        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $num_date = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
        $arr_date = [];
        $data_column = [];
        for ($i = 0; $i < $num_date; $i++) {
            $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
            $arr_date[] = $tomorrow;
        }
        //Biểu đồ miền.
        $dataValue = [];
        $dataValue[] = ['day', __('TỔNG LỊCH HẸN'), __('LỊCH HẸN MỚI'), __('ĐÃ XÁC NHẬN'), __('CHỜ PHỤC VỤ'), __('HỦY'), __('HOÀN THÀNH')];

        foreach ($arr_date as $item) {
            $format_date = Carbon::createFromFormat('d/m/Y', $item)->format('Y-m-d');
            $list_new = $this->customer_appointment->reportDateBranch($format_date, 'new', $branch_id);
            $list_confirm = $this->customer_appointment->reportDateBranch($format_date, 'confirm', $branch_id);
            $list_cancel = $this->customer_appointment->reportDateBranch($format_date, 'cancel', $branch_id);
            $list_finish = $this->customer_appointment->reportDateBranch($format_date, 'finish', $branch_id);
            $list_wait = $this->customer_appointment->reportDateBranch($format_date, 'wait', $branch_id);

            $dataValue[] = [
                substr($item, 0, -5),
                $list_new[0]['number']
                + $list_confirm[0]['number']
                + $list_wait[0]['number']
                + $list_cancel[0]['number']
                + $list_finish[0]['number'],
                $list_new[0]['number'],
                $list_confirm[0]['number'],
                $list_wait[0]['number'],
                $list_cancel[0]['number'],
                $list_finish[0]['number'],
            ];
        }

        ///Biểu đồ tròn theo nguồn lịch hẹn
        $appointment_source = $this->customer_appointment->reportAppointmentSource($year, $branch_id);
        $all_appointment_source = [];
        $data_appointment_source = [];
        foreach ($appointment_source as $item) {
            $all_appointment_source[] = $item['number_appointment_source'];

        }
        foreach ($appointment_source as $item) {
            if ($item['appointment_source_name'] != null) {
                $data_appointment_source[] = [
                    'name' => __($item['appointment_source_name']),
                    'number' => $item['number_appointment_source']
                ];
            }


        }

        ///Biểu đồ tròn theo giới tính khách hàng
        $gender_customer = $this->customer_appointment->reportGenderBranch($year, $branch_id);
        $all_gender = [];
        $data_gender = [];
        foreach ($gender_customer as $item) {
            $all_gender[] = $item['number'];

        }
        foreach ($gender_customer as $item) {
            $data_gender[] = [
                'gender' => $item['gender'],
                'number' => $item['number']
            ];
        }
        ///Biểu đồ tròn theo nguồn khách hàng
        $customer_source = $this->customer_appointment->reportCustomerSourceBranch($year, $branch_id);
        $all_cus_source = [];
        $data_cus_source = [];
        foreach ($customer_source as $item) {
            $all_cus_source[] = $item['number'];

        }
        foreach ($customer_source as $item) {
            $data_cus_source[] = [
                'name' => __($item['customer_source_name']),
                'number' => $item['number']
            ];
        }
        //Biểu đồ nguồn lịch hẹn
        $dataCustomeAppointmentSource = [];
        $dataCustomeAppointmentSource[] = ['Task', 'Hours per Day'];

        foreach ($data_appointment_source as $item) {
            $dataCustomeAppointmentSource[] = [$item['name'], $item['number']];
        }

        //Biểu đồ giới tính
        $dataCustomeGender = [];
        $dataCustomeGender[] = ['Task', 'Hours per Day'];
        foreach ($data_gender as $item) {
            if ($item['gender'] == 'male') {
                $dataCustomeGender[] = [__('Nam'), $item['number']];
            } elseif ($item['gender'] == 'female') {
                $dataCustomeGender[] = [__('Nữ'), $item['number']];
            } else {
                $dataCustomeGender[] = [__('Khác'), $item['number']];
            }
        }
        //Biểu đồ nguồn khách hàng
        $dataCustomeSource = [];
        $dataCustomeSource[] = ['Task', 'Hours per Day'];
        foreach ($data_cus_source as $item) {
            $dataCustomeSource[] = [$item['name'], $item['number']];
        }

        return response()->json([
            'data_column' => $data_column,
            'year' => $year,
            'branch' => $branch,
            'data_appointment_source' => $data_appointment_source,
            'data_gender' => $data_gender,
            'data_cus_source' => $data_cus_source,
            'dataValue' => $dataValue,
            'dataCustomeAppointmentSource' => $dataCustomeAppointmentSource,
            'dataCustomeGender' => $dataCustomeGender,
            'dataCustomeSource' => $dataCustomeSource
        ]);
    }

    public function filterBranchAction(Request $request)
    {
        $time = $request->time;
        $branch_id = $request->branch_id;
        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $num_date = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            $arr_date = [];
            $data_column = [];
            for ($i = 0; $i < $num_date; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arr_date[] = $tomorrow;
            }
            foreach ($arr_date as $item) {
                $format_date = Carbon::createFromFormat('d/m/Y', $item)->format('Y-m-d');
                $list_new = $this->customer_appointment->reportDateBranch($format_date, 'new', $branch_id);
                $list_confirm = $this->customer_appointment->reportDateBranch($format_date, 'confirm', $branch_id);
                $list_cancel = $this->customer_appointment->reportDateBranch($format_date, 'cancel', $branch_id);
                $list_finish = $this->customer_appointment->reportDateBranch($format_date, 'finish', $branch_id);
                $list_wait = $this->customer_appointment->reportYearAllBranch($format_date, 'wait', $branch_id);
                foreach ($list_new as $value) {
                    foreach ($list_confirm as $k1 => $v1) {
                        foreach ($list_cancel as $k2 => $v2) {
                            foreach ($list_finish as $k3 => $v3) {
                                foreach ($list_wait as $k4 => $v4) {
                                    $data_column[] = [
                                        'date' => $item,
                                        'number_new' => $value['number'],
                                        'number_confirm' => $v1['number'],
                                        'number_cancel' => $v2['number'],
                                        'number_finish' => $v3['number'],
                                        'number_wait' => $v4['number_wait']
                                    ];
                                }

                            }
                        }
                    }
                }
            }
            ///Biểu đồ tròn theo nguồn lịch hẹn
            $appointment_source = $this->customer_appointment->reportTimeAppointmentSource($time, $branch_id);
            $all_appointment_source = [];
            $data_appointment_source = [];
            foreach ($appointment_source as $item) {
                $all_appointment_source[] = $item['number_appointment_source'];

            }
            foreach ($appointment_source as $item) {
                if ($item['appointment_source_name'] != null) {
                    $data_appointment_source[] = [
                        'name' => $item['appointment_source_name'],
                        'number' => $item['number_appointment_source']
                    ];
                }
            }
            ///Biểu đồ tròn theo giới tính khách hàng
            $gender_customer = $this->customer_appointment->reportTimeGenderBranch($time, $branch_id);
            $all_gender = [];
            $data_gender = [];
            foreach ($gender_customer as $item) {
                $all_gender[] = $item['number'];

            }
            foreach ($gender_customer as $item) {
                $data_gender[] = [
                    'gender' => $item['gender'],
                    'number' => $item['number']
                ];
            }
            ///Biểu đồ tròn theo nguồn khách hàng
            $customer_source = $this->customer_appointment->reportTimeCustomerSourceBranch($time, $branch_id);
            $all_cus_source = [];
            $data_cus_source = [];
            foreach ($customer_source as $item) {
                $all_cus_source[] = $item['number'];

            }
            foreach ($customer_source as $item) {
                $data_cus_source[] = [
                    'name' => $item['customer_source_name'],
                    'number' => $item['number']
                ];
            }
            return response()->json([
                'time_null' => 0,
                'date' => $arr_date,
                'data_column' => $data_column,
                'data_appointment_source' => $data_appointment_source,
                'data_gender' => $data_gender,
                'data_cus_source' => $data_cus_source
            ]);
        } else {
            $month = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
            $year = date('Y');
            $data_column = [];
            foreach ($month as $item) {
                $list_new = $this->customer_appointment->reportMonthYearBranch($year, $item, 'new', $branch_id);
                $list_confirm = $this->customer_appointment->reportMonthYearBranch($year, $item, 'confirm', $branch_id);
                $list_cancel = $this->customer_appointment->reportMonthYearBranch($year, $item, 'cancel', $branch_id);
                $list_finish = $this->customer_appointment->reportMonthYearBranch($year, $item, 'finish', $branch_id);
                $list_wait = $this->customer_appointment->reportMonthYearBranch($year, $item, 'wait', $branch_id);
                foreach ($list_new as $key => $value) {
                    foreach ($list_confirm as $k1 => $v1) {
                        foreach ($list_cancel as $k2 => $v2) {
                            foreach ($list_finish as $k3 => $v3) {
                                foreach ($list_wait as $k4 => $v4) {
                                    $data_column[] = [
                                        'month' => $item . '/' . $year,
                                        'number_new' => $value['number'],
                                        'number_confirm' => $v1['number'],
                                        'number_cancel' => $v2['number'],
                                        'number_finish' => $v3['number'],
                                        'number_wait' => $v4['number']
                                    ];
                                }

                            }
                        }
                    }
                }
            }
            ///Biểu đồ tròn theo nguồn lịch hẹn
            $appointment_source = $this->customer_appointment->reportAppointmentSource($year, $branch_id);
            $all_appointment_source = [];
            $data_appointment_source = [];
            foreach ($appointment_source as $item) {
                $all_appointment_source[] = $item['number_appointment_source'];

            }
            foreach ($appointment_source as $item) {
                if ($item['appointment_source_name'] != null) {
                    $data_appointment_source[] = [
                        'name' => $item['appointment_source_name'],
                        'number' => $item['number_appointment_source']
                    ];
                }


            }
            ///Biểu đồ tròn theo giới tính khách hàng
            $gender_customer = $this->customer_appointment->reportGenderBranch($year, $branch_id);
            $all_gender = [];
            $data_gender = [];
            foreach ($gender_customer as $item) {
                $all_gender[] = $item['number'];

            }
            foreach ($gender_customer as $item) {
                $data_gender[] = [
                    'gender' => $item['gender'],
                    'number' => $item['number']
                ];
            }
            ///Biểu đồ tròn theo nguồn khách hàng
            $customer_source = $this->customer_appointment->reportCustomerSourceBranch($year, $branch_id);
            $all_cus_source = [];
            $data_cus_source = [];
            foreach ($customer_source as $item) {
                $all_cus_source[] = $item['number'];

            }
            foreach ($customer_source as $item) {
                $data_cus_source[] = [
                    'name' => $item['customer_source_name'],
                    'number' => $item['number']
                ];
            }

            return response()->json([
                'time_null' => 1,
                'data_column' => $data_column,
                'month' => $month,
                'data_appointment_source' => $data_appointment_source,
                'data_gender' => $data_gender,
                'data_cus_source' => $data_cus_source
            ]);
        }
    }

    public function filterTimeAction(Request $request)
    {
        $time = $request->time;
        $branch_id = $request->branch_id;
        if ($branch_id != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $num_date = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            $arr_date = [];
            $data_column = [];
            for ($i = 0; $i < $num_date; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arr_date[] = $tomorrow;
            }
            foreach ($arr_date as $item) {
                $format_date = Carbon::createFromFormat('d/m/Y', $item)->format('Y-m-d');

                $list_new = $this->customer_appointment->reportDateBranch($format_date, 'new', $branch_id);
                $list_confirm = $this->customer_appointment->reportDateBranch($format_date, 'confirm', $branch_id);
                $list_cancel = $this->customer_appointment->reportDateBranch($format_date, 'cancel', $branch_id);
                $list_finish = $this->customer_appointment->reportDateBranch($format_date, 'finish', $branch_id);
                $list_wait = $this->customer_appointment->reportDateBranch($format_date, 'wait', $branch_id);
                foreach ($list_new as $value) {
                    foreach ($list_confirm as $k1 => $v1) {
                        foreach ($list_cancel as $k2 => $v2) {
                            foreach ($list_finish as $k3 => $v3) {
                                foreach ($list_wait as $k4 => $v4) {
                                    $data_column[] = [
                                        'date' => $item,
                                        'number_new' => $value['number'],
                                        'number_confirm' => $v1['number'],
                                        'number_cancel' => $v2['number'],
                                        'number_finish' => $v3['number'],
                                        'number_wait' => $v4['number']
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            ///Biểu đồ tròn theo nguồn lịch hẹn
            $appointment_source = $this->customer_appointment->reportTimeAppointmentSource($time, $branch_id);
            $all_appointment_source = [];
            $data_appointment_source = [];
            foreach ($appointment_source as $item) {
                $all_appointment_source[] = $item['number_appointment_source'];

            }
            foreach ($appointment_source as $item) {
                if ($item['appointment_source_name'] != null) {
                    $data_appointment_source[] = [
                        'name' => $item['appointment_source_name'],
                        'number' => $item['number_appointment_source']
                    ];
                }


            }
            ///Biểu đồ tròn theo giới tính khách hàng
            $gender_customer = $this->customer_appointment->reportTimeGenderBranch($time, $branch_id);
            $all_gender = [];
            $data_gender = [];
            foreach ($gender_customer as $item) {
                $all_gender[] = $item['number'];

            }
            foreach ($gender_customer as $item) {
                $data_gender[] = [
                    'gender' => $item['gender'],
                    'number' => $item['number']
                ];
            }
            ///Biểu đồ tròn theo nguồn khách hàng
            $customer_source = $this->customer_appointment->reportTimeCustomerSourceBranch($time, $branch_id);
            $all_cus_source = [];
            $data_cus_source = [];
            foreach ($customer_source as $item) {
                $all_cus_source[] = $item['number'];

            }
            foreach ($customer_source as $item) {
                $data_cus_source[] = [
                    'name' => $item['customer_source_name'],
                    'number' => $item['number']
                ];
            }

            //Biểu đồ miền.
            $dataValue = [];
            $dataValue[] = ['day', __('TỔNG LỊCH HẸN'), __('LỊCH HẸN MỚI'), __('ĐÃ XÁC NHẬN'), __('CHỜ PHỤC VỤ'), __('HỦY'), __('HOÀN THÀNH')];

            foreach ($data_column as $item) {
                $dataValue[] = [
                    substr($item['date'], 0, -5),
                    $item['number_new']
                    + $item['number_confirm']
                    + $item['number_cancel']
                    + $item['number_finish']
                    + $item['number_wait'],
                    $item['number_new'],
                    $item['number_confirm'],
                    $item['number_wait'],
                    $item['number_cancel'],
                    $item['number_finish'],
                ];
            }

            //Biểu đồ nguồn lịch hẹn
            $dataCustomeAppointmentSource = [];
            $dataCustomeAppointmentSource[] = ['Task', 'Hours per Day'];

            foreach ($data_appointment_source as $item) {
                $dataCustomeAppointmentSource[] = [$item['name'], $item['number']];
            }

            //Biểu đồ giới tính
            $dataCustomeGender = [];
            $dataCustomeGender[] = ['Task', 'Hours per Day'];
            foreach ($data_gender as $item) {
                if ($item['gender'] == 'male') {
                    $dataCustomeGender[] = [__('Nam'), $item['number']];
                } elseif ($item['gender'] == 'female') {
                    $dataCustomeGender[] = [__('Nữ'), $item['number']];
                } else {
                    $dataCustomeGender[] = [__('Khác'), $item['number']];
                }
            }
            //Biểu đồ nguồn khách hàng
            $dataCustomeSource = [];
            $dataCustomeSource[] = ['Task', 'Hours per Day'];
            foreach ($data_cus_source as $item) {
                $dataCustomeSource[] = [$item['name'], $item['number']];
            }
            return response()->json([
                'branch_id_null' => 0,
                'date' => $arr_date,
                'dataCustomeAppointmentSource'=>$dataCustomeAppointmentSource,
                'dataCustomeGender'=>$dataCustomeGender,
                'dataCustomeSource'=>$dataCustomeSource,
                'dataValue'=>$dataValue
            ]);
        } else {
            $branch = $this->branch->getBranch();
            $data_column = [];
            foreach ($branch as $key => $item) {
                $list_new = $this->customer_appointment->reportTimeAllBranch($time, 'new', $key);
                $list_confirm = $this->customer_appointment->reportTimeAllBranch($time, 'confirm', $key);
                $list_cancel = $this->customer_appointment->reportTimeAllBranch($time, 'cancel', $key);
                $list_finish = $this->customer_appointment->reportTimeAllBranch($time, 'finish', $key);
                $list_wait = $this->customer_appointment->reportTimeAllBranch($time, 'wait', $key);
                foreach ($list_new as $value) {
                    foreach ($list_confirm as $k1 => $v1) {
                        foreach ($list_cancel as $k2 => $v2) {
                            foreach ($list_finish as $k3 => $v3) {
                                foreach ($list_wait as $k4 => $v4) {
                                    $data_column[] = [
                                        'month' => $item,
                                        'number_new' => $value['number'],
                                        'number_confirm' => $v1['number'],
                                        'number_cancel' => $v2['number'],
                                        'number_finish' => $v3['number'],
                                        'number_wait' => $v4['number']
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            ///Biểu đồ tròn theo nguồn lịch hẹn
            $appointment_source = $this->customer_appointment->reportTimeAppointmentSource($time, $branch_id);
            $all_appointment_source = [];
            $data_appointment_source = [];
            foreach ($appointment_source as $item) {
                $all_appointment_source[] = $item['number_appointment_source'];

            }
            foreach ($appointment_source as $item) {
                if ($item['appointment_source_name'] != null) {
                    $data_appointment_source[] = [
                        'name' => $item['appointment_source_name'],
                        'number' => $item['number_appointment_source']
                    ];
                }
            }
            ///Biểu đồ tròn theo giới tính khách hàng
            $gender_customer = $this->customer_appointment->reportTimeGenderBranch2($time, $branch_id);
            $all_gender = [];
            $data_gender = [];
            foreach ($gender_customer as $item) {
                $all_gender[] = $item['number'];

            }
            foreach ($gender_customer as $item) {
                $data_gender[] = [
                    'gender' => $item['gender'],
                    'number' => $item['number']
                ];
            }
            ///Biểu đồ tròn theo nguồn khách hàng
            $customer_source = $this->customer_appointment->reportTimeCustomerSourceBranch($time, $branch_id);
            $all_cus_source = [];
            $data_cus_source = [];
            foreach ($customer_source as $item) {
                $all_cus_source[] = $item['number'];

            }
            foreach ($customer_source as $item) {
                $data_cus_source[] = [
                    'name' => $item['customer_source_name'],
                    'number' => $item['number']
                ];
            }

            $branch = $this->branch->getBranch();

            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $num_date = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            $arr_date = [];
            $data_column = [];
            for ($i = 0; $i < $num_date; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arr_date[] = $tomorrow;
            }
            //Biểu đồ miền.
            $dataValue = [];
            $dataValue[] = ['day', __('TỔNG LỊCH HẸN'), __('LỊCH HẸN MỚI'), __('ĐÃ XÁC NHẬN'), __('CHỜ PHỤC VỤ'), __('HỦY'), __('HOÀN THÀNH')];

            foreach ($arr_date as $item) {
                $format_date = Carbon::createFromFormat('d/m/Y', $item)->format('Y-m-d');
                $list_new = $this->customer_appointment->reportDateBranch($format_date, 'new', $branch_id);
                $list_confirm = $this->customer_appointment->reportDateBranch($format_date, 'confirm', $branch_id);
                $list_cancel = $this->customer_appointment->reportDateBranch($format_date, 'cancel', $branch_id);
                $list_finish = $this->customer_appointment->reportDateBranch($format_date, 'finish', $branch_id);
                $list_wait = $this->customer_appointment->reportDateBranch($format_date, 'wait', $branch_id);

                $dataValue[] = [
                    substr($item, 0, -5),
                    $list_new[0]['number']
                    + $list_confirm[0]['number']
                    + $list_wait[0]['number']
                    + $list_cancel[0]['number']
                    + $list_finish[0]['number'],
                    $list_new[0]['number'],
                    $list_confirm[0]['number'],
                    $list_wait[0]['number'],
                    $list_cancel[0]['number'],
                    $list_finish[0]['number'],
                ];
            }

            //Biểu đồ nguồn lịch hẹn
            $dataCustomeAppointmentSource = [];
            $dataCustomeAppointmentSource[] = ['Task', 'Hours per Day'];

            foreach ($data_appointment_source as $item) {
                $dataCustomeAppointmentSource[] = [$item['name'], $item['number']];
            }

            //Biểu đồ giới tính
            $dataCustomeGender = [];
            $dataCustomeGender[] = ['Task', 'Hours per Day'];
            foreach ($data_gender as $item) {
                if ($item['gender'] == 'male') {
                    $dataCustomeGender[] = [__('Nam'), $item['number']];
                } elseif ($item['gender'] == 'female') {
                    $dataCustomeGender[] = [__('Nữ'), $item['number']];
                } else {
                    $dataCustomeGender[] = [__('Khác'), $item['number']];
                }
            }

            //Biểu đồ nguồn khách hàng
            $dataCustomeSource = [];
            $dataCustomeSource[] = ['Task', 'Hours per Day'];
            foreach ($data_cus_source as $item) {
                $dataCustomeSource[] = [$item['name'], $item['number']];
            }

            return response()->json([
                'branch_id_null' => 1,
                'data_column' => $data_column,
                'branch' => $branch,
                'dataCustomeAppointmentSource'=>$dataCustomeAppointmentSource,
                'dataCustomeGender'=>$dataCustomeGender,
                'dataCustomeSource'=>$dataCustomeSource,
                'dataValue'=>$dataValue
            ]);
        }
    }
}