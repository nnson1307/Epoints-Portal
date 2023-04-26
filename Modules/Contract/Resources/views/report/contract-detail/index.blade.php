@extends('layout')
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO HỢP ĐỒNG')}}</span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('BÁO CÁO CHI TIẾT HỢP ĐỒNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('contract.report.contract-detail.export', session()->get('routeList')))
                    <button onclick="contractDetail.export()"
                            class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc mr-2">
                        <span>
                            <i class="la la-files-o"></i>
                            <span> {{ __('XUẤT DỮ LIỆU') }}</span>
                        </span>
                    </button>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group row">
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text"
                                       class="form-control m-input daterange-picker" id="created_at"
                                       name="created_at"
                                       autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control select m-input" id="contract_category_id" name="contract_category_id" style="width:100%;">
                                <option value="">@lang('Chọn loại hợp đồng')</option>
                                @foreach($optionCategory as $item)
                                    <option value="{{$item['contract_category_id']}}">{{$item['contract_category_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control select m-input" id="status_code" name="status_code" style="width:100%;">
                                <option value="">@lang('Chọn trạng thái')</option>
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control select m-input" id="partner_object" name="partner_object" style="width:100%;">
                                <option value="">@lang('Chọn đối tác')</option>
                                @foreach($optionPartner as $item)
                                    <option value="{{$item['key']}}">{{$item['value']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text"
                                       class="form-control m-input daterange-picker" id="expired_date"
                                       name="expired_date"
                                       autocomplete="off" placeholder="{{__('Chọn ngày hết hiệu lực')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text"
                                       class="form-control m-input daterange-picker" id="warranty_end_date"
                                       name="warranty_end_date"
                                       autocomplete="off" placeholder="{{__('Chọn ngày kết thúc bảo hành')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn-primary btn-search color_button">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-content m--padding-top-15">
{{--                @include('contract::report.contract-detail.list')--}}
            </div><!-- end table-content -->
        </div>

    </div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/contract/report/contract-detail/script.js')}}"
            type="text/javascript"></script>
    <script>
        $(".m_selectpicker").selectpicker();
    </script>
@stop
