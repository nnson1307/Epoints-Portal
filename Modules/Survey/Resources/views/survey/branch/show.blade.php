@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> {{ __('QUẢN LÝ KHẢO SÁT') }}</span>
@stop
@section('after_style')
    <style>
          .modal {
            overflow-y: auto;
        }

        .table-bordered-none th,
        .table-bordered-none td {
            border-top: 0;
            border-bottom: 0 !important;
        }

        .kt-radio.kt-radio--brand.kt-radio--bold>input:checked~span {
            border: 2px solid #000000 !important;
        }

        .kt-avatar.kt-avatar--circle .kt-avatar__holder {
            border-radius: 0% !important;
        }

        .ss--kt-avatar__upload {
            width: 20px !important;
            height: 20px !important;
        }

        .kt-checkbox.kt-checkbox--bold>input:checked~span {
            background: #027177;
            border: 2px solid #027177 !important;
            border-radius: 3px !important;
        }

        .kt-checkbox>span:after {
            border: solid #fff;
        }

        .kt-checkbox.kt-checkbox--bold>input:checked~span {
            background: #4FC4CA;
            border: 2px solid #4FC4CA !important;
            border-radius: 3px !important;
        }

        .kt-checkbox>span:after {
            border: solid #fff;
        }

        .kt-checkbox-fix {
            padding: 5px 15px;
        }

        .kt-checkbox-fix span {
            position: absolute;
            top: unset !important;
            bottom: -20px !important;
            left: 30px !important;
        }

        .m-portlet__body {
            color
        }


        .color_title {
            color: #008990 !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
        }

        .td-trash a {
            color: #575962;
            padding: 10px;
            transition: background-color 0.5s ease;
            border-radius: 50%;
        }

        .td-trash a:hover {
            border-color: #f4516c !important;
            color: #ffffff !important;
            background-color: #f4516c !important;
        }

        .list_customer-button {
            display: flex;
            flex-direction: column;
        }


        .m-radio>span:after {
            background: #4FC4CA !important;
            border: 1px solid #4FC4CA !important;
        }

        .m-radio>span {
            border: 1px solid #4FC4CA !important;
        }

        .title_tab {
            color: #000000;

        }

        .color_button_destroy {
            background-color: #FE4C4C !important;
            color: #fff;
            border-color: #FE4C4C !important;
        }

        .m-datatable>.m-datatable__pager>.m-datatable__pager-nav>li>.m-datatable__pager-link.m-datatable__pager-link--active {
            background: #4fc4ca !important;
            color: #ffffff;
        }

        .m-datatable>.m-datatable__pager>.m-datatable__pager-nav>li>.m-datatable__pager-link:hover {
            background: #4fc4ca !important;
            color: #ffffff;
        }

        .show-group_customer-selected {
            width: 250px;
        }

        .show-group_customer-selected input {
            padding: 8px;
            outline: none;
            border: 1px solid #787878;
            border-radius: 4px;
            width: 100%;
        }
        .input_condition_seleted {
            width: 100%;
            height: 100%;
        }
        #modal-survey .modal-body .title {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            color: #000000;
        }

        #modal-survey .modal-body .description {
            font-size: 14px;
            font-weight: 400;
            text-align: center;
            align-content: center;
            color: #000000;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #a7abc3 !important;
        }
        
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="m-portlet  m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text">
                        {{ __('CHI TIẾT ÁP DỤNG KHẢO SÁT') }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if ($detail['status'] == 'N')
                    <button type="button" onclick="survey.showModalDestroy()"
                        class="btn btn-primary color_button color_button_destroy  btn-search ml-2">
                        @lang('Xóa')
                    </button>
                    <button type="button" onclick="survey.showModalRefuse()" class="btn btn-secondary btn-search ml-2"
                        style="color:black; border:1px solid">
                        @lang('Từ chối')
                    </button>
                    <button type="button" onclick="survey.showModalConfirm()"
                        class="btn btn-primary color_button btn-search ml-2">
                        @lang('Duyệt')
                    </button>
                @elseif($detail['status'] == 'R')
                    @if ($detail['is_exec_time'] == 0 || \Carbon\Carbon::parse($detail['close_date'])->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d'))
                        <button type="button" onclick="survey.showModalEnd()"
                            class="btn btn-primary color_button color_button_destroy ml-2">
                            @lang('survey::survey.show.end')
                        </button>
                    @endif
                @endif
                <a href="{{ route('survey.index') }}" class="btn btn-secondary btn-search ml-2"
                    style="color:black; border:1px solid">
                    @lang('Quay lại trang trước')
                </a>
            </div>
        </div>
    </div>
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-padding-0" id="kt_content">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div class="row form-group">
                    <div class="col-xl-12 col-lg-12">
                        <div class="btn-group btn-group" role="group" aria-label="...">
                            <a href="{{ route('survey.show', [$id]) }}" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                @lang('Thông tin chung')
                            </a>
                            <a href="{{ route('survey.show-question', [$id]) }}" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                @lang('Câu hỏi khảo sát')
                            </a>
                            <button type="button"
                                class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">
                                @lang('Đối tượng áp dụng')
                            </button>
                            <a href="{{ route('survey.report', [$detail['survey_id']]) }}" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                @lang('Báo cáo')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-12">
                        @if($detail['status'] == 'N')
                            <a href="{{route('survey.edit-branch', [$id])}}" type="button" class="btn btn-primary color_button btn-search ">
                                @lang('Chỉnh sửa thông tin')
                            </a>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group row">
                        <div class="col-12 form-group">
                            <h3 class="title_tab">@lang('Loại đối tương áp dụng')</h3>
                        </div>
                        <div class="col-12">
                            <label class="m-radio cus  mb-4">
                                <input type="radio" 
                                {{$detail->type_apply == 'all_customer' ? 'checked' : ''}} 
                                name="type_apply" 
                                disabled
                                onclick="branch.toggleApplyUser(this)"
                                    class="object_apply" value="all_customer" onclick="">
                                @lang('Tất cả khách hàng')
                                <span></span>
                            </label>
                            <br>
                            <label class="m-radio cus  mb-4">
                                <input type="radio" 
                                name="type_apply"
                                disabled
                                onclick="branch.toggleApplyUser(this)"
                                {{$detail->type_apply == 'all_staff' ? 'checked' : ''}} 
                                class="object_apply" 
                                    value="all_staff" onclick="">
                                @lang('Tất cả nhân viên')
                                <span></span>
                            </label>
                            <br>
                            <label class="m-radio cus  mb-4">
                                <input type="radio" 
                                name="type_apply"
                                disabled
                                {{$detail->type_apply == 'customers' ? 'checked' : ''}} 
                                 onclick="branch.toggleApplyUser(this)"
                                    class="object_apply" 
                                    value="customers" onclick="">
                                @lang('Chỉ áp dụng cho khách hàng cụ thể')
                                <span></span>
                            </label>
                            <br>
                            <label class="m-radio cus  mb-4">
                                <input type="radio"
                                 name="type_apply" 
                                 disabled
                                 {{$detail->type_apply == 'staffs' ? 'checked' : ''}} 
                                onclick="branch.toggleApplyUser(this)"
                                    class="object_apply" value="staffs" onclick="">
                                @lang('Chỉ áp dụng cho nhân viên cụ thể')
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="list_customer-button" disabled style="display: none">
                            <div class="col-12 d-flex" style="gap:50px">
                                @php
                                $idCustomerGroupAuto = !empty($itemCustomerFilter) ? $itemCustomerFilter->id : '';
                                @endphp
                                <button disabled onclick="branch.renderModalCustomerAuto()" type="button"
                                    class="
                                btn btn-primary 
                                color_button btn-search 
                                btn-store">
                                    @lang('Thêm khách hàng động')
                                </button>
                                <div class="show-group_customer-selected">
                                    @if(!empty($itemCustomerFilter))
                                    <input type="text"  disabled value="" placeholder="{{ $itemCustomerFilter->name }}">
                                    <input type="text" id="itemGroupChecked" hidden value="{{$itemCustomerFilter->id}}">
                                    @else
                                    <input type="text" id="itemGroupChecked" hidden value="">
                                    @endif
                                    
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" disabled onclick="branch.renderModalCustomer()"
                                    class="
                                btn btn-primary 
                                color_button btn-search 
                                btn-store">
                                    @lang('Thêm khách hàng cụ thể')
                                </button>
                            </div>
                        </div>
                        <div class="list_staff-button row"  style="display: none">
                            <div class="col-12">
                                <button 
                                onclick="branch.showModalStaffAuto()"
                                type="button"
                                disabled
                                    class="
                                btn btn-primary 
                                color_button btn-search 
                                btn-store">
                                    @lang('Thêm nhân viên động')
                                </button>
                            </div>
                            <div class="col-12" id="staff_condition_seleted">
                                @include('survey::branch.list.staff-seleted-auto')
                            </div>
                            <div class="col-12">
                                <button disabled onclick="branch.renderModalStaff()" type="button"
                                    class="
                                btn btn-primary 
                                color_button btn-search 
                                btn-store">
                                    @lang('Thêm nhân viên cụ thể')
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="hr-space mt-5 mb-5 row">

                    </div>
                    <div class="list_apply">
                        <div class="list_customer-apply" style="display: none">
                            <div class="row list-search">
                                <div class="col-3 form-group">
                                    <input type="text" class="form-control" id="code_or_name_customer"
                                        name="code_or_name_customer" placeholder="@lang('Nhập tên hoặc mã khách hàng')">
                                </div>
                                <div class="col-3 form-group">
                                    <select id="customer_type" name="customer_type" class="form-control ss--width-100 ss-select2"
                                        style="width: 100%">
                                        <option value=""  selected>@lang('Loại khách hàng')</option>
                                        <option value="personal">@lang('Cá nhân')</option>
                                        <option value="business">@lang('Doanh nghiệp')</option>
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <select class="form-control ss--width-100 ss-select2" id="customer_group" name="customer_group">
                                        <option value="">@lang('Nhóm khách hàng')</option>
                                        @if (!empty($optionCustomer['customerGroup']))
                                            @foreach ($optionCustomer['customerGroup'] as $key => $value)
                                                <option value="{{ $key }}">
                                                    @lang($value)
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <select class="form-control ss--width-100 ss-select2" id="customer_source" name="customer_source">
                                        <option value="">@lang('Nguồn khách hàng')</option>
                                        <option value="">@lang('Nhóm khách hàng')</option>
                                        @if (!empty($optionCustomer['customerSource']))
                                            @foreach ($optionCustomer['customerSource'] as $key => $value)
                                                <option value="{{ $key }}">
                                                    @lang($value)
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <select type="text" name="province_main" id="province_id"
                                        class="form-control ss--width-100 ss-select2" style="width: 100%">
                                        <option value=" ">{{ __('Chọn tỉnh/thành') }}</option>
                                        @foreach ($optionProvince as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <select type="text" name="district" id="district_id"
                                        class="form-control ss--width-100 ss-select2 district" style="width: 100%">
                                        <option value=" ">{{ __('Chọn quận/huyện') }}</option>
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <select type="text" name="ward_main" id="ward_id"
                                        class="form-control ss--width-100 ss-select2" style="width: 100%">
                                        <option value=" ">{{ __('Chọn phường/xã') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3">
                                    <button type="button" onclick="branch.resetSearchItemSelectedCustomer()"
                                        class="btn btn-primary color_button color_button_destroy kt-margin-r-5 btn-search"
                                        style="float: left">@lang('Xóa bộ lọc')</button>
                                    <button type="button" onclick="branch.loadItemSelectedCustomer()"
                                    class="btn btn-primary color_button btn-search  btn-list-store"
                                    style="float: left">@lang('Tìm kiếm')</button>
                                </div>
                            </div>
                            <div class="row form-group kt-margin-t-20">
                                <div class="col-lg-12 table_customer_selected">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    @lang('#')
                                                </th>
                                                <th>
                                                    @lang('Mã khách hàng')
                                                </th>
                                                <th>
                                                    @lang('Tên khách hàng')
                                                </th>
                                                <th>
                                                    @lang('Số điện thoại')
                                                </th>
                                                <th>
                                                    @lang('Địa chỉ')
                                                </th>
                                                <th>
                                                    @lang('Loại khách hàng')
                                                </th>
                                                <th>
                                                    @lang('Nhóm khách hàng')
                                                </th>
                                                <th>
                                                    @lang('Trạng thái')
                                                </th>
                                                <th class="text-center">
                                                    @lang('Hành động')
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="list_staff-apply" style="display: none">
                            <div class="row list-search">
                                <div class="col-2 form-group">
                                    <input type="text" class="form-control" id="name_or_code_staff"
                                        name="name_or_code_staff" placeholder="@lang('Nhập tên hoặc mã nhân viên')">
                                </div>
                                <div class="col-2 form-group">
                                    <select type="text" name="staff_branch" id="staff_branch"
                                        class="form-control ss--width-100 ss-select2" style="width: 100%">
                                        <option value="">@lang('Chi nhánh')</option>
                                        @foreach ($branch as $item)
                                            <option value="{{ $item->branch_id }}">{{ $item->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <select type="text" name="staff_department" id="staff_department"
                                        class="form-control ss--width-100 ss-select2" style="width: 100%">
                                        <option value="">@lang('Phòng ban')</option>
                                        @foreach ($department as $item)
                                            <option value="{{ $item->department_id }}">{{ $item->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <select type="text" name="staff_position" id="staff_position"
                                        class="form-control ss--width-100 ss-select2" style="width: 100%">
                                        <option value="">@lang('Chức vụ')</option>
                                        @foreach ($staffTitle as $item)
                                            <option value="{{ $item->staff_title_id }}">{{ $item->staff_title_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 form-group">
                                    <input type="text" class="form-control" id="address_staff"
                                        name="address_staff" placeholder="{{__('Nhập tên địa chỉ')}}">
                                </div>
                                <div class="form-group col-lg-2">
                                    <button type="button" onclick="branch.loadItemSelectedStaff()"
                                        class="btn btn-primary color_button btn-search kt-margin-l-5 btn-list-store"
                                        style="float: right">@lang('Tìm kiếm')</button>
                                    <button type="button" onclick="branch.resetSearchItemSelectedStaff()"
                                        class="btn btn-primary color_button color_button_destroy  btn-search"
                                        style="float: right">@lang('Xóa bộ lọc')</button>
                                </div>
                            </div>
                            <div class="row form-group kt-margin-t-20">
                                <div class="col-lg-12 table_staff_selected">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>
                                                    @lang('Mã nhân viên')
                                                </th>
                                                <th>
                                                    @lang('Tên nhân viên')
                                                </th>
                                                <th>
                                                    @lang('Số điện thoại')
                                                </th>
                                                <th>
                                                    @lang('Địa chỉ')
                                                </th>
                                                <th>
                                                    @lang('Chi nhánh')
                                                </th>
                                                <th>
                                                    @lang('Phòng ban')
                                                </th>
                                                <th>
                                                    @lang('Chức vụ')
                                                </th>
                                                <th>
                                                    @lang('Trạng thái')
                                                </th>
                                                <th class="text-center">
                                                    @lang('Hành động')
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="div_modal">
    </div>
    @include('survey::survey.modal.destroy-survey')
    @include('survey::survey.modal.confirm-survey')
    @include('survey::survey.modal.refuse-survey')
    @include('survey::survey.modal.end-survey')
@endsection
@section('after_script')
    <script>
        const UNIQUE = "{{ $unique }}";
        const IS_SHOW = 1;
        const TYPE_SHOW_CUSTOMER_GROUP = ''
    </script>
    <script src="{{ asset('static/backend/js/bootstrap-datepicker.vi.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script src="{{ asset('static/backend/js/jquery.mask.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/survey/branch/apply/script.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script src="{{ asset('static/backend/js/survey/branch/script.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/survey/edit.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $(document).ajaxStart(function() {
            $('.select_action').prop('disabled', true);
        });
        $(document).ajaxStop(function() {
            $('.select_action').prop('disabled', true);
        });
    </script>
     <script type="text/template" id="choose-condition">
        <div class="form-group row A-condition-1 div-A-1-condition">
            <div class="col-lg-4" style="padding-left: 0px">
                <select name="" id="" onchange="branch.chooseCondition(this)"
                        class="form-control ss--select-2 condition" style="width: 100%">
                    <option value="">
                        {{__('Chọn điều kiện')}}
                    </option>
                    {option}
                </select>
            </div>
            <div class="col-lg-6 div-content-condition">

            </div>
            <div class="col-lg-2">
                <button style="float: right;" type="button" onclick="branch.removeCondition(this)"
                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                    <i class="la la-close"></i>
                </button>

            </div>
        </div>
    </script>
     <script type="text/template" id="tpl-branch-define">
            <select name="branch[]" id="condition_branch"
            multiple="multiple"
                    class="form-control  chooses-condition" style="width: 100%">
                <option value="">
                    {{__('Chọn chi nhánh')}}
                </option>
                @foreach($branch as $item)
                    <option value="{{$item['branch_id']}}">
                        {{$item['branch_name']}}
                    </option>
                @endforeach
            </select>
    </script>
    <script type="text/template" id="tpl-department-define">
            <select name="department[]" id="condition_department"
            multiple="multiple"
                    class=" form-control  chooses-condition" style="width: 100%">
                <option value="">
                    {{__('Chọn phòng ban')}}
                </option>
                @foreach($department as $item)
                    <option value="{{$item['department_id']}}">
                        {{$item['department_name']}}
                    </option>
                @endforeach
            </select>
    </script>
    <script type="text/template" id="tpl-title-define">
            <select name="title[]" id="condition_titile"
            multiple="multiple"
                    class=" form-control chooses-condition" style="width: 100%">
                <option value="">
                    {{__('Chọn chức vụ')}}
                </option>
                @foreach($staffTitle as $item)
                    <option value="{{$item['staff_title_id']}}">
                        {{$item['staff_title_name']}}
                    </option>
                @endforeach
            </select>
    </script>
@endsection
