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
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Repositories\Cart\CartRepo;
use Modules\Admin\Repositories\Collection\CollectionRepoIf;
use Illuminate\Routing\Controller as BaseController;

class CartController extends BaseController
{
    protected $cart;

    public function __construct(
        CartRepo $cart
    )
    {
        $this->cart = $cart;
    }

    // Product Cart

    /**
     * Danh sách filter
     *
     * @return array
     */
    public function filters($param = [])
    {
        $result = [];


        if (in_array('cart_id', $param)) {
            $array = $this->cart->getPaginate(['perpage'=>9999]) ?? [];
            $option[''] = 'Danh mục sản phẩm cha';
            $result['cart_id'] = [
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
  //          'cart_id',
        ];

        $param = $request->only("current_page", "perpage", "search", "customer_id", "branch_id");

        $param['is_deleted'] = 0;

        $customerTable = app()->get(CustomerTable::class);
        $branchTable = app()->get(BranchTable::class);

        return view('admin::cart.list', [
            'list' => $this->cart->getPaginate($param),
            'param' => $param,
            'arrCustomer' => $customerTable->where([
                ['is_actived','1'],
                ['is_deleted','0'],
            ])->get(),
            'arrBranch' => $branchTable->where([
                ['is_actived','1'],
                ['is_deleted','0'],
            ])->get(),
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

        $param = $request->only("current_page", "perpage", "search", "customer_id", "branch_id");
        $data['param'] = $param;

        $param['is_deleted'] = 0;


        $data['list'] = $this->cart->getPaginate($param);

        return [
            "status" => "success",
            "action" => ["html", ""],
            "html" => [
                '.cart-table' => view("admin::cart.table", $data)->render(),
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
            "cart_id",
        ]);
        $param['per_page']=9999999;


        $data['list'] = $this->cart->getPaginate($param);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal", "remove"],
            "appendOrReplace" => [
                ".cart-detail-modal" => view("admin::cart.detail-modal", $data)->render(),
            ],
            "modal" => [
                ".cart-detail-modal" => "show",
            ],
            "remove" => [
                0 => ".cart-verify-add-modal"
            ],
        ];
    }


}