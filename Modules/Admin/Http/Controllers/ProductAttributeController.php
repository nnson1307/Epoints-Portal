<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/28/2018
 * Time: 9:44 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\ProductAttribute\ProductAttributeRepositoryInterface;
use Modules\Admin\Repositories\ProductAttributeGroup\ProductAttributeGroupRepositoryInterface;

class ProductAttributeController extends Controller
{
    protected $productAttribute;
    protected $productAttributeGroup;
    protected $code;

    public function __construct(
        ProductAttributeRepositoryInterface $productAttribute,
        ProductAttributeGroupRepositoryInterface $productAttributeGroup,
        CodeGeneratorRepositoryInterface $code
    )
    {
        $this->productAttribute = $productAttribute;
        $this->productAttributeGroup = $productAttributeGroup;
        $this->code = $code;
    }

    protected function filters()
    {
        $optionProductAttrGr = (['' => __('Nhóm thuộc tính')]) + $this->productAttributeGroup->getOption();
        return [
            'product_attributes$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ],
            'product_attribute_groups$product_attribute_group_id' => [
                'data' => $optionProductAttrGr
            ]
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type',
            'search_keyword', 'product_attributes$is_actived',
            'product_attribute_groups$product_attribute_group_id',
            'product_attributes$product_attribute_label', 'product_attributes$product_attribute_code']);
        $productAttributeList = $this->productAttribute->list($filters);
        return view('admin::product-attribute.list', [
                'LIST' => $productAttributeList,
                'page' => $filters['page']]
        );
    }

    public function indexAction()
    {
        $productAttributeList = $this->productAttribute->list();
        $optionProductAttrGr = $this->productAttributeGroup->getOption();
        return view('admin::product-attribute.index', [
            'LIST' => $productAttributeList,
            'FILTER' => $this->filters(),
            'PRODUCTATTRGROUP' => $optionProductAttrGr
        ]);
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $label = $request->productAttributeLabel;
            $attributeTypeLabel = $request->attributeTypeLabel;
            $code = $this->code->generateCodeRandom('TT');
            $checkExistUnDelete = $this->productAttribute->checkExist($request->productAttributeGroup_id, $label, 0);

            $checkExistDelete = $this->productAttribute->checkExist($request->productAttributeGroup_id, $label, 1);
            $message = [];
            if ($checkExistUnDelete != null) {
                $message ['label'] = 0;
                return response()->json($message);
            } else {
                if ($checkExistDelete != null) {
                    $this->productAttribute->edit(['is_deleted' => 0], $checkExistDelete->product_attribute_id);
                    $message ['label'] = 1;
                } else {
                    if ($checkExistUnDelete == null && $checkExistDelete == null) {
                        $data = [
                            'product_attribute_group_id' => $request->productAttributeGroup_id,
                            'product_attribute_label' => $label,
                            'product_attribute_code' => $code,
                            'is_actived' => 1,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'slug'=>str_slug($label),
                            'type'=>$attributeTypeLabel,
                        ];
                        $insert = $this->productAttribute->add($data);
                        $ma = '';
                        if ($insert < 10) {
                            $ma = "0";
                        }
                        $this->productAttribute->edit(['product_attribute_code' => $this->code->codeDMY('TT', $ma . '' . $insert)], $insert);
                        $message ['label'] = 1;
                        $message['productAttributeId'] = $insert;
                    }
                }
                return response()->json($message);
            }

        }
    }

    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $item = $this->productAttribute->getItem($id);
            $optionProductAttrGr = $this->productAttributeGroup->getOption();
            $jsonString = [
                'id' => $id,
                'product_attribute_group_id' => $item->product_attribute_group_id,
                'product_attribute_label' => $item->product_attribute_label,
                'is_actived' => $item->is_actived,
                'productAttributeGroup' => $optionProductAttrGr,
                'type' => $item->type,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $code = $request->productAttributeCode;
            $label = $request->productAttributeLabel;
            $attributeTypeLabel = $request->attributeTypeLabel;
            $productAttributeGroupId = $request->productAttributeGroup_id;
            $testIsDeleted = $this->productAttribute->checkExist($productAttributeGroupId, $label, 1);
            $testEdit = $this->productAttribute->testEdit($id, $productAttributeGroupId, $label);
            if ($request->parameter == 0) {
                if ($testIsDeleted != null) {
                    //Tồn tại tên nhóm khách hàng trong db. is_deleted = 1.
                    return response()->json(['status' => 2]);
                } else {
                    if ($testEdit == null) {
                        $data = [
                            'product_attribute_group_id' => $request->productAttributeGroup_id,
                            'product_attribute_label' => $request->productAttributeLabel,
                            'is_actived' => $request->isActived,
                            'updated_by' => Auth::id(),
                            'slug'=>str_slug($request->productAttributeLabel),
                            'type' => $attributeTypeLabel
                        ];
                        $this->productAttribute->edit($data, $id);
                        return response()->json(['status' => 1]);
                    } else if ($testEdit != null) {
                        return response()->json(['status' => 0]);
                    }
                }
            } else {
                //Kích hoạt lại tên nhóm khách hàng.
                $this->productAttribute->edit(['is_deleted' => 0], $testIsDeleted->product_attribute_id);
                return response()->json(['status' => 3]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->productAttribute->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->productAttribute->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function getProductAttributeByGroup(Request $request)
    {
        $idGroup = $request->id;
        $data = $this->productAttribute->getProductAttributeByGroup($idGroup);
        return response()->json($data);
    }
}