<?php
//    if(Cookie::get('arrColumn1') != null && Cookie::get('arrColumn2')){
//        $arrColumn = json_decode(Cookie::get('arrColumn1') . Cookie::get('arrColumn2'));
//    }
?>

@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ HỢP ĐỒNG')</span>
@stop
    <style>
        .border-custom {
            border: solid;
        }
    </style>
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("CẤU HÌNH DANH SÁCH HIỂN THỊ")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--@if(in_array('contract.contract.config',session('routeList')))--}}
                <a href="{{route('contract.contract')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                    <span>
                        <i class="la la-arrow-left"></i>
                        <span>@lang('HỦY')</span>
                    </span>
                </a>
                {{--@endif--}}
                {{--@if(in_array('contract.contract.create',session('routeList')))--}}
                    <button onclick="saveConfig()"
                       class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <span> @lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                {{--@endif--}}
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row m--font-bold align-conter1">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                        <div class="form-group m-form__group ss--margin-bottom-0">
                            <label class="ml-3 m--margin-top-20 ss--text-center ss--font-weight-400">
                                {{__('BỘ TÌM KIẾM')}}
                            </label>
                        </div>
                        <div class="form-group m-form__group row m--font-bold align-conter1"
                             style="min-width: 290px; height: auto; margin: 0 auto">
                            <div class="col-lg-5 m-portlet--bordered-semi m-portlet" id="append_search_default">
                                <div class="form-group">
                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                        <input class="check-all-search-default" onclick="checkAllSearchDefault(this)" type="checkbox">
                                        <span></span>
                                    </label>
                                    <label><h4>{{__('Các trường có sẵn')}}</h4></label><br/><label>{{__('đã chọn')}}</label>
                                    <hr>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['contract_no']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="contract_no"  type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Số hợp đồng')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['content']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="content" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Nội dung')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['customer_name']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="customer_name" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Tên khách hàng')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['supplier_name']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="supplier_name" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Tên nhà cung cấp')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['tax_code']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="tax_code" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Mã số thuế')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['address']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="address" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Địa chỉ')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['phone']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="phone" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Số điện thoại')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['email']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="email" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Email')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrSearch')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrSearch')), 'key'))['goods']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-search-default" name="goods" type="checkbox"  onclick="checkSearchDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_search">{{__('Hàng hoá')}}</label>
                                </div>
                            </div>
                            <div class="col-lg-2" style="   margin-top: 10em;">
                                <button class="btn btn-outline-secondary btn-lg" onclick="addSearch();">
                                    <span><i class="fas fa-chevron-right"></i></span>
                                </button>
                                <button class="btn btn-outline-secondary btn-lg" onclick="removeSearch()">
                                    <span><i class="fas fa-chevron-left"></i></span>
                                </button>
                            </div>
                            <div class="col-lg-5 m-portlet--bordered-semi m-portlet" id="append_search">
                                <div class="form-group">
                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                        <input class="check-all-search" onclick="checkAllSearch(this)" type="checkbox">
                                        <span></span>
                                    </label>
                                    <label><h4>{{__('Tìm kiếm theo')}}</h4></label><br>
                                    <label>{{__('đã chọn')}}</label>
                                    <hr>
                                </div>
                                @if(json_decode(Cookie::get('arrSearch')) != null)
                                    @foreach(json_decode(Cookie::get('arrSearch')) as $key => $value)
                                        <?php $value = (array)$value ?>
                                        <div class="form-group">
                                            <label class="m-checkbox m-checkbox--air">
                                                <input class="check-search" name="{{$value['key']}}" type="checkbox"  onclick="checkSearch(this)">
                                                <span></span>
                                            </label>
                                            <label class="label_search">{{$value['value']}}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                        <div class="form-group m-form__group ss--margin-bottom-0">
                            <label class="ml-3 m--margin-top-20 ss--text-center ss--font-weight-400">
                                {{__('BỘ LỌC')}}
                            </label>
                        </div>
                        <div class="form-group m-form__group row m--font-bold align-conter1"
                             style="min-width: 290px; height: auto; margin: 0 auto">
                            <div class="col-lg-5 m-portlet--bordered-semi m-portlet" id="append_filter_default">
                                <div class="form-group">
                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                        <input class="check-all-filter-default" onclick="checkAllFilterDefault(this)" type="checkbox">
                                        <span></span>
                                    </label>
                                    <label><h4>{{__('Các trường có sẵn')}}</h4></label><br/><label>{{__('đã chọn')}}</label>
                                    <hr>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['customer_group_id']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="customer_group_id" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Loại khách hàng')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['expired_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="expired_date" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Ngày hết hiệu lực')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['effective_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="effective_date" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Ngày có hiệu lực')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['sign_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="sign_date" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Ngày ký hợp đồng')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['performer_by']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="performer_by" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Người thực hiện')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['staff_title_id']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="staff_title_id" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Chức vụ')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['department_id']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="department_id" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Phòng ban')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['contract_tag_id']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="contract_tag_id" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Tags')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['status_code']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="status_code" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Trạng thái')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['tax']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="tax" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('VAT')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['total_amount']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="total_amount" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Giá trị hợp đồng')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['payment_method_id']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="payment_method_id" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Phương thức thanh toán')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['warranty_start_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="warranty_start_date" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Ngày bắt đầu bảo hành')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrFilter')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrFilter')), 'key'))['warranty_end_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-filter-default" name="warranty_end_date" type="checkbox"  onclick="checkFilterDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_filter">{{__('Ngày kết thúc bảo hành')}}</label>
                                </div>
                            </div>
                            <div class="col-lg-2" style="margin-top: 10em;">
                                <button class="btn btn-outline-secondary btn-lg" onclick="addFilter();">
                                    <span><i class="fas fa-chevron-right"></i></span>
                                </button>
                                <button class="btn btn-outline-secondary btn-lg" onclick="removeFilter();">
                                    <span><i class="fas fa-chevron-left"></i></span>
                                </button>
                            </div>
                            <div class="col-lg-5 m-portlet--bordered-semi m-portlet" id="append_filter">
                                <div class="form-group">
                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                        <input class="check-all-filter" onclick="checkAllFilter(this)" type="checkbox">
                                        <span></span>
                                    </label>
                                    <label><h4>{{__('Lọc theo')}}</h4></label><br>
                                    <label>{{__('đã chọn')}}</label>
                                    <hr>
                                </div>
                                @if(json_decode(Cookie::get('arrFilter')) != null)
                                    @foreach(json_decode(Cookie::get('arrFilter')) as $key => $value)
                                        <?php $value = (array)$value ?>
                                            <div class="form-group">
                                                <label class="m-checkbox m-checkbox--air">
                                                    <input class="check-filter" name="{{$value['key']}}" type="checkbox"  onclick="checkFilter(this)">
                                                    <span></span>
                                                </label>
                                                <label class="label_filter">{{$value['value']}}</label>
                                            </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                        <div class="form-group m-form__group ss--margin-bottom-0">
                            <label class="ml-3 m--margin-top-20 ss--text-center ss--font-weight-400">
                                {{__('CỘT CẦN HIỂN THỊ')}}
                            </label>
                        </div>
                        <div class="form-group m-form__group row m--font-bold align-conter1"
                             style="min-width: 290px; height: auto; margin: 0 auto">
                            <div class="col-lg-5 m-portlet--bordered-semi m-portlet" id="append_column_default">
                                <div class="form-group">
                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                        <input class="check-all-column-default" onclick="checkAllColumnDefault(this)" type="checkbox">
                                        <span></span>
                                    </label>
                                    <label><h4>{{__('Các trường có sẵn')}}</h4></label><br/><label>{{__('đã chọn')}}</label>
                                    <hr>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['stt']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="stt" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('#')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['contract_no']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="contract_no" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Số hợp đồng')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['content']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="content" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Nội dung')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['partner_name']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="partner_name" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Tên đối tác')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['customer_group_id']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="customer_group_id" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Loại khách hàng')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['address']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="address" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Địa chỉ')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['representative']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="representative" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Người đại diện')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['hotline']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="hotline" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Hotline')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['staff_title']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="staff_title" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Chức vụ')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['is_renew']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="is_renew" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Tự động gia hạn')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['phone']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="phone" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Số điện thoại')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['email']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="email" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Email')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['goods']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="goods" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Hàng hoá')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['contract_category_id']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="contract_category_id" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Loại hợp đồng')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['effective_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="effective_date" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Ngày có hiệu lực')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['expired_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="expired_date" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Ngày hết hiệu lực')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['sign_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="sign_date" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Ngày ký hợp đồng')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['status_code']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="status_code" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Trạng thái')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['total_amount']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="total_amount" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Giá trị hợp đồng chưa VAT')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['tax']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="tax" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('VAT')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['discount']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="discount" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Giảm giá')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['last_total_amount']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="last_total_amount" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Giá trị sau giảm giá (có VAT)')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['performer_by']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="performer_by" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Người thực hiện')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['department']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="department" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Phòng ban')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['created_by']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="created_by" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Người tạo')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['updated_by']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="updated_by" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Người cập nhật')}}</label>
                                </div>

                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['warranty_start_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="warranty_start_date" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Ngày bắt đầu bảo hành')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['warranty_end_date']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="warranty_end_date" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Ngày kết thúc bảo hành')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['reason']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="reason" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Lý do')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['contract_file']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="contract_file" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Hồ sơ đính kèm')}}</label>
                                </div>
                                <div class="form-group" {{json_decode(Cookie::get('arrColumn')) != null && isset(array_count_values(array_column(json_decode(Cookie::get('arrColumn')), 'key'))['note']) == 1 ? 'hidden' : ''}}>
                                    <label class="m-checkbox m-checkbox--air">
                                        <input class="check-column-default" name="note" type="checkbox"  onclick="checkColumnDefault(this)">
                                        <span></span>
                                    </label>
                                    <label class="label_column">{{__('Ghi chú')}}</label>
                                </div>
                            </div>
                            <div class="col-lg-2" style="margin-top: 16em;">
                                <button class="btn btn-outline-secondary btn-lg" onclick="addColumn();">
                                    <span><i class="fas fa-chevron-right"></i></span>
                                </button>
                                <button class="btn btn-outline-secondary btn-lg" onclick="removeColumn();">
                                    <span><i class="fas fa-chevron-left"></i></span>
                                </button>
                            </div>
                            <div class="col-lg-5 m-portlet--bordered-semi m-portlet" id="append_column">
                                <div class="form-group">
                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                        <input class="check-all-column" onclick="checkAllColumn(this)" type="checkbox">
                                        <span></span>
                                    </label>
                                    <label><h4>{{__('Các cột hiển thị')}}</h4></label><br>
                                    <label>{{__('đã chọn')}}</label>
                                    <hr>
                                </div>
                                @if(json_decode(Cookie::get('arrColumn')) != null)
                                    @foreach(json_decode(Cookie::get('arrColumn')) as $key => $value)
                                        <?php $value = (array)$value ?>
                                        <div class="form-group">
                                            <label class="m-checkbox m-checkbox--air">
                                                <input class="check-column" name="{{$value['key']}}" type="checkbox"  onclick="checkColumn(this)">
                                                <span></span>
                                            </label>
                                            <label class="label_column">{{$value['value']}}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@stop
@section('after_script')
    {{--<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>--}}
    {{--<script>--}}
        {{--var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};--}}
    {{--</script>--}}
    <script src="{{asset('static/backend/js/contract/contract/config.js')}}" type="text/javascript"></script>
    <script type="text/javascript">

    </script>
@stop
