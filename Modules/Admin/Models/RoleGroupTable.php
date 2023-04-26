<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/20/2019
 * Time: 5:01 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class RoleGroupTable extends Model
{
    use ListTableTrait;
    protected $table = 'role_group';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'slug', 'is_actived', 'created_at', 'updated_at'
    ];

    const ROLE_ADMIN_NAME = 'Admin';

    protected function _getList($filter = [])
    {
        $select = $this->select('id', 'name', 'slug', 'is_actived', 'created_at', 'updated_at')
            ->orderBy('id', 'desc');
        return $select;
    }

    public function getLists()
    {
        return $this->select('id', 'name', 'slug', 'is_actived', 'created_at', 'updated_at')->where('is_actived',1)->get();
    }

    public function add(array $data)
    {
        $o = $this->create($data);
        return $o->id;
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function checkName($name, $id)
    {
        $select = $this->where('slug', $name)->where('id', '<>', $id)->first();
        return $select;
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function getOptionActive()
    {
        return $this->select('id', 'name')->where('is_actived', 1)->get();
    }

    public function getOptionRoleContractActive()
    {
        $data = $this->select(
            "{$this->table}.id",
            "{$this->table}.name",
            "contract_role_data_config.role_data_type"
        )
            ->leftJoin("contract_role_data_config", "contract_role_data_config.role_group_id", "{$this->table}.id")
            ->where("{$this->table}.is_actived", 1);
        return $data->orderBy("{$this->table}.id")->get();
    }

    /**
     * Lấy nhóm quyền admin
     *
     * @return mixed
     */
    public function getRoleAdmin()
    {
        return $this->where("name", self::ROLE_ADMIN_NAME)->where("is_actived", 1)->first();
    }
}
//