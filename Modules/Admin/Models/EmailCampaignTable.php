<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 30/1/2019
 * Time: 09:01
 */

namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class EmailCampaignTable extends Model
{
    use ListTableTrait;
    protected $table = 'email_campaign';
    protected $primaryKey = 'campaign_id';
    protected $fillable = [
        'campaign_id', 'name','status','content','slug','value','created_by','updated_by','created_at',
        'updated_at','is_now','sent_by','time_sent','branch_id','cost','is_deal_created'
    ];

    const NEW = "new";

    /**
     * @param $filter
     * @return mixed
     */
    protected function _getList(&$filter)
    {
        $oSelect = $this->select('campaign_id', 'name', 'status');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $oSelect->where('campaign_name', 'like', '%' . $search . '%');
        }
        unset($filter['search']);
        return $oSelect;
    }
    public function getlist()
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'email_campaign.created_by')
            ->select(
                'campaign_id',
                'name',
                'status',
                'content',
                'slug',
                'value',
                'is_now',
                'email_campaign.created_by',
                'staffs.full_name',
                'email_campaign.created_at as created_at',
                'sent_by',
                'time_sent'
            )
            ->orderBy($this->primaryKey, 'desc')->get();
        return $select;
    }
    public function getLog()
    {
        $ds = $this->leftJoin('email_log', 'email_log.campaign_id', '=', 'email_campaign.campaign_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'email_log.created_by')
            ->select(
                'email_campaign.campaign_id as campaign_id',
                'email_campaign.name as email_campaign_name',
                'email_campaign.status as status',
                'staffs.full_name',
                'email_campaign.created_at as created_at',
                'email_campaign.time_sent',
                'email_campaign.sent_by as sent_by',
                'email_log.email_status as email_status'
            );
        return $ds->get();
    }
    public function getListCampaign(array $filter = [])
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'email_campaign.created_by')
            ->select(
                'email_campaign.campaign_id as campaign_id',
                'email_campaign.name as name',
                'email_campaign.status as status',
                'staffs.full_name',
                'email_campaign.created_at as created_at',
                'email_campaign.time_sent',
                'email_campaign.sent_by as sent_by'
            )->orderBy($this->primaryKey, 'desc');
        if (!empty($filter['search_keyword'])) {
            $select->where('email_campaign.name', 'like', '%' . $filter['search_keyword'] . '%');
        }
        if (!empty($filter['created_by'])) {
            $select->where('email_campaign.created_by', $filter['created_by']);
        }
        if (!empty($filter['sent_by'])) {
            $select->where('email_campaign.sent_by', $filter['sent_by']);
        }
        if (!empty($filter['status'])) {
            $select->where('email_campaign.status', $filter['status']);
        }
        if (!empty($filter['day_sent'])) {
            $select->whereBetween('email_campaign.time_sent', [$filter['day_sent'] . " 00:00:00", $filter['day_sent'] . " 23:59:59"]);
        }
        if (!empty($filter['created_at'])) {
            $select->whereBetween('email_campaign.created_at', [$filter['created_at'] . " 00:00:00", $filter['created_at'] . " 23:59:59"]);
        }
        return $select->get();
    }
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->campaign_id;
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        return $this->where('slug', $name)->where('campaign_id', '<>', $id)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id)
    {
        return $this->where('campaign_id', $id)->delete();
    }

    /**
     * @return mixed
     */
    public function getOption()
    {
        return $this->select('campaign_id', 'campaign_name')
            ->where('type', 'customer_care')->get()
            ->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $ds = $this->select('campaign_id', 'cost','is_deal_created','name', 'status', 'content','value', 'is_now','branch_id')
            ->where('campaign_id', $id)
            ->first();
        return $ds;
    }


    /**
     * Chỉnh sửa chiến dịch email
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('campaign_id',$id)->update($data);
    }

    /**
     * Lấy email marketing mới
     *
     * @return mixed
     */
    public function getCampaignNew()
    {
        return $this
            ->select(
                "campaign_id",
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
    public function getOptionEmail($filter)
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