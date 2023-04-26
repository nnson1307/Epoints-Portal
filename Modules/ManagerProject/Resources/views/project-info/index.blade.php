@extends('layout')
@section("after_style")
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">

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
        .low-risk {
            border: 1px solid #FFF0F5;
            background: #FFF0F5;
            border-radius: 5px;
            color: green;
            font-weight: 600;
        }
        hight-risk {
            border: 1px solid #FFF0F5;
            background: #FFF0F5;
            border-radius: 5px;
            color: dodgerblue;
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
            margin-bottom: 10px;
            position: relative;
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
        .fa-trash-alt{
            font-weight: 900;
            color: red;
            border: 1px solid white;
            width: 30px;
            height: 30px;
            padding: 7px;
            border-radius: 50%;
            background-color: white;
        }
        .fa-trash-alt:hover{
            cursor:pointer;
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
        .select2 {
            width :100% !important;
        }
        .project_describe {
            overflow: auto;
        }
        .height-main {
            height : 350px;
            overflow: auto;
        }
    </style>
    <div class="m-portlet" id="autotable">
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
    <div class="m-portlet" id="autotable" style="margin-bottom: 0.15rem">
        <div class="m-portlet__head m-portlet__head-update">
        @include('manager-project::layouts.project-info-tab-header')
        </div>
    </div>
    <div class="pie-chart" style="display: flex">
        <div class="col-6" style="padding: 0;padding-right:2px">
            <div class="m-portlet" id="autotable">
                @include('manager-project::project-info.block-project-info')
            </div>
        </div>
        <div class="col-6" style="padding: 0;padding-left:2px">
            <div class="m-portlet" id="autotable" style="    height: 99.26%;">
                <div class="m-portlet__body">
                    <span class="chart-name">{{__('Mô tả')}}</span>
                </div>
                <div class="m-portlet__body project_describe height-main" style="font-weight: 400;">
                    {!! isset($info['project_describe']) ? $info['project_describe'] : __('Chưa có mô tả') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="pie-chart" style="display: flex">
        <div class="col-6" style="padding: 0;padding-right:2px">
            <div class="m-portlet" id="autotable"  style="    height: 99.5%;">
                <div class="m-portlet__body">
                    <span class="chart-name">{{__('Vấn đề gần đây')}}</span>
                    @if(isset($info['issue']) && count($info['issue'])>3)
                    <a style="float: right;    font-weight: 400;" title="{{__('Tất cả vấn đề')}}"
                       href="{{route('manager-project.project.project-info-all-issue' ,['id' => $info['project_id']])}}">{{__('Xem tất cả')}}</a>
                    @endif
                </div>
                @if(isset($info['issue']) && count($info['issue'])>0)
                    <div class="m-portlet__body height-main" style="font-weight: 400;">
                        @foreach($info['issue'] as $key=>$value)
                            @if($key <= 2)
                                <div class="issue">
                                    <p class="font-weight-bold">
                                        <img src="{{isset($value['staff_avatar']) ? $value['staff_avatar'] : ''}}"
                                             alt="" style="    width: 35px;height: 35px;border-radius: 50%;">
                                        {{isset($value['staff_name']) ? $value['staff_name'] : ''}}
                                    </p>
                                    <p style="margin: 10px">{{isset($value['content']) ? $value['content'] : ''}}</p>

                                    <div class="display-flex">
                                        <p>
                                            <i class=" la 	la-clock-o"></i>
                                            {{isset($value['created_at']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $value['created_at'])->format('d-m-Y H:i') : ''}}
                                        </p>
                                        @if(!in_array($info['manage_project_status_group_config_id'],[3,4]))
                                            <button class="card-status" title="{{__('Thêm công việc')}}" onclick="WorkChild.issueShowPopup('{{$value['project_issue_id']}}')"
                                                    style="    position: absolute;right: 0%;    padding: 5px;color: white;background: #0067AC ;">
                                                <i class="fa 	fa-plus" style="    font-size: 15px;color: white"></i>
                                                {{__('Thêm công việc')}}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                @else
                    <div class="m-portlet__body height-main">
                        <p>{{__('Chưa có vấn đề')}}</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-6" style="padding: 0;padding-left:2px">
            <div class="m-portlet" id="autotable" style="    height: 99.5%;">
                <div class="m-portlet__body">
                    <span class="chart-name">{{__('Nhắc nhở')}}</span>
                    <a href="javascript:void(0)" onclick="Remind.showPopup({{$info['project_id']}})">
                    <span>
                        <i title="{{__('Thêm nhắc nhở')}}" class="fa 	fa-plus"
                           style="    font-size: 25px;color: #66CCFF;float: right;"></i>
                    </span>
                    </a>
                </div>
                @if(isset($info['remind']) && count($info['remind'])>0)
                    <div class="m-portlet__body height-main" >
                        @foreach($info['remind'] as $key => $value)
                            <div class="row" style="padding-bottom: 15px">
                                <div class="col-1">
                                    <a
                                            class="fa 	fa-trash-alt"
                                            style="    width: 35px;height: 35px;    font-size: 20px;"
                                            title="{{__('Xóa nhắc nhở')}}"
                                            href="javascript:void(0)" onclick="projectInfo.deleteRemind({{$value}})">
                                    </a>
                                </div>
                                <div class="issue col-11"
                                     style="box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;">
                                    <div class="display-flex">
                                        <p style="     padding: 0px 5px;   border: 1px solid grey;border-radius: 5px;box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;margin: 5px;">
                                            <i class=" la 	la-clock-o"></i>
                                            {{isset($value['created_at']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' ,$value['created_at'])->format('d/m/Y H:i') : ''}}
                                        </p>
                                        <i style="margin-top: 6px;color: grey;">{{ ' - ' . $value['time_remainng']}}</i>
                                    </div>
                                    <p class="font-weight-bold kt-margin-0">
                                        {{isset($value['title']) ? $value['title'] : ''}}
                                    </p>
                                    <p style="margin-top: 0;margin-bottom: 0rem;    font-weight: 400;">
                                        {{isset($value['description']) ? $value['description'] : ''}}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="m-portlet__body height-main">
                        <p>{{__('Chưa có nhắc nhở')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__body">
            @include('manager-project::project-info.block-statistical')
        </div>
    </div>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__body">
            <span class="chart-name">{{__('TÓM TẮT')}}</span>
            @include('manager-project::project-info.block-summary')
        </div>
    </div>
    <div id="frm-search">
        <input type="hidden" name="manage_project_id" value="{{$info['project_id']}}">
    </div>
    <div class="append-popup"></div>
    <form id="form-work">
        <div id="append-add-work"></div>
    </form>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/remind/script.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/managerWork/list.js?v='.time())}}"></script>
    <script>
        $('.select2').select2();

    </script>
    <script>
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        var projectInfo = {
            showPopupAddRemind : function (id) {
                $.ajax({
                    url: laroute.route("manager-project.project.popup-add-remind"),
                    method: "POST",
                    data:{
                        id : id
                    },
                    success: function (res) {
                        $('.append-popup').empty();
                        $('.append-popup').append(res.view);
                        $('#add-remind').modal('show');
                    }
                });
            },
            deleteRemind : function (data) {
                Swal.fire({
                    title: jsonLang['Thông báo'],
                    text: jsonLang['Bạn chắc chắn muốn xóa nhắc nhở này?'],
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: jsonLang['Tiếp tục'],
                    cancelButtonText: jsonLang['Hủy']
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: laroute.route("manager-project.project.delete-remind"),
                            method: "POST",
                            data: {
                                data : data
                            },
                            success: function (res) {
                                if (res.error == true) {
                                    swal("Lỗi", res.message , "error")
                                } else {
                                    swal(jsonLang["Xóa thành công!"], jsonLang["Nhấn OK để tiếp tục!"], "success").then(function () {
                                        location.reload();
                                    });
                                }
                            }
                        })
                    }
                })
            }
        }
    </script>



@stop

