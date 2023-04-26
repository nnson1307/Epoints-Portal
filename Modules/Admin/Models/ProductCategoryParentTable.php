<?php
/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 4:32 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductCategoryParentTable extends Model
{
    protected $table = "product_category_parents";
    protected $primaryKey = "product_category_parent_id";
    protected $fillable = [
        "product_category_parent_name",
        "icon_image",
        "is_actived",
        "is_deleted",
    ];

    const NOT_DELETED = 0;

    /**
     * tự làm query builder
     *
     * @param array $filter
     * @return mixed
     */
    public function queryBuild($param = []){
        $query = $this->select(
            "{$this->table}.product_category_parent_id",
            "{$this->table}.product_category_parent_name",
            "{$this->table}.icon_image",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.created_at",
            "{$this->table}.updated_at"
            );

        // filter primaryKey
        if (isset($param[$this->primaryKey]) && $param[$this->primaryKey] ) {
            $query = $query->where("{$this->table}.{$this->primaryKey}",$param[$this->primaryKey]);
        }

        // filter search
        if (isset($param['search']) && $param['search'] ) {
            $search = $param['search'];
            $query = $query->where(function ($condition)use($search){
                $condition->where("{$this->table}.product_category_parent_name","LIKE","%{$search}%");
                $condition->orWhere("{$this->table}.product_category_parent_name","LIKE","%{$search}%");
            });
        }

        // filter is_actived
        if (isset($param['is_actived']) ) {
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
     * Danh sách có phân trang
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
     * Chi tiết
     *
     * @param array $filter
     * @return mixed
     */
    public function getItem($param = [])
    {
        return $this->queryBuild($param)->first();
    }


    /**
     * Thêm lý lịch công dân
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->product_category_parent_id;
    }
}
