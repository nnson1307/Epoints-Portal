<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/30/2019
 * Time: 6:45 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class SmsCampaignTable extends Model
{
    use ListTableTrait;
    protected $table = 'sms_campaign';
    protected $primaryKey = 'campaign_id';
    protected $fillable = [
        'campaign_id',
        'name',
        'cost',
        'is_deal_created',
        'status',
        'content',
        'slug',
        'code',
        'value',
        'is_now',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_deleted',
        'sent_by',
        'time_sent',
        'branch_id',
        'slug'
    ];

    const NEW = "new";

    protected function _getList()
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'sms_campaign.created_by')
            ->select(
                'campaign_id',
                'name',
                'status',
                'content',
                'slug',
                'code',
                'value',
                'is_now',
                'sms_campaign.created_by',
                'full_name',
                'sms_campaign.created_at as created_at',
                'sent_by', 'time_sent'
            )
            ->orderBy($this->primaryKey, 'desc');
        return $select;
    }

    public function getlist()
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'sms_campaign.created_by')
            ->select(
                'campaign_id',
                'name',
                'status',
                'content',
                'slug',
                'code',
                'value',
                'is_now',
                'sms_campaign.created_by',
                'full_name',
                'sms_campaign.created_at as created_at',
                'sent_by', 'time_sent'
            )
            ->orderBy($this->primaryKey, 'desc')->get();
        return $select;
    }

    public function add(array $data)
    {
        $data = $this->create($data);
        return $data->campaign_id;
    }

    public function getOptionCustomerCare()
    {
        $select = $this->select('campaign_id', 'campaign_name')->where('type', 'customer_care')->get();
        return $select;
    }

    public function remove($id)
    {
        return $this->where('campaign_id', $id)->update(['status'=>'cancel']);
    }

    public function getItem($id)
    {
        return $this->where('campaign_id', $id)->first();
    }

    //Dữ liệu danh sách chiến dịch.
    public function getListIndex()
    {
        $select = $this->leftJoin('sms_log', 'sms_log.campaign_id', '=', 'sms_campaign.campaign_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'sms_campaign.created_by')
            ->select(
                'sms_campaign.campaign_id as campaign_id',
                'sms_campaign.name as sms_campaign_name',
                'sms_campaign.status as status',
                'sms_campaign.code as sms_campaign_code',
                'full_name',
                'sms_campaign.created_at as created_at',
                'error_code',
                'sms_campaign.time_sent',
                'sms_campaign.sent_by as sent_by',
                'sms_campaign.time_sent as time_sent'
            );
        return $select->get();
    }

    public function getListCampaign(array $filter = [])
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'sms_campaign.created_by')
            ->select(
                'sms_campaign.campaign_id as campaign_id',
                'sms_campaign.name as name',
                'sms_campaign.status as status',
                'full_name',
                'sms_campaign.created_at as created_at',
                'sms_campaign.time_sent',
                'sms_campaign.sent_by as sent_by',
                'sms_campaign.time_sent as time_sent'
            );
        if (isset($filter['search_keyword']) != "") {
            $search = $filter['search_keyword'];
            $select->where(function ($query) use ($search) {
                $query->where('sms_campaign.name', 'like', '%' . $search . '%')
                    ->orWhere('sms_campaign.code', '%' . $search . '%');
            });
        }
        if (!empty($filter['created_by'])) {
            $select->where('sms_campaign.created_by', $filter['created_by']);
        }
        if (!empty($filter['sent_by'])) {
            $select->where('sms_campaign.sent_by', $filter['sent_by']);
        }
        if (!empty($filter['status'])) {
            $select->where('sms_campaign.status', $filter['status']);
        }
        if (!empty($filter['day_sent'])) {
            $select->whereBetween('sms_campaign.time_sent', [$filter['day_sent'] . " 00:00:00", $filter['day_sent'] . " 23:59:59"]);
        }
        if (!empty($filter['created_at'])) {
            $select->whereBetween('sms_campaign.created_at', [$filter['created_at'] . " 00:00:00", $filter['created_at'] . " 23:59:59"]);
        }
        return $select->get();
    }

    //Kiểm tra trùng tên chiến dịch.
    public function checkSlugName($slug, $id)
    {
        $select = $this->where('campaign_id', '<>', $id)->where('slug', $slug)->first();
        return $select;
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * Lấy sms marketing mới
     *
     * @return mixed
     */
    public function getCampaignNew()
    {
        return $this
            ->select(
                "campaign_id",
                "code",
                "name",
                "content",
                "value",
                "is_now",
                "branch_id",
                "status",
                "is_deal_created",
                "created_by"
            )
            ->where("status", self::NEW)
            ->get();
    }
    public function getOptionSms($filter)
    {
        $data = $this->select(
            "campaign_id",
            "name"
        )
            ->where("{$this->table}.status", "=", "sent");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $data->get()->toArray();
    }
    public function getCostReport($filter)
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT(time_sent,'%d/%m/%Y') as created_group"),
            DB::raw("SUM(cost) as cost")
        )
            ->whereNotNull("{$this->table}.cost")
            ->where("{$this->table}.status", "=", "sent")
            ->groupBy(DB::raw("DATE_FORMAT(time_sent,'%d/%m/%Y')"));
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('time_sent', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $data->get()->toArray();
    }
}