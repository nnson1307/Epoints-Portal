<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/10/2022
 * Time: 14:23
 */

namespace Modules\StaffSalary\Repositories\Template;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\StaffSalary\Models\SalaryAllowanceTable;
use Modules\StaffSalary\Models\StaffSalaryPayPeriodTable;
use Modules\StaffSalary\Models\StaffSalaryTemplateAllowanceTable;
use Modules\StaffSalary\Models\StaffSalaryTemplateTable;
use Modules\StaffSalary\Models\StaffSalaryTypeTable;
use Modules\StaffSalary\Models\StaffSalaryUnitTable;

class TemplateRepo implements TemplateRepoInterface
{
    protected $template;

    public function __construct(
        StaffSalaryTemplateTable $template
    )
    {
        $this->template = $template;
    }

    /**
     * Lấy data danh sách mẫu lương
     *
     * @param array $filter
     * @return array|mixed
     */
    public function getList($filter = [])
    {
        $list = $this->template->getList($filter);

        return [
            'list' => $list
        ];
    }


    /**
     * Lấy danh sách option mẫu lương
     *
     * @param array $filter
     * @return array|mixed
     */
    public function getOption()
    {
        return $list = $this->template->where('is_actived',1)->where('is_deleted',0)->get()->toArray();
    }


