<?php


namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerTable extends Model
{
    protected $table = 'chathub_customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_id',
        'customer_social_id',
        'channel_id',
        'email',
        'phone',
        'name',
        'gender',
        'address',
        'avatar',
        'tag_id',
        'status',
        'created_at',
        'updated_at',
        'birthday',
        'location'
    ];

    /**
     * @param $date_range
     * @return CustomerTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUser($date_range)
    {
        $ds = $this
            ->leftJoin('chathub_tag as tag', 'tag.tag_id', '=', 'chathub_customer.tag_id')
            ->select(
                'chathub_customer.name',
                'chathub_customer.email',
                'chathub_customer.phone',
                'chathub_customer.gender',
                'tag.name as tag_name',
                'chathub_customer.customer_id',
                'chathub_customer.created_at as JoinedAt'
            );
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_customer.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }


    /**
     * @param $month
     * @param bool $notMonth
     * @return CustomerTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUserMonth($month)
    {
        // dd(1);
        $ds = $this
            ->select(
                DB::raw('count(name) as total')
            )
            ->whereMonth('created_at', '=', $month->month);
        return $ds->get();
    }

    /**
     * @param $date_start
     * @param $date_end
     * @param bool $one
     * @param bool $before
     * @return CustomerTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUserMonthRange($date_start, $date_end)
    {
        $ds = $this
            ->select(
                DB::raw('count(name) as total')
            )->whereBetween('created_at', [$date_start . ' 00:00:00', $date_end . ' 23:59:59']);
        return $ds->get();
    }
    /**
     * @param $date_range
     * @return CustomerTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalGender($date_range)
    {
        $ds = $this->select(
            'gender',
            DB::raw('count(gender) as total')
        )->groupBy('gender');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @param $year_query
     * @param bool $one
     * @return CustomerTable|Model|null
     */
    public function totalAge($date_range, $year_query, $one = false)
    {
        $ds = $this->select(
            DB::raw('count(customer_id) as total')
        );
        if ($one == true) {
            $ds->whereYear('birthday', $year_query);
        } else {
            $arr_date = explode(" - ", $year_query);
            $ds->whereBetween(\DB::raw('year(birthday)'), [$arr_date[0], $arr_date[1]]);
        }
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->first();
    }

    /**
     * @param $date_range
     * @return CustomerTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalLocation($date_range)
    {
        $ds = $this->select(
            'location',
            DB::raw('count(location) as total')
        )->groupBy('location');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    public function getAll($filter, $isPaging = true) {
        $oSelect = $this
            ->leftJoin('channel' ,'chathub_customer.channel_id','channel.channel_id')
            ->where('is_survey',1);

        if (isset($filter['customer_social_id'])) {
            $oSelect->where('chathub_customer.customer_social_id','like','%' .  $filter['customer_social_id']. '%' );
        }

        if (isset($filter['channel'])) {
            $oSelect->where('channel.name','like','%' .  $filter['channel']. '%' );
        }

        if (isset($filter['name'])) {
            $oSelect->where('chathub_customer.name','like','%' .  $filter['name']. '%' );
        }

        $oSelect->select('chathub_customer.*','channel.name as channel_title')
            ->orderBy('chathub_customer.customer_id','DESC');
        return $oSelect->paginate(25);
    }

//    Lấy tất cả tài khoản
    public function getAllCustomer($filter){
        $oSelect = $this
            ->leftJoin('channel' ,'chathub_customer.channel_id','channel.channel_id')
            ->where('is_survey',1);
        if (isset($filter['customer_social_id'])) {
            $oSelect->where('chathub_customer.customer_social_id','like','%' .  $filter['customer_social_id']. '%' );
        }

        if (isset($filter['channel'])) {
            $oSelect->where('channel.name','like','%' .  $filter['channel']. '%' );
        }

        if (isset($filter['name'])) {
            $oSelect->where('chathub_customer.name','like','%' .  $filter['name']. '%' );
        }
        $oSelect->select('chathub_customer.*','channel.name as channel_title')
            ->orderBy('chathub_customer.customer_id','DESC');
        return $oSelect->get();
    }

}