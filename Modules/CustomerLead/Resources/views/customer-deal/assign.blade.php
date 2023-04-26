@extends('layout')
@section('title_header')
    <span class="title_header">@lang('PHÂN BỔ DEAL')</span>
@stop

@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('PHÂN BỔ DEAL')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
            <div class="m-portlet__body">
                <form id="form-assign">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Phòng ban'):<b class="text-danger">*</b>
                    </label>
                    <select class="form-control" name="department" id="department" style="width:100%" multiple>
                        <option value=""></option>
                        @if(isset($optionDepartment)  && count($optionDepartment) > 0)
                            @foreach($optionDepartment as $key => $value)
                                <option value="{{$value['department_id']}}">
                                    {{__($value['department_name'])}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chọn nhân viên'):<b class="text-danger">*</b>
                    </label>
                    <select class="form-control" name="staff" id="staff" style="width:100%" multiple>
                    </select>
                </div>
                <div class="input-group m-input-group m-input-group--solid m--margin-top-10">
                    <label class="m-checkbox m-checkbox--state-success">
                        <input type="checkbox" id="checkAllSale" onclick="assign.checkAllSale()">
                        {{__('Tất cả nhân viên')}}
                        <span></span>
                    </label>
                </div>
                </form>
                <div class="form-group m-form__group mt-3">
                    <label class="black_title">
                        @lang('Chọn danh sách deal'):<b class="text-danger">*</b>
                    </label>
                    <div class="input-group m-input-group m-input-group--solid m--margin-top-10">
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" id="checkAllLead" onclick="assign.checkAllLead()">
                            {{__('Tất cả deal')}}
                            <span></span>
                        </label>
                    </div>
                    <div id="autotable">
                        <div class="padding_row bg">
                            <form class="frmFilter">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group m-form__group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="search"
                                                       placeholder="{{__('Nhập tên deal, mã deal hoặc khách hàng')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <select class="form-control" style="width:100%;"
                                                    id="pipeline_code"
                                                    name="pipeline_code"
                                            >
                                                <option value="">@lang("Chọn pipeline")</option>
                                                @foreach($optionPipeline as $key => $value)
                                                    <option value="{{$value['pipeline_code']}}">{{$value['pipeline_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <select class="form-control" style="width:100%;" id="journey_code" name="journey_code">
                                                <option value="">@lang("Chọn hành trình")</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group m-form__group">
                                            <button class="btn btn-primary color_button btn-search">
                                                {{__('TÌM KIẾM')}} <i
                                                        class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-content m--margin-top-30">
                            @include('customer-lead::customer-deal.assign-list-lead')
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('customer-lead.customer-deal')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="assign.submit()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('PHÂN BỔ')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer-lead/customer-deal/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        assign._init();
    </script>
@endsection