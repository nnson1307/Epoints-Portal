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
        .line_note_shift {
            color: #444;
            font-size: 0.8rem;
            line-height: 15px;
            min-height: 15px;
            min-width: 15px;
            vertical-align: middle;
            text-align: center;
            display: inline-block;
            padding: 0px 2px;
            border-radius: 0.75rem;
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
                        @lang("CHẤM CÔNG")
                    </h2>
                </div>
                {{-- @include('shift::timekeeping.navigation') --}}
            </div>

        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
                        <div class="form-group">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-lg-3 form-group">
                                    <select class="form-control m_selectpicker" id="staff_object" name="staff_object" disabled>
                                       @foreach ($staff as $value => $item)
                                        <option value="{{$item['staff_id']}}" {{$item['staff_id'] == $objStaff['staff_id'] ? 'selected': ''}}>
                                           {{ $item['full_name'] }}
                                        </option>
                                       @endforeach
                                    </select>
                                    <input type="hidden" value="{{ $objStaff['staff_id'] }}" name="staff_id" id="staff_id">
                                    <input type="hidden" value="{{ $itemYear }}" name="date_year">
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select class="form-control m-input width-select" name="date_object"
                                    id="date_object" style="width : 100%;">
                                  
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{$i}}" {{$i == $itemMonth ? 'selected': ''}}>
                                                {{__('Tháng ' . $i)}}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                              
                            </div>

                        </div>
                    </div>
                </form>
                @include('shift::timekeeping.list_detail')
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="my-modal-my-shift"></div>
    <div id="my-modal-recompense"></div>

    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/huniel.css')}}">
@stop
@section('after_script')
<script src="{{asset('static/backend/js/shift/time-working-staff/list.js?v='.time())}}"
type="text/javascript"></script>
    <script>
        $('#date_object, #staff_object').select2();
        $('#date_object').change(function(){
            $(".frmFilter").submit();
            
        });
        $('#autotable').PioTable({
            baseUrl: laroute.route('timekeeping.list_detail')
        });
    </script>
@stop
