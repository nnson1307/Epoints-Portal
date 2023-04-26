<?php

namespace Modules\Warranty\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class WarrantyPackageTable extends Model
{
    use ListTableTrait;
    protected $table = "warranty_packed";
    protected $primaryKey = "warranty_packed_id";
    protected $fillable = [
        'warranty_packed_id',
        'packed_code',
        'packed_name',
        'time_type',
        'time',
        'percent',
        'quota',
        'required_price',
        'slug',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'description',
        'detail_description',
    ];

    const NOT_DELETED = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy thông tin gói bảo hành theo id
     *
     * @param $id
     * @return mixed
     */
    public function getInfoById($id)
    {
        $select = $this->select(
            "{$this->table}.warranty_packed_id",
            "{$this->table}.packed_code",
            "{$this->table}.packed_name",
            "{$this->table}.time_type",
            "{$this->table}.time",
            "{$this->table}.percent",
            "{$this->table}.quota",
            "{$this->table}.required_price",
            "{$this->table}.slug",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.description",
            "{$this->table}.detail_description"
        )
            ->where("{$this->table}.{$this->primaryKey}", $id)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE);
        return $select->first();
    }

    public function _getList($filter = [])
    {
        $select = $this->select(
            "{$this->table}.warranty_packed_id",
            "{$this->table}.packed_code",
            "{$this->table}.packed_name",
            "{$this->table}.time_type",
            "{$this->table}.time",
            "{$this->table}.percent",
            "{$this->table}.quota",
            "{$this->table}.required_price",
            "{$this->table}.slug",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.created_at",
            "{$this->table}.description",
            "{$this->table}.detail_description"
        )
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.warranty_packed_id", "desc");
        // filter tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('packed_code', 'like', '%' . $search . '%')
                    ->orWhere('packed_name', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) &&  $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $select;
    }

    /**
     * Insert gói bảo hành
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật gói bảo hành
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("{$this->table}.warranty_packed_id", $id)->update($data);
    }

    /**
     * Cập nhật gói bảo thanh theo package code
     *
     * @param array $data
     * @param $code
     * @return mixed
     */
    public function editByPackageCode(array $data, $code)
    {
        return $this->where("{$this->table}.packed_code", $code)->update($data);
    }

    /**
     * Lấy thông tin gói bảo hành theo code
     *
     * @param $code
     * @return mixed
     */
    public function getInfoByCode($code)
    {
        $select = $this->select(
            "{$this->table}.warranty_packed_id",
            "{$this->table}.packed_code",
            "{$this->table}.packed_name",
            "{$this->table}.time_type",
            "{$this->table}.time",
            "{$this->table}.percent",
            "{$this->table}.quota",
            "{$this->table}.required_price",
            "{$this->table}.slug",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.description",
            "{$this->table}.detail_description"
        )
            ->where("{$this->table}.packed_code", $code)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE);
        return $select->first();
    }
}