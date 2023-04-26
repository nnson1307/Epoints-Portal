@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('Quản lý QR Code') }}</span>
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

        /*.background-status {*/
        /*    color: #fff;*/
        /*    text-align: center;*/
        /*    padding: 10px 25px;*/
        /*    border-radius: 25px;*/
        /*    width: fit-content;*/
        /*}*/

        .box-qr-code-download {
            height: 80vh;
            overflow-y: auto;
        }

        .block-qr {
            text-align: center;
        }

    </style>
@endsection
@section('content')
    <form id="form-qr-code">
        <div class="m-portlet" id="autotable">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="la la-th-list"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            {{ __('Chi tiết MÃ QR CODE') }}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">

                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row">
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
                                                <input type="radio" class="custom-control-input" id="apply_for_custom" disabled  name="apply_for" value="custom" {{$detail['apply_for'] == 'custom' ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="apply_for_custom">{{__('Tùy chỉnh')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="apply_for_all" disabled name="apply_for" value="all" {{$detail['apply_for'] == 'all' ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="apply_for_all">{{__('Tất cả các bàn')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group apply_block" style="display: {{$detail['apply_for'] == 'custom' ? 'block' : 'none'}}">
                                    <div class="row">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Áp dụng cho chi nhánh')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <p class="mb-0">{{$detail['branch_name']}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group apply_block" style="display: {{$detail['apply_for'] == 'custom' ? 'block' : 'none'}}">
                                    <div class="row">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Áp dụng cho khu vực')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <p class="mb-0">{{$detail['apply_arear_id'] == -1 ? __('Tất cả') : $detail['area_name']}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group apply_block" style="display: {{$detail['apply_for'] == 'custom' ? 'block' : 'none'}}">
                                    <div class="row">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Áp dụng bàn')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <p class="mb-0">{{$detail['apply_table_id'] == -1 ? __('Tất cả') : $detail['table_name']}}</p>
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
                                                <input type="radio" class="custom-control-input" disabled id="expire_type_limited" name="expire_type" value="limited" {{$detail['expire_type'] == 'limited' ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="expire_type_limited">{{__('Tùy chỉnh')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" disabled id="expire_type_unlimited" name="expire_type" value="unlimited" {{$detail['expire_type'] == 'unlimited' ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="expire_type_unlimited">{{__('Vô hạn')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group expire_type" style="display : {{$detail['expire_type'] == 'unlimited' ? 'none' : 'block'}}" >
                                    <div class="row">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Thời gian từ')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <div class="col-5 text-center">
                                                    {{isset($detail['expire_start']) ? \Carbon\Carbon::parse($detail['expire_start'])->format('H:i d/m/Y') : ''}}
                                                </div>
                                                <div class="col-2 d-flex align-items-center justify-content-center ">
                                                    <p class="mb-0">{{__('đến')}}</p>
                                                </div>
                                                <div class="col-5 pl-0 text-center">
                                                    {{isset($detail['expire_end']) ? \Carbon\Carbon::parse($detail['expire_end'])->format('H:i d/m/Y') : ''}}
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
                                            <div class="background-status" style="background : {{$status[$detail['status']]['color']}}">
    {{--                                            {{$status[$detail['status']]['name']}}--}}
                                                <select class="form-control" name="status" id="status">
                                                    @foreach($status as $key => $item)
                                                        <option value="{{$key}}" {{$detail['status'] == $key ? 'selected' : ''}}>{{$item['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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
                                                <input type="checkbox" {{$detail['is_request_location'] == 1 ? 'checked' : ''}} class="manager-btn is_request_location" name="is_request_location">
                                                <span></span>
                                            </label>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group expire_type is_request_location_block" style="display: {{$detail['is_request_location'] == 1 ? 'block' : 'none'}}">
                                    <div class="row form-group">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Kinh độ')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="location_lat" value="{{$detail['location_lat']}}" placeholder="{{__('Nhập kinh độ')}}">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Vĩ độ')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="location_lng" value="{{$detail['location_lng']}}" placeholder="{{__('Nhập vĩ độ')}}">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Bán kính cho phép')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="location_radius" value="{{$detail['location_radius']}}" placeholder="{{__('Nhập bán kính cho phép')}}">
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
                                                <input type="checkbox"  class="manager-btn is_request_wifi"{{$detail['is_request_wifi'] == 1 ? 'checked' : ''}} name="is_request_wifi" style="display: {{$detail['is_request_wifi'] == 1 ? 'checked' : ''}}">
                                                <span></span>
                                            </label>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group expire_type is_request_wifi_block" style="display:{{$detail['is_request_wifi'] == 1 ? 'block' : 'none'}}">
                                    <div class="row form-group">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Tên wifi')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="wifi_name" value="{{$detail['wifi_name']}}" placeholder="{{__('Nhập tên wifi')}}">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-3 offset-1 d-flex align-items-center">
                                            <p class="mb-0">{{__('Địa chỉ IP')}}:</p>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control wifi_ip" name="wifi_ip" value="{{$detail['wifi_ip']}}" placeholder="{{__('Nhập địa chỉ IP')}}">
                                        </div>
                                    </div>

                                </div>
    {{--                            <div class="col-12 form-group">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-4 d-flex align-items-center">--}}
    {{--                                        <p class="mb-0"><strong>{{__('Ghi chú')}}</strong>:</p>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-8">--}}
    {{--                                        <input type="text" class="form-control" name="qc_note">--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
                            </div>
                            <div class="row">
                                <div class="col-12 bg mb-3">
                                    <p class="mb-0 pt-2 pb-2"><strong>{{__('Thông tin mã QR Code')}}</strong></p>
                                </div>
                                <div class="col-12 form-group">
                                    <p><strong>{{__('Loại mã QR Code')}}:</strong> <span>URL</span></p>

                                </div>
                                <div class="col-12 form-group">
                                    <p><strong>{{__('Trang web (URL)')}}:</strong> <span>{{$config['value']}}</span></p>
                                </div>
    {{--                            <div class="col-12">--}}
    {{--                                <p><strong>{{__('Số lần quét QR')}}:</strong> <span>{{count($totalScan)}}</span></p>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-12 form-group">--}}
    {{--                                <form id="search-table">--}}
    {{--                                    <div class="row">--}}
    {{--                                        <input type="hidden" name="qr_code_template_id" value="{{$detail['qr_code_template_id']}}">--}}
    {{--                                        <div class="col-4">--}}
    {{--                                            <input type="text" class="form-control datepicker_search" placeholder="{{__('Chọn thời gian')}}">--}}
    {{--                                        </div>--}}
    {{--                                        <div class="col-4">--}}

    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </form>--}}
    {{--                            </div>--}}
    {{--                            <div class="col-12">--}}
    {{--                                <div class="table-responsive append-table-table">--}}

    {{--                                </div>--}}
    {{--                            </div>--}}
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12 bg mb-3">
                                    <p class="mb-0 pt-2 pb-2"><strong>{{__('Ảnh QR Code')}}</strong></p>
                                </div>
                                <div class="col-12">
                                    <div class="row box-qr-code-download mb-5" id="box-qr-code-download">
                                        @foreach($listQr as $item)
                                            <div class=" block-qr mb-5" style="width:45%; display:inline-block;text-align:center">
                                                <svg viewBox="0 0 350 350" xmlns="http://www.w3.org/2000/svg" width="80%" data-code="{{$item['code']}}">
                                                    @if($item['frames_frames_id'] != 1)
                                                        {!! $item['frames_image'] !!}
                                                    @endif
                                                    @if(isset($item['template_content']))
                                                        <g transform="{{$item['transform_text']}}"><style>
                                                                .small38{
                                                                    fill:{{$item['template_color']}};
                                                                    font-size:25px;
                                                                    font-family: {{isset($item['font_value']) ? $item['font_value'] : 'Roboto, sans-serif'}} ;
                                                                }</style>
                                                            <text x="0" y="-11" text-anchor="middle" class="small38">
                                                                {{$item['template_content']}}
                                                            </text>
                                                        </g>
                                                    @endif

                                                    <g transform="{{$item['transform_qr_code']}}" >
                                                        @if(isset($item['template_logo']))
                                                            <svg width="300" height="300">
                                                                <style>.background-color{ fill: transparent; }.dot-color{ fill: #000000; }.corners-square-color-0-0{ fill: #000000; }.corners-dot-color-0-0{ fill: #000000; }.corners-square-color-1-0{ fill: #000000; }.corners-dot-color-1-0{ fill: #000000; }.corners-square-color-0-1{ fill: #000000; }.corners-dot-color-0-1{ fill: #000000; }</style>
                                                                <image
                                                                        width="280"
                                                                        height="280"
                                                                        xlink:href="data:image/png;base64,{!!
                                                                            base64_encode(QrCode::format('png')
                                                                            ->merge($item['template_logo'], 0.3, true)
                                                                            ->size(400)->errorCorrection('H')
                                                                            ->generate($item['url']))
                                                                        !!}"
                                                                />
                                                            </svg>
                                                        @else
                                                            {!! QrCode::size(300)->generate($item['url']); !!}
                                                        @endif
                                                    </g>
                                                    <g transform = "translate(150, 360)scale(1.5)">
                                                        <style>
                                                            .small39{
                                                                {{--fill:{{$item['template_color']}};--}}
                                                                font-size:25px;
                                                                font-family: {{isset($item['font_value']) ? $item['font_value'] : 'Roboto, sans-serif'}} ;
                                                            }</style>
                                                        <text x="0" y="-11" text-anchor="middle" class="small39">
                                                            {{$item['areas_name']}} - {{$item['table_name']}}
                                                        </text>
                                                    </g>
                                                </svg>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12 form-group">
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
                                    <button type="button" class="btn btn-refresh ss--button-cms-piospa m-btn--icon " onclick="qrCode.preview('detail','{{$detail['qr_code_template_id']}}')">
                                        {{__('Xem trước')}}
                                    </button>
                                    <button type="button" class="btn btn-refresh ss--button-cms-piospa m-btn--icon " onclick="qrCode.download()">
                                        {{__('Tải xuống')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <input type="hidden" id="qr_code_template_id" name="qr_code_template_id" value="{{$detail['qr_code_template_id']}}">
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
                                onclick="qrCode.editQrCode()"
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
    </form>
    @if(isset($listQr[0]))
        <input type="hidden" class="list-frames" data-frame-id="{{$listQr[0]['frames_frames_id']}}">
        <input type="hidden" class="list-logo" data-image-logo="{{$listQr[0]['template_logo']}}">
        <input type="hidden" class="jscolor" data-current-color="{{$listQr[0]['template_color']}}">
        <input type="hidden" id="template_font_id" data-value="{{$listQr[0]['font_value']}}">
        <input type="hidden" id="scan-text" value="{{$listQr[0]['template_content']}}">
    @endif
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/fnb/qr-code/script.js?v='.time())}}"></script>
    <script>
        qrCode._initAdd();
        configColumn.searchTable();
    </script>
@stop
