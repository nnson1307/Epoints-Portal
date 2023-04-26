@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
                                    style="height: 20px;"> {{ __('QUẢN LÝ GiAI ĐOẠN') }}</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
@stop
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        input[type=file] {
            padding: 10px;
            background: #fff;
        }

        .m-widget5 .m-widget5__item .m-widget5__pic>img {
            width: 100%
        }

        .m-image {
            /*padding: 5px;*/
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }

        .button {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
        .group_template label:before , .group_template label:after{
            top: 120%;
            left: 0;
            right: 0;
            margin: auto;
        }
        .group_template{
            height : 100%;
            overflow-y: auto;
        }

        .height-template {
            height : 75vh;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ __('Mẫu có sẵn') }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
            <div class="d-flex">
                <a href="{{route('manager-project.phase.add',['id' => $manage_project_id])}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md align-self-center">
                    <span>
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('HỦY') }}</span>
                    </span>
                </a>

                <button type="button" onclick="Phase.applyTemplate()"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center">
                    <span>
                        <i class="la la-check"></i>
                        <span>{{ __('Áp dụng') }}</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="m-portlet__body height-template">
            <div class="row h-100">
                <div class="col-2 border-right group_template">
                    <div class="row ">
                        @foreach($listGroupPhase as $key => $item)
                            <div class="col-6">
                                <label for="customRadio{{$key}}">
                                    <img src="{{asset('static/backend/images/default-placeholder.png')}}" class="img-fluid mb-2">
                                </label>
                                <div class="custom-control custom-radio text-center p-0">
                                    <input type="radio" class="custom-control-input" onchange="Phase.changeSample('{{$item['manage_phase_group_code']}}')" id="customRadio{{$key}}" {{$key == 0 ? 'checked' : ''}} name="template" value="{{$item['manage_phase_group_code']}}">
                                    <label class="custom-control-label" for="customRadio{{$key}}">{{__('Mẫu').' '.($key + 1)}} </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-10">
                    <div class="row add-phase">
                        @foreach($listPhase as $itemPhase)
                            <div class="col-lg-12 block">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{ __('Tên giai đoạn') }}:</label>
                                            <input type="text" class="form-control m-input name" disabled value="{{$itemPhase['name']}}" placeholder="{{__('Nhập tên giai đoạn')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{ __('Ngày bắt đầu') }}:</label>
                                            <input type="text" class="form-control m-input date_start" disabled value="{{$itemPhase['date_start'] != null ? \Carbon\Carbon::parse($itemPhase['date_start'])->format('d/m/Y') : ''}}" placeholder="{{__('Ngày bắt đầu')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{ __('Ngày kết thúc') }}:</label>
                                            <input type="text" class="form-control m-input date_end" disabled value="{{$itemPhase['date_end'] != null ? \Carbon\Carbon::parse($itemPhase['date_end'])->format('d/m/Y') : ''}}" placeholder="{{__('Ngày kết thúc')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="manage_project_id" value="{{$manage_project_id}}">
@stop

@section('after_script')
    <script src="{{ asset('static/backend/js/manager-project/phase/script.js?v=' . time()) }}" type="text/javascript">
@endsection
