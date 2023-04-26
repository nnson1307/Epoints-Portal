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

class PeopleObjectGroupTable extends Model
{
    protected $table = "people_object_group";
    protected $primaryKey = "people_object_group_id";
    protected $fillable = [
        "name",
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
            "{$this->table}.name",
            "{$this->table}.created_by",
            "{$this->table}.is_active",
            "{$this->table}.is_deleted",
            "{$this->table}.is_skip",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "staffs.full_name as full_name",
            "people_deletable.people_deletable_id as deletable"
            );
        // relationship
        $query = $query->leftJoin('staffs','staffs.staff_id',"{$this->table}.created_by");
        $query = $query->leftJoin('people_deletable',"people_deletable.{$this->primaryKey}","{$this->table}.{$this->primaryKey}");

        // filter primaryKey
        if (isset($param[$this->primaryKey]) && $param[$this->primaryKey] ) {
            $query = $query->where("{$this->table}.{$this->primaryKey}",$param[$this->primaryKey]);
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
    public function objectGroup($param = [])
    {
        return $this->queryBuild($param)->first();
    }


}
