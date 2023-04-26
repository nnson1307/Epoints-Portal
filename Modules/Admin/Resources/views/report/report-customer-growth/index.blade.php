@extends('layout')
@section('content')

    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

        .applyBtn
        {
            display: none;
        }

        .cancelBtn
        {
            display: none;
        }
    </style>
    <div class="m-portlet m-portlet--creative  m-portlet--first m-portlet--bordered-semi" id="autotable">

        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-label m-portlet__head-label--primary" style=" white-space:nowrap">
                        <span><i class="la la-user"></i> {{__('TĂNG TRƯỞNG KHÁCH HÀNG')}}</span>
                    </h2>
                    <h3 class="m-portlet__head-text">

                    </h3>
                </div>
            </div>

        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form--label-align-right m--margin-bottom-20">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-2">
                                <select name="year" id="year" class="form-control m_selectpicker m-btn--pill">
                                    <option></option>
                                    @for($i=0;$i<=3;$i++)
                                        @if($year==date('Y') - $i)
                                            <option value="{{date('Y') - $i}}" selected>{{date('Y') - $i}}</option>
                                        @else
                                            <option value="{{date('Y') - $i}}">{{date('Y') - $i}}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 filter-child">
                                <select name="branch_id" id="branch_id" class="form-control" style="width: 100%">
                                    <option></option>
                                    @foreach($optionBranch as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="container" class="m_blockui_1_content m-section__content"
                 style="min-width: 250px; height: 300px; margin: 0 auto;">

            </div>


        </div>


    </div>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/report-customer-growth/script.js')}}"
            type="text/javascript"></script>

@stop