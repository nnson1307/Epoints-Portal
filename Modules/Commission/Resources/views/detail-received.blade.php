@extends('layout')
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
    <style>
        .note-editor {
            width: 100%;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('QUẢN LÝ HOA HỒNG') }}
    </span>
@endsection
@section('content')
    <style>
        .btn.btn-default, .btn.btn-secondary.active {
            color: #fff !important;
            background: #4fc4ca !important;
            border-color: #4fc4ca !important;
        }
    </style>

    {{--<form id="form-banner" autocomplete="off">--}}
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="la la-eye"></i>
                        </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('CHI TIẾT HOA HỒNG NHÂN VIÊN') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('admin.commission.received')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="form-group">
                <strong>@lang('Nhân viên'):</strong> {{$item['staff_name']}}
            </div>
            <div class="form-group">
                <strong>@lang('Chi nhánh'):</strong> {{$item['branch_name']}}
            </div>
            <div class="form-group">
                <strong>@lang('Phòng ban'):</strong> {{$item['department_name']}}
            </div>
            <div class="form-group">
                <strong>
                    @lang('Tổng hoa hồng thực nhận'):
                </strong>
                {{number_format($item['total_commission_money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('VNĐ')
            </div>
            <div class="row">
                <div class="form-group col-lg-3">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr class="ss--nowrap">
                                <th class="ss--font-size-th">{{ __('#') }}</th>
                                <th class="ss--font-size-th">{{ __('CÁC CHỈ SỐ TÍNH HOA HỒNG') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($allocation))
                                @foreach ($allocation as $k => $v)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            {{$k+1}}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <a href="{{route('admin.commission.detail', $v['commission_id'])}}" target="_blank">
                                                {{$v['commission_name']}}
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">@lang('Không có dữ liệu')</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group col-lg-9" id="autotable-staff-commission">
                    <div class="padding_row bg">
                        <form class="frmFilter">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <input type="hidden" name="staff_id" value="{{$item['staff_id']}}">

                                    <select class="form-control" id="month" name="month">
                                        <option value="1" {{\Carbon\Carbon::now()->format('m') == 1 ? 'selected': ''}}>@lang('Tháng 1')</option>
                                        <option value="2" {{\Carbon\Carbon::now()->format('m') == 2 ? 'selected': ''}}>@lang('Tháng 2')</option>
                                        <option value="3" {{\Carbon\Carbon::now()->format('m') == 3 ? 'selected': ''}}>@lang('Tháng 3')</option>
                                        <option value="4" {{\Carbon\Carbon::now()->format('m') == 4 ? 'selected': ''}}>@lang('Tháng 4')</option>
                                        <option value="5" {{\Carbon\Carbon::now()->format('m') == 5 ? 'selected': ''}}>@lang('Tháng 5')</option>
                                        <option value="6" {{\Carbon\Carbon::now()->format('m') == 6 ? 'selected': ''}}>@lang('Tháng 6')</option>
                                        <option value="7" {{\Carbon\Carbon::now()->format('m') == 7 ? 'selected': ''}}>@lang('Tháng 7')</option>
                                        <option value="8" {{\Carbon\Carbon::now()->format('m') == 8 ? 'selected': ''}}>@lang('Tháng 8')</option>
                                        <option value="9" {{\Carbon\Carbon::now()->format('m') == 9 ? 'selected': ''}}>@lang('Tháng 9')</option>
                                        <option value="10" {{\Carbon\Carbon::now()->format('m') == 10 ? 'selected': ''}}>@lang('Tháng 10')</option>
                                        <option value="11" {{\Carbon\Carbon::now()->format('m') == 11 ? 'selected': ''}}>@lang('Tháng 11')</option>
                                        <option value="12" {{\Carbon\Carbon::now()->format('m') == 12 ? 'selected': ''}}>@lang('Tháng 12')</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3">
                                    <select class="form-control" id="commission_type" name="commission_type">
                                        <option value="">@lang('Chọn loại hoa hồng')</option>
                                        <option value="order">@lang('Đơn hàng')</option>
                                        <option value="kpi">@lang('Kpi')</option>
                                        <option value="contract">@lang('Hợp đồng')</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <button class="btn btn-primary color_button btn-search" style="display: block">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-content m--margin-top-30">
                        @include('commission::components.received.detail.list-staff-commission')
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--</form>--}}
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
        $('#month, #commission_type').select2({
            width: "100%"
        });

        $('#autotable-staff-commission').PioTable({
            baseUrl: laroute.route('admin.commission.list-staff-commission')
        });
    </script>
@stop
