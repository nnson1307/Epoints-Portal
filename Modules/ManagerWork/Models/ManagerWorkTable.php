<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;

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
        'manage_project_phase_id',
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
        'obj_id',
        'branch_id',
        'create_object_type',
        'create_object_id',
        'is_deleted'
    ];

    const STARTED = 3;
    const FINISH = 6;
    const CANCEL = 7;
    const IS_DELETED = 1;
    const DATE = 'd';
    const TIME = 'h';

    public function staff_created()
    {
        return $this->belongsTo('Modules\ManagerWork\Models\StaffTable', 'created_by', 'staff_id');
    }
    public function processor()
    {
        return $this->belongsTo('Modules\ManagerWork\Models\StaffTable', 'processor_id', 'staff_id');
    }

    public function workSupport()
    {
        return $this->hasMany('Modules\ManagerWork\Models\ManageWorkSupportTable', 'manage_work_id', 'manage_work_id')->select('staff_id');
    }
    public function workSupportListAvatar()
    {
        return $this->hasMany('Modules\ManagerWork\Models\ManageWorkSupportTable', 'manage_work_id', 'manage_work_id')
            ->leftJoin("staffs as staff", "staff.staff_id", '=', "manage_work_support.staff_id")->select('manage_work_support.*', 'staff.full_name as full_name', 'staff.staff_avatar as staff_avatar');
    }
    public function countComment()
    {
        return $this->hasMany('Modules\ManagerWork\Models\ManagerCommentTable', 'manage_work_id', 'manage_work_id')
            ->leftJoin("staffs as staff", "staff.staff_id", '=', "manage_comment.staff_id")->select('manage_comment.*', 'staff.full_name as full_name', 'staff.staff_avatar as staff_avatar');;
    }
    public function workTag()
    {
        return $this->hasMany('Modules\ManagerWork\Models\ManageWorkTagTable', 'manage_work_id', 'manage_work_id')->select('manage_tag_id');
    }
    public function repeatTime()
    {
        return $this->hasMany('Modules\ManagerWork\Models\ManageRepeatTimeTable', 'manage_work_id', 'manage_work_id')->select('time');
    }

    public function remind()
    {
        return $this->hasMany('Modules\ManagerWork\Models\ManageRedmindTable', 'manage_work_id', 'manage_work_id')
            ->leftJoin("staffs as staff", "staff.staff_id", '=', "manage_remind.staff_id")->select('manage_remind.*', 'staff.full_name as full_name');
    }

    protected function _getList(&$filters = [])
    {
        //        Lấy danh sách công việc
        if (!isset($filters['report_view'])) {
            $query = $this->select(
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.manage_type_work_id",
                "{$this->table}.manage_work_title",
                "{$this->table}.manage_work_code",
                "{$this->table}.date_start",
                "{$this->table}.date_end",
                "{$this->table}.date_finish",
                "{$this->table}.processor_id",
                "{$this->table}.assignor_id",
                "{$this->table}.time",
                "{$this->table}.time_type",
                "{$this->table}.progress",
                "{$this->table}.customer_id",
                "{$this->table}.customer_name",
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
                "{$this->table}.branch_id",
                "type_work.manage_type_work_name",
                "type_work.manage_type_work_icon",
                "manage_project.manage_project_name",
                "processor.full_name as processor_full_name",
                'processor.staff_avatar as processor_avatar',
                "status.manage_status_name",
                "status.manage_color_code",
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job"),
                "{$this->table}.create_object_type"
            )
                ->leftJoin("manage_type_work as type_work", "type_work.manage_type_work_id", '=', "{$this->table}.manage_type_work_id")
                ->leftJoin("manage_status as status", "status.manage_status_id", '=', "{$this->table}.manage_status_id")
                ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
                ->leftJoin('manage_project', 'manage_project.manage_project_id', '=', "{$this->table}.manage_project_id")
                ->leftJoin('staffs as processor', 'staffs.staff_id', $this->table . '.processor_id')
                ->orderBy($this->primaryKey, 'desc');

            // filters tên + mô tả
            if (isset($filters["search"]) != "") {
                $search = $filters["search"];
                $query->where("{$this->table}.manage_project_id", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.manage_work_title", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.manage_work_code", "like", "%" . $search . "%");
            }
            // filters nguoi tao
            if (isset($filters["created_by"]) != "") {
                $query->where("{$this->table}.created_by", $filters["created_by"]);
            }
            // filters nguoi phu trach
            if (isset($filters["processor_id"]) != "") {
                $query->where("{$this->table}.processor_id", $filters["processor_id"]);
            }

            // filter ngày tạo
            if (isset($filters["created_at"]) != "") {
                $arr_filter = explode(" - ", $filters["created_at"]);
                $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
                $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
                $query->whereDate("{$this->table}.created_at", ">=", $startTime);
                $query->whereDate("{$this->table}.created_at", "<=", $endTime);
            }
            return $query;
        } else if(isset($filters['report_view'])) {
            //            Lấy danh sách công việc cho báo cáo

            $oSelect = $this
                ->select(
                    'staffs.full_name',
                    'staffs.staff_id',
                    DB::raw('COUNT(manage_work.manage_work_id) as total_process'),
                    DB::raw('SUM(IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.manage_status_id IN (2,3,5,6), ABS((DATE_FORMAT(TIMEDIFF(manage_work.date_finish,manage_work.date_end),"%H")*60 + DATE_FORMAT(TIMEDIFF(manage_work.date_finish,manage_work.date_end),"%i"))),0)) as total_time_work'),
                    DB::raw('SUM(IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish < manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0)) as total_completed_schedule'),
                    DB::raw('SUM(IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish > manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0)) as total_completed_overdue'),
                    DB::raw('SUM(IF(manage_work.manage_status_id NOT IN (6,7), 1 ,0)) as total_not_completed'),
                    DB::raw('SUM(IF(manage_work.manage_status_id NOT IN (6,7) AND NOW() > manage_work.date_end, 1 ,0)) as total_overdue')
                )
                ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id');

            if (isset($filters['dateSelect'])) {
                $date = explode(' - ', $filters['dateSelect']);
                $start = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d 00:00:00');
                $end = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d 23:59:59');

                //                $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                //                    $sql->whereBetween($this->table . '.date_start', [$start, $end])
                //                        ->orWhereBetween($this->table . '.date_end', [$start, $end]);
                //                });

                $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                    $sql
                        ->whereBetween('manage_work.date_start', [$start, $end])
                        ->where('manage_work.date_end', '>=', Carbon::now())
                        ->where('manage_work.date_start', '<=', $end)
                        ->where('manage_work.date_end', '>=', $end)
                        ->where(function ($sql1) use ($start, $end) {
                            $sql1
                                ->orWhere(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->whereNull('manage_work.date_start')
                                        ->where('manage_work.created_at', '>=', $end);
                                })
                                ->orWhere(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->whereNotNull('manage_work.date_start')
                                        ->where('manage_work.date_start', '>=', $end);
                                });
                        })
                        ->orWhere(function ($sql1) use ($start, $end) {
                            $sql1
                                //                            ->where('manage_work.date_start','<=',$start)
                                ->where(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->orWhere(function ($sql3) use ($start) {
                                            $sql3
                                                ->whereNull('manage_work.date_start');
                                        })
                                        ->orWhere(function ($sql3) use ($start, $end) {
                                            $sql3
                                                ->whereNotNull('manage_work.date_start')
                                                ->where('manage_work.date_start', '>=', $start);
                                        });
                                })
                                ->where('manage_work.date_end', '>=', $start);
                        })
                        ->orWhere(function ($sql1) use ($end) {
                            $sql1
                                ->where('manage_work.date_start', '<=', $end)
                                ->where('manage_work.date_end', '>=', $end);
                        });
                });
            }

            if (isset($filters['branch_id'])) {
                $oSelect = $oSelect->where('staffs.branch_id', $filters['branch_id']);
            }

            if (isset($filters['department_id'])) {
                $oSelect = $oSelect->where('staffs.department_id', $filters['department_id']);
            }

            if (isset($filters['staff_id'])) {
                $oSelect = $oSelect->where('staffs.staff_id', $filters['staff_id']);
            }

            if (isset($filters['sort_key'])) {
                $oSelect = $oSelect->orderBy($filters['sort_key'], $filters['sort_type']);
            }

            unset($filters['sort_key']);
            unset($filters['sort_type']);
            unset($filters['dateSelect']);
            unset($filters['branch_id']);
            unset($filters['department_id']);
            unset($filters['report_view']);
            unset($filters['processor_id']);
            unset($filters['staff_id']);

            $oSelect = $this->getPermission($oSelect);

            return $oSelect->groupBy($this->table . '.processor_id');
        }
    }

    /**
     * Lấy tổng công việc theo trạng thái cho từng nhân viên
     * @param $filters
     * @return mixed
     */
    public function getTotalStatusOfStaff($filters){
        $oSelect = $this
            ->join('manage_status_config','manage_status_config.manage_status_id',$this->table.'.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->where('manage_status_config.is_active',1);

        if (isset($filters['dateSelect'])) {
            $date = explode(' - ', $filters['dateSelect']);
            $start = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d 23:59:59');

            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql
                    ->whereBetween('manage_work.date_start',[$start,$end])
                    ->where('manage_work.date_end','>=',Carbon::now())
                    ->where('manage_work.date_start','<=',$end)
                    ->where('manage_work.date_end','>=',$end)
                    ->where(function ($sql1) use ($start,$end){
                        $sql1
                            ->orWhere(function ($sql2) use ($start,$end){
                                $sql2
                                    ->whereNull('manage_work.date_start')
                                    ->where('manage_work.created_at','>=',$end);
                            })
                            ->orWhere(function ($sql2) use ($start,$end){
                                $sql2
                                    ->whereNotNull('manage_work.date_start')
                                    ->where('manage_work.date_start','>=',$end);
                            });
                    })
                    ->orWhere(function ($sql1) use ($start,$end){
                        $sql1
//                            ->where('manage_work.date_start','<=',$start)
                            ->where(function ($sql2) use($start,$end){
                                $sql2
                                    ->orWhere(function ($sql3) use ($start){
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start,$end){
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start','>=',$start);
                                    });
                            })
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });
            });
        }

        if (isset($filters['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $filters['branch_id']);
        }

        if (isset($filters['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $filters['department_id']);
        }

        if (isset($filters['staff_id'])) {
            $oSelect = $oSelect->where('staffs.staff_id', $filters['staff_id']);
        }

        if (isset($filters['list_staff_process_id'])) {
            $oSelect = $oSelect->whereIn('staffs.staff_id', $filters['list_staff_process_id']);
        }

//            Kiểm tra param danh sách nhóm trạng thái bị loại ra
        if (isset($filters['array_not_group_status'])) {
            $oSelect = $oSelect->whereNotIn('manage_status_config.manage_status_group_config_id', $filters['array_not_group_status']);
        }
        $oSelect = $this->getPermission($oSelect);

        return $oSelect
            ->get();
    }

    public function getWorkByReport($filters = []){
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
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
                "{$this->table}.customer_name",
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
                "{$this->table}.branch_id",
                "type_work.manage_type_work_name",
                "type_work.manage_type_work_icon",
                "staffs.full_name as processor_full_name",
                'staffs.staff_avatar as processor_avatar',
                "status.manage_status_name",
                DB::raw('IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish < manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0) as work_completed_schedule'),
                DB::raw('IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish > manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0) as work_completed_overdue'),
                DB::raw('IF(manage_work.manage_status_id NOT IN (6,7), 1 ,0) as work_not_completed'),
                DB::raw('IF(manage_work.manage_status_id NOT IN (6,7) AND NOW() > manage_work.date_end, 1 ,0) as work_overdue')
            )
            ->leftJoin("manage_type_work as type_work", "type_work.manage_type_work_id", '=', "{$this->table}.manage_type_work_id")
            ->leftJoin("manage_status as status", "status.manage_status_id", '=', "{$this->table}.manage_status_id")
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id');

        if (isset($filters['dateSelect'])) {
            $date = explode(' - ', $filters['dateSelect']);
            $start = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d 23:59:59');

            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql
                    ->whereBetween('manage_work.date_start',[$start,$end])
                    ->where('manage_work.date_end','>=',Carbon::now())
                    ->where('manage_work.date_start','<=',$end)
                    ->where('manage_work.date_end','>=',$end)
                    ->where(function ($sql1) use ($start,$end){
                        $sql1
                            ->orWhere(function ($sql2) use ($start, $end) {
                                $sql2
                                    ->whereNull('manage_work.date_start')
                                    ->where('manage_work.created_at', '>=', $end);
                            })
                            ->orWhere(function ($sql2) use ($start, $end) {
                                $sql2
                                    ->whereNotNull('manage_work.date_start')
                                    ->where('manage_work.date_start', '>=', $end);
                            });
                    })
                    ->orWhere(function ($sql1) use ($start, $end) {
                        $sql1
                            //                            ->where('manage_work.date_start','<=',$start)
                            ->where(function ($sql2) use ($start, $end) {
                                $sql2
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start, $end) {
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start', '>=', $start);
                                    });
                            })
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        if (isset($filters['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $filters['branch_id']);
        }

        if (isset($filters['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $filters['department_id']);
        }

        if (isset($filters['staff_id'])) {
            $oSelect = $oSelect->where('staffs.staff_id', $filters['staff_id']);
        }

        //        if (isset($filters['sort_key'])) {
        //            $oSelect = $oSelect->orderBy($filters['sort_key'], $filters['sort_type']);
        //        }

        if (isset($filters['type_work'])) {
            if ($filters['type_work'] == 'finish') {
                $oSelect = $oSelect->where(DB::raw('IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish < manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0)'), 1);
            }
            if ($filters['type_work'] == 'finish_overdue') {
                $oSelect = $oSelect->where(DB::raw('IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish > manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0)'), 1);
            }
            if ($filters['type_work'] == 'unfinish') {
                $oSelect = $oSelect->where(DB::raw('IF(manage_work.manage_status_id NOT IN (6,7), 1 ,0)'), 1);
            }
            if ($filters['type_work'] == 'overdue') {
                $oSelect = $oSelect->where(DB::raw('IF(manage_work.manage_status_id NOT IN (6,7) AND NOW() > manage_work.date_end, 1 ,0)'), 1);
            }
        }

        if (isset($filters['type_card_work'])) {
            $oSelect = $oSelect->where('manage_work.type_card_work', $filters['type_card_work']);
        }
//
        if (isset($filters['manage_status_id'])) {
            $oSelect = $oSelect->where($this->table.'.manage_status_id', $filters['manage_status_id']);
        }

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    public function getListWork($filters = [])
    {
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        //        Lấy danh sách công việc
        $query = $this->select(
            "{$this->table}.manage_work_id",
            "parent_work.manage_work_id as manage_parent_work_id",
            "{$this->table}.manage_project_id",
            "{$this->table}.manage_type_work_id",
            "{$this->table}.manage_work_title",
            "{$this->table}.manage_work_code",
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
            "{$this->table}.branch_id",
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
            'manage_project_name',
            'parent_work.manage_work_code as manage_work_parent_code',
            DB::raw("IFNULL({$this->table}.parent_id,0) as manage_work_parent_name"),
            "{$this->table}.create_object_type"
//            Db::raw("manage_work.parent_id as manage_work_parent_name")
            // DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
        )
            ->leftJoin('manage_project as mp', 'mp.manage_project_id', '=', "{$this->table}.manage_project_id")
            ->leftJoin("manage_type_work as type_work", "type_work.manage_type_work_id", '=', "{$this->table}.manage_type_work_id")
            ->leftJoin("manage_status as status", "status.manage_status_id", '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent_work','parent_work.manage_work_id',$this->table.'.parent_id')
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->leftJoin('manage_work_support as p4', function ($sql) {
                $sql->on('p4.manage_work_id', '=', $this->table . '.manage_work_id');
                //                    ->on('p4.staff_id', '=', DB::raw(Auth::id()));
            })
            ->leftJoin('staffs', 'staffs.staff_id', '=', $this->table . '.processor_id')

            ->leftJoin('customers as c1', 'c1.customer_id', '=', "{$this->table}.customer_id")
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', '=', "{$this->table}.customer_id")
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', '=', "{$this->table}.customer_id")
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC');

        // search// filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("{$this->table}.manage_project_id", "like", "%" . $search . "%")
                ->orWhere("{$this->table}.manage_work_title", "like", "%" . $search . "%")
                ->orWhere("{$this->table}.manage_work_code", "like", "%" . $search . "%");
        }

        // filters for me assign_by
        if (isset($filters["assign_by"]) && $filters["assign_by"] != "" && $filters["assign_by"] != "all") {
            /*
                1 Tôi hỗ trợ
                2 Tôi tạo
                3 Cần duyệt: filter danh sách công việc ở trạng thái "Đã thực hiện"
                mà người duyệt là tôi và được đánh dấu cần phê duyệt
                4 Giao cho tôi
                5 Tất cả
            */

            $user_id = \Auth::id();
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 1) {
                $list_support = \DB::table('manage_work_support')->where('staff_id', $user_id)->get()->pluck("manage_work_id")->toArray();
                $list_support = array_values($list_support);
                $query->whereIn("{$this->table}.manage_work_id", $list_support);
            }
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 2) {
                $query->where("{$this->table}.created_by", $user_id);
            }
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 3) {
                $query->where("{$this->table}.manage_status_id", 2)
                    ->where("{$this->table}.approve_id", $user_id)
                    ->where("{$this->table}.is_approve_id", 1);
            }
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 4) {
                $query->where("{$this->table}.processor_id", $user_id);
            }

            if (isset($filters["assign_by"]) && $filters["assign_by"] == 6) {
                $query->where("{$this->table}.assignor_id", $user_id);
            }
            // filters tên + mô tả
            if (isset($filters["search"]) != "") {
                $search = $filters["search"];
                $query->where("{$this->table}.manage_project_id", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.manage_work_title", "like", "%" . $search . "%");
            }
            // filters nguoi tao
            if (isset($filters["$this->table}.created_by"]) != "") {
                $query->where("{$this->table}.created_by", $filters["created_by"]);
            }

            if (isset($filters["manage_work_customer_type"])) {
                if ($filters["manage_work_customer_type"] == 'lead') {
                } else if ($filters["manage_work_customer_type"] == 'deal') {
                } else if ($filters["manage_work_customer_type"] == 'customer') {
                }
            }

            // filters nguoi phu trach
            if (isset($filters["$this->table}.processor_id"]) != "") {
                $query->where("{$this->table}.processor_id", $filters["processor_id"]);
            }
        }

        // filter ngày tạo
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 23:59:59");
            $query->whereBetween($this->table . ".created_at", [$startTime, $endTime]);
        }

        //date_end

        if (isset($filters["date_start"]) && isset($filters["date_end"])) {
            $start = Carbon::createFromFormat('d/m/Y', $filters["date_start"])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $filters["date_end"])->format('Y-m-d 23:59:59');

            if ($end < $start) {
                $query = $query->where($this->table . '.manage_work_id', 0);
                return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
            }

            if (isset($filters['type-page'])) {
                $query = $query->where(function ($sql) use ($start, $end) {
                    $sql
                        ->whereBetween('manage_work.date_start', [$start, $end])
                        ->where('manage_work.date_end', '>=', Carbon::now())
                        ->where('manage_work.date_start', '<=', $end)
                        ->where('manage_work.date_end', '>=', $end)
                        ->where(function ($sql1) use ($start, $end) {
                            $sql1
                                ->orWhere(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->whereNull('manage_work.date_start')
                                        ->where('manage_work.created_at', '>=', $end);
                                })
                                ->orWhere(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->whereNotNull('manage_work.date_start')
                                        ->where('manage_work.date_start', '>=', $end);
                                });
                        })
                        ->orWhere(function ($sql1) use ($start, $end) {
                            $sql1
                                ->where(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->orWhere(function ($sql3) use ($start) {
                                            $sql3
                                                ->whereNull('manage_work.date_start');
                                        })
                                        ->orWhere(function ($sql3) use ($start, $end) {
                                            $sql3
                                                ->whereNotNull('manage_work.date_start')
                                                ->where('manage_work.date_start', '>=', $start);
                                        });
                                })
                                ->where('manage_work.date_end', '>=', $start);
                        })
                        ->orWhere(function ($sql1) use ($end) {
                            $sql1
                                ->where('manage_work.date_start', '<=', $end)
                                ->where('manage_work.date_end', '>=', $end);
                        });
                });
            } else {
                if (isset($filters["date_start"])) {
                    $start = Carbon::createFromFormat('d/m/Y', $filters['date_start'])->format('Y-m-d 00:00:00');
                    $query = $query
                        //                        ->where(function($ds) use ($start){
                        //                            $ds->where(function ($sql) use ($start){
                        //                                $sql->whereNull($this->table.'.date_start')
                        //                                    ->whereDate($this->table.'.created_at','<=',$start);
                        //                            })
                        //                                ->orWhere(function ($sql) use ($start){
                        //                                    $sql->whereNotNull($this->table.'.date_start')
                        //                                        ->whereDate($this->table.'.date_start','<=',$start);
                        //                                });
                        //                        })
                        ->where(function ($ds) use ($start) {
                            $ds->where(function ($sql) use ($start) {
                                $sql->whereNull($this->table . '.date_start')
                                    ->whereDate($this->table . '.created_at', '<=', $start)
                                    ->orWhereDate($this->table . '.created_at', '>=', $start);
                            })
                                ->orWhere(function ($sql) use ($start) {
                                    $sql->whereNotNull($this->table . '.date_start')
                                        ->whereDate($this->table . '.date_start', '<=', $start)
                                        ->orWhereDate($this->table . '.date_start', '>=', $start);
                                });
                        })
                        ->whereDate($this->table . '.date_end', '>=', $start);
                }

                if (isset($filters["date_end"])) {
                    $end = Carbon::createFromFormat('d/m/Y', $filters['date_end'])->format('Y-m-d 23:59:59');

                    if ($end > Carbon::now()) {
                        $query = $query
                            ->where(function ($ds) use ($end) {
                                $ds
                                    ->where(function ($qs) use ($end) {
                                        $qs->whereNull($this->table . '.date_start')
                                            ->whereDate($this->table . '.created_at', '<=', Carbon::now())
                                            ->orWhereDate($this->table . '.created_at', '>=', Carbon::now());
                                    })
                                    ->orWhere(function ($qs) use ($end) {
                                        $qs->wherenotNull($this->table . '.date_start')
                                            ->whereDate($this->table . '.date_start', '<=', Carbon::now())
                                            ->orWhereDate($this->table . '.date_start', '>=', Carbon::now());
                                    });
                            });
                    }

                    $query = $query
                        ->where(function ($ds) use ($end) {
                            $ds->whereDate($this->table . '.date_end', '>=', $end)
                                ->orWhere(function ($ds) use ($end) {
                                    $ds->whereDate($this->table . '.date_end', '<=', $end);
                                });
                        })
                        ->whereDate($this->table . '.date_start', '<=', $end);
                }
            }
        } else {
            if (isset($filters["date_start"])) {
                $startTime = Carbon::createFromFormat("d/m/Y", $filters["date_start"])->format("Y-m-d 00:00:00");
                $query = $query
                    ->where(function ($ds) use ($startTime) {
                        $ds->where($this->table . ".date_start", '>=', $startTime)
                            ->orWhere($this->table . ".created_at", '>=', $startTime);
                    });
            }

            if (isset($filters["date_end"])) {
                if (isset($filters['type-search'])) {
                    $endTime = Carbon::createFromFormat("d/m/Y", $filters["date_end"])->format("Y-m-d H:i:s");
                } else {
                    $endTime = Carbon::createFromFormat("d/m/Y", $filters["date_end"])->format("Y-m-d 23:59:59");
                }

                $query = $query->where($this->table . '.date_end', '<=', $endTime);
            }
        }

        //date_overtime
        if (isset($filters["date_overtime"]) && $filters["date_overtime"] != "") {
            $arr_filter = explode(" - ", $filters["date_overtime"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $current_date = Carbon::now()->format("Y-m-d 00:00:00");
            // ngày quá hạn đã hoàn thành
            // ngày quá hạn chưa hoàn thành
            $query->whereDate("{$this->table}.date_end", "<", $current_date);
            $query->whereBetween("{$this->table}.date_end", [$startTime, $endTime]);
        }

        if (isset($filters['is_parent'])) {
            if ($filters['is_parent'] == 1) {
                $query = $query->whereNotNull($this->table . ".parent_id");
            } elseif ($filters['is_parent'] == 2) {
                $query = $query->whereNull($this->table . ".parent_id");
            }
        }

        if (isset($filters["manage_tag_id"]) && $filters["manage_tag_id"] != "" && $filters["manage_tag_id"] != "all") {
            $query->join('manage_work_tag as mwt', 'mwt.manage_work_id', "{$this->table}.manage_work_id")
                ->where('mwt.manage_tag_id', $filters["manage_tag_id"]);
        }

        if (isset($filters["processor_id"]) && $filters["processor_id"] != "" && $filters["processor_id"] != "all") {
            $query->where("{$this->table}.processor_id", $filters["processor_id"]);
        }
        if (isset($filters["manage_work_support_id"]) && $filters["manage_work_support_id"] != "" && $filters["manage_work_support_id"] != "all") {
            $query->join('manage_work_support as mwp', 'mwp.manage_work_id', "{$this->table}.manage_work_id")
                ->where('mwp.staff_id', $filters["manage_work_support_id"]);
        }

        if (isset($filters["repeat_type"]) && $filters["repeat_type"] != "" && $filters["repeat_type"] != "all") {
            if($filters["repeat_type"] == 'none'){
                $query->whereNull("{$this->table}.repeat_type");
            } else {
                $query->where("{$this->table}.repeat_type",$filters["repeat_type"]);
            }
        }

        if (isset($filters["created_by"]) && $filters["created_by"] != "" && $filters["created_by"] != "all") {
            $query->where("{$this->table}.created_by", $filters["created_by"]);
        }

        if (isset($filters["approve_id"]) && $filters["approve_id"] != "" && $filters["approve_id"] != "all") {
            $query->where("{$this->table}.approve_id", $filters["approve_id"]);
        }

        if (isset($filters["updated_by"]) && $filters["updated_by"] != "" && $filters["updated_by"] != "all") {
            $query->where("{$this->table}.updated_by", $filters["updated_by"]);
        }

        if (isset($filters["type_card_work"]) && $filters["type_card_work"] != "" && $filters["type_card_work"] != "all") {
            $query->where("{$this->table}.type_card_work", $filters["type_card_work"]);
        }

        if (isset($filters["department_id"]) && $filters["department_id"] != "" && $filters["department_id"] != "all") {
            $departmentId = $filters["department_id"];

            $query->where(function ($ds) use ($departmentId) {
                $ds
                    ->where("p1.department_id", $departmentId)
                    ->orWhere("p2.department_id", $departmentId)
                    ->orWhere("p3.department_id", $departmentId)
                    ->orWhere("staffs.department_id", $departmentId);
            });
        }

        if (isset($filters["manage_type_work_id"]) && $filters["manage_type_work_id"] != "" && $filters["manage_type_work_id"] != "all") {
            $query->where("{$this->table}.manage_type_work_id", $filters["manage_type_work_id"]);
        }

        if (isset($filters["manage_project_id"]) && $filters["manage_project_id"] != "" && $filters["manage_project_id"] != "all") {
            $query->where("{$this->table}.manage_project_id", $filters["manage_project_id"]);
        }

        if (isset($filters["priority"]) && $filters["priority"] != "" && $filters["priority"] != "all") {
            $query->where("{$this->table}.priority", $filters["priority"]);
        }

        if (isset($filters["date_finish"]) && $filters["date_finish"] != "") {
            $arr_filter = explode(" - ", $filters["date_finish"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.date_finish", ">=", $startTime);
            $query->whereDate("{$this->table}.date_finish", "<=", $endTime);
        }

        if (isset($filters["updated_at"]) && $filters["updated_at"] != "") {
            $arr_filter = explode(" - ", $filters["updated_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate($this->table . ".updated_at", ">=", $startTime);
            $query->whereDate($this->table . ".updated_at", "<=", $endTime);
        }

        if (isset($filters["customer_id"]) && $filters["customer_id"] != "" && $filters["customer_id"] != "all") {
            $query->where("{$this->table}.customer_id", $filters["customer_id"]);
        }

        //Filter theo người thực hiện
        if (isset($filters["processor_id"]) && $filters["processor_id"] != "") {
            $query->where("{$this->table}.processor_id", $filters["processor_id"]);
        }

        //Filter theo chi nhánh người thực hiện
        if (isset($filters["branch_id"]) && $filters["branch_id"] != "") {
            $query->where("staffs.branch_id", $filters["branch_id"]);
        }

        //manage_status_id
        if (isset($filters["manage_status_id"]) && $filters["manage_status_id"] != "" && $filters["manage_status_id"] != "all") {
            //            $query->where("{$this->table}.manage_status_id", $filters["manage_status_id"]);
            $query = $query->whereIn("{$this->table}.manage_status_id", $filters["manage_status_id"]);
        }

        if(isset($filters['work_overdue_search'])){
//            Lấy danh sách công việc quá hạn không nằm trong nhóm hoàn thành
            if ($filters['work_overdue_search'] == 2){
                $query = $query
                    ->where('manage_status_config.manage_status_group_config_id',3)
                    ->whereRaw($this->table.".date_finish > ".$this->table.".date_end");
            } else if ($filters['work_overdue_search'] == 3){
                $query = $query
                    ->whereNotIn('manage_status_config.manage_status_group_config_id',[3,4])
                    ->where($this->table.".date_end",'<',Carbon::now());
            }

            unset($filters['work_overdue_search']);
        }

        //Filter nguồn công việc
        if(isset($filters['create_object_type']) && $filters['create_object_type'] != null) {
            switch ($filters['create_object_type']) {
                case 'live':
                    $query->whereNull("{$this->table}.create_object_type");
                    break;
                case 'shift':
                    $query->where("{$this->table}.create_object_type", $filters['create_object_type']);
                    break;
                case 'ticket':
                    $query->where("{$this->table}.create_object_type", $filters['create_object_type']);
                    break;
            }

            unset($filters['create_object_type']);
        }


        $query->groupBy("{$this->table}.manage_work_id");

        $user = Auth::user();

        //        if (!isset($filters['processor_id']) && !isset($filters['support_id']) && !isset($filters['assignor_id'])) {
        //            $staffId = Auth::id();
        ////            $query = $query
        ////                ->where(function ($sql) use ($staffId){
        ////                    $sql->where($this->table.'.processor_id',$staffId)
        ////                        ->orWhere($this->table.'.assignor_id',$staffId)
        ////                        ->orWhere($this->table.'.approve_id',$staffId)
        ////                        ->orWhere('p4.staff_id',$staffId);
        ////                });
        //        } else {
        //            $query = $this->getPermission($query);
        //        }

        $query = $this->getPermission($query);

        if (isset($filters['page']) && $filters['page'] == 'all') {
            return $query->get();
        }

        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getListWorkAll($filters = [])
    {
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        //        Lấy danh sách công việc
        $query = $this->select(
            "{$this->table}.manage_work_id",
            "parent_work.manage_work_id as manage_parent_work_id",
            "{$this->table}.manage_project_id",
            "{$this->table}.manage_type_work_id",
            "{$this->table}.manage_work_title",
            "{$this->table}.manage_work_code",
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
            "{$this->table}.branch_id",
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
            'manage_project_name',
            'parent_work.manage_work_code as manage_work_parent_code',
            DB::raw("IFNULL({$this->table}.parent_id,0) as manage_work_parent_name")
//            Db::raw("manage_work.parent_id as manage_work_parent_name")
        // DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
        )
            ->leftJoin('manage_project as mp', 'mp.manage_project_id', '=', "{$this->table}.manage_project_id")
            ->leftJoin("manage_type_work as type_work", "type_work.manage_type_work_id", '=', "{$this->table}.manage_type_work_id")
            ->leftJoin("manage_status as status", "status.manage_status_id", '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent_work','parent_work.manage_work_id',$this->table.'.parent_id')
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->leftJoin('manage_work_support as p4', function ($sql) {
                $sql->on('p4.manage_work_id', '=', $this->table . '.manage_work_id');
                //                    ->on('p4.staff_id', '=', DB::raw(Auth::id()));
            })
            ->leftJoin('staffs', 'staffs.staff_id', '=', $this->table . '.processor_id')

            ->leftJoin('customers as c1', 'c1.customer_id', '=', "{$this->table}.customer_id")
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', '=', "{$this->table}.customer_id")
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', '=', "{$this->table}.customer_id")
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC');

        // search// filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where(function ($sql) use ($search){
                $sql->where("{$this->table}.manage_project_id", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.manage_work_title", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.manage_work_code", "like", "%" . $search . "%");
            });
        }

        // filters for me assign_by
        if (isset($filters["assign_by"]) && $filters["assign_by"] != "" && $filters["assign_by"] != "all") {
            /*
                1 Tôi hỗ trợ
                2 Tôi tạo
                3 Cần duyệt: filter danh sách công việc ở trạng thái "Đã thực hiện"
                mà người duyệt là tôi và được đánh dấu cần phê duyệt
                4 Giao cho tôi
                5 Tất cả
            */

            $user_id = \Auth::id();
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 1) {
                $list_support = \DB::table('manage_work_support')->where('staff_id', $user_id)->get()->pluck("manage_work_id")->toArray();
                $list_support = array_values($list_support);
                $query->whereIn("{$this->table}.manage_work_id", $list_support);
            }
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 2) {
                $query->where("{$this->table}.created_by", $user_id);
            }
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 3) {
                $query->where("{$this->table}.manage_status_id", 2)
                    ->where("{$this->table}.approve_id", $user_id)
                    ->where("{$this->table}.is_approve_id", 1);
            }
            if (isset($filters["assign_by"]) && $filters["assign_by"] == 4) {
                $query->where("{$this->table}.processor_id", $user_id);
            }

            if (isset($filters["assign_by"]) && $filters["assign_by"] == 6) {
                $query->where("{$this->table}.assignor_id", $user_id);
            }
            // filters tên + mô tả
            if (isset($filters["search"]) != "") {
                $search = $filters["search"];
                $query->where("{$this->table}.manage_project_id", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.manage_work_title", "like", "%" . $search . "%");
            }
            // filters nguoi tao
            if (isset($filters["$this->table}.created_by"]) != "") {
                $query->where("{$this->table}.created_by", $filters["created_by"]);
            }

            if (isset($filters["manage_work_customer_type"])) {
                if ($filters["manage_work_customer_type"] == 'lead') {
                } else if ($filters["manage_work_customer_type"] == 'deal') {
                } else if ($filters["manage_work_customer_type"] == 'customer') {
                }
            }

            // filters nguoi phu trach
            if (isset($filters["$this->table}.processor_id"]) != "") {
                $query->where("{$this->table}.processor_id", $filters["processor_id"]);
            }
        }

        // filter ngày tạo
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 23:59:59");
            $query->whereBetween($this->table . ".created_at", [$startTime, $endTime]);
        }

        //date_end

        if (isset($filters["date_start"]) && isset($filters["date_end"])) {
            $start = Carbon::createFromFormat('d/m/Y', $filters["date_start"])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $filters["date_end"])->format('Y-m-d 23:59:59');

            if ($end < $start) {
                $query = $query->where($this->table . '.manage_work_id', 0);
                return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
            }

            if (isset($filters['type-page'])) {
                $query = $query->where(function ($sql) use ($start, $end) {
                    $sql
                        ->whereBetween('manage_work.date_start', [$start, $end])
                        ->where('manage_work.date_end', '>=', Carbon::now())
                        ->where('manage_work.date_start', '<=', $end)
                        ->where('manage_work.date_end', '>=', $end)
                        ->where(function ($sql1) use ($start, $end) {
                            $sql1
                                ->orWhere(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->whereNull('manage_work.date_start')
                                        ->where('manage_work.created_at', '>=', $end);
                                })
                                ->orWhere(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->whereNotNull('manage_work.date_start')
                                        ->where('manage_work.date_start', '>=', $end);
                                });
                        })
                        ->orWhere(function ($sql1) use ($start, $end) {
                            $sql1
                                ->where(function ($sql2) use ($start, $end) {
                                    $sql2
                                        ->orWhere(function ($sql3) use ($start) {
                                            $sql3
                                                ->whereNull('manage_work.date_start');
                                        })
                                        ->orWhere(function ($sql3) use ($start, $end) {
                                            $sql3
                                                ->whereNotNull('manage_work.date_start')
                                                ->where('manage_work.date_start', '>=', $start);
                                        });
                                })
                                ->where('manage_work.date_end', '>=', $start);
                        })
                        ->orWhere(function ($sql1) use ($end) {
                            $sql1
                                ->where('manage_work.date_start', '<=', $end)
                                ->where('manage_work.date_end', '>=', $end);
                        });
                });
            } else {
                if (isset($filters["date_start"])) {
                    $start = Carbon::createFromFormat('d/m/Y', $filters['date_start'])->format('Y-m-d 00:00:00');
                    $query = $query
                        //                        ->where(function($ds) use ($start){
                        //                            $ds->where(function ($sql) use ($start){
                        //                                $sql->whereNull($this->table.'.date_start')
                        //                                    ->whereDate($this->table.'.created_at','<=',$start);
                        //                            })
                        //                                ->orWhere(function ($sql) use ($start){
                        //                                    $sql->whereNotNull($this->table.'.date_start')
                        //                                        ->whereDate($this->table.'.date_start','<=',$start);
                        //                                });
                        //                        })
                        ->where(function ($ds) use ($start) {
                            $ds->where(function ($sql) use ($start) {
                                $sql->whereNull($this->table . '.date_start')
                                    ->whereDate($this->table . '.created_at', '<=', $start)
                                    ->orWhereDate($this->table . '.created_at', '>=', $start);
                            })
                                ->orWhere(function ($sql) use ($start) {
                                    $sql->whereNotNull($this->table . '.date_start')
                                        ->whereDate($this->table . '.date_start', '<=', $start)
                                        ->orWhereDate($this->table . '.date_start', '>=', $start);
                                });
                        })
                        ->whereDate($this->table . '.date_end', '>=', $start);
                }

                if (isset($filters["date_end"])) {
                    $end = Carbon::createFromFormat('d/m/Y', $filters['date_end'])->format('Y-m-d 23:59:59');

                    if ($end > Carbon::now()) {
                        $query = $query
                            ->where(function ($ds) use ($end) {
                                $ds
                                    ->where(function ($qs) use ($end) {
                                        $qs->whereNull($this->table . '.date_start')
                                            ->whereDate($this->table . '.created_at', '<=', Carbon::now())
                                            ->orWhereDate($this->table . '.created_at', '>=', Carbon::now());
                                    })
                                    ->orWhere(function ($qs) use ($end) {
                                        $qs->wherenotNull($this->table . '.date_start')
                                            ->whereDate($this->table . '.date_start', '<=', Carbon::now())
                                            ->orWhereDate($this->table . '.date_start', '>=', Carbon::now());
                                    });
                            });
                    }

                    $query = $query
                        ->where(function ($ds) use ($end) {
                            $ds->whereDate($this->table . '.date_end', '>=', $end)
                                ->orWhere(function ($ds) use ($end) {
                                    $ds->whereDate($this->table . '.date_end', '<=', $end);
                                });
                        })
                        ->whereDate($this->table . '.date_start', '<=', $end);
                }
            }
        } else {
            if (isset($filters["date_start"])) {
                $startTime = Carbon::createFromFormat("d/m/Y", $filters["date_start"])->format("Y-m-d 00:00:00");
                $query = $query
                    ->where(function ($ds) use ($startTime) {
                        $ds->where($this->table . ".date_start", '>=', $startTime)
                            ->orWhere($this->table . ".created_at", '>=', $startTime);
                    });
            }

            if (isset($filters["date_end"])) {
                if (isset($filters['type-search'])) {
                    $endTime = Carbon::createFromFormat("d/m/Y", $filters["date_end"])->format("Y-m-d H:i:s");
                } else {
                    $endTime = Carbon::createFromFormat("d/m/Y", $filters["date_end"])->format("Y-m-d 23:59:59");
                }

                $query = $query->where($this->table . '.date_end', '<=', $endTime);
            }
        }

        //date_overtime
        if (isset($filters["date_overtime"]) && $filters["date_overtime"] != "") {
            $arr_filter = explode(" - ", $filters["date_overtime"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $current_date = Carbon::now()->format("Y-m-d 00:00:00");
            // ngày quá hạn đã hoàn thành
            // ngày quá hạn chưa hoàn thành
            $query->whereDate("{$this->table}.date_end", "<", $current_date);
            $query->whereBetween("{$this->table}.date_end", [$startTime, $endTime]);
        }

        if (isset($filters['is_parent'])) {
            if ($filters['is_parent'] == 1) {
                $query = $query->whereNotNull($this->table . ".parent_id");
            } elseif ($filters['is_parent'] == 2) {
                $query = $query->whereNull($this->table . ".parent_id");
            }
        }

        if (isset($filters["manage_tag_id"]) && $filters["manage_tag_id"] != "" && $filters["manage_tag_id"] != "all") {
            $query->join('manage_work_tag as mwt', 'mwt.manage_work_id', "{$this->table}.manage_work_id")
                ->where('mwt.manage_tag_id', $filters["manage_tag_id"]);
        }

        if (isset($filters["processor_id"]) && $filters["processor_id"] != "" && $filters["processor_id"] != "all") {
            $query->where("{$this->table}.processor_id", $filters["processor_id"]);
        }
        if (isset($filters["manage_work_support_id"]) && $filters["manage_work_support_id"] != "" && $filters["manage_work_support_id"] != "all") {
            $query->join('manage_work_support as mwp', 'mwp.manage_work_id', "{$this->table}.manage_work_id")
                ->where('mwp.staff_id', $filters["manage_work_support_id"]);
        }

        if (isset($filters["created_by"]) && $filters["created_by"] != "" && $filters["created_by"] != "all") {
            $query->where("{$this->table}.created_by", $filters["created_by"]);
        }

        if (isset($filters["approve_id"]) && $filters["approve_id"] != "" && $filters["approve_id"] != "all") {
            $query->where("{$this->table}.approve_id", $filters["approve_id"]);
        }

        if (isset($filters["updated_by"]) && $filters["updated_by"] != "" && $filters["updated_by"] != "all") {
            $query->where("{$this->table}.updated_by", $filters["updated_by"]);
        }

        if (isset($filters["type_card_work"]) && $filters["type_card_work"] != "" && $filters["type_card_work"] != "all") {
            $query->where("{$this->table}.type_card_work", $filters["type_card_work"]);
        }

        if (isset($filters["department_id"]) && $filters["department_id"] != "" && $filters["department_id"] != "all") {
            $departmentId = $filters["department_id"];

            $query->where(function ($ds) use ($departmentId) {
                $ds
//                    ->where("p1.department_id", $departmentId)
//                    ->orWhere("p2.department_id", $departmentId)
//                    ->orWhere("p3.department_id", $departmentId)
                    ->where("staffs.department_id", $departmentId);
            });
        }

        if (isset($filters["manage_type_work_id"]) && $filters["manage_type_work_id"] != "" && $filters["manage_type_work_id"] != "all") {
            $query->where("{$this->table}.manage_type_work_id", $filters["manage_type_work_id"]);
        }

        if (isset($filters["manage_project_id"]) && $filters["manage_project_id"] != "" && $filters["manage_project_id"] != "all") {
            $query->where("{$this->table}.manage_project_id", $filters["manage_project_id"]);
        }

        if (isset($filters["priority"]) && $filters["priority"] != "" && $filters["priority"] != "all") {
            $query->where("{$this->table}.priority", $filters["priority"]);
        }

        if (isset($filters["date_finish"]) && $filters["date_finish"] != "") {
            $arr_filter = explode(" - ", $filters["date_finish"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.date_finish", ">=", $startTime);
            $query->whereDate("{$this->table}.date_finish", "<=", $endTime);
        }

        if (isset($filters["updated_at"]) && $filters["updated_at"] != "") {
            $arr_filter = explode(" - ", $filters["updated_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate($this->table . ".updated_at", ">=", $startTime);
            $query->whereDate($this->table . ".updated_at", "<=", $endTime);
        }

        if (isset($filters["customer_id"]) && $filters["customer_id"] != "" && $filters["customer_id"] != "all") {
            $query->where("{$this->table}.customer_id", $filters["customer_id"]);
        }

        //Filter theo người thực hiện
        if (isset($filters["processor_id"]) && $filters["processor_id"] != "") {
            $query->where("{$this->table}.processor_id", $filters["processor_id"]);
        }

        //Filter theo chi nhánh người thực hiện
        if (isset($filters["branch_id"]) && $filters["branch_id"] != "") {
            $query->where("staffs.branch_id", $filters["branch_id"]);
        }

        //manage_status_id
        if (isset($filters["manage_status_id"]) && $filters["manage_status_id"] != "" && $filters["manage_status_id"] != "all") {
            //            $query->where("{$this->table}.manage_status_id", $filters["manage_status_id"]);
            $query = $query->whereIn("{$this->table}.manage_status_id", $filters["manage_status_id"]);
        }

        if(isset($filters['work_overdue_search'])){
//            Lấy danh sách công việc quá hạn không nằm trong nhóm hoàn thành
            if ($filters['work_overdue_search'] == 2){
                $query = $query
                    ->where('manage_status_config.manage_status_group_config_id',3)
                    ->whereRaw($this->table.".date_finish > ".$this->table.".date_end");
            } else if ($filters['work_overdue_search'] == 3){
                $query = $query
                    ->whereNotIn('manage_status_config.manage_status_group_config_id',[3,4])
                    ->where($this->table.".date_end",'<',Carbon::now());
            }

            unset($filters['work_overdue_search']);
        }

        $query->groupBy("{$this->table}.manage_work_id");

        $user = Auth::user();

        //        if (!isset($filters['processor_id']) && !isset($filters['support_id']) && !isset($filters['assignor_id'])) {
        //            $staffId = Auth::id();
        ////            $query = $query
        ////                ->where(function ($sql) use ($staffId){
        ////                    $sql->where($this->table.'.processor_id',$staffId)
        ////                        ->orWhere($this->table.'.assignor_id',$staffId)
        ////                        ->orWhere($this->table.'.approve_id',$staffId)
        ////                        ->orWhere('p4.staff_id',$staffId);
        ////                });
        //        } else {
        //            $query = $this->getPermission($query);
        //        }

//        $query = $this->getPermission($query);

        if (isset($filters['page']) && $filters['page'] == 'all') {
            return $query->get();
        }

        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getAll()
    {
        return $this
            ->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getAllParent()
    {
        return $this
            ->whereNull('parent_id')
            ->orderBy($this->primaryKey, 'desc')->get();
    }

    /**
     * Danh sách công việc cha có phân trang
     * @param array $filter
     * @return mixed
     */
    public function getAllParentPagination($filters = [])
    {
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $oSelect = $this
            ->whereNull('parent_id')
            ->orderBy($this->primaryKey, 'desc');

        if(isset($filters['parent_id'])){
            $oSelect = $oSelect->where('manage_work_id',$filters['parent_id']);
        }

        if(isset($filters['manage_project_id'])){
            $oSelect = $oSelect->where('manage_project_id',$filters['manage_project_id']);
        } else {
            $oSelect = $oSelect->whereNull('manage_project_id');
        }

        if(isset($filters['manage_type_work_id'])){
            $oSelect = $oSelect->where('manage_type_work_id',$filters['manage_type_work_id']);
        }

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getName(){
        $oSelect= self::select("manage_work_id","manage_work_title")->get();
        return ($oSelect->pluck("manage_work_title","manage_work_id")->toArray());
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_work_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        if (isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && !isset($data['date_finish'])) {
            $data['date_finish'] = Carbon::now();
        }

        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this
            ->select(
                $this->table . '.*',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->where($this->primaryKey, $id)->first();
    }

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '')
    {
        $select = $this->where('manage_project_id', $name)
            ->where('manage_work_id', '<>', $id)
            ->first();
        return $select;
    }

    /**
     * Danh sách export
     * @param array $filters
     * @return mixed
     */
    public function getListExport($filters = [])
    {
        $oSelect = $this
            ->select(
                'staffs.full_name',
                DB::raw('COUNT(manage_work.manage_work_id) as total_process'),
                DB::raw('SUM(IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.manage_status_id IN (2,3,5,6), ABS((DATE_FORMAT(TIMEDIFF(manage_work.date_finish,manage_work.date_end),"%H")*60 + DATE_FORMAT(TIMEDIFF(manage_work.date_finish,manage_work.date_end),"%i"))),0)) as total_time_work'),
                DB::raw('SUM(IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish < manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0)) as total_completed_schedule'),
                DB::raw('SUM(IF(manage_work.date_finish IS NOT NULL AND manage_work.date_finish != "0000-00-00 00:00:00" AND manage_work.date_finish > manage_work.date_end AND manage_work.manage_status_id = 6, 1 ,0)) as total_completed_overdue'),
                DB::raw('SUM(IF(manage_work.manage_status_id NOT IN (6,7), 1 ,0)) as total_not_completed'),
                DB::raw('SUM(IF(manage_work.manage_status_id NOT IN (6,7) AND NOW() > manage_work.date_end, 1 ,0)) as total_overdue')
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id');

        if (isset($filters['dateSelect'])) {
            $date = explode(' - ', $filters['dateSelect']);
            $start = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d 23:59:59');

            //            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
            //                $sql->whereBetween($this->table . '.date_start', [$start, $end])
            //                    ->orWhereBetween($this->table . '.date_end', [$start, $end]);
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql
                    ->whereBetween('manage_work.date_start', [$start, $end])
                    ->where('manage_work.date_end', '>=', Carbon::now())
                    ->where('manage_work.date_start', '<=', $end)
                    //                        ->whereBetween('manage_work.date_end',[$start,$end])
                    ->where('manage_work.date_end', '>=', $end)
                    //                        ->orWhere(function ($sql1) use ($start){
                    //                            $sql1
                    //                                ->where('manage_work.date_start','<=',$start)
                    //                                ->where('manage_work.date_end','>=',$start);
                    //                        })
                    ->where(function ($sql1) use ($start, $end) {
                        $sql1
                            ->orWhere(function ($sql2) use ($start, $end) {
                                $sql2
                                    ->whereNull('manage_work.date_start')
                                    ->where('manage_work.created_at', '>=', $end);
                            })
                            ->orWhere(function ($sql2) use ($start, $end) {
                                $sql2
                                    ->whereNotNull('manage_work.date_start')
                                    ->where('manage_work.date_start', '>=', $end);
                            });
                    })
                    ->orWhere(function ($sql1) use ($start, $end) {
                        $sql1
                            //                            ->where('manage_work.date_start','<=',$start)
                            ->where(function ($sql2) use ($start, $end) {
                                $sql2
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start, $end) {
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start', '>=', $start);
                                    });
                            })
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        if (isset($filters['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $filters['branch_id']);
        }

        if (isset($filters['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $filters['department_id']);
        }

        if (isset($filters['staff_id'])) {
            $oSelect = $oSelect->where('staffs.staff_id', $filters['staff_id']);
        }

        if (isset($filters['sort_key'])) {
            $oSelect = $oSelect->orderBy($filters['sort_key'], $filters['sort_type']);
        }

        return $oSelect->groupBy($this->table . '.processor_id')->get();
    }

    /**
     * Tổng công việc theo trạng thái
     * @param $filter
     */
    public function getTotalByStatus($filter)
    {
        $oSelect = $this
            ->select(
                //                DB::raw('SUM(IF(manage_work.manage_status_id = 1, 1 , 0)) as status_1'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id = 2, 1 , 0)) as status_2'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id = 3, 1 , 0)) as status_3'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id = 4, 1 , 0)) as status_4'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id = 5, 1 , 0)) as status_5'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id = 6, 1 , 0)) as status_6'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id = 7, 1 , 0)) as status_7'),
                DB::raw('COUNT(*) as total_work'),
                'manage_work.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                DB::raw('SUM(IF(manage_work.date_end < now(), 1 , 0)) as overdue')
            )
//            ->leftJoin('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->where('manage_status_config.is_active',1);
        //            ->whereNotIn($this->table.'.manage_status_id', [6,7]);

        if (isset($filter['chart_dateSelect'])) {
            $date = explode(' - ', $filter['chart_dateSelect']);
            $start = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d 23:59:59');


            //            $oSelect = $oSelect->where(function ($sql) use ($start,$end){
            //                $sql
            //                    ->where('manage_work.date_end','>=',Carbon::now())
            //                    ->where(function ($sql1) use ($end){
            //                        $sql1->whereNull('manage_work.date_start')
            //                            ->orWhere('manage_work.date_start','<=',Carbon::now())
            //                            ->orWhere('manage_work.date_start','<=',$end);
            //                    });
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql
                    ->whereBetween('manage_work.date_start', [$start, $end])
                    //                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere('manage_work.date_end', '>=', $end)
                    ->where('manage_work.date_start', '<=', $end)
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            //                            ->where('manage_work.date_start','<=',$start)
                            ->where(function ($sql2) use ($start) {
                                $sql2
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start', '<=', $start);
                                    });
                            })
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        if (isset($filter['chart_branch_id']) && $filter['chart_branch_id'] != '') {
            $oSelect = $oSelect->where('staffs.branch_id', $filter['chart_branch_id']);
        }

        if (isset($filter['chart_department_id']) && $filter['chart_department_id'] != '') {
            //            $oSelect = $oSelect->where('staffs.department_id', $filter['chart_department_id']);
            $departmentId = $filter['chart_department_id'];
            $oSelect = $oSelect->where(function ($ds) use ($departmentId) {
                $ds->where("p1.department_id", $departmentId)
                    ->orWhere("p2.department_id", $departmentId)
                    ->orWhere("p3.department_id", $departmentId)
                    ->orWhere("staffs.department_id", $departmentId);
            });
        }

        if (isset($filter['chart_manage_project_id']) && $filter['chart_manage_project_id'] != '') {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $filter['chart_manage_project_id']);
        }

        $oSelect = $this->getPermission($oSelect);

        //        return $oSelect->orderBy($this->table.'.manage_status_id','ASC')->first();
        return $oSelect->orderBy($this->table . '.manage_status_id', 'ASC')->groupBy($this->table . '.manage_status_id')->get();
    }

    /**
     * Tổng công việc theo mức độ
     * @param $filter
     */
    public function getTotalByPriority($filter)
    {
        $oSelect = $this
            ->select(
                DB::raw('SUM(IF(manage_work.priority = 1, 1 , 0)) as priority_1'),
                DB::raw('SUM(IF(manage_work.priority = 2, 1 , 0)) as priority_2'),
                DB::raw('SUM(IF(manage_work.priority = 3, 1 , 0)) as priority_3')
            )
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->where('manage_status_config.is_active',1);
//            ->whereNotIn($this->table . '.manage_status_id', [6, 7]);

        if (isset($filter['chart_dateSelect'])) {
            $date = explode(' - ', $filter['chart_dateSelect']);
            $start = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d 23:59:59');

            //            $oSelect = $oSelect->where(function ($sql) use ($start,$end){
            //                $sql
            //                    ->where('manage_work.date_end','>=',Carbon::now())
            //                    ->where(function ($sql1) use ($end){
            //                        $sql1->whereNull('manage_work.date_start')
            //                            ->orWhere('manage_work.date_start','<=',Carbon::now())
            //                            ->orWhere('manage_work.date_start','<=',$end);
            //                    });
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    //                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere('manage_work.date_end', '>=', $end)
                    ->where('manage_work.date_start', '<=', $end)
                    //                    ->orWhere(function ($sql1) use ($start){
                    //                        $sql1
                    //                            ->where('manage_work.date_start','<=',$start)
                    //                            ->where('manage_work.date_end','>=',$start);
                    //                    })
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            //                            ->where('manage_work.date_start','<=',$start)
                            ->where(function ($sql2) use ($start) {
                                $sql2
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start', '<=', $start);
                                    });
                            })
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        if (isset($filter['chart_branch_id']) && $filter['chart_branch_id'] != '') {
            $oSelect = $oSelect->where('staffs.branch_id', $filter['chart_branch_id']);
        }

        if (isset($filter['chart_department_id']) && $filter['chart_department_id'] != '') {
            //            $oSelect = $oSelect->where('staffs.department_id', $filter['chart_department_id']);
            $departmentId = $filter['chart_department_id'];
            $oSelect = $oSelect->where(function ($ds) use ($departmentId) {
                $ds->where("p1.department_id", $departmentId)
                    ->orWhere("p2.department_id", $departmentId)
                    ->orWhere("p3.department_id", $departmentId)
                    ->orWhere("staffs.department_id", $departmentId);
            });
        }

        if (isset($filter['chart_manage_project_id']) && $filter['chart_manage_project_id'] != '') {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $filter['chart_manage_project_id']);
        }

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->orderBy($this->table . '.priority', 'ASC')->first();
    }

    /**
     * Danh sách công việc theo mức độ phân trang
     * @param $filter
     */
    public function getTotalByPriorityPagination($filter = [])
    {
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.updated_at',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'p3.full_name as approve_name',
                'p1.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'customers.full_name as customer_name',
                DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"),
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                'manage_status_config.manage_color_code',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')

            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id');

        if (isset($filter['chart_dateSelect'])){
            $date = explode(' - ',$filter['chart_dateSelect']);
            $start = Carbon::createFromFormat('d/m/Y',$date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$date[1])->format('Y-m-d 23:59:59');

            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhere('manage_work.date_end','>=',$end)
                    ->where('manage_work.date_start','<=',$end)
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where(function ($sql2) use($start){
                                $sql2
                                    ->orWhere(function ($sql3) use ($start){
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start){
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start','<=',$start);
                                    });
                            })
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });
            });

        }

        if (isset($filter['chart_branch_id']) && $filter['chart_branch_id'] != '') {
            $oSelect = $oSelect->where('staffs.branch_id', $filter['chart_branch_id']);
        }

        if (isset($filter['chart_department_id']) && $filter['chart_department_id'] != '') {
            $departmentId = $filter['chart_department_id'];
            $oSelect = $oSelect->where(function ($ds) use ($departmentId) {
                $ds->where("p1.department_id", $departmentId)
                    ->orWhere("p2.department_id", $departmentId)
                    ->orWhere("p3.department_id", $departmentId)
                    ->orWhere("staffs.department_id", $departmentId);
            });
        }

        if (isset($filter['chart_manage_project_id']) && $filter['chart_manage_project_id'] != '') {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $filter['chart_manage_project_id']);
        }

        $oSelect = $this->getPermission($oSelect);

        return $oSelect
            ->where('manage_status_config.is_active',1)
            ->whereNotNull($this->table . '.priority')
            ->orderBy($this->table . '.priority', 'ASC')
            ->groupBy($this->table.'.manage_work_id')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Danh sách công việc theo trạng thái
     * @param $filter
     */
    public function getTotalByStatusPagination($filter)
    {
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.updated_at',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'p3.full_name as approve_name',
                'p1.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'customers.full_name as customer_name',
                DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"),
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                'manage_status_config.manage_color_code',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id');

        if (isset($filter['chart_dateSelect'])){
            $date = explode(' - ',$filter['chart_dateSelect']);
            $start = Carbon::createFromFormat('d/m/Y',$date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$date[1])->format('Y-m-d 23:59:59');

            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql
                    ->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhere('manage_work.date_end','>=',$end)
                    ->where('manage_work.date_start','<=',$end)
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where(function ($sql2) use($start){
                                $sql2
                                    ->orWhere(function ($sql3) use ($start){
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start){
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start','<=',$start);
                                    });
                            })
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });

            });


        }

        if (isset($filter['chart_branch_id']) && $filter['chart_branch_id'] != '') {
            $oSelect = $oSelect->where('staffs.branch_id', $filter['chart_branch_id']);
        }

        if (isset($filter['chart_department_id']) && $filter['chart_department_id'] != '') {
            $departmentId = $filter['chart_department_id'];
            $oSelect = $oSelect->where(function ($ds) use ($departmentId) {
                $ds->where("p1.department_id", $departmentId)
                    ->orWhere("p2.department_id", $departmentId)
                    ->orWhere("p3.department_id", $departmentId)
                    ->orWhere("staffs.department_id", $departmentId);
            });
        }

        if (isset($filter['chart_manage_project_id']) && $filter['chart_manage_project_id'] != '') {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $filter['chart_manage_project_id']);
        }

        $oSelect = $this->getPermission($oSelect);

        return $oSelect
            ->where('manage_status_config.is_active',1)
            ->orderBy($this->table . '.date_end', 'DESC')
            ->groupBy($this->table.'.manage_work_id')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy danh sách công việc bị trễ hạn
     * @param $data
     */
    public function getListOverdue($data)
    {
        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                'staffs.branch_id as processor_branch_id',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.is_approve_id',
                $this->table . '.priority',
                $this->table . '.branch_id',
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} , 1,0) as is_approve"),
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                'departments.department_id',
                'departments.department_name',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('departments', 'departments.department_id', 'staffs.department_id')
            ->join('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->whereNotIn($this->table . '.manage_status_id', [6, 7]);

        //        Tìm kiếm chi nhánh
        if (isset($data['staff_branch_id']) && $data['staff_branch_id'] != '') {
            $oSelect = $oSelect->where('staffs.branch_id', $data['staff_branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['staff_department_id']) && $data['staff_department_id'] != '') {
            $oSelect = $oSelect->where('staffs.department_id', $data['staff_department_id']);
        }

        if (isset($data['staff_manage_project_id']) && $data['staff_manage_project_id'] != '') {
            $oSelect = $oSelect->where($this->table.'.manage_project_id', $data['staff_manage_project_id']);
        }


        if (isset($data['status_overdue']) && $data['status_overdue'] != '') {
            $oSelect = $oSelect
                ->where($this->table . '.date_end', '<=', Carbon::now())
//                ->whereIn($this->table . '.manage_status_id', $data['status_overdue']);
                ->whereNotIn('manage_status_config.manage_status_group_config_id',[3,4]);;
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect
            ->orderBy($this->table . '.date_end', 'DESC')
            ->get();
        return $oSelect;
    }

    /**
     * lấy danh sách công việc của tôi theo ngày
     * @param $data
     */
    public function getMyWorkByDate($data)
    {

        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                $this->table . '.manage_project_id',
                'manage_project.manage_project_name',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.branch_id',
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
            ->join('staffs', 'staffs.staff_id', '=', $this->table . '.processor_id')
            //            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('manage_work_support', 'manage_work_support.manage_work_id', $this->table . '.manage_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->whereNotIn($this->table . '.manage_status_id', [6, 7]);

        //        if(isset($data['list_dateSelect']) && $data['list_dateSelect'] != ''){
        //            $date = explode(' - ',$data['list_dateSelect']);
        //            $start = Carbon::createFromFormat('d/m/Y',$date[0])->format('Y-m-d 00:00:00');
        //            $end = Carbon::createFromFormat('d/m/Y',$date[1])->format('Y-m-d 23:59:59');
        //            $data['from_date'] = $start;
        //            $data['to_date'] = $end;
        //        }

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = $data['from_date'];
            $end = $data['to_date'];
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql
                    ->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            //                            ->where('manage_work.date_start','<=',$start)
                            ->where(function ($sql2) use ($start) {
                                $sql2
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start', '<=', $start);
                                    });
                            })
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        //        Tìm kiếm chi nhánh
        if (isset($data['list_branch_id']) && $data['list_branch_id'] != '') {
            $oSelect = $oSelect->where('staffs.branch_id', $data['list_branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['list_department_id']) && $data['list_department_id'] != '') {
            //            $oSelect = $oSelect->where('staffs.department_id', $data['list_department_id']);
            $departmentId = $data['list_department_id'];
            $oSelect = $oSelect->where(function ($ds) use ($departmentId) {
                $ds->where("p1.department_id", $departmentId)
                    ->orWhere("p2.department_id", $departmentId)
                    ->orWhere("p3.department_id", $departmentId)
                    ->orWhere("staffs.department_id", $departmentId);
            });
        }

        //        Dự án
        if (isset($data['list_manage_project_id']) && $data['list_manage_project_id'] != '') {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $data['list_manage_project_id']);
        }

        if (isset($data['type']) && $data['type'] == 'expired') {
            //            $oSelect->where(function($query){
            //                $query->where(function($query1){
            //                    $query1->whereDate($this->table.'.date_end', '<=', Carbon::now()->format('Y-m-d H:i:00'))
            //                        ->where($this->table.'.manage_status_id', '<>' , 6);
            //                })
            //                    ->orWhere(function($query3){
            //                        $query3->where(function($query4){
            //                            $query4->whereDate($this->table.'.date_finish', '>=', $this->table.'.date_end')
            //                                ->where($this->table.'.manage_status_id' , 6);
            //                        });
            //                    });
            //            });

            $oSelect = $oSelect
                ->where($this->table . '.date_end', '<=', Carbon::now())
                ->whereNotIn('manage_status_config.manage_status_group_config_id',[3,4]);
                //                ->whereIn($this->table . '.manage_status_id', $data['status_overdue']);
//                ->whereNotIn($this->table . '.manage_status_id', [6, 7]);
        };

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC')
            ->groupBy($this->table . '.manage_work_id')
            ->get();


        if (count($oSelect) != 0) {
            foreach ($oSelect as $key => $item) {
                $oSelect[$key]['text_overdue'] = '';
            }
        }

        return $oSelect;
    }

    /**
     * lấy danh sách công việc của tôi theo ngày
     * @param $data
     */
    public function getMyWorkReportByDate($data)
    {
        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                $this->table . '.manage_project_id',
                'manage_project.manage_project_name',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.branch_id',
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('manage_work_support as p4', function ($sql) {
                $sql->on('p4.manage_work_id', '=', $this->table . '.manage_work_id');
                //                    ->on('p4.staff_id', '=', DB::raw(Auth::id()));
            })
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('manage_work_support', 'manage_work_support.manage_work_id', $this->table . '.manage_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->where(function ($sql) {
                $sql->where($this->table . '.processor_id', Auth::id());
//                    ->orWhere('p4.staff_id', Auth::id());
            })
            ->whereNotIn($this->table . '.manage_status_id', [6, 7]);

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = $data['from_date'];
            $end = $data['to_date'];
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    //                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere('manage_work.date_end', '>=', $end)
                    ->where('manage_work.date_start', '<=', $end)
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });

                //                $sql
                //                    ->where('manage_work.date_end', '>=', Carbon::now())
                //                    ->where(function ($sql1) use ($end) {
                //                        $sql1->whereNull('manage_work.date_start')
                //                            ->orWhere('manage_work.date_start', '<=', Carbon::now())
                //                            ->orWhere('manage_work.date_start', '<=', $end);
                //                    });
            });
        }

        if (isset($data['type']) && $data['type'] == 'expired') {

            $oSelect = $oSelect
                ->where($this->table . '.date_end', '<=', Carbon::now())
                ->whereNotIn('manage_status_config.manage_status_group_config_id', [3,4]);
                //                ->whereIn($this->table.'.manage_status_id',$data['status_overdue']);
//                ->whereNotIn($this->table . '.manage_status_id', [6, 7]);
        };

        //        Tìm kiếm chi nhánh
        if (isset($data['list_branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['list_branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['list_department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['list_department_id']);
        }

        //        Dự án
        if (isset($data['list_manage_project_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $data['list_manage_project_id']);
        }

        if (isset($data['sort_manage_work_title'])) {
            $oSelect = $oSelect->orderBy($this->table . '.manage_work_title', $data['sort_manage_work_title']);
        }

        if (isset($data['sort_manage_work_progress'])) {
            $oSelect = $oSelect->orderBy($this->table . '.progress', $data['sort_manage_work_progress']);
        }

        if (isset($data['sort_manage_work_date_end'])) {
            $oSelect = $oSelect->orderBy($this->table . '.date_end', $data['sort_manage_work_date_end']);
        }

        if (!isset($data['sort_manage_work_title']) && !isset($data['sort_manage_work_progress']) && !isset($data['sort_manage_work_date_end'])) {
            $oSelect = $oSelect->orderBy($this->table . '.date_end', 'DESC');
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->groupBy($this->table . '.manage_work_id')
            ->get();

        if (count($oSelect) != 0) {
            foreach ($oSelect as $key => $item) {
                $oSelect[$key]['text_overdue'] = '';
            }
        }

        return $oSelect;
    }

    /**
     * lấy danh sách công việc theo mảng id công việc
     */
    public function getListWorkByWork($arrWorkId)
    {
        return $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.branch_id',
                'staffs.full_name as staff_name',
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->whereIn('manage_work_id', $arrWorkId)
            ->get();
    }

    /**
     * Chi tiết công việc
     * @param $manage_work_id
     * @return mixed
     */
    public function getDetail($manage_work_id)
    {
        $personal = __('Cá nhân');
        $businness = __('Doanh nghiệp');

        return $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_customer_type',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.customer_id',
                $this->table . '.is_booking',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'approve.full_name as approve_name',
                'createdStaff.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw("CONCAT((CASE WHEN customers.customer_type = 'bussiness' THEN '{$personal}' ELSE '{$businness}' END),'_',COALESCE(customers.full_name,''),'_',COALESCE(customers.phone1,''),'_',COALESCE(customers.email,'')) as customer_name"),
                DB::raw("CONCAT((CASE WHEN lead.customer_type = 'bussiness' THEN '{$personal}' ELSE '{$businness}' END),'_',COALESCE(lead.full_name,''),'_',COALESCE(lead.phone,''),'_',COALESCE(lead.email,'')) as lead_name"),
                'deal.deal_name',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', $this->table . '.customer_id')
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', $this->table . '.customer_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as createdStaff', 'createdStaff.staff_id', $this->table . '.created_by')
            ->where($this->table . '.manage_work_id', $manage_work_id)
            ->first();
    }

    /**
     * Lấy danh sách công việc con
     * @param $data
     */
    public function getListWorkChild($data)
    {

        $page    = (int) ($data['page'] ?? 1);
        $display = (int) ($data['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.updated_at',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'approve.full_name as approve_name',
                'createdStaff.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'customers.full_name as customer_name',
                DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"),
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                'manage_status_config.manage_color_code',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as createdStaff', 'createdStaff.staff_id', $this->table . '.created_by')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id');

        if (isset($data['manage_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.parent_id', $data['manage_work_id']);
        }

        if (isset($data['manage_status_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_status_id', $data['manage_status_id']);
        }

        if (isset($data['date_created'])) {
            $data = explode(' - ', $data['date_created']);
            $start = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table . '.date_start', [$start, $end]);
        }

        if (isset($data['date_created_detail'])) {
            $data = explode(' - ', $data['date_created_detail']);
            $start = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table . '.created_at', [$start, $end]);
        }

        if (isset($data['date_end'])) {
            $data = explode(' - ', $data['date_end']);
            $start = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table . '.date_end', [$start, $end]);
        }

        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }


        return $oSelect
            ->orderBy($this->table . '.date_end', 'DESC')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Tạo công việc
     * @param $data
     * @return mixed
     */
    public function createdWork($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * Cập nhật công việc
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updateWork($data, $id)
    {
        if (isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && !isset($data['date_finish'])) {
            $data['date_finish'] = Carbon::now();
        }

        return $this->where('manage_work_id', $id)->update($data);
    }

    /**
     * Tổng công việc tôi được giao
     */
    public function getTotalMyWorkProcessor()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        return $this
            ->where('processor_id', Auth::id())
            ->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            })
            ->count();
    }

    /**
     * Lấy danh sách công việc của tôi
     */
    public function getListMyWork()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $oSelect =  $this
            ->select(
                $this->table . '.*',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work_support as p4', function ($sql){
                $sql->on('p4.manage_work_id', '=', $this->table.'.manage_work_id');
//                    ->on('p4.staff_id', '=', DB::raw(Auth::id()));
            })
            ->where('manage_status_config.is_active',1)
            ->where('processor_id',Auth::id())
//            ->orWhere('p4.staff_id',Auth::id());
            ->where(function ($sql) use ($start,$end){
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });

        $oSelect = $this->getPermission($oSelect);
        return $oSelect->groupBy($this->table . '.manage_work_id')->get();
    }

    /**
     * Lấy danh sách công việc của tôi phân trang
     */
    public function getListMyWorkPagination($filter = []){
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $oSelect =  $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.updated_at',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'approve.full_name as approve_name',
                'createdStaff.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'customers.full_name as customer_name',
                DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"),
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                'manage_status_config.manage_color_code',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as createdStaff', 'createdStaff.staff_id', $this->table . '.created_by')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')

        ->leftJoin('manage_work_support as p4', function ($sql){
                $sql->on('p4.manage_work_id', '=', $this->table.'.manage_work_id');
            })
            ->where('manage_status_config.is_active',1)
            ->where($this->table.'.processor_id',Auth::id())
            ->where(function ($sql) use ($start,$end){
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where('manage_work.date_start','<=',$start)
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });
            });

        $oSelect = $this->getPermission($oSelect);

        return $oSelect
            ->groupBy($this->table.'.manage_work_id')
            ->orderBy($this->table . '.date_end', 'DESC')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy danh sách công việc của tôi support
     */
    public function getListMyWorkSupport()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d 23:00:00');
        $oSelect =  $this
            ->select(
                $this->table . '.*',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work_support as p4', function ($sql){
                $sql->on('p4.manage_work_id', '=', $this->table.'.manage_work_id');
//                    ->on('p4.staff_id', '=', DB::raw(Auth::id()));
            })
