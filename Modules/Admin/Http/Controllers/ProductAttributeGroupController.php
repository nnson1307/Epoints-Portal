<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/27/2018
 * Time: 4:13 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\ProductAttributeGroup\ProductAttributeGroupRepositoryInterface;

class ProductAttributeGroupController extends Controller
{
    protected $productAttributeGroup;

    public function __construct(ProductAttributeGroupRepositoryInterface $productAttributeGroup)
    {
        $this->productAttributeGroup = $productAttributeGroup;
    }

    protected function filters()
    {
        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $productAttributeGroupList = $this->productAttributeGroup->list($filters);
        return view('admin::product-attribute-group.list', [
                'LIST' => $productAttributeGroupList,
                'page'=>$filters['page']]
        );
    }

    public function indexAction()
    {
        $productAttrGroupList = $this->productAttributeGroup->list();
        return view('admin::product-attribute-group.index', [
            'LIST' => $productAttrGroupList,
            'FILTER' => $this->filters()
        ]);
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->productAttrName;
            $test = $this->productAttributeGroup->testProductAttGroupName($name, 0);
            if ($this->productAttributeGroup->testIsDeleted($name) != null) {
                $this->productAttributeGroup->editByName($name);
                return response()->json(['message' => '']);
            } else {
                if (empty($test)) {
                    $data = [
                        'product_attribute_group_name' => $request->productAttrName,
                        'is_actived' => $request->isActived,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'slug'=>str_slug($request->productAttrName)
                    ];
                    $this->productAttributeGroup->add($data);
                    return response()->json(['message' => '']);
                } else {
                    return response()->json(['message' => __('Nhóm thuộc tính đã tồn tại')]);
                }
            }
        }
    }

    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $item = $this->productAttributeGroup->getItem($id);
            $jsonString = [
                'id' => $id,
                'product_attribute_group_name' => $item->product_attribute_group_name,
                'is_actived' => $item->is_actived,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $name = $request->productAttrName;
            $test = $this->productAttributeGroup->testProductAttGroupName($name, $id);
            $testIsDeleted = $this->productAttributeGroup->testIsDeleted($name);

            if ($request->parameter == 0) {
                if ($testIsDeleted != null) {
                    //Tồn tại nhóm thuộc tính sản phẩm trong db. is_deleted = 1.
                    return response()->json(['status' => 2]);
                } else {
                    if ($test == null) {
                        $data = [
                            'product_attribute_group_name' => $request->productAttrName,
                            'is_actived' => intval($request->isActive),
                            'updated_by' => Auth::id(),
                            'slug'=>str_slug($request->productAttrName)
                        ];
                        $this->productAttributeGroup->edit($data, $id);
                        return response()->json(['status' => 1]);
                    } else {
                        return response()->json(['status' => 0]);
                    }
                }

            } else {
                //Kích hoạt lại nhóm thuộc tính sản phẩm.
                $this->productAttributeGroup->edit(['is_deleted' => 0], $testIsDeleted->product_attribute_group_id);
                return response()->json(['status' => 3]);
            }


        }
    }

    public
    function removeAction($id)
    {
        $this->productAttributeGroup->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public
    function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->productAttributeGroup->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}