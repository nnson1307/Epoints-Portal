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
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Repositories\Collection\CollectionRepoIf;
use Illuminate\Routing\Controller as BaseController;
use Modules\Admin\Repositories\ProductFavourite\ProductFavouriteRepo;

class ProductFavouriteController extends BaseController
{
    protected $productFavourite;

    public function __construct(
        ProductFavouriteRepo $productFavourite
    )
    {
        $this->productFavourite = $productFavourite;
    }

    // Product Favourite

    /**
     * Danh sách filter
     *
     * @return array
     */
    public function filters($param = [])
    {
        $result = [];


        if (in_array('product_favourite_id', $param)) {
            $array = $this->productFavourite->getPaginate(['perpage'=>9999]) ?? [];
            $option[''] = 'Danh mục sản phẩm cha';
            $result['product_favourite_id'] = [
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
  //          'product_favourite_id',
        ];
        $filters = $this->filters($field);

        $param = $request->only("current_page", "perpage", "search");
        $customerTable = app()->get(CustomerTable::class);

        $param['is_deleted'] = 0;

        return view('admin::product-favourite.list', [
            'list' => $this->productFavourite->getPaginate($param),
            'param' => $param,
            'filters' => $filters,
            'arrCustomer' => $customerTable->where([
                ['is_actived','1'],
                ['is_deleted','0'],
            ])->get()
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


        $data['list'] = $this->productFavourite->getPaginate($param);

        return [
            "status" => "success",
            "action" => ["html", ""],
            "html" => [
                '.product-favourite-table' => view("admin::product-favourite.table", $data)->render(),
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
            "customer_id",
        ]);
        $param['per_page']=9999999;


        $data['list'] = $this->productFavourite->getPaginate($param);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal", "remove"],
            "appendOrReplace" => [
                ".product-favourite-detail-modal" => view("admin::product-favourite.detail-modal", $data)->render(),
            ],
            "modal" => [
                ".product-favourite-detail-modal" => "show",
            ],
            "remove" => [
                0 => ".product-favourite-verify-add-modal"
            ],
        ];
    }


}