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

class ZaloCustomertagTable extends Model
{
    use ListTableTrait;

    protected $table = 'zalo_customer_tag';
    protected $primaryKey = 'zalo_customer_tag_id';

    protected $fillable = [
        "zalo_customer_tag_id",
        "tag_name",
        "color_code",
        "created_at",
        "updated_at"
    ];

    protected function _getList($filters = [])
    {
        $query = $this->select(
            "{$this->table}.zalo_customer_tag_id",
            "{$this->table}.tag_name",
            "{$this->table}.color_code",
            "{$this->table}.created_at",
            "{$this->table}.updated_at"
        );

        // filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $query->where("{$this->table}.tag_name", "like", "%" . $filters["tag_name"] . "%");
        }
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");

            $query->whereDate("{$this->table}.created_at", ">=", $startTime);
            $query->whereDate("{$this->table}.created_at", "<=", $endTime);
        }
        $query = $query->orderBy($this->primaryKey, 'DESC');
        return $query;
    }

    public function getName()
    {
        $oSelect = self::select("zalo_customer_tag_id", "tag_name")
            ->get();
        return ($oSelect->pluck("tag_name", "zalo_customer_tag_id")->toArray());
    }

    public function add(array $data)
    {
        $first = $this->where("tag_name", $data['tag_name'])->first();
        if($first){
            return -1;
        }
        $oData = $this->create($data);
        return $oData->zalo_customer_tag_id;
    }

    public function remove($id)
    {
        $first = \DB::table('zalo_customer_tag_map')->where('zalo_customer_tag_id', $id)->first();
        if($first){
            return -1;
        }
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

}