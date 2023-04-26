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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ZaloCustomerCareTable extends Model
{
    use ListTableTrait;

    protected $table = 'zalo_customer_care';
    protected $primaryKey = 'zalo_customer_care_id';

    protected $fillable = [
        "zalo_customer_care_id",
        "full_name",
        "avatar",
        "phone_number",
        "address",
        "province_id",
        "district_id",
        "zalo_customer_tag_id",
        "zalo_user_id",
        "status",
        "created_at",
        "updated_at"
    ];

    public function tagList()
    {
        return $this->hasMany('Modules\ZNS\Models\ZaloCustomerTagMapTable', 'zalo_customer_care_id', 'zalo_customer_care_id')
            ->select("zalo_customer_tag_map.zalo_customer_care_id", "zalo_customer_tag_map.zalo_customer_tag_id", "tag.tag_name")
            ->leftJoin("zalo_customer_tag as tag", "tag.zalo_customer_tag_id", "zalo_customer_tag_map.zalo_customer_tag_id")
            ->get();
    }

    protected function _getList($filters = [])
    {
        $query = $this->select(
            "{$this->table}.zalo_customer_care_id",
            "{$this->table}.full_name",
            "{$this->table}.avatar",
            "{$this->table}.phone_number",
            "{$this->table}.address",
            "{$this->table}.province_id",
            "{$this->table}.district_id",
            "{$this->table}.status",
            "{$this->table}.zalo_customer_tag_id",
            "{$this->table}.created_at",
            "{$this->table}.updated_at"
        );

        // filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $query->where("{$this->table}.full_name", "like", "%" . $filters["search"] . "%")
                ->orWhere("{$this->table}.phone_number", "like", "%" . $filters["search"] . "%");
        }
        // filters status
        if (isset($filters["zalo_customer_tag_id"]) && $filters["zalo_customer_tag_id"] != "") {
            $query->where("{$this->table}.zalo_customer_tag_id", '=', $filters["zalo_customer_tag_id"]);
        }
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");

            $query->whereDate("{$this->table}.created_at", ">=", $startTime);
            $query->whereDate("{$this->table}.created_at", "<=", $endTime);
        }
        $query->where("{$this->table}.status", '=', 'follower');
        $query = $query->orderBy($this->primaryKey, 'DESC');
        return $query;
    }

    public function getName()
    {
        $oSelect = self::select("zalo_customer_care_id", "full_name")
            ->where("{$this->table}.status", '=', 'follower')->get();
        return ($oSelect->pluck("full_name", "zalo_customer_care_id")->toArray());
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->zalo_customer_care_id;
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

    public function getItemByZaloUserId($id)
    {
        return $this->where("zalo_user_id", $id)->first();
    }

    public function searchCustomerFollower($data = null, $id_customer_checked = [])
    {
        if($id_customer_checked){
            if(is_array($id_customer_checked)){
                $arr = '('.implode(",",$id_customer_checked).')';
            }else{
                $arr = '('.$id_customer_checked.')';
            }
        }else{
            $arr = "('','')";
        }

        $select = $this->select("*",
            \DB::raw("(CASE WHEN zalo_customer_care_id in {$arr} and full_name NOT LIKE '%{$data}%' THEN 1 ELSE 0 END) as is_hide"));
        $select = $select->where("status", "follower");
        if ($data != null) {
            $select->where(function ($query) use ($data) {
                $query->where('full_name', 'like', '%' . $data . '%');
            })->orWhereIn('zalo_customer_care_id',$id_customer_checked);
        }

        if ($data == null) {
            $select->limit(500);
        }
        return $select->get();
    }

    public function insertOrUpdateMultipleRows($data)
    {
        $oData = $this->getItemByZaloUserId($data['zalo_user_id']);
        if ($oData != null) {
            $this->where("zalo_user_id", $data['zalo_user_id'])->update($data);
            return $oData->zalo_customer_care_id;
        }
        return $this->add($data);
    }

}