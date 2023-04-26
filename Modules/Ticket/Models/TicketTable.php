<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\Ticket\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class TicketTable extends Model
{
    use ListTableTrait;

    protected $table = 'ticket';
    protected $primaryKey = 'ticket_id';

    protected $fillable = ['ticket_id', 'ticket_code', 'localtion_id', 'ticket_type', 'ticket_issue_group_id',
        'ticket_issue_id', 'issule_level', 'priority', 'title', 'description', 'date_issue', 'date_estimated',
        'date_expected', 'date_finished', 'found_by', 'customer_id', 'customer_address', 'queue_process_id',
        'ticket_status_id', 'staff_notification_id', 'image', 'date_request',
        'operate_by', 'alert_time', 'platform', 'created_by', 'updated_by', 'count_opened', 'created_at', 'updated_at'];

    public function staff_created()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable', 'created_by', 'staff_id');
    }

    public function issue_group()
    {
        return $this->belongsTo('Modules\Ticket\Models\RequestGroupTable', 'ticket_type', 'ticket_issue_group_id');
    }

    public function issue()
    {
        return $this->belongsTo('Modules\Ticket\Models\RequestTable', 'ticket_issue_id', 'ticket_issue_id');
    }

    public function queue()
    {
        return $this->belongsTo('Modules\Ticket\Models\QueueTable', 'queue_process_id', 'ticket_queue_id');
    }

    public function status()
    {
        return $this->belongsTo('Modules\Ticket\Models\TicketStatusTable', 'ticket_status_id', 'ticket_status_id');
    }

    public function file()
    {
        return $this->hasMany('Modules\Ticket\Models\TicketFileTable', 'ticket_id', 'ticket_id')->where('type', 'file');
    }

    public function images()
    {
        return $this->hasMany('Modules\Ticket\Models\TicketFileTable', 'ticket_id', 'ticket_id')->where('type', 'image');
    }

    public function processor()
    {
        return $this->hasMany('Modules\Ticket\Models\TicketProcessorTable', 'ticket_id', 'ticket_id');
    }

    public function operate()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable', 'operate_by', 'staff_id');
    }

    public function rating()
    {
        return $this->hasOne('Modules\Ticket\Models\TicketRatingTable', 'ticket_id', 'ticket_id')->leftJoin("staffs as staff", "staff.staff_id", '=', "ticket_rating.created_by")->select('ticket_rating.*', 'staff.full_name as full_name_rating');
    }

    public function history()
    {
        return $this->hasMany('Modules\Ticket\Models\TicketHistoryTable', 'ticket_id', 'ticket_id')->leftJoin("staffs as staff", "staff.staff_id", '=', "ticket_history.created_by")
            ->select('ticket_history.*', 'staff.full_name as full_name_history')->orderBy('ticket_history.created_at', 'desc');
    }

    public function checkAcceptanceStatus()
    {
        $oData = $this->belongsTo('Modules\Ticket\Models\AcceptanceTable', 'ticket_id', 'ticket_id')
            ->select('ticket_acceptance.status')->first();
        return (isset($oData->status) ? $oData->status : '');
    }

    //  phân quyền admin & user thường
    private function permission($query, $check_permission = true, $arrQueueStaff = [])
    {
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        // lấy danh sách ticket có nhân viên xử lý là user
        $user_id = \Auth::id();
        if ($check_permission) {
            if (!\Auth::user()->is_admin) {
                $query = $query->where(function ($query) use ($list_processor, $user_id, $arrQueueStaff) {
                    $query->orWhere("{$this->table}.created_by", $user_id)
                        ->orWhereIn("{$this->table}.ticket_id", $list_processor)
                        ->orWhere("{$this->table}.operate_by", $user_id)
                        ->orWhere("{$this->table}.staff_notification_id", $user_id)
                        ->orWhereIn("{$this->table}.queue_process_id", $arrQueueStaff);
                });
            }
        } else {
            $query = $this->where(function ($query) use ($list_processor, $user_id, $arrQueueStaff) {
                $query->whereIn("{$this->table}.ticket_id", $list_processor)
                    ->orWhere("{$this->table}.operate_by", $user_id)
                    ->orWhere("{$this->table}.staff_notification_id", $user_id)
                    ->orWhereIn("{$this->table}.queue_process_id", $arrQueueStaff);
            });
        }
        /* 
        - Phân quyền theo 
            + Cá nhân , 
            + Theo queue ,
            + Theo phòng ,
            + Theo chi nhánh ,
            + tất cả ,
        */
        return $query;
    }

    public function filterWhere($filters = [], $query)
    {
        // filter theo nhân viên xử lý
        if (isset($filters["processor_by"]) && $filters["processor_by"] != '') {
            // lấy danh sách ticket được assign
            $list_processor = \DB::table('ticket_processor')->where('process_by', $filters["processor_by"])->groupBy('ticket_id')->pluck('ticket_id')->toArray();
            $query->whereIn("{$this->table}.ticket_id", $list_processor);
        }
        if (isset($filters["operate_by"])) {
            $query->where("{$this->table}.operate_by", $filters["operate_by"]);
        }
        // filter theo queue
        if (isset($filters["queue_process_id"])) {
            if (is_array($filters["queue_process_id"])) {
                $query->whereIn("{$this->table}.queue_process_id", $filters["queue_process_id"]);
            } else {
                $query->where("{$this->table}.queue_process_id", $filters["queue_process_id"]);
            }
        }
        // filter theo localtion
        if (isset($filters["localtion_id"])) {
            $query->where("localtion_id", $filters["localtion_id"]);
        }
        // filter theo handle_by
        if (isset($filters["handle_by"])) {
            // lấy danh sách ticket có nhân viên xử lý cần tìm
            $list_processor = \DB::table('ticket_processor')->where('process_by', $filters["handle_by"])->groupBy('ticket_id')->pluck('ticket_id')->toArray();
            $query->WhereIn("{$this->table}.ticket_id", $list_processor);
        }
        // filter theo priority
        if (isset($filters["priority"]) && $filters["priority"] != '') {
            $query->where("{$this->table}.priority", $filters["priority"]);
        }
        // ticket chưa hoàn thành
        if (isset($filters["ticket_not_done"]) && $filters["ticket_not_done"] != '') {
            $arr_status = [1, 2, 6, 7];
            $query->WhereIn("{$this->table}.ticket_status_id", $arr_status);
            unset($filters['ticket_not_done']);
        }
        // filters tên
        if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where(function ($query) use ($search) {
                $query->where("{$this->table}.ticket_code", "like", "%" . $search . "%")
                    ->orwhere("{$this->table}.title", "like", "%" . $search . "%");
            });
        }
        // filters loai
        if (isset($filters["ticket_type"]) && $filters["ticket_type"] != "") {
            $query->where("{$this->table}.ticket_type", $filters["ticket_type"]);
        }
        if (isset($filters["ticket_issue_group_id"]) && $filters["ticket_issue_group_id"] != "") {
            $query->where("{$this->table}.ticket_type", $filters["ticket_issue_group_id"]);
        }
        // filters y/c
        if (isset($filters["ticket_issue_id"]) && $filters["ticket_issue_id"] != "") {
            $query->where("{$this->table}.ticket_issue_id", $filters["ticket_issue_id"]);

        }
        // filters cap do su co
        if (isset($filters["issule_level"]) && $filters["issule_level"] != "") {
            $query->where("{$this->table}.issule_level", $filters["issule_level"]);
        }
        // filters staff_notification
        if (isset($filters["staff_notification_id"]) && $filters["staff_notification_id"] != "") {
            $query->where("{$this->table}.staff_notification_id", $filters["staff_notification_id"]);
        }
        // filters status
        if (isset($filters["ticket_status_id"]) && $filters["ticket_status_id"] != "") {
            if ($filters["ticket_status_id"] == 7) {
//                #[1,2] mới đang xử lý và ngày bắt buộc hoàn thành < ngày hiện tại
//                #[3] hoàn thất và ngày hoàn thành < ngày hiện tại
                $query->where(function ($query) {
                    $query->where(function ($query) {
                        $query->whereIn("{$this->table}.ticket_status_id", [1, 2])
                            ->whereDate("{$this->table}.date_expected", '<', Carbon::today());
                    })->orWhere(function ($query) {
                        $query->whereIn("{$this->table}.ticket_status_id", [3])
                            ->where("{$this->table}.date_finished", '>', "{$this->table}.date_expected");
                    });
                });
            } else {
                $query->where("{$this->table}.ticket_status_id", $filters["ticket_status_id"]);
            }
        }
        // filters created_by
        if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("{$this->table}.created_by", $filters["created_by"]);
        }
        // filter date issue
        if (isset($filters["date_issue"]) && $filters["date_issue"] != "") {
            $arr_filter = explode(" - ", $filters["date_issue"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.date_issue", ">=", $startTime);
            $query->whereDate("{$this->table}.date_issue", "<=", $endTime);
        }
        // filter date estimated
        if (isset($filters["date_estimated"]) && $filters["date_estimated"] != "") {
            $arr_filter = explode(" - ", $filters["date_estimated"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.date_estimated", ">=", $startTime);
            $query->whereDate("{$this->table}.date_estimated", "<=", $endTime);
        }
        // filter date date_expected
        if (isset($filters["date_expected"]) && $filters["date_expected"] != "") {
            $arr_filter = explode(" - ", $filters["date_expected"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.date_expected", ">=", $startTime);
            $query->whereDate("{$this->table}.date_expected", "<=", $endTime);
        }
        if (isset($filters["created_at"])) {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        /*
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $query = $this->permissionView($query);
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $query->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }
        return $query;
    }

    protected function _getList($filters = [])
    {
        $query = $this->select('ticket_id', 'ticket_code', 'localtion_id', 'ticket_type', 'ticket_issue_group_id',
            'ticket_issue_id', 'issule_level', 'priority', 'title', 'description', 'date_issue', 'date_estimated',
            'date_expected', 'date_finished', 'found_by', 'customer_id', 'customer_address', 'queue_process_id', 'ticket_status_id',
            'staff_notification_id', 'image',
            'operate_by', 'alert_time', 'platform', 'created_by', 'updated_by', 'count_opened', 'created_at', 'updated_at')
            ->orderBy('priority', 'ASC')
            ->orderBy($this->primaryKey, 'desc');
        $query = $this->filterWhere($filters, $query);
        return $query;
    }

    public function getTicketList($filters = [])
    {
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $query = $this
            ->select(
                "{$this->table}.ticket_id",
                "{$this->table}.ticket_code",
                "{$this->table}.localtion_id",
                "{$this->table}.ticket_type",
                "{$this->table}.ticket_issue_group_id",
                "{$this->table}.ticket_issue_id",
                "{$this->table}.issule_level",
                "{$this->table}.priority",
                "{$this->table}.title",
                "{$this->table}.description",
                "{$this->table}.date_issue",
                "{$this->table}.date_estimated",
                "{$this->table}.date_expected",
                "{$this->table}.date_finished",
                "{$this->table}.found_by",
                "{$this->table}.customer_id",
                "{$this->table}.customer_address",
                "{$this->table}.queue_process_id",
                "{$this->table}.ticket_status_id",
                "{$this->table}.staff_notification_id",
                "{$this->table}.image",
                "{$this->table}.operate_by",
                "{$this->table}.alert_time",
                "{$this->table}.platform",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.count_opened",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                DB::raw("(CASE WHEN (({$this->table}.ticket_status_id in (1,2) and {$this->table}.date_expected < NOW()) or ({$this->table}.ticket_status_id in (3,4) and {$this->table}.date_finished > {$this->table}.date_expected)) THEN 1 ELSE 0 END) as is_overtime"),
                DB::raw("(GROUP_CONCAT(p.process_by)) as process_by")
            )
            ->leftJoin("ticket_processor as p", "p.ticket_id", "=", "{$this->table}.ticket_id")
            ->groupBy("{$this->table}.ticket_id");

        if (isset($filters['sort_priority']) && $filters['sort_priority'] != "") {
            $query->orderBy('priority', $filters['sort_priority']);
            unset($filters['sort_priority']);
        } else {
            $query->orderBy($this->primaryKey, 'desc');
        }

        $query = $this->filterWhere($filters, $query);
        if (isset($filters["time"])) {
            $arr_filter = explode(" - ", $filters["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * ds ticket hiển thị ở popup khi khách hàng gọi đến
     *
     * @param $customerId
     * @return mixed
     */
    public function getListTicketFromOncall($customerId)
    {
        $query = $this->select(
            "ticket.ticket_id",
            "ticket.ticket_code",
            "ticket.title",
            "ticket_issue_group.name as ticket_request_type_name",
            "ticket_issue.name as ticket_issue_name",
            "staffs.full_name as creator",
            "ticket_status.ticket_status_id",
            "ticket_status.status_name as ticket_status_name",
            "ticket.created_at"
        )
            ->join("ticket_issue_group", "ticket_issue_group.ticket_issue_group_id", "ticket.ticket_type")
            ->join("ticket_issue", "ticket_issue.ticket_issue_id", "ticket.ticket_issue_id")
            ->join("ticket_status", "ticket_status.ticket_status_id", "ticket.ticket_status_id")
            ->leftJoin("staffs", "staffs.staff_id", "ticket.created_by")
            ->where("customer_id", $customerId);

        $query->orderBy($this->primaryKey, 'desc');
        return $query->get();
    }

    public function getAll()
    {
        return $this->orderBy('priority', 'ASC')->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName()
    {
        $oSelect = self::select("ticket_id", "ticket_code")->get();
        return ($oSelect->pluck("ticket_code", "ticket_id")->toArray());
    }

    public function getTicketCode()
    {
        // lấy danh sách ticket đang xử lý
        $listStatus = [2];
        $oSelect = self::select("ticket_id", "ticket_code");
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $oSelect = $this->permissionView($oSelect);
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $oSelect->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }
        $oSelect->whereIn('ticket_status_id', $listStatus)->get();
        return ($oSelect->pluck("ticket_code", "ticket_id")->toArray());
    }

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this
            ->select(
                "ticket.*",
                "c.full_name as customer_name",
                "c.phone1 as customer_phone",
                DB::raw("(GROUP_CONCAT(p.process_by)) as process_by")
            )
            ->join("customers as c", "c.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("ticket_processor as p", "p.ticket_id", "=", "{$this->table}.ticket_id")
            ->where("{$this->table}.ticket_id", $id)
            ->groupBy("{$this->table}.ticket_id")
            ->first();
    }

    public function getTicketByStatus($status = [], $filters = [])
    {
        $user_id = \Auth::id();
        $query = $this->select('*');
        $query = $this->permissionView($query);
        // (Mới, đang xử lý, hoàn tất, quá hạn)

        $list_status = [1, 2, 3, 6, 7];
        $check_expired = in_array(7, $status);
        $query = $this->filterWhere($filters, $query);
        if (isset($filters["created_filter"]) && $filters["created_filter"] != "") {
            $time = Carbon::createFromFormat('d/m/Y', $filters["created_filter"])->format('Y-m-d');
            $query->whereBetween('created_at', [$time . ' 00:00:00', $time . ' 23:59:59']);
        }
        if ($status == [6]) {
            return $query->where("{$this->table}.count_opened", '!=', 0)->get()->count();
        }
        #kiểm tra quá hạn
        if ($check_expired && $status != [1, 2, 3, 4, 5, 6, 7]) {
//            #[1,2] mới đang xử lý và ngày bắt buộc hoàn thành < ngày hiện tại
//            #[3] hoàn thất và ngày hoàn thành < ngày hiện tại
            $query = $query->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn("{$this->table}.ticket_status_id", [1, 2])
                        ->whereDate("{$this->table}.date_expected", '<', Carbon::today());
                })->orWhere(function ($query) {
                    $query->whereIn("{$this->table}.ticket_status_id", [3])
                        ->where("{$this->table}.date_finished", '>', "{$this->table}.date_expected");
//                    ->whereDate("{$this->table}.date_finished",'>', Carbon::today());
                });
            });
            return $query->whereIn('ticket_status_id', $list_status)->get()->count();
        }
        return $query->whereIn('ticket_status_id', $status)->get()->count();
    }

    public function getNumberTicketAssignMe()
    {
        $query = $this->select('*');
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
//        $query = $this->permission($query);
        $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        // // lấy danh sách ticket có nhân viên xử lý là user
        $user_id = \Auth::id();
        $query = $this->where(function ($query) use ($list_processor, $user_id) {
            $query->whereIn("{$this->table}.ticket_id", $list_processor)
                ->orWhere("{$this->table}.operate_by", $user_id);
        });
        return $query->get()->count();
    }

    // lấy ds ticket của tôi
    public function getTicketAssignMe($filters)
    {
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $query = $this->select('*');
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        // $query = $this->permission($query);
        $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        // lấy danh sách ticket có nhân viên xử lý là user
        $user_id = \Auth::id();
        $query = $this->where(function ($query) use ($list_processor, $user_id) {
            $query->whereIn("{$this->table}.ticket_id", $list_processor)
                ->orWhere("{$this->table}.operate_by", $user_id);
        });

        if (isset($filters['sort_priority']) && $filters['sort_priority'] != "") {
            $query->orderBy('priority', $filters['sort_priority']);
            unset($filters['sort_priority']);
        } else {
            $query->orderBy($this->primaryKey, 'desc');
        }
        // dd($list_processor);

        $query = $this->filterWhere($filters, $query);
        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    // lấy sl ticket tôi tạo
    public function getNumberTicketCreatedByMe()
    {
        $user_id = \Auth::id();
        return $this->where("{$this->table}.created_by", $user_id)->get()->count();
    }

    // lấy ds ticket tôi tạo
    public function getTicketCreatedByMe($filters)
    {
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $user_id = \Auth::id();
        $query = $this;
        $query = $this->filterWhere($filters, $query);
        return $this->where("{$this->table}.created_by", $user_id)->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    // lấy danh sách ticket group theo queue + status
    public function getTicketProcessingList()
    {
        // (Mới, đang xử lý, reopen, quá hạn)
        $list_status = [1, 2, 6, 7];
        $query = $this->select('queue_process_id', 'ticket_status_id', \DB::raw('count(*) as count'));
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $query = $this->permissionView($query);
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $query = $query->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }

        return $query->whereIn('ticket_status_id', $list_status)
            ->groupBy('queue_process_id')->groupBy('ticket_status_id')->get()->toArray();
    }

    public function getTicketProcessingListExpired()
    {
        // (Mới, đang xử lý, reopen, quá hạn)
        $list_status = [1, 2, 3, 6, 7];
        $query = $this->select('queue_process_id', 'ticket_status_id', \DB::raw('count(*) as count'));
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $query = $this->permissionView($query);
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $query = $query->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }
        # kiểm tra quá hạn
        $check_expired = in_array(7, $list_status);
        if ($check_expired) {
            # [1,2] mới đang xử lý và ngày bắt buộc hoàn thành < ngày hiện tại
            # [3] hoàn thất và ngày hoàn thành < ngày hiện tại
            $query = $query->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn("{$this->table}.ticket_status_id", [1, 2])
                        ->whereDate("{$this->table}.date_expected", '<', Carbon::today());
                })->orWhere(function ($query) {
                    $query->whereIn("{$this->table}.ticket_status_id", [3])
                        ->where("{$this->table}.date_finished", '>', "{$this->table}.date_expected");
//                    ->whereDate("{$this->table}.date_finished",'>', Carbon::today());
                });
            });
        }
        return $query->whereIn('ticket_status_id', $list_status)
            ->groupBy('queue_process_id')->groupBy('ticket_status_id')->get()->toArray();
    }

    // lấy danh sách ticket chưa assign
    public function getTicketUnAssign($queue_process_id)
    {
        // (Mới, đang xử lý, hoàn tất, quá hạn)
        $list_status = [1, 2, 3, 7];
        $query = $this->select(
            "{$this->table}.ticket_id",
            "{$this->table}.title",
            "{$this->table}.ticket_code",
            "{$this->table}.ticket_type",
            "{$this->table}.ticket_issue_group_id",
            "{$this->table}.ticket_issue_id",
            "{$this->table}.issule_level",
            "{$this->table}.date_issue",
            "{$this->table}.date_estimated",
            "{$this->table}.date_expected",
            "{$this->table}.date_finished",
            "{$this->table}.queue_process_id",
            "{$this->table}.ticket_status_id",
            "{$this->table}.operate_by",
            "{$this->table}.count_opened",
            "issue.name as issue_name",
            "issue_group.name as issue_group_name")
            ->leftJoin("ticket_issue_group as issue_group", "issue_group.ticket_issue_group_id", '=', "{$this->table}.ticket_type")
            ->leftJoin("ticket_issue as issue", "issue.ticket_issue_id", '=', "{$this->table}.ticket_issue_id");

        $list_processor_in_queue = \DB::table('ticket_staff_queue')->select('staff_id')->where("ticket_queue_id", $queue_process_id)->groupBy('staff_id')->pluck('staff_id')->toArray();

        $list_processor = \DB::table('ticket_processor')->select('ticket_id')->groupBy('ticket_id')->pluck('ticket_id')->toArray();

        // lấy danh sách ticket có nhân viên xử lý là user

        $query = $query->where(function ($query) use ($list_processor, $list_processor_in_queue) {
            $query->whereNotIn("{$this->table}.ticket_id", $list_processor);
            if (!\Auth::user()->is_admin) {
                $query->whereIn("{$this->table}.operate_by", $list_processor_in_queue);
            }
        });
        return $query->whereIn("{$this->table}.ticket_status_id", $list_status)
            ->where("{$this->table}.queue_process_id", $queue_process_id)->get()->toArray();
    }

    public function dataSeries($filters = [])
    {
        $list_status = [1, 2, 3, 4, 5, 6, 7];
        $query = $this->select(
            \DB::raw("DATE_FORMAT({$this->table}.created_at,'%d/%m/%Y') as created_group"),
            "{$this->table}.ticket_status_id",
            \DB::raw("count({$this->table}.ticket_id) as count"),
            \DB::raw("(CASE WHEN (({$this->table}.ticket_status_id in (1,2) and ({$this->table}.date_expected < NOW())) or ({$this->table}.ticket_status_id in (3,4) and ({$this->table}.date_finished > {$this->table}.date_expected))) THEN COUNT({$this->table}.ticket_id) END) as count_overtime")
        );
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $query = $this->filterWhere($filters, $query);
        if (isset($filters["time"])) {
            $arr_filter = explode(" - ", $filters["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $query = $query->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }

//        // filters y/c
//        if (isset($filters["ticket_status_id"]) && $filters["ticket_status_id"] != "") {
//            $query->where("ticket_status_id", $filters["ticket_status_id"]);
//        }
//        if (isset($filters["queue_process_id"]) && $filters["queue_process_id"] != "") {
//            $query->where("queue_process_id", $filters["queue_process_id"]);
//        }
//        if (isset($filters["ticket_type"]) && $filters["ticket_type"] != "") {
//            $query->where("ticket_type", $filters["ticket_type"]);
//        }
//        if (isset($filters["created_by"]) && $filters["created_by"] != "") {
//            $query->where("created_by", $filters["created_by"]);
//        }
        $query = $this->permissionView($query);
        if (isset($filters["time"])) {
            $arr_filter = explode(" - ", $filters["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        $query->groupBy(\DB::raw("DATE_FORMAT(created_at,'%d/%m/%Y')"));
        return $query->groupBy('ticket_status_id')->get()->toArray();
    }

    /*
     * kpi chart
     */
    public function countTicketByProcessor($filters = [])
    {
        $query = $this->select('*')
            ->leftJoin('ticket_processor as processor', 'processor.ticket_id', "{$this->table}.ticket_id");
        // ->leftJoin('staffs as p1', 'p1.staff_id', '=', "ticket_processor.process_by");
        if (isset($filters["time"])) {
            $arr_filter = explode(" - ", $filters["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filters["queue_process_id"]) && $filters["queue_process_id"] != "") {
            $query->where("{$this->table}.queue_process_id", $filters["queue_process_id"]);
        }
        if (isset($filters["ticket_type"]) && $filters["ticket_type"] != "") {
            $query->where("{$this->table}.ticket_type", $filters["ticket_type"]);
        }
        if (isset($filters["process_by"]) && $filters["process_by"] != "") {
            $query->where("processor.process_by", $filters["process_by"]);
        }
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $query = $query->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $query = $this->permissionView($query);
        $query->where("{$this->table}.ticket_status_id", 4);
        return $query->get()->count();
    }

    public function getKPITicket($filters = [])
    {
        $query = $this->select("{$this->table}.ticket_type",
            \DB::raw("count({$this->table}.ticket_id) as count"),
            \DB::raw('round(AVG(rating.point),0) as avg_point'),
            'p1.full_name as full_name', 'processor.process_by as process_by'
        )->leftJoin('ticket_rating as rating', 'rating.ticket_id', "{$this->table}.ticket_id")
            ->leftJoin('ticket_processor as processor', 'processor.ticket_id', "{$this->table}.ticket_id")
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "processor.process_by");
        if (isset($filters["time"])) {
            $arr_filter = explode(" - ", $filters["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filters["queue_process_id"]) && $filters["queue_process_id"] != "") {
            $query->where("{$this->table}.queue_process_id", $filters["queue_process_id"]);
        }
        if (isset($filters["ticket_type"]) && $filters["ticket_type"] != "") {
            $query->where("{$this->table}.ticket_type", $filters["ticket_type"]);
        }
        if (isset($filters["process_by"]) && $filters["process_by"] != "") {
            $query->where("processor.process_by", $filters["process_by"]);
        }
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $query = $this->permissionView($query);
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('ticket_processor.process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $query = $query->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }

        $query->where("processor.process_by", '!=', "");
        $query->where("{$this->table}.ticket_status_id", 4);
        $query->groupBy("processor.process_by");
        return $query->get()->toArray();
    }

    public function getKPITicketTable($filters = [])
    {
        $page = (int)($filters['page'] ?? 15);
        $display = (int)($filters['perpage'] ?? 15);
        $query = $this->select(
            'p1.full_name as full_name', 'processor.process_by as process_by',
            "{$this->table}.queue_process_id",
            \DB::raw("count({$this->table}.ticket_id) as total_ticket"),
            \DB::raw("SUM(
                CASE WHEN (({$this->table}.ticket_status_id in (1,2) 
                and {$this->table}.date_expected < NOW()) 
                or ({$this->table}.ticket_status_id in (3,4) and {$this->table}.date_finished > {$this->table}.date_expected)) 
                THEN 1 ELSE 0 END
            ) AS total_overtime"),
//            \DB::raw("(CASE WHEN {$this->table}.ticket_status_id = 6 THEN count({$this->table}.ticket_id) ELSE 0 END) AS total_reopen"),
//            \DB::raw("SUM({$this->table}.count_opened) AS total_reopen"),
            \DB::raw("SUM(CASE WHEN {$this->table}.count_opened != 0 THEN 1 ELSE 0 END) AS total_reopen"),
            \DB::raw("(TIMESTAMPDIFF(HOUR,{$this->table}.date_expected, {$this->table}.date_issue)) AS total_time_hander"),
            \DB::raw("SUM(p2.process_time) AS total_time_handers"),
            \DB::raw("round(AVG(rating.point),0) as avg_point", 'p1.full_name as full_name')
        )->leftJoin("ticket_rating as rating", "rating.ticket_id", "{$this->table}.ticket_id")
            ->leftJoin('ticket_processor as processor', 'processor.ticket_id', "{$this->table}.ticket_id")
            ->leftJoin('staffs as p1', 'p1.staff_id', '=', "processor.process_by")
            ->leftJoin('ticket_issue as p2', 'p2.ticket_issue_id', '=', "{$this->table}.ticket_issue_id");
        if (isset($filters["time"])) {
            $arr_filter = explode(" - ", $filters["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filters["queue_process_id"]) && $filters["queue_process_id"] != "") {
            $query->where("{$this->table}.queue_process_id", $filters["queue_process_id"]);
        }
        if (isset($filters["ticket_type"]) && $filters["ticket_type"] != "") {
            $query->where("{$this->table}.ticket_type", $filters["ticket_type"]);
        }
        if (isset($filters["process_by"]) && $filters["process_by"] != "") {
            $query->where("processor.process_by", $filters["process_by"]);
        }
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $query = $this->permissionView($query);
        // if (!\Auth::user()->is_admin) {
        //     $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        //     // lấy danh sách ticket có nhân viên xử lý là user
        //     $user_id = \Auth::id();
        //     $query = $query->where(function ($query) use ($list_processor, $user_id) {
        //         $query->orWhere("{$this->table}.created_by", $user_id)
        //             ->orWhereIn("{$this->table}.ticket_id", $list_processor)
        //             ->orWhere("{$this->table}.operate_by", $user_id);
        //     });
        // }
        $query->where("processor.process_by", '!=', "");
        $query->groupBy("processor.process_by");
        $query->where("{$this->table}.ticket_status_id", 4);
        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * //* Lấy danh sách ticket chưa có biên bản nghiệm thu
     */
    public function getListTicketNotAcceptance($ticket_acceptance_id = null)
    {
        $oSelect = $this
            ->select(
                $this->table . '.ticket_id',
                $this->table . '.ticket_code'
            )
            ->leftJoin('ticket_acceptance', 'ticket_acceptance.ticket_id', $this->table . '.ticket_id')
            ->whereNull('ticket_acceptance.ticket_acceptance_id');

        if ($ticket_acceptance_id != null) {
            $oSelect = $oSelect->orWhere('ticket_acceptance.ticket_acceptance_id', $ticket_acceptance_id);
        }
        return $oSelect->orderBy($this->table . '.ticket_id')->get();
    }

    public function getDetailTicket($id)
    {
        return $this
            ->select(
                $this->table . '.ticket_id',
                $this->table . '.ticket_code',
                $this->table . '.customer_id',
                'customers.full_name',
                'customers.phone1'
            )
            ->join('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->where($this->table . '.ticket_id', $id)
            ->first();
    }

    public function ticketDetailByTicket($ticketId)
    {
        return $this
            ->where('ticket_id', $ticketId)
            ->first();
    }

    public function ticketRefundList($id)
    {
        $query = $this->select(
            "{$this->table}.ticket_id",
            "{$this->table}.ticket_code",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "p1.full_name as created_by_full_name",
            \DB::raw("SUM(p3.quantity_return) as sum_quantity_return"),
            \DB::raw("ticket_acceptance_incurred_join_child.sum_price")
        )
            ->leftJoin("staffs as p1", "p1.staff_id", "{$this->table}.created_by")
            ->leftJoin("ticket_request_material as p2", "p2.ticket_id", "{$this->table}.ticket_id")
            ->leftJoin("ticket_request_material_detail as p3", "p3.ticket_request_material_id", "p2.ticket_request_material_id")
            ->leftJoin("product_childs as p4", "p4.product_id", "p3.product_id")
            ->leftJoin("ticket_acceptance as p5", "p5.ticket_id", "{$this->table}.ticket_id")
            ->leftJoin(\DB::raw('(SELECT .ticket_acceptance_incurred.ticket_acceptance_id,SUM(ticket_acceptance_incurred.money) as sum_price FROM `ticket_acceptance_incurred`
            join ticket_acceptance on ticket_acceptance.ticket_acceptance_id = ticket_acceptance_incurred.ticket_acceptance_id
            GROUP BY ticket_acceptance.ticket_id)
            ticket_acceptance_incurred_join_child'),
                function ($join) {
                    $join->on('p5.ticket_acceptance_id', '=', 'ticket_acceptance_incurred_join_child.ticket_acceptance_id');
                })
            ->where("{$this->table}.ticket_status_id", 4);
        // ds sp có phát sinh

        $process_id = \DB::table('ticket_refund')->select('staff_id')->where('ticket_refund_id', $id)->first();
        $process_id = isset($process_id->staff_id) ? $process_id->staff_id : '';
        $list_ticket_acceptance = \DB::table('ticket_acceptance_incurred')->where('quantity', '>', 0)->pluck('ticket_acceptance_id')->toArray();

        // ticket do user xử lý
        $list_processor = \DB::table('ticket_processor')->where('process_by', $process_id)->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        $query = $query->WhereIn("{$this->table}.ticket_id", $list_processor);

        $query = $query->where(function ($query) use ($list_ticket_acceptance) {
            $query->where('p3.quantity_return', '>', 0)
                ->orWhereIn("p5.ticket_acceptance_id", $list_ticket_acceptance);
        });
        $list_ticket_refund_cancle = \DB::table('ticket_refund_map')->leftJoin("ticket_refund as p1", "p1.ticket_refund_id", "ticket_refund_map.ticket_refund_id")->where('p1.status', '!=', 'R')->whereNotIn('ticket_refund_map.ticket_refund_id', [$id])->pluck('ticket_id')->toArray();
        $list_ticket_refund_this = \DB::table('ticket_refund_map')->leftJoin("ticket_refund as p1", "p1.ticket_refund_id", "ticket_refund_map.ticket_refund_id")->where('p1.status', 'D')->whereIn('ticket_refund_map.ticket_refund_id', [$id])->pluck('ticket_id')->toArray();

        $query = $query->whereNotIn("{$this->table}.ticket_id", $list_ticket_refund_cancle);
        $query = $query->orWhereIn("{$this->table}.ticket_id", $list_ticket_refund_this);
        $query = $query->groupBy("{$this->table}.ticket_id");
        $query = $query->orderBy("{$this->table}.ticket_id", 'desc')->get();
        return $query;
    }

    public function loadTicketRefundDetail($id)
    {
        $query = $this->select(
            "{$this->table}.ticket_id",
            "{$this->table}.ticket_code",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "p1.full_name as created_by_full_name"
        )
            ->leftJoin("staffs as p1", "p1.staff_id", "{$this->table}.created_by")
            ->join("ticket_request_material as p2", "p2.ticket_id", "{$this->table}.ticket_id")
            ->join("ticket_request_material_detail as p3", "p3.ticket_request_material_id", "p2.ticket_request_material_id")
            ->join("product_childs as p4", "p4.product_id", "p3.product_id")
            ->where('p3.quantity_return', '>', 0)
            ->where("{$this->table}.ticket_id", $id)->get();
        return $query;
    }

    /**
     * Lấy ds ticket khi export excel
     *
     * @param array $filters
     * @return mixed
     */
    public function getDataExportExcel($filters = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.ticket_id",
                "{$this->table}.ticket_code",
                "{$this->table}.localtion_id",
                "{$this->table}.ticket_type",
                "{$this->table}.ticket_issue_group_id",
                "{$this->table}.ticket_issue_id",
                "{$this->table}.issule_level",
                "{$this->table}.priority",
                "{$this->table}.title",
                "{$this->table}.description",
                "{$this->table}.date_issue",
                "{$this->table}.date_estimated",
                "{$this->table}.date_expected",
                "{$this->table}.date_finished",
                "{$this->table}.found_by",
                "{$this->table}.customer_id",
                "{$this->table}.customer_address",
                "{$this->table}.queue_process_id",
                "{$this->table}.ticket_status_id",
                "{$this->table}.staff_notification_id",
                "{$this->table}.image",
                "{$this->table}.operate_by",
                "{$this->table}.alert_time",
                "{$this->table}.platform",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.count_opened",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "cs.full_name as customer_name",
                "cs.phone1 as customer_phone",
                "ig.name as issue_group_name",
                "i.name as issue_name",
                "i.level as issue_level",
                "q.queue_name",
                "s.status_name",
                "sf.full_name as operate_name"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->join("ticket_issue as i", "i.ticket_issue_id", "=", "{$this->table}.ticket_issue_id")
            ->join("ticket_issue_group as ig", "ig.ticket_issue_group_id", "=", "i.ticket_issue_group_id")
            ->join("ticket_queue as q", "q.ticket_queue_id", "=", "{$this->table}.queue_process_id")
            ->join("ticket_status as s", "s.ticket_status_id", "=", "{$this->table}.ticket_status_id")
            ->leftJoin("staffs as sf", "sf.staff_id", "=", "{$this->table}.operate_by")
            ->orderBy("{$this->table}.priority", 'ASC')
            ->orderBy("{$this->table}.ticket_id", 'desc');

        $ds = $this->filterWhere($filters, $ds);

        return $ds->get();
    }

    function permissionView($oSelect)
    {

        if (!\Auth::user()->is_admin) {
            $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
            $tiketQueue = \DB::table('ticket_staff_queue')
                ->join('ticket_staff_queue_map', 'ticket_staff_queue_map.ticket_staff_queue_id', 'ticket_staff_queue.ticket_staff_queue_id')
                ->where('ticket_staff_queue.staff_id',  \Auth::id())->get();
            $arrQueue = [];

            foreach ($tiketQueue as $objQueue) {

                $arrQueue[] = $objQueue->ticket_queue_id;
            }
            $oSelect = $oSelect->where(function ($oSelect) use ($list_processor, $arrQueue) {
                $oSelect->orWhere("{$this->table}.created_by", \Auth::id())
                    ->orWhere("{$this->table}.operate_by", \Auth::id())
                    ->orWhereIn("{$this->table}.ticket_id", $list_processor)
                    ->orWhere("{$this->table}.staff_notification_id", \Auth::id());
                if (count($arrQueue) > 0) {
                    $oSelect->orWhereIn("{$this->table}.queue_process_id", $arrQueue);
                }
            });
        }
        return $oSelect;
    }
}