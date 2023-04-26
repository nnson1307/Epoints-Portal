@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('TRANG ĐẶT LỊCH')}}
    </span>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="fa fa-plus-circle"></i> {{__('THÊM ĐƠN VỊ KINH DOANH')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">


            </div>
        </div>
        <form id="form-add">
            {!! csrf_field() !!}
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tên đơn vị')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="name" class="form-control m-input btn-sm"
                                   id="name"
                                   placeholder="{{__('Nhập tên đơn vị')}}...">
                            <span class="error_name" style="color:red"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Mã đại diện')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="code" class="form-control m-input btn-sm"
                                   id="code"
                                   placeholder="{{__('Nhập mã đại diện')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="phone" class="form-control m-input btn-sm" id="phone"
                                   placeholder="{{__('Hãy nhập số điện thoại')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Email')}}:
                            </label>
                            <input type="text" name="email" class="form-control m-input btn-sm" id="email"
                                   placeholder="{{__('Hãy nhập email')}}...">
                            <span class="error_email" style="color: red"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Hot line')}}:
                            </label>
                            <input type="text" name="hot_line" class="form-control m-input btn-sm" id="hot_line"
                                   placeholder="{{__('Hãy nhập hot line')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                Facebook Fanpage:
                            </label>
                            <input type="text" name="fanpage" class="form-control m-input btn-sm" id="fanpage"
                                   placeholder="{{__('Hãy nhập link fanpage')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                Zalo:
                            </label>
                            <input type="text" name="zalo" class="form-control m-input btn-sm" id="zalo"
                                   placeholder="{{__('Hãy nhập số zalo')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                Instagram page:
                            </label>
                            <input type="text" name="instagram_page" class="form-control m-input btn-sm"
                                   id="instagram_page"
                                   placeholder="{{__('Hãy nhập link instagram')}}...">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tỉnh/ Thành phố')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="provinceid" name="provinceid">
                                    <option></option>
                                    @foreach($optionProvince as $key=>$value)
                                        @if($key==79)
                                            <option value="{{$key}}" selected>{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Quận/ Huyện')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="districtid" name="districtid">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="address" class="form-control m-input btn-sm" id="address"
                                   placeholder="{{__('Hãy nhập địa chỉ')}}...">
                        </div>
                        <div class="form-group m-form__group ">
                            <div class="row">
                                <div class="col-lg-3  w-col-mb-100">
                                    <a href="javascript:void(0)"
                                       onclick="document.getElementById('getFile').click()"
                                       class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm logo')}}
                                                </span>
                                            </span>
                                    </a>
                                </div>

                                <div class="col-lg-9  w-col-mb-100 div_avatar">
                                    <div class="wrap-img avatar">
                                        <img  class="m--bg-metal m-image img-sd" id="blah"
                                              src="http://archwayarete.greatheartsacademies.org/wp-content/uploads/sites/11/2016/11/default-placeholder.png"
                                              alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        <span class="delete-img">
                                                    <a href="javascript:void(0)" onclick="spa_info.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                        <input type="hidden" id="logo" name="logo" value="">
                                    </div>

                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                           data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                           id="getFile" type="file"
                                           onchange="uploadImage(this);" class="form-control"
                                           style="display:none">
                                    {{--<div class="m-widget19">--}}
                                    {{--<div class="m-widget19__pic">--}}
                                    {{----}}

                                    {{--</div>--}}
                                    {{----}}
                                    {{--<div class="m-widget19__action" style="max-width: 155px">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                Slogan:
                            </label>
                            <textarea rows="5" cols="40"
                                      name="slogan" id="slogan" class="form-control"></textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Ngành nghề kinh doanh')}}
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="bussiness_id" name="bussiness_id">
                                    <option></option>
                                    @foreach($optionBussiness as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.page-appointment')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>

                        <button type="button" onclick="spa_info.submit_add()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                        </span>
                        </button>

                        {{--<button type="submit" onclick="branch.add(0)"--}}
                        {{--class="btn btn-success color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">--}}
                        {{--<span>--}}
                        {{--<i class="fa fa-plus-circle"></i>--}}
                        {{--<span>{{__('LƯU & TẠO MỚI')}}</span>--}}
                        {{--</span>--}}
                        {{--</button>--}}


                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/config-page-appointment.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="avatar-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah"
             src="http://archwayarete.greatheartsacademies.org/wp-content/uploads/sites/11/2016/11/default-placeholder.png"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="#">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="logo" name="logo" value="">
    </script>
    <script src="{{asset('static/backend/js/admin/config-page-appointment/spa-info/script.js?v='.time())}}"
            type="text/javascript"></script>
@stop


