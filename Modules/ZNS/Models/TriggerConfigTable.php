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

class TriggerConfigTable extends Model
{
    use ListTableTrait;
    protected $table = 'zns_trigger_config';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'zns_template_id',
        'key',
        'value',
        'time_sent',
        'name',
        'hint',
        'is_active',
        'actived_by',
        'datetime_actived',
        'created_by',
        'updated_by',
        'check_send',
        'created_at',
        'updated_at'
    ];

    protected function _getList($filters = [])
    {
        $query = $this->select(
            "{$this->table}.id",
            "{$this->table}.zns_template_id",
            "{$this->table}.key",
            "{$this->table}.value",
            "{$this->table}.time_sent",
            "{$this->table}.name",
            "{$this->table}.hint",
            "{$this->table}.is_active",
            "{$this->table}.actived_by",
            "{$this->table}.datetime_actived",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "p1.template_name",
            "p1.preview"
        )
        ->leftJoin("zns_template as p1","p1.zns_template_id","{$this->table}.zns_template_id");
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
         if (isset($filters["is_active"]) && $filters["is_active"] != "") {
            $query->where("{$this->table}.is_active",'=', $filters["is_active"]);
        }
        $query = $query
            ->where('is_deleted', 0)
            ->orderBy($this->primaryKey, 'ASC');
        return $query;
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {

        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->select(
            "{$this->table}.id",
            "{$this->table}.zns_template_id",
            "{$this->table}.key",
            "{$this->table}.value",
            "{$this->table}.time_sent",
            "{$this->table}.name",
            "{$this->table}.hint",
            "{$this->table}.is_active",
            "{$this->table}.actived_by",
            "{$this->table}.datetime_actived",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "p1.template_name",
            "p1.preview"
        )
            ->leftJoin("zns_template as p1","p1.zns_template_id","{$this->table}.zns_template_id")
            ->where('is_deleted', 0)
            ->where($this->primaryKey, $id)->first();
    }

    public function getInfoByKey($key)
    {
        return $this->select("{$this->table}.*","p1.template_id")
        ->join("zns_template as p1", "p1.zns_template_id", "=", "{$this->table}.zns_template_id")
        ->where('key', $key)->first();
    }

}