<?php

/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 4:32 PM
 */

namespace Modules\People\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PeopleTable extends Model
{
    protected $table = "people";
    protected $primaryKey = "people_id";
    protected $fillable = [
        "people_id",
        "people_id",
        "full_name",
        "code",
        "birthday",
        "gender",
        "temporary_address",
        "permanent_address",
        "id_number",
        "id_license_date",
        "people_id_license_place_id",
        "hometown_id",
        "ethnic_id",
        "religion_id",
        "people_job_id",
        "birthplace_id",
        "group",
        "quarter",
        "people_family_type_id",
        "educational_level_id",
        "elementary_school",
        "middle_school",
        "high_school",
        "from_18_to_21",
        "from_21_to_now",
        "birth_year",
        "created_at",
        "updated_at",
        "is_deleted",
        "avatar",
        "hometown",
        "birthplace",
        "union_join_date",
        "group_join_date",
        "foreign_language",
        "graduation_year",
        "specialized",
        "workplace",
    ];

    const NOT_DELETED = 0;

    /**
     * tự làm query builder
     *
     * @param array $filter
     * @return mixed
     */
    public function queryBuild($param = [])
    {
        $query = $this->select(
            "{$this->table}.people_id",
            "{$this->table}.people_id",
            "{$this->table}.full_name",
            "{$this->table}.code",
            "{$this->table}.birthday",
            "{$this->table}.gender",
            "{$this->table}.temporary_address",
            "{$this->table}.permanent_address",
            "{$this->table}.id_number",
            "{$this->table}.id_license_date",
            "{$this->table}.people_id_license_place_id",
            "{$this->table}.hometown_id",
            "{$this->table}.ethnic_id",
            "{$this->table}.religion_id",
            "{$this->table}.people_job_id",
            "{$this->table}.birthplace_id",
            "{$this->table}.group",
            "{$this->table}.quarter",
            "{$this->table}.people_family_type_id",
            "{$this->table}.educational_level_id",
            "{$this->table}.elementary_school",
            "{$this->table}.middle_school",
            "{$this->table}.high_school",
            "{$this->table}.from_18_to_21",
            "{$this->table}.from_21_to_now",
            "{$this->table}.birth_year",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "{$this->table}.is_deleted",
            "{$this->table}.avatar",
            "{$this->table}.hometown",
            "{$this->table}.birthplace",
            "{$this->table}.union_join_date",
            "{$this->table}.group_join_date",
            "{$this->table}.foreign_language",
            "{$this->table}.graduation_year",
            "{$this->table}.specialized",
            "{$this->table}.workplace",
            "people_object.name as people_object_name",
            "people_object_group.name as people_object_group_name",
            "birthplace.name as birthplace_name",
            "hometown.name as hometown_name",
            "people_family_type.name as people_family_type_name",
            "ethnic.name as ethnic_name",
            "religion.name as religion_name",
            "educational_level.name as educational_level_name",
            "people_job.name as people_job_name",
            DB::raw("IF( people_verify.people_verify_id,1,0 ) as is_verified"),
            "{$this->table}.register_nvqs",
            "{$this->table}.date_register_nvqs",
            "{$this->table}.issuer_register_nvqs"
        );

        // relationship
        $people_verification_id = $param['people_verification_id'] ?? 0;
        $query = $query->leftJoin('people_verify', function ($join) use ($people_verification_id) {
            $join->on('people_verify.people_id', "{$this->table}.people_id");
            //$join->where('people_verify.people_verification_id',$people_verification_id);
        });
        $query = $query->leftJoin('people_verification', 'people_verification.people_verification_id', "people_verify.people_verification_id");

        $query = $query->leftJoin('people_object', 'people_object.people_object_id', "people_verify.people_object_id");
        $query = $query->leftJoin('people_object_group', 'people_object_group.people_object_group_id', "people_object.people_object_group_id");
        $query = $query->leftJoin('province as birthplace', 'birthplace.provinceid', "{$this->table}.birthplace_id");
        $query = $query->leftJoin('province as hometown', 'hometown.provinceid', "{$this->table}.hometown_id");
        $query = $query->leftJoin('ethnic', 'ethnic.ethnic_id', "{$this->table}.ethnic_id");
        $query = $query->leftJoin('religion', 'religion.religion_id', "{$this->table}.religion_id");
        $query = $query->leftJoin('people_family_type', 'people_family_type.people_family_type_id', "{$this->table}.people_family_type_id");
        $query = $query->leftJoin('educational_level', 'educational_level.educational_level_id', "{$this->table}.educational_level_id");
        $query = $query->leftJoin('people_job', 'people_job.people_job_id', "{$this->table}.people_job_id");



        // filter primaryKey
        if (isset($param[$this->primaryKey]) && $param[$this->primaryKey]) {
            $query = $query->where("{$this->table}.{$this->primaryKey}", $param[$this->primaryKey]);
        }

        // filter people_object_id
        if (isset($param['people_object_id'])) {
            $query = $query->where("people_object.people_object_id", $param['people_object_id']);
        }

        // filter people_verification_year
        if (isset($param['people_verification_year'])) {
            $query = $query->where("people_verification.year", $param['people_verification_year']);
        }

        // filter people_object_group_id
        if (isset($param['people_object_group_id'])) {
            $query = $query->where("people_object.people_object_group_id", $param['people_object_group_id']);
        }

        // filter is_verified
        if (isset($param['is_verified'])) {
            if ($param['is_verified'] == 1) {
                $query = $query->whereNotNull("people_verify.people_verify_id");
            } else {
                $query = $query->whereNull("people_verify.people_verify_id");
            }
        }

        // filter search
        if (isset($param['search']) && $param['search']) {
            $search = $param['search'];
            $query = $query->where(function ($condition) use ($search) {
                $condition->where("{$this->table}.full_name", "LIKE", "%{$search}%");
                $condition->orWhere("{$this->table}.code", "LIKE", "%{$search}%");
                $condition->orWhere("{$this->table}.id_number", "LIKE", "%{$search}%");
            });
        }

        // filter age
        if (isset($param['age']) && $param['age']) {
            $fromYear = Carbon::now()->subYear($param['age'] + 1)->format('Y-d-m');
            $toYear = Carbon::now()->subYear($param['age'])->format('Y-d-m');
            $query = $query->where(function ($condition) use ($fromYear, $toYear) {
                $condition->where("{$this->table}.birthday", ">", $fromYear);
                $condition->where("{$this->table}.birthday", "<", $toYear);
            });
        }

        // filter is_active
        if (isset($param['is_active'])) {
            $query = $query->where("{$this->table}.is_active", $param['is_active']);
        }

        // filter is_deleted
        if (isset($param['is_deleted'])) {
            $query = $query->where("{$this->table}.is_deleted", $param['is_deleted']);
        }

        // filter created_at
        if (isset($param['created_at']) && $param['created_at']) {
            $time = explode(" - ", $param['created_at']);
            $from = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');

            $query = $query->where(function ($where) use ($from, $to) {
                $where->where("{$this->table}.created_at", ">=", $from);
                $where->where("{$this->table}.created_at", "<=", $to);
            });
        }

        //filter năm phúc tra
        if (isset($param['people_verification_id']) && $param['people_verification_id'] != null) {
            $query->where("people_verify.people_verification_id", $param['people_verification_id']);
        }


        // sort
        if (isset($param['sort']) && $param['sort']) {
            $sort = $param['sort'];
            $query = $query->orderBy($sort[0], $sort['1']);
            unset($param['sort']);
        } else {
            $query = $query->orderBy($this->primaryKey, 'DESC');
        }

        return $query->groupBy("{$this->table}.people_id");
    }

