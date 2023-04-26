<div class="row">
    <div class="col-1">
        <div class="form-group m-form__group">
            <input type="hidden" id="staff_avatar" name="staff_avatar"
                   value="{{$detail['staff_avatar']}}">
            <input type="hidden" id="staff_avatar_upload" name="staff_avatar_upload" value="">
            <div class="form-group m-widget19">
                <div class="m-widget19__pic">
                    @if($detail['staff_avatar']!=null)
                        <img class="m--bg-metal m-image img-sd" id="blah"
                             src="{{$detail['staff_avatar']}}"
                             alt="Hình ảnh" width="220px" height="220px">
                    @else
                        <img class="m--bg-metal m-image img-sd" id="blah"
                             src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                             alt="Hình ảnh" width="220px" height="220px">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        <h5>{{$detail['full_name']}}</h5>
        <p><i class="fa fa-birthday-cake icon_color"></i> {{isset($detail['birthday']) ? \Carbon\Carbon::parse($detail['birthday'])->format('d/m/Y H:i') : ''}}</p>
        <p><i class="la la-phone icon_color"></i> {{$detail['phone1']}}</p>
        <p><i class="la la-map-marker icon_color"></i> {{$detail['address'].','.$detail['ward_type'].' '.$detail['ward_name'].','.$detail['district_type'].' '.$detail['district_name'].','.$detail['province_type'].' '.$detail['province_name']}}</p>
        <p><i class="la la-envelope icon_color"></i> {{$detail['email']}}</p>
    </div>
    <div class="col-6">
        <p>{{__('Mã giới thiệu')}} : {{$detail['referral_code']}}</p>
        <p>{{__('Người giới thiệu')}} :
            @if($referral != null)
                <a href="{{route('referral.referral-member.detailCommissionReferral',['id' => $referral['inviter_member_id']])}}">
                    {{$referral['full_name']}}
                </a>
            @endif
        </p>
        <p>{{__('Số người đã giới thiệu')}} : {{$totalRefer}}</p>
        <p>{{__('Tổng hoa hồng đã ghi nhận')}} : <strong>{{number_format($detail['total_commission'])}} đ</strong></p>
        <p>{{__('Hoa hồng chưa ghi nhận')}} : <strong>{{number_format($detail['total_money'])}} đ</strong></p>
        {{--                        <p>{{__('Hoa hồng khả dụng')}} : {{$detail['referral_code']}}</p>--}}
        <p>
            {{__('Trạng thái')}} : <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px; padding-top: 4px">
                    <input type="checkbox" id="active_list_detail" onclick="referralMember.changeStatusReferralMember('{{$detail["referral_member_id"]}}')" {{$detail['status'] == 'active' ? 'checked' : ''}}>
                    <span></span>
                </label>
            </span>
        </p>
    </div>
</div>