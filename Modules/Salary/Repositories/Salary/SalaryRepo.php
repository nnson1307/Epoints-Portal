<?php

namespace Modules\Salary\Repositories\Salary;

use Illuminate\Support\Facades\Auth;
use Modules\Salary\Models\ContractReceiptTable;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Modules\Salary\Models\SalaryCommissionConfigCacheTable;
use Modules\Salary\Models\SalaryCommissionConfigTable;
use Modules\Salary\Models\SalaryStaffDetailTable;
use Modules\Salary\Models\SalaryStaffTable;
use Modules\Salary\Models\SalaryTable;
use Modules\Salary\Models\StaffTable;
use App\Exports\ExportFile;
use App\Exports\ExportSalaryFile;
use App\Exports\ExportSalaryStaff;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Salary\Models\DepartmentTable;
use Carbon\Carbon;

/**
 * Class SalaryRepo
 * @package Modules\Salary\Repositories\Salary
 * @author VuND
 * @since 02/12/2021
 */
class SalaryRepo implements SalaryInterface

{
    protected $department_table;
    protected $salary_commission_config;
    protected $mSalary;


    public function __construct(
        DepartmentTable $department_table,
        SalaryCommissionConfigTable $salary_commission_config,
        SalaryTable $mSalary
    )
    {
        $this->department_table = $department_table;
        $this->salary_commission_config = $salary_commission_config;
        $this->mSalary = $mSalary;
    }
    /**
     * Job create salary
     * @param $id
     */
    public function createSalary($salaryId){

        $mSalary = app()->get(SalaryTable::class);
        $arrSalary = $mSalary->getDetail($salaryId);
        if(!$arrSalary) return;

        // TODO : save cache config
        $mCommission = app()->get(SalaryCommissionConfigTable::class);
        $mCommissionCache = app()->get(SalaryCommissionConfigCacheTable::class);

        $arrCommission = $mCommission->getAllForInsert($salaryId);
//         dd($arrCommission);
//        foreach($arrCommission as $key => $value){
//            unset($arrCommission[$key]['salary_commission_config_id']);
//        }
        $mCommissionCache->insert($arrCommission);

        // TODO : insert salary staff
        $mStaff = app()->get(StaffTable::class);
        $arrStaff = $mStaff->getAllForSalary();

        $arrSalaryStaff = [];

        $mSalaryStaff = app()->get(SalaryStaffTable::class);

        foreach ($arrStaff as $staff){
            $arrSalaryStaff[$staff['staff_id']] = [
                'salary_id' => $salaryId,
                'staff_id' => $staff['staff_id'],
                'staff_code' => $staff['staff_code'],
                'staff_name' => $staff['full_name'],
                'department_id' => $staff['department_id'],
                'department_name' => $staff['department_name'],
                'total_allowance' => $staff['subsidize'],
                'salary' => $staff['salary'],
                'total' => $staff['salary'] + $staff['subsidize'],
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ];

            if(count($arrSalaryStaff) > 300){
                $mSalaryStaff->insert($arrSalaryStaff);
                $arrSalaryStaff = [];
            }
        }

        if($arrSalaryStaff){
            $mSalaryStaff->insert($arrSalaryStaff);
        }

        // TODO : get contract by staff

        $mContractReceipt = app()->get(ContractReceiptTable::class);

        $arrContractReceipt = $mContractReceipt->getReceiptByStaffGroup($arrSalary['date_start'], $arrSalary['date_end']);

        if($arrContractReceipt){
            $this->createSalaryStaffDetail($salaryId, $arrContractReceipt, 'sale');
        }

        $arrContractReceiptTicket = $mContractReceipt->getReceiptByStaffTicketGroup($arrSalary['date_start'], $arrSalary['date_end']);

        if($arrContractReceiptTicket){
            $this->createSalaryStaffDetail($salaryId, $arrContractReceiptTicket, 'ticket');
        }

    }

