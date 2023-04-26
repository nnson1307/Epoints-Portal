@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ NHÂN VIÊN')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

        .select2 {
            width: 100% !important;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
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
                {{--@if(in_array('customer-lead.create',session('routeList')))--}}
                <a href="javascript:void(0)" onclick="create.popupCreate()"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM CẤU HÌNH CHẤM CÔNG')</span>
                                    </span>
                </a>
                {{--@endif--}}
            </div>
        </div>
        <div class="m-portlet__body">
            <ul class="nav nav-pills" role="tablist">
                @if (in_array('shift.config-general',session('routeList')))
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('shift.config-general')}}">
                            @lang('Cấu hình chung')
                        </a>
                    </li>
                @endif
                @if (in_array('timekeeping-config',session('routeList')))
                    <li class="nav-item">
                        <a class="nav-link active show" href="{{route('timekeeping-config')}}">
                            @lang('Danh sách cấu hình chấm công')
                        </a>
                    </li>
                @endif
            </ul>

            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
                        <div class="form-group">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-3 form-group input-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach
                                <div class="col-lg-3 form-group">
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('shift::timekeeping-config.list')
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="popup-work-edit"></div>
    <div id="vund_popup"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/shift/timekeeping-config/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/shift/timekeeping-config/work.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>


    <script>
        listTimekeepingConfig._init();

        @if(isset($param['id']))
        listTimekeepingConfig.detail({{$param['id']}})
        @endif

        $('#autotable').PioTable({
            baseUrl: laroute.route('timekeeping-config.list')
        });

        $(".m_selectpicker").select2({
            width: "100%"
        }).on('select2:select', function () {
            $('.btn-search').trigger('click');
        });


        function onTimeKeepingTypeChange(e) {

            $("#wifi_name-error").remove();
            $("#wifi_ip-error").remove();
            $("#latitude-error").remove();
            $("#longitude-error").remove();

            if (e.value == 'wifi') {
                $('.group-gps').addClass('d-none');
                $('.group-wifi').removeClass('d-none');

                $('input[name=wifi_name]', '#form-register').val('');
                $('input[name=wifi_ip]', '#form-register').val('');
                $('input[name=latitude]', '#form-register').val(' ');
                $('input[name=longitude]', '#form-register').val(' ');
            }
            else if (e.value == 'gps') {
                $('.group-wifi').addClass('d-none');
                $('.group-gps').removeClass('d-none');

                $('input[name=wifi_name]', '#form-register').val(' ');
                $('input[name=wifi_ip]', '#form-register').val(' ');
                $('input[name=latitude]', '#form-register').val('');
                $('input[name=longitude]', '#form-register').val('');
            }
        }
    </script>
@stop