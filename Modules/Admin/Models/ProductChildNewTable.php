<?php


namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductChildNewTable extends Model
{
    use ListTableTrait;

    protected $table = 'product_childs';
    protected $primaryKey = 'product_child_id';
    protected $fillable = [
        'product_child_id',
        'product_id',
        'product_code',
        'product_child_name',
        'unit_id',
        'cost',
        'price',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'is_actived',
        'slug',
        'is_sales',
        'type_app',
        'percent_sale',
        'is_display',
        'is_applied_kpi',
        'custom_1',
        'custom_2',
        'custom_3',
        'custom_4',
        'custom_5',
        'custom_6',
        'custom_7',
        'custom_8',
        'custom_9',
        'custom_10',
        'is_remind',
        'remind_value',
        'barcode',
        'product_child_sku'
    ];

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;
    const IS_SALE = 1;

    protected function _getList(&$filter = [])
    {
        $select = $this
            ->select(
                "{$this->table}.product_child_id",
                "{$this->table}.product_id",
                "{$this->table}.product_code",
                "{$this->table}.product_child_name",
                "{$this->table}.unit_id",
                "{$this->table}.cost",
                "{$this->table}.price",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.is_deleted",
                "{$this->table}.is_actived",
                "{$this->table}.is_display",
                "{$this->table}.is_surcharge",
                "units.name as unit_name",
                "{$this->table}.product_child_sku"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->leftJoin("units", "{$this->table}.unit_id", "=", "units.unit_id")
            ->where("{$this->table}.is_deleted", self::IS_NOT_DELETED)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("products.is_deleted", self::IS_NOT_DELETED)
            ->orderBy($this->primaryKey, 'desc');

        // filter tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $select->where(function ($query) use ($search) {
                $query->where("{$this->table}.product_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.product_child_name", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            if ($startTime == $endTime) {
                $select->whereBetween('product_childs.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            } else {
                $select->whereBetween('product_childs.created_at', [$startTime, $endTime]);
            }
        }
        unset($filter["search"]);
        unset($filter["created_at"]);
        return $select;
    }

    /**
     * Cập nhật product child theo id
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }


    /**
     * Lấy chi tiết sản phẩm con theo id
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this
            ->join('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('units', 'units.unit_id', '=', 'product_childs.unit_id')
            ->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->select(
                'product_childs.product_child_id',
                'product_childs.product_id',
                'product_childs.product_code',
                'product_childs.product_child_name',
                'product_childs.unit_id',
                'product_childs.cost',
                'product_childs.price',
                'product_childs.created_at',
                'product_childs.updated_at',
                'product_childs.created_by',
                'product_childs.updated_by',
                'product_childs.is_deleted',
                'product_childs.is_actived',
                'product_childs.slug',
                'product_childs.is_sales',
                'product_childs.type_app',
                'product_childs.percent_sale',
                'product_childs.is_display',
                'product_childs.is_surcharge',
                'product_childs.is_applied_kpi',
                'products.type_refer_commission',
                'products.refer_commission_value',
                'products.type_staff_commission',
                'products.staff_commission_value',
                'products.product_name',
                'product_categories.category_name',
                'units.name as unit_name',
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10",
                "{$this->table}.is_remind",
                "{$this->table}.remind_value",
                "{$this->table}.barcode",
                "{$this->table}.product_child_sku"
            )
            ->where('product_childs.product_child_id', $id)
            ->first();
    }

    public function checkExistsCustomDefineProductCode($customI, $customValue, $productChildId)
    {
        $data = $this->select(
            "product_child_id"
        )->where("product_child_id", "!=", $productChildId)
            ->where($customI, "=", $customValue)->get()->toArray();
        return $data;
    }

    public function checkExistProductSku($id, $sku)
    {
        $data = $this->select(
            "product_child_id"
        )->where("product_child_id", "!=", $id)
            ->where("product_child_sku", "=", $sku)->first();
        return $data;
    }
}