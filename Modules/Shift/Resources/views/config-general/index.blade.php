@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ NHÂN VIÊN')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("CẤU HÌNH CHẤM CÔNG")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="view.submitEdit()"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc ">
                    <span>
                        <i class="la la-check"></i>
                        <span>@lang('LƯU THÔNG TIN')</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <ul class="nav nav-pills" role="tablist">
                @if (in_array('shift.config-general',session('routeList')))
                    <li class="nav-item">
                        <a class="nav-link active show" href="{{route('shift.config-general')}}">
                            @lang('Cấu hình chung')
                        </a>
                    </li>
                @endif
                @if (in_array('timekeeping-config',session('routeList')))
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('timekeeping-config')}}">
                            @lang('Danh sách cấu hình chấm công')
                        </a>
                    </li>
                @endif
            </ul>

            <div class="data_view">
                @if (count($list) > 0)
                    @foreach($list as $v)
                        <div class="form-group row div_child">
                            <input type="hidden" class="config_general_id" value="{{$v['config_general_id']}}">
                            <input type="hidden" class="config_general_code" value="{{$v['config_general_code']}}">

                            <div class="col-lg-3">
                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                    <input type="checkbox" class="is_actived" {{$v['is_actived'] == 1 ? 'checked': ''}}>
                                    @switch($v['config_general_code'])
                                        @case('late_check_in')
                                        @lang('Tính là đi trễ khi thời gian chấm công vào sau')
                                        @break
                                        @case('off_check_in')
                                        @lang('Tính là nghỉ không lương khi thời gian chấm công vào sau')
                                        @break
                                        @case('back_soon_check_out')
                                        @lang('Tính là về sớm khi thời gian chấm công ra trước')
                                        @break
                                        @case('off_check_out')
                                        @lang('Tính là nghỉ không lương khi thời gian chấm công ra trước')
                                        @break
                                    @endswitch
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="input-group col-lg-4">
                                        <input type="text" class="form-control m-input config_general_value"
                                               value="{{$v['config_general_value']}}">
                                        <div class="input-group-append">
                                <span class="input-group-text">
                                    @switch($v['config_general_unit'])
                                        @case('minute')
                                        @lang('Phút')
                                        @break
                                        @case('hour')
                                        @lang('Giờ')
                                        @break
                                    @endswitch
                                </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        @switch($v['config_general_code'])
                                            @case('late_check_in')
                                            @lang('so với thời gian làm việc bắt đầu')
                                            @break
                                            @case('off_check_in')
                                            @lang('so với thời gian làm việc bắt đầu')
                                            @break
                                            @case('back_soon_check_out')
                                            @lang('so với thời gian làm việc kết thúc')
                                            @break
                                            @case('off_check_out')
                                            @lang('so với thời gian làm việc kết thúc')
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/shift/config-general/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init();
    </script>
@stop