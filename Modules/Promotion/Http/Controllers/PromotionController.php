<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/8/2020
 * Time: 2:26 PM
 */

namespace Modules\Promotion\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Promotion\Http\Requests\Promotion\StoreRequest;
use Modules\Promotion\Http\Requests\Promotion\UpdateRequest;
use Modules\Promotion\Repositories\Promotion\PromotionRepoInterface;

class PromotionController extends Controller
{
    protected $promotion;

    public function __construct(
        PromotionRepoInterface $promotion
    ) {
        $this->promotion = $promotion;
    }

    /**
     * Danh sách CTKM
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->promotion->list();

        return view('promotion::promotion.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'promotion_master$promotion_type' => [
                'data' => [
                    '' => __('Loại chương trình'),
                    '1' => __('Giảm giá'),
                    '2' => __('Quà tặng'),
                    '3' => __('Tích lũy'),
                ]
            ],
            'promotion_master$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ],
        ];
    }

    /**
     * Ajax load filter, phân trang CTKM
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'promotion_master$promotion_type',
            'time_promotion',
            'promotion_master$is_actived'
        ]);

        $data = $this->promotion->list($filter);

        return view('promotion::promotion.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm CTKM
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create()
    {
        $data = $this->promotion->dataCreate();

        return view('promotion::promotion.create', $data);
    }

    /**
     * Show popup sp/dv/thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupAction(Request $request)
    {
        $data = $this->promotion->showPopup($request->all());

        return response()->json($data);
    }

    /**
     * Ajax phân trang, filter product
     *
     * @param Request $request
     * @return mixed
     */
    public function listProductAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_keyword',
            'products$product_category_id'
        ]);

        return $this->promotion->listProduct($filter);
    }

    /**
     * Ajax phân trang, filter service
     *
     * @param Request $request
     * @return mixed
     */
    public function listServiceAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search', 'services$service_category_id']);

        return $this->promotion->listService($filter);
    }

    /**
     * Ajax filter, phân trang service card
     *
     * @param Request $request
     * @return mixed
     */
    public function listServiceCardAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_keyword', 'service_cards$service_card_group_id']);

        return $this->promotion->listServiceCard($filter);
    }

    /**
     * Chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAllAction(Request $request)
    {
        $data = $this->promotion->chooseAll($request->all());

        return response()->json($data);
    }

    /**
     * Chọn sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAction(Request $request)
    {
        $data = $this->promotion->choose($request->all());

        return response()->json($data);
    }

    /**
     * Bỏ chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAllAction(Request $request)
    {
        $data = $this->promotion->unChooseAll($request->all());

        return response()->json($data);
    }

    /**
     * Bỏ chọn sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAction(Request $request)
    {
        $data = $this->promotion->unChoose($request->all());

        return response()->json($data);
    }

    /**
     * Submit chọn sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitChooseAction(Request $request)
    {
        $data = $this->promotion->submitChoose($request->all());

        return response()->json($data);
    }

    /**
     * Phân trang ds discount sp, dv, thẻ dv
     *
     * @param Request $request
     * @return mixed
     */
    public function listDiscountAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'discount_type', 'discount_value_percent', 'discount_value_same']);

        return $this->promotion->listDiscount($filter);
    }

    /**
     * Thay đổi giá khuyến mãi
     *
     * @param Request $request
     * @return mixed
     */
    public function changePriceAction(Request $request)
    {
        return $this->promotion->changePrice($request->all());
    }

    /**
     * Xóa dòng table sp, dv, thẻ db
     *
     * @param Request $request
     * @return mixed
     */
    public function removeTrAction(Request $request)
    {
        $data = $this->promotion->removeTr($request->all());

        return response()->json($data);
    }

    /**
     * Thay đổi trạng thái table sp, dv, thẻ dv
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatusAction(Request $request)
    {
        return $this->promotion->changeStatusTr($request->all());
    }

    /**
     * Option sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listOptionAction(Request $request)
    {
        $data = $this->promotion->listOption($request->all());

        return response()->json($data);
    }

    /**
     * Thay đổi loại quà tặng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeGiftTypeAction(Request $request)
    {
        $data = $this->promotion->changeGiftType($request->all());

        return response()->json($data);
    }

    /**
     * Phân trang ds gift sp, dv, thẻ dv
     *
     * @param Request $request
     * @return mixed
     */
    public function listGiftAction(Request $request)
    {
        $filter = $request->only(['page', 'display']);

        return $this->promotion->listGift($filter);
    }

    /**
     * Thay đổi quà tặng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeGiftAction(Request $request)
    {
        $data = $this->promotion->changeGift($request->all());

        return response()->json($data);
    }

    /**
     * Thay đổi số lượng cần mua
     *
     * @param Request $request
     * @return mixed
     */
    public function changeQuantityBuyAction(Request $request)
    {
        return $this->promotion->changeQuantityBuy($request->all());
    }

    /**
     * Thay đổi số lượng quà tặng
     *
     * @param Request $request
     * @return mixed
     */
    public function changeNumberGiftAction(Request $request)
    {
        return $this->promotion->changeNumberGift($request->all());
    }

    /**
     * Xóa hết session list all, sp, dv, thẻ dv
     *
     */
    public function clearListAllAction()
    {
        session()->forget('product');
        session()->forget('service');
        session()->forget('service_card');
        session()->forget('list_all');
    }

    /**
     * Submit thêm CTKM
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $this->promotion->store($request->all());

        return $data;
    }

    /**
     * Chỉnh sửa CTKM
     *
     * @param $promotionId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($promotionId)
    {
        $data = $this->promotion->dataEdit($promotionId);

        return view('promotion::promotion.edit', $data);
    }

    /**
     * Chỉnh sửa CTKM
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->promotion->update($request->all());

        return $data;
    }

    /**
     * Xóa CTKM
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->promotion->destroy($request->all());

        return response()->json($data);
    }

    /**
     * Thay đổi trạng thái CTKM
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusPromotionAction(Request $request)
    {
        $data = $this->promotion->changeStatusPromotion($request->all());

        return response()->json($data);
    }

    /**
     * Chi tiết CTKM
     *
     * @param $promotionId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($promotionId)
    {
        $data = $this->promotion->dataEdit($promotionId);

        return view('promotion::promotion.detail', $data);
    }

    /**
     * Phân trang ds discount sp, dv, thẻ dv
     *
     * @param Request $request
     * @return mixed
     */
    public function listDiscountDetailAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'discount_type', 'discount_value_percent', 'discount_value_same']);

        return $this->promotion->listDiscountDetail($filter);
    }

    /**
     * Phân trang ds gift sp, dv, thẻ dv
     *
     * @param Request $request
     * @return mixed
     */
    public function listGiftDetailAction(Request $request)
    {
        $filter = $request->only(['page', 'display']);

        return $this->promotion->listGiftDetail($filter);
    }

    /**
     * Load session all
     *
     * @param Request $request
     * @return mixed
     */
    public function loadSessionAction(Request $request)
    {
        return $this->promotion->loadSessionAll($request->promotion_code);
    }
}