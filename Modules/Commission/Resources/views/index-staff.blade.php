@extends('layout')

@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;"/>
        {{ __('QUẢN LÝ YÊU CẦU MUA GÓI ĐẦU TƯ - TIẾT KIỆM') }}
    </span>
@endsection

@section('content')
    <style>
        #table-allocation tr:nth-child(1) {
            counter-reset: rowNumber;
        }

        #table-allocation tr {
            counter-increment: rowNumber;
        }

        #table-allocation tr td:first-child::before {
            content: counter(rowNumber);
            min-width: 1em;
            margin-right: 0.5em;
        }
    </style>

    <div class="m-portlet" id="autotable-staff">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('DANH SÁCH HOA HỒNG') }}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools nt-class">
                <a href="{{ route('admin.commission.allocation') }}" style="margin-right: 10px;"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="flaticon-refresh"></i>
                        <span> @lang('PHÂN BỔ HOA HỒNG')</span>
                    </span>
                </a>

                <a href="{{ route('admin.commission.add') }}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle m--margin-right-5"></i>
                        <span> @lang('THÊM HOA HỒNG')</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="card-header tab-card-header ">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.commission') }}">@lang('Theo hoa hồng')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active show"
                       href="{{ route('admin.commission.received') }}">@lang('Theo nhân viên')</a>
                </li>
            </ul>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter bg">

                <!-- LIST FILTER -->
                <div class="row padding_row filter-block">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select style="width: 100%;" name="staff_type"
                                        class="form-control m-input ss--select-2">
                                    <option value="">@lang('Loại nhân viên')</option>
                                    <option value="probationers">@lang('Thử việc')</option>
                                    <option value="staff">@lang('Chính thức')</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="branch_id" class="form-control m-input ss--select-2">
                            <option value="">@lang('Chọn chi nhánh')</option>
                            @if (isset($BRANCH_DATA))
                                @foreach ($BRANCH_DATA as $branchIdItem)
                                    <option value="{{ $branchIdItem['branch_id'] }}">
                                        {{ $branchIdItem['branch_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="department_id" class="form-control m-input ss--select-2">
                            <option value="">@lang('Chọn phòng ban')</option>
                            @if (isset($DEPARTMENT_DATA))
                                @foreach ($DEPARTMENT_DATA as $departmentIdItem)
                                    <option value="{{ $departmentIdItem['department_id'] }}">
                                        {{ $departmentIdItem['department_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="staff_title_id"
                                class="form-control m-input ss--select-2">
                            <option value="">@lang('Chọn chức vụ')</option>
                            @if (isset($TITLE_DATA))
                                @foreach ($TITLE_DATA as $staffTitleItem)
                                    <option value="{{ $staffTitleItem['staff_title_id'] }}">
                                        {{ $staffTitleItem['staff_title_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="full_name"
                                       placeholder="{{ __('Tên nhân viên') }}"/>
                            </div>
                        </div>
                    </div>

{{--                    <div class="col-lg-3">--}}
{{--                        <div class="m-input-icon m-input-icon--right">--}}
{{--                            <input readonly class="form-control m-input daterange-picker"--}}
{{--                                   style="background-color: #fff"--}}
{{--                                   id="commission_day"--}}
{{--                                   name="commission_day"--}}
{{--                                   autocomplete="off" placeholder="@lang('Ngày nhận hoa hồng')">--}}
{{--                            <span class="m-input-icon__icon m-input-icon__icon--right">--}}
{{--                                    <span><i class="la la-calendar"></i></span></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <button class="btn btn-primary color_button btn-search">{{ __('TÌM KIẾM') }} <i
                                        class="fa fa-search ic-search m--margin-left-5"></i></button>

                            <a href="{{ route('admin.commission.received')}}"
                               class="btn btn-primary color_button btn-search padding9x">
                                <span>
                                    <i class="flaticon-refresh"></i>
                                </span>
                            </a>
                        </div>
                    </div>

                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible"><strong>{{ __('Success') }} : </strong>
                        {!! session('status') !!}
                    </div>
                @endif
            </form>

            <div class="table-content m--padding-top-30">
                @include('commission::list-commission-staff')
            </div>
        </div>
    </div>

    <div id="my-modal"></div>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/son.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}"/>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/commission/received/script.js?v='.time())}}"></script>

    <script>
        listStaff._init();
    </script>
@stop
