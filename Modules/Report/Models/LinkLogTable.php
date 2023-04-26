<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-03
 * Time: 3:40 PM
 * @author SonDepTrai
 */

namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LinkLogTable extends Model
{
    protected $table = 'chatbot_link_log';
    protected $primaryKey = 'link_log_id';
    protected $fillable = [
        'link_log_id',
        'link_id',
        'code',
        'user_id',
        'created_at'
    ];

    /**
     * @param $date_range
     * @return LinkLogTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUserClickLink($date_range)
    {
        $ds = $this
            ->join('chatbot_link', 'chatbot_link.chatbot_link_id', '=', 'chatbot_link_log.link_id')
            ->select(
                'chatbot_link.source',
                DB::raw('count(chatbot_link_log.link_id) as total')
            )
            ->groupBy('chatbot_link_log.link_id');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chatbot_link_log.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds ->get();
    }

    public function totalUniqueUserClickLink($date_range)
    {
        $ds = $this
            ->join('chatbot_link', 'chatbot_link.chatbot_link_id', '=', 'chatbot_link_log.link_id')
            ->select(
                'chatbot_link.source',
                'user_id'
            );
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chatbot_link_log.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds ->get();
    }
}