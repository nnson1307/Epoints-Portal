@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
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
            position: relative;
            margin-bottom: 10px;
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
            </div>
        </div>
        <div class="m-portlet__body" style="padding: 20px 0px">
            <div class="card-title">
                <h4 style="display:flex">
                    <div style="color:#0b2e13">
                        <b>{{__('Danh sách vấn đề')}}</b>
                    </div>
                    <div class="m-demo__preview m-demo__preview--btn" style="    position: absolute;right: 50px">
                        <a href="{{route('manager-project.project.project-info-overview',['id' => $id])}}" type="button" class="btn m-btn--pill    btn-outline-info btn-sm" style="float: right">
                            <i class="fa fa-angle-double-left m--margin-right-5" style="padding-bottom: 2px"></i>
                            {{__('Trở về')}}
                        </a>
                    </div>
                </h4>
            </div>
            <div class="m-portlet__body">
                @foreach($data as $key=>$value)
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
                                    <button class="card-status" onclick="WorkChild.issueShowPopup('{{$value['project_issue_id']}}')" title="{{__('Thêm công việc')}}"
                                            style="    position: absolute;right: 0%;    color: white;background: dodgerblue;">
                                        <i class="fa 	fa-plus" style="    font-size: 15px;color: white"></i>
                                        {{__('Thêm công việc')}}
                                    </button>
                                @endif
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
    </div>
    <div id="frm-search">
        <input type="hidden" name="manage_project_id" value="{{$id}}">
    </div>
    <form id="form-work">
        <div id="append-add-work"></div>
    </form>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/managerWork/list.js?v='.time())}}"></script>
@stop

