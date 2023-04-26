<?php

namespace Modules\Referral\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Referral\Repositories\ReferralMember\ReferralMemberInterface;
use Modules\Referral\Repositories\ReferralPayment\ReferralPaymentInterface;
use Modules\Referral\Repositories\ReferralPaymentMember\ReferralPaymentMemberInterface;
use Modules\Referral\Repositories\ReferralProgramInvite\ReferralProgramInviteInterface;
use Modules\Referral\Repositories\Staffs\StaffsInterface;

class ReferralMemberController extends Controller
{
    public function index(Request $request){
        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $param = $request->all();
        $param['perpage'] = $input['display'] ?? 25;
        $list = $rReferralMember->list($param);

        return view('referral::ReferralMember.index',['list' => $list]);
    }

    public function list(Request $request){
        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $filters = $request->all();
        $filters['perpage'] = $filters['display'] ?? 25;
        $list = $rReferralMember->list($filters);
        return view('referral::ReferralMember.list',['list' => $list]);
    }

    public function detail($id){
        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $detail = $rReferralMember->getDetailCustomer($id);
        $referral = $rReferralMember->getDetailInvite($id);
        return [
            'detail' => $detail,
            'totalRefer' => $detail['total_node_nearest'],
            'referral' => $referral
        ];
    }

    /**
     * Chi tiết khách hàng tab Hoa hồng
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detailCommissionReferral($id,Request $request){

        $info = $this->detail($id);
        $rReferralProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $filters = $request->all();
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $filters['referral_member_id'] = $id;
        $list = $rReferralProgramInvite->list($filters);

        //        Lấy danh sách mảng referral_member có trong referral_payment_member
        $listMemberInPaymentMember = $rReferralPaymentMember->getListMemberInPaymentMember($list);
        return view('referral::ReferralMember.detail-commission.detail-commission-referral',[
            'detail' => $info['detail'],
            'totalRefer' => $info['totalRefer'],
            'referral' => $info['referral'],
            'list' => $list,
            'listMemberInPaymentMember' => $listMemberInPaymentMember
        ]);
    }

    /**
     * Chi tiết khách hàng tab Hoa hồng search
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detailCommissionReferralList(Request $request){
        $rReferralProgramInvite = app()->get(ReferralProgramInviteInterface::class);
        $filters = $request->all();
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $list = $rReferralProgramInvite->list($filters);

        //        Lấy danh sách mảng referral_member có trong referral_payment_member
        $listMemberInPaymentMember = $rReferralPaymentMember->getListMemberInPaymentMember($list);

        return view('referral::ReferralMember.detail-commission.list',['list' => $list,'listMemberInPaymentMember' => $listMemberInPaymentMember]);
    }

    /**
     * Chi tiết khách hàng tab Lịch sử thanh toán
     * @param $id
     * @param Request $request
     */
    public function detailHistoryPayment($id , Request $request){
        $info = $this->detail($id);
        $rStaffs = app()->get(StaffsInterface::class);
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $param = $request->all();
        $param['referral_member_id'] = $id;
        $list = $rReferralPaymentMember->list($param);
        $listStaff = $rStaffs->getAll();
        $listPayment = $rReferralPayment->getAll();

        return view('referral::ReferralMember.detail-history.detail-history-payment',[
            'detail' => $info['detail'],
            'totalRefer' => $info['totalRefer'],
            'referral' => $info['referral'],
            'list' => $list,
            'listStaff' => $listStaff,
            'listPayment' => $listPayment
        ]);
    }

    /**
     * Chi tiết khách hàng tab Lịch sử thanh toán
     * @param $id
     * @param Request $request
     */
    public function detailHistoryPaymentList(Request $request){
        $rReferralPayment = app()->get(ReferralPaymentInterface::class);
        $rReferralPaymentMember = app()->get(ReferralPaymentMemberInterface::class);
        $filters = $request->all();
        $list = $rReferralPaymentMember->list($filters);
        $view = view('referral::ReferralMember.detail-history.list',['list' => $list])->render();
        return \response()->json([
            'error' => false,
            'view' => $view
        ]);

    }

    /**
     * Chi tiết khách hàng tab Danh sách người được giới thiệu
     * @param $id
     * @param Request $request
     */
    public function detailReferral($id , Request $request){
        $info = $this->detail($id);

        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $filters = $request->all();
        $filters['referral_member_id'] = $id;
        $list = $rReferralMember->listRefferal($filters);
        return view('referral::ReferralMember.detail-referral.detail-referral',[
            'detail' => $info['detail'],
            'totalRefer' => $info['totalRefer'],
            'referral' => $info['referral'],
            'list' => $list
        ]);
    }

    /**
     * Chi tiết khách hàng tab Danh sách người được giới thiệu
     * @param $id
     * @param Request $request
     */
    public function detailReferralChild($id , Request $request){
        $info = $this->detail($id);

        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $filters = $request->all();
        $filters['referral_member_id'] = $id;
        $list = $rReferralMember->listChild($filters);
        return view('referral::ReferralMember.detail-referral-child.detail-referral',[
            'detail' => $info['detail'],
            'totalRefer' => $info['totalRefer'],
            'referral' => $info['referral'],
            'list' => $list
        ]);
    }

    public function getChild(Request $request){
        $filters = $request->all();
        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $list = $rReferralMember->listChild($filters);
        $html = view('referral::ReferralMember.detail-referral-child.ul-list',[
            'list' => $list,
            'lv' => $request->lv + 1
        ])->render();

        return \response()->json(
            [
                'data' => $html
            ]
        );
    }

    /**
     * Chi tiết khách hàng tab Danh sách người được giới thiệu
     * @param $id
     * @param Request $request
     */
    public function detailReferralList(Request $request){
        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $filters = $request->all();
        $list = $rReferralMember->listRefferal($filters);
        $view = view('referral::ReferralMember.detail-referral.list',[
            'list' => $list
        ])->render();
        return \response()->json([
            'error' => false,
            'view' => $view
        ]);
    }


    /**
     * Thay đổi trạng thái
     * @param Request $request
     */
    public function changeStatusReferralMember(Request $request){
        $rReferralMember = app()->get(ReferralMemberInterface::class);
        $param = $request->all();
        $data = $rReferralMember->changeStatusReferralMember($request->all());
        return \response()->json($data);
    }
}
