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

class ZaloCustomerTagMapTable extends Model
{
    use ListTableTrait;

    protected $table = 'zalo_customer_tag_map';
    protected $primaryKey = 'zalo_customer_tag_map_id';

    protected $fillable = [
        "zalo_customer_tag_map_id",
        "zalo_customer_care_id",
        "zalo_customer_tag_id",
        "created_at",
        "updated_at"
    ];

    protected function _getList($filters = [])
    {
        $query = $this->select(
            "{$this->table}.zalo_customer_tag_map_id",
            "{$this->table}.zalo_customer_care_id",
            "{$this->table}.zalo_customer_tag_id",
            "{$this->table}.created_at",
            "{$this->table}.updated_at"
        );
        $query = $query->orderBy($this->primaryKey, 'DESC');
        return $query;
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->zalo_customer_tag_map_id;
    }

    public function removeByZaloCustomerCareId($zalo_customer_care_id)
    {
        return $this->where("zalo_customer_care_id", $zalo_customer_care_id)->delete();
    }


    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

}