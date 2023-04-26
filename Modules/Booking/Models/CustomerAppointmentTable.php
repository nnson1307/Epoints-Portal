<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/12/2018
 * Time: 10:11 AM
 */

namespace Modules\Booking\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerAppointmentTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_appointments';
    protected $primaryKey = 'customer_appointment_id';
    protected $fillable = [
        'customer_appointment_id', 'customer_id', 'customer_refer', 'date', 'time',
        'description', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'branch_id',
        'customer_appointment_type', 'appointment_source_id', 'customer_quantity', 'customer_appointment_code'
    ];

    /**
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customer_appointments.date as date_appointment',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.time as time_join',
                'customer_appointments.customer_appointment_id as customer_appointment_id');
        if (isset($filter['date']) != "") {
            $arr_filter = explode(" - ", $filter["date"]);

            $from = Carbon::createFromFormat('m/d/Y', $arr_filter[0])->format('Y-m-d');
            $ds->whereDate('customer_appointments.date', $from);
        }
        unset($filter['date']);
        return $ds;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_appointment_id;
    }

    public function listCalendar($day_now)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customer_appointments.date as date_appointment',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'customer_appointments.time as time',
                'customer_appointments.customer_quantity'
//                DB::raw("COUNT(customer_appointments.date) as number")
            )
            ->where('customer_appointments.date', '>=', $day_now);
//            ->groupBy('customer_appointments.date')

        if (Auth::user()->is_admin != 1) {
            $ds->where('customer_appointments.branch_id', Auth::user()->branch_id);
        }
        return $ds->get();
    }

    public function listDayGroupBy($day)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.full_name as full_name_cus',
                DB::raw("COUNT(customer_appointments.date) as number")
            )
            ->where('customer_appointments.date', $day)
            ->groupBy('customer_appointments.date');
        if (Auth::user()->is_admin != 1) {
            $ds->where('customer_appointments.branch_id', Auth::user()->branch_id);
        }
        return $ds->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('appointment_source', 'appointment_source.appointment_source_id', '=', 'customer_appointments.appointment_source_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customers.address as address',
                'customer_appointments.date as date_appointment',
                'customers.customer_avatar as customer_avatar',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'customer_appointments.time as time',
                'customer_appointments.customer_quantity',
                'customer_appointments.description',
                'appointment_source.appointment_source_name',
                'customer_appointments.customer_appointment_type',
                'customer_appointments.customer_appointment_code as customer_appointment_code',
                'customers.phone2 as phone2',
                'customers.birthday as birthday',
                'customers.gender as gender',
                'customer_appointments.branch_id'
            )
            ->where('customer_appointments.customer_appointment_id', $id)
            ->get();
        return $ds;
    }

    public function getItemEdit($id)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customers.customer_id',
                'customers.address as address',
                'customer_appointments.date as date_appointment',
                'customers.customer_avatar as customer_avatar',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'customer_appointments.description as description',
                'member_levels.member_level_id',
                'member_levels.name as member_level_name',
                'member_levels.discount as member_level_discount'
                )
            ->where('customer_appointments.customer_appointment_id', $id)
            ->first();
        return $ds;
    }

    public function getItemRefer($id)
    {
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_refer')
            ->select('customers.full_name as full_name_refer',
                'customers.phone1 as phone')->where('customer_appointments.customer_appointment_id', $id)
            ->first();
        return $ds;
    }

    /**
     * @param $id
     */
    public function getItemServiceDetail($id)
    {
        $ds = $this->leftJoin('appointment_services as app_sv', 'app_sv.customer_appointment_id', '=', 'customer_appointments.customer_appointment_id')
            ->leftJoin('services', 'services.service_id', '=', 'app_sv.service_id')
            ->select('services.service_name as service_name',
                'services.time as time',
                'app_sv.quantity as quantity',
                'app_sv.service_id as service_id',
                'app_sv.appointment_service_id as appointment_service_id',
                'services.price_standard as price',
                'services.service_code')
            ->where('app_sv.customer_appointment_id', $id)
            ->where('app_sv.is_deleted', 0)
            ->get();
        return $ds;
    }

    public function listDay($day)
    {
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customer_appointments.date as date_appointment',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'customer_appointments.time as time',
                'customers.customer_avatar as customer_avatar'
//                DB::raw("COUNT(customers.full_name) as number")
            )
            ->where('customer_appointments.date', $day);
        if (Auth::user()->is_admin != 1) {
            $ds->where('customer_appointments.branch_id', Auth::user()->branch_id);
        }
        $ds->orderBy('customer_appointments.time', 'asc');
        return $ds->get();
    }

    public function listDayStatus($day, $status)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customer_appointments.date as date_appointment',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                DB::raw("COUNT(customer_appointments.date) as number"))
            ->where('customer_appointments.date', $day)
            ->where('customer_appointments.status', $status)
            ->orderBy('customer_appointments.time', 'asc')
            ->groupBy('customer_appointments.date');
        if (Auth::user()->is_admin != 1) {
            $ds->where('customer_appointments.branch_id', Auth::user()->branch_id);
        }
        return $ds->get();
    }

    public function listByTime($time, $day, $id)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('appointment_services', 'appointment_services.customer_appointment_id', '=', 'customer_appointments.customer_appointment_id')
            ->leftJoin('services', 'services.service_id', '=', 'appointment_services.service_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customer_appointments.date as date_appointment',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'services.service_name as service_name')
            ->where('customer_appointments.time', $time)
            ->where('customer_appointments.date', $day)
            ->where('appointment_services.customer_appointment_id', $id)
            ->where('appointment_services.is_deleted', 0)
            ->orderBy('customer_appointments.time', 'asc')