    /**
     * Danh sách nhóm đối tượng có phân trang
     *
     * @param array $filter
     * @return mixed
     */
    public function getPaginate($param = [])
    {
        $query = $this->queryBuild($param);
        // paginate
        $per_page = $param['per_page'] ?? 10;
        $current_page = $param['current_page'] ?? 1;

        return $query->paginate($per_page, '*', 'page', $current_page);
    }

    /**
     * Chi tiết nhóm đối tượng
     *
     * @param array $filter
     * @return mixed
     */
    public function object($param = [])
    {
        return $this->queryBuild($param)->first();
    }

    /**
     * Kiểm tra CMND đã tồn tại chưa
     *
     * @param $idNumber
     * @return mixed
     */
    public function checkUniqueIdNumber($idNumber)
    {
        return $this
            ->where("id_number", $idNumber)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Kiểm tra mã hồ sơ trùng
     *
     * @param $code
     * @return mixed
     */
    public function checkUniqueCode($code)
    {
        return $this
            ->where("code", $code)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Thêm lý lịch công dân
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->people_id;
    }

     /**
     * Chỉnh sửa lý lịch công dân
     *
     * @param array $data
     * @param peopleId
     * @return mixed
     */
    public function edit(array $data, $peopleId)
    {
        return $this->where("people_id", $peopleId)->update($data);
    }
}
