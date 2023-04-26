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

class ManageRoleTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_role';
    protected $primaryKey = 'manage_role_id';

    protected $fillable = ['manage_role_id', 'role_group_id', 'is_all',
        'is_branch', 'is_department', 'is_own','created_at','created_by','updated_at','updated_by'];

    /**
     * Tạo cấu hình role
     * @param $data
     * @return mixed
     */
    public function createdRole($data){
        return $this->insert($data);
    }

    /**
     * Xoá role
     * @return bool|null
     * @throws \Exception
     */
    public function deleteRole(){
        return $this->whereNotNull('manage_role_id')->delete();
    }

}