//            ->groupBy('services.service_name')
            ->get();
        return $ds;
    }

    public function listTimeSearch($time, $day)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('appointment_services', 'appointment_services.customer_appointment_id', '=', 'customer_appointments.customer_appointment_id')
            ->leftJoin('services', 'services.service_id', '=', 'appointment_services.service_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customer_appointments.date as date_appointment',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'services.service_name as service_name')
            ->where('customer_appointments.time', $time)
            ->where('customer_appointments.date', $day)
            ->orderBy('cus_time.time', 'asc')
//            ->groupBy('services.service_name')
            ->get();
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('customer_appointment_id', $id)->update($data);
    }

    public function listNameSearch($search, $day)
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.full_name as full_name_cus',
                'customers.phone1 as phone1',
                'customer_appointments.date as date_appointment',
                'customer_appointments.created_at as created_at',
                'customer_appointments.customer_refer as customer_refer',
                'customer_appointments.status as status',
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'customer_appointments.time as time',
                'customers.customer_avatar as customer_avatar')
            ->where(function ($query) use ($search) {
                $query->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $search . '%')
                    ->orWhere('customer_appointments.customer_appointment_code', 'like', '%' . $search . '%');
            })
            ->where('customer_appointments.date', $day)
            ->orderBy('customer_appointments.time', 'asc');
        if (Auth::user()->is_admin != 1) {
            $ds->where('customer_appointments.branch_id', Auth::user()->branch_id);
        }
        return $ds->get();
    }

    public function detailDayCustomer($id)
    {
        $ds = $this->select('date')
            ->where('customer_id', $id)
            ->groupBy(DB::raw('Date(date)'))
            ->get();
        return $ds;
    }

    public function detailCustomer($day, $id)
    {
        $ds = $this->leftJoin('branches', 'branches.branch_id', '=', 'customer_appointments.branch_id')
            ->select('customer_appointments.customer_appointment_id',
                'customer_appointments.date', 'customer_appointments.time',
                'customer_appointments.status', 'customer_appointments.customer_quantity')
            ->where(DB::raw('Date(customer_appointments.date)'), $day)
            ->where('customer_appointments.customer_id', $id)
            ->orderBy('customer_appointments.date', 'DESC')
            ->orderBy('customer_appointments.time', 'DESC')->get();
        return $ds;
    }

    /**
     * @param $year
     * @param $status
     * @param $branch
     * @return mixed
     * Thống kê lịch hẹn theo năm hiện tại của tất cả chi nhánh
     */
    public function reportYearAllBranch($year, $status, $branch)
    {
        $ds = $this->select('date', DB::raw('count(date) as number'))
            ->whereYear('date', '=', $year)
            ->where('status', $status);
        if ($branch != null) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     * Thống kê nguồn lịch hẹn theo chi nhánh
     */
    public function reportAppointmentSource($year, $branch)
    {
        $ds = $this->leftJoin('appointment_source', 'appointment_source.appointment_source_id', '=',
            'customer_appointments.appointment_source_id')
            ->select('appointment_source.appointment_source_name',
                DB::raw('count(customer_appointments.appointment_source_id) as number_appointment_source'))
            ->whereYear('customer_appointments.date', '=', $year);
        if ($branch != null) {
            $ds->where('customer_appointments.branch_id', $branch);
        }
        return $ds->groupBy('customer_appointments.appointment_source_id')->get();
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     * Thống kê giới tính khách hàng theo chi nhánh
     */
    public function reportGenderBranch($year, $branch)
    {
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.gender', DB::raw('count(customers.gender) as number'))
            ->whereYear('customer_appointments.date', '=', $year);
        if ($branch != null) {
            $ds->where('customer_appointments.branch_id', $branch);
        }
        return $ds->groupBy('customers.gender')->get();
    }

    public function reportCustomerSourceBranch($year, $branch)
    {
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('customer_sources', 'customer_sources.customer_source_id', '=', 'customers.customer_source_id')
            ->select('customer_sources.customer_source_name',
                DB::raw('count(customer_sources.customer_source_id) as number'))
            ->whereYear('customer_appointments.date', '=', $year);
        if ($branch != null) {
            $ds->where('customer_appointments.branch_id', $branch);
        }
        return $ds->groupBy('customer_sources.customer_source_id')->get();
    }

    /**
     * @param $year
     * @param $month
     * @param $status
     * @param $branch
     * @return mixed
     * Thống kê lịch hẹn theo năm, tháng theo chi nhánh
     */
    public function reportMonthYearBranch($year, $month, $status, $branch)
    {
        $ds = $this->select('date', DB::raw('count(date) as number'))
            ->whereYear('date', '=', $year)->whereMonth('date', '=', $month)
            ->where('status', $status);
        if ($branch != null) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

    /**
     * @param $time
     * @param $status
     * @param $branch
     * @return mixed
     * Thống kê từ ngày đến ngày theo tất cả chi nhánh
     */
    public function reportTimeAllBranch($time, $status, $branch)
    {
        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $ds = $this->select('date', DB::raw('count(date) as number'))
            ->whereBetween('date', [$startTime, $endTime])
            ->where('status', $status);
        if ($branch != null) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     * Thống kê nguồn lịch hẹn theo chi nhánh
     */
    public function reportTimeAppointmentSource($time, $branch)
    {
        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $ds = $this->leftJoin('appointment_source', 'appointment_source.appointment_source_id', '=',
            'customer_appointments.appointment_source_id')
            ->select('appointment_source.appointment_source_name',
                DB::raw('count(customer_appointments.appointment_source_id) as number_appointment_source'))
            ->whereBetween('date', [$startTime, $endTime]);
        if ($branch != null) {
            $ds->where('customer_appointments.branch_id', $branch);
        }
        return $ds->groupBy('customer_appointments.appointment_source_id')->get();
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     * Thống kê giới tính khách hàng theo chi nhánh
     */
    public function reportTimeGenderBranch($time, $branch)
    {
        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.gender', DB::raw('count(customers.gender) as number'))
            ->whereBetween('customer_appointments.date', [$startTime, $endTime]);
        if ($branch != null) {
            $ds->where('customer_appointments.branch_id', $branch);
        }
        return $ds->groupBy('customers.gender')->get();
    }

    public function reportTimeCustomerSourceBranch($time, $branch)
    {
        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('customer_sources', 'customer_sources.customer_source_id', '=', 'customers.customer_source_id')
            ->select('customer_sources.customer_source_name',
                DB::raw('count(customer_sources.customer_source_id) as number'))
            ->whereBetween('customer_appointments.date', [$startTime, $endTime]);
        if ($branch != null) {
            $ds->where('customer_appointments.branch_id', $branch);
        }
        return $ds->groupBy('customer_sources.customer_source_id')->get();
    }

    /**
     * @param $date
     * @param $status
     * @param $branch
     * @return mixed
     * Thống kê từ ngày đến ngày theo 1 chi nhánh
     */
    public function reportDateBranch($date, $status, $branch)
    {
//        if (Auth::user()->is_admin != 1) {
//            $branch = Auth::user()->branch_id;
//        }
        $ds = $this->select('date', DB::raw('count(date) as number'))
            ->where('date', '=', $date)
            ->where('status', $status);
        if ($branch != null) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

//    public function getNewAppointments()
//    {
//
//    }
    //Lất tất cả lịch hẹn của hôm nay.
    public function getCustomerAppointmentTodays()
    {
        $select = $this->select(
            'customer_appointments.customer_appointment_id as customer_appointment_id',
            'customers.full_name as full_name_cus',
            'customers.phone1 as phone1',
            'customer_appointments.date as date_appointment',
            'customer_appointments.created_at as created_at',
            'customer_appointments.time as time',
            'customer_appointments.customer_appointment_code as customer_appointment_code',
            'customers.phone2 as phone2',
            'customers.gender as gender',
            'customer_appointments.date as date'
        )
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->where('customer_appointments.status', 'confirm')
            ->where('date', date('Y-m-d'))->get();
        return $select;
    }

    //search dashboard
    public function searchDashboard($keyword)
    {
        $time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->subDays(30))->format('Y-m-d');
        $select = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select(
                'customer_appointments.customer_appointment_id as customer_appointment_id',
                'customer_appointments.customer_appointment_code as customer_appointment_code',
                'customers.full_name as full_name',
                'customers.phone1 as phone1',
                'customer_appointments.status as status',
                'customer_appointments.date as date',
                'customer_appointments.time as time',
                'customers.customer_avatar as customer_avatar'
            )
            ->where(function ($query) use ($keyword) {
                $query->where('customers.full_name', 'like', '%' . $keyword . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $keyword . '%')
                    ->orWhere('customers.email', 'like', '%' . $keyword . '%')
                    ->orWhere('customer_appointments.customer_appointment_code', 'like', '%' . $keyword . '%');
            })
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1)
            ->where('customer_appointments.status', '<>', 'cancel')
            ->where('customer_appointments.created_at', '>', $time . ' 00:00:00')
            ->orderBy('customer_appointments.date', 'desc');
        if (Auth::user()->is_admin != 1) {
            $select->where('customer_appointments.branch_id', Auth::user()->branch_id);
        };
        return $select->get();

    }

    public function reportTimeGenderBranch2($time, $branch)
    {
        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->select('customers.gender', DB::raw('count(customer_appointments.customer_appointment_id) as number'))
            ->whereBetween('customer_appointments.date', [$startTime, $endTime]);
        if ($branch != null) {
            $ds->where('customer_appointments.branch_id', $branch);
        }
        return $ds->groupBy('customers.gender')->get();
    }

    protected function _getListCancel($filter = [])
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('appointment_source', 'appointment_source.appointment_source_id', '=', 'customer_appointments.appointment_source_id')
            ->select(
                'customers.full_name as full_name',
                'customers.phone1 as phone1',
                'customer_appointments.date',
                'customer_appointments.status',
                'customer_appointments.time',
                'customer_appointments.customer_appointment_id',
                'customer_appointments.customer_appointment_code as customer_appointment_code',
                'customer_appointments.customer_appointment_type as customer_appointment_type',
                'customer_appointments.customer_quantity',
                'customer_appointments.branch_id',
                'appointment_source.appointment_source_name')
            ->where('customer_appointments.branch_id', Auth::user()->branch_id)
            ->where('customer_appointments.status', 'cancel')
            ->orderBy('customer_appointments.date', 'customer_appointments.time', 'asc');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query
                    ->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $search . '%')
                    ->orWhere('customer_appointments.customer_appointment_code', 'like', '%' . $search . '%');
            });
        }
        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('customer_appointments.date', [$startTime, $endTime]);
        }
        return $ds;
    }

    public function getListCancel(array $filter = [])
    {
        $select = $this->_getListCancel($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword'])) {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display'],
            $filter['search'], $filter["created_at"], $filter["birthday"]);

        // filter list
        foreach ($filter as $key => $val) {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function _getListLate($filter = [])
    {
        $ds = $this
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->leftJoin('appointment_source', 'appointment_source.appointment_source_id', '=', 'customer_appointments.appointment_source_id')
            ->select(
                'customers.full_name as full_name',
                'customers.phone1 as phone1',
                'customer_appointments.date',
                'customer_appointments.status',
                'customer_appointments.time',
                'customer_appointments.customer_appointment_id',
                'customer_appointments.customer_appointment_code as customer_appointment_code',
                'customer_appointments.customer_appointment_type as customer_appointment_type',
                'customer_appointments.customer_quantity',
                'customer_appointments.branch_id',
                'appointment_source.appointment_source_name')
            ->where('customer_appointments.branch_id', Auth::user()->branch_id)
            ->whereIn('customer_appointments.status', ['new', 'confirm', 'finish', 'wait'])
            ->whereDate('customer_appointments.date', '<=', date('Y-m-d'))
            ->whereTime('customer_appointments.time', '<=', date('H:i'))
            ->orderBy('customer_appointments.date', 'desc');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query
                    ->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $search . '%')
                    ->orWhere('customer_appointments.customer_appointment_code', 'like', '%' . $search . '%');
            });
        }
        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('customer_appointments.date', [$startTime, $endTime]);
        }
        return $ds;
    }

    public function getListLate(array $filter = [])
    {
        $select = $this->_getListLate($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword'])) {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display'],
            $filter['search'], $filter["created_at"], $filter["birthday"]);

        // filter list
        foreach ($filter as $key => $val) {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function checkNumberAppointment($customer_id, $date, $type)
    {
        $ds = $this->select('customer_appointment_id',
            'customer_id',
            'appointment_source_id',
            'customer_appointment_type',
            'date',
            'time',
            'customer_quantity',
            'description',
            'status')
            ->where(function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->where('date', $date)
            ->whereNotIn('status', ['finish', 'cancel']);
        if ($type == 'check') {
            $ds->orderBy('time', 'asc');
        }
        if ($type == 'update') {
            $ds->orderBy('customer_appointment_id', 'desc');
        }
        return $ds->get();
    }

    /**
     * Danh sách KH từ ngày .... now
     * @param $day
     * @return mixed
     */
    public function getCustomerAppointmentDayTo($day)
    {
        $select = $this->select(
            'customer_appointments.customer_id as customer_id'
        )
            ->join('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1)
            ->where('date','>', $day)->get();
        return $select;
    }

    /**
     * Lấy danh sách KH theo trạng thái lịch hẹn
     * @param $status
     * @return mixed
     */
    public function getCustomerAppointmentByStatus($status)
    {
        $select = $this->select(
            'customer_appointments.customer_id as customer_id'
        )
            ->join('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1)
            ->where('status',$status)->get();
        return $select;
    }

    /**
     *
     * @param $day
     * @return mixed
     */
    public function getCustomerAppointmentByTime($timeFrom, $timeTo)
    {
        $select = $this->select(
            'customer_appointments.customer_id as customer_id',
            'time'
        )
            ->join('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
            ->whereBetween('customer_appointments.time', [$timeFrom, $timeTo])
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1)
            ->get();
        return $select;
    }
}
