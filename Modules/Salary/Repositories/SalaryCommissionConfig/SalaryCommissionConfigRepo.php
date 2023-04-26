<?php

namespace Modules\Salary\Repositories\SalaryCommissionConfig;

/**
 * Class SalaryCommissionConfigRepo
 * @package Modules\SalaryCommissionConfig\Repositories\SalaryCommissionConfig
 * @author VuND
 * @since 02/12/2021
 */
use Modules\Salary\Models\DepartmentTable;
use Modules\Salary\Models\SalaryCommissionConfigTable;
use Carbon\Carbon;


class SalaryCommissionConfigRepo implements SalaryCommissionConfigInterface
{
    protected $department_table;
    protected $salary_commission_config;


    public function __construct(
        DepartmentTable $department_table,
        SalaryCommissionConfigTable $salary_commission_config
        )
    {
        $this->department_table = $department_table;
        $this->salary_commission_config = $salary_commission_config;
    }
    /**
     *get list Salary Commission Config
     */
    public function list(array $filters = [])
    {
        $filters = array_filter($filters, function($value) { return !is_null($value) && $value !== ''; });
        $allDepartment = $this->department_table->getOption();
        $departmentHasSalary = $this->salary_commission_config->getDepmartentId();
        $department_list = array_diff_key($allDepartment, array_flip($departmentHasSalary));

        return [
            'list' => $this->salary_commission_config->getDataList($filters),
            'department_list' => $department_list,
            'all_department' => $allDepartment,
            'params' => $filters,
            'page' => isset($filters['page'])?$filters['page']:1
        ];
    }

    /**
     * delete Salary Commission Config
     */
    public function remove($id)
    {
        $this->salary_commission_config->remove($id);
    }

    /**
     * add Salary Commission Config
     */
    public function add(array $data)
    {

        return $this->salary_commission_config->add($data);
    }

    /*
     * edit Salary Commission Config
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

    public function addAction($params)
    {
        // try{
            $salary_commission_config_data = [
                'department_id' =>  $params['department_id'],
                'type_view' =>  $params['type_view'],
                'internal_new' => str_replace('.', '.', isset($params['internal_new']) ? $params['internal_new']: 0),
                'internal_renew' => str_replace('.', '.', isset($params['internal_renew']) ? $params['internal_renew']: 0),
                'external_new' => str_replace('.', '.', isset($params['external_new']) ? $params['external_new']: 0),
                'external_renew' => str_replace('.', '.', isset($params['external_renew']) ? $params['external_renew']: 0),
                'partner_new' => str_replace('.', '.', isset($params['partner_new']) ? $params['partner_new']: 0),
                'partner_renew' => str_replace('.', '.', isset($params['partner_renew']) ? $params['partner_renew']: 0),
                'installation_commission' => str_replace('.', '.', isset($params['installation_commission']) ? $params['installation_commission']: 0),
                'kpi_staff' => str_replace(',', '', isset($params['kpi_staff']) ? $params['kpi_staff']: 0),
                'kpi_probationers' => str_replace(',', '', isset($params['kpi_probationers']) ? $params['kpi_probationers']: 0),
                'created_by' => \Auth::id(),
                'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
            ];
            $salary_commission_config_id = $this->salary_commission_config->add($salary_commission_config_data);
            if($salary_commission_config_id){
                return [
                    'error' => 0,
                    'salary_commission_config_id' => $salary_commission_config_id,
                    'message' => __('Thêm thành công'),
                ];
            }
        // }catch (\Exception $e){
        //     return [
        //         'error' => 1,
        //         'refund_id' => '',
        //         'message' => __('Hiện tại có lỗi xảy ra'),
        //     ];
        // }
    }

    public function addView()
    {
            $allDepartment = $this->department_table->getOption();
            $departmentHasSalary = $this->salary_commission_config->getDepmartentId();
            $params['department_list'] = array_diff_key($allDepartment, array_flip($departmentHasSalary));
            $html =  view('Salary::salary_commission_config.add',$params)->render();
            return [
                'error' => 0,
                'html' => $html,
                'message' => __('Show modal thành công'),
            ];
    }
    public function editAction($params)
    {
            $params['item'] =  $this->salary_commission_config->getItem($params['id']);
            $params['department_list'] = $this->department_table->getOption();
            $html =  view('Salary::salary_commission_config.edit',$params)->render();
            return [
                'error' => 0,
                'html' => $html,
                'message' => __('Show modal thành công'),
            ];
    }

    public function submitAction($params)
    {
        $salary_commission_config_data = [
            'type_view' =>  $params['type_view'],
            'internal_new' => str_replace('.', '.', isset($params['internal_new']) ? $params['internal_new']: 0),
            'internal_renew' => str_replace('.', '.', isset($params['internal_renew']) ? $params['internal_renew']: 0),
            'external_new' => str_replace('.', '.', isset($params['external_new']) ? $params['external_new']: 0),
            'external_renew' => str_replace('.', '.', isset($params['external_renew']) ? $params['external_renew']: 0),
            'partner_new' => str_replace('.', '.', isset($params['partner_new']) ? $params['partner_new']: 0),
            'partner_renew' => str_replace('.', '.', isset($params['partner_renew']) ? $params['partner_renew']: 0),
            'installation_commission' => str_replace('.', '.', isset($params['installation_commission']) ? $params['installation_commission']: 0),
            'kpi_staff' => str_replace(',', '', isset($params['kpi_staff']) ? $params['kpi_staff']: 0),
            'kpi_probationers' => str_replace(',', '', isset($params['kpi_probationers']) ? $params['kpi_probationers']: 0),
            'updated_by' => \Auth::id(),
            'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
        ];
        $salary_commission_config_id = $this->salary_commission_config->edit($salary_commission_config_data,$params['salary_commission_config_id']);
        if($salary_commission_config_id){
            return [
                'error' => 0,
                'salary_commission_config_id' => $salary_commission_config_id,
                'message' => __('Cập nhật thành công'),
            ];
        }
    }
}