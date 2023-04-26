<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 11:13
 */

namespace Modules\Dashbroad\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class CustomerTable extends Model
{

    use ListTableTrait;
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_id',
        'created_at',
        'is_deleted'];

    public function getTotal()
    {
        $oSelect = $this->where($this->primaryKey, '<>', 1)->where('customer_id', '<>', 1);
        if (Auth::user()->is_admin != 1) {
            return $oSelect->where('customers.branch_id', Auth::user()->branch_id)
                ->where('is_deleted', 0)->count();
        } else {
            return $oSelect->where('is_deleted', 0)->count();
        }
    }
    public function getTotalOnDay()
    {
        $day = Carbon::now()->format('Y-m-d');

        $select = $this
            ->whereDate('created_at', $day)
            ->where('is_deleted', 0)
            ->where('customer_id', '<>', 1);

        if (Auth::user()->is_admin != 1) {
            $select->where('customers.branch_id', Auth::user()->branch_id);
        }

        return $select->count();
    }
    public function getTotalOnMonth()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $select = $this
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('is_deleted', 0)
            ->where('customer_id', '<>', 1);
        if (Auth::user()->is_admin != 1) {
            $select->where('customers.branch_id', Auth::user()->branch_id);
        }
        return $select->count();
    }

    protected function _getList($filter = [])
    {
        $ds = $this->from($this->table . ' as cu')
            ->leftJoin('branches as ba', 'ba.branch_id', '=', 'cu.branch_id')
            ->leftJoin('customer_groups as cug', 'cug.customer_group_id', '=', 'cu.customer_group_id')
            ->leftJoin('province', 'province.provinceid', '=', 'cu.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'cu.district_id')
            ->select(
                'cu.customer_id as id',
                'cu.full_name as name',
                'cu.gender as gender',
                'cu.phone1 as phone',
                'cu.customer_code as code',
                'cug.group_name as group',
                'cu.customer_avatar as avatar',
                'cu.address as address',
                'cu.birthday as birthday',
                'ba.branch_name as branch',
                'province.name as province_name',
                'district.name as district_name',
                'cu.postcode as postcode'
            )
//            ->whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(cu.birthday) AND DAYOFYEAR(curdate()) + 7 >=  dayofyear(cu.birthday)')
            ->whereMonth('cu.birthday', '=', Carbon::now()->format('m'))
            ->where(function ($query) {
                $query->whereDay('cu.birthday', '>=', Carbon::now()->format('d'))
                    ->whereDay('cu.birthday', '<=', Carbon::now()->addDays(7)->format('d'));
            })
            ->orderByRaw('DAYOFYEAR(cu.birthday)')
            ->whereNotIn('cu.customer_id', [1])
            ->where('cu.is_deleted', 0);

        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('cu.full_name', 'like', '%' . $search . '%')
                    ->orWhere('cu.customer_code', 'like', '%' . strtoupper($search) . '%');
            });
        }

        return $ds;
    }

    public function getItem($id) {
        $oSelect= $this->where('customer_id',$id)->first();
        return $oSelect;
    }


}