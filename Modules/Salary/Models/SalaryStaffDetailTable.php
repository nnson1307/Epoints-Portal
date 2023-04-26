<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class SalaryStaffDetailTable extends Model
{

    protected $table = "salary_staff_detail";
    protected $primaryKey = "salary_staff_detail_id";

    /**
     * Lấy danh sashc hoa hồng
     */
    public function getListDetail($filters = []){
        $page = (int)($filters["page"] ?? 1);
        $display = (int)($filters["perpage"] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                'role',
                'contracts.contract_code',
                'contracts.contract_name',
                'ticket.ticket_id',
                $this->table.'.ticket_code',
                $this->table.'.value',
                $this->table.'.percent',
                $this->table.'.commission',
                'contract_category_status.status_name'
            )
            ->join('contracts','contracts.contract_id',$this->table.'.contract_id')
            ->leftJoin('contract_partner','contract_partner.contract_id','contracts.contract_id')
            ->leftJoin('ticket','ticket.ticket_code',$this->table.'.ticket_code')
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->leftJoin('salary_commission_config','salary_commission_config.department_id','staffs.department_id')
            ->leftJoin('contract_category_status','contract_category_status.status_code','contracts.status_code')
            ->orderBy($this->table.'.salary_staff_detail_id','DESC');



        if(isset($filters['salary_staff_id']) && $filters['salary_staff_id'] != ""){
            $oSelect = $oSelect->where($this->table.'.salary_staff_id',$filters['salary_staff_id']);
        }

//        if(isset($filters['check_staff_commission']) && $filters['check_staff_commission'] == 1){
//            $oSelect = $oSelect->whereRaw("CASE WHEN staffs.staff_type = 'probationers' THEN salary_commission_config.kpi_probationers <= {$this->table}.value ELSE salary_commission_config.kpi_staff <= {$this->table}.value END");
//        }

        return $oSelect->paginate($display, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Lấy danh sashc hoa hồng
     */
    public function getTotalValue($filters = []){
        $page = (int)($filters["page"] ?? 1);
        $display = (int)($filters["perpage"] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                DB::raw("SUM({$this->table}.value) as total_value"),
                DB::raw("SUM({$this->table}.commission) as total_commisson")
            )
            ->join('contracts','contracts.contract_id',$this->table.'.contract_id')
            ->leftJoin('ticket','ticket.ticket_code',$this->table.'.ticket_code')
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->leftJoin('salary_commission_config','salary_commission_config.department_id','staffs.department_id')
            ->leftJoin('contract_category_status','contract_category_status.status_code','contracts.status_code')
            ->orderBy($this->table.'.salary_staff_detail_id','DESC');


        if(isset($filters['salary_staff_id'])){
            $oSelect = $oSelect->where($this->table.'.salary_staff_id',$filters['salary_staff_id']);
        }
//        if(isset($filters['check_staff_commission']) && $filters['check_staff_commission'] == 1){
//            $oSelect = $oSelect->whereRaw("CASE WHEN staffs.staff_type = 'probationers' THEN salary_commission_config.kpi_probationers <= {$this->table}.value ELSE salary_commission_config.kpi_staff <= {$this->table}.value END");
//        }
        return $oSelect->first();
    }

    /**
     * kiểm tra phòng kỹ thuật mới có ticket_id
     */
    public function checkDepartment($salary_staff_id){
        $oSelect = $this
            ->select("{$this->table}.*","salary_commission_config.type_view")
            ->leftjoin("salary_staff","salary_staff.salary_staff_id","{$this->table}.salary_staff_id")
            ->leftjoin("salary_commission_config","salary_commission_config.department_id","salary_staff.department_id")
            ->where("{$this->table}.salary_staff_id",$salary_staff_id);

        return $oSelect->first();
    }

    /**
     * Lấy tất cả danh sách
     * @param array $filter
     */
    public function getAll($filter = []){
        $oSelect = $this
            ->leftJoin('staffs' ,'staffs.staff_id',$this->table.'.staff_id')
            ->leftJoin('staff_title' ,'staff_title.staff_title_id','staffs.staff_title_id')
            ->leftJoin('salary_staff' ,'salary_staff.salary_staff_id',$this->table.'.salary_staff_id')
            ->leftJoin('salary_commission_config' ,'salary_commission_config.department_id','salary_staff.department_id')
            ->leftJoin('contracts','contracts.contract_id',$this->table.'.contract_id')
            ->leftJoin('contract_payment','contract_payment.contract_id','contracts.contract_id')
            ->leftJoin('contract_partner','contract_partner.contract_id','contracts.contract_id')
            ->leftJoin('ticket','ticket.ticket_code',$this->table.'.ticket_code')
            ->leftJoin('ticket_staff_queue','ticket_staff_queue.staff_id',$this->table.'.staff_id')
            ->leftJoin("ticket_issue as issue", "issue.ticket_issue_id", '=', "ticket.ticket_issue_id")
            ->leftJoin('customers', 'customers.customer_id', 'ticket.customer_id')
            ->leftJoin('ticket_queue', 'ticket_queue.ticket_queue_id', 'ticket_staff_queue.ticket_queue_id')
            ->select(
                'salary_staff.staff_name',
                'contracts.contract_code as contract_code',
                'contracts.contract_form as contract_form',
                $this->table.'.commission',
                $this->table.'.ticket_code',
                'customers.full_name as customer_name',
                'ticket_queue.queue_name',
                'issue.name as issue_name',
                'ticket.date_issue',
                'ticket.date_finished',
                'ticket.ticket_status_id',
                'contract_payment.total_amount',
                'contract_payment.last_total_amount',
                'contract_partner.partner_object_form',
                'salary_commission_config.internal_new',
                'salary_commission_config.internal_renew',
                'salary_commission_config.external_new',
                'salary_commission_config.external_renew',
                'salary_commission_config.partner_new',
                'salary_commission_config.partner_renew',
            );

//        Kì lương
        if(isset($filter['salary_id']) && $filter['salary_id'] != "") {
            $oSelect = $oSelect->where('salary_staff.salary_id',$filter['salary_id']);
        }

//        Lọc theo phòng ban
        if(isset($filter['department_id']) && $filter['department_id'] != "") {
            $oSelect = $oSelect->where('salary_staff.department_id',$filter['department_id']);
        }

//        Lọc theo nhân viên
        if(isset($filter['staff_id']) && $filter['staff_id'] != "") {
            $oSelect = $oSelect->where('salary_staff.staff_id',$filter['staff_id']);
        }
        // lọc theo loại phòng ban kỹ thuật hay kinh doanh
        if(isset($filter['type']) && $filter['type'] != "") {
            $oSelect = $oSelect->where('salary_commission_config.type_view',$filter['type']);
        }
        return $oSelect->orderBy($this->table.'.salary_staff_detail_id','DESC')->get();

    }

}