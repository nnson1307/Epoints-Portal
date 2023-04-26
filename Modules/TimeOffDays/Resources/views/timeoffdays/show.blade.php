@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NGÀY PHÉP')}}</span>
@stop

@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        input[type=file] {
            padding: 10px;
            background: #fff;
        }

        .m-widget5 .m-widget5__item .m-widget5__pic > img {
            width: 100%
        }

        .m-image {
            /*padding: 5px;*/
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }
    </style>
    @include('timeoffdays::timeoffdays.pop.modal-image')
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::staff-title.add')
        </div>
    </div>
    <div class="modal fade" id="modalAddPartment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::department.add')
        </div>
    </div>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    
                    <h2 class="m-portlet__head-text">
                        {{$data['time_off_type_name']}}  
                         

                        @if(is_null($data['is_approve']))
                            <span class="m-badge ml-1 m-badge--warning m-badge--wide">@lang('Chờ duyệt')</span>
                        @elseif($data['is_approve'] === 1)
                            <span class="m-badge ml-1 m-badge--success m-badge--wide">@lang('Chấp nhận')</span>
                        @elseif($data['is_approve'] === 0)
                            <span class="m-badge ml-1 m-badge--danger m-badge--wide">@lang('Từ chối')</span>
                        @endif

                    </h2>
                </div>
            </div>
   
        </div>
      
            <div class="m-portlet__body">
                <div class="row">
                    
                    <div class="col-lg-12">
                        
                        <div class="row clearfix">
                            <div class="col-lg-6">
                                <h5 class="m-portlet__head-text">
                                    Thông tin chung
                                </h5>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-4 col-lg-4  black_title">
                                        @lang('Thời gian'):
                                    </label>
                                    <div class="col-lg-8 col-xl-8 div_quota  text-right">
                                        {{$data['time_off_days_start']}} - 
                                        {{$data['time_off_days_end']}}
                                        @if($data['time_off_days_time'])
                                                
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-4 col-lg-4  black_title">
                                        @lang('Lý do'):
                                    </label>
                                    <div class="col-lg-8 col-xl-8 div_quota  text-right">
                                        {{$data['time_off_note']}}
                                    </div>
                                </div>

                                <div class="form-group m-form__group row">
                                    <label class="col-xl-4 col-lg-4  black_title">
                                        @lang('Người nhận thông báo'):
                                    </label>
                                    <div class="col-lg-8 col-xl-8 div_quota text-right">
                                        {{$data['full_name']}}
                                    </div>
                                </div>

                                <div class="form-group m-form__group row">
                                    <label class="col-xl-12 col-lg-12  black_title">
                                        @lang('Người duyệt'):
                                    </label>
                                    <div class="col-lg-12 col-xl-12 div_quota">
                                        <ul class="d-flex flex-row justify-content-between" style="list-style-type: none; padding-left: 0px;">
                                    
                                            @if($staff)
                                                @foreach ($staff as $key => $item)
                                                    @if($key != 0)
                                                        <li class="d-flex flex-column align-self-center color">
                                                            <i class="fa fa-thin fa-arrow-right"></i>
                                                        </li>
                                                    @endif
                                                    <li class="d-flex flex-column align-items-center">
                                                        <img src="{{$item['staff_avatar']}}" onerror="if (this.src != '/static/backend/images/default-placeholder.png') this.src = '/static/backend/images/default-placeholder.png';" class="m--img-rounded m--marginless" alt="photo" width="50px" height="50px">    
                                                        <p class="font-weight-bold  mt-2">{{$item['full_name']}}</p>
                                                        <p>{{$item['staff_title']}}</p>
                                                    </li>
                                                    
                                                @endforeach
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                 
                            </div>
                            <div class="col-lg-6">
                                <h5 class="m-portlet__head-text">
                                    Hoạt động
                                </h5>    
                                <ul class="timeline">
                                    @if($log)
                                        @foreach($log as $item)
                                        <li class="event" data-date="{{\Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y')}}">
                                            <h3>{{$item['time_off_days_title']}}</h3>
                                            <p>{{$item['time_off_days_content']}}</p>
                                        </li>
                                        @endforeach
                                    @endif
                                </ul>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('timeoffdays.index')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        
             

                    </div>
                </div>
            </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <style>
        .alert{
            background-color: #f9fafb;
        }

        .timeline {
    border-left: 2px dotted #2ca189;
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: rgba(114, 124, 245, 0.09);
    margin: 0 auto;
    letter-spacing: 0.2px;
    position: relative;
    line-height: 1.4em;
    font-size: 1.03em;
    padding: 5px;
    padding-left: 10px;
    list-style: none;
    text-align: left;
    max-width: 65%;
}

@media (max-width: 767px) {
    .timeline {
        max-width: 98%;
        padding: 25px;
    }
}

.timeline h1 {
    font-weight: 300;
    font-size: 1.4em;
}

.timeline h2,
.timeline h3 {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 10px;
}

.timeline .event {
    border-bottom: 1px dashed #e8ebf1;
    padding-bottom: 25px;
    margin-bottom: 25px;
    position: relative;
}

@media (max-width: 767px) {
    .timeline .event {
        padding-top: 30px;
    }
}

.timeline .event:last-of-type {
    padding-bottom: 0;
    margin-bottom: 0;
    border: none;
}

.timeline .event:before,
.timeline .event:after {
    position: absolute;
    display: block;
    top: 0;
}

.timeline .event:before {
    left: -160px;
    content: attr(data-date);
    text-align: right;
    font-weight: 100;
    font-size: 0.9em;
    min-width: 120px;
}

@media (max-width: 767px) {
    .timeline .event:before {
        left: 0px;
        text-align: left;
    }
}

.timeline .event:after {
    -webkit-box-shadow: 0 0 0 2px #4fc4ca;
    box-shadow: 0 0 0 2px #4fc4ca;
    left: -15.8px;
    background: #fff;
    border-radius: 50%;
    height: 9px;
    width: 9px;
    content: "";
    top: 5px;
}

@media (max-width: 767px) {
    .timeline .event:after {
        left: -31.8px;
    }
}

.rtl .timeline {
    border-left: 0;
    text-align: right;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
    border-right: 3px solid #727cf5;
}

.rtl .timeline .event::before {
    left: 0;
    right: -170px;
}

.rtl .timeline .event::after {
    left: 0;
    right: -55.8px;
}
    </style>
@stop
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/timeoffdays/script.js?v='.time())}}" type="text/javascript"></script>    

@stop
