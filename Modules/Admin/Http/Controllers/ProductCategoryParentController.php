<?php
/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 5:37 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Collection\CollectionRepoIf;
use Illuminate\Routing\Controller as BaseController;
use Modules\Admin\Repositories\ProductCategoryParent\ProductCategoryParentRepo;

class ProductCategoryParentController extends BaseController
{
    protected $productCategoryParent;

    public function __construct(
        ProductCategoryParentRepo $productCategoryParent
    )
    {
        $this->productCategoryParent = $productCategoryParent;
    }

    // Product Category Parent

    /**
     * Danh sách filter
     *
     * @return array
     */
    public function filters($param = [])
    {
        $result = [];


        if (in_array('product_category_parent_id', $param)) {
            $array = $this->productCategoryParent->getPaginate(['perpage'=>9999]) ?? [];
            $option[''] = 'Danh mục sản phẩm cha';
            $result['product_category_parent_id'] = [
                'data' => $option + $array,
            ];
        }

        return $result;
    }


    /**
     * Page danh sách
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function list(Request $request)
    {
        $field = [
  //          'product_category_parent_id',
        ];
        $filters = $this->filters($field);

        $param = $request->only("current_page", "perpage", "search");

        $param['is_deleted'] = 0;

        return view('admin::product-category-parent.list', [
            'list' => $this->productCategoryParent->getPaginate($param),
            'param' => $param,
            'filters' => $filters
        ]);

    }

    /**
     * ajax search List
     *
     * @return array;
     */
    public function ajaxList(Request $request)
    {
        $field = [
        ];
        $filters = $this->filters($field);

        $param = $request->only("current_page", "perpage", "search");
        $data['param'] = $param;

        $param['is_deleted'] = 0;


        $data['list'] = $this->productCategoryParent->getPaginate($param);

        return [
            "status" => "success",
            "action" => ["html", ""],
            "html" => [
                '.product-category-parent-table' => view("admin::product-category-parent.table", $data)->render(),
            ],
        ];

    }

    /**
     * ajax modal add
     *
     * @return array;
     */
    public function ajaxAddModal(Request $request)
    {
        $param = $request->only([
        ]);

        $data['filters'] = $this->filters([
        ]);


        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".product-category-parent-add-modal" => view("admin::product-category-parent.add-modal", $data)->render(),
            ],
            "modal" => [
                ".product-category-parent-add-modal" => "show",
            ],
        ];
    }

    /**
     * ajax action add
     *
     * @return array;
     */
    public function ajaxAdd(Request $request)
    {
        $param = $request->only([
            "product_category_parent_name",
            "icon_image",
        ]);
        $param['is_actived'] = 1;
        $param['is_deleted'] = 0;
        $param['created_at'] = Carbon::now()->toDateTimeString();
        $param['updated_at'] = Carbon::now()->toDateTimeString();

        $result = $this->productCategoryParent->actionAdd($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => $result['error'] ?? "",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }
        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal", "submitForm"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".product-category-parent-add-modal" => "hide",
                ],
                "submitForm" => '.ajax-product-category-parent-list-form',
                "action2" => $request->action2,
            ];
        }
        return [
            "status" => "error",
            "action" => ["swal"],
            "swal" => [
                "text" => __("Lỗi không xác định"),
                "title" => __('Lỗi'),
                "type" => 'error',
            ],
        ];

    }

    /**
     * ajax mở modal edit
     *
     * @return array;
     */
    public function ajaxEditModal(Request $request)
    {
        $param = $request->only([
            "product_category_parent_id",
        ]);

        $data['filters'] = $this->filters([
        ]);

        $data['item'] = $this->productCategoryParent->getItem(["product_category_parent_id" => $param["product_category_parent_id"]]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".product-category-parent-edit-modal" => view("admin::product-category-parent.edit-modal", $data)->render(),
            ],
            "modal" => [
                ".product-category-parent-edit-modal" => "show",
                ".product-category-parent-detail-modal" => "hide",
            ],
        ];

    }

    /**
     * ajax action edit
     *
     * @return array;
     */
    public function ajaxEdit(Request $request)
    {
        $param = $request->only([
            "product_category_parent_id",
            "product_category_parent_name",
            "icon_image",
        ]);
        //if(($param['is_actived']??'')=='on') $param['is_actived'] = 1;

        $result = $this->productCategoryParent->actionEdit($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => $result['error'] ?? "",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }

        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal", "submitForm"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".product-category-parent-edit-modal" => "hide",
                    ".product-category-parent-delete-modal" => "hide",
                ],
                "submitForm" => ".ajax-product-category-parent-list-form",
            ];
        }
        return [
            "status" => "error",
            "action" => ["swal"],
            "swal" => [
                "text" => __("Lỗi không xác định"),
                "title" => __('Lỗi'),
                "type" => 'error',
            ],
        ];
    }

    /**
     * ajax xóa công dân
     *
     * @return array;
     */
    public function ajaxDelete(Request $request)
    {
        $param = $request->only([
            "product_category_parent_id",
        ]);

        $result = $this->productCategoryParent->actionDelete($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal", "modal", ""],
                "swal" => [
                    "text" => $result['error'] ?? "",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
                "modal" => [
                    ".product-category-parent-delete-modal" => "hide",
                ],
            ];
        }
        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal", "submitForm"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".product-category-parent-delete-modal" => "hide",
                ],
                "submitForm" => '.ajax-product-category-parent-list-form',
            ];
        }
        return [
            "status" => "error",
            "action" => ["swal"],
            "swal" => [
                "text" => __("Lỗi không xác định"),
                "title" => __('Lỗi'),
                "type" => 'error',
            ],
        ];

    }


    /**
     * ajax mở modal xem chi tiết
     *
     * @return array;
     */
    public function ajaxDetailModal(Request $request)
    {
        $param = $request->only([
            "product_category_parent_id",
        ]);

        $data['filters'] = $this->filters([
            "search",
        ]);
        $data['item'] = $this->productCategoryParent->getItem(["product_category_parent_id" => $param["product_category_parent_id"]]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal", "remove"],
            "appendOrReplace" => [
                ".product-category-parent-detail-modal" => view("admin::product-category-parent.detail-modal", $data)->render(),
            ],
            "modal" => [
                ".product-category-parent-detail-modal" => "show",
            ],
            "remove" => [
                0 => ".product-category-parent-verify-add-modal"
            ],
        ];
    }


}