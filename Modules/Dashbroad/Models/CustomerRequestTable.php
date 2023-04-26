<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/31/2020
 * Time: 4:57 PM
 */

namespace Modules\Dashbroad\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerRequestTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_request";
    protected $primaryKey = "customer_request_id";
    protected $fillable = [
        "customer_request_id",
        "object_id",
        "object_type",
        "customer_request_name",
        "customer_request_phone",
        "customer_request_type",
        "customer_request_note",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Thêm khách hàng tiềm năng
     *
     * @param array $data
     * @return mixed
     */
    public function getListToDay()
    {
        $day = Carbon::now()->format('Y-m-d');
        $ds = $this
            ->select(
                "{$this->table}.customer_request_id",
                "{$this->table}.object_id",
                "{$this->table}.object_type",
                "{$this->table}.customer_request_name",
                "{$this->table}.customer_request_phone",
                "{$this->table}.customer_request_type",
                "{$this->table}.customer_request_note",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "st.full_name as staff_name",
                "st.staff_id as staff_id",
                "c.full_name as customer_name",
                "c.customer_id as customer_id",
                "cpo.full_name as customer_lead_name",
                "sl.full_name as sale_name",
                "cpo.sale_id",
                "cpo.customer_lead_id as customer_lead_id",
            )
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin('cpo_customer_lead as cpo', function ($join) {
                $join->on('cpo.customer_lead_id', '=',  "{$this->table}.object_id");
                $join->where("{$this->table}.object_type", '=', 'customer_lead');
            })
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "cpo.pipeline_code")
            ->leftJoin('staffs as sl', 'sl.staff_id', "=", "cpo.sale_id")
            ->leftJoin('customers as c', function ($join) {
                $join->on('c.customer_id', '=', "{$this->table}.object_id");
                $join->where("{$this->table}.object_type", '=', 'customer');
            })->whereDate("{$this->table}.created_at", $day)
            ->orderBy("{$this->table}.created_at", 'DESC');
        $ds = $this->permissionView($ds);
        return $ds->get();
        $page    = (int) ($filters['page'] ?? 1);
        // $display = (int) ($filters['perpage'] ?? 10);
        // return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }


    /**
     * Lấy tổng số tiếp nhận
     *
     * @param array $data
     * @return mixed
     */
    public function getTotal()
    {
        $day = Carbon::now()->format('Y-m-d');
        $ds = $this
            ->select(
                DB::raw("COUNT({$this->table}.customer_request_id) as total")
            )
            ->whereDate("{$this->table}.created_at", $day)->first();
        return $ds;
    }

    function getConfigShowInfo()
    {
        $listConfig = DB::table('customer_request_attribute')->where('object_type', 'info')->get();
        $arrData = [];

        foreach ($listConfig as $value) {
            $arrData[$value->object_key] = $value->object_value;
        }
        return $arrData;
    }

    function permissionView($oSelect)
    {
        // var_dump(\Auth::id());die;
        if (!\Auth::user()->is_admin) {
            $oSelect = $oSelect->where(function ($oSelect) {
                $oSelect->orWhere("{$this->table}.created_by", \Auth::id())
                    ->orWhere("cpo.sale_id", \Auth::id())
                    ->orWhere("cpo_pipelines.owner_id", \Auth::id());
               
            });
        }
        return $oSelect;
    }
}