    protected function createSalaryStaffDetail($salaryId, $arrData, $workType){

        $mSalaryStaff = app()->get(SalaryStaffTable::class);

        $mCommission = app()->get(SalaryCommissionConfigTable::class);

        $mSalaryStaffDetail = app()->get(SalaryStaffDetailTable::class);

        foreach ($arrData as $staffId => $itemContractReceipt){

            $arrSalaryDetail = $mSalaryStaff->getDetailByStaffId($salaryId, $staffId);

            if(!$arrSalaryDetail) continue;

            $arrCommissionDep = $mCommission->getByDepartment($arrSalaryDetail['department_id']);

            if(!$arrCommissionDep) continue;

            $totalRevenue = $totalKpi = $totalCommissionOk = 0;

            $arrStaffSalaryDetailReceipt = [];
            $totalCommission = $arrSalaryDetail['total_commission'];

            foreach ($itemContractReceipt as $itemReceipt){

                if($workType == 'ticket' && !$itemReceipt['is_applied_kpi']){
                    continue;
                }

                if ($workType == 'ticket'){
                    $commissionName = 'installation_commission';
                } else {
                    $commissionName = $itemReceipt['partner_object_form'].'_'.$itemReceipt['contract_form'];
                }



                $percent = 0;
                $commission = 0;
                if(isset($arrCommissionDep[$commissionName])){
                    $percent = $arrCommissionDep[$commissionName];
                    $commission = ($itemReceipt['total_amount_receipt'] * $percent / 100);
                }

                $totalKpi = $totalKpi + $commission;

                $totalRevenue = $totalRevenue + $itemReceipt['total_amount_receipt'];

                $arrStaffSalaryDetailReceipt[] = [
                    'salary_staff_id' => $arrSalaryDetail['salary_staff_id'],
                    'staff_id' => $staffId,
                    'role' => $workType,
                    'contract_id' => $itemReceipt['contract_id'],
                    'ticket_code' => $itemReceipt['ticket_code'],
                    'value' => $itemReceipt['total_amount_receipt'],
                    'percent' => $percent,
                    'commission' => $commission,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ];


            }

            $mSalaryStaffDetail->insert($arrStaffSalaryDetailReceipt);

            $kpi = $arrCommissionDep['kpi_'.Auth::user()->staff_type];

//            if($totalRevenue >= $kpi){
                $totalCommissionOk = $totalKpi;
//            }

            $totalCommission = $totalCommission + $totalCommissionOk;
            $arrUpdateSalaryStaff = [
                'total_revenue' => $totalRevenue,
                'revenue_kpi' => $totalRevenue * 1.1,
                'total_commission' => $totalCommission,
//                'total' => $arrSalaryDetail['total'] + $totalCommission
                'total' => $arrSalaryDetail['total'] + $totalCommissionOk
            ];
            $mSalaryStaff->updateItem($arrSalaryDetail['salary_staff_id'], $arrUpdateSalaryStaff);
        }
    }

    public function list(array $filters = [])
    {
        $filters = array_filter($filters, function($value) { return !is_null($value) && $value !== ''; });
        return [
            'list' => $this->mSalary->getDataList($filters),
            'department_list' => $this->department_table->getOption(),
            'params' => $filters,
            'page' => isset($filters['page'])?$filters['page']:1
        ];
    }

    public function tableSalaryDetail($id,array $filters = [])
    {
        $filters = array_filter($filters, function($value) { return !is_null($value) && $value !== ''; });
        if (request()->session()->has('export-salary')) {
            request()->session()->forget('export-salary');
        }
        $filters['salary_id'] = $id;

        request()->session()->put('export-salary', $filters);
        $mStaff = app()->get(StaffTable::class);
        $item = $this->mSalary->getItem($id);
        return [
            'list' => $this->mSalary->salaryStaffList($id,$filters),
            'item' => $item,
            'department_list' => $this->department_table->getOption(),
            'params' => $filters,
            'arrStaff' => $mStaff->getName(),
            'page' => isset($filters['page'])?$filters['page']:1
        ];
    }

    public function addAction($params)
    {
        $arr_filter = explode(" - ", $params["time"]);
        $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
        $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");

        $season_month = Carbon::createFromFormat("m/Y", $params["salary_period"])->format("m");
        $season_year = Carbon::createFromFormat("m/Y", $params["salary_period"])->format("Y");
        $data = [
            'name' =>  $params['name'],
            'season_month' =>  $season_month,
            'season_year' =>  $season_year,
            'date_start' =>  $startTime,
            'date_end' =>  $endTime,
            'is_active' =>  0,
            'created_by' => \Auth::id(),
            'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
        ];
        $id = $this->mSalary->add($data);
        if($id){
            $this->createSalary($id);
            return [
                'error' => 0,
                'id' => $id,
                'message' => __('Thêm thành công'),
            ];
        }
    }
    /**
     * Export excel bảng lương
     * @param $data
     * @return mixed|void
     */
    public function exportExcelSalary()
    {
        $data = request()->session()->get('export-salary');
        $mSalaryStaff = app()->get(SalaryStaffTable::class);
        $dataSalary['detail'] = $this->mSalary->getItem($data['salary_id']);
        $dataSalary['list'] = $mSalaryStaff->getAll($data);
        $dataSalary['total'] = $mSalaryStaff->getAllTotal($data);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportSalaryFile(collect($dataSalary)->toArray()), 'Bảng Lương.xlsx');
    }

    /**
     * delete Salary Config
     */
    public function remove($id)
    {
        $this->salary_commission_config->remove($id);
    }

    /**
     * add Salary Config
     */
    public function add(array $data)
    {

        return $this->salary_commission_config->add($data);
    }

    /*
     * edit Salary Config
     */
    public function edit(array $data, $id)
    {
        return $this->salary_commission_config->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->salary_commission_config->getItem($id);
    }

    /**
     * Import excel
     * @param $data
     * @return mixed|void
     */
    public function importExcelSalary($data)
    {
        try {
            $mSalaryStaff = app()->get(SalaryStaffTable::class);
            $mStaff = app()->get(StaffTable::class);
            if (isset($data['file']) && isset($data['salary_id'])) {
                $salaryId = $data['salary_id'];
                $file = $data['file'];
                $typeFileExcel = $file->getClientOriginalExtension();

                if ($typeFileExcel == "xlsx") {
                    $reader = ReaderFactory::create(Type::XLSX);
                    $reader->open($file);

                    $arrError = [];
                    $numberSuccess = 0;
                    $numberError = 0;

                    // sẽ trả về các object gồm các sheet
                    foreach ($reader->getSheetIterator() as $sheet) {
                        // đọc từng dòng
                        foreach ($sheet->getRowIterator() as $key => $row) {
                            $tmp = [];

                            if($key >= 3 && $row[0] != 'TỔNG CỘNG' && $row[1] != ''){
                                $tmp = [
                                    'staff_code' => isset($row[1]) ? $row[1] : '',
                                    'staff_name' => isset($row[2]) ? $row[2] : '',
                                    'department_name' => isset($row[3]) ? $row[3] : '',
                                    'salary' => isset($row[5]) ? str_replace(',','',$row[5]) : '',
                                    'total_revenue' => isset($row[6]) ? str_replace(',','',$row[6]) : '',
                                    'total_commission' => isset($row[7]) ? str_replace(',','',$row[7]) : '',
                                    'total_kpi' => isset($row[8]) ? str_replace(',','',$row[8]) : '',
                                    'total_allowance' => isset($row[9]) ? str_replace(',','',$row[9]) : '',
                                    'plus' => isset($row[10]) ? str_replace(',','',$row[10]) : '',
                                    'minus' => isset($row[11]) ? str_replace(',','',$row[11]) : '',
                                    'total' => isset($row[12]) ? str_replace(',','',$row[12]) : '',
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id(),
                                ];

                                $totalMoney = (int)$tmp['salary'] + (int)$tmp['total_kpi'] + (int)$tmp['total_allowance'] + (int)$tmp['plus'] - (int)$tmp['minus'];
                                $tmp['total'] = $totalMoney;
                                $checkCode = $mSalaryStaff->checkCode(['salary_id' => $salaryId,'staff_code' => $tmp['staff_code']]);
                                if($checkCode != null){
                                    unset($tmp['staff_code']);
                                    unset($tmp['staff_name']);
                                    unset($tmp['department_name']);
                                    unset($tmp['total_revenue']);
                                    unset($tmp['total_commission']);
//                                    unset($tmp['total']);
//                                    $tmp['total'] = $tmp['total'] + (int)$checkCode['total_revenue'] + (int)$checkCode['total_commission'];
                                    $tmp['total'] = $tmp['total'] + (int)$checkCode['total_commission'];
                                    $mSalaryStaff->editSalaryStaff($tmp,$checkCode['salary_staff_id']);
                                    $numberSuccess++;
                                } else {
                                    $checkCodeStaff = $mStaff->getInfoStaffByCode($tmp['staff_code']);
                                    if($checkCodeStaff == null){
                                        $numberError++;
                                        $tmp['error_message'] = 'Nhân viên không tồn tại';
                                        $arrError[] = $tmp;
                                    } else {
                                        $numberSuccess++;
                                        $tmp['total'] = $tmp['total'] + (int)$tmp['total_commission'];
                                        $tmp['salary_id'] = $salaryId;
                                        $tmp['staff_id'] = $checkCodeStaff['staff_id'];
                                        $tmp['staff_name'] = $checkCodeStaff['full_name'];
                                        $tmp['department_id'] = $checkCodeStaff['department_id'];
                                        $tmp['department_name'] = $checkCodeStaff['department_name'];
                                        $tmp['created_at'] = Carbon::now();
                                        $tmp['created_by'] = Auth::id();
                                        $mSalaryStaff->addSalaryStaff($tmp);
                                    }
                                }
                            }
                        }
                    }

                    $reader->close();

                    return response()->json([
                        'success' => 1,
                        'message' => __('Số dòng thành công') . ':' . $numberSuccess . '<br/>' . __('Số dòng thất bại') . ':' . $numberError,
                        'number_error' => $numberError,
                        'data_error' => $arrError
                    ]);
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => __('File không đúng định dạng')
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => __('Import thông tin khách hàng thất bại'),
                '_message' => $e->getMessage() . ' ' . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * Khoá lương
     * @param $data
     * @return mixed|void
     */
    public function lockSalary($data)
    {
        try {

            $mSalary = app()->get(SalaryTable::class);
            $data_edit = [
                'updated_by' => \Auth::id(),
                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
                'is_active' => 1
            ];
            $mSalary->edit($data_edit,$data['salary_id']);

            return [
                'error' => false,
                'message' => 'Khoá bảng lương thành công'
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => 'Khoá bảng lương thất bại',
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * hiển thị popup cập nhật lương
     * @param $data
     * @return mixed|void
     */
    public function showModalEditSalary($data)
    {
        try {
            $mSalary = app()->get(SalaryTable::class);

            $detail = $mSalary->getDetail($data['salary_id']);

            $view = view('Salary::salary.modal.modal-edit-salary',['detail' => $detail])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e){
            return [
                'error' => true,
                'message' => 'Hiển thị popup thất bại'
            ];
        }
    }

    /**
     * cập nhật tên bảng lương
     * @param $data
     * @return mixed|void
     */
    public function editSalary($data)
    {
        try {

            $mSalary = app()->get(SalaryTable::class);
            $data_edit = [
                'updated_by' => \Auth::id(),
                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
                'name' => $data['name']
            ];
            $mSalary->edit($data_edit,$data['salary_id']);

            return[
                'error' => false,
                'message' => 'Chỉnh sửa bảng lương thành công'
            ];
        }catch (\Exception $e){
            return[
                'error' => true,
                'message' => 'Chỉnh sửa bảng lương thất bại'
            ];
        }
    }

    /**
     * lấy thông tin chi tiết lương nhân viên
     * @param $data
     * @return mixed|void
     */
    public function getDetailSalaryStaff($id)
    {
        $mSalary = app()->get(SalaryTable::class);
        $mSalaryStaff = app()->get(SalaryStaffTable::class);

        $data['detail'] = $mSalaryStaff->getDetail($id);
        if($data['detail'] == null){
            return [
                'error' => true,
            ];
        }
        $data['info'] = $mSalary->getDetail($data['detail']['salary_id']);
        return [
            'detail' => $data['detail'],
            'info' => $data['info'],
        ];
    }

    /**
     * Lưu bảng lương
     * @param $data
     * @return mixed|void
     */
    public function editSalarySave($data)
    {
        try {

            $mSalaryStaff = app()->get(SalaryStaffTable::class);

            $dataValue['salary'] = isset($data['salary']) ? str_replace(',', '', $data["salary"]) : 0;
            $dataValue['total_commission'] = isset($data['total_commission']) ? str_replace(',', '', $data["total_commission"]) : 0;
            $dataValue['plus'] = isset($data['plus']) ? str_replace(',', '', $data["plus"]) : 0;
            $dataValue['minus'] = isset($data['minus']) ? str_replace(',', '', $data["minus"]) : 0;
            $dataValue['total_kpi'] = isset($data['total_kpi']) ? str_replace(',', '', $data["total_kpi"]) : 0;
            $dataValue['total_allowance'] = isset($data['total_allowance']) ? str_replace(',', '', $data["total_allowance"]) : 0;
            $dataValue['note'] = isset($data['note']) ? $data['note'] : '';
            $dataValue['total'] = $dataValue['salary'] + $dataValue['total_commission'] + $dataValue['plus'] + $dataValue['total_kpi'] + $dataValue['total_allowance'] - $dataValue['minus'];
            $dataValue['updated_at'] = Carbon::now();
            $dataValue['updated_by'] = Auth::id();

            $mSalaryStaff->editSalaryStaff($dataValue,$data['salary_staff_id']);

            $salary_id = $mSalaryStaff->getDetail($data['salary_staff_id'])->salary_id;
            $mSalary = app()->get(SalaryTable::class);
            $data_edit = [
                'updated_by' => \Auth::id(),
                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
            ];
            $mSalary->edit($data_edit,$salary_id);

            return [
                'error' => false,
                'message' => 'Lưu bảng lương thành công'
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => 'Lưu bảng lương thất bại'
            ];
        }
    }

    /**
     * hiển thị table hoa hồng
     * @param $data
     * @return mixed|void
     */
    public function showTableCommission($data)
    {
        // try {
            $mSalaryStaffDetail = app()->get(SalaryStaffDetailTable::class);
            $data['check_staff_commission'] = 1;
            $list = $mSalaryStaffDetail->getListDetail($data);
            $check = $mSalaryStaffDetail->checkDepartment($data['salary_staff_id']);
            $totalValue = $mSalaryStaffDetail->getTotalValue($data);

            $view = view('Salary::salary.salary_staff.table-commission',[
                'list' => $list,
                'totalValue' => $totalValue,
                'check' => $check,
                ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        // }catch (\Exception $e){
        //     return [
        //         'error' => true,
        //         'message' => 'Hiển thị danh sách hoa hồng thất bại'
        //     ];
        // }
    }

    /**
     * Export excel bảng lương
     * @param $data
     * @return mixed|void
     */
    public function exportExcelSalaryCommission($data)
    {
        $mSalaryStaffDetail = app()->get(SalaryStaffDetailTable::class);
        $dataSalary['detail'] = $this->mSalary->getItem($data['salary_id']);
        $dataSalary['list'] = $mSalaryStaffDetail->getAll($data);
        $type = "bán hàng ";
        if($data['type'] == 'kt'){
            $type = "lắp đặt ";
        }
        $dataSalary['type'] = $data['type'];
        $name = $dataSalary['detail']->season_month.'-'.$dataSalary['detail']->season_year;
        $dataSalary['table_name'] = 'Danh sách hoa hồng '.$type.$name;
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportSalaryStaff(collect($dataSalary)->toArray()), 'Danh sách hoa hồng '.$dataSalary['table_name'].'.xlsx');
    }
}