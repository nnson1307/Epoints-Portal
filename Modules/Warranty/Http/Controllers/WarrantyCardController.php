<?php

namespace Modules\Warranty\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warranty\Repository\WarrantyCard\WarrantyCardRepoInterface;

class WarrantyCardController extends Controller
{
    protected $warrantyCard;
    public function __construct(WarrantyCardRepoInterface $warrantyCard)
    {
        $this->warrantyCard = $warrantyCard;
    }

    public function index()
    {
        $data = $this->warrantyCard->list();
        return view('warranty::warranty-card.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    public function filters()
    {
        return [

        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
            'warranty_card$customer_code'
        ]);
        $data = $this->warrantyCard->list($filter);
        return view('warranty::warranty-card.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View chỉnh sửa thẻ bảo hành điện tử
     *
     * @param $warrantyCardId
     * @return array
     */
    public function edit($warrantyCardId)
    {
        $data = $this->warrantyCard->dataViewEdit($warrantyCardId);
        return view('warranty::warranty-card.edit', $data);
    }

    /**
     * Cập nhật thẻ bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $request->all();
        return $this->warrantyCard->update($data);
    }

    /**
     * Huỷ thẻ bảo hành điện tử (chưa kích hoạt mới cho huỷ)
     *
     * @param Request $request
     * @return mixed
     */
    public function cancel(Request $request)
    {
        $data = $request->all();
        return $this->warrantyCard->cancel($data);
    }

    /**
     * Kích hoạt thẻ bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function active(Request $request)
    {
        $data = $request->all();
        return $this->warrantyCard->active($data);
    }

    /**
     * View chi tiết thẻ bảo hành
     *
     * @param $warrantyCardId
     * @return array
     */
    public function show($warrantyCardId)
    {
        $data = $this->warrantyCard->dataViewEdit($warrantyCardId);

        return view('warranty::warranty-card.detail', $data);
    }

    /**
     * Load tab chi tiết phiếu bảo hành
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadTabDetailAction(Request $request)
    {
        //Xử lý lấy data của từng tab
        $data = $this->warrantyCard->loadTabDetail($request->all());

        $view = '';

        //Xử lý trả view của từng tab
        switch ($request->tab_view) {
            case 'info':
                $view = \View::make('warranty::warranty-card.tab-detail.info', $data)->render();

                break;
            case 'maintenance':
                $view = \View::make('warranty::warranty-card.tab-detail.maintenance', $data)->render();

                break;
        };

        return response()->json([
            'view' => $view
        ]);
    }
}