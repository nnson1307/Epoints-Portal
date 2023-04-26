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

class PeopleObjectTable extends Model
{
    protected $table = "people_object";
    protected $primaryKey = "people_object_id";
    protected $fillable = [
        "people_object_group_id",
        "name",
        "code",
        "created_by",
        "is_active",
        "is_deleted",
        "is_skip",
        "created_at",
        "updated_at",
    ];

    /**
     * tự làm query builder
     *
     * @param array $filter
     * @return mixed
     */
    public function queryBuild($param = []){
        $query = $this->select(
            "{$this->table}.{$this->primaryKey}",
            "{$this->table}.people_object_group_id",
            "{$this->table}.name",
            "{$this->table}.code",
            "{$this->table}.created_by",
            "{$this->table}.is_active",
            "{$this->table}.is_deleted",
            "{$this->table}.is_skip",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "staffs.full_name as full_name",
            "people_deletable.people_deletable_id as deletable",
            "people_object.name as people_object_name",
            "people_object_group.name as people_object_group_name"
            );
        // relationship
        $query = $query->leftJoin('staffs','staffs.staff_id',"{$this->table}.created_by");
        $query = $query->leftJoin('people_deletable',"people_deletable.{$this->primaryKey}","{$this->table}.{$this->primaryKey}");
        $query = $query->leftJoin('people_object_group',"people_object_group.people_object_group_id","{$this->table}.people_object_group_id");

        // filter primaryKey
        if (isset($param[$this->primaryKey]) && $param[$this->primaryKey] ) {
            $query = $query->where("{$this->table}.{$this->primaryKey}",$param[$this->primaryKey]);
        }

        // filter people_object_group_id
        if (isset($param['people_object_group_id']) ) {
            $query = $query->where("people_object_group.people_object_group_id",$param['people_object_group_id']);
        }

        // filter name
        if (isset($param['name']) && $param['name'] ) {
            $query = $query->where("{$this->table}.name","LIKE","%{$param['name']}%");
        }

        // filter is_active
        if (isset($param['is_active']) ) {
            $query = $query->where("{$this->table}.is_active",$param['is_active']);
        }

        // filter is_deleted
        if (isset($param['is_deleted']) ) {
            $query = $query->where("{$this->table}.is_deleted",$param['is_deleted']);
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
            $query = $query->orderBy($this->primaryKey,'DESC');
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
    public function object($param = [])
    {
        return $this->queryBuild($param)->first();
    }


}
