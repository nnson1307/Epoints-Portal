<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ZNS\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ZaloLogFollowerTable extends Model
{
    use ListTableTrait;
    protected $table = 'zalo_log_follower';
    protected $primaryKey = "zalo_log_follower_id";

    protected $fillable = [
        "zalo_log_follower_id",
        'zalo_campaign_follower_id',
        'user_id',
        'phone',
        'message',
        'params',
        'status',
        'error_code',
        'error_description',
        'type_customer',
        'time_sent_done',
        'object_id',
        'template_id',
        'object_type',
        'deal_code',
        'time_sent',
        'msg_id',
        'sent_by',
        'created_at',
        'created_by',
        'updated_at'
    ];

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function removeByZnsCampaignId($zalo_campaign_follower_id)
    {
        return $this->where("zalo_campaign_follower_id", $zalo_campaign_follower_id)->delete();
    }

    public function edit(array $data, $id)
    {

        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function removeByZnsCampaignFollowerId($zns_campaign_follower_id)
    {
        return $this->where("zalo_campaign_follower_id", $zns_campaign_follower_id)->delete();
    }

    public function getItemByCampaignFollowerId($id)
    {
        return $this->where("{$this->table}.zalo_campaign_follower_id", $id)->get()->toArray();
    }

    public function messSendSuccess($campaignId)
    {
        return $this->where("{$this->table}.zalo_campaign_follower_id", $campaignId)
            ->where("{$this->table}.status", 'sent')->count();
    }

    public function getCustomerListByCampaignId($id)
    {
        return $this->select(
            "{$this->table}.zalo_log_follower_id",
            "{$this->table}.zalo_campaign_follower_id",
            "{$this->table}.user_id",
            "{$this->table}.phone",
            "{$this->table}.message",
            "{$this->table}.params",
            "{$this->table}.status",
            "{$this->table}.error_code",
            "{$this->table}.error_description",
            "{$this->table}.type_customer",
            "{$this->table}.time_sent_done",
            "{$this->table}.object_id",
            "{$this->table}.template_id",
            "{$this->table}.object_type",
            "{$this->table}.deal_code",
            "{$this->table}.time_sent",
            "{$this->table}.msg_id",
            "{$this->table}.sent_by",
            "{$this->table}.created_at",
            "{$this->table}.created_by",
            "{$this->table}.updated_at",
            "p1.full_name",
            "p1.zalo_user_id",
            "p1.zalo_customer_care_id",
            "p3.full_name as created_by_full_name",
            "p4.full_name as sent_by_full_name"
        )
            ->leftJoin("zalo_customer_care as p1", "p1.zalo_customer_care_id", "{$this->table}.user_id")
            ->leftJoin("staffs as p3", "p3.staff_id", "{$this->table}.created_by")
            ->leftJoin("staffs as p4", "p4.staff_id", "{$this->table}.sent_by")
            ->where("{$this->table}.zalo_campaign_follower_id", $id)->get();
    }

}