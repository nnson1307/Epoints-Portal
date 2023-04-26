<?php


namespace Modules\Survey\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class OutletMasterTable extends Model
{
    use ListTableTrait;
    public $timestamps = false;
    protected $table = "outlet_master";
    protected $primaryKey = "outlet_id";

    protected $fillable = [
        "create_datetime",
        "create_by_id",
        "create_by_screen",
        "last_modify_datetime",
        "last_modify_by_id",
        "last_modify_by_screen",
        "outlet_id",
        "customer_code",
        "ship_to_code",
        "customer_name",
        "ship_to_name",
        "address",
        "address_2",
        "province_id",
        "province_name",
        "district_id",
        "district_name",
        "ward_id",
        "ward_name",
        "phone",
        "register_status",
        "passcode",
        "geo_location",
        "latitude",
        "longitude",
        "approve_status",
        "status",
        "is_delete",
        "source",
        "country_id",
        "country_name"
    ];

    /**
     * Lấy toàn bộ danh sách cửa hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function getListAll(array $filter = [])
    {
        $result = $this
            ->where("is_delete", 0)
            ->where("{$this->table}.is_temp", 0)
            ->select(
                "outlet_id",
                "customer_code",
                "ship_to_code",
                "customer_name",
                "ship_to_name",
                "phone",
                "register_status",
                \DB::raw('CONCAT(customer_code, "--", ship_to_code) AS abc')
            )
            ->get();
        return $result;
    }
}
