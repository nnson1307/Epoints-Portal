<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\Shift\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ManagerWorkTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_work';
    protected $primaryKey = 'manage_work_id';

    protected $fillable = [
        'manage_work_id',
        'manage_project_id',
        'manage_type_work_id',
        'manage_work_customer_type',
        'manage_work_code',
        'manage_work_title',
        'date_start',
        'date_end',
        'date_finish',
        'processor_id',
        'assignor_id',
        'time',
        'time_type',
        'progress',
        'customer_id',
        'customer_name',
        'description',
        'approve_id',
        'parent_id',
        'type_card_work',
        'priority',
        'manage_status_id',
        'repeat_type',
        'repeat_end',
        'repeat_end_time',
        'repeat_end_type',
        'repeat_end_full_time',
        'repeat_time',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_approve_id',
        'is_booking',
        'obj_id'
    ];

    const STARTED = 3;
    const FINISH = 6;
    const CANCEL = 7;

    public function getListWork($filters = [])
    {

        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);

        //        Lấy danh sách công việc
        $query = $this->select(
            "{$this->table}.manage_work_id",
            "{$this->table}.manage_project_id",
            "{$this->table}.manage_type_work_id",
            "{$this->table}.manage_work_title",
            "{$this->table}.date_start",
            "{$this->table}.date_end",
            "{$this->table}.date_finish",
            "{$this->table}.processor_id",
            "{$this->table}.assignor_id",
            "{$this->table}.time",
            "{$this->table}.time_type",
            "{$this->table}.progress",
            "{$this->table}.customer_id",
            "{$this->table}.description",
            "{$this->table}.approve_id",
            "{$this->table}.parent_id",
            "{$this->table}.type_card_work",
            "{$this->table}.priority",
            "{$this->table}.manage_status_id",
            "{$this->table}.repeat_type",
            "{$this->table}.repeat_end",
            "{$this->table}.repeat_end_time",
            "{$this->table}.repeat_end_type",
            "{$this->table}.repeat_end_full_time",
            "{$this->table}.repeat_time",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            'p1.full_name as created_name',
            'p2.full_name as updated_name',
            'p3.full_name as approve_name',
            //            'c1.full_name as customer_name',
            DB::raw("IF({$this->table}.manage_work_customer_type = 'lead', lead.full_name,IF({$this->table}.manage_work_customer_type = 'deal',deal.deal_name,c1.full_name)) as customer_name"),
            "type_work.manage_type_work_name",
            "type_work.manage_type_work_icon",
            "status.manage_status_name",
            "staffs.full_name as processor_full_name",
            'staffs.staff_avatar as processor_avatar',
            'manage_status_config.is_edit',
            'manage_status_config.is_deleted',
            'manage_status_config.manage_color_code',
            'manage_project_name'
        )
            ->leftJoin('manage_project as mp', 'mp.manage_project_id', '=', "{$this->table}.manage_project_id")
            ->leftJoin("manage_type_work as type_work", "type_work.manage_type_work_id", '=', "{$this->table}.manage_type_work_id")
            ->leftJoin("manage_status as status", "status.manage_status_id", '=', "{$this->table}.manage_status_id")

            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->join('staffs', 'staffs.staff_id', '=', $this->table . '.processor_id')

            ->leftJoin('customers as c1', 'c1.customer_id', '=', "{$this->table}.customer_id")
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', '=', "{$this->table}.customer_id")
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', '=', "{$this->table}.customer_id")
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->orderBy($this->primaryKey, 'desc');

        if (isset($filters["staff_id"]) && $filters["staff_id"] != "") {
            $query->where("{$this->table}.processor_id", $filters["staff_id"]);

        }

        // filter ngày tạo
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);

            $query->where(function ($query) use ($arr_filter) {
                $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
                $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 23:59:59");
                $query->whereBetween($this->table . ".created_at", [$startTime, $endTime]);
                $query->orWhereBetween($this->table . '.date_start', [$startTime, $endTime]);
            });
        }

        $query->groupBy("{$this->table}.manage_work_id");

        $user = Auth::user();

//        $query = $this->getPermission($query);

        if (isset($filters['page']) && $filters['page'] == 'all') {
            return $query->get();
        }

        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}
