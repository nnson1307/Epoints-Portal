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

class PeopleVerifyTable extends Model
{
    protected $table = "people_verify";
    protected $primaryKey = "people_verify_id";
    protected $fillable = [
        "people_verify_id",
        "people_verification_id",
        "people_id",
        "age",
        "people_object_id",
        "content",
        "people_health_type_id",
        "note",
    ];

    /**
     * tự làm query builder
     *
     * @param array $filter
     * @return mixed
     */
    public function queryBuild($param = []){
        $query = $this->select([
            "{$this->table}.people_verify_id",
            "{$this->table}.people_verification_id",
            "{$this->table}.people_id",
            "{$this->table}.age",
            "{$this->table}.people_object_id",
            "{$this->table}.content",
            "{$this->table}.people_health_type_id",
            "{$this->table}.note",
            "people_verification.name as people_verification_name",
            "people_health_type.name as people_health_type_name",
            "people_object.name as people_object_name",
            "people_object_group.name as people_object_group_name",
            "people.birthday",
        ]);
        // relationship
        $query = $query->leftJoin('people','people.people_id',"{$this->table}.people_id");
        $query = $query->leftJoin('people_verification','people_verification.people_verification_id',"{$this->table}.people_verification_id");
        $query = $query->leftJoin('people_health_type','people_health_type.people_health_type_id',"{$this->table}.people_health_type_id");
        $query = $query->leftJoin('people_object','people_object.people_object_id',"{$this->table}.people_object_id");
        $query = $query->leftJoin('people_object_group','people_object_group.people_object_group_id',"people_object.people_object_group_id");

        // filter primaryKey
        if (isset($param[$this->primaryKey]) && $param[$this->primaryKey] ) {
            $query = $query->where("{$this->table}.{$this->primaryKey}",$param[$this->primaryKey]);
        }

        // filter people_id
        if (isset($param['people_id']) ) {
            $query = $query->where("{$this->table}.people_id",$param['people_id']);
        }

        // filter people_verification_id
        if (isset($param['people_verification_id']) ) {
            $query = $query->where("{$this->table}.people_verification_id",$param['people_verification_id']);
        }

        // filter people_object_id
        if (isset($param['people_object_id']) ) {
            $query = $query->where("{$this->table}.people_object_id",$param['people_object_id']);
        }

        // filter search
        if (isset($param['search']) && $param['search'] ) {
            $search = $param['search'];
            $query = $query->where(function ($condition)use($search){
                $condition->where("{$this->table}.full_name","LIKE","%{$search}%");
                $condition->orWhere("{$this->table}.code","LIKE","%{$search}%");
                $condition->orWhere("{$this->table}.id_number","LIKE","%{$search}%");
            });
        }

        // filter age
        if (isset($param['age']) && $param['age'] ) {
            $fromYear = Carbon::now()->subYear($param['age']+1) ->format('Y-d-m');
            $toYear = Carbon::now()->subYear($param['age']) ->format('Y-d-m');
            $query = $query->where(function ($condition)use($fromYear,$toYear){
                $condition->where("{$this->table}.birthday",">",$fromYear);
                $condition->where("{$this->table}.birthday","<",$toYear);
            });
        }

        // filter created_at
        if (isset($param['created_at']) && $param['created_at'] ) {
            $time = explode(" - ",$param['created_at']);
            $from = Carbon::createFromFormat('d/m/Y',$time[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y',$time[1])->format('Y-m-d');

            $query = $query->where(function ($where) use ($from,$to) {
                $where->where("{$this->table}.created_at",">=", $from);
                $where->where("{$this->table}.created_at","<=", $to);
            });
        }

        // sort
        if (isset($param['sort']) && $param['sort'] ) {
            $sort = $param['sort'];
            $query = $query->orderBy($sort[0],$sort['1']);
            unset($param['sort']);
        }else{
            $query = $query->orderBy('people_verification.date','DESC');
        }

        return $query;
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
        $per_page = $param['per_page']??10;
        $current_page = $param['current_page']??1;


        return $query->paginate($per_page,'*','page',$current_page);
    }

    /**
     * Chi tiết nhóm đối tượng
     *
     * @param array $filter
     * @return mixed
     */
    public function detail($param = [])
    {
        return $this->queryBuild($param)->first();
    }

    public function listReport($filter, $paginate = false){

        $per_page = 10;
        $oSelect = $this->from($this->table.' as pv')
            ->select('p.full_name',
                'pv.content',
                'p.birthday',
                'p.id_number',
                'p.id_license_date',
                'p.temporary_address',
                'p.specialized',
                'p.foreign_language',
                'p.group_join_date',
                'p.union_join_date',
                'p.group',
                'p.quarter',
                'po.people_object_id as people_object_id',
                'po.name as people_object_name',
                'po.code as people_object_code',
                'pog.name as people_object_group_name',
                'pog.people_object_group_id as people_object_group_id',
                'p.educational_level_id',
                'p.permanent_address',
                'e.name as ethnic_name',
                'r.name as religion_name',
                'p.people_id',
                'p.code',
                'p.workplace',
                'el.name as educational_level_name',
                'pft.name as people_family_type_name',
                'pj.name as people_job_name'
            )
            ->join('people_verification as pvi', 'pv.people_verification_id', '=', 'pvi.people_verification_id')
            ->join('people_object as po', 'po.people_object_id', '=', 'pv.people_object_id')
            ->join('people_object_group as pog', 'pog.people_object_group_id', '=', 'po.people_object_group_id')
            ->join('people as p', 'p.people_id', 'pv.people_id')
            ->leftJoin('educational_level as el', 'el.educational_level_id', '=', 'p.educational_level_id')
            ->leftJoin('people_family_type as pft', 'pft.people_family_type_id', '=', 'p.people_family_type_id')
            ->leftJoin('ethnic as e', 'e.ethnic_id', "=", "p.ethnic_id")
            ->leftJoin('religion as r', 'r.religion_id', "=", "p.religion_id")
            ->leftJoin('people_job as pj', 'pj.people_job_id', "=", "p.people_job_id")
            ->where('pvi.year', $filter['year']);

        if($filter['people_object_group_id']??false){
            $oSelect = $oSelect->where('pog.people_object_group_id', $filter['people_object_group_id']);
        }

        $oSelect = $oSelect->orderBy('p.birthday','ASC');
        if($paginate){
            $page = $filter['page'];
            return $oSelect->paginate($per_page,'*','page' ,$page);
        }

        if($oSelect->get()){
            return $oSelect->get()->toArray();
        }
    }


}
