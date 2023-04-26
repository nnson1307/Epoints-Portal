<?php


namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class BrandTable extends Model
{
    use ListTableTrait;
    protected $table = 'brand';
    protected $primaryKey = 'brand_id';
    protected $fillable = [
        'brand_id',
        'parent_id',
        'tenant_id',
        'brand_name',
        'brand_code',
        'brand_url',
        'brand_avatar',
        'brand_banner',
        'brand_about',
        'company_name',
        'position',
        'display_name',
        'is_published',
        'is_activated',
        'is_deleted',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function getListCore($filter = [])
    {
        $ds = $this->select(
            'brand_id',
            'brand_name',
            'brand_avatar',
            'brand_url',
            'tenant_id',
            'brand_code',
            'company_name',
            'is_activated',
            'is_published'
        )
            ->where('is_deleted', 0);
        return $ds;
    }

    /**
     * Lấy danh sách thương hiệu không phân trang
     *
     * @param array $filter
     * @return mixed
     */
    public function getListAll(array $filter = [])
    {
        $result = $this->getListCore();

        if (count($filter) > 0) {
            foreach ($filter as $column => $value) {
                $result->where($this->table.'.'.$column, $value);
            }
        }

        return $result->get();
    }

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->brand_id;
    }

    public function getItem($id)
    {
        return $this->where('brand_id', $id)->first();
    }

    public function edit(array $data, $id)
    {
        return $this->where('brand_id', $id)->update($data);
    }

    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    /**
     * Lấy danh sách
     *
     * @param null $filter
     * @return array
     */
    public function getList($filter = null)
    {
        $list = $this->where('is_deleted', 0);

        if (isset($filter['brand_name']) && $filter['brand_name'] != null) {
            $list->where('brand_name', 'like', '%'.$filter['brand_name'].'%');
        }
        if (isset($filter['brand_code']) && $filter['brand_code'] != null) {
            $list->where('brand_code', 'like', '%'.$filter['brand_code'].'%');
        }
        if (isset($filter['company_name']) && $filter['company_name'] != null) {
            $list->where('company_name', 'like', '%'.$filter['company_name'].'%');
        }
        if (isset($filter['is_activated']) && $filter['is_activated'] != null && $filter['is_activated'] != -1) {
            $list->where('is_activated', $filter['is_activated']);
        }
        if (isset($filter['is_published']) && $filter['is_published'] != null && $filter['is_published'] != -1) {
            $list->where('is_published', $filter['is_published']);
        }
//        $nonPaging = $list->orderBy('created_at', 'desc')->get();
        $page = (isset($filter['page'])) ? $filter['page'] : 1;
        $paging = $list->orderBy('created_at', 'desc')->paginate(
            END_POINT_PAGING,
            ['*'],
            'page',
            $page
        );

        return ['paging' => $paging];
    }

    /**
     * Kiểm tra brand url đã tồn tại chưa
     *
     * @param string $brandUrl
     * @param int $id
     * @return mixed
     */
    public function checkBrandUrl($brandUrl, $id = 0)
    {
        $result = $this->where('brand_url', $brandUrl)
            ->where($this->primaryKey, '<>', $id)
            ->where('is_deleted', 0)
            ->get();

        return $result;
    }

    /**
     * Lấy thông brand theo tenant_id
     *
     * @param int $tenant_id
     * @return mixed
     */
    public function getDetailBy($tenant_id)
    {
        $result = $this->where('tenant_id', $tenant_id)
            ->where('is_deleted', 0)
            ->select($this->fillable)
            ->first();

        return $result;
    }

    /**
     * Lấy chi tiết theo tenant id
     *
     * @param $tenantId
     * @return mixed
     */
    public function getItemByTenantId($tenantId)
    {
        return $this->where('tenant_id', $tenantId)->first();
    }
}
