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

class PeopleDeletableTable extends Model
{
    protected $table = "people_deletable";
    protected $primaryKey = "people_deletable_id";
    protected $fillable = [
        "people_object_group_id",
        "people_object_id",
        "people_id"
    ];
    public $timestamps = false;

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
            "{$this->table}.people_object_id"
        );

        // relationship
        $query = $query->leftJoin('people_object_group','people_object_group.people_object_group_id',"{$this->table}.people_object_group");
        $query = $query->leftJoin('people_object',"people_object.people_object_id","{$this->table}.people_object_id");

        // filter primaryKey
        if (isset($param[$this->primaryKey]) && $param[$this->primaryKey] ) {
            $query = $query->where("{$this->table}.{$this->primaryKey}",$param[$this->primaryKey]);
        }

        // filter people_object_group_id
        if (isset($param['people_object_group_id']) && $param['people_object_group_id'] ) {
            $query = $query->where("people_object_group.people_object_group_id",$param['people_object_group_id'] );
        }

        // filter people_object_id
        if (isset($param['people_object_id']) && $param['people_object_id'] ) {
            $query = $query->where("people_object.people_object_id",$param['people_object_id'] );
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
        }

        return $query;
    }

    /**
     * check deletable
     *
     * @param array $filter
     * @return mixed
     */
    public function peopleDeletable($param = [])
    {
        return $this->queryBuild($param)->first();
    }

    /**
     * Thêm data có thể xoá
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}
