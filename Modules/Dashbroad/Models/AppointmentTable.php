<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 11:13
 */

namespace Modules\Dashbroad\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class AppointmentTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_appointments';
    protected $primaryKey = 'customer_appointment_id';

    protected $fillable = [
        'customer_appointment_id',
        'customer_id',
        'customer_appointment_code',
        'status',
        'created_at',
        'is_deleted',
        'branch_id'];

    public function getAppointment($status)
    {
        $date = Carbon::now()->format('Y-m-d');

        $ds = $this
            ->from($this->table . ' as ca')
            ->leftJoin('customers as cu', 'cu.customer_id', '=', 'ca.customer_id')
            ->leftJoin('branches as br', 'br.branch_id', '=', 'ca.branch_id')
            ->select(
                'cu.full_name as full_name',
                'ca.customer_appointment_id',
                'ca.customer_appointment_code as code',
                'ca.date as date_appointment',
                'ca.status as status',
                'br.branch_name as branch_name',
                'ca.time as time_appointment',
                'ca.customer_quantity as quantity'
            )
//            ->where('ca.status', $status)
            ->whereNotIn('ca.status', ['cancel', 'finish'])
            ->whereDate('ca.date', $date);
        if (Auth::user()->is_admin != 1) {
            $ds->where('ca.branch_id', Auth::user()->branch_id);
        }
        return $ds->count();
    }

    public function appointmentByDate($date)
    {

        $ds = $this->from($this->table . ' as ca')
            ->leftJoin('customers as cu', 'cu.customer_id', '=', 'ca.customer_id')
            ->leftJoin('branches as br', 'br.branch_id', '=', 'ca.branch_id')
            ->select('cu.full_name as full_name',
                'ca.customer_appointment_id',
                'ca.customer_appointment_code as code',
                'ca.date as date_appointment',
                'ca.status as status',
                'br.branch_name as branch_name',
                'ca.time as time_appointment',
                'ca.customer_quantity as quantity'
            )
            ->where('ca.status', 'new')
            ->whereDate('ca.date', $date);

        if (Auth::user()->is_admin != 1) {
            $ds->where('ca.branch_id', Auth::user()->branch_id);
        }
        return $ds->count();
    }

    protected function _getList($filter = [])
    {
        $date = Carbon::now()->format('Y-m-d');

        $ds = $this
            ->from($this->table . ' as ca')
            ->leftJoin('customers as cu', 'cu.customer_id', '=', 'ca.customer_id')
            ->leftJoin('branches as br', 'br.branch_id', '=', 'ca.branch_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'ca.created_by')
            ->select('cu.full_name as full_name',
                'ca.customer_appointment_id',
                'ca.customer_appointment_code as code',
                'staffs.full_name as staff',
                'ca.date as date_appointment',
                'ca.status as status',
                'br.branch_name as branch_name',
                'ca.time as time_appointment',
                'ca.customer_quantity as quantity'
            )
            ->orderBy('time_appointment')
            ->whereIn('ca.status', ['new', 'confirm', 'wait'])
            ->whereDate('ca.date', $date);

        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('cu.full_name', 'like', '%' . $search . '%')
                    ->orWhere('ca.customer_appointment_code', 'like', '%' . strtoupper($search) . '%');
            });
        }
        if (Auth::user()->is_admin != 1) {
            $ds->where('ca.branch_id', Auth::user()->branch_id);
        }
        return $ds;
    }
}