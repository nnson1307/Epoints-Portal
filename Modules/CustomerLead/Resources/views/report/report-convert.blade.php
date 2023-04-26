@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" style="height: 20px;">
        {{__('BÁO CÁO CHUYỂN ĐỔI')}}
    </span>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                {{__('BÁO CÁO PHỄU CHUYỂN ĐỔI')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="m-portlet__head-tools">
                            <form action="{{route('customer-lead.report.export-excel-view-report-convert')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="time_export" name="time">
                                <input type="hidden" id="pipeline_code_export" name="pipeline_code">
                                <input type="hidden" id="customer_source_id_export" name="customer_source_id">

                                <button type="submit"
                                        class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                    <span>
                                        <i class="la la-files-o"></i>
                                        <span>{{__('Export báo cáo')}}</span>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">

                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="pipeline" style="width: 100%" id="pipeline"
                                        class="form-control" onchange="">
{{--                                    <option value="">{{__('Chọn pipeline')}}</option>--}}
                                    @if(isset($optionPipeline) && count($optionPipeline) > 0)
                                        @foreach($optionPipeline as $item)
                                            <option value="{{$item['pipeline_code']}}">
                                                {{$item['pipeline_name']}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="customer_source_id" style="width: 100%" id="customer_source_id"
                                        class="form-control" onchange="">
                                    @if(isset($optionCs) && count($optionCs) > 0)
                                        <option value="">{{__("Chọn nguồn lead")}}</option>
                                        @foreach($optionCs as $item)
                                            <option value="{{$item['customer_source_id']}}">
                                                {{$item['customer_source_name']}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <button type="button" class="btn btn-info" onclick="convert.renderTableReport()">
                                    <i class="la la-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="container">
                            <div id="table-report">
                                @include('customer-lead::report.table-report-convert');
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>

    <div id="modal-lead-report-convert">

    </div>
    <input type="hidden" id="flag" value="0">
    <div id="value-12-month-controller">

    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection

@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer-lead/report/script.js')}}"
            type="text/javascript"></script>
    <script>
        $('#pipeline').select2();
        $('#customer_source_id').select2();

        convert.renderTableReport();
    </script>
@stop
