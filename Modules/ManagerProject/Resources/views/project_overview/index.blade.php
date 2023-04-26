@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('TỔNG QUAN DỰ ÁN')}}
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
        .chart-name{
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
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            width: 100%;
            border-radius: 5px;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        img {
            border-radius: 5px 5px 0 0;
        }

        .container {
            padding: 2px 16px;
        }
        table, th, td{
            border-top:1px solid #ccc;
            border-bottom:1px solid #ccc;
        }
        table{
            border-collapse:collapse;
            width:100%;
        }
        th, td{
            text-align:left;
            padding:10px;
        }
        .statistical td{
            border: none;
            /*display:flex*/
        }
        .card-title{
            padding: 10px 20px;
            margin: 0;
        }
        .card-status{
            font-size: 15px;
            color: #2F9AF4;
            border: 1px solid #CAE1FF;
            border-radius: 4px;
            background: #CAE1FF;
            margin: 10px;
        }
        .hight-risk{
            border: 1px solid #FFF0F5;
            background: #FFF0F5;
            border-radius: 5px;
            color: #A0522D;
            font-weight: 600;
        }
        .fs-15{
            font-size: 15px;
        }
        .style-icon-statistical{
            font-size: 2rem;
            padding: 7px
        }
        .issue{
            border: 1px solid;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .display-flex{
            display: flex;
        }
        .inline-block{
            display: inline-block;
        }
        .ui-datepicker-calendar {
            display: none;
        }

    </style>
    <div class="m-portlet" id="">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon" style="font-weight: bold;">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text" style="font-weight: bold;">
                        {{__('TỔNG QUAN DỰ ÁN')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-portlet__body" style="padding: 0px;    padding-bottom: 20px;">
                <form class="frmFilter ss--background search-project">
                    <div class="row ss--bao-filter" style="    background-color: white;">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <select class="form-control select2" name="department_id">
                                        <option value="">{{__('Phòng ban')}}</option>
                                        @foreach($department as $key => $value)
                                            <option value="{{$value['department_id']}}">{{$value['department_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <select class="form-control select2" name="month">
                                        @for( $i = 1 ; $i <= 12 ; $i ++)
                                            <option value= {{$i}} {{isset($input['month']) && $input['month'] == $i  ? 'selected' : ' '}}>
                                                @switch($i)
                                                    @case(1)
                                                    {{__('Tháng 1')}}
                                                    @break
                                                    @case(2)
                                                    {{__('Tháng 2')}}
                                                    @break
                                                    @case(3)
                                                    {{__('Tháng 3')}}
                                                    @break
                                                    @case(4)
                                                    {{__('Tháng 4')}}
                                                    @break
                                                    @case(5)
                                                    {{__('Tháng 5')}}
                                                    @break
                                                    @case(6)
                                                    {{__('Tháng 6')}}
                                                    @break
                                                    @case(7)
                                                    {{__('Tháng 7')}}
                                                    @break
                                                    @case(8)
                                                    {{__('Tháng 8')}}
                                                    @break
                                                    @case(9)
                                                    {{__('Tháng 9')}}
                                                    @break
                                                    @case(10)
                                                    {{__('Tháng 10')}}
                                                    @break
                                                    @case(11)
                                                    {{__('Tháng 11')}}
                                                    @break
                                                    @case(12)
                                                    {{__('Tháng 12')}}
                                                    @break
                                                @endswitch
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input class="date-own form-control"
                                           style="width: 300px;" type="text"
                                           name="year" placeholder="Năm" value="{{isset($input['year']) && $input['year'] != null ? $input['year'] : getdate()['year'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{route('project-overview')}}"
                               class="btn btn-refresh btn-primary color_button m-btn--icon">
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
                    @include('manager-project::project_overview.projects_progress')
                </form>
            </div>
        </div>
    </div>
    @include('manager-project::project_overview.chart')
    <div class="m-portlet" id="">
        <div class="m-portlet__body">
            <span class="chart-name">{{__('DỰ ÁN CÓ MỨC ĐỘ RỦI RO CAO')}}</span>
            @if(isset($projectHighRisk) && count($projectHighRisk) > 0)
            <div class="m-portlet__body" style="padding: 20px 0px;height: 380px;overflow: auto;">
                    @foreach($projectHighRisk as $k => $v)
                        <div class="card" style="    margin-bottom: 10px">
                            <div class="card-title">
                                <h4>
                                    <b>{{$v['project_name']}}</b>
                                    <b class="card-status" style="padding: 5px 10px">
                                        &#x2022;{{isset($v['project_status_name']) ? ' '.$v['project_status_name'] : ''}}</b>
                                    <div class="m-demo__preview m-demo__preview--btn">
                                        <a href="{{route('project-overview')}}" type="button"
                                           class="btn m-btn--pill    btn-outline-info btn-sm" style="float: right">
                                            <i class="fa fa-plus-circle m--margin-right-5" style="padding-bottom: 2px"></i>
                                            {{__('Thêm nhắc nhở')}}
                                        </a>
                                    </div>
                                </h4>
                            </div>
                            <div class="info-card" style="display:flex">
                                <div class="col-6">
                                    <table class="table-hover">
                                        <tr>
                                            <th>{{__('Người quản trị')}}</th>
                                            <th>{{isset($v['manager_name']) ? $v['manager_name'] : ''}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('Khách hàng')}}</th>
                                            <th>{{isset($v['manager_name']) ? $v['manager_name'] : ''}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('Ngày bắt đầu')}}</th>
                                            <td style="font-weight: bold;color: #363636">{{isset($v['from_date']) && $v['from_date']!= null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d' , $v['from_date'])->format('d/m/Y') : ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Ngày kết thúc')}}</th>
                                            <td style="font-weight: bold;color: #2E8B57">{{isset($v['to_date']) && $v['to_date']!= null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d' , $v['to_date'])->format('d/m/Y') : ''}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <div class="row" style="display:flex">
                                        <div class="col-6" style="    text-align: center;">
                                            <p class="mb-0 font-weight-bold">{{__('Mức độ rủi ro')}}</p>
                                            <p class="mb-0 hight-risk">{{__('Cao')}}</p>
                                        </div>
                                        <div class="col-6" style="    text-align: center;">
                                            <p class="mb-0 font-weight-bold">{{__('Tình trạng')}}</p>
                                            <p class="mb-0 hight-risk">{{$v['condition']}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="mb-0 font-weight-bold" style="margin:15px">{{__('Tiến độ')}}</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="progress" style="width: 100%;height: 1.5rem;position: relative">
                                                @if(isset($v['progress']) && $v['progress']!=null)
                                                    <div class="progress-bar font-weight-bold" role="progressbar" style="width: {{$v['progress']}}%;color: black !important;background-color: dodgerblue"
                                                         title="{{' '.$v['progress']}}%"
                                                         aria-valuenow="{{$v['progress']}}" aria-valuemin="0" aria-valuemax="100">
                                                        {{' '.$v['progress']}}%
                                                    </div>
                                                @else
                                                    <div class="progress-bar font-weight-bold" role="progressbar" style="width: {{$v['progress']}}%;color: black !important;background-color: #e9ecef"
                                                         aria-valuenow="{{$v['progress']}}" aria-valuemin="0" aria-valuemax="100">
                                                        &ensp; 0%
                                                    </div>
                                                @endif

                                                <span style="position: absolute;right: 10px;top: 0;bottom: 0;margin: auto;height: fit-content;font-weight: bold">100%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="mb-0 font-weight-bold" style="margin:15px">{{__('Số ngày làm việc')}}</p>
                                        <br>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="progress" style="width: 100%;height: 1.5rem;position: relative">
                                                @if($v['resource_implement'] != 0 && ($v['resource_total'] != 0 && $v['resource_total'] != null))

                                                    <div class="progress-bar font-weight-bold" role="progressbar"
                                                         style="width: {{$v['resource_implement']/$v['resource_total']*100}}%;color: black !important;background-color: dodgerblue"
                                                         title="{{$v['resource_implement']}} {{__(' ngày')}}"
                                                         aria-valuenow="{{$v['resource_implement']/$v['resource_total']*100}}"
                                                         aria-valuemin="0" aria-valuemax="100">{{$v['resource_implement']}} {{__(' ngày')}}
                                                    </div>
                                                @else
                                                    <div class="progress-bar font-weight-bold" role="progressbar"
                                                         style="width: 0%;color: black !important;background-color: #0066cc"
                                                         aria-valuenow="0"
                                                         aria-valuemin="0" aria-valuemax="100">&ensp;0 {{__(' ngày')}}
                                                    </div>
                                                @endif

                                                <span style="position: absolute;right: 10px;top: 0;bottom: 0;margin: auto;height: fit-content;font-weight: bold">{{$v['resource_total']}}{{__(' ngày')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="statistical" style="padding: 0 10px;">
                                <table style="border:1px solid white">
                                    <td>
                                        <i class="fa fa-users style-icon-statistical"></i>
                                        <div class="inline-block">
                                            <p class="fs-15 mb-0 text-center">{{__('Thành viên')}}</p>
                                            <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{$v['total_member']}}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fa fa-file-alt style-icon-statistical"></i>
                                        <div class="inline-block">
                                            <p class="fs-15 mb-0 text-center">{{__('Công việc')}}</p>
                                            <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($v['total_work']) ? $v['total_work'] : 0}}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fa fa-book style-icon-statistical"></i>
                                        <div class="inline-block">
                                            <p class="fs-15 mb-0 text-center">{{__('Tài liệu')}}</p>
                                            <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">35</p>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fa fa-dollar-sign style-icon-statistical"></i>
                                        <div class="inline-block">
                                            <p class="fs-15 mb-0 text-center">{{__('Ngân sách')}}</p>
                                            <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($v['budget']) ? $v['budget'] : 0}} {{__('VNĐ')}}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fa fa-dollar-sign style-icon-statistical"></i>
                                        <div class="inline-block">
                                            <p class="fs-15 mb-0 text-center">{{__('Thu ')}}</p>
                                            <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($v['total_receipt']) ? $v['total_receipt'] : 0}} {{__('VNĐ')}}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fa fa-dollar-sign style-icon-statistical"></i>
                                        <div class="inline-block">
                                            <p class="fs-15 mb-0 text-center">{{__('Chi ')}}</p>
                                            <p class="mb-0 font-weight-bold" style="font-size: 20px;text-align: center;">{{isset($v['total_payment']) ? $v['total_payment'] : 0}} {{__('VNĐ')}}</p>
                                        </div>
                                    </td>
                                </table>
                            </div>
                        </div>
                    @endforeach
            </div>
                @else
                <div class="m-portlet__body" style="padding: 20px 0px;height: 80px;">
                    <span>{{__('Chưa có thông tin')}}</span>
                </div>
            @endif

        </div>
    </div>
    <div class="m-portlet" id="">
        <div class="m-portlet__body">
            <span class="chart-name">{{__('DỰ ÁN ĐÃ LÂU KHÔNG HOẠT ĐỘNG')}}</span>
            @if(isset($projectLongTimeInactive) && count($projectLongTimeInactive) > 0)
                <figure class="highcharts-figure-1">
                    <div id="barchart-inactive"></div>
                </figure>
            @else
                <div class="m-portlet__body" style="padding: 20px 0px;height: 50px;">
                    <span>{{__('Chưa có thông tin')}}</span>
                </div>
            @endif

        </div>
    </div>
    <div class="m-portlet" id="">
        <div class="m-portlet__body">
            <span class="chart-name">{{__('VẤN ĐỀ PHÁT SINH GẦN ĐÂY')}}</span>
            @if($listIssue != [])
            <div style="padding: 20px 0px;height: 380px;overflow: auto;">
                    @foreach($listIssue as $k => $v)
                        <div class="issue">
                            <div class="display-flex">
                                <p style="font-size: 20px;font-weight: bold;color: #2F9AF4;margin: 0;">
                                    <a href="{{route('manager-project.project.project-info-overview',['id'=> $v['project_id']])}}" title="{{__('Xem chi tiết')}}">{{isset($v['project_name']) ?  $v['project_name'] : ''}}</a>
                                </p>
                                @if($v['status'] == 'new')
                                    <b class="card-status" style="margin:3px 20px;    padding: 0px 7px;"> &#x2022;{{__('Mới')}}</b>
                                @elseif($v['status'] == 'success')
                                    <b class="card-status" style="margin:3px 20px;    padding: 0px 7px;color: #4CAF50;background-color:#B4EEB4 "> &#x2022;{{__('Đã xử lí')}}</b>
                                @elseif($v['status'] == 'processing')
                                    <b class="card-status" style="margin:3px 20px;    padding: 0px 7px;color: #FFCD39;background-color: #FFF68F"> &#x2022;{{__('Đang xử lí')}}</b>
                                @elseif($v['status'] == 'reject')
                                    <b class="card-status" style="margin:3px 20px;    padding: 0px 7px;color: #F44336;background-color: #FFA07A"> &#x2022;{{__('Từ chối')}}</b>
                                @endif
                            </div>
                            <p class="font-weight-bold">
                                <img src="{{isset($v['staff_avatar']) ? $v['staff_avatar'] : 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAHkAtgMBIgACEQEDEQH/xAAZAAADAQEBAAAAAAAAAAAAAAABAgMABAf/xAAtEAADAAEDBAIABAYDAAAAAAAAAQIRAyExEiJBcVFhBDKBkRMjUqGxwUJicv/EABQBAQAAAAAAAAAAAAAAAAAAAAD/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwD2hIpMhUoztT7+AE1J716DKB1Ze5RYAW12MRIq3lYQEsAZIm5737HepK2W7Fl5eWA0yDVnZexnUyuSbvq9AaZKY7X6MmsZ4FrV8Ss/YCTJRIWBnqTP2/gBdRd4ZkXqdVlrBTMyst4AFrsYkoZ6nVsuAzgApbE8d7Heolst2LLy8sBpkZ8AdKVuL/EbfCwA25jIADEms2/Yz1F4/cE/YDTI2MGRqYGN4A8Lkm9TO0oATJSUCOA1qzOy3fwgE1F3r0GJF6nVZax8FOqZWW8AbUX8tk5nAL1naxKwhYbl/PsDoSJVPe/Yz1dtp3BDzyA0ybVnZewu5jz+gj1Hb+gDMlEthU1jd4A9XxK/cBJkpKBHA1XM7Ld/CAS57v0DMg6nVZZRYAyRjZ+DASmSkoyWDVan7YBYGKrpvPgZZAGpvLEUlWttxUkAZnYjaStt/I9ay4hdT+Tn1G3TbAatTxP9xMtvcAyAyWR0BIZIAoZAQykBdWc3lfA0yOiWjrT011NZVNAUtdgkzwZ6jrZLCHjGNwDK3J47mM9VcTv9ghtvcBpQzQHSnkXrpv6AfBgIwB2wSa7mGtT4/c0/YDTIz4FdzPO7+Beun9AUFfBurbfYm9TP5QBEi6qxa9FYE1cO/wBAJ4CkHA2AAgpGQyAyRRfAJQwGOKFu/wD2zuS+Tg0ab1bXhW/8gdMyUxsBNJZewj1W/wAq2+QBM7FZQscbhrUS2S3AXUnvDKAm28sosYAKRjZABKZKSg4S8C1qKdluwF1F3L0GZAm29x3SlbgbUXYyag1alVtwh52W4BmSNLvr2O9bxCz9kqq5fU56p845QDYDgEVNrMvJRIBUhkhkkEDJBwYLeF9AY4vw0/zLf/d/5H1fxkS+nS76/sJ+HpQm75bb9gdOouwSJA9Sq+l8FIAKnYn09zLZBhc4AWUO0LVqNvInVTe4FNjAQAGJNZphep4Ro+wGmTai2Xs1Wp2xlidVVyAZkolsBPCy+BHqU+EkgFmCkwvIIyCtZJ4lPIEdfQX8RVDc1jlB09W521Zyv6kv9DptvL3Htyp7lyA01NLMtNDJHFU6nX1ab6H9f7BV6+p22+mfOFyB0a34mIfSu+v6UcWpWtr01bxP9M8DysbTOPsvpTjncCej+HS8Fr08KfZZNJbIDeeQEmSiWxkbIGMDIlX4QGtZsaUJOW8sqgDgwMmAlMlJQcCVq4eJW/2BtRdy9BmRE23l7lHSlZwBrXYxJkDuq9FJAKnYk5737DWrnaUac533AaZBrLZIN6il4SbYnVVPLA0yO4WG8G6lM5a2Qj1KrhYQCxpotMoWM+TVq4fTOc/LAZ8hJy22NVdK4yAxiXVT+kOntuAz4J9OaYHedkNGQDKGewtainZLcVVTeWBQBgAMSa7mVfAnkAzJtRbL2P4BfE+wElDpbAkcCEyVlCyOgJ6k9wZlBv8AMFAC12MSZK3+RiSAyRNz3P2W+Ca5YGmTai2XseQan/H2AikfGxhvAEJWxRLYE8IdcAT1F3hmQ3+cKAKMEwH/2Q=='}}" alt="" style="    width: 35px;height: 35px;border-radius: 50%;">
                                {{$v['staff_name']}}
                            </p>
                            <p style="margin: 10px">{{isset($v['content']) ?$v['content'] : ''}}</p>
                            <p>
                                <i class=" la 	la-clock-o"></i>
                                {{isset($v['created_at']) && $v['created_at']!= null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $v['created_at'])->format('d/m/Y') : ''}}
                            </p>
                            @if(!in_array($v['manage_project_status_group_config_id'],[3,4]))
                                <div class="text-right">
                                    <button class="card-status" onclick="WorkChild.issueShowPopup('{{$v['project_issue_id']}}','{{$v['project_id']}}')" title="{{__('Thêm công việc')}}"
                                            style=" padding: 5px 10px;color: white;background: #0067AC;">
                                        <i class="fa 	fa-plus" style="    font-size: 15px;color: white"></i>
                                        {{__('Thêm công việc')}}
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
            </div>
            @else
                <div  style="padding: 20px 0px;height: 50px">
                    <span>{{__('Chưa có thông tin')}}</span>

                </div>
            @endif
        </div>
    </div>
    <form id="form-work">
        <div id="append-add-work"></div>
    </form>
@endsection
@section('after_script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/managerWork/list.js?v='.time())}}"></script>

    <script>
        $('.select2').select2();
        $('.date-own').datepicker({
            minViewMode: 2,
            format: 'yyyy'
        });
    </script>
    <script>
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        @if(isset($chartStatus) && $chartStatus!=[])
        Highcharts.chart('piechart-status', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: 'none',
            tooltip: {
                pointFormat:
                 jsonLang['Số lượng'] + ' : <b>{point.y}</b><br/>'+
                  jsonLang['Tỉ lệ'] + ': <b>{point.percentage:.1f}%</b><br/>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Tỉ lệ',
                colorByPoint: true,
                data: {!! json_encode($chartStatus) !!}
            }]
        });
        @endif
        @if(isset($chartRisk) && $chartRisk!=[])
        Highcharts.chart('piechart-risk', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: 'none',
            tooltip: {
                pointFormat:
                jsonLang['Số lượng']  +  ' : <b>{point.y}</b><br/>'+
                jsonLang['Tỉ lệ']  +  ': <b>{point.percentage:.1f}%</b><br/>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Tỉ lệ',
                colorByPoint: true,
                data: {!! json_encode($chartRisk) !!}
            }]
        });
        @endif
        @if(isset($chartManager) && $chartManager!=[])
        Highcharts.chart('barchart-manager', {
            chart: {
                type: 'bar'
            },
            title: 'none',
            xAxis: {
                categories: {!! json_encode($chartManager['categories']) !!}
            },
            yAxis: {
                min: 0,
                title: 'none'
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: 'none'
                }
            },
            series: {!! json_encode($chartManager['series']) !!}
        });
        @endif
        @if(isset($chartDepartment) && $chartDepartment!=[])
        Highcharts.chart('barchart-department', {
            chart: {
                type: 'bar'
            },
            title: 'none',
            xAxis: {
                categories: {!! json_encode($chartDepartment['categories']) !!}
            },
            yAxis: {
                min: 0,
                title: 'none'
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: 'none'
                }
            },
            series: {!! json_encode($chartDepartment['series']) !!}
        });
        @endif
        @if(isset($chartBudget) && $chartBudget!=[])
        Highcharts.chart('barchart-budget', {
            chart: {
                type: 'bar'
            },
            title: 'none',
            subtitle: 'none',
            xAxis: {
                categories: {!! json_encode($chartBudget['categories']) !!},
                title: {
                    text: null
                },
            },
            yAxis: {
                min: 0,
                title: {
                    text: jsonLang['Số tiền (VNĐ)'],
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: jsonLang['VNĐ'],
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: false
                    }
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                floating: true,
                reversed: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            },
            credits: {
                enabled: false
            },
            series: {!! json_encode($chartBudget['series']) !!}
        });
        @endif

        @if(isset($chartResource) && $chartResource!=[])
        Highcharts.chart('barchart-resource', {
            chart: {
                type: 'bar'
            },
            title: 'none',
            xAxis: {
                categories:{!! json_encode($chartResource['categories'] )!!}
            },
            yAxis: {
                min: 0,
                title: 'none'
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: 'none'
                }
            },
            series: {!! json_encode($chartResource['series']) !!}
        });
        @endif
        @if(isset($projectLongTimeInactive) && $projectLongTimeInactive!=[])
        Highcharts.chart('barchart-inactive', {
            chart: {
                type: 'bar'
            },
            title: 'none',
            subtitle: 'none',
            xAxis: {
                categories: {!! json_encode($projectLongTimeInactive['categories']) !!},
                title: {
                    text: null
                },
            },
            yAxis: {
                min: 0,
                title: {
                    text: jsonLang['Ngày'],
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: jsonLang[' Ngày']
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: false
                    }
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            },
            credits: {
                enabled: false
            },
            series: [{
                name: jsonLang['Thời gian'],
                data: {!! json_encode($projectLongTimeInactive['series']) !!}
            }]
        });
        @endif

    </script>

@stop