    /**
     * Lấy data view tạo
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataCreate()
    {
        $mSalaryType = app()->get(StaffSalaryTypeTable::class);
        $mSalaryPayPeriod = app()->get(StaffSalaryPayPeriodTable::class);
        $mSalaryUnit = app()->get(StaffSalaryUnitTable::class);

        //Option loại lương
        $optionType = $mSalaryType->getList();
        //Option kỳ hạn trả lương
        $optionPayPeriod = $mSalaryPayPeriod->getList();
        //Option đơn vị tiền tệ
        $optionUnit = $mSalaryUnit->getUnit();
      
        return [
            'optionType' => $optionType,
            'optionPayPeriod' => $optionPayPeriod,
            'optionUnit' => $optionUnit
        ];
    }

    /**
     * Lấy data view thêm phụ cấp
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataPopCreateAllowance()
    {
        $mSalaryAllowance = app()->get(SalaryAllowanceTable::class);

        //Lấy option phụ cấp
        $optionAllowance = $mSalaryAllowance->getList();

        return [
            'optionAllowance' => $optionAllowance
        ];
    }

    /**
     * Thêm mẫu lương
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mTemplateAllowance = app()->get(StaffSalaryTemplateAllowanceTable::class);

            //Thêm mẫu lương
            $templateId = $this->template->add([
                'staff_salary_template_name' => $input['staff_salary_template_name'],
                'staff_salary_pay_period_code' => $input['staff_salary_pay_period_code'],
                'payment_type' => $input['payment_type'],
                'staff_salary_type_code' => $input['staff_salary_type_code'],
                'staff_salary_unit_code' => $input['staff_salary_unit_code'],
                'salary_default' => $input['salary_default'],
                'salary_saturday_default' => $input['salary_saturday_default'],
                'salary_saturday_default_type' => $input['salary_saturday_default_type'],
                'salary_sunday_default' => $input['salary_sunday_default'],
                'salary_sunday_default_type' => $input['salary_sunday_default_type'],
                'salary_holiday_default' => $input['salary_holiday_default'],
                'salary_holiday_default_type' => $input['salary_holiday_default_type'],
                'is_overtime' => $input['is_overtime'],
                'salary_overtime' => $input['salary_overtime'],
                'salary_saturday_overtime' => $input['salary_saturday_overtime'],
                'salary_saturday_overtime_type' => $input['salary_saturday_overtime_type'],
                'salary_sunday_overtime' => $input['salary_sunday_overtime'],
                'salary_sunday_overtime_type' => $input['salary_sunday_overtime_type'],
                'salary_holiday_overtime' => $input['salary_holiday_overtime'],
                'salary_holiday_overtime_type' => $input['salary_holiday_overtime_type'],
                'is_allowance' => $input['is_allowance'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            //Update template code
            $templateCode = 'SLT_' . date('dmY') . sprintf("%02d", $templateId);

            $this->template->edit([
                'staff_salary_template_code' => $templateCode
            ], $templateId);

            $arrayAllowance = [];

            if (isset($input['arrayAllowance']) && count($input['arrayAllowance']) > 0) {
                foreach ($input['arrayAllowance'] as $v) {
                    $arrayAllowance [] = [
                        'staff_salary_template_id' => $templateId,
                        'salary_allowance_id' => $v['salary_allowance_id'],
                        'staff_salary_allowance_num' => $v['staff_salary_allowance_num'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Thêm phụ cấp
            $mTemplateAllowance->insert($arrayAllowance);

            DB::commit();

            return [
                'error' => false,
                'message' => __('Thêm thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Thêm thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }



    /**
     * Thêm mẫu lương
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function ajaxCreate($input)
    {
        $repoStaffTemplate = app()->get(TemplateRepoInterface::class);
       
        DB::beginTransaction();
        try {
            $mTemplateAllowance = app()->get(StaffSalaryTemplateAllowanceTable::class);

            //Thêm mẫu lương
            $templateId = $this->template->add([
                'staff_salary_template_name' => $input['staff_salary_template_name'],
                'staff_salary_pay_period_code' => $input['staff_salary_pay_period_code'],
                'payment_type' => $input['payment_type'],
                'staff_salary_type_code' => $input['staff_salary_type_code'],
                'staff_salary_unit_code' => $input['staff_salary_unit_code'],
                'salary_default' => $input['salary_default'],
                'salary_saturday_default' => $input['salary_saturday_default'],
                'salary_saturday_default_type' => $input['salary_saturday_default_type'],
                'salary_sunday_default' => $input['salary_sunday_default'],
                'salary_sunday_default_type' => $input['salary_sunday_default_type'],
                'salary_holiday_default' => $input['salary_holiday_default'],
                'salary_holiday_default_type' => $input['salary_holiday_default_type'],
                'is_overtime' => $input['is_overtime'],
                'salary_overtime' => $input['salary_overtime'],
                'salary_saturday_overtime' => $input['salary_saturday_overtime'],
                'salary_saturday_overtime_type' => $input['salary_saturday_overtime_type'],
                'salary_sunday_overtime' => $input['salary_sunday_overtime'],
                'salary_sunday_overtime_type' => $input['salary_sunday_overtime_type'],
                'salary_holiday_overtime' => $input['salary_holiday_overtime'],
                'salary_holiday_overtime_type' => $input['salary_holiday_overtime_type'],
                'is_allowance' => $input['is_allowance'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            //Update template code
            $templateCode = 'SLT_' . date('dmY') . sprintf("%02d", $templateId);

            $this->template->edit([
                'staff_salary_template_code' => $templateCode
            ], $templateId);

            $arrayAllowance = [];

            if (isset($input['arrayAllowance']) && count($input['arrayAllowance']) > 0) {
                foreach ($input['arrayAllowance'] as $v) {
                    $arrayAllowance [] = [
                        'staff_salary_template_id' => $templateId,
                        'salary_allowance_id' => $v['salary_allowance_id'],
                        'staff_salary_allowance_num' => $v['staff_salary_allowance_num'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Thêm phụ cấp
            $mTemplateAllowance->insert($arrayAllowance);

           
            DB::commit();

            return [
                'error' => false,
                'message' => __('Thêm thành công'),
                'staff_salary_template_id' => $templateId,
                'staff_salary_template_id_input' => view('staff-salary::template.staff_salary_template_id',[
                    'optionStaffSalaryTemplate' => $repoStaffTemplate->getOption(),
                ])->render(),
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Thêm thất bại'),
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $templateId
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataEdit($templateId)
    {
        $mSalaryType = app()->get(StaffSalaryTypeTable::class);
        $mSalaryPayPeriod = app()->get(StaffSalaryPayPeriodTable::class);
        $mSalaryUnit = app()->get(StaffSalaryUnitTable::class);
        $mTemplateAllowance = app()->get(StaffSalaryTemplateAllowanceTable::class);

        //Lấy data mẫu lương
        $info = $this->template->getInfo($templateId);
        //Lấy data phụ cấp của mẫu lương
        $getAllowance = $mTemplateAllowance->getTemplateAllowance($templateId);
        //Option loại lương
        $optionType = $mSalaryType->getList();
        //Option kỳ hạn trả lương
        $optionPayPeriod = $mSalaryPayPeriod->getList();
        //Option đơn vị tiền tệ
        $optionUnit = $mSalaryUnit->getUnit();

        return [
            'item' => $info,
            'templateAllowance' => $getAllowance,
            'optionType' => $optionType,
            'optionPayPeriod' => $optionPayPeriod,
            'optionUnit' => $optionUnit
        ];
    }

    /**
     * Chỉnh sửa mẫu lương
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mTemplateAllowance = app()->get(StaffSalaryTemplateAllowanceTable::class);

            //Chỉnh sửa mẫu lương
            $this->template->edit([
                'staff_salary_template_name' => $input['staff_salary_template_name'],
                'staff_salary_pay_period_code' => $input['staff_salary_pay_period_code'],
                'payment_type' => $input['payment_type'],
                'staff_salary_type_code' => $input['staff_salary_type_code'],
                'staff_salary_unit_code' => $input['staff_salary_unit_code'],
                'salary_default' => $input['salary_default'],
                'salary_saturday_default' => $input['salary_saturday_default'],
                'salary_saturday_default_type' => $input['salary_saturday_default_type'],
                'salary_sunday_default' => $input['salary_sunday_default'],
                'salary_sunday_default_type' => $input['salary_sunday_default_type'],
                'salary_holiday_default' => $input['salary_holiday_default'],
                'salary_holiday_default_type' => $input['salary_holiday_default_type'],
                'is_overtime' => $input['is_overtime'],
                'salary_overtime' => $input['salary_overtime'],
                'salary_saturday_overtime' => $input['salary_saturday_overtime'],
                'salary_saturday_overtime_type' => $input['salary_saturday_overtime_type'],
                'salary_sunday_overtime' => $input['salary_sunday_overtime'],
                'salary_sunday_overtime_type' => $input['salary_sunday_overtime_type'],
                'salary_holiday_overtime' => $input['salary_holiday_overtime'],
                'salary_holiday_overtime_type' => $input['salary_holiday_overtime_type'],
                'is_allowance' => $input['is_allowance'],
                'is_actived' => $input['is_actived'],
                'updated_by' => Auth()->id()
            ], $input['staff_salary_template_id']);

            $arrayAllowance = [];

            if (isset($input['arrayAllowance']) && count($input['arrayAllowance']) > 0) {
                foreach ($input['arrayAllowance'] as $v) {
                    $arrayAllowance [] = [
                        'staff_salary_template_id' => $input['staff_salary_template_id'],
                        'salary_allowance_id' => $v['salary_allowance_id'],
                        'staff_salary_allowance_num' => $v['staff_salary_allowance_num'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Xoá phụ cấp cũ
            $mTemplateAllowance->removeTemplateAllowance($input['staff_salary_template_id']);
            //Thêm phụ cấp
            $mTemplateAllowance->insert($arrayAllowance);

            DB::commit();

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return array|mixed
     */
    public function updateStatus($input)
    {
        try {
            //Cập nhật trạng thái mẫu lương
            $this->template->edit([
                'is_actived' => $input['is_actived']
            ], $input['staff_salary_template_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }

    /**
     * Xoá mẫu lương
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            //Cập nhật cờ xoá của mẫu lương
            $this->template->edit([
                'is_deleted' => 1
            ], $input['staff_salary_template_id']);

            return [
                'error' => false,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá thất bại')
            ];
        }
    }
}