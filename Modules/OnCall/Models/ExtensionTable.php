<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/07/2021
 * Time: 09:37
 */

namespace Modules\OnCall\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ExtensionTable extends Model
{
    use ListTableTrait;
    protected $table = "oc_extensions";
    protected $primaryKey = "extension_id";
    protected $fillable = [
        "extension_id",
        "extension_number",
        "full_name",
        "user_agent",
        "email",
        "phone",
        "staff_id",
        "status",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;
    const IS_DELETED = 1;

    /**
     * Lấy danh sách extension
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.extension_id",
                "{$this->table}.extension_number",
                "{$this->table}.full_name",
                "{$this->table}.user_agent",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.staff_id",
                "{$this->table}.status",
                "st.full_name as staff_name"
            )
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.extension_number", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.phone", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.email", 'like', '%' . $search . '%');
            });
        }

        // filter nhân viên được phân bổ
        if (isset($filter['staff_id']) && $filter['staff_id'] != "") {
            $ds->where("{$this->table}.staff_id", $filter['staff_id']);

            unset($filter['staff_id']);
        }

        // filter trạng thái
        if (isset($filter['status']) && $filter['status'] != "") {
            $ds->where("{$this->table}.status", $filter['status']);

            unset($filter['status']);
        }

        return $ds;
    }

    /**
     * Thêm extension
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Chỉnh sửa extension
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("extension_id", $id)->update($data);
    }

    /**
     * Lấy thông tin extension
     *
     * @param $extensionId
     * @return mixed
     */
    public function getInfo($extensionId)
    {
        return $this->where("extension_id", $extensionId)->first();
    }

    /**
     * Lấy thông tin extension bằng number
     *
     * @param $extensionNumber
     * @return mixed
     */
    public function getInfoByExtension($extensionNumber)
    {
        return $this->where("extension_number", $extensionNumber)->first();
    }

    /**
     * Xoá những extension không tồn tại
     *
     * @param $arrExtensionNumber
     * @return mixed
     */
    public function removeExtensionNotExist($arrExtensionNumber)
    {
        return $this->whereNotIn("extension_number", $arrExtensionNumber)->update([
            "is_deleted" => self::IS_DELETED
        ]);
    }
}