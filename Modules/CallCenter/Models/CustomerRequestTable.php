<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/31/2020
 * Time: 4:57 PM
 */

namespace Modules\CallCenter\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        "custom_column_name_1",
        "custom_column_value_1",
        "custom_column_name_2",
        "custom_column_value_2",
        "custom_column_name_3",
        "custom_column_value_3",
        "custom_column_name_4",
        "custom_column_value_4",
        "custom_column_name_5",
        "custom_column_value_5",
        "custom_column_name_6",
        "custom_column_value_6",
        "custom_column_name_7",
        "custom_column_value_7",
        "custom_column_name_8",
        "custom_column_value_8",
        "custom_column_name_9",
        "custom_column_value_9",
        "custom_column_name_10",
        "custom_column_value_10",
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Thêm khách hàng tiềm năng
     *
     * @param array $data
     * @return mixed
     */
    public function getList(array $filters)
    {
       
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
                "{$this->table}.custom_column_name_1",
                "{$this->table}.custom_column_value_1",
                "{$this->table}.custom_column_name_2",
                "{$this->table}.custom_column_value_2",
                "{$this->table}.custom_column_name_3",
                "{$this->table}.custom_column_value_3",
                "{$this->table}.custom_column_name_4",
                "{$this->table}.custom_column_value_4",
                "{$this->table}.custom_column_name_5",
                "{$this->table}.custom_column_value_5",
                "{$this->table}.custom_column_name_6",
                "{$this->table}.custom_column_value_6",
                "{$this->table}.custom_column_name_7",
                "{$this->table}.custom_column_value_7",
                "{$this->table}.custom_column_name_8",
                "{$this->table}.custom_column_value_8",
                "{$this->table}.custom_column_name_9",
                "{$this->table}.custom_column_value_9",
                "{$this->table}.custom_column_name_10",
                "{$this->table}.custom_column_value_10",
                "st.full_name as staff_name",
                "st.staff_id as staff_id",
                "c.full_name as customer_name",
                "c.customer_id as customer_id",
                "cpo.full_name as customer_lead_name",
                "sl.full_name as sale_name",
                "cpo.sale_id",
                "cpo.customer_lead_id as customer_lead_id",
                "cpo_pipelines.owner_id"
            )
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin('cpo_customer_lead as cpo', function($join)
            {
                $join->on('cpo.customer_lead_id', '=',  "{$this->table}.object_id");
                $join->where("{$this->table}.object_type", '=', 'customer_lead');
            })
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "cpo.pipeline_code")
            ->leftJoin('staffs as sl', 'sl.staff_id', "=", "cpo.sale_id")
            ->leftJoin('customers as c', function($join)
            {
                $join->on('c.customer_id', '=', "{$this->table}.object_id");
                $join->where("{$this->table}.object_type", '=', 'customer');
            });
        if (isset($filters['search']) && $filters['search'] != "") {
            $keyWord = $filters['search'];
            $ds->where(function ($query) use ($keyWord) {
                $query->where("c.full_name", 'like', '%' . $keyWord . '%')
                    ->orWhere("c.customer_code", 'like', '%' . $keyWord . '%')
                    ->orWhere("cpo.customer_lead_code", 'like', '%' . $keyWord . '%')
                    ->orWhere("cpo.full_name", 'like', '%' . $keyWord . '%')
                    ->orWhere("c.phone1", 'like', '%' . $keyWord . '%')
                    ->orWhere("cpo.phone", 'like', '%' . $keyWord . '%');
            });
        }
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $ds->whereDate("{$this->table}.created_at", ">=", $startTime);
            $ds->whereDate("{$this->table}.created_at", "<=", $endTime);
        }
        $ds = $this->permissionView($ds);
        $ds->orderBy("{$this->table}.created_at", 'DESC');   
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? 10);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy thông tin chi tiết
     */
    public function getInfo($id){
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
                "{$this->table}.custom_column_name_1",
                "{$this->table}.custom_column_value_1",
                "{$this->table}.custom_column_name_2",
                "{$this->table}.custom_column_value_2",
                "{$this->table}.custom_column_name_3",
                "{$this->table}.custom_column_value_3",
                "{$this->table}.custom_column_name_4",
                "{$this->table}.custom_column_value_4",
                "{$this->table}.custom_column_name_5",
                "{$this->table}.custom_column_value_5",
                "{$this->table}.custom_column_name_6",
                "{$this->table}.custom_column_value_6",
                "{$this->table}.custom_column_name_7",
                "{$this->table}.custom_column_value_7",
                "{$this->table}.custom_column_name_8",
                "{$this->table}.custom_column_value_8",
                "{$this->table}.custom_column_name_9",
                "{$this->table}.custom_column_value_9",
                "{$this->table}.custom_column_name_10",
                "{$this->table}.custom_column_value_10",
            )->where('customer_request_id',$id);
        return $ds->first();
    }


    /**
     * Thêm khách hàng tiềm năng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_request_id;
    }

    /**
     * Thêm khách hàng tiềm năng
     *
     * @param array $data
     * @return mixed
     */
    public function getTotalByMonth($dayStart, $dayEnd)
    {
        $ds = $this
            ->select(
                DB::raw("COUNT(*) as Total"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%d') as days")
            )->whereDate("{$this->table}.created_at", ">=", $dayStart)
            ->whereDate("{$this->table}.created_at", "<=", $dayEnd)
            ->groupBy("days");
        return $ds->get();
    }

    /**
     * Thêm khách hàng tiềm năng
     *
     * @param array $data
     * @return mixed
     */
    public function getTotalStaffByMonth($dayStart, $dayEnd)
    {
        $ds = $this
            ->select(
                DB::raw("COUNT(*) as Total"),
                "sl.full_name as sale_name",
            )->join('cpo_customer_lead as cpo', function ($join) {
                $join->on('cpo.customer_lead_id', '=',  "{$this->table}.object_id");
                $join->where("{$this->table}.object_type", '=', 'customer_lead');
            })->join('staffs as sl', 'sl.staff_id', "=", "cpo.sale_id")
            ->whereDate("{$this->table}.created_at", ">=", $dayStart)
            ->whereDate("{$this->table}.created_at", "<=", $dayEnd)
            ->groupBy("sale_name");
        return $ds->get();
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