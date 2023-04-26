@extends('layout')
@section('title_header')

@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-institution"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CẤU HÌNH THÔNG BÁO NHÂN VIÊN TỰ ĐỘNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            @if(count($dataGroup) > 0)
                @foreach($dataGroup as $item)
                    <h3 class="m--font-success">{{$item['config_notification_group_name']}}</h3>
                    @if(isset($dataConfig[$item['config_notification_group_id']]) && $dataConfig[$item['config_notification_group_id']] > 0)
                        @foreach($dataConfig[$item['config_notification_group_id']] as $v)

                            <div class="m-widget4 form-group">
                                <div class="m-widget4__item ss--background-config-sms">
                                    <div class="m-widget4__checkbox  m--margin-left-15">
                                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                            <input type="checkbox" {{$v['is_active'] == 1 ? 'checked' : ''}}
                                            onchange="index.changeStatus('{{$v['key']}}', this)">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="m-widget4__info">
                                        <div class="row">
                                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                        {{$v['name']}}
                                </span><br>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-11">
                                                        <label class="sz_sms">{{__('Nội dung thông báo')}}</label>

                                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="3"
                                                                  name="message-new-calendar"
                                                                  id="message-new-calendar"
                                                                  class="form-control m-input ss--background-color">{{$v['message']}}</textarea>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <a href="{{route('config-staff.edit', $v['key'])}}"
                                                           style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                                    class="la la-edit"></i></a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/notification/config-staff/script.js')}}" type="text/javascript"></script>
@stop
