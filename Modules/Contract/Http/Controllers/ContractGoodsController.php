<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 16:20
 */

namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Repositories\ContractGoods\ContractGoodsRepoInterface;

class ContractGoodsController extends Controller
{
    protected $contractGoods;

    public function __construct(
        ContractGoodsRepoInterface $contractGoods
    ) {
        $this->contractGoods = $contractGoods;
    }

    /**
     * Danh sách hàng hoá
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        //Lấy danh sách hàng hoá
        $data = $this->contractGoods->list($request->all());

        $html = \View::make('contract::contract.inc.contract-goods.list', $data)->render();

        return response()->json([
            'html' => $html,
            'countGoods' => $data['countGoods']
        ]);
    }
    public function listActionAnnex(Request $request)
    {
        //Lấy danh sách hàng hoá
        $data = $this->contractGoods->listAnnexGood($request->all());

        $html = \View::make('contract::contract.inc.contract-goods.list-edit-contract-annex', $data)->render();

        return response()->json([
            'html' => $html,
            'countGoods' => $data['countGoods']
        ]);
    }

    /**
     * Thay đổi hàng hoá
     *
     * @param Request $request
     * @return mixed
     */
    public function changeObjectAction(Request $request)
    {
        return $this->contractGoods->changeObject($request->all());
    }

    /**
     * Thêm hàng hoá
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->contractGoods->store($request->all());
    }

    /**
     * Tìm kiếm đơn hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function searchOrderAction(Request $request)
    {
        return $this->contractGoods->searchOrder($request->all());
    }
}