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
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ __('THÊM GIAI ĐOẠN') }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
            <div class="d-flex">
                <a href="{{route('manager-project.project.project-info-phase',['id' => $detailProject['manage_project_id']])}}"
                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md align-self-center">
                    <span>
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('HỦY') }}</span>
                    </span>
                </a>

{{--                <a href="{{route('manager-project.phase.template-sample',['id' => $detailProject['manage_project_id']])}}"--}}
{{--                   class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center">--}}
{{--                    <span>--}}
{{--                        <i class="la la-file"></i>--}}
{{--                        <span>{{ __('Sử dụng mẫu có sẵn') }}</span>--}}
{{--                    </span>--}}
{{--                </a>--}}

{{--                <button type="button" onclick="Phase.submitAdd('{{$detailProject['manage_project_id']}}','save')"--}}
{{--                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center">--}}
{{--                    <span>--}}
{{--                        <i class="la la-plus"></i>--}}
{{--                        <span>{{ __('Lưu & Lưu mẫu') }}</span>--}}
{{--                    </span>--}}
{{--                </button>--}}

                <button type="button" onclick="Phase.submitAdd('{{$detailProject['manage_project_id']}}')"
                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center">
                    <span>
                        <i class="la la-check"></i>
                        <span>{{ __('LƯU THÔNG TIN') }}</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="m-portlet__body ">
            <div class="row add-phase">
                @foreach($listPhase as $key => $item)
                    <div class="col-lg-12 block block_{{$key}}">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Dự án') }}:</label>
                                    <input type="text" class="form-control m-input" disabled readonly value="{{$detailProject['manage_project_name']}}">
                                    <input type="hidden" class="form-control m-input manage_project_id"  value="{{$detailProject['manage_project_id']}}">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Tên giai đoạn') }}:<b class="text-danger">*</b></label>
                                    <input type="text" class="form-control m-input name" name="group[{{$key}}]['name']" value="{{$item['name']}}" placeholder="{{__('Nhập tên giai đoạn')}}">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Người chịu trách nhiệm') }}:<b class="text-danger">*</b></label>
                                    <select class="form-control select2 pic"  name="group[{{$key}}]['pic']">
                                        <option value="">{{__('Người chịu trách nhiệm')}}</option>
                                        @foreach($listStaff as $item)
                                            <option value="{{$item['staff_id']}}">{{$item['staff_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Ngày bắt đầu') }}:</label>
                                    <input type="text" class="form-control m-input date_start" name="group[{{$key}}]['date_start']" value="{{$item['date_start'] == null ? \Carbon\Carbon::parse($item['date_start'])->format('d/m/Y') : ''}}" placeholder="{{__('Ngày bắt đầu')}}">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Ngày kết thúc') }}:</label>
                                    <input type="text" class="form-control m-input date_end" name="group[{{$key}}]['date_end']" value="{{$item['date_end'] == null ? \Carbon\Carbon::parse($item['date_end'])->format('d/m/Y') : ''}}" placeholder="{{__('Ngày kết thúc')}}">
                                </div>
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                <button type="button" onclick="Phase.removeContentPhase('{{$key}}')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa"><i class="la la-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <button type="button" onclick="Phase.addContentPhase()" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center">
                    <span>
                        <i class="fa fa-plus"></i>
                        <span>{{__('Thêm giai đoạn')}}</span>
                    </span>
                </button>
            </div>
        </div>
        <input type="hidden" id="main_manage_project_id" value="{{$detailProject['manage_project_id']}}">
    </div>
@stop

@section('after_script')
    <script>
        var phaseTmp = parseInt('{{count($listPhase)}}');

        $(document).ready(function (){
            $('.date_start').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'dd/mm/yyyy',
            });
            $('.date_end').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'dd/mm/yyyy',
            });
        });
    </script>
    <script type="text/template" id="tpl-phase">
        <div class="col-lg-12 block block_{n}">
            <div class="row">
                <div class="col-2">
                    <div class="form-group m-form__group">
                        <label class="black-title">{{ __('Dự án') }}:</label>
                        <input type="text" class="form-control m-input" disabled readonly value="{{$detailProject['manage_project_name']}}">
                        <input type="hidden" class="form-control m-input manage_project_id"  value="{{$detailProject['manage_project_id']}}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group m-form__group">
                        <label class="black-title">{{ __('Tên giai đoạn') }}:<b class="text-danger">*</b></label>
                        <input type="text" class="form-control m-input name" name="group[{n}]['name']" placeholder="{{__('Nhập tên giai đoạn')}}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group m-form__group">
                        <label class="black-title">{{ __('Người chịu trách nhiệm') }}:<b class="text-danger">*</b></label>
                        <select class="form-control select2 pic"  name="group[{n}]['pic']">
                            <option value="">{{__('Người chịu trách nhiệm')}}</option>
                            @foreach($listStaff as $item)
                            <option value="{{$item['staff_id']}}">{{$item['staff_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group m-form__group">
                        <label class="black-title">{{ __('Ngày bắt đầu') }}:</label>
                        <input type="text" class="form-control m-input date_start" name="group[{n}]['date_start']" placeholder="{{__('Ngày bắt đầu')}}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group m-form__group">
                        <label class="black-title">{{ __('Ngày kết thúc') }}:</label>
                        <input type="text" class="form-control m-input date_end" name="group[{n}]['date_end']" placeholder="{{__('Ngày kết thúc')}}">
                    </div>
                </div>
                <div class="col-2 d-flex align-items-center">
                    <button type="button" onclick="Phase.removeContentPhase('{n}')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa"><i class="la la-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </script>
    <script src="{{ asset('static/backend/js/manager-project/phase/script.js?v=' . time()) }}" type="text/javascript">
@endsection
