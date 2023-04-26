@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('Thêm QR Code') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .timepicker {
            border: 1px solid rgb(163, 175, 251);
            text-align: center;
            /* display: inline; */
            border-radius: 4px;
            padding: 2px;
            height: 38px;
            line-height: 30px;
            width: 130px;
        }

        .timepicker .hh, .timepicker .mm {
            width: 50px;
            outline: none;
            border: none;
            text-align: center;
        }

        .timepicker.valid {
            border: solid 1px springgreen;
        }

        .timepicker.invalid {
            border: solid 1px red;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .custom-remind-item {
            color: #575962 !important;
            border: 1px solid #4bb072 !important;
            position: relative;
        }

        .custom-remind-item strong {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .custom-remind-item button {
            color: #575962 !important;
        }

        .custom-remind-item::before {
            content: '';
            position: absolute;
            left: -1px;
            background: #79cca8;
            width: 9px;
            height: calc(100% + 2px);
            top: -1px;
            /* border-radius: 0px 5px 5px 0px; */
            border-radius: 5px;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .modal .modal-content .modal-body-config {
            padding: 25px;
            max-height: 400px;
            overflow-y: scroll;
        }

        .weekDays-selector input {
            display: none !important;
        }

        .weekDays-selector input[type=checkbox] + label {
            display: inline-block;
            border-radius: 6px;
            background: #dddddd;
            height: 40px;
            width: 30px;
            margin-right: 3px;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
        }

        .weekDays-selector input[type=checkbox]:checked + label {
            background: #2AD705;
            color: #ffffff;
        }

        .table-content-font-a a {
            font-size: 1rem;
        }
        .card-header {
            padding : 0 !important;
            background : transparent;
        }

        .card-header > .btn {
            background: #54BAFF;
            position: relative;
        }

        .card-header > .collapsed {
            background: transparent !important;
        }

        .card-header > .btn:hover , .card-header > .btn:focus {
            color : #fff !important;
            background: #54BAFF !important;
        }

        .card-header > .btn i {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 3%;
            margin: auto;
            height: fit-content;
            transform: rotateX(180deg);
            transition: all 1s;
        }

        .card-header > .collapsed i {
            transform: rotateX(0);
        }

        .sample-picture {
            width: fit-content;
            text-align: center;
        }

        .sample-picture i {
            display: block;
        }

        .sample-picture i:hover {
            cursor: pointer;
        }

        .box-qr-code {
            width: 300px;
            height: 300px;
            margin: auto;
            box-shadow: 0px 1px 15px 1px rgb(69 65 78 / 10%);
        }
        .list-frames , .list-logo{
            list-style-type: none;
        }
        .list-frames li  , .list-logo li{
            display: inline-block;
            border: 3px solid #00000038;
            border-radius: 10px;
        }
        .list-frames svg{
            width : 100px;
            height : 100px;
        }

        .list-frames li.active , .list-logo li.active{
            border: 3px solid #90C52C;
        }

        .img-logo {
            width: 50px;
            border-radius : 7%;
        }

        .select2 {
            width : 100% !important;
        }

        .add-logo-text {
            border: 0;
            text-decoration: underline;
        }

        .add-logo-text:hover , .add-logo-text:focus {
            background-color : transparent !important;
        }

    </style>
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('TẠO MÃ QR CODE') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
{{--            <div class="row">--}}
                <form id="form-qr-code" class="row" autocomplete="off">
                    <div class="col-7">
                        <div class="row">
                            <div class="col-12 bg mb-3">
                                <p class="mb-0 pt-2 pb-2"><strong>{{__('Cấu hình mã QR Code')}}</strong></p>
                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <p class="mb-0"><strong>{{__('Áp dụng cho')}}</strong></p>
                                    </div>
                                    <div class="col-8 d-flex justify-content-around">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="apply_for_custom"  name="apply_for" value="custom" checked>
                                            <label class="custom-control-label" for="apply_for_custom">{{__('Tùy chỉnh')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="apply_for_all" name="apply_for" value="all">
                                            <label class="custom-control-label" for="apply_for_all">{{__('Tất cả các bàn')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group apply_block">
                                <div class="row">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Áp dụng cho chi nhánh')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" id="apply_branch_id" name="apply_branch_id">
                                            <option value="">{{__('Chọn chi nhánh')}}</option>
                                            @foreach($listBranch as $item)
                                                <option value="{{$item['branch_id']}}">{{$item['branch_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group apply_block">
                                <div class="row">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Áp dụng cho khu vực')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" id="apply_arear_id" name="apply_arear_id">
                                            <option value="">{{__('Chọn khu vực')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group apply_block">
                                <div class="row">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Áp dụng bàn')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" id="apply_table_id" name="apply_table_id">
                                            <option value="">{{__('Chọn bàn')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <p class="mb-0"><strong>{{__('Thời gian hiệu lực')}}</strong>:</p>
                                    </div>
                                    <div class="col-8 d-flex justify-content-around">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="expire_type_limited" name="expire_type" value="limited" checked>
                                            <label class="custom-control-label" for="expire_type_limited">{{__('Tùy chỉnh')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="expire_type_unlimited" name="expire_type" value="unlimited">
                                            <label class="custom-control-label" for="expire_type_unlimited">{{__('Vô hạn')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group expire_type">
                                <div class="row">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Thời gian từ')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col-5">
                                                <input type="text" class="form-control datetimepicker w-100" name="expire_start" placeholder="{{__('Chọn thời gian từ')}}">
                                            </div>
                                            <div class="col-2 d-flex align-items-center justify-content-center ">
                                                <p class="mb-0">{{__('đến')}}</p>
                                            </div>
                                            <div class="col-5 pl-0">
                                                <input type="text" class="form-control datetimepicker w-100" name="expire_end" placeholder="{{__('Chọn thời gian đến')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <p class="mb-0"><strong>{{__('Trạng thái')}}</strong>:</p>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" name="status">
                                            @foreach($status as $key => $item)
                                                <option value="{{$key}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <p class="mb-0"><strong>{{__('Yêu cầu vị trí')}}</strong>:</p>
                                    </div>
                                    <div class="col-8">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px; padding-top: 4px">
                                            <input type="checkbox"  class="manager-btn is_request_location" name="is_request_location">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group expire_type is_request_location_block" style="display:none">
                                <div class="row form-group">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Kinh độ')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="location_lat" placeholder="{{__('Nhập kinh độ')}}">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Vĩ độ')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="location_lng" placeholder="{{__('Nhập vĩ độ')}}">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Bán kính cho phép')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="location_radius" placeholder="{{__('Nhập bán kính cho phép')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <p class="mb-0"><strong>{{__('Yêu cầu đăng nhập WIFI')}}</strong>:</p>
                                    </div>
                                    <div class="col-8">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px; padding-top: 4px">
                                            <input type="checkbox"  class="manager-btn is_request_wifi" name="is_request_wifi">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group expire_type is_request_wifi_block" style="display:none">
                                <div class="row form-group">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Tên wifi')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="wifi_name" placeholder="{{__('Nhập tên wifi')}}">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-3 offset-1 d-flex align-items-center">
                                        <p class="mb-0">{{__('Địa chỉ IP')}}:</p>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control wifi_ip" name="wifi_ip" placeholder="{{__('Nhập địa chỉ IP')}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-refresh ss--button-cms-piospa m-btn--icon"  onclick="qrCode.getClientIp()">{{__('Lấy địa chỉ IP từ wifi hiện tại')}}</button>
                                </div>

                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <p class="mb-0"><strong>{{__('Ghi chú')}}</strong>:</p>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control" name="qc_note">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 bg mb-3">
                                <p class="mb-0 pt-2 pb-2"><strong>{{__('Thông tin mã QR Code')}}</strong></p>
                            </div>
                            <div class="col-12 form-group">
                                <p><strong>{{__('Loại mã QR Code')}}:</strong></p>
                                <select class="form-control" placeholder="URL" name="qr_type">
                                    <option value="url">Url</option>
                                    {{--                                @foreach($typeQR as $key => $item)--}}
                                    {{--                                    <option value="{{$key}}">{{$item}}</option>--}}
                                    {{--                                @endforeach--}}
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <p><strong>{{__('Trang web (URL)')}}:</strong></p>
                                <input type="text" readonly class="form-control" placeholder="{{__('Nhập địa chỉ URL')}}" value="{{$config['value']}}">
                            </div>
{{--                            <div class="col-12 text-right">--}}
{{--                                <button type="button" class="btn btn-refresh ss--button-cms-piospa m-btn--icon ">--}}
{{--                                    <i class="fa fa-plus-circle"></i>--}}
{{--                                    {{__('Tạo mã QR Code')}}--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="row">
                            <div class="col-12 bg mb-3">
                                <p class="mb-0 pt-2 pb-2"><strong>{{__('Ảnh QR Code')}}</strong></p>
                            </div>
                            <div class="col-12">
                                <div class="text-center d-flex align-items-center justify-content-center box-qr-code box-qr-code-download" id="box-qr-code-download">
                                    <div class="block-qr">
                                        {!! QrCode::size(200)->generate($config['value']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <p>{{__('Tùy chỉnh')}}</p>
                            </div>

                            <div class="col-12">
                                <div id="accordion">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <button type="button" class="btn btn-link w-100 text-left collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                {{__('Khung')}}
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </button>
                                        </div>

                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul class="list-frames">
                                                    @foreach($listFrames as $key => $item)
                                                        <li class="{{$key == 0 ? 'active' : ''}}" data-frame-id="{{$item['template_frames_id']}}">{!! $item['image'] !!}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <button type="button" class="btn btn-link w-100 text-left collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                {{__('Màu & Văn bản')}}
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 form-group">
                                                        <p>{{__('Phông chữ văn bản')}}</p>
                                                        <select class="form-control" name="template_font_id" id="template_font_id" onchange="qrCode.viewQrCode()">
                                                            <option value="">{{__('Chọn phông')}}</option>
                                                            @foreach($listFont as $item)
                                                                <option value="{{$item['template_font_id']}}" data-value="{{$item['value']}}">{{$item['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 form-group">
                                                        <p>{{__('Văn bản')}} :</p>
                                                        <input type="text" class="form-control" onfocusout="qrCode.viewQrCode()" id="scan-text" placeholder="{{__('Quét mã để đặt món')}}">
                                                    </div>
                                                    <div class="col-12 form-group">
                                                        <p>{{__('Chọn màu')}} :</p>
                                                        <button data-jscolor="{value:'#000'}" onfocusout="qrCode.viewQrCode()"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <button type="button" class="btn btn-link w-100 text-left collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                {{__('Logo')}}
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul class="list-logo">
                                                    @foreach($listLogo as $key => $item)
                                                        <li class="{{$key == 0 ? 'active':''}}" data-logo-id="{{$item['template_logo_id']}}" data-image-logo="{{$item['template_logo_id'] == 1 ? '' : $item['image']}}">
                                                            <img class="img-logo" src="{{$item['image']}}">
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <div class="form-group m-form__group m-widget19">
                                                    <input type="hidden" id="image" name="image">
                                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                                           data-msg-accept="Hình ảnh không đúng định dạng"
                                                           id="getFileLogo" type='file'
                                                           onchange="uploadLogo(this);"
                                                           class="form-control"
                                                           style="display:none"/>
                                                    <div class="m-widget19__action" >
                                                        <a href="javascript:void(0)"
                                                           onclick="document.getElementById('getFileLogo').click()"
                                                           class="btn  btn-sm m-btn--icon color w-100 add-logo-text">
                                                <span class="m--margin-left-20">
                                                    <span>
                                                        @lang('Thêm logo')
                                                    </span>
                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

{{--                            <div class="col-12">--}}
{{--                                <div class="m-form__group form-group row">--}}
{{--                                    <label class="col-lg-3 col-form-label d-flex align-items-center"><strong>@lang('Mẫu của tôi')</strong>:</label>--}}
{{--                                    <div class="col-lg-4">--}}
{{--                                        <div class="form-group m-form__group m-widget19">--}}
{{--                                            <input type="hidden" id="image" name="image">--}}
{{--                                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"--}}
{{--                                                   data-msg-accept="Hình ảnh không đúng định dạng"--}}
{{--                                                   id="getFile" type='file'--}}
{{--                                                   onchange="uploadAvatar(this);"--}}
{{--                                                   class="form-control"--}}
{{--                                                   style="display:none"/>--}}
{{--                                            <div class="m-widget19__action" >--}}
{{--                                                <a href="javascript:void(0)"--}}
{{--                                                   onclick="document.getElementById('getFile').click()"--}}
{{--                                                   class="btn  btn-sm m-btn--icon color w-100">--}}
{{--                                                <span class="m--margin-left-20">--}}
{{--                                                    <i class="fa fa-plus"></i>--}}
{{--                                                    <span>--}}
{{--                                                        @lang('Thêm mẫu')--}}
{{--                                                    </span>--}}
{{--                                                </span>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="m-widget19__pic mb-3 sample-picture" style="display:none">--}}
{{--                                    <img class="m--bg-metal  m-image  img-sd" id="blah_en" height="150px"--}}
{{--                                         src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"--}}
{{--                                         alt="Hình ảnh"/>--}}
{{--                                    <i class="la la-trash mt-3 text-danger" onclick="deleteImage()"></i>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-12 form-group mt-3">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center">
                                        <p class="mb-0"><strong>{{__('Tải xuống')}}</strong>:</p>
                                    </div>
                                    <div class="col-8 d-flex justify-content-around">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="download_png" name="download" value="png" checked>
                                            <label class="custom-control-label" for="download_png">{{__('PNG')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="download_svg" name="download" value="svg">
                                            <label class="custom-control-label" for="download_svg">{{__('SVG')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-refresh ss--button-cms-piospa m-btn--icon " onclick="qrCode.print()">
                                    {{__('In')}}
                                </button>
                                <button type="button" class="btn btn-refresh ss--button-cms-piospa m-btn--icon " onclick="qrCode.preview('created')">
                                    {{__('Xem trước')}}
                                </button>
                                <button type="button" class="btn btn-refresh ss--button-cms-piospa m-btn--icon " onclick="qrCode.download()">
                                    {{__('Tải xuống')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
{{--            </div>--}}
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('fnb.qr-code')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button"
                            onclick="qrCode.submitQrCode()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/customer-lead/pipeline/jscolor.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/fnb/qr-code/script.js?v='.time())}}"></script>
    <script>
        qrCode._initAdd();
    </script>
@stop
