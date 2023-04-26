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

    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("DANH SÁCH CA LÀM VIỆC")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--@if(in_array('shift.create', session('routeList')))--}}
                <a href="javascript:void(0)" onclick="create.popupCreate(false)"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc ">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM CA LÀM VIỆC')</span>
                                    </span>
                </a>

                {{--@endif--}}

                @if(in_array('timekeeping', session('routeList')))
                    <a href="{{route('timekeeping')}}" target="_blank"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-left-5">
                <span>
                <i class="la la-th-list"></i>
                <span> {{__('BẢNG CHẤM CÔNG')}}</span>
                </span>
                    </a>
                @endif
                @if(in_array('timekeeping', session('routeList')))
                    <a href="{{route('shift.time-working-staff')}}" target="_blank"
                    class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-left-5">
                        <span>
                        <i class="la la-calendar-o"></i>
                        <span style="text-transform: uppercase;"> {{__('Lịch làm việc của nhân viên')}}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="@lang("Nhập tên ca")">
                                </div>

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
                    @include('shift::shift.list')
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
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>

    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/shift/shift/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/shift/shift/work.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}"
            type="text/javascript"></script>

    <script>
        listShift._init();

        $(".m_selectpicker").select2({
            width: "100%"
        });
    </script>

    <script type="text/template" id="input-min-time-work-tpl">
        <input type="text" class="form-control m-input phone" id="min_time_work"
               name="min_time_work" placeholder="@lang('Số giờ')" value="{hour}">
    </script>
@stop