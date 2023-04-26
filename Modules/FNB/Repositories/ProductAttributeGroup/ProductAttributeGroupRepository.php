<?php


namespace Modules\FNB\Repositories\ProductAttributeGroup;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\ProductAttributeGroupTable;

class ProductAttributeGroupRepository implements ProductAttributeGroupRepositoryInterface
{
    private $productAttributeGroup;

    public function __construct(ProductAttributeGroupTable $productAttributeGroup)
    {
        $this->productAttributeGroup = $productAttributeGroup;
    }

    /**
     * Lấy chi tiết attribute group
     * @param $id
     */
    public function getDetail($id){
        $detail = $this->productAttributeGroup->getItem($id);

        $view = view('fnb::product-attribute-group.edit',['detail' => $detail])->render();

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

            $id = $data['product_attribute_group_id'];
            $name = $data['product_attribute_group_name_en'];

            $data = [
                'product_attribute_group_name_en' => $name,
                'updated_by' => Auth::id(),
                'slug_en'=>str_slug($name)
            ];
            $this->productAttributeGroup->edit($data, $id);

            return [
                'error' => false,
                'message' => __('Cập nhật nhóm thuộc tính sản phẩm thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Cập nhật nhóm thuộc tính sản phẩm thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

}