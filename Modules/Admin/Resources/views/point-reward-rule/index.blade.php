@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <style>
        .ss--button-cms-piospa {
            color: #fff;
            background-color: #4fc4cb;
            border-color: #4fc4cb;
            font-weight: 500;
            font-size: 0.8rem !important;
        }
        .m-checkbox.ss--m-checkbox--state-success.m-checkbox--solid > span {
            background: #1aa203c !important;
        }
        .m-checkbox.ss--m-checkbox--state-success.m-checkbox--solid > span:after {
             border: 1px solid #ffffff;
         }

        .m-checkbox.ss--m-checkbox--state-success.m-checkbox--solid > input:focus ~ span {
            border: 1px solid transparent !important;
        }

        .m-checkbox.ss--m-checkbox--state-success.m-checkbox--solid > input:checked ~ span {
            background: #1aa203;
        }
        .ss--background-config-sms {
            background-color: #f8f8f8;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" style="height: 20px;">
        {{__('QUY TẮC ĐIỂM THƯỞNG')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm m-portlet--tabs m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-calendar-1"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('QUY TẮC ĐIỂM THƯỞNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand  m-tabs-line--right m-tabs-line-danger" role="tablist">
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link active" data-toggle="tab" href="#purchase" role="tab">
                            {{__('Mua hàng')}}
                        </a>
                    </li>

                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#event" role="tab">
                            {{__('Hoạt động khách hàng')}}
                        </a>
                    </li>

                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#config" role="tab">
                            {{__('Cấu hình chung')}}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="tab-content">
                <div class="tab-pane active" id="purchase" role="tabpanel">
                    @include('admin::point-reward-rule.include.purchase')
                </div>
                <div class="tab-pane " id="event" role="tabpanel">
                    @include('admin::point-reward-rule.include.event')
                </div>
                <div class="tab-pane " id="config" role="tabpanel">
                    @include('admin::point-reward-rule.include.config')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_script')
    <script type="text/template" id="tpl-input-mask">
        <input type="text" class="form-control point_value input-mask"
               value="" >
    </script>
    <script type="text/template" id="tpl-numeric">
        <input type="text" class="form-control point_value numeric"
               value="" >
    </script>
    <script src="{{asset('static/backend/js/admin/general/jquery.mask.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/numeric.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/point-reward-rule/script.js?v='.time())}}"
            type="text/javascript"></script>
@stop