<?php

namespace Modules\Referral\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Referral\Repositories\ReferralPayment\ReferralPaymentInterface;

class ReferralPaymentController extends Controller
{
    public function index(Request $request){
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $param = $request->all();
        $param['perpage'] = $param['display'] ?? 25;
        $list = $rReferralPayment->list($param);

        return view('referral::ReferralPayment.index',['list' => $list]);
    }

//    Danh sách tìm kiếm
    public function list(Request $request){
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $filters = $request->all();
        $filters['perpage'] = $filters['display'] ?? 25;
        $list = $rReferralPayment->list($filters);
        return view('referral::ReferralPayment.list',['list' => $list]);
    }
}
