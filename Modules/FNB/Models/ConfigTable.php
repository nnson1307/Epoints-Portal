<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/21/2019
 * Time: 11:43 PM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";
    public $timestamps = false;
    protected $fillable
        = [
            'config_id', 'key', 'value', 'name', 'is_show', 'type'
        ];

    const IS_SHOW = 1;
    const keyBranchOrder = "branch_apply_order";

    public function getAll()
    {
        return $this->select('config_id', 'key', 'value', 'name')->where("is_show", self::IS_SHOW)->get();
    }
    /**
     * Edit
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('config_id', $id)->update($data);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getInfoByKey($key)
    {
        return $this->where('key', $key)->first();
    }

    public function getInfoById($id)
    {
        return $this->where('config_id', $id)->first();
    }

    /**
     * Cập nhật config theo key
     *
     * @param array $data
     * @param $key
     * @return mixed
     */
    public function editByKey(array $data, $key)
    {
        return $this->where('key', $key)->update($data);
    }

    /**
     * Lấy chi nhánh mặc định khi đơn hàng ko có chi nhánh
     *
     * @return mixed
     */
    public function getBranchApplyOrder()
    {
        return $this
            ->select(
                "branches.branch_id",
                "branches.branch_code"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.value")
            ->where("{$this->table}.key", self::keyBranchOrder)
            ->first();
    }

    public function getAllKey(){
        return $this->get()->pluck('value', 'key')->toArray();
    }
}
