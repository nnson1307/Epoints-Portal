<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class RoleGroupTable extends Model
{
    use ListTableTrait;
    protected $table = 'role_group';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'name', 'slug',
        'is_actived', 'created_at', 'updated_at'];

    /**
     * lấy danh sách quyền
     * @return mixed
     */
    public function getAll(){
        return $this
            ->select(
                $this->table.'.id',
                $this->table.'.name',
                'manage_role.manage_role_id',
                'manage_role.is_all',
                'manage_role.is_branch',
                'manage_role.is_department',
                'manage_role.is_own'
            )
            ->leftJoin('manage_role','manage_role.role_group_id',$this->table.'.id')
            ->where($this->table.'.is_actived',1)
            ->orderBy($this->table.'.id','DESC')
            ->get();
    }

}