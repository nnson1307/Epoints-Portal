<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/29/2018
 * Time: 10:37 AM
 */

namespace Modules\Booking\Models;


use function Aws\filter;
use Illuminate\Database\Eloquent\Model;

class StaffsTable extends Model
{
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    protected $fillable = [
        'staff_id', 'department_id', 'branch_id', 'staff_title_id', 'user_name', 'password', 'salt', 'full_name',
        'birthday', 'gender', 'phone1', 'phone2', 'email', 'facebook', 'date_last_login', 'is_admin', 'is_actived',
        'is_deleted', 'staff_avatar', 'address', 'created_by', 'updated_by', 'created_at', 'updated_at', 'remember_token'
    ];

    /**
     * @return mixed
     */
    public function bookingGetTechnician($filters)
    {
//        $page = (int)($filters['page'] ?? 1);
//        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->leftJoin('departments', 'departments.department_id', '=', 'staffs.department_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', 'staffs.staff_title_id')
            ->select('staffs.staff_id as staff_id',
                'departments.department_name as department_name',
                'branches.branch_name as branch_name',
                'staff_title.staff_title_name as staff_title_name',
                'staffs.user_name as account',
                'staffs.salt as salt',
                'staffs.full_name as name',
                'staffs.birthday as birthday',
                'staffs.gender as gender', 'staffs.phone1 as phone1',
                'staffs.phone2 as phone2',
                'staffs.email as email',
                'staffs.facebook as facebook',
                'staffs.date_last_login as date_last_login',
                'staffs.is_admin as is_admin',
                'staffs.is_actived as is_actived',
                'staffs.staff_avatar as staff_avatar',
                'staffs.address as address',
                'staffs.branch_id as branch_id'
            )
            ->where('staffs.branch_id', $filters['branch_id'])
            ->where('staffs.is_deleted', 0);
//        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        return $select->get();
    }
}