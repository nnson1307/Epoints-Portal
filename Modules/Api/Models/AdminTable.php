<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class AdminTable extends Model
{
    use ListTableTrait;

    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'admin_code','brand_id', 'type', 'full_name', 'account', 'email', '', 'password', 'remember_token',
        'default_menu', 'default_menu', 'is_actived', 'is_deleted', 'is_admin',
        'created_at', 'updated_at', 'deleted_at', 'deleted_by',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Lấy thông tin chi tiết
     *
     * @param int $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Chỉnh sửa thông tin tài khoản admin
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function changeStatus(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }
}