//            ->where('processor_id',Auth::id())
            ->where('p4.staff_id',Auth::id())
            ->where('manage_status_config.is_active',1)
            ->where(function ($sql) use ($start,$end){
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });

        $oSelect = $this->getPermission($oSelect);
        return $oSelect->groupBy($this->table . '.manage_work_id')->get();
    }

    /**
     * Lấy danh sách công việc của tôi support phân trang
     */
    public function getListMyWorkSupportPagination($filter = []){
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $start = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d 23:00:00');
        $oSelect =  $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.updated_at',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'approve.full_name as approve_name',
                'createdStaff.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'customers.full_name as customer_name',
                DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"),
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                'manage_status_config.manage_color_code',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as createdStaff', 'createdStaff.staff_id', $this->table . '.created_by')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')

            ->leftJoin('manage_work_support as p4', function ($sql){
                $sql->on('p4.manage_work_id', '=', $this->table.'.manage_work_id');
            })
            ->where('p4.staff_id',Auth::id())
            ->where('manage_status_config.is_active',1)
            ->where(function ($sql) use ($start,$end){
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where('manage_work.date_start','<=',$start)
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });
            });

        $oSelect = $this->getPermission($oSelect);
        return $oSelect->groupBy($this->table.'.manage_work_id')
            ->orderBy($this->table . '.date_end', 'DESC')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getTotalHome($data = []){
        $staffId = Auth::id();
        $data['from_date'] = Carbon::now()->startOfMonth()->format('Y/m/d');
        $data['to_date'] = Carbon::now()->endOfMonth()->format('Y/m/d');
        $oSelect = $this
            ->select(
                DB::raw('SUM(IF((manage_work.date_start IS NULL OR manage_work.date_start < NOW()) AND manage_work.date_end > NOW() AND manage_work.manage_status_id NOT IN (6,7) , 1 , 0)) as total_work_day'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,5,6) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,3,4,5,6,7) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 1 , 1 , 0)) as total_not_started_yet'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 2 , 1 , 0)) as total_started'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 6 , 1 , 0)) as total_complete'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 5 , 1 , 0)) as total_unfinished'),
                //                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id IN (1,2,5) ) , 1 , 0)) as total_overdue')
                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id NOT IN (6,7) ) , 1 , 0)) as total_overdue')
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            });

        if (!isset($data['job_overview'])) {
            $oSelect = $oSelect->where(function ($sql) use ($staffId) {
                $sql->where($this->table . '.processor_id', $staffId);
                //                    ->orWhere($this->table.'.assignor_id',$staffId)
                //                    ->orWhere($this->table.'.approve_id',$staffId)
                //                    ->orWhere('manage_work_support.staff_id',$staffId);
            });
        }

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = Carbon::createFromFormat('Y/m/d', $data['from_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['to_date'])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }
        /*
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->first();
        return $oSelect;
    }

    public function getTotalHomeSupport($data = [])
    {
        $staffId = Auth::id();
        $data['from_date'] = Carbon::now()->startOfMonth()->format('Y/m/d');
        $data['to_date'] = Carbon::now()->endOfMonth()->format('Y/m/d');
        $oSelect = $this
            ->select(
                DB::raw('SUM(IF((manage_work.date_start IS NULL OR manage_work.date_start < NOW()) AND manage_work.date_end > NOW() AND manage_work.manage_status_id NOT IN (6,7) , 1 , 0)) as total_work_day'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,5,6) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,3,4,5,6,7) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 1 , 1 , 0)) as total_not_started_yet'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 2 , 1 , 0)) as total_started'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 6 , 1 , 0)) as total_complete'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 5 , 1 , 0)) as total_unfinished'),
                //                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id IN (1,2,5) ) , 1 , 0)) as total_overdue')
                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id NOT IN (6,7) ) , 1 , 0)) as total_overdue')
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            });

        if (!isset($data['job_overview'])) {
            $oSelect = $oSelect->where(function ($sql) use ($staffId) {
                $sql
                    //                    ->where($this->table.'.processor_id',$staffId);
                    //                    ->orWhere($this->table.'.assignor_id',$staffId)
                    //                    ->orWhere($this->table.'.approve_id',$staffId)
                    ->where('manage_work_support.staff_id', $staffId);
            });
        }

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = Carbon::createFromFormat('Y/m/d', $data['from_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['to_date'])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }
        /*
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->first();
        return $oSelect;
    }

    /**
     * Tổng công việc chưa hoàn thành
     * @return mixed
     */
    public function getTotalMyWorkUnfinished()
    {
        $staffId = Auth::id();
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $oSelect = $this
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            })->where(function ($sql) use ($staffId) {
                $sql->where($this->table . '.processor_id', $staffId)
                    ->orWhere($this->table . '.assignor_id', $staffId)
                    ->orWhere($this->table . '.approve_id', $staffId)
                    ->orWhere('manage_work_support.staff_id', $staffId);
            })
            ->where('processor_id', Auth::id())
            ->whereNotIn('manage_work.manage_status_id', [6, 7]);

        //        $oSelect = $this
        //            ->whereNotIn('manage_status_id',[self::FINISH,self::CANCEL])
        //            ->where(function ($sql) use ($start,$end){
        //                $sql->whereBetween('manage_work.date_start',[$start,$end])
        //                    ->orWhereBetween('manage_work.date_end',[$start,$end])
        //                    ->orWhere(function ($sql1) use ($start){
        //                        $sql1
        //                            ->where('manage_work.date_start','<=',$start)
        //                            ->where('manage_work.date_end','>=',$start);
        //                    })
        //                    ->orWhere(function ($sql1) use ($end){
        //                        $sql1
        //                            ->where('manage_work.date_start','<=',$end)
        //                            ->where('manage_work.date_end','>=',$end);
        //                    });
        //            })
        //            ->whereNotIn($this->table.'.manage_status_id', [6,7]);

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->count();
    }

    /**
     * Tổng công việc quá hạn
     * @return mixed
     */
    public function getTotalMyWorkOverdue()
    {
        $staffId = Auth::id();
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $oSelect = $this
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            })->where(function ($sql) use ($staffId) {
                $sql->where($this->table . '.processor_id', $staffId)
                    ->orWhere($this->table . '.assignor_id', $staffId)
                    ->orWhere($this->table . '.approve_id', $staffId)
                    ->orWhere('manage_work_support.staff_id', $staffId);
            })
            ->where('processor_id', Auth::id())
            ->where('manage_work.date_end', '<', Carbon::now())
            ->whereNotIn('manage_work.manage_status_id', [6, 7]);
        //            ->where(function ($sql) use ($start,$end){
        //                $sql->whereBetween('manage_work.date_start',[$start,$end])
        //                    ->orWhereBetween('manage_work.date_end',[$start,$end])
        //                    ->orWhere(function ($sql1) use ($start){
        //                        $sql1
        //                            ->where('manage_work.date_start','<=',$start)
        //                            ->where('manage_work.date_end','>=',$start);
        //                    })
        //                    ->orWhere(function ($sql1) use ($end){
        //                        $sql1
        //                            ->where('manage_work.date_start','<=',$end)
        //                            ->where('manage_work.date_end','>=',$end);
        //                    });
        //            })
        //            ->where(function ($sql){
        //                $sql
        //                    ->where(function ($sql){
        //                    $sql->whereNotIn('manage_status_id',[self::FINISH,self::CANCEL])
        //                        ->where('date_end' ,'<', Carbon::now());
        //                    })
        //                    ->orWhere(function ($sql){
        //                    $sql->where('manage_status_id',self::FINISH)
        //                        ->whereRaw('manage_work.date_finish > manage_work.date_end');
        //                    });
        //
        //            })

        $oSelect = $this->getPermission($oSelect);
        return $oSelect->count();
    }

    /**
     * Lấy danh sách công việc tôi giao và chờ duyệt
     * @param $data
     */
    public function getListMyWorkAssignPending($data)
    {
        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                $this->table . '.manage_project_id',
                'manage_project.manage_project_name',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.branch_id',
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('manage_work_support', 'manage_work_support.manage_work_id', $this->table . '.manage_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->orderBy($this->table . '.manage_status_id', 'ASC');

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = $data['from_date'];
            $end = $data['to_date'];
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        if (isset($data['type_my_work']) && $data['type_my_work'] == 'pending') {
            $oSelect = $oSelect
                ->where($this->table . '.approve_id', Auth::id())
                ->where($this->table . '.manage_status_id', self::STARTED)
                ->where($this->table . '.is_approve_id', 1);
        }

        if (isset($data['type_my_work']) && $data['type_my_work'] == 'assign') {
            $oSelect = $oSelect
                ->where($this->table . '.assignor_id', Auth::id())
                ->whereNotIn($this->table . '.manage_status_id', [self::FINISH, self::CANCEL]);
        }

        //        Tìm kiếm chi nhánh
        if (isset($data['list_branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['list_branch_id']);
        }

        if (isset($data['sort_assign_manage_work_title'])) {
            $oSelect = $oSelect->orderBy($this->table . '.manage_work_title', $data['sort_assign_manage_work_title']);
        }

        if (isset($data['sort_assign_manage_work_progress'])) {
            $oSelect = $oSelect->orderBy($this->table . '.progress', $data['sort_assign_manage_work_progress']);
        }

        if (isset($data['sort_assign_manage_work_date_end'])) {
            $oSelect = $oSelect->orderBy($this->table . '.date_end', $data['sort_assign_manage_work_date_end']);
        }

        if (!isset($data['sort_assign_manage_work_title']) && !isset($data['sort_assign_manage_work_progress']) && !isset($data['sort_assign_manage_work_date_end'])) {
            $oSelect = $oSelect
                ->orderBy($this->table . '.date_end', 'DESC');
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect
            //
            //            ->orderBy($this->table.'.date_end','DESC')
            ->groupBy($this->table . '.manage_work_id')
            ->get();


        return $oSelect;
    }

    /**
     * Chỉnh sửa công việc
     * @param $data
     * @param $id
     */
    public function editWork($data, $id)
    {
        if (isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && !isset($data['date_finish'])) {
            $data['date_finish'] = Carbon::now();
        }

        return $this->where('manage_work_id', $id)->update($data);
    }

    /**
     * Kiểm tra code tạo trong ngày
     * @param $code
     */
    public function getCodeWork($code)
    {
        $oSelect = $this
            ->where('manage_work_code', 'like', '%' . $code . '%')
            ->orderBy('manage_work_id', 'DESC')
            ->first();

        return $oSelect != null ? $oSelect['manage_work_code'] : null;
    }

    /**
     * lấy danh sách công việc theo loại công việc
     */
    public function getWorkByTypeWork($manage_type_work_id)
    {
        return $this->where('manage_type_work_id', $manage_type_work_id)->get();
    }

    public function getPermission($oSelect)
    {
        $user = Auth::user();

        $userId = $user->staff_id;

        $dataRole = DB::table('map_role_group_staff')
            ->select('manage_role.role_group_id', 'is_all', 'is_branch', 'is_department', 'is_own')
            ->join('manage_role', 'manage_role.role_group_id', 'map_role_group_staff.role_group_id')
            ->where('staff_id', $userId)
            ->get()->toArray();

        $isAll = $isBranch = $isDepartment = $isOwn = 0;
        foreach ($dataRole as $role) {
            $role = (array)$role;
            if ($role['is_all']) {
                $isAll = 1;
            }

            if ($role['is_branch']) {
                $isBranch = 1;
            }

            if ($role['is_department']) {
                $isDepartment = 1;
            }

            if ($role['is_own']) {
                $isOwn = 1;
            }
        }
        $listManageSupport = DB::table('manage_work_support')
            ->where('staff_id', $userId)
            ->get()->pluck('manage_work_id')->toArray();

        if ($isAll) {
        } else if ($isBranch) {
            $myBrand = $user->branch_id;

            //            $oSelect = $oSelect->join('staffs as per_staff', function ($join) use($myBrand){
            //                $join->on('per_staff.staff_id', '=', $this->table.'.processor_id')->on('per_staff.branch_id', '=', DB::raw($myBrand));
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($userId, $listManageSupport, $myBrand) {
                $sql->join('staffs as per_staff', function ($join) use ($myBrand) {
                    $join->on('per_staff.staff_id', '=', $this->table . '.processor_id')->on('per_staff.branch_id', '=', DB::raw($myBrand));
                })->orWhere($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        } else if ($isDepartment) {
            $myDep = $user->department_id;

            //            $oSelect = $oSelect->join('staffs as per_staff', function ($join) use($myDep){
            //                $join->on('per_staff.staff_id', '=', $this->table.'.processor_id')->on('per_staff.department_id', '=', DB::raw($myDep));
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($userId, $listManageSupport, $myDep) {
                $sql->join('staffs as per_staff', function ($join) use ($myDep) {
                    $join->on('per_staff.staff_id', '=', $this->table . '.processor_id')->on('per_staff.department_id', '=', DB::raw($myDep));
                })->orWhere($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        } else {
            $listManageSupport = DB::table('manage_work_support')
                ->where('staff_id', $userId)
                ->get()->pluck('manage_work_id')->toArray();

            $oSelect = $oSelect->where(function ($query) use ($userId, $listManageSupport) {
                $query->where($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        }

        return $oSelect;
    }

    /**
     * Tìm kiếm các công việc đang sử dụng trạng thái
     * @param $statusId
     * @return mixed
     */
    public function checkWorkByStatus($statusId)
    {
        return $this->where('manage_status_id', $statusId)->get();
    }

    public function getListByProject($id)
    {
        $oSelect = $this->where('manage_project_id', $id)->get();
        if ($oSelect) {
            return $oSelect->toArray();
        }

        return [];
    }

    /**
     * Lấy tổng công việc theo theo loại công việc
     */
    public function getTotalTypeWorkByLead($listCustomer, $manage_work_customer_type)
    {
        return $this
            ->select(
                'manage_type_work.manage_type_work_key',
                'manage_type_work.manage_type_work_name',
                'manage_type_work.manage_type_work_icon',
                DB::raw("COUNT(*) as total_work")

            )
            ->leftJoin('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->where('manage_type_work.manage_type_work_default', 1)
            ->where('manage_type_work.is_active', 1)
            ->where($this->table . '.manage_work_customer_type', $manage_work_customer_type)
            ->whereIn($this->table . '.customer_id', $listCustomer)
            ->whereNotIn($this->table . '.manage_status_id', [6, 7])
            ->groupBy('manage_type_work.manage_type_work_id')
            ->get();
    }

    public function getListWorkByCustomer($data)
    {
        $page    = (int) ($data['page'] ?? 1);
        $display = (int) ($data['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.manage_work_customer_type',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.updated_at',
                $this->table . '.customer_id',
                $this->table . '.branch_id',
                $this->table . '.created_at',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'approve.full_name as approve_name',
                'createdStaff.full_name as createdStaff_name',
                'updatedStaff.full_name as updatedStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'customers.full_name as customer_name',
                DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"),
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as createdStaff', 'createdStaff.staff_id', $this->table . '.created_by')
            ->join('staffs as updatedStaff', 'updatedStaff.staff_id', $this->table . '.updated_by')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id');

        if (isset($data['manage_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.parent_id', $data['manage_work_id']);
        }

        if (isset($data['processor_id'])) {
            $oSelect = $oSelect->where($this->table . '.processor_id', $data['processor_id']);
        }

        if (isset($data['manage_status_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_status_id', $data['manage_status_id']);
        }

        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where($this->table . '.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        if (isset($data['date_created'])) {
            $data = explode(' - ', $data['date_created']);
            $start = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table . '.date_start', [$start, $end]);
        }

        if (isset($data['date_end'])) {
            $data = explode(' - ', $data['date_end']);
            $start = Carbon::createFromFormat('d/m/Y', $data[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table . '.date_end', [$start, $end]);
        }

        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        if (isset($data['manage_work_customer_type'])) {
            $oSelect = $oSelect->where($this->table . '.manage_work_customer_type', $data['manage_work_customer_type']);
        }

        if (isset($data['customer_id'])) {
            $oSelect = $oSelect->where($this->table . '.customer_id', $data['customer_id']);
        }

        if (isset($data['manage_type_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_type_work_id', $data['manage_type_work_id']);
        }

        // if (isset($data['type_search'])) {
        //     if ($data['type_search'] == 'support') {
        //         $oSelect = $oSelect->whereNotIn($this->table . '.manage_status_id', [6, 7]);
        //     } else {
        //         $oSelect = $oSelect->whereIn($this->table . '.manage_status_id', [6, 7]);
        //     }
        // }

        return $oSelect
            ->orderBy($this->table . '.date_end', 'DESC')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * lấy danh sách công việc con
     * @param $manage_work_id
     * @return mixed
     */
    public function getListWorkChildInsert($manage_work_id)
    {
        return $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_customer_type',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.customer_id',
                $this->table . '.is_booking',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'approve.full_name as approve_name',
                'createdStaff.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw('CONCAT((CASE WHEN customers.customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",COALESCE(customers.full_name,""),"_",COALESCE(customers.phone1,""),"_",COALESCE(customers.email,"")) as customer_name'),
                DB::raw('CONCAT((CASE WHEN lead.customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",COALESCE(lead.full_name,""),"_",COALESCE(lead.phone,""),"_",COALESCE(lead.email,"")) as lead_name'),
                'deal.deal_name',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', $this->table . '.customer_id')
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', $this->table . '.customer_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as createdStaff', 'createdStaff.staff_id', $this->table . '.created_by')
            ->where($this->table . '.parent_id', $manage_work_id)
            ->get();
    }

    /**
     * Lấy danh sáhc công việc con
     * @param $parentId
     */
    public function getListChildTask($parentId)
    {
        return $this
            ->where('parent_id', $parentId)
            ->get()
            ->count();
    }

    /**
     * lấy tổng tiến trình
     */
    public function getTotalChildProgress($parentId)
    {
        return $this
            ->where('parent_id', $parentId)
            ->select(
                DB::raw("COUNT(1) as sum_work"),
                DB::raw("SUM(progress) as total_progress")
            )
            ->first();
    }

    public function getDetailOnly($id)
    {
        return $this
            ->where('manage_work_id', $id)
            ->first();
    }

    public function updateByParentId($data, $parentId)
    {
        return $this
            ->where('parent_id', $parentId)
            ->update($data);
    }

    /**
     * Lấy danh sách công việc con
     * @param $parentId
     * @return mixed
     */
    public function getListTaskOfParent($parentId)
    {
        return $this
            ->select(
                $this->table . '.*',
                'manage_status.manage_status_name'
            )
            ->leftJoin('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->where($this->table . '.parent_id', $parentId)
            ->get();
    }

    /**
     * Tổng công việc user tạo trong tháng
     * @return mixed
     */
    public function getTotalCreated(){
        $start = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
        return $this
            ->join('manage_status_config','manage_status_config.manage_status_id',$this->table.'.manage_status_id')
            ->where('manage_status_config.is_active',1)
            ->where($this->table.'.created_by',Auth::id())
            ->where(function ($sql) use ($start,$end){
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where('manage_work.date_start','<=',$start)
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });
            })
            ->count();
    }

    /**
     * Tổng số công việc tối duyệt trong tháng
     */
    public function getTotalApprove(){
        $start = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
        return $this
            ->join('manage_status_config','manage_status_config.manage_status_id',$this->table.'.manage_status_id')
            ->where('manage_status_config.is_active',1)
            ->where("{$this->table}.manage_status_id", 2)
            ->where("{$this->table}.is_approve_id", 1)
            ->where($this->table.'.approve_id',Auth::id())
            ->where(function ($sql) use ($start,$end){
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where('manage_work.date_start','<=',$start)
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });
            })
            ->count();
    }

    /**
     * Tính tổng số giờ làm việc
     * @param ManagerWorkTable $work
     * @return mixed
     */

    public function getTotalWorkTime($work)
    {
        $result = $work->select(
            DB::raw("SUM(IF({$this->table}.time_type = 'h',{$this->table}.time,0))+(SUM(IF({$this->table}.time_type = 'd',{$this->table}.time,0)) * 8) as total_time")
        )->first();
        return $result;
    }

    /**
     * Tính tiến độ dự án
     * @param $work
     * @param $type
     * @return mixed
     */

    public function getProgress($work)
    {
        $result = $work
            ->select(
                DB::raw("SUM(CASE WHEN mpsc.manage_project_status_group_config_id = 3 THEN 1 ELSE 0 END) AS total_status_complete"),
                DB::raw("COUNT(*) as total_status"),
                DB::raw("SUM(CASE WHEN mpsc.manage_project_status_group_config_id = 3 THEN 1 ELSE 0 END) / COUNT(*) * 100 as total_progress")
            )
            ->join("manage_project_status_config as mpsc", function ($join) {
                $join->on("{$this->table}.manage_status_id", "mpsc.manage_project_status_id")
                    ->where("mpsc.manage_project_status_group_config_id", "<>", 4);
            })
            ->first();
        return $result;
    }

    /**
     * danh công việc của nhân viên phòng ban theo từng trạng thái
     * @param $idProject
     * @param $idDepartment
     * @return mixed
     */

    public function getListWorkFollowStatus($idProject)
    {
        return $this
            ->select(
                "{$this->table}.{$this->primaryKey}",
                "{$this->table}.date_end",
                "{$this->table}.processor_id",
                "dpm.department_name",
                "dpm.department_id",
                "msc.manage_status_config_title as name_status",
                "msc.manage_status_id",
                "st.staff_id",
                "msc.manage_status_group_config_id"
            )
            ->where("{$this->table}.manage_project_id", $idProject)
            ->join("staffs as st", function ($join) {
                $join->on("st.staff_id", "{$this->table}.processor_id");
                $join->join("departments as dpm", "dpm.department_id", "st.department_id");
            })
            ->join("manage_status_config as msc", function ($join) {
                $join->on("msc.manage_status_id", "{$this->table}.manage_status_id");
                $join->where("msc.is_active", 1);
                $join->where("msc.manage_status_group_config_id", "<>", 4);
            })
            ->orderBy("msc.manage_status_id")
            ->get();
    }

    /**
     * Lấy danh sách công việc theo dự án
     * @param $projectId
     * @return mixed
     */
    public function getListWorkByProjectId($projectId){
        $oSelect = $this
            ->join('staffs','staffs.staff_id',$this->table.'.processor_id')
            ->leftJoin('departments','departments.department_id','staffs.department_id')
            ->where($this->table.'.manage_project_id',$projectId)
            ->get();
        return $oSelect;

//        $query = $this->select(
//            "{$this->table}.manage_work_id",
//            "parent_work.manage_work_id as manage_parent_work_id",
//            "{$this->table}.manage_project_id",
//            "{$this->table}.manage_type_work_id",
//            "{$this->table}.manage_work_title",
//            "{$this->table}.manage_work_code",
//            "{$this->table}.date_start",
//            "{$this->table}.date_end",
//            "{$this->table}.date_finish",
//            "{$this->table}.processor_id",
//            "{$this->table}.assignor_id",
//            "{$this->table}.time",
//            "{$this->table}.time_type",
//            "{$this->table}.progress",
//            "{$this->table}.customer_id",
//            "{$this->table}.description",
//            "{$this->table}.approve_id",
//            "{$this->table}.parent_id",
//            "{$this->table}.type_card_work",
//            "{$this->table}.priority",
//            "{$this->table}.manage_status_id",
//            "{$this->table}.repeat_type",
//            "{$this->table}.repeat_end",
//            "{$this->table}.repeat_end_time",
//            "{$this->table}.repeat_end_type",
//            "{$this->table}.repeat_end_full_time",
//            "{$this->table}.repeat_time",
//            "{$this->table}.created_by",
//            "{$this->table}.updated_by",
//            "{$this->table}.created_at",
//            "{$this->table}.updated_at",
//            "{$this->table}.branch_id",
//            'p1.full_name as created_name',
//            'p2.full_name as updated_name',
//            'p3.full_name as approve_name',
//            DB::raw("IF({$this->table}.manage_work_customer_type = 'lead', lead.full_name,IF({$this->table}.manage_work_customer_type = 'deal',deal.deal_name,c1.full_name)) as customer_name"),
//            "type_work.manage_type_work_name",
//            "type_work.manage_type_work_icon",
//            "status.manage_status_name",
//            "staffs.full_name as processor_full_name",
//            'staffs.staff_avatar as processor_avatar',
//            'manage_status_config.is_edit',
//            'manage_status_config.is_deleted',
//            'manage_status_config.manage_color_code',
//            'manage_project_name',
//            'parent_work.manage_work_code as manage_work_parent_code',
//            DB::raw("IFNULL({$this->table}.parent_id,0) as manage_work_parent_name")
//        )
//            ->leftJoin('manage_project as mp', 'mp.manage_project_id', '=', "{$this->table}.manage_project_id")
//            ->leftJoin("manage_type_work as type_work", "type_work.manage_type_work_id", '=', "{$this->table}.manage_type_work_id")
//            ->leftJoin("manage_status as status", "status.manage_status_id", '=', "{$this->table}.manage_status_id")
//            ->leftJoin('manage_work as parent_work','parent_work.manage_work_id',$this->table.'.parent_id')
//            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "{$this->table}.created_by")
//            ->leftJoin('staffs as p2', 'p2.staff_id', '=', "{$this->table}.updated_by")
//            ->leftJoin('staffs as p3', 'p3.staff_id', '=', "{$this->table}.approve_id")
//            ->leftJoin('manage_work_support as p4', function ($sql) {
//                $sql->on('p4.manage_work_id', '=', $this->table . '.manage_work_id');
//            })
//            ->leftJoin('staffs', 'staffs.staff_id', '=', $this->table . '.processor_id')
//
//            ->leftJoin('customers as c1', 'c1.customer_id', '=', "{$this->table}.customer_id")
//            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', '=', "{$this->table}.customer_id")
//            ->leftJoin('cpo_deals as deal', 'deal.deal_id', '=', "{$this->table}.customer_id")
//            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
//            ->where($this->table . '.manage_project_id', $projectId)
//            ->orderBy($this->table . '.manage_status_id', 'ASC')
//            ->orderBy($this->table . '.date_end', 'DESC');
//
//
//        $query->groupBy("{$this->table}.manage_work_id");
//
//        $query = $this->getPermission($query);
//
//        return $query->get();
    }

    public  function getWorkLead($id, $type = 'lead'){
        $mSelect = $this
            -> select(
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_work_customer_type",
                "{$this->table}.manage_status_id",
                "{$this->table}.manage_type_work_id",
                "{$this->table}.manage_work_title"
            )
            ->where("{$this->table}.customer_id",$id)
            ->where("{$this->table}.manage_status_id",'<>', 6)
            ->where("{$this->table}.manage_work_customer_type", $type);
        return $mSelect->get();
    }

    public  function getWorkLeadOverdue($customerLeadId, $type = 'lead'){
        $oSelect = $this
            ->where("{$this->table}.manage_work_customer_type", $type)
            ->where("{$this->table}.customer_id", $customerLeadId)
            ->whereNotIn("{$this->table}.manage_status_id", [6, 7]) 
            ->where("{$this->table}.date_end", '<', Carbon::now());
        return $oSelect->count();
    }
}
