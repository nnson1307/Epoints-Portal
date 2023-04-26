<?php
namespace Modules\ManagerProject\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProjectExpenditureTable extends Model
{
protected $table = "manage_project_expenditure";
protected $primaryKey = "manage_project_expenditure_id";
    protected $casts = [
        'total_money' => 'float',
        'total_amount' => 'float',
    ];
    public function addExpenditure($dataExpenditure){
        return $this->insertGetId($dataExpenditure);
    }


    public function getExpenditureReceipt($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_expenditure_id as expenditure_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.obj_id as receipt_id",
                "{$this->table}.obj_code as receipt_code",
                "receipts.total_money"
            )
        ->leftJoin("receipts", "{$this->table}.obj_id","receipts.receipt_id")
        ->where("receipts.status","paid")
        ->where( "{$this->table}.type","receipt");
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }
    public function getExpenditurePayment($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_expenditure_id as expenditure_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.obj_id as payment_id",
                "{$this->table}.obj_code as payment_code",
                "payments.total_amount"
            )
            ->leftJoin("payments", "{$this->table}.obj_id","payments.payment_id")
            ->where("payments.status","paid")
            ->where("{$this->table}.type","payment");
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }
    public function getListExpenditure($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_expenditure_id as expenditure_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.obj_id",
                "{$this->table}.obj_code"
            );

        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        if(isset($filter['receipt_payment_type']) && $filter['receipt_payment_type'] != null){
            $mSelect->where("{$this->table}.type",$filter['receipt_payment_type']);
        }
        if(isset($filter['arrIdProject']) && $filter['arrIdProject'] != null){
            $mSelect->whereIn("{$this->table}.manage_project_id",$filter['arrIdProject']);
        }
        return $mSelect->get()->toArray();
    }
    public function getListExpenditurePaginate($filter = []){
//        dd($filter);
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_expenditure_id as expenditure_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.obj_id",
                "{$this->table}.obj_code"
            )
            ->leftJoin('receipts','receipts.receipt_code',$this->table.'.obj_code')
            ->leftJoin('payments','payments.payment_code',$this->table.'.obj_code')
            ->leftJoin('branches','branches.branch_code','payments.branch_code');

        if(isset($filter['search'])){
            $mSelect->where("{$this->table}.obj_code",'like','%'.$filter['search'].'%');
            unset($filter);
        }

        if(isset($filter['status']) && $filter['status'] != null){
            $status = $filter['status'];
            $mSelect
                ->where(function ($qs) use ($status){
                    $qs->where("receipts.status",$status)
                        ->orWhere("payments.status",$status);
                });
        }

        if(isset($filter['staff_id']) && $filter['staff_id'] != null){
            $staffId = $filter['staff_id'];
            $mSelect
                ->where(function ($qs) use ($staffId){
                    $qs->where("receipts.created_by",$staffId)
                        ->orWhere("payments.created_by",$staffId);
                });
        }

        if(isset($filter['branch_id']) && $filter['branch_id'] != null){
            $branchId = $filter['branch_id'];
            $mSelect
                ->where(function ($qs) use ($branchId){
                    $qs->where("receipts.branch_id",$branchId)
                        ->orWhere("branches.branch_id",$branchId);
                });
        }

        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }

        if(isset($filter['created_at']) && $filter['created_at'] != null){
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 23:59:59");
            $mSelect
                ->where(function ($qs) use ($startTime,$endTime){
                    $qs->whereBetween("receipts.created_at",[$startTime,$endTime])
                        ->orWhereBetween("payments.created_at",[$startTime,$endTime]);
                });
        }

        if(isset($filter['receipt_payment_type']) && $filter['receipt_payment_type'] != null){
            $mSelect->where("{$this->table}.type",$filter['receipt_payment_type']);
        }
        if(isset($filter['arrIdProject']) && $filter['arrIdProject'] != null){
            $mSelect->whereIn("{$this->table}.manage_project_id",$filter['arrIdProject']);
        }
        return $mSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}