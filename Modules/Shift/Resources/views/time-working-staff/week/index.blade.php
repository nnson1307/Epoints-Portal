@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .is_shift {
            height: 80px;
            width: 175px;
            vertical-align: inherit !important;
            text-align: center;
        }

        .is_shift_month {
            vertical-align: inherit !important;
            text-align: center;
        }

        .un_shift {
            height: 80px;
            width: 175px;
            text-align: center;
            vertical-align: inherit !important;
        }

        .line_note {
            color: #444;
            font-size: 0.8rem;
            line-height: 20px;
            min-height: 20px;
            min-width: 20px;
            vertical-align: middle;
            text-align: center;
            display: inline-block;
            padding: 0px 3px;
            border-radius: 0.75rem;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid rgba(142, 142, 142, 0.35);
            min-width: 200px;
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
                        {{__('LỊCH LÀM VIỆC')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="create.popupCreate(false)" class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc ">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span>@lang('THÊM CA LÀM VIỆC')</span>
                                    </span>
                </a>
                {{--<a href="{{route('timekeeping')}}" target="_blank"--}}
                   {{--class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">--}}
                {{--<span>--}}
                {{--<i class="la la-th-list"></i>--}}
                {{--<span> {{__('BẢNG CHẤM CÔNG')}}</span>--}}
                {{--</span>--}}
                {{--</a>--}}
            </div>
        </div>

        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-12" style="text-align: right;">
                    <ul class="nav nav-pills" role="tablist">
                        {{--<li class="nav-item">--}}
                            {{--<a class="nav-link" href="{{route('shift.work-schedule')}}">--}}
                                {{--@lang('Lịch làm việc')--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('shift.time-working-staff.index-shift')}}">
                                @lang('Theo ca')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active show" href="{{route('shift.time-working-staff')}}">
                                @lang('Theo nhân viên')
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <select class="form-control" id="years" name="years" onchange="list.changeDateType(this)">
                                 
                                    <option value="{{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}">
                                        {{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}
                                    </option>
                                    <option value="{{\Carbon\Carbon::now()->format('Y')}}" selected>
                                        {{\Carbon\Carbon::now()->format('Y')}}
                                    </option>
                                    <option value="{{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}">
                                        {{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <select class="form-control" id="date_type" name="date_type" onchange="list.changeDateType(this)">
                                    <option value="by_week" selected>@lang('Theo tuần')</option>
                                    <option value="by_month">@lang('Theo tháng')</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <select class="form-control" id="date_object" name="date_object">
                                    @for($i = 1; $i <= $week_in_year; $i++)
                                        <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->isoWeek ? 'selected': ''}}>
                                            <?php
                                            $now = \Carbon\Carbon::now();
                                            $date = $now->setISODate($now->format('Y'), $i);
                                            ?>
                                            @lang('Tuần') {{$i. ' ('.$date->startOfWeek()->format('d/m/Y'). ' - '. $date->endOfWeek()->format('d/m/Y'). ')'}}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 6 == 0))
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

                            <div class="col-lg-2 form-group">
                                <button class="btn btn-primary color_button btn-search">
                                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="table-content m--padding-top-30">
                    {{-- @if ($number_day <= 6)
                        @include('shift::time-working-staff.week.list')
                    @else
                        @include('shift::time-working-staff.week.list-month')
                    @endif --}}

                </div><!-- end table-content -->

                <div class="form-group m--padding-top-30">
                    <span class="line_note" style="background: #ECECEC;"></span> &nbsp; @lang('Chưa đến ca') &nbsp;
                    <span class="line_note" style="background: #DBEFDC;"></span> &nbsp; @lang('Chấm công đúng giờ')
                    &nbsp;
                    <span class="line_note" style="background: #FDD9D7;"></span> &nbsp; @lang('Chưa vào/ ra ca') &nbsp;
                    <span class="line_note" style="background: #FFEACC;"></span> &nbsp; @lang('Vào trễ/ ra sớm') &nbsp;
                    <span class="line_note" style="background: #D9DCF0;"></span> &nbsp; @lang('Nghỉ phép có lương')
                    &nbsp;
                    <span class="line_note" style="background: #EBD4EF;"></span> &nbsp; @lang('Nghỉ phép không lương')
                    &nbsp;
                    <span class="line_note" style="background: #F6695E;"></span> &nbsp; @lang('Tăng ca')
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="my-modal-my-shift"></div>
    <div id="my-modal-recompense"></div>

    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>

    <input type="hidden" id="week_in_year" name="week_in_year" value="{{$week_in_year}}">
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/shift/time-working-staff/list.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/shift/shift/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('#autotable').PioTable({
            baseUrl: laroute.route('shift.time-working-staff.list')
        });

        $("#years").select2({
            width: "100%"
        });
        $("#date_object").select2({
            width: "100%"
        }).on('select2:select', function () {
                $('.btn-search').trigger('click');
            });
        $("#date_type").select2({
            width: "100%"
        });
        $(".m_selectpicker").select2({
            width: "100%"
        }).on('select2:select', function () {
            $('.btn-search').trigger('click');
        });
        $('.btn-search').trigger('click');
    </script>

    <script type="text/template" id="option-week-tpl">
        @for($i = 1; $i <= $week_in_year; $i++)
        <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->isoWeek ? 'selected': ''}}>
            <?php
            $now = \Carbon\Carbon::now();
            $date = $now->setISODate($now->format('Y'), $i);
            ?>
            @lang('Tuần') {{$i. ' ('.$date->startOfWeek()->format('d/m/Y'). ' - '. $date->endOfWeek()->format('d/m/Y'). ')'}}
        </option>
        @endfor
    </script>

    <script type="text/template" id="option-month-tpl">
        @for($i = 1; $i <= 12; $i++)
        <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->format('m') ? 'selected': ''}}>
            {{ __('Tháng ' . $i) }}
        </option>
        @endfor
    </script>
    <script type="text/template" id="input-min-time-work-tpl">
        <input type="text" class="form-control m-input phone" id="min_time_work"
               name="min_time_work" placeholder="@lang('Số giờ')" value="{hour}">
    </script>
@stop