<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 09:52
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ContractFileTable extends Model
{
    use ListTableTrait;
    protected $table = "contract_files";
    protected $primaryKey = "contract_file_id";
    protected $fillable = [
        "contract_file_id",
        "contract_id",
        "name",
        "note",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách file hợp đồng
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.contract_file_id",
                "{$this->table}.name",
                "{$this->table}.note",
                "sf.full_name as update_by_name",
                "{$this->table}.updated_at"
            )
            ->join("staffs as sf", "sf.staff_id", "=", "{$this->table}.updated_by")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        //Filter theo HĐ
        if (isset($filter['contract_id'])) {
            $ds->where("{$this->table}.contract_id", $filter['contract_id']);
            unset($filter['contract_id']);
        }

        return $ds;
    }

    /**
     * Thêm file HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_file_id;
    }

    /**
     * Chỉnh sửa file HĐ
     *
     * @param array $data
     * @param $fileId
     * @return mixed
     */
    public function edit(array $data, $fileId)
    {
        return $this->where("contract_file_id", $fileId)->update($data);
    }

    /**
     * Lấy thông tin file HĐ
     *
     * @param $fileId
     * @return mixed
     */
    public function getInfo($fileId)
    {
        return $this
            ->select(
                "contract_file_id",
                "contract_id",
                "name",
                "note"
            )
            ->where("contract_file_id", $fileId)
            ->first();
    }
}