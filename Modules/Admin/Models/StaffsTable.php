<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/29/2018
 * Time: 10:37 AM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class StaffsTable extends Model
{
    use ListTableTrait;
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    protected $fillable = [
        'staff_id',
        'department_id',
        'branch_id',
        'staff_title_id',
        'user_name',
        'password',
        'salt',
        'full_name',
        'birthday',
        'gender',
        'phone1',
        'phone2',
        'email',
        'facebook',
        'date_last_login',
        'is_admin',
        'is_actived',
        'is_deleted',
        'staff_avatar',
        'address',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'remember_token',
        'staff_code',
        'salary',
        'subsidize',
        'commission_rate',
        'staff_type',
        'password_chat',
        'bank_number',
        'bank_name',
        'bank_branch_name',
        'token_md5',
        'team_id'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * @return mixed
     */
    protected function _getList($filter = [])
    {
        $ds = $this->leftJoin('departments', 'departments.department_id', '=', 'staffs.department_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', 'staffs.staff_title_id')
            ->select(
                'staffs.staff_id as staff_id',
                'departments.department_name as department_name',
                'branches.branch_name as branch_name',
                'staff_title.staff_title_name as staff_title_name',
                'staffs.user_name as account',
                'staffs.salt as salt',
                'staffs.full_name as name',
                'staffs.birthday as birthday',
                'staffs.gender as gender',
                'staffs.phone1 as phone1',
                'staffs.phone2 as phone2',
                'staffs.email as email',
                'staffs.facebook as facebook',
                'staffs.date_last_login as date_last_login',
                'staffs.is_admin as is_admin',
                'staffs.is_actived as is_actived',
                'staffs.staff_avatar as staff_avatar',
                'staffs.address as address'
            )
            ->where('staffs.is_deleted', 0)
            ->where('staffs.is_master', 0)
            ->orderBy('staffs.staff_id', 'desc');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('staffs.full_name', 'like', '%' . $search . '%')
                    ->orWhere('staffs.user_name', 'like', '%' . $search . '%')
                    ->orWhere('staffs.email', 'like', '%' . $search . '%')
                    ->where('staffs.is_deleted', 0);
            });
        }
        return $ds;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->staff_id;
    }
    //function xoa

    /**
     * @param $id
     */
    public function remove($id)
    {
        $this->where("{$this->table}.staff_id", $id)->update(['is_deleted' => 1]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this
            ->select(
                'staffs.*',
                'departments.department_name as department_name',
                'branches.branch_name as branch_name',
                'staff_title.staff_title_name as staff_title_name',
                'staffs.user_name as account',
                'staffs.salt as salt',
                'staffs.staff_type',
                'staffs.full_name as name',
                'staffs.birthday as birthday',
                'staffs.gender as gender',
                'staffs.phone1 as phone1',
                'staffs.phone2 as phone2',
                'staffs.email as email',
                'staffs.facebook as facebook',
                'staffs.date_last_login as date_last_login',
                'staffs.is_admin as is_admin',
                'staffs.is_actived as is_actived',
                'staffs.staff_avatar as staff_avatar',
                'staffs.address as address',
                'staffs.salary as salary',
                'staffs.subsidize as subsidize',
                'staffs.commission_rate as commission_rate',
                "{$this->table}.bank_number",
                "{$this->table}.bank_name",
                "{$this->table}.bank_branch_name",
                "{$this->table}.team_id"
            )
            ->leftJoin('departments', 'departments.department_id', '=', 'staffs.department_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', 'staffs.staff_title_id')
            ->where("{$this->table}.staff_id", $id)
            ->first();
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("{$this->table}.staff_id", $id)->update($data);
    }

    /**
     * @param $userName
     * @param $id
     * @return mixed
     */
    public function testUserName($userName, $id)
    {
        return $this->where('user_name', $userName)->where('staff_id', '<>', $id)->where('is_deleted', 0)->first();
    }

    /**
     * @return array
     */
    public function getName()
    {
        $oSelect = self::select("staff_id", "full_name")->where('is_deleted', 0)->get();
        return (["" => "Tất cả"]) + ($oSelect->pluck("full_name", "staff_id")->toArray());
    }

    /**
     * @return mixed
     */
    public function getStaffOption()
    {
        return $this->select('staff_id', 'full_name', 'address', 'phone1', 'phone2')->where('is_deleted', 0)->get()->toArray();
    }

    public function getStaffTechnician()
    {
        $ds = $this->select(
            'staff_id',
            'full_name',
            'address',
            'phone1',
            'phone2'
        )
            ->where('is_deleted', 0)
            //            ->where('staff_title_id', 2)
            ->where('branch_id', Auth::user()->branch_id)
            ->get()
            ->toArray();
        return $ds;
    }


    public function getStaffByBranch($branchId)
    {
        $ds = $this->select(
            'staff_id',
            'full_name',
            'address',
            'phone1',
            'phone2'
        )
            ->where('is_deleted', 0)
            ->where('branch_id', $branchId)
            ->get()
            ->toArray();
        return $ds;
    }

    public function getNameStaff($id)
    {
        return $this->where('staff_id', $id)->where('is_deleted', 0)->first();
    }

    /**
     * Lấy thông tin tất cả nhân viên
     *
     * @return mixed
     */
    public function getAllStaff()
    {
        return $this
            ->select(
                "{$this->table}.full_name",
                "{$this->table}.phone1 as phone",
                "{$this->table}.address",
                "branches.branch_name",
                "staff_title.staff_title_name",
                "{$this->table}.salary",
                "{$this->table}.subsidize",
                "{$this->table}.commission_rate"
            )
            ->leftJoin("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->join("staff_title", "staff_title.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->get();
    }

    /**
     * Lấy thông tin hoa hồng của nhân viên
     *
     * @param $idStaff
     * @return mixed
     */
    public function getCommissionStaff($idStaff)
    {
        return $this
            ->select(
                "staff_id",
                "commission_rate"
            )
            ->where("staff_id", $idStaff)
            ->where('is_deleted', 0)
            ->first();
    }

    /**
     * lấy tất cả nhân viên và phân trang khảo sát 
     * @param $filters
     * @return mixed
     */

    public function getAllStaffCondition($filters = [])
    {
        $select = $this->select(
            "{$this->table}.staff_id",
            "{$this->table}.full_name",
            "{$this->table}.phone1",
            "{$this->table}.phone2",
            "{$this->table}.staff_code",
            "{$this->table}.address",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "departments.department_name",
            "branches.branch_name",
            "staff_title.staff_title_name",

        );
        $select->where("{$this->table}.is_actived", 1)
            ->where("{$this->table}.is_deleted", 0);
        if (isset($filters['not_in'])) {
            $select->whereNotIn("{$this->table}.staff_id", $filters['not_in']);
            unset($filters['not_in']);
        }
        if (isset($filters['where_in'])) {
            $select->whereIn("{$this->table}.staff_id", $filters['where_in']);
            unset($filters['where_in']);
        }

        if (!empty($filters['nameOrCode'])) {
            $nameOrCode = $filters['nameOrCode'];
            $select->where(function ($query) use ($nameOrCode) {
                $query->where("{$this->table}.full_name", 'like', '%' . $nameOrCode . '%');
                $query->orWhere("{$this->table}.staff_code", $nameOrCode);
            });
            unset($filters['nameOrCode']);
        }

        if (!empty($filters['address'])) {
            $address = $filters['address'];
            $select->where("{$this->table}.address", 'like', '%' . $address . '%');
            unset($filters['address']);
        }

        if (!empty($filters['status'])) {
            $status = $filters['status'];
            if ($status == '1') {
                $select->where("{$this->table}.is_actived", 1)
                    ->where("{$this->table}.is_deleted", 0);
            } else {
                $select->where(function ($query) {
                    $query->where("{$this->table}.is_actived", 0)
                        ->orWhere("{$this->table}.is_deleted", 1);
                });
            }
            unset($filters['status']);
        }

        $select->leftJoin("departments", "departments.department_id", "{$this->table}.department_id");
        $select->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id");
        $select->leftJoin("staff_title", "staff_title.staff_title_id", "{$this->table}.staff_title_id");

        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        unset($filters['perpage']);
        unset($filters['page']);


        if ($filters) {
            // filter list
            foreach ($filters as $key => $val) {
                if (is_array($val) || trim($val) == '' || trim($val) == null) {
                    continue;
                }
                if (strpos($key, 'keyword_') !== false) {
                    $select->where(str_replace('$', '.', str_replace('keyword_', '', $key)), 'like', '%' . $val . '%');
                } elseif (strpos($key, 'sort_') !== false) {
                    $select->orderBy(str_replace('$', '.', str_replace('sort_', '', $key)), $val);
                } else {
                    $select->where(str_replace('$', '.', $key), $val);
                }
            }
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * lấy tất cả nhân viên 
     * @return mixed
     */

    public function getAll()
    {
        return $this->where("is_actived", 1)
            ->where("is_deleted", 0)
            ->orderBy('staff_id', 'DESC')
            ->get();
    }


    /**
     * lấy tất cả nhân viên theo điều kiện động
     * @param $filter
     * @param $type
     * @return mixed
     */

    public function getAllByConditionAuto($filters, $type)
    {
        $select = $this->where("is_deleted", 0)
            ->where("is_actived", 1);
        if ($type == 'and') {
            if (!empty($filters['condition_branch'])) {
                $select->whereIn('branch_id', $filters['condition_branch']);
                unset($filters['condition_branch']);
            }

            if (!empty($filters['condition_department'])) {
                $select->whereIn('department_id', $filters['condition_department']);
                unset($filters['condition_department']);
            }

            if (!empty($filters['condition_titile'])) {
                $select->whereIn('staff_title_id', $filters['condition_titile']);
                unset($filters['condition_titile']);
            }
        } else {

            $condtionBranch =  $filters['condition_branch'] ?? [];
            $condtionDepart = $filters['condition_department'] ?? [];
            $conditionTitile = $filters['condition_titile'] ?? [];
            $select->where(function ($query) use ($condtionBranch, $condtionDepart, $conditionTitile) {
                $query->orWhereIn('department_id', $condtionDepart);
                $query->orWhereIn('branch_id', $condtionBranch);
                $query->orWhereIn('staff_title_id', $conditionTitile);
            });
        }

        return $select->get();
    }

    /**
     * Lấy option nhân viên theo chức vụ
     *
     * @param $titleId
     * @return mixed
     */
    public function getOptionByTitle($titleId)
    {
        return $this
            ->select(
                "staff_id",
                "full_name"
            )
            ->where("staff_title_id", $titleId)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}
