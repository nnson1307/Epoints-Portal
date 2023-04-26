<?php


namespace Modules\FNB\Repositories\ReviewListDetail;


use Carbon\Carbon;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\FNBCustomerReviewTable;
use Modules\FNB\Models\FNBReviewListDetailTable;
use Modules\FNB\Repositories\ReviewList\ReviewListRepositoryInterface;

class ReviewListDetailRepository implements ReviewListDetailRepositoryInterface
{
    public function getList(array $filter = []){
        $mReivewListDetail = app()->get(FNBReviewListDetailTable::class);
        return $mReivewListDetail->getList($filter);
    }

    public function showPopup($data)
    {
        try {

            $rReviewList = app()->get(ReviewListRepositoryInterface::class);
            $mReivewListDetail = app()->get(FNBReviewListDetailTable::class);
            $listReview = $rReviewList->getAll();

            $detail = null;
            if (isset($data['id'])){
                $detail =  $mReivewListDetail->getDetail($data['id']);
            }

            $view = view('fnb::review-list-detail.popup.popup-review-list-detail',[
                'listReview' => $listReview,
                'detail' => $detail
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    public function saveReviewListDetail($data)
    {
        try {

            $mReivewListDetail = app()->get(FNBReviewListDetailTable::class);

            $dataTmp = [
                'review_list_id' => $data['popup_review_list_id'],
                'name' => strip_tags($data['popup_name'])
            ];

//            check Name

            $checkName = $mReivewListDetail->checkName($dataTmp['review_list_id'],$dataTmp['name'],isset($data['popup_review_list_detail_id']) ? $data['popup_review_list_detail_id'] : null);

            if (count($checkName) != 0){
                return [
                    'error' => true,
                    'message' => __('Tên đánh giá theo cấp độ bị trùng vui lòng chọn tên khác')
                ];
            }

            if (isset($data['popup_review_list_detail_id'])){
                $mReivewListDetail->editData($dataTmp,$data['popup_review_list_detail_id']);
            } else {
                $dataTmp['created_at'] = Carbon::now();
                $mReivewListDetail->insertData($dataTmp);
            }


            return [
                'error' => false,
                'message' => __('Lưu thông tin thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lưu thông tin thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    public function removeReviewListDetail($data)
    {
        try {
            $mCustomerReview = app()->get(FNBCustomerReviewTable::class);
            $mReivewListDetail = app()->get(FNBReviewListDetailTable::class);
//            Kiểm tra chi tiết đánh giá có đang được sử dụng
            $checkUsing = $mCustomerReview->checkUsing($data['id']);

            if (count($checkUsing) != 0){
                return [
                    'error' => true,
                    'message' => __('Chi tiết đánh giá đã được sử dụng không thể xóa'),
                ];
            }

            $mReivewListDetail->removeReviewListDetail($data['id']);

            return [
                'error' => false,
                'message' => __('Xóa chi tiết đánh giá thành công'),
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Xóa chi tiết đánh giá thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }
}