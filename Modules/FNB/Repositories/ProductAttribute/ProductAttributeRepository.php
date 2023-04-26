<?php


namespace Modules\FNB\Repositories\ProductAttribute;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\ProductAttributeTable;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    private $productAttribute;

    public function __contruct(ProductAttributeTable $productAttribute){
        $this->productAttribute = $productAttribute;
    }

    public function getDetail($id)
    {
        $productAttribute = app()->get(ProductAttributeTable::class);

        $detail = $productAttribute->getItem($id);

        $view = view('fnb::product-attribute.edit',['detail' => $detail])->render();

        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * Lưu thông tin
     * @param $data
     * @return mixed|void
     */
    public function update($data)
    {
        try {
            $productAttribute = app()->get(ProductAttributeTable::class);
            $id = $data['product_attribute_id'];
            $name = $data['product_attribute_label_en'];

            $data = [
                'product_attribute_label_en' => $name,
                'updated_by' => Auth::id(),
                'slug_en'=>str_slug($name)
            ];
            $productAttribute->edit($data, $id);

            return [
                'error' => false,
                'message' => __('Cập nhật thuộc tính sản phẩm thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Cập nhật thuộc tính sản phẩm thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách name của attribute
     * @param array $data
     */
    public function getNameAttribute($arrAttributeId = [])
    {
        $productAttribute = app()->get(ProductAttributeTable::class);
        return $productAttribute->getNameAttribute($arrAttributeId);
    }

}