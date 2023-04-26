<?php

namespace Modules\Booking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Booking\Repositories\Loyalty\LoyaltyRepositoryInterface;
use MyCore\Http\Response\ResponseFormatTrait;

class LoyaltyController extends Controller
{
    use ResponseFormatTrait;

    protected $loyalty;

    public function __construct(
        LoyaltyRepositoryInterface $loyalty
    )
    {
        $this->loyalty = $loyalty;
    }

    /**
     * Tính điểm đơn hàng.
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function scoreCalculationAction(Request $request)
    {
        $validator = $this->validate(
            $request, [
            'order_id' => 'required|integer',
        ]);
        $data = $request->all();
        $result = $this->loyalty->scoreCalculation($data);

        return $this->responseJson(CODE_SUCCESS, 'Success', $result);
    }

    /**
     * Tính điểm tích lũy cho event.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function plusPointEventAction(Request $request)
    {
        $validator = $this->validate(
            $request, [
            'customer_id' => 'required|integer',
            'rule_code' => 'required',
        ]);
        $data = $request->all();
        $result = $this->loyalty->plusPointEvent($data);
        return $this->responseJson(CODE_SUCCESS, 'Success', $result);
    }

    /**
     * Cộng điểm khi thanh toán
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function plusPointReceiptAction(Request $request)
    {
        $validator = $this->validate(
            $request, [
            'receipt_id' => 'required|integer'
        ]);

        $result = $this->loyalty->plusPointReceiptAction($request->all());
        return $this->responseJson(CODE_SUCCESS, 'Success', $result);
    }

    /**
     * Cộng điểm khi thanh toán đủ tiền
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function plusPointReceiptFullAction(Request $request)
    {
        $validator = $this->validate(
            $request, [
            'receipt_id' => 'required|integer'
        ]);
        $data = $request->all();
        $result = $this->loyalty->plusPointReceiptFullAction($data);

        return $this->responseJson(CODE_SUCCESS, 'Success', $result);
    }
}
