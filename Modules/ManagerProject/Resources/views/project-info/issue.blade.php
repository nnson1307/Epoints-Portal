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
        .button-issue{
            color: white;
            border: none;
            border-radius: 5px;
            background-color: dodgerblue;
        }
        .button-issue:hover{
            color: white;
            background-color: #0067AC ;
            cursor: pointer;
        }
        .processed{
            color: white;
            border: none;
            border-radius: 5px;

        }

        .select2 {
            width :100% !important;
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
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head m-portlet__head-update">
            @include('manager-project::layouts.project-info-tab-header')
        </div>
    </div>
    <div class="m-portlet" id="autotable" style="margin-bottom: 0.15rem;padding: 10px;    height: 115px;">
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
    <div style="padding-top: 10px;float:right">
        <div class="m-portlet__head-tools">
            <a href="javascript:void(0)" onclick="projectInfo.showPopupAddIssue({{$info['project_id']}})"
               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Thêm vấn đề') }}</span>
                    </span>
            </a>
        </div>
    </div>
    <div class="m-portlet__body" style="padding: 0px;padding-top: 50px;">
        <form class="frmFilter ss--background search-work-by-phase">
            <div class="row ss--bao-filter">
                <div class="col-lg-4">
                    <div class="form-group m-form__group">
                        <div class="input-group">
                            <select class="form-control select2" name="issue_status">
                                <option value="">{{__('Trạng thái')}}</option>
                                <option value="new" {{isset($param['issue_status']) && $param['issue_status'] == 'new' ? 'selected' : ''}}>{{__('Mới')}}</option>
                                <option value="processing" {{isset($param['issue_status']) && $param['issue_status'] == 'processing' ? 'selected' : ''}}>{{__('Đang xử lí')}}</option>
                                <option value="success" {{isset($param['issue_status']) && $param['issue_status'] == 'success' ? 'selected' : ''}}>{{__('Đã xử lí')}}</option>
                                <option value="reject" {{isset($param['issue_status']) && $param['issue_status'] == 'reject' ? 'selected' : ''}}>{{__('Hủy')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group m-form__group">
                        <div class="input-group">
                            <select class="form-control select2" name="staff_id">
                                <option value="">{{__('Người tạo')}}</option>
                                @foreach($listStaff as $key => $val)
                                    <option value="{{$val['manager_id']}}" {{isset($param['staff_id']) && $param['staff_id'] == $val['manager_id'] ? 'selected' : ''}}>{{$val['manager_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input readonly="" class="form-control "
                                   id="m_datepicker_1" style="background-color: #fff"
                                   name="created_at" value="" autocomplete="off"
                                   placeholder="{{isset($param['created_at']) && $param['created_at'] != null  ? $param['created_at'] : __('Ngày bắt đầu')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                <div class="text-right">
                    <a href="{{route('manager-project.project.project-info-issue',['id'=> $info['project_id']])}}"
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
        </form>
        <div class="pie-chart" style="display: flex">
            <div class="col-12" style="padding: 0;padding-right:2px">
                <div class="m-portlet" id="autotable" style="    height: 99.5%;">
                    <div class="m-portlet__body">
                        <span class="chart-name">{{__('Danh sách vấn đề')}}</span>
                    </div>
                    @if(isset($info['issue']) && count($info['issue'])>0)
                        <div class="m-portlet__body" style="height: 500px;overflow: scroll;">
                            @foreach($info['issue'] as $key=>$value)
                                <div class="issue" style="    margin-bottom: 10px;    position: relative">
                                    <div style="display: flex">
                                        <p class="font-weight-bold">
                                            <img src="{{isset($value['staff_avatar']) ? $value['staff_avatar'] : ''}}"
                                                 alt="" style="    width: 35px;height: 35px;border-radius: 50%;">
                                            {{isset($value['staff_name']) ? $value['staff_name'] : ''}}
                                        </p>
                                        <p style="    margin-top: 5px;margin-left: 10px;">
                                            <i class=" la 	la-clock-o"></i>
                                            {{isset($value['created_at']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $value['created_at'])->format('d-m-Y H:i') : ''}}
                                        </p>
                                        <div>
                                            <button
                                                    type="button"
                                                    onclick="projectInfo.showPopupEditIssue({{$value['project_issue_id']}})"
                                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                    title="Chỉnh sửa">
                                                <i class="la la-edit"></i>
                                            </button>
                                            <a href="javascript:void(0)"
                                               onclick="projectInfo.deleteIssue({{$value['project_issue_id']}})"
                                               class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                               title="Xóa">
                                                <i class="la la-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <p style="margin: 10px">{{isset($value['content']) ? $value['content'] : ''}}</p>
                                    <div style="margin-bottom:10px">
                                        @if(!in_array($info['manage_project_status_group_config_id'],[3,4]))
                                            <button class="button-issue"
                                                    onclick="WorkChild.issueShowPopup('{{$value['project_issue_id']}}')"
                                                    title="{{__('Thêm công việc')}}">
                                                <i class="fa 	fa-plus"></i>
                                                {{__('Thêm công việc')}}
                                            </button>
                                        @endif
                                        <button class="button-issue" title="{{__('Trao đổi')}}"
                                                onclick="projectInfo.showPopupAddExchange('{{$value['project_issue_id']}}','exchange')">
                                            <i class="fa fa-regular fa-comment"></i>
                                            {{__('Trao đổi')}}
                                        </button>
                                        @if(isset($value['status']) && $value['status'] == 'success')
                                            <button class="processed" style="background-color: #339933;">
                                                <i class="fa fa-solid fa-check"></i>

                                                {{__('Đã xử lí')}}
                                            </button>
                                        @elseif(isset($value['status']) && $value['status'] == 'new')
                                            <button class="processed" style="background-color: #00CCCC;">
                                                <i class="fa fa-light fa-sparkles"></i>

                                                {{__('Mới')}}
                                            </button>
                                        @elseif(isset($value['status']) && $value['status'] == 'processing')
                                            <button class="processed" style="background-color: #9966FF;">
                                                <i class="fa fa-duotone fa-typewriter"></i>
                                                {{__('Đang xử lí')}}
                                            </button>
                                        @else
                                            <button class="processed" style="background-color: #FF6633;">
                                                <i class="fa fa-solid fa-xmark"></i>
                                                {{__('Hủy')}}
                                            </button>
                                        @endif
                                        @if(isset($value['list_work']) && $value['list_work'] != null  && $value['list_work'] != [])
                                            @foreach($value['list_work'] as $keyyWork => $vallWork)
                                                <button class="processed" style="background-color: #CCFFFF;    margin-left: 2px;"
                                                        title="{{__('Công việc liên quan: ').$vallWork['work_code']}}">
                                                    <a target="_blank"
                                                       href="{{route('manager-work.detail',['id' => $vallWork['work_id']])}}">{{$vallWork['work_code']}}</a>
                                                </button>
                                            @endforeach
                                        @endif
                                    </div>
                                    @if(isset($value['issue_child']) && $value['issue_child'] != [])
                                        <span style="margin-bottom: 10px;    position: relative ;    width: 98%;margin-left: 2%; font-weight: bold">{{__('Danh sách trao đổi:')}}</span>

                                        @foreach($value['issue_child'] as $k => $v)
                                            <div class="issue"
                                                 style="    margin-bottom: 10px;    position: relative ;    width: 98%;margin-left: 2%;">
                                                <div style="display: flex">
                                                    <p class="font-weight-bold">
                                                        <img src="{{isset($v['staff_avatar']) ? $v['staff_avatar'] : ''}}"
                                                             alt=""
                                                             style="    width: 35px;height: 35px;border-radius: 50%;">
                                                        {{isset($v['staff_name']) ? $v['staff_name'] : ''}}
                                                    </p>
                                                    <p style="    margin-top: 5px;margin-left: 10px;">
                                                        <i class=" la 	la-clock-o"></i>
                                                        {{isset($v['created_at']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $v['created_at'])->format('d-m-Y H:i') : ''}}
                                                    </p>
                                                    <div>
                                                        <button
                                                                type="button"
                                                                onclick="projectInfo.showPopupEditIssue({{$v['project_issue_id']}})"
                                                                class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                                title="Chỉnh sửa">
                                                            <i class="la la-edit"></i>
                                                        </button>
                                                        <a href="javascript:void(0)"
                                                           onclick="projectInfo.deleteIssue({{$v['project_issue_id']}})"
                                                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                           title="Xóa">
                                                            <i class="la la-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <p style="margin: 10px">{{isset($v['content']) ? $v['content'] : ''}}</p>
                                                <div>
                                                    @if(!in_array($info['manage_project_status_group_config_id'],[3,4]))
                                                        <button class="button-issue" onclick="WorkChild.issueShowPopup('{{$v['project_issue_id']}}')" title="{{__('Thêm công việc')}}">
                                                            <i class="fa 	fa-plus"></i>
                                                            {{__('Thêm công việc')}}
                                                        </button>
                                                    @endif
                                                    @if(isset($v['status']) && $v['status'] == 'success')
                                                        <button class="processed" style="background-color: #339933;">
                                                            <i class="fa fa-solid fa-check"></i>

                                                            {{__('Đã xử lí')}}
                                                        </button>
                                                    @elseif(isset($v['status']) && $v['status'] == 'new')
                                                        <button class="processed" style="background-color: #00CCCC;">
                                                            <i class="fa fa-light fa-sparkles"></i>

                                                            {{__('Mới')}}
                                                        </button>
                                                    @elseif(isset($v['status']) && $v['status'] == 'processing')
                                                        <button class="processed" style="background-color: #9966FF;">
                                                            <i class="fa fa-duotone fa-typewriter"></i>
                                                            {{__('Đang xử lí')}}
                                                        </button>
                                                    @else
                                                        <button class="processed" style="background-color: #FF6633;">
                                                            <i class="fa fa-solid fa-xmark"></i>
                                                            {{__('Hủy')}}
                                                        </button>
                                                    @endif
                                                        @if(isset($v['list_work']) && $v['list_work'] != null  && $v['list_work'] != [])
                                                            @foreach($v['list_work'] as $keyyWork => $vallWork)
                                                                <button class="processed" style="background-color: #CCFFFF;    margin-left: 2px;"
                                                                        title="{{__('Công việc liên quan: ').$vallWork['work_code']}}">
                                                                    <a target="_blank"
                                                                       href="{{route('manager-work.detail',['id' => $vallWork['work_id']])}}">{{$vallWork['work_code']}}</a>
                                                                </button>
                                                            @endforeach
                                                        @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="m-portlet__body">
                            <p>{{__('Chưa có vấn đề')}}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <div class="append-popup"></div>

    <div id="frm-search">
        <input type="hidden" name="manage_project_id" value="{{$info['project_id']}}">
    </div>
    <form id="form-work">
        <div id="append-add-work"></div>
    </form>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/info-project/script.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/managerWork/list.js?v='.time())}}"></script>
@stop

