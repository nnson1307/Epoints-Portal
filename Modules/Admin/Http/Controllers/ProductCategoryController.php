<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/29/2018
 * Time: 12:17 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\ProductCategory\ProductCategoryRepositoryInterface;

class ProductCategoryController extends Controller
{
    protected $productCategory;

    public function __construct(ProductCategoryRepositoryInterface $productCategory)
    {
        $this->productCategory = $productCategory;
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
        $productCategoryList = $this->productCategory->list($filters);
        return view(
            'admin::product-category.list',
            [
                'LIST' => $productCategoryList,
                'page' => $filters['page']
            ]
        );
    }

    public function indexAction()
    {
        $productCategoryList = $this->productCategory->list();
        return view('admin::product-category.index', [
            'LIST' => $productCategoryList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Show modal thêm loại sản phẩm
     *
     * @return mixed
     */
    public function showModalAddAction()
    {
        return $this->productCategory->showModalAdd();
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->categoryName;
            $code = $request->categoryCode ?? '';
            $item = $this->productCategory->testProductCategoryName(0, $name);
            if ($code != '') {
                $objCate = $this->productCategory->checkProductCategoryCode(0, $code);
                if ($objCate != null) {
                    return response()->json(
                        [
                            'status'    => 0,
                            'message'   => __('Mã code danh mục đã tồn tại')
                        ]
                    );
                }
            }
            if ($this->productCategory->testIsDeleted($name) != null) {
                $this->productCategory->editByName($name);
                return response()->json(['status' => 1]);
            } else {
                if ($item == null) {
                    $data = [
                        'category_name' => $name,
                        'category_code' => $code,
                        'description' => $request->description,
                        'is_actived' => $request->isActived,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'slug' => str_slug($name),
                        'icon_image' => $request->icon_image
                    ];

                    $id = $this->productCategory->add($data);
                    $category = $this->productCategory->getAll();

                    return response()->json(
                        [
                            'status'   => 1,
                            'category' => $category,
                            'id'       => $id,
                        ]
                    );
                } else {
                    return response()->json(
                        [
                            'status'    => 0,
                            'message'   => __('Danh mục đã tồn tại')
                        ]
                    );
                }
            }
        }
    }

    public function removeAction($id)
    {
        $this->productCategory->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * Show modal chỉnh sửa loại sản phẩm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            //Lấy thông tin loại sản phẩm
            $item = $this->productCategory->getItem($request->id);

            $data = [
                'categoryName' => $item->category_name,
                'categoryCode' => $item->category_code,
                'description' => $item->description,
                'isActived' => $item->is_actived,
                'categoryId' => $request->id,
                'iconImage' => $item->icon_image
            ];

            $html = \View::make('admin::product-category.edit', $data)->render();

            return response()->json([
                'html' => $html
            ]);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $name = $request->categoryName;
            $code = $request->categoryCode ?? '';
            $test = $this->productCategory->testProductCategoryName($id, $name);
            $testIsDeleted = $this->productCategory->testIsDeleted($name);
            if ($code != '') {
                $objCate = $this->productCategory->checkProductCategoryCode($id, $code);
                if ($objCate != null) {
                    return response()->json(
                        [
                            'status'    => 0,
                            'message'   => __('Mã code danh mục đã tồn tại')
                        ]
                    );
                }
            }
            if ($request->parameter == 0) {
                if ($testIsDeleted != null) {
                    //Tồn tại danh mục sản phẩm trong db. is_deleted = 1.
                    return response()->json(['status' => 2]);
                } else {
                    if ($test == null) {
                        $data = [
                            'updated_by' => Auth::id(),
                            'category_name' => $request->categoryName,
                            'category_code' => $code,
                            'description' => $request->description,
                            'is_actived' => $request->isActived,
                            'slug' => str_slug($request->categoryName),
                            'icon_image' => $request->icon_image
                        ];
                        $this->productCategory->edit($data, $id);
                        return response()->json(['status' => 1]);
                    } else {

                        return response()->json([
                            'status'    => 0,
                            'message'   => __('Danh mục đã tồn tại')
                        ]);
                    }
                }
            } else {
                //Kích hoạt lại danh mục sản phẩm.
                $this->productCategory->edit(['is_deleted' => 0], $testIsDeleted->product_category_id);
                return response()->json(['status' => 3]);
            }
        }
    }

    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->productCategory->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}