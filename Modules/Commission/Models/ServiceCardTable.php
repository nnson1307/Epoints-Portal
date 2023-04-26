<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/10/2022
 * Time: 14:27
 */

namespace Modules\Commission\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardTable extends Model
{
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách thẻ dịch vụ
     *
     * @param array $filter
     * @return mixed
     */
    public function getListChildPaginate($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.service_card_id",
                "{$this->table}.name",
                "{$this->table}.code"
            )
            ->join("service_card_groups as g", "g.service_card_group_id", "=", "{$this->table}.service_card_group_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("g.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.service_card_id", "desc");

        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.code", 'like', '%' . $search . '%');
            });

        }

        //filter nhóm thẻ dv
        if (isset($filter['object_group_id']) && $filter['object_group_id'] != null && $param['object_group_id'] != 'all') {
            $ds->where("{$this->table}.service_card_group_id", $filter['object_group_id']);
        }

        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);

        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}