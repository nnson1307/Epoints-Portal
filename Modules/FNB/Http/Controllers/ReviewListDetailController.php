<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FNB\Repositories\ReviewList\ReviewListRepositoryInterface;
use Modules\FNB\Repositories\ReviewListDetail\ReviewListDetailRepositoryInterface;


class ReviewListDetailController extends Controller
{
    private $reviewListDetail;

    public function __construct(ReviewListDetailRepositoryInterface $reviewListDetail)
    {
        $this->reviewListDetail = $reviewListDetail;
    }
    public function index(Request $request){
        $list = $this->reviewListDetail->getList($request->all());

        $rReviewList = app()->get(ReviewListRepositoryInterface::class);

        $listReview = $rReviewList->getAll();

        return view('fnb::review-list-detail.index',[
            'list' => $list,
            'listReview' => $listReview
        ]);
    }

    public function list(Request $request){
        $list = $this->reviewListDetail->getList($request->all());

        return view('fnb::review-list-detail.list',[
            'list' => $list
        ]);
    }

    public function showPopup(Request $request){
        $param = $request->all();
        $data = $this->reviewListDetail->showPopup($param);
        return \response()->json($data);
    }

    /**
     * Lưu đánh giá chi tiết
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveReviewListDetail(Request $request){
        $param = $request->all();
        $data = $this->reviewListDetail->saveReviewListDetail($param);
        return \response()->json($data);
    }

    /**
     * Xóa đánh giá chi tiết
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeReviewListDetail(Request $request){
        $param = $request->all();
        $data = $this->reviewListDetail->removeReviewListDetail($param);
        return \response()->json($data);
    }

}
