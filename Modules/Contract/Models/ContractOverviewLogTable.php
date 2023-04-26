<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/24/2021
 * Time: 4:35 PM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractOverviewLogTable extends Model
{
    protected $table = "contract_overview_logs";
    protected $primaryKey = "contract_overview_log_id";
    protected $fillable = [
        "contract_overview_log_id",
        "contract_id",
        "contract_overview_type",
        "effective_date",
        "performer_by",
        "total_amount",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

    /**
     * lưu lại log hợp đồng khi thoả điều kiện trang thái đang thực hiện và có ngày hiệu lực
     *
     * @param $data
     * @return mixed
     */
    public function createDataLog($data)
    {
        return $this->create($data)->contract_overview_log_id;
    }

    /**
     * kiểm tra có tồn tại log loại này chưa?
     *
     * @param $contractId
     * @param $type
     * @return mixed
     */
    public function checkExistsLog($contractId, $type)
    {
        return $this->where("contract_id", $contractId)->where("contract_overview_type" , $type)->first();
    }

    public function getReportOverview($filter)
    {
        $ds = $this->select(
            DB::raw("COUNT(IF({$this->table}.contract_overview_type = 'new', 1, null)) as total_new"),
            DB::raw("COUNT(IF({$this->table}.contract_overview_type = 'renew', 1, null)) as total_renew"),
            DB::raw("COUNT(IF({$this->table}.contract_overview_type = 'recare', 1, null)) as total_recare"),
            DB::raw("SUM({$this->table}.total_amount) as total_date_amount"),
            DB::raw("DATE_FORMAT({$this->table}.effective_date, '%d/%m/%Y') as created_group")
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.performer_by");
        if(isset($filter['time']) && $filter['time'] != ''){
            $arrTime = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $arrTime[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $arrTime[1])->format('Y-m-d 23:59:59');
            $ds->whereBetween("{$this->table}.effective_date", [$startTime, $endTime]);
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
        $ds->groupBy(DB::raw("DATE_FORMAT({$this->table}.effective_date, '%d/%m/%Y')"));
        return $ds->get()->toArray();
    }
}