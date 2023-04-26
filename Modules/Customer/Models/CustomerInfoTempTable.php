<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/05/2021
 * Time: 11:05
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerInfoTempTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_info_temp";
    protected $primaryKey = "customer_info_temp_id";
    protected $fillable = [
        "customer_info_temp_id",
        "full_name",
        "phone",
        "province_id",
        "district_id",
        "address",
        "email",
        "gender",
        "birthday",
        "status",
        "confirm_by",
        "customer_id",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Danh sách thông tin cần cập nhật
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_info_temp_id",
                "customers.full_name",
                "customers.phone1 as phone",
                "customers.email",
                DB::raw("CONCAT(pr.type, ' ', pr.name) as province_name"),
                DB::raw("CONCAT(dt.type, ' ', dt.name) as district_name"),
                "customers.address",
                "{$this->table}.full_name as full_name_temp",
                "{$this->table}.phone as phone_temp",
                "{$this->table}.email as email_temp",
                DB::raw("CONCAT(prt.type, ' ', prt.name) as province_name_temp"),
                DB::raw("CONCAT(dtt.type, ' ', dtt.name) as district_name_temp"),
                "{$this->table}.address as address_temp",
                "{$this->table}.status"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("province as pr", "pr.provinceid", "=", "customers.province_id")
            ->leftJoin("district as dt", "dt.districtid", "=", "customers.district_id")
            ->leftJoin("province as prt", "prt.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district as dtt", "dtt.districtid", "=", "{$this->table}.district_id")
            ->where("customers.is_deleted", 0)
            ->orderBy("{$this->table}.customer_info_temp_id", "desc");

        // filter tên + sđt
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("customers.full_name", 'like', '%' . $search . '%')
                    ->orWhere("customers.phone1", 'like', '%' . $search . '%');
            });
        }

        return $ds;
    }

    /**
     * Lấy thông tin cần cập nhật
     *
     * @param $infoTempId
     * @return mixed
     */
    public function getInfo($infoTempId)
    {
        return $this
            ->select(
                "{$this->table}.customer_info_temp_id",
                "customers.full_name",
                "customers.phone1 as phone",
                "customers.email",
                DB::raw("CONCAT(pr.type, ' ', pr.name) as province_name"),
                DB::raw("CONCAT(dt.type, ' ', dt.name) as district_name"),
                "customers.address",
                "customers.gender",
                "customers.birthday",
                "{$this->table}.full_name as full_name_temp",
                "{$this->table}.phone as phone_temp",
                "{$this->table}.email as email_temp",
                DB::raw("CONCAT(prt.type, ' ', prt.name) as province_name_temp"),
                DB::raw("CONCAT(dtt.type, ' ', dtt.name) as district_name_temp"),
                "{$this->table}.address as address_temp",
                "{$this->table}.gender as gender_temp",
                "{$this->table}.birthday as birthday_temp",
                "{$this->table}.status",
                "{$this->table}.customer_id",
                "{$this->table}.province_id as province_id_temp",
                "{$this->table}.district_id as district_id_temp"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("province as pr", "pr.provinceid", "=", "customers.province_id")
            ->leftJoin("district as dt", "dt.districtid", "=", "customers.district_id")
            ->leftJoin("province as prt", "prt.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district as dtt", "dtt.districtid", "=", "{$this->table}.district_id")
            ->where("{$this->table}.customer_info_temp_id", $infoTempId)
            ->first();
    }

    /**
     * Cập nhật thông tin cần cập nhật
     *
     * @param array $data
     * @param $infoTempId
     * @return mixed
     */
    public function edit(array $data, $infoTempId)
    {
        return $this->where("customer_info_temp_id", $infoTempId)->update($data);
    }
}