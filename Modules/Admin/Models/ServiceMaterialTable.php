<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/16/2018
 * Time: 3:52 PM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceMaterialTable extends Model
{
    use ListTableTrait;
    protected $table = 'service_materials';
    protected $primaryKey = 'service_material_id';
    protected $fillable = [
        'service_material_id', 'service_id', 'material_id', 'material_code', 'quantity', 'unit_id',
        'is_actived', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'service_material_type'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const PRODUCT = "product";
    const SERVICE = "service";

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->service_material_id;
    }

    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->updated(['is_deleted' => 1]);
    }

    public function deleteItem($id)
    {
        $this->where('service_id', $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where('service_materials.service_material_id', $id)->update($data);
    }

    public function getItem($id)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_materials.service_id')
            ->leftJoin('units', 'units.unit_id', '=', 'service_materials.unit_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'service_materials.material_id')
            ->select(
                'service_materials.material_id as material_id',
                'service_materials.material_code as material_code',
                'service_materials.quantity as quantity',
                'service_materials.is_actived as is_actived',
                'service_materials.created_at as created_at',
                'service_materials.updated_at as updated_at',
                'service_materials.created_by as created_by',
                'service_materials.updated_by as update_by',
                'units.name as name',
                'units.unit_id as unit_id',
                'product_childs.product_child_name as product_child_name',
                'service_materials.service_id as mate_service_id',
                'service_materials.service_material_id as service_material_id'
            )
            ->where('service_materials.is_deleted',0)
            ->where('service_materials.service_id', $id)
            ->where("service_material_type", self::PRODUCT)
            ->get();
        return $ds;
    }
    public function getSelectProduct($id)
    {
        $list = $this->leftJoin('products','products.product_id','=','service_materials.material_id')
            ->select(
                'service_materials.material_id as material_Id',
                'products.product_name as product_Name'
            )->where('service_materials.service_id',$id)->where('service_materials.is_deleted',0)->get()->toArray();
        return $list;
    }
    public function deleteWhenEdit(array $data,$id)
    {
        return $this->where('service_materials.service_material_id',$id)->update($data);
    }
    public function listPagingServiceDetail($id, &$filter = []){

        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_materials.service_id')
            ->leftJoin('units', 'units.unit_id', '=', 'service_materials.unit_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'service_materials.material_id')
            ->select('service_materials.material_id as material_id',
                'service_materials.material_code as material_code',
                'service_materials.quantity as quantity',
                'service_materials.is_actived as is_actived',
                'service_materials.created_at as created_at',
                'service_materials.updated_at as updated_at',
                'service_materials.created_by as created_by',
                'service_materials.updated_by as update_by',
                'units.name as name',
                'units.unit_id as unit_id',
                'product_childs.product_child_name as product_name',
                'service_materials.service_id as mate_service_id',
                'service_materials.service_material_id as service_material_id')
            ->where('service_materials.is_deleted',0)
            ->where('service_materials.service_id', $id);
        return $ds;
    }
    public function getListServiceDetail($id, array $filter = [])
    {
        $select = $this->listPagingServiceDetail($id, $filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword'])) {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display']);

        // filter list
        foreach ($filter as $key => $val) {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getSelectMaterialsService($id)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_materials.material_id')
            ->select(
                'service_materials.material_id as material_id',
                'service_materials.material_code as material_code',
                'service_materials.quantity as quantity',
                'service_materials.is_actived as is_actived',
                'service_materials.created_at as created_at',
                'service_materials.updated_at as updated_at',
                'service_materials.created_by as created_by',
                'service_materials.updated_by as update_by',
                'service_materials.service_id as mate_service_id',
                'service_materials.service_material_id as service_material_id',
                'services.service_name'
            )
            ->where('service_materials.is_deleted', 0)
            ->where('service_materials.service_material_type', self::SERVICE)
            ->where('service_materials.service_id', $id)
            ->get();
        return $ds;
    }

    /**
     * Lấy thông tin dịch vụ kèm theo
     *
     * @param $serviceId
     * @param $branchId
     * @return mixed
     */
    public function getServiceMaterial($serviceId, $branchId)
    {
        return $this
            ->select(
                "{$this->table}.material_id",
                "sv.service_name",
                "sv.service_code",
                "sbp.new_price"
            )
            ->join("services as sv", "sv.service_id", "=", "{$this->table}.material_id")
            ->join("service_branch_prices as sbp", "sbp.service_id", "=", "{$this->table}.material_id")
            ->where("{$this->table}.service_id", $serviceId)
            ->where("sv.is_actived", self::IS_ACTIVE)
            ->where("sv.is_deleted", self::NOT_DELETED)
            ->where("sbp.branch_id", $branchId)
            ->where("sbp.is_actived", self::IS_ACTIVE)
            ->get();
    }
}