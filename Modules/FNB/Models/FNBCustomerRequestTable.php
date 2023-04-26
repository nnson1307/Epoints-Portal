<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Carbon;

class FNBCustomerRequestTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_customer_request';
    protected $primaryKey = 'customer_request_id';
    protected $fillable
        = [
            'customer_request_id',
            'fnb_customer_id',
            'table_id',
            'action',
            'payment',
            'note',
            'status',
            'process_by',
            'process_at',
            'created_at'
        ];

    public $timestamps = false;

    public function _getList(&$filter = []){
        $oSelect = $this
            ->select(
                $this->table.'.*',
                'customers.full_name',
                'staffs.full_name as process_name'
            )
            ->join('fnb_customer','fnb_customer.fnb_customer_id',$this->table.'.fnb_customer_id')
            ->join('customers','customers.customer_id','fnb_customer.customer_id')
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.process_by');

        if (isset($filter['tableId'])){
            $oSelect = $oSelect->where($this->table.'.table_id',$filter['tableId']);
            unset($filter['tableId']);
        }

        return $oSelect->orderBy($this->table.'.customer_request_id','DESC');
    }

    public function editAction($data,$id){
        return $this
            ->where('customer_request_id',$id)
            ->update($data);
    }

    public function getDetail($id){
        $oSelect = $this
            ->select(
                $this->table.'.*',
                'customers.full_name',
                'staffs.full_name as process_name'
            )
            ->join('fnb_customer','fnb_customer.fnb_customer_id',$this->table.'.fnb_customer_id')
            ->join('customers','customers.customer_id','fnb_customer.customer_id')
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.process_by')
            ->where($this->table.'.customer_request_id',$id);

        return $oSelect->first();
    }
    public function allRequest( &$input = []){
        $page    = (int) ($input['page'] ?? 1);
        $display = (int) ($input['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $oSelect = $this
            ->select(
                "{$this->table}.customer_request_id",
                "{$this->table}.fnb_customer_id as customer_id",
                "customers.full_name as customer_name",
                "{$this->table}.table_id",
                "fnb_table.name as table_name",
                "{$this->table}.action",
                "{$this->table}.status",
                "{$this->table}.payment",
                "payment_method.payment_method_name_vi as  method_name_vi",
                "payment_method.payment_method_name_en as  method_name_en",
                "{$this->table}.note",
                "{$this->table}.process_by",
                "{$this->table}.process_at",
                "{$this->table}.created_at"
            )
            ->orderBy( "{$this->table}.customer_request_id",'asc')
            ->leftJoin("fnb_customer","{$this->table}.fnb_customer_id","fnb_customer.fnb_customer_id")
            ->leftJoin("customers","fnb_customer.customer_id","customers.customer_id")
            ->leftJoin("payment_method","payment_method.payment_method_code","{$this->table}.payment")
            ->leftJoin("fnb_table","{$this->table}.table_id","fnb_table.table_id");
        if(isset($input['table_id']) && $input['table_id'] != null){
            $oSelect = $oSelect->where( "{$this->table}.table_id",$input['table_id'] );
        }
        if(isset($input['action']) && $input['action'] != null){
            $oSelect = $oSelect->where( "{$this->table}.action",$input['action'] );
        }
        if(isset($input['payment']) && $input['payment'] != null){
            $oSelect = $oSelect->where( "{$this->table}.payment",$input['payment'] );
        }
        if(isset($input['status']) && $input['status'] != null){
            $oSelect = $oSelect->where( "{$this->table}.status",$input['status'] );
        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);

    }
}