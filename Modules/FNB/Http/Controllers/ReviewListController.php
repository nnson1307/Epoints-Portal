<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FNB\Repositories\FNBRequest\FNBRequestRepositoryInterface;
use Modules\FNB\Repositories\ReviewList\ReviewListRepositoryInterface;


class ReviewListController extends Controller
{
    private $reviewList;

    public function __construct(ReviewListRepositoryInterface $reviewList)
    {
        $this->reviewList = $reviewList;
    }
    public function index(Request $request){
        $list = $this->reviewList->getList();

        return view('fnb::review-list.index',[
            'list' => $list
        ]);
    }

}
