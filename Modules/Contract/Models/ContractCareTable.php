<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/2/2021
 * Time: 10:26 AM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractCareTable extends Model
{
    protected $table = "contract_care";
    protected $primaryKey = "contract_care_id";
    protected $fillable = [
      "contract_care_id",
      "contract_id",
      "contract_type",
      "status",
      "created_by",
      "updated_by",
      "created_at",
      "updated_at",
    ];
    use ListTableTrait;

    protected function _getList(&$filter = [])
    {
        $ds = $this->select(
            "contract_care.status as care_status",
            "contracts.contract_id",
            "contracts.contract_category_id",
            "contract_categories.contract_category_name",
            "contracts.contract_name",
            "contracts.contract_code",
            "contracts.contract_no",
            "contracts.sign_date",
            "contracts.performer_by",
            "staff_performer.full_name as staff_performer_name",
            "contracts.effective_date",
            "contracts.expired_date",
            "contracts.warranty_start_date",
            "contracts.warranty_end_date",
            "contracts.content",
            "contracts.note",
            "contracts.status_code",
            "contract_category_status.status_name",
            "contract_partner.address",
            "contract_partner.email",
            "contract_partner.phone",
            "contract_partner.tax_code",
            "contract_partner.representative",
            "contract_partner.hotline",
            "contract_partner.staff_title as staff_title_name",
            "contract_partner.partner_object_type as partner_object_type",
            DB::raw("(CASE WHEN contract_partner.partner_object_type != 'supplier' THEN customers.full_name
                                ELSE suppliers.supplier_name END) as partner_name"),
            "contract_payment.total_amount",
            "contract_payment.tax",
            "contract_payment.discount",
            "contract_payment.last_total_amount",
        )
            ->join("contracts", "contracts.contract_id", "{$this->table}.contract_id")
            ->leftJoin('contract_categories', 'contract_categories.contract_category_id', '=', "contracts.contract_category_id")
            ->leftJoin("contract_category_status", function($join){
                $join->on("contract_category_status.status_code", "=", "contracts.status_code")
                    ->on("contract_category_status.contract_category_id", "=", "contract_categories.contract_category_id");
            })
            ->leftJoin('staffs as staff_performer', 'staff_performer.staff_id', '=', "contracts.performer_by")
            ->leftJoin('contract_partner', 'contract_partner.contract_id', '=', "contracts.contract_id")
            ->leftJoin('contract_payment', 'contract_payment.contract_id', '=', "contracts.contract_id")
            ->leftJoin("customers", function($join){
                $join->on("customers.customer_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "!=", DB::raw("'supplier'"));
            })
            ->leftJoin("suppliers", function($join){
                $join->on("suppliers.supplier_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'supplier'"));
            })
            ->where("contracts.is_deleted", 0);
        if(isset($filter['search']) && $filter['search'] != ""){
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("contracts.contract_name", 'like', '%' . $search . '%')
                    ->orWhere("customers.full_name", 'like', '%' . $search . '%')
                    ->orWhere("suppliers.supplier_name", 'like', '%' . $search . '%');
            });
            unset($filter['search']);
        }
        if(isset($filter['contract_type']) && $filter['contract_type'] != ""){
            $ds->where("{$this->table}.contract_type", $filter['contract_type']);
            unset($filter['contract_type']);
        }
        if(isset($filter['contract_category_id']) && $filter['contract_category_id'] != ""){
            $ds->where("contracts.contract_category_id", $filter['contract_category_id']);
            unset($filter['contract_category_id']);
        }
        if(isset($filter['partner_object_type']) && $filter['partner_object_type'] != ""){
            $ds->where("contract_partner.partner_object_type", $filter['partner_object_type']);
            unset($filter['partner_object_type']);
        }
        if(isset($filter['performer_by']) && $filter['performer_by'] != ""){
            $ds->where("contracts.performer_by", $filter['performer_by']);
            unset($filter['performer_by']);
        }
        if(isset($filter['status_code']) && $filter['status_code'] != ""){
            $ds->where("contracts.status_code", $filter['status_code']);
            unset($filter['status_code']);
        }
        if (isset($filter["expired_date"]) != "") {
            $arr_filter = explode(" - ", $filter["expired_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("contracts.expired_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["expired_date"], $filter['contract_type']);
        return $ds->orderBy("contract_care.created_at", "desc");
    }

    public function createData($data)
    {
        return $this->create($data);
    }
    public function deleteByContract($id)
    {
        return $this->where("contract_id", $id)->delete();
    }
    public function updateData($data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
    public function updateDataByContract(array $data, $id)
    {
        return $this->where("contract_id", $id)->update($data);
    }

    public function getReportContractCare($filter)
    { 
        $ds = $this->select(
            DB::raw("COUNT(IF({$this->table}.contract_type = 'expire', 1, null)) as total_expire"),
            DB::raw("COUNT(IF({$this->table}.contract_type = 'soon_expire', 1, null)) as total_soon_expire"),
            DB::raw("DATE_FORMAT({$this->table}.created_at, '%d/%m/%Y') as created_group")
        )
            ->join("contracts", "contracts.contract_id", "{$this->table}.contract_id")
            ->leftJoin("staffs", "staffs.staff_id", "contracts.performer_by");
        if(isset($filter['time']) && $filter['time'] != ''){
            $arrTime = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $arrTime[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $arrTime[1])->format('Y-m-d 23:59:59');
            $ds->whereBetween("{$this->table}.created_at", [$startTime, $endTime]);
        }
        if(isset($filter['branch_id']) && $filter['branch_id'] != ''){
            $ds->where("staffs.branch_id", $filter['branch_id']);
        }
        if(isset($filter['department_id']) && $filter['department_id'] != ''){
            $ds->where("staffs.department_id", $filter['department_id']);
        }
        if(isset($filter['staff_id']) && $filter['staff_id'] != ''){
            $ds->where("staffs.staff_id", $filter['staff_id']);
        }
        $ds->groupBy(DB::raw("DATE_FORMAT({$this->table}.created_at, '%d/%m/%Y')"));
        return $ds->get()->toArray();
    }
    public function getReportContractCareSuccess($filter)
    {
        $ds = $this->select(
            DB::raw("COUNT(IF({$this->table}.contract_type = 'expire' and status = 'success', 1, null)) as total_success_expire"),
            DB::raw("COUNT(IF({$this->table}.contract_type = 'soon_expire' and status = 'success', 1, null)) as total_success_soon_expire"),
            DB::raw("DATE_FORMAT({$this->table}.updated_at, '%d/%m/%Y') as created_group")
        )
            ->join("contracts", "contracts.contract_id", "{$this->table}.contract_id")
            ->leftJoin("staffs", "staffs.staff_id", "contracts.performer_by");
        if(isset($filter['time']) && $filter['time'] != ''){
            $arrTime = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $arrTime[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $arrTime[1])->format('Y-m-d 23:59:59');
            $ds->whereBetween("{$this->table}.updated_at", [$startTime, $endTime]);
        }
        if(isset($filter['branch_id']) && $filter['branch_id'] != ''){
            $ds->where("staffs.branch_id", $filter['branch_id']);
        }
        if(isset($filter['department_id']) && $filter['department_id'] != ''){
            $ds->where("staffs.department_id", $filter['department_id']);
        }
        if(isset($filter['staff_id']) && $filter['staff_id'] != ''){
            $ds->where("staffs.staff_id", $filter['staff_id']);
        }
        $ds->groupBy(DB::raw("DATE_FORMAT({$this->table}.updated_at, '%d/%m/%Y')"));
        return $ds->get()->toArray();
    }
}