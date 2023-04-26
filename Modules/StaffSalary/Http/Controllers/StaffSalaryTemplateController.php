<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 09/04/2022
 * Time: 10:46
 */

namespace Modules\StaffSalary\Http\Controllers;

use Modules\StaffSalary\Repositories\SalaryAllowance\SalaryAllowanceRepoInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Modules\StaffSalary\Repositories\SalaryBonusMinus\SalaryBonusMinusRepoInterface;
use Modules\StaffSalary\Repositories\Template\TemplateRepoInterface;

class StaffSalaryTemplateController extends Controller
{
    protected $salaryAllowance;
    protected $salaryBonusMinus;
    protected $template;
    protected $repoStaffTemplate;

    public function __construct(
        SalaryAllowanceRepoInterface $salaryAllowance,
        SalaryBonusMinusRepoInterface $salaryBonusMinus,
        TemplateRepoInterface $template,
        TemplateRepoInterface $repoStaffTemplate
    )
    {
       $this->salaryAllowance = $salaryAllowance;
       $this->salaryBonusMinus = $salaryBonusMinus;
       $this->template = $template;
        $this->repoStaffTemplate = $repoStaffTemplate;
    }
    
    public function index(Request $request)
    {
       
        return view('staff-salary::staff-salary.index', [

        ]);
    }

