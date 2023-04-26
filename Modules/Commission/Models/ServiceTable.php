<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/10/2022
 * Time: 14:27
 */

namespace Modules\Commission\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceTable extends Model
{
    use ListTableTrait;
    protected $table = 'services';
    protected $primaryKey = 'service_id';

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy ds dịch vụ có phân trang
     *
     * @param array $filter
     * @return mixed
     */
    public function getListChildPaginate($filter = [])
    {
        $ds = $this
            ->select
            (
                "{$this->table}.service_id",
                "{$this->table}.service_avatar",
                "{$this->table}.service_name",
                "{$this->table}.service_code",
                "{$this->table}.time",
                "{$this->table}.service_category_id",
            )
            ->leftJoin("service_categories as cate", "cate.service_category_id", '=', "{$this->table}.service_category_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("cate.is_actived", self::IS_ACTIVE)
            ->where("cate.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.service_id", 'desc');

        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('services.service_name', 'like', '%' . $search . '%')
                    ->orWhere('services.service_code', 'like', '%' . $search . '%')
                    ->where('services.is_deleted', 0);
            });
        }

        //filter loại dịch vụ
        if (isset($filter['object_group_id']) && $filter['object_group_id'] != null && $param['object_group_id'] != 'all') {
            $ds->where("{$this->table}.service_category_id", $filter['object_group_id']);
        }

        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);

        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}