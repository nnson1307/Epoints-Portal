<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FNB\Repositories\FNBCustomerReview\FNBCustomerReviewRepositoryInterface;


class CustomerReviewController extends Controller
{
    private $customerReview;
    private $route = 'fnb.customerReview';

    public function __construct(FNBCustomerReviewRepositoryInterface $customerReview)
    {
        $this->customerReview = $customerReview;
    }
    public function index(Request $request){
        $input = $request->all();
        //danh sách bàn(filter)
        $listTable = $this->listTable();
        //danh sách phương thức thanh toán
        $reviewList = $this->reviewList();
        //danh sách yêu cầu
        $listCustomerReview =  $this->customerReview->getListCustomerReview($input);
        return view('fnb::customer-reviews.index',[
            'input' => $input,
            'listTable' => $listTable,
            'listCustomerReview' => $listCustomerReview,
            'reviewList' => $reviewList,
        ]);
    }

    public function list(Request $request){
        $input = $request->all();
        //danh sách bàn(filter)
        $listTable = $this->listTable();
        //danh sách phương thức thanh toán
        $reviewList = $this->reviewList();
        //danh sách yêu cầu
        $listCustomerReview =  $this->customerReview->getListCustomerReview($input);
        return view('fnb::customer-reviews.list',[
            'input' => $input,
            'listTable' => $listTable,
            'listCustomerReview' => $listCustomerReview,
            'reviewList' => $reviewList,
        ]);
    }

    public function listTable(){
        $data =  $this->customerReview ->getListTable();
        return $data;
    }

    public function reviewList(){
        $data =  $this->customerReview->reviewList();
        return $data;
    }

}
