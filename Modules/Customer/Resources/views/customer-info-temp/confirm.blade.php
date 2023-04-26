@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> @lang('QUẢN LÝ KHÁCH HÀNG')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-check-square"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__("XÁC NHẬN THÔNG TIN")}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-confirm">
            <div class="m-portlet__body">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="m-section__heading">@lang("THÔNG TIN HIỆN TẠI")</h4>
                        <input type="hidden" id="customer_id" name="customer_id" value="{{$item['customer_id']}}">
                        <input type="hidden" id="customer_info_temp_id" name="customer_info_temp_id" value="{{$item['customer_info_temp_id']}}">

                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Tên khách hàng'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['full_name']}}"
                                           disabled>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Số điện thoại'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['phone']}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Email'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['email']}}" disabled>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Ngày sinh'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           value="{{\Carbon\Carbon::parse($item['birthday'])->format('d/m/Y')}}"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Tỉnh thành'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['province_name']}}"
                                           disabled>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Quận huyện'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['district_name']}}"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" value="{{$item['address']}}" disabled>
                            </div>
                        </div>
                        <div class="m-form__group form-group ">
                            <label class="black_title">
                                @lang('Giới tính'):
                            </label>
                            <div class="m-radio-inline">
                                <label class="m-radio cus">
                                    <input type="radio" value="male"
                                           {{$item['gender'] == 'male' ? 'checked' : ''}} disabled> @lang('Nam') <span
                                            class="span"></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" value="female"
                                           {{$item['gender'] == 'female' ? 'checked' : ''}} disabled> @lang('Nữ') <span
                                            class="span"></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" value="other"
                                           {{$item['gender'] == 'other' || $item['gender'] == null ? 'checked' : ''}} disabled> @lang('Khác')
                                    <span class="span"></span>
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="m-section__heading">@lang("THÔNG TIN YÊU CẦU THAY ĐỔI")</h4>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Tên khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           id="full_name" name="full_name" value="{{$item['full_name_temp']}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Số điện thoại'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           id="phone" name="phone" value="{{$item['phone_temp']}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Email'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           id="email" name="email" value="{{$item['email_temp']}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Ngày sinh'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           id="birthday" name="birthday"
                                           value="{{\Carbon\Carbon::parse($item['birthday_temp'])->format('d/m/Y')}}"
                                           readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Tỉnh thành'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control m-input" id="province_id" name="province_id" onchange="view.changeProvince(this)">
                                        <option></option>
                                        @if (count($optionProvince) > 0)
                                            @foreach($optionProvince as $v)
                                                <option value="{{$v['provinceid']}}" {{$v['provinceid'] == $item['province_id_temp'] ? 'selected' : ''}}>
                                                    {{$v['type'] . ' '.$v['name']}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Quận huyện'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control m-input" id="district_id" name="district_id">
                                        <option></option>
                                        @if (count($optionDistrict) > 0)
                                            @foreach($optionDistrict as $v)
                                                <option value="{{$v['districtid']}}" {{$v['districtid'] == $item['district_id_temp'] ? 'selected' : ''}}>
                                                    {{$v['type'] . ' '.$v['name']}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input"
                                       id="address" name="address" value="{{$item['address_temp']}}">
                            </div>
                        </div>
                        <div class="m-form__group form-group ">
                            <label class="black_title">
                                @lang('Giới tính'):
                            </label>
                            <div class="m-radio-inline">
                                <label class="m-radio cus">
                                    <input type="radio" name="gender"
                                           value="male" {{$item['gender_temp'] == 'male' ? 'checked' : ''}}> @lang('Nam')
                                    <span class="span"></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" name="gender"
                                           value="female" {{$item['gender_temp'] == 'female' ? 'checked' : ''}}> @lang('Nữ')
                                    <span class="span"></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" name="gender"
                                           value="other" {{$item['gender_temp'] == 'other' || $item['gender'] == null ? 'checked' : ''}}> @lang('Khác')
                                    <span class="span"></span>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('customer-info-temp')}}"
                           class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" onclick="view.confirm()"
                                class="btn  btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('XÁC NHẬN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer/customer-info-temp/script.js')}}"
            type="text/javascript"></script>
    <script>
        view._init();
    </script>
@stop
