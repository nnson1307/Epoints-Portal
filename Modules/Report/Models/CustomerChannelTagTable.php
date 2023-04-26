<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-10
 * Time: 10:04 AM
 * @author SonDepTrai
 */

namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerChannelTagTable extends Model
{
    public $timestamps = false;
    protected $table = 'chathub_customer_channel_tag';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'customer_channel_id',
        'tag_id',
        'created_date'
    ];


    /**
     * Thêm customer channel tag
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    /**
     * Kiểm tra đã tồn tại user, tag
     *
     * @param $userId
     * @param $tagId
     * @return CustomerChannelTagTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function checkIssetUserTag($userId, $tagId)
    {
        return $this
            ->select(
                'id'
            )
            ->where('customer_channel_id', $userId)
            ->where('tag_id', $tagId)
            ->get();
    }

    /**
     * Chart total unique user by brand
     *
     * @param $date_range
     * @return CustomerChannelTagTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUniqueUserByBrand($date_range=null)
    {
        $ds = $this
            ->join('chathub_tag as tag', 'tag.tag_id', '=', 'chathub_customer_channel_tag.tag_id')
            ->join('chathub_customer as customer', 'customer.customer_id', 'chathub_customer_channel_tag.customer_channel_id')
            ->select(
                'tag.keyword',
                'chathub_customer_channel_tag.customer_channel_id',
                'customer.name',
                'customer.phone',
                'customer.email',
                'customer.gender',
                'tag.name as tag_name',
                'customer.customer_id'
            );
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_customer_channel_tag.created_date', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }
}