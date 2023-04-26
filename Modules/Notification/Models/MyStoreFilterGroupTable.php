<?php


namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class MyStoreFilterGroupTable extends Model
{
    use ListTableTrait;

    protected $table = 'mystore_filter_group';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'is_active',
        'filter_group_type',
        'tenant_id',
        'is_brand',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'filter_condition_rule_A',
        'filter_condition_rule_B'
        ];

    protected function getListCore(&$filters = [])
    {
        $select = $this->where('is_active', 1);
        if (!isset($filters['is_brand'])) {
            $select->where('is_brand', 0);
        }
        if (isset($filters['sort_mystore_filter_group$name'])
        && isset($filters['sort_mystore_filter_group$filter_group_type'])
        && isset($filters['sort_mystore_filter_group$created_at'])) {
            if (
                $filters['sort_mystore_filter_group$name'] == null
                && $filters['sort_mystore_filter_group$filter_group_type'] == null
                && $filters['sort_mystore_filter_group$created_at'] == null
            ) {
                $select->orderBy('id', 'desc');
            }
        }
        
        return $select;
    }
    /**
     * Get option.
     * @param int $tenant_id
     * @return mixed
     */
    public function getOption($tenant_id = null)
    {
        $select = $this->where('is_active', 1)
            ->where('filter_group_type', 'user_define');

        if ($tenant_id != null) {
            $select->where('tenant_id', $tenant_id);
        } else {
            $select->where('is_brand', 0);
        }

        return $select->get();
    }

    /**
     * Thêm mới.
     * @param array $data
     *
     * @return mixed
     */
    public function store(array $data)
    {
        $oUser = $this->create($data);
        return $oUser->id;
    }

    /**
     * Get item.
     * @param $id
     * @param $tenant_id
     * @return mixed
     */
    public function getItem($id, $tenant_id = null)
    {
        $result = $this->where('id', $id);

        if ($tenant_id != null) {
            $result->where('tenant_id', $tenant_id);
        }

        return $result->first();
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    /**
     * Lấy danh sách modal
     * @by Minh
     * @param null $filter
     * @return array
     */
    public function getList($filter = null)
    {
        $list = $this->select($this->fillable);

        if (isset($filter['group_name']) && $filter['group_name'] != null) {
            $list->where('name', 'like', '%'.$filter['group_name'].'%');
        }
        if (isset($filter['group_type']) && $filter['group_type'] != null && $filter['group_type'] != -1) {
            $list->where('filter_group_type', $filter['group_type']);
        }

        $list->where('is_brand', $filter['is_brand']);
        $page = (isset($filter['page'])) ? $filter['page'] : 1;
        $paging = $list->orderBy('created_at', 'desc')->paginate(
            END_POINT_PAGING,
            ['*'],
            'page',
            $page
        );
        return ['paging' => $paging];
    }
}
