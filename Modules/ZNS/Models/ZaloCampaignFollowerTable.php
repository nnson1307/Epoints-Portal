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

class ZaloCampaignFollowerTable extends Model
{
    use ListTableTrait;

    protected $table = 'zalo_campaign_follower';
    protected $primaryKey = 'zalo_campaign_follower_id';

    protected $fillable = [
        'zalo_campaign_follower_id',
        'zns_template_id',
        'zns_client_id',
        'campaign_type',
        'name',
        'status',
        'is_actived',
        'slug',
        'code',
        'value',
        'cost',
        'is_now',
        'time_sent',
        'branch_id',
        'params',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function countSend()
    {
        return $this->hasMany('Modules\ZNS\Models\ZaloLogFollowerTable', 'zalo_campaign_follower_id', 'zalo_campaign_follower_id')->count();
    }

    public function countSendSuccess()
    {
        return $this->hasMany('Modules\ZNS\Models\ZaloLogFollowerTable', 'zalo_campaign_follower_id', 'zalo_campaign_follower_id')
            ->where("zalo_log_follower.status", '=', "sent")->count();
    }

    protected function _getList(&$filters = [])
    {
        $query = $this->select(
            "{$this->table}.zalo_campaign_follower_id",
            "{$this->table}.zns_template_id",
            "{$this->table}.zns_client_id",
            "{$this->table}.campaign_type",
            "{$this->table}.name",
            "{$this->table}.status",
            "{$this->table}.is_actived",
            "{$this->table}.slug",
            "{$this->table}.code",
            "{$this->table}.value",
            "{$this->table}.cost",
            "{$this->table}.is_now",
            "{$this->table}.time_sent",
            "{$this->table}.branch_id",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "{$this->table}.params",
            "p1.full_name as created_by_full_name",
            "p2.full_name as updated_by_full_name"
        );
        // filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $query->where("{$this->table}.name", "like", "%" . $filters["search"] . "%");
        }
        // filters nhân viên người tạo
        if (isset($filters["staff_id"]) && $filters["staff_id"] != "") {
            $query->where("{$this->table}.staff_id", $filters["staff_id"]);
        }
        // filters người tạo
        if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("{$this->table}.created_by", $filters["created_by"]);
        }
        // filters lại chiến dịch
        if (isset($filters["campaign_type"]) && $filters["campaign_type"] != "") {
            $query->where("{$this->table}.campaign_type", $filters["campaign_type"]);
        }
        // filters status
        if (isset($filters["status"]) && $filters["status"] != "") {
            $query->where("{$this->table}.status", '=', $filters["status"]);
        }
        // filter ngày tạo
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.created_at", ">=", $startTime);
            $query->whereDate("{$this->table}.created_at", "<=", $endTime);
        }
        if (isset($filters["time_sent"]) && $filters["time_sent"] != "") {
//            $arr_filter = explode(" - ", $filters["time_sent"]);
//            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d");
//            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d");
//            $query->whereDate("{$this->table}.time_sent", ">=", $startTime . ' 00:00:00');
//            $query->whereDate("{$this->table}.time_sent", "<=", $endTime . ' 23:59:59');

            $arr_filter = explode(" - ", $filters["time_sent"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $query->whereBetween("{$this->table}.time_sent", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filters["time_sent"]);
        }
        $query = $query->leftJoin("staffs as p1", "p1.staff_id", "{$this->table}.created_by")
            ->leftJoin("staffs as p2", "p2.staff_id", "{$this->table}.updated_by")
            ->orderBy($this->primaryKey, 'DESC');

        return $query;
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->zalo_campaign_follower_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function duplicateRowWithNewId($id)
    {
        $oData = $this->find($id);
        $new = $oData->replicate();
        $new->is_actived = 0;
        $new->status = "new";
        $new->save();
        return $new->zalo_campaign_follower_id;
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->select(
            "{$this->table}.zalo_campaign_follower_id",
            "{$this->table}.zns_template_id",
            "{$this->table}.zns_client_id",
            "{$this->table}.campaign_type",
            "{$this->table}.name",
            "{$this->table}.status",
            "{$this->table}.is_actived",
            "{$this->table}.slug",
            "{$this->table}.code",
            "{$this->table}.value",
            "{$this->table}.cost",
            "{$this->table}.is_now",
            "{$this->table}.time_sent",
            "{$this->table}.branch_id",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "{$this->table}.params",
            "p1.template_id",
            "p1.preview",
            "p1.price",
            "p1.template_name",
            "p2.full_name as created_by_full_name"
        )
            ->leftJoin("zns_template as p1", "p1.zns_template_id", "{$this->table}.zns_template_id")
            ->leftJoin("staffs as p2", "p2.staff_id", "{$this->table}.created_by")
            ->where($this->primaryKey, $id)->first();
    }

}