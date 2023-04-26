<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2021
 * Time: 15:42
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerFileTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_files";
    protected $primaryKey = "customer_file_id";
    protected $fillable = [
        'customer_file_id',
        'customer_id',
        'type',
        'link',
        'is_deleted',
        'created_at',
        'updated_at',
        'file_name',
        'note',
        'created_by',
        'updated_by',
        'file_type'
    ];

    const NOT_DELETE = 0;

    /**
     * Danh sách file của khách hàng
     *
     * @param $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        return $this
            ->select(
                "{$this->table}.customer_file_id",
                "{$this->table}.type",
                "{$this->table}.file_name",
                "{$this->table}.link",
                "{$this->table}.note",
                "s1.full_name as staff_name_create",
                "s2.full_name as staff_name_update"
            )
            ->leftJoin("staffs as s1", "s1.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("staffs as s2", "s2.staff_id", "=", "{$this->table}.updated_by")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.customer_file_id", "desc");
    }

    /**
     * Lấy thông tin file của khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getCustomerFile($customerId)
    {
        return $this
            ->where("customer_id", $customerId)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    public function getArrayCustomerFile($customerId, $type)
    {
        return $this->select("link")
            ->where("customer_id", $customerId)
            ->where("type", $type)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Xoá tất cả file của khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function removeFile($customerId)
    {
        return $this->where("customer_id", $customerId)->delete();
    }

    /**
     * Lấy thông tin file của KH
     *
     * @param $fileId
     * @return mixed
     */
    public function getInfo($fileId)
    {
        return $this->where("customer_file_id", $fileId)->first();
    }

    /**
     * Chỉnh sửa thông tin file của KH
     *
     * @param array $data
     * @param $fileId
     * @return mixed
     */
    public function edit(array $data, $fileId)
    {
        return $this->where("customer_file_id", $fileId)->update($data);
    }
}