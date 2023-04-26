<div class="row">
    <div class="col-lg-6">
        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Loại khách hàng")
                :
                @switch($info['customer_type'])
                    @case('personal')
                        @lang('Cá nhân')
                    @break
                    @case('business')
                        @lang('Doanh nghiệp')
                        @break
                @endswitch
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Tên khách hàng")
                : {{$info['full_name']}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Số điện thoại / hotline")
                : {{$info['phone1']}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Email")
                : {{$info['email'] != null ? $info['email'] : __('Chưa xác định')}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Địa chỉ")
                : {{$info['full_address'] != null ? $info['full_address'] : __('Chưa xác định')}}
            </span>
        </div>
        @if ($info['customer_type'] == 'personal')
            <div class="form-group m-form__group">
                <span class="sz_dt">@lang("Giới tính")
                    :
                    @switch($info['gender'])
                        @case('male')
                            @lang('Nam')
                            @break
                        @case('female')
                            @lang('Nữ')
                            @break
                        @case('other')
                            @lang('Khác')
                            @break
                    @endswitch
                </span>
            </div>
        @endif
        <div class="form-group m-form__group">
            <span class="sz_dt">
                @switch($info['customer_type'])
                    @case('personal')
                        @lang("Ngày sinh nhật") : {{$info['birthday'] != null ? \Carbon\Carbon::parse($info['birthday'])->format('d/m/Y') : __('Chưa xác định')}}
                    @break
                    @case('business')
                        @lang("Ngày thành lập") : {{$info['birthday'] != null ? \Carbon\Carbon::parse($info['birthday'])->format('d/m/Y') : __('Chưa xác định')}}
                    @break
                @endswitch
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Người giới thiệu")
                : {{$info['refer_name'] != null ? $info['refer_name'] : __('Chưa xác định')}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Mã hồ sơ")
                : {{$info['profile_code'] != null ? $info['profile_code'] : __('Chưa xác định')}}
            </span>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Nhóm khách hàng")
                : {{$info['group_name'] != null ? $info['group_name'] : __('Chưa xác định')}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Nguồn khách hàng")
                : {{$info['customer_source_name'] != null ? $info['customer_source_name'] : __('Chưa xác định')}}
            </span>
        </div>

{{--        <div class="form-group m-form__group">--}}
{{--            <span class="sz_dt">@lang("Tag")--}}
{{--                :--}}
{{--            </span>--}}
{{--        </div>--}}

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Zalo profile")
                : {{$info['zalo'] != null ? $info['zalo'] : __('Chưa xác định')}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Facebook profile")
                : {{$info['facebook'] != null ? $info['facebook'] : __('Chưa xác định')}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Chi nhánh")
                : {{$info['branch_name'] != null ? $info['branch_name'] : __('Chưa xác định')}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Ngày tạo")
                : {{$info['created_at'] != null ? \Carbon\Carbon::parse($info['created_at'])->format('d/m/Y H:i:s') : __('Chưa xác định')}}
            </span>
        </div>

        <div class="form-group m-form__group">
            <span class="sz_dt">@lang("Ngày cập nhật")
                : {{$info['updated_at'] != null ? \Carbon\Carbon::parse($info['updated_at'])->format('d/m/Y H:i:s') : __('Chưa xác định')}}
            </span>
        </div>
    </div>
</div>