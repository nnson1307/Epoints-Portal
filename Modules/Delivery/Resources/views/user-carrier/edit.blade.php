@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ NHÂN VIÊN GIAO HÀNG')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA NHÂN VIÊN GIAO HÀNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Họ & tên'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" id="full_name" name="full_name" value="{{$item['full_name']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số điện thoại'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" id="phone" name="phone" value="{{$item['phone']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">
                                @lang('Giới tính'):
                            </label>
                            <div class="m-form__group form-group ">

                                <div class="m-radio-inline">
                                    <label class="m-radio cus">
                                        <input type="radio" name="gender" value="male" {{$item['gender'] == 'male' ? 'checked': ''}}> @lang('Nam')                                                    <span class="span"></span>
                                    </label>
                                    <label class="m-radio cus">
                                        <input type="radio" name="gender" value="female" {{$item['gender'] == 'female' ? 'checked': ''}}> @lang('Nữ')                                                    <span class="span"></span>
                                    </label>
                                    <label class="m-radio cus">
                                        <input type="radio" name="gender" value="other" {{$item['gender'] == 'other' ? 'checked': ''}}> @lang('Khác')                                                    <span class="span"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ'):
                            </label>
                            <input type="text" class="form-control m-input" id="address" name="address" value="{{$item['address']}}">
                        </div>
                        <div class="m-form__group form-group row">
                            <label class="col-lg-3 col-form-label">@lang('Ảnh đại diện'):</label>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group m-widget19">
                                    <div class="m-widget19__pic">
                                        <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                             src="{{$item['avatar'] != null ? $item['avatar'] : "https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"}}"
                                             alt="Hình ảnh"/>
                                    </div>
                                    <input type="hidden" id="avatar_old" name="avatar_old" value="{{$item['avatar']}}">
                                    <input type="hidden" id="avatar" name="avatar">
                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                           data-msg-accept="Hình ảnh không đúng định dạng"
                                           id="getFile" type='file'
                                           onchange="uploadAvatar(this);"
                                           class="form-control"
                                           style="display:none"/>
                                    <div class="m-widget19__action" style="max-width: 170px">
                                        <a href="javascript:void(0)"
                                           onclick="document.getElementById('getFile').click()"
                                           class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    @lang('Tải ảnh lên')
                                                </span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên tài khoản'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" id="user_name" name="user_name" value="{{$item['user_name']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mật khẩu mới'):
                            </label>
                            <input type="password" class="form-control m-input" id="password_new" name="password_new">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhập lại mật khẩu'):
                            </label>
                            <input type="password" class="form-control m-input" id="password_confirm" name="password_confirm">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                @lang('Trạng thái'):
                            </label>
                            <div class="row">
                                <div class="col-lg-2">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived" name="is_actived" type="checkbox"
                                {{$item['is_actived']==1?'checked':''}}>
                        <span></span>
                    </label>
                </span>
                                </div>
                                <div class="col-lg-10 m--margin-top-5">
                                    <i>@lang('Chọn để kích hoạt trạng thái')</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('user-carrier')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="edit.save({{$item['user_carrier_id']}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
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
    <script src="{{asset('static/backend/js/delivery/user-carrier/script.js?v='.time())}}" type="text/javascript"></script>

    <script>
        edit._init();
    </script>
@stop


