<?php

namespace Modules\Referral\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Referral\Repositories\PaymentMethod\PaymentMethodInterface;
use Modules\Referral\Repositories\ReferralPayment\ReferralPaymentInterface;
use Modules\Referral\Repositories\ReferralPaymentMember\ReferralPaymentMemberInterface;
use Modules\Referral\Repositories\Staffs\StaffsInterface;

class ReferralPaymentMemberController extends Controller
{
//    Chờ thanh toán
    public function index($id,Request $request){
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $rPaymentMethod = app()->get(PaymentMethodInterface::class);
        $param = $request->all();
        $param['status'] = 'new';
        $param['referral_payment_id'] = $id;
        $list = $rReferralPaymentMember->list($param);
        $detail = $rReferralPayment->getDetail($id);
        $listMethod = $rPaymentMethod->getAll();
        return view('referral::ReferralPaymentMember.index',[
            'list' => $list,
            'detail' => $detail,
            'listMethod' => $listMethod,
            'filters' => $param
        ]);
    }

//    Danh sách tìm kiếm
    public function list($id, Request $request){
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $filters = $request->all();
        $filters['status'] = 'new';
        $filters['referral_payment_id'] = $id;
        $list = $rReferralPaymentMember->list($filters);
        return view('referral::ReferralPaymentMember.list',['list' => $list, 'filters' => $filters]);
    }


    public function history($id,Request $request){
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $rStaffs = app()->get(StaffsInterface::class);
        $param = $request->all();
        $param['not_status'] = 'new';
        $param['referral_payment_id'] = $id;
        $list = $rReferralPaymentMember->list($param);
        $detail = $rReferralPayment->getDetail($id);
        $listStaff = $rStaffs->getAll();
        return view('referral::ReferralPaymentMember.history',['list' => $list,'detail' => $detail,'listStaff' => $listStaff]);
    }

//    Danh sách tìm kiếm
    public function historyList(Request $request){
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $filters = $request->all();
        $list = $rReferralPaymentMember->list($filters);
        return view('referral::ReferralPaymentMember.history-list',['list' => $list]);
    }

    /**
     * Từ chối thanh toán
     * @param Request $request
     */
    public function rejectPayment(Request $request){
        $param = $request->all();
//        if($param[''])
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        if(isset($param['referral_payment_member_id'])){

            if(is_array($param['referral_payment_member_id'])){
                foreach ($param['referral_payment_member_id'] as $payment){
                    $param['referral_payment_member_id'] = $payment['value'];
                    $data = $rReferralPaymentMember->rejectPayment($param);
                }
            } else {
                $data = $rReferralPaymentMember->rejectPayment($param);
            }
        } else {
            $data =  [
                'error' => true,
                'message' => __('Từ chối thất bại')
            ];
        }

        return \response()->json($data);
    }

}
