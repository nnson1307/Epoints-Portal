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

        .form-control-feedback {
            color: red;
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
                <a href="{{route('shift.work-schedule.create')}}"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill">
                <span>
                <i class="fa fa-plus-circle"></i>
                <span> {{__('PHÂN CA LÀM')}}</span>
                </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-12" style="text-align: right;">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show" href="{{route('shift.work-schedule')}}">
                                @lang('Lịch làm việc')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('shift.time-working-staff.index-shift')}}">
                                @lang('Theo ca')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('shift.time-working-staff')}}">
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
                                <input type="text" class="form-control" name="search"
                                placeholder="@lang("Nhập tên lịch làm việc")">
                            </div>
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 3 == 0))
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
                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="table-content m--padding-top-30">
                    @include('shift::work-schedule.list')
                </div><!-- end table-content -->
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/shift/work-schedule/list.js')}}" type="text/javascript"></script>
    <script>
        $(".m_selectpicker").select2({
            width: "100%"
        });
    </script>
@stop
