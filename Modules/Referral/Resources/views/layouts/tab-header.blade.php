<ul class="nav nav-pills nav-fill nav-custom-new" role="tablist" style="margin-bottom: -8px">
    <li class="nav-item">
        <a class="nav-link {{\Request::route()->getName() == 'referral.referral-member.index' ? 'active' : ''}}" href="{{route('referral.referral-member.index')}}" style="font-weight: bold">{{__('Danh sách người giới thiệu')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{\Request::route()->getName() == 'referral.commission-order' ? 'active' : ''}}" href="{{route('referral.commission-order')}}"
           style="font-weight: bold">{{__('Đơn hàng hưởng hoa hồng')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{\Request::route()->getName() == 'referral.referral-program-invite.index' ? 'active' : ''}}" href="{{route('referral.referral-program-invite.index')}}" style="font-weight: bold">{{__('Hoa hồng cho người giới thiệu')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{\Request::route()->getName() == 'referral.policyCommission' ? 'active' : ''}}" href="{{route('referral.policyCommission')}}"
           style="font-weight: bold">{{__('Chính sách hoa hồng')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ in_array(\Request::route()->getName(),['referral.referral-payment.index','referral.referral-payment-member.index','referral.referral-payment-member.history']) ? 'active' : ''}}" href="{{route('referral.referral-payment.index')}}" style="font-weight: bold">{{__('Thanh toán')}}</a>
    </li>
{{--    <li class="nav-item">--}}
{{--        <a class="nav-link {{ in_array(\Request::route()->getName(),['referral.multiLevelConfig','referral.editMultiLevelConfig']) ? 'active' : ''}}" href="{{route('referral.multiLevelConfig')}}" style="font-weight: bold">{{__('Cấu hình nhiều cấp')}}</a>--}}
{{--    </li>--}}
    <li class="nav-item">
{{--        <a class="nav-link {{ in_array(\Request::route()->getName(),['referral.multiLevelConfig','referral.editMultiLevelConfig','referral.historyGeneralConfig']) ? 'active' : ''}}" href="{{route('referral.generalConfig')}}">Cấu hình chung</a>--}}
        <a style="font-weight: bold" class="nav-link {{ in_array(\Request::route()->getName(),['referral.generalConfig','referral.historyGeneralConfig','referral.editGeneralConfig']) ? 'active' : ''}}" href="{{route('referral.generalConfig')}}">{{__('Cấu hình chung')}}</a>
    </li>
</ul>
<hr style="margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #0067AC">

<style>
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
