<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/11/2021
 * Time: 14:09
 */

namespace Modules\Config\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Config\Repositories\ConfigReview\ConfigReviewRepoInterface;

class ConfigReviewController extends Controller
{
    protected $configReview;

    public function __construct(
        ConfigReviewRepoInterface $configReview
    ) {
        $this->configReview = $configReview;
    }

    /**
     * Lấy dữ liệu cấu hình đánh giá
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function configOrderAction()
    {
        $data = $this->configReview->getDataConfigOrder();

        return view('config::config-review.config-review-order', $data);
    }

    /**
     * Thêm cú pháp gợi ý đánh giá
     *
     * @param Request $request
     * @return mixed
     */
    public function insertContentSuggestAction(Request $request)
    {
        return $this->configReview->insertContentSuggest($request->all());
    }

    /**
     * Cập nhật cấu hình đánh giá đơn hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function updateConfigOrderAction(Request $request)
    {
        return $this->configReview->updateConfigOrder($request->all());
    }
}