@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ HỢP ĐỒNG')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="fas fa-cog"></i> {{__('CHĂM SÓC HỢP ĐỒNG HẾT HẠN')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="expireContract.perform('expire')"
                    class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THỰC HIỆN')}}</span>
                        </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable-expire">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group row">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{__('Nhập tên hợp đồng hoặc tên khách hàng')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text"
                                       class="form-control m-input daterange-picker" id="expire_date"
                                       name="expire_date"
                                       autocomplete="off" placeholder="{{__('Chọn ngày hết hiệu lực')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control m-input select-select-2" name="partner_object_type">
                                <option value="" selected="selected">{{__('Chọn loại khách hàng')}}</option>
                                <option value="personal">@lang('Cá nhân')</option>
                                <option value="business">@lang('Doanh nghiệp')</option>
                                <option value="supplier">@lang('Nhà cung cấp')</option>
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control m-input select-select-2" name="performer_by">
                                <option value="" selected="selected">{{__('Chọn nhân viên phụ trách')}}</option>
                                @foreach($optionStaff as $item)
                                    <option value="{{$item['staff_id']}}">{{$item['staff_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control m-input select-select-2" name="contract_category_id" id="contract_category_id">
                                <option value="" selected="selected">{{__('Chọn loại hợp đồng')}}</option>
                                @foreach($optionCategory as $item)
                                    <option value="{{$item['contract_category_id']}}">{{$item['contract_category_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control m-input select-select-2" name="status_code" id="status_code">
                                <option value="" selected="selected">{{__('Chọn trạng thái')}}</option>
                            </select>
                        </div>
                        <div class="col-lg-2 form-group">
                            <div class="input-group">
                                <button class="btn btn-primary btn-search color_button">
                                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-content m--padding-top-15">
                @include('contract::contract-care.list-expire')
            </div><!-- end table-content -->
    </div>
<div id="my-modal"></div>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        $('.select2').select2();
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/contract-care/script.js')}}" type="text/javascript"></script>
@stop
