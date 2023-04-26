<?php


namespace Modules\FNB\Repositories\FNBCustomerReview;
use Modules\FNB\Models\FNBCustomerRequestTable;
use Modules\FNB\Models\FNBTableTable;
use Modules\FNB\Models\PaymentMethodTable;
use Modules\FNB\Models\FNBCustomerReviewTable;
use Modules\FNB\Models\FNBReviewListTable;
use Modules\FNB\Models\FNBReviewListDetailTable;
use Modules\FNB\Repositories\FNBCustomerReview\FNBCustomerReviewRepositoryInterface;


class FNBCustomerReviewRepository implements FNBCustomerReviewRepositoryInterface
{
   public  function getListTable(){
       $mTable = app()->get(FNBTableTable::class);
       $listTable = $mTable->getListNoPage();
       return $listTable;

   }
    public  function getListCustomerReview($input){
       $mCustomerReview = app()->get(FNBCustomerReviewTable::class);
        $mReviewListDetail = app()->get(FNBReviewListDetailTable::class);

        $listCustomerReview  = $mCustomerReview->allCustomerReview($input);
        //danh sách review_list_detail
        $listReviewDetail = $mReviewListDetail->listReviewDetail();
        $listReviewDetailKeyById = collect($listReviewDetail)->keyBy('review_list_detail_id');
       foreach ($listCustomerReview as $key => $val){
           $review_list_detail_id = \GuzzleHttp\json_decode($val['review_list_detail_id']);
           $ex = [];
           foreach ($review_list_detail_id as $k => $v){
               $ex[] = $listReviewDetailKeyById[$v]['name'];
           }
           $val['review_list_detail_name'] = $ex;
           $listCustomerReview[$key] = $val;
       }
       return $listCustomerReview;
    }
    public  function reviewList(){
        $mReviewList = app()->get(FNBReviewListTable::class);
        $listReviewList = $mReviewList->allReview();
        return $listReviewList;
    }

}