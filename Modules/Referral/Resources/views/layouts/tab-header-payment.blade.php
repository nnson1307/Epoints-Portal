<ul class="nav nav-pills nav-fill nav-child nav-custom-new" role="tablist" style="margin-bottom: -8px">
    <li class="nav-item">
        <a class="nav-link {{\Request::route()->getName() == 'referral.referral-payment-member.index' ? 'active' : ''}}" href="{{route('referral.referral-payment-member.index',['id' => $detail['referral_payment_id']])}}" style="font-weight: bold">
            {{__('Chờ thanh toán')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{\Request::route()->getName() == 'referral.referral-payment-member.history' ? 'active' : ''}}" href="{{route('referral.referral-payment-member.history',['id' => $detail['referral_payment_id']])}}" style="font-weight: bold">{{__('Lịch sử thanh toán')}}</a>
    </li>
</ul>
<hr style="margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #0067AC">
<style>
    .nav-item .nav-link {
        font-weight: bold !important;
    }
    .nav-item .nav-link.active {
        font-weight: bold !important;
        background-color: #4fc4cb !important;
        color: white !important;
        border-radius: 0px
    }

    .nav-custom-new .nav-item .nav-link.active , .nav-item:hover {
        background-color : #0067AC !important;
    }
</style>