    /**
     * Show modal add template salary
     *
     * @return mixed
     */
    public function getRowSalaryAction()
    {
        $html = \View::make('staff-salary::staff-salary-template.salary-row')->render();
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Show modal add template salary
     *
     * @return mixed
     */
    public function getRowSalaryAllowanceAction(Request $request)
    {
        if ($request->ajax()) {
            $salary_allowance_num = $request->salary_allowance_num;
            $salary_allowance = $request->salary_allowance;
            $salary_allowance_text = $request->salary_allowance_text;
            $unitText = $request->unitText;
            $html = \View::make('staff-salary::staff-salary-template.salary-allowances',[
                'salary_allowance_num' => $salary_allowance_num,
                'salary_allowance' => $salary_allowance,
                'salary_allowance_text' => $salary_allowance_text,
                'unitText' => $unitText
            ])->render();
            return response()->json([
                'html' => $html

            ]);
        }

    }

    /**
     * Show modal add template salary
     *
     * @return mixed
     */
    public function getRowSalaryBonusMinusAction(Request $request)
    {
        if ($request->ajax()) {
            $salary_bonus_minus_num = $request->salary_bonus_minus_num;
            $salary_bonus_minus = $request->salary_bonus_minus;
            $salary_bonus_minus_text = $request->salary_bonus_minus_text;
            $salaryBonusMinus = $this->salaryBonusMinus->getDetail($salary_bonus_minus);
            $html = \View::make('staff-salary::staff-salary-template.salary-bonus-minus',[
                'salary_bonus_minus_num' => $salary_bonus_minus_num,
                'salary_bonus_minus' => $salary_bonus_minus,
                'salary_bonus_minus_text' => $salary_bonus_minus_text,
                'salaryBonusMinus' => $salaryBonusMinus
            ])->render();
            return response()->json([
                'html' => $html
            ]);
        }

    }

    /**
     * Show modal add template salary
     *
     * @return mixed
     */
    public function changeStaffSalaryTypeAction(Request $request)
    {
        $html = "";
        if ($request->ajax()) {
            $branch_id = $request->branch_id;
            if($request->salary_type == "shift"){
                $html = \View::make('staff-salary::staff-salary-template.salary-shift',[
                    'branch_id' => $branch_id
                ])->render();
            }else if($request->salary_type == "monthly"){
                $html = \View::make('staff-salary::staff-salary-template.salary-month',[
                    'branch_id' => $branch_id
                ])->render();
            }else {
                $html = \View::make('staff-salary::staff-salary-template.salary-hour',[
                    'branch_id' => $branch_id
                ])->render();
            }
        }
       
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Change Template action
     *
     * @return mixed
     */
    public function changeStaffSalaryTemplateAction(Request $request)
    {
        $data = $this->template->getDataEdit($request->staff_salary_template_id);
        $data['branch_id'] = $request->branch_id;
        $data['staff_salary_template_id'] = $request->staff_salary_template_id;
        $optionStaffSalaryTemplate = $this->repoStaffTemplate->getOption();
        $data['optionStaffSalaryTemplate'] = $optionStaffSalaryTemplate;

        //return view('staff-salary::template.staff-edit',$data)->render();
        //$data['staffSalaryConfig'] = $this->staffSalaryConfig->getDetailByStaff($request->staff_id);

        //$getBranch = $this->branch->getBranchOption();
        //$roleGroup = $this->roleGroup->getOptionActive();
        //$mapGroupStaff = $this->mapRoleGroupStaff->getRoleGroupByStaffId($id);
        //$staffSalaryType = $this->staffSalary->getListStaffSalaryType();
        //$staffSalaryPayPeriod = $this->staffSalaryPayPeriod->getList();
        //$arraySalaryBonusMinus = $this->staffSalary->getDetailSalaryBonusMinusByStaff($id);
        //$arraySalaryAllowance = $this->staffSalary->getDetailSalaryAllowanceByStaff($id);
        //$staffSalaryOvertime = $this->staffSalary->getDetailSalaryOvertimeByStaff($id);
        $optionStaffSalaryTemplate = $this->repoStaffTemplate->getOption();

        //$staffSalaryAttribute = $this->staffSalaryAttribute->getDetailByStaff($id);
        $arrayStaffSalaryAttribute = [];
//        foreach ($staffSalaryAttribute as $key => $itemStaffSalary) {
//            $arrayStaffSalaryAttribute += [
//                $itemStaffSalary['staff_salary_attribute_code'] => [
//                    'staff_salary_attribute_value' => $itemStaffSalary['staff_salary_attribute_value'],
//                    'staff_salary_attribute_type' => $itemStaffSalary['staff_salary_attribute_type'],
//                ],
//            ];
//        }
        //var_dump($arrayStaffSalaryAttribute);die;
//        $arrayMapRoleGroupStaff = [];
//        if (count($mapGroupStaff) > 0) {
//            foreach ($mapGroupStaff as $values) {
//                $arrayMapRoleGroupStaff[] = $values['role_group_id'];
//            }
//        }

        //dd( ( $data['item']->toArray() ) );
        //dd( ( $data['templateAllowance']->toArray() ) );
        $data['staff_salary_template_id'] = $data['item']['staff_salary_template_id'];
        $data['staff_salary_type_code'] = $data['item']['staff_salary_type_code'];
        $data['payment_type'] = $data['item']['payment_type'];
        $data['staff_salary_unit_code'] = $data['item']['staff_salary_unit_code'];
        $data['staff_salary_pay_period_code'] = $data['item']['staff_salary_pay_period_code'];
        $data['staff_salary_config_id'] = $request->staff_salary_config_id;
        $data['staffSalaryType'] = $data['optionType'];
        $data['staffSalaryPayPeriod'] = $data['optionPayPeriod'];

        //$staffSalaryConfig['staff_salary_type_code'];

        $data['item2'] = $data['item'];

        $data['arrayStaffSalaryAttribute']['salary_weekday']['staff_salary_attribute_value'] = $data['item2']['salary_default'];
        $data['arrayStaffSalaryAttribute']['salary_sarturday']['staff_salary_attribute_value'] = $data['item2']['salary_saturday_default'];
        $data['arrayStaffSalaryAttribute']['salary_sarturday']['staff_salary_attribute_type'] = $data['item2']['salary_saturday_default_type'];
        $data['arrayStaffSalaryAttribute']['salary_sunday']['staff_salary_attribute_value'] = $data['item2']['salary_sunday_default'];
        $data['arrayStaffSalaryAttribute']['salary_sunday']['staff_salary_attribute_type'] = $data['item2']['salary_sunday_default_type'];
        $data['arrayStaffSalaryAttribute']['salary_holiday']['staff_salary_attribute_value'] = $data['item2']['salary_holiday_default'];
        $data['arrayStaffSalaryAttribute']['salary_holiday']['staff_salary_attribute_type'] = $data['item2']['salary_holiday_default_type'];
        $data['arrayStaffSalaryAttribute']['salary_monthly']['staff_salary_attribute_value'] = $data['item2']['salary_default'];

        $data['staff_salary_template_id'] = $request->staff_salary_template_id;
        return response()->json([
            'html' => view('staff-salary::template.staff-edit',$data)->render()
        ]);

    }

    /**
     * Show modal add template salary
     *
     * @return mixed
     */
    public function showModalAddSalaryTemplateAction()
    {
        $html = \View::make('staff-salary::staff-salary-template.add')->render();
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Show modal add template salary
     *
     * @return mixed
     */
    public function showModalAddSalaryCommissionAction()
    {
        $html = \View::make('staff-salary::staff-salary-template.commission-add')->render();
        return response()->json([
            'html' => $html
        ]);
    }

     /**
     * Show modal add template salary
     *
     * @return mixed
     */
    public function showModalAddSalaryBonusMinusAction()
    {
        $salaryBonusMinus = $this->salaryBonusMinus->getList();
        $html = \View::make('staff-salary::staff-salary-template.bonus-minus-add',[
            'salaryBonusMinus' => $salaryBonusMinus
        ])->render();
        return response()->json([
            'html' => $html
        ]);
    }

    public function showModalAddSalaryAllowancesAction(Request $request){

        $salaryAllowance =  $this->salaryAllowance->getList();
        $html = \View::make('staff-salary::staff-salary-template.allowances-add',[
            'salaryAllowance' => $salaryAllowance,
            'unit' => $request->unit
        ])->render();
        return response()->json([
            'html' => $html
        ]);
    }
}