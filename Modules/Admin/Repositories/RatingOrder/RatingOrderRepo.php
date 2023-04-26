<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/11/2021
 * Time: 14:08
 */

namespace Modules\Admin\Repositories\RatingOrder;


use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\RatingLogImageTable;
use Modules\Admin\Models\RatingLogSuggestTable;
use Modules\Admin\Models\RatingOrderLogTable;
use Modules\Admin\Models\StaffTable;
use Modules\Config\Models\ConfigReviewContentSuggestTable;
use Modules\Config\Models\ContentSuggestTable;

class RatingOrderRepo implements RatingOrderRepoInterface
{
    protected $ratingOrder;

    public function __construct(
        RatingOrderLogTable $ratingOrder
    ) {
        $this->ratingOrder = $ratingOrder;
    }

    /**
     * Lấy danh sách đánh giá đơn hàng
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $mLogSuggest = app()->get(RatingLogSuggestTable::class);
        $mLogImage = app()->get(RatingLogImageTable::class);

        //Lấy ds đánh giá đơn hàng
        $listOrder = $this->ratingOrder->getList($filters);

        if (count($listOrder->items()) > 0) {
            foreach ($listOrder->items() as $v) {
                //Lấy cú pháp gợi ý
                $v['log_suggest'] = $mLogSuggest->getLogSuggest($v['id']);
                $v['log_image'] = $mLogImage->getRatingFile($v['id']);
            }
        }

        return [
            'list' => $listOrder
        ];
    }

    /**
     * Lấy option cú pháp gợi ý
     *
     * @return array
     */
    public function getOptionFilter()
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mContentSugget = app()->get(ContentSuggestTable::class);

        $arrCustomer = [];
        $arrContentSuggest = [];

        //Lấy option người đánh giá
        $getCustomer = $mCustomer->getCustomerOption();

        if (count($getCustomer) > 0) {
            foreach ($getCustomer as $v) {
                $arrCustomer[$v['customer_id']] = $v['full_name'];
            }
        }
        //Lấy option cú pháp
        $getSuggest = $mContentSugget->getOptionSuggest();

        if (count($getSuggest) > 0) {
            foreach ($getSuggest as $v) {
                $arrContentSuggest[$v['content_suggest']] = $v['content_suggest'];
            }
        }

        return [
            'arrCustomer' => $arrCustomer,
            'arrContentSuggest' => $arrContentSuggest
        ];
    }

    /**
     * Xem hình ảnh phóng to
     *
     * @param $input
     * @return mixed|void
     */
    public function viewImage($input)
    {
        $html = \View::make('admin::rating-order.pop.modal-view-image', [
            'link' => $input['link'],
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Xem video
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function viewVideo($input)
    {
        $html = \View::make('admin::rating-order.pop.modal-view-video', [
            'link' => $input['link'],
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Lấy data chi tiết đánh giá
     *
     * @param $id
     * @return array|mixed
     */
    public function getDataDetail($id)
    {
        $mLogSuggest = app()->get(RatingLogSuggestTable::class);
        $mLogImage = app()->get(RatingLogImageTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);

        //Lấy thông tin đánh giá đơn hàng
        $info = $this->ratingOrder->getInfo($id);
        //Lấy cú pháp gợi ý
        $logSuggest = $mLogSuggest->getLogSuggest($info['id']);
        $logImage = $mLogImage->getRatingFile($info['id']);
        //Lấy chi tiết đơn hàng
        $getOrderDetail = $mOrderDetail->getItem($info['order_id']);

        return [
            'item' => $info,
            'logSuggest' => $logSuggest,
            'logImage' => $logImage,
            'getOrderDetail' => $getOrderDetail
        ];
    }
}