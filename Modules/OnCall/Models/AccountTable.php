<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/07/2021
 * Time: 13:43
 */

namespace Modules\OnCall\Models;


use Illuminate\Database\Eloquent\Model;

class AccountTable extends Model
{
    protected $table = "oc_account";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "user_name",
        "password",
        "is_actived",
        "enabled_webhook",
        "link_webhook",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin tài khoản
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this
            ->select(
                "id",
                "user_name",
                "password",
                "is_actived",
                "enabled_webhook",
                "link_webhook",
            )
            ->first();
    }

    /**
     * Tạo tài khoản
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data);
    }

    /**
     * Chỉnh sửa tài khoản
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("id", $id)->update($data);
    }
}