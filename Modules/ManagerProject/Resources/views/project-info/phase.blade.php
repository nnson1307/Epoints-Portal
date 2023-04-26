@extends('layout')
@section("after_style")
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('THÔNG TIN DỰ ÁN')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            /*background-color: #4fc4cb;*/
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }

        .m-portlet .m-portlet__body {
            padding: 1.2rem 2.2rem;
            background-color: white;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both
        }

        .m-portlet {
            margin-bottom: 0.2rem;
        }

        .column-pie-chart {
            width: 100%;
            font-weight: bold;
        }

        .chart-name {
            font-size: 20px;
            font-weight: bold;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 660px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 100%;
            border-radius: 5px;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        img {
            border-radius: 5px 5px 0 0;
        }

        .container {
            padding: 2px 16px;
        }

        table, th, td {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        .statistical td {
            border: none;
            /*display:flex*/
        }

        .card-title {
            padding: 10px 20px;
            margin: 0;
        }

        .card-status {
            font-size: 15px;
            color: #5CACEE;
            border: 1px solid #CAE1FF;
            border-radius: 4px;
            background: #CAE1FF;
            margin: 5px;
            padding: 5px 10px !important;
            margin-top: -5px;
        }


        .hight-risk {
            border: 1px solid #FFF0F5;
            background: #FFF0F5;
            border-radius: 5px;
            color: #A0522D;
            font-weight: 600;
        }

        .fs-15 {
            font-size: 15px;
        }

        .style-icon-statistical {
            font-size: 2rem;
            padding: 7px
        }

        .issue {
            border: 1px solid;
            border-radius: 10px;
            padding: 10px;
        }

        .display-flex {
            display: flex;
        }

        .inline-block {
            display: inline-block;
        }

        .edit-name {
            border: none;
            background-color: white;
            color: #66CCFF;
        }

        .edit-name:hover {
            border: none;
            background-color: #66CCFF;
            color: white;
            border-radius: 5px;
            transition: 1s;
            cursor: pointer
        }

        .fa-trash-alt {
            font-weight: 900;
            color: red;
            border: 1px solid white;
            width: 30px;
            height: 30px;
            padding: 7px;
            border-radius: 50%;
            background-color: white;
        }

        .fa-trash-alt:hover {
            cursor: pointer;
            background-color: red;
            color: white;
            transition: 0.5s
        }

        .card-status-important{
            font-size: 15px;
            color: #FFCC00;
            border: 1px solid #FAFAD2;
            border-radius: 4px;
            background: #FAFAD2;
            margin: 5px;
            margin-top: -5px !important;


        }
        .card-status-red{
            font-size: 15px;
            color: red;
            border: 1px solid #EEB4B4;
            border-radius: 4px;
            background: #EEB4B4;
            margin: 5px;
            margin-top: -5px !important;
        }

        .number-status {
            font-size: 35px
        }

        .column-status {
            float: left;
            width: 25%;
            padding: 10px;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .select2 {
            width : 100% !important;
        }

    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÔNG TIN DỰ ÁN')}}
                    </h3>
                </div>
                <div style="    right: 1%;position: absolute;">
                    <a href="{{route('manager-project.project')}}" type="button" class="btn btn-secondary" data-dismiss="modal" style="    color: black;font-weight: bold;">
                        <span class="la 	la-arrow-left"></span>
                        {{__('TRỞ VỀ')}}
                    </a>
                </div>
            </div>
        </div>
        @include('manager-project::project-info.block-project-info-master')
    </div>
    <div class="m-portlet">
        <div class="m-portlet__head m-portlet__head-update">
            @include('manager-project::layouts.project-info-tab-header')
        </div>
    </div>
    <div class="m-portlet" id="autotable" style="margin-bottom: 0.15rem;padding: 10px">
        <div class="m-portlet__head" style="height: 6.1rem !important">
            <div class="row" style="width:100%">
                <div class="column-status" style="background-color:#3399FF;">
                    <p class="mb-0">{{__('CÔNG VIỆC')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['totalWork'] : 0}}</p>
                </div>
{{--                <div class="column-status" style="background-color:#FFCC00;">--}}
{{--                    <p class="mb-0">{{__('CÓ NGUY CƠ TRỄ HẠN')}}</p>--}}
{{--                    <p class="mb-0 number-status">{{ $info['work-duration'] != [] ? $info['work-duration']['workMayBeLate']: 0}}</p>--}}
{{--                </div>--}}
                <div class="column-status" style="background-color:#66CC33;">
                    <p class="mb-0">{{__('HOÀN THÀNH ĐÚNG HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteOnTime']:0}}</p>
                </div>
                <div class="column-status" style="background-color:#CC33FF;">
                    <p class="mb-0">{{__('HOÀN THÀNH TRỄ HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteLate']:0}}</p>
                </div>
                <div class="column-status" style="background-color:#FF3300;">
                    <p class="mb-0">{{__('ĐÃ TRỄ HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workOutOfDate']:0}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="m-portlet">
        <div class="m-portlet__head text-right">
            <div class="m-portlet__head-caption"></div>
            <div class="m-portlet__head-tools ">
                <a href="{{route('manager-project.phase.add',['id' => $info['project_id']])}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3 mt-3">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Thêm giai đoạn') }}</span>
                    </span>
                </a>
            </div>
        </div>

        <div class="m-portlet__body" style="padding: 0px;padding-top: 10px;">
            <form class="frmFilter ss--background search-work-by-phase">
                <div class="row ss--bao-filter">
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input id="config_content" name="phase_name" type="text"
                                       class="form-control m-input class"
                                       value="{{isset($param['phase_name']) ? $param['phase_name'] : ''}}"
                                       placeholder="{{__('Tên giai đoạn')}}"
                                       aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control select2" name="phase_status">
                                    <option value="">{{__('Trạng thái')}}</option>
                                    <option value="new" {{isset($param['phase_status']) && $param['phase_status'] == 'new' ? 'selected' : ''}}>{{__('Mới')}}</option>
                                    <option value="processing" {{isset($param['phase_status']) && $param['phase_status'] == 'processing' ? 'selected' : ''}}>{{__('Đang thực hiện')}}</option>
                                    <option value="success" {{isset($param['phase_status']) && $param['phase_status'] == 'success' ? 'selected' : ''}}>{{__('Hoàn thành')}}</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly="" class="form-control date-picker-list"
                                       id="m_datepicker_1" style="background-color: #fff"
                                       name="date_start" value="" autocomplete="off"
                                       placeholder="{{isset($param['date_start']) && $param['date_start'] != null  ? $param['date_start'] : __('Ngày bắt đầu')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly="" class="form-control date-picker-list"
                                       id="m_datepicker_2" style="background-color: #fff"
                                       name="date_end" value="" autocomplete="off"
                                       placeholder="{{isset($param['date_end']) && $param['date_end'] != null  ? $param['date_end'] : __('Ngày kết thúc')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-right form-group">
                            <a href="{{route('manager-project.project.project-info-phase',['id'=> $info['project_id']])}}"
                               class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </a>
                            <button type="submit"
                                    class="btn ss--btn-search1 color_button">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="m-portlet" id="" style="margin-top: 10px;">
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table">
                        <thead>
                        <tr class="ss--nowrap">
                            <th class="ss--font-size-th ss--text-center">{{__('#')}}</th>
                            <th class="ss--font-size-th ss--text-center">{{__('Hành động')}}</th>
                            <th class="ss--font-size-th ss--text-center" style="width: 350px">{{__('Tên giai đoạn')}}</th>
                            <th class="ss--font-size-th  ss--text-center">{{__('Người chịu trách nhiệm')}}</th>
                            <th class="ss--font-size-th ss--text-center">{{__('Công việc')}}</th>
                            <th class="ss--font-size-th ss--text-center">{{__('Ngày bắt đầu')}}</th>
                            <th class="ss--font-size-th ss--text-center">{{__('Ngày kết thúc')}}</th>
                            <th class="ss--font-size-th ss--text-center">{{__('Ngày hoàn thành')}}</th>
                            <th class="ss--font-size-th ss--text-center">{{__('Trạng thái')}}</th>
                            <th class="ss--font-size-th ss--text-center">{{__('Tình trạng1')}}</th>
                        </tr>
                        </thead>
                        <tbody style="font-weight: 400">
                        @foreach( $info['listPhase'] as $key => $val)
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">{{isset($param['page']) ? ($param['page']-1)*10 + $key+1 :$key+1}}</td>

                                <td class="ss--text-center">
                                    <button
                                            type="button"
                                            onclick="Phase.showPopup({{ $val['manage_project_phase_id']}})"
                                            class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                            title="Chỉnh sửa">
                                        <i class="la la-edit"></i>
                                    </button>
                                    @if($val['is_default'] == 0)
                                        <a href="javascript:void(0)"
                                           onclick="Phase.deletePhase(this,{{$val['manage_project_phase_id']}})"
                                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                           title="Xóa">
                                            <i class="la la-trash"></i>
                                        </a>
                                    @endif
                                </td>
                                <td class=" ss--text-center">{{ $val['phase_name'] }}</td>
                                <td class=" ss--text-center">{{ $val['staff_name'] }}</td>
                                <td class=" ss--text-center">{{ $val['work'] }}</td>
                                <td class=" ss--text-center">{{ isset($val['phase_start']) &&  $val['phase_start'] != null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $val['phase_start'])->format('d/m/Y') : ''}}</td>
                                <td class=" ss--text-center">{{ isset($val['phase_end']) &&  $val['phase_end'] != null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $val['phase_end'])->format('d/m/Y') : ''}}</td>
                                <td class=" ss--text-center">{{ isset($val['max_date_end_work']) &&  $val['max_date_end_work'] != null && $val['work'] > 0 ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $val['max_date_end_work'])->format('d/m/Y') : ''}}</td>
                                @if(isset($val['phase_status']) && $val['phase_status'])
                                    @if($val['phase_status'] == 'new')
                                        <td class=" ss--text-center">{{__('Mới')}}</td>
                                    @elseif ($val['phase_status'] == 'processing')
                                        <td class=" ss--text-center">{{__('Đang thực hiện')}}</td>
                                    @else
                                        <td class=" ss--text-center">{{__('Hoàn thành')}}</td>
                                    @endif
                                @else
                                    <td class=" ss--text-center"></td>
                                @endif

                                <td class=" ss--text-center">{{ $val['condition']['condition_name'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(count($info['listPhase']) < 1)
                        <p style="    text-align: center;">{{__('Chưa có thông tin')}}</p>
                    @endif
                </div>
                @if(isset($info['listPhase']) && count($info['listPhase']) > 0)
                    {{ $info['listPhase']->links('manager-project::helpers.paging') }}
                @endif
            </div>
        </div>
    </div>
    <div class="append-popup"></div>
@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{ asset('static/backend/js/manager-project/phase/script.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script>
        Phase._init();
    </script>

@stop

