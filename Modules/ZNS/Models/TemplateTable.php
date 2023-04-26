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
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class TemplateTable extends Model
{
    use ListTableTrait;

    protected $table = 'zns_template';
    protected $primaryKey = 'zns_template_id';

    protected $fillable = [
        'zns_template_id',
        'template_id',
        'template_name',
        'type',
        'status',
        'price',
        'preview',
        'template_tag',
        'is_trigger_config',
        'type_template_follower',
        'image',
        'image_title',
        'title_show',
        'sub_title',
        'file',
        'file_title',
        'token_upload_file',
        'attachment_id',
        'link_image',
        'created_at',
        'updated_at'
    ];

    public function template_button()
    {
        return $this->hasMany('Modules\ZNS\Models\TemplateButtonTable', 'zns_template_id', 'zns_template_id')->get();
    }

    protected function _getList($filters = [])
    {
        $query = $this->select(
            "{$this->table}.zns_template_id",
            "{$this->table}.template_id",
            "{$this->table}.template_name",
            "{$this->table}.type",
            "{$this->table}.status",
            "{$this->table}.price",
            "{$this->table}.preview",
            "{$this->table}.template_tag",
            "{$this->table}.type_template_follower",
            "TotalNumberSend.number_sent as number_sent",
            "{$this->table}.created_at",
            "{$this->table}.updated_at"
        );
        // filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $query->where("{$this->table}.template_name", "like", "%" . $filters["search"] . "%");
        }
        // filters status
        if (isset($filters["status"]) && $filters["status"] != "") {
            $query->where("{$this->table}.status", '=', $filters["status"]);
        }
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");

            $query->whereDate("{$this->table}.created_at", ">=", $startTime);
            $query->whereDate("{$this->table}.created_at", "<=", $endTime);
        }
        if (isset($filters["type"]) && $filters["type"] == "zns") {
            $query = $query->leftJoin(DB::raw('(SELECT COUNT(id) number_sent,template_id 
        FROM `zns_log` 
        WHERE zns_log.status = "sent"
        GROUP BY template_id)
        TotalNumberSend'),
                function ($join) {
                    $join->on("{$this->table}.template_id", '=', "TotalNumberSend.template_id");
                });
        }
        if (isset($filters["type"]) && $filters["type"] == "follower") {
            $query = $query->leftJoin(DB::raw('(SELECT COUNT(zalo_log_follower_id) number_sent,template_id 
        FROM `zalo_log_follower` 
        WHERE zalo_log_follower.status = "sent"
        GROUP BY template_id)
        TotalNumberSend'),
                function ($join) {
                    $join->on("{$this->table}.zns_template_id", '=', "TotalNumberSend.template_id");
                });
        }
        if (isset($filters["type"]) && $filters["type"] != "") {
            $query = $query->where("{$this->table}.type", $filters["type"]);
        }
        $query = $query->orderBy($this->primaryKey, 'DESC');
        return $query;
    }

    public function getName($is_trigger_config = 0)
    {
        $oSelect = self::select("zns_template_id", "template_name")
            ->where('is_trigger_config', $is_trigger_config)
            ->where('status', 1)
            ->where('type', 'zns')
            ->get();
        return ($oSelect->pluck("template_name", "zns_template_id")->toArray());
    }

    public function getNameFollower()
    {
        $oSelect = self::select("zns_template_id", "template_name")
            ->where('type', 'follower')
            ->get();
        return ($oSelect->pluck("template_name", "zns_template_id")->toArray());
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->zns_template_id;
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
        return $this->where($this->primaryKey, $id)->first();
    }

    public function getItemByTemplateId($id)
    {
        return $this->where("template_id", $id)->where('type', 'zns')->first();
    }

    public function getItemByTemplateFollowerId($id)
    {
        return $this->where("template_id", $id)->where('type', 'follower')->first();
    }

    public function insertOrUpdateMultipleRows($data)
    {
        $oData = $this->getItemByTemplateId($data['template_id']);
        if ($oData != null) {
            $this->where("template_id", $data['template_id'])->update($data);
            return $oData->zns_template_id;
        }
        return $this->add($data);
    }

    public function duplicateRowWithNewId($params)
    {
        $oData = $this->find($params['zns_template_id']);
        $new = $oData->replicate();
        $new->template_name = $params['template_name'];
        $new->save();
        return $new->zns_template_id;
    }

    /**
     * Mass (bulk) insert or update on duplicate for Laravel 4/5
     *
     * insertOrUpdate([
     *   ['id'=>1,'value'=>10],
     *   ['id'=>2,'value'=>60]
     * ]);
     *
     *
     * @param array $rows
     */
    public function insertOrUpdate(array $rows)
    {
        $table = \DB::getTablePrefix() . with(new self)->getTable();


        $first = reset($rows);

        $columns = implode(',',
            array_map(function ($value) {
                return "$value";
            }, array_keys($first))
        );

        $values = implode(',', array_map(function ($row) {
                return '(' . implode(',',
                        array_map(function ($value) {
                            return '"' . str_replace('"', '""', $value) . '"';
                        }, $row)
                    ) . ')';
            }, $rows)
        );

        $updates = implode(',',
            array_map(function ($value) {
                return "$value = VALUES($value)";
            }, array_keys($first))
        );

        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        return \DB::statement($sql);
    }
}