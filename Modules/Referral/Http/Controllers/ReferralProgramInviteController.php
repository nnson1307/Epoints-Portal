<?php

namespace Modules\Referral\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Referral\Repositories\ReferralPaymentMember\ReferralPaymentMemberInterface;
use Modules\Referral\Repositories\ReferralProgram\ReferralProgramInterface;
use Modules\Referral\Repositories\ReferralProgramInvite\ReferralProgramInviteInterface;

class ReferralProgramInviteController extends Controller
{
    public function index(Request $request){
        $rReferraProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $rReferralProgram = app()->get(ReferralProgramInterface::class);
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $param = $request->all();
        $param['perpage'] = $input['display'] ?? 25;
        $list = $rReferraProgramInvite->list($param);
//        dd($list);

//        Lấy danh sách mảng referral_member có trong referral_payment_member
        $listMemberInPaymentMember = $rReferralPaymentMember->getListMemberInPaymentMember($list);

        $filterProgram['status'] = 'actived';
        $listProgram = $rReferralProgram->getAll($filterProgram);
        return view('referral::ReferralProgramInvite.index',['list' => $list,'listProgram' => $listProgram,'listMemberInPaymentMember' => $listMemberInPaymentMember]);
    }

    public function list(Request $request){
        $rReferraProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $filters = $request->all();
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $filters['perpage'] = $filters['display'] ?? 25;
        $list = $rReferraProgramInvite->list($filters);

        //        Lấy danh sách mảng referral_member có trong referral_payment_member
        $listMemberInPaymentMember = $rReferralPaymentMember->getListMemberInPaymentMember($list);

        return view('referral::ReferralProgramInvite.list',['list' => $list,'listMemberInPaymentMember' => $listMemberInPaymentMember]);
    }

    /**
     *
     * @param Request $request
     */
    public function updateProgramInvite(Request $request){
        $rReferralMember = app()->get(ReferralProgramInviteInterface::class);
        $param = $request->all();
        $data = $rReferralMember->updateProgramInvite($param);
        return \response()->json($data);
    }

    /**
     * Lấy chi tiết reject
     * @param Request $request
     */
    public function showReject(Request $request){
        $rReferralMember = app()->get(ReferralProgramInviteInterface::class);
        $param = $request->all();
        $data = $rReferralMember->showReject($param);
        return \response()->json($data);
    }

    public function commissionOrder(Request $request){
        $rReferraProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $rReferralProgram = app()->get(ReferralProgramInterface::class);
        $param = $request->all();
        $param['perpage'] = $input['display'] ?? 25;
        $list = $rReferraProgramInvite->getListCommissionOrder($param);

        $filterProgram['status'] = 'actived';
        $listProgram = $rReferralProgram->getAll($filterProgram);

        return view('referral::ReferralProgramInvite.commissionOrder.index',[
            'list' => $list,
            'listProgram' => $listProgram,
        ]);
    }

    public function listCommissionOrder(Request $request){
        $rReferraProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $filters = $request->all();
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $filters['perpage'] = $filters['display'] ?? 25;
        $list = $rReferraProgramInvite->getListCommissionOrder($filters);

        //        Lấy danh sách mảng referral_member có trong referral_payment_member
        $listMemberInPaymentMember = $rReferralPaymentMember->getListMemberInPaymentMember($list);

        return view('referral::ReferralProgramInvite.commissionOrder.list',['list' => $list,'listMemberInPaymentMember' => $listMemberInPaymentMember]);
    }

    public function commissionOrderDetail($id, Request $request){

        $rReferraProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $filters = $request->all();
        $filters['perpage'] = $filters['display'] ?? 25;
        $filters['referral_program_invite_id'] = $id;
        $list = $rReferraProgramInvite->list($filters);
        $detail = $rReferraProgramInvite->detailItem($id);



        return view('referral::ReferralProgramInvite.commissionOrder.detail',
            [
                'programInviteId' => $id,
                'list' => $list,
                'detail' => $detail
            ]);

    }

    public function commissionOrderDetailList($id, Request $request){
        $rReferraProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $filters = $request->all();
        $filters['perpage'] = $filters['display'] ?? 25;
        $filters['referral_program_invite_id'] = $id;
        $list = $rReferraProgramInvite->list($filters);

        return view('referral::ReferralProgramInvite.commissionOrder.detail-list',
            [
                'list' => $list
            ]);
    }

    public function rejectCommission(Request $request){
        $params = $request->all();
        $rReferralProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $data = $rReferralProgramInvite->rejectCommission($params);
        return \response()->json($data);
    }

    /**
     * Lấy chi tiết reject
     * @param Request $request
     */
    public function showRejectCommission(Request $request){
        $rReferralMember = app()->get(ReferralProgramInviteInterface::class);
        $param = $request->all();
        $data = $rReferralMember->showRejectCommission($param);
        return \response()->json($data);
    }

}
