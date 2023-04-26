<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-14
 * Time: 10:09 AM
 * @author SonDepTrai
 */

namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChatbotKeywordLogTable extends Model
{
    public $timestamps = false;
    protected $table = 'chathub_keyword_log';
    protected $primaryKey = 'keyword_log_id';
    protected $fillable = [
        'keyword_log_id',
        'keyword_id',
        'keyword',
        'user_id',
        'history_id',
        'log_date',
        'created_at'
    ];

    /**
     * Thêm keyword log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->keyword_log_id;
    }

    /**
     * Chart keyword dashboard
     *
     * @param $date_range
     * @return ChatbotKeywordLogTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function chartKeyword($date_range)
    {
        $ds = $this
            ->select(
                'keyword',
                'total'
            )
            ->orderByDesc('total')
            ->limit(200)
            ->groupBy('keyword');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('log_date', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    public function chartKeywordExport($date_range)
    {
        $ds = $this
            ->select(
                'keyword',
                'total'
            )
            ->orderByDesc('total')
            ->groupBy('keyword');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('log_date', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }
    //$startTime, $endTime, $key
    public function existKeyWord($startTime, $endTime, $key){
        return $this->where('chathub_keyword_log.keyword','=',$key)
                    ->whereBetween('log_date', [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
                    ->first();
    }
    public function updateKey(array $data, $id){
        return $this->where('keyword_log_id', '=', $id)
                    ->update($data);
    }
}