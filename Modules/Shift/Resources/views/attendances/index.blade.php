@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
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
                    @lang("Lịch sử chấm công")
                </h2>
            </div>
        </div>
        
    </div>
    <div class="m-portlet__body">
        <div id="autotable">
            <form class="frmFilter bg">
                <input type="hidden" id="isValid" name="isValid" value="0">
                <div class="row padding_row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" placeholder="{{ __('Nhập tên nhân viên') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select class="form-control m-input" name="department_id" id="department_id">
                            <option value="" selected="selected">@lang('Chọn phòng ban')</option>
                            @if(isset($department))
                                @foreach ($department as $key => $item)
                                    <option value="{{ $item['department_id'] }}">{{ $item['department_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select class="form-control m-input" name="branch_id" id="branch_id">
                            <option value="" selected="selected">@lang('Vui lòng chọn chi nhánh.')</option>
                            @if(isset($branch))
                                @foreach ($branch as $key => $item)
                                    <option value="{{ $item['branch_id'] }}">{{ $item['branch_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select class="form-control m-input" name="status" id="status">
                            <option value="0" selected="selected">@lang('Vui lòng chọn trạng thái')</option>
                            <option value="1">@lang('Đi đúng giờ')</option>
                            <option value="2">@lang('Đi trễ')</option>
                            <option value="3">@lang('Về sớm')</option>
                            <option value="4">@lang('Chưa checkout')</option>
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input readonly class="form-control m-input daterange-picker"
                                   style="background-color: #fff" name="created_at"
                                   autocomplete="off" placeholder="{{ __('Ngày làm việc') }}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary color_button btn-search">
                            SEARCH <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                @include('shift::attendances.list')
            </div>
        </div>
       
    </div>
</div>
<div id="my-modal"></div>
<div id="popup-work-edit"></div>
<div id="vund_popup"></div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
<script src="{{asset('static/backend/js/shift/attendances/list.js?v='.time())}}"></script>
<script>
   $('#branch_id').select2();
   $('#department_id').select2();
   $('#staff').select2();
   $('#status').select2();
   $('#date_start').datepicker()
</script>
@stop
