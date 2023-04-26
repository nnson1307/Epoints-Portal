<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/11/2021
 * Time: 14:07
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\RatingOrder\RatingOrderRepoInterface;

class RatingOrderController extends Controller
{
    protected $ratingOrder;

    public function __construct(
        RatingOrderRepoInterface $ratingOrder
    ) {
        $this->ratingOrder = $ratingOrder;
    }

    /**
     * Danh sách đánh giá đơn hàng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->ratingOrder->list();

        return view('admin::rating-order.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        //Lấy option filter
        $getOption = $this->ratingOrder->getOptionFilter();

        $customer = (["" => __('Chọn người đánh giá')]) + $getOption['arrCustomer'];
        $suggest = (["" => __('Chọn cú pháp')]) + $getOption['arrContentSuggest'];

        return [
            'rating_log$rating_value' => [
                'data' => [
                    "" => __("Chọn số sao"),
                    "5" => __("5 sao"),
                    "4" => __("4 sao"),
                    "3" => __("3 sao"),
                    "2" => __("2 sao"),
                    "1" => __("1 sao")
                ]
            ],
            'rating_log$rating_by' => [
                'data' => $customer
            ],
            'rating_log_suggest$content_suggest' => [
                'data' => $suggest
            ],
            'check_rating' => [
                'data' => [
                    "" => __('Tất cả'),
                    "comment" => __('Có bình luận'),
                    "image_video" => __('Có hình ảnh/video')
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách đánh giá
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'created_at',
            'rating_log$rating_by', 'rating_log_suggest$content_suggest', 'rating_log$rating_value', 'check_rating']);

        $data = $this->ratingOrder->list($filters);

        return view('admin::rating-order.list', [
            'LIST' => $data['list'],
            'page' => $filters['page']
        ]);
    }

    /**
     * Xem hình ảnh phóng to
     *
     * @param Request $request
     * @return mixed
     */
    public function viewImageAction(Request $request)
    {
        return $this->ratingOrder->viewImage($request->all());
    }

    /**
     * Xem video
     *
     * @param Request $request
     * @return mixed
     */
    public function viewVideoAction(Request $request)
    {
        return $this->ratingOrder->viewVideo($request->all());
    }

    /**
     * Chi tiết đánh giá
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $data = $this->ratingOrder->getDataDetail($id);

        return view('admin::rating-order.detail', $data);
    }
}