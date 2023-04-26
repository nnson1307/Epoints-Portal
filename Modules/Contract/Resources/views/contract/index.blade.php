@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ HỢP ĐỒNG')</span>
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
                        @lang("DANH SÁCH HỢP ĐỒNG")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button onclick="listContract.export()"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc mr-2">
                    <span>
                        <i class="la la-files-o"></i>
                        <span> {{ __('XUẤT DỮ LIỆU') }}</span>
                    </span>
                </button>
                @if(in_array('contract.contract.config',session('routeList')))
                <a href="{{route('contract.contract.config')}}"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc mr-2">
                    <span>
                        <i class="fas fa-cog"></i>
                        <span> @lang('CẤU HÌNH')</span>
                    </span>
                </a>
                @endif

                @if(in_array('contract.contract.create',session('routeList')))
                    <a href="{{route('contract.contract.create')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc mr-2">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM HỢP ĐỒNG')</span>
                                    </span>
                    </a>
                @endif

                @if(in_array('contract.contract.import-excel', session()->get('routeList')))
                <a href="javascript:void(0)" onclick="listContract.showModalImport()"
                   class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('Nhập file')}}
                                            </span>
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
                                           placeholder="@lang("Nhập thông tin cần tìm")">
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select class="form-control select m-input" id="contract_category_id" name="contract_category_id" style="width:100%;">
                                        <option value="">@lang('Chọn loại hợp đồng')</option>
                                        @foreach($optionCategory as $item)
                                            <option value="{{$item['contract_category_id']}}">{{$item['contract_category_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['status_code']) == 0 ? 'hidden' : ''}}>
                                    <select class="form-control select m-input" id="status_code" name="status_code" style="width:100%;">
                                        <option value="">@lang('Chọn trạng thái')</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['customer_group_id']) == 0 ? 'hidden' : ''}}>
                                    <select class="form-control select m-input" id="customer_group_id" name="customer_group_id" style="width:100%;">
                                        <option value="">@lang('Chọn loại khách hàng')</option>
                                        @foreach($optionCustomerGroup as $item)
                                            <option value="{{$item['customer_group_id']}}">{{$item['group_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['expired_date']) == 0 ? 'hidden' : ''}}>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text"
                                               class="form-control m-input daterange-picker" id="expired_date"
                                               name="expired_date"
                                               autocomplete="off" placeholder="{{__('Chọn ngày hết hiệu lực')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['effective_date']) == 0 ? 'hidden' : ''}}>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text"
                                               class="form-control m-input daterange-picker" id="effective_date"
                                               name="effective_date"
                                               autocomplete="off" placeholder="{{__('Chọn ngày có hiệu lực')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['sign_date']) == 0 ? 'hidden' : ''}}>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text"
                                               class="form-control m-input daterange-picker" id="sign_date"
                                               name="sign_date"
                                               autocomplete="off" placeholder="{{__('Chọn ngày ký hợp đồng')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['performer_by']) == 0 ? 'hidden' : ''}}>
                                    <select class="form-control select m-input" id="staff_id" name="staff_id" style="width:100%;">
                                        <option value="">@lang('Chọn người thực hiện')</option>
                                        @foreach($optionStaff as $item)
                                            <option value="{{$item['staff_id']}}">{{$item['staff_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['staff_title_id']) == 0 ? 'hidden' : ''}}>
                                    <select class="form-control select m-input" id="staff_title_id" name="staff_title_id" style="width:100%;">
                                        <option value="">@lang('Chọn chức vụ')</option>
                                        @foreach($optionStaffTitle as $item)
                                            <option value="{{$item['staff_title_id']}}">{{$item['staff_title_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['department_id']) == 0 ? 'hidden' : ''}}>
                                    <select class="form-control select m-input" id="department_id" name="department_id" style="width:100%;">
                                        <option value="">@lang('Chọn phòng ban')</option>
                                        @foreach($optionDepartment as $item)
                                            <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['contract_tag_id']) == 0 ? 'hidden' : ''}}>
                                    <select class="form-control select m-input" id="contract_tag_id" name="contract_tag_id" style="width:100%;">
                                        <option value="">@lang('Chọn tags')</option>
                                        @foreach($optionTag as $item)
                                            <option value="{{$item['contract_tag_id']}}">{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['tax']) == 0 ? 'hidden' : ''}}>
                                    <input type="text" class="form-control m-input" name="tax" id="tax"
                                           placeholder="@lang("Nhập giá trị VAT")">
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['total_amount']) == 0 ? 'hidden' : ''}}>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="m-input-icon m-input-icon--right">
                                                <select class="form-control select" style="width:100px !important;"
                                                        id="compare_total_amount"
                                                        name="compare_total_amount">
                                                    <option value="">@lang('So sánh')</option>
                                                    <option value=">">@lang('Lớn hơn')</option>
                                                    <option value="<">@lang('Bé hơn')</option>
                                                    <option value="=">@lang('Bằng')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="total_amount" id="total_amount"
                                               placeholder="@lang("Nhập giá trị hợp đồng")">
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['payment_method_id']) == 0 ? 'hidden' : ''}}>
                                    <select class="form-control select m-input" id="payment_method_id" name="payment_method_id" style="width:100%;">
                                        <option value="">@lang('Chọn hình thức thanh toán')</option>
                                        @foreach($optionPaymentMethod as $item)
                                            <option value="{{$item['payment_method_id']}}">{{$item['payment_method_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['warranty_start_date']) == 0 ? 'hidden' : ''}}>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text"
                                               class="form-control m-input daterange-picker" id="warranty_start_date"
                                               name="warranty_start_date"
                                               autocomplete="off" placeholder="{{__('Chọn ngày bắt đầu bảo hành')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group" {{json_decode(Cookie::get('arrFilter')) == null || isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['warranty_end_date']) == 0 ? 'hidden' : ''}}>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text"
                                               class="form-control m-input daterange-picker" id="warranty_end_date"
                                               name="warranty_end_date"
                                               autocomplete="off" placeholder="{{__('Chọn ngày kết thúc bảo hành')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-15">
                    @include('contract::contract.list')
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
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/contract/contract/list.js?v='.time())}}" type="text/javascript"></script>
    <script type="text-template" id="tpl-data-error">
        <input type="hidden" name="contract_no[]" value="{contract_no}">
        <input type="hidden" name="contract_name[]" value="{contract_name}">
        <input type="hidden" name="contract_category_name[]" value="{contract_category_name}">
        <input type="hidden" name="partner_type[]" value="{partner_type}">
        <input type="hidden" name="partner_name[]" value="{partner_name}">
        <input type="hidden" name="partner_phone[]" value="{partner_phone}">
        <input type="hidden" name="sign_date[]" value="{sign_date}">
        <input type="hidden" name="effective_date[]" value="{effective_date}">
        <input type="hidden" name="expired_date[]" value="{expired_date}">
        <input type="hidden" name="performer_by[]" value="{performer_by}">
        <input type="hidden" name="sign_by[]" value="{sign_by}">
        <input type="hidden" name="follow_by[]" value="{follow_by}">
        <input type="hidden" name="warranty_start_date[]" value="{warranty_start_date}">
        <input type="hidden" name="warranty_end_date[]" value="{warranty_end_date}">
        <input type="hidden" name="error[]" value="{error}">
    </script>
@stop
