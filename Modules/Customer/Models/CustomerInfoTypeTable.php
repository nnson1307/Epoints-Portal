<?php

namespace Modules\Customer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerInfoTypeTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_info_type';
    protected $primaryKey = 'customer_info_type_id';
    protected $fillable = [
        'customer_info_type_id',
        'customer_info_type_name_vi',
        'customer_info_type_name_en',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Danh sách hoa hồng nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $select = $this
            ->select(
                "{$this->table}.customer_info_type_id",
                "{$this->table}.customer_info_type_name_vi",
                "{$this->table}.customer_info_type_name_en",
                "{$this->table}.is_actived",
                "{$this->table}.is_deleted",
                "{$this->table}.created_at"
            )
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.customer_info_type_id", "desc");

        // filter tên
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where("{$this->table}.customer_info_type_name_vi", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.customer_info_type_name_en", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $select;
    }

    /**
     * Thêm loại thông tin kèm theo
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->primaryKey}", $id)->update($data);
    }

    /**
     * Chi tiết loại
     *
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        return $this->select(
            'customer_info_type_id',
            'customer_info_type_name_vi',
            'customer_info_type_name_en',
            'is_actived',
            'is_deleted'
        )
            ->where("{$this->primaryKey}", $id)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->first();
    }
}