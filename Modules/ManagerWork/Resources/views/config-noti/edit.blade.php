@extends('layout')
@section('title_header')

@endsection
@section('content')
    <style>
        .m-radio > span, .m-checkbox > span {
            top: -5px;
        }
        .nav-tabs .nav-item:hover , .fa-plus-circle:hover , .kt-checkbox input:hover{
            cursor: pointer;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link.active {
            color:#6f727d !important;
            border-bottom: #6f727d !important;;
            background: #EEF3F9 !important;;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link {
            padding: 15px;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-institution"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('managerwork::managerwork.config_notification_auto') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                   onclick="Notification.updateConfigNoti()"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
							<span> {{ __('managerwork::managerwork.update') }}</span>
                        </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body p-0">
            <ul class="nav nav-tabs nav-pills mb-3" role="tablist" style="margin-bottom: 0;">
                <li class="nav-item">
                    <a href="{{route('config')}}" class="nav-link">{{ __('managerwork::managerwork.general_configuration') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" {{route('manager-work.manage-config.notification')}}>{{ __('managerwork::managerwork.work') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('config-noti')}}">{{__('Chấm công')}}</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('config',['tab' => 'contract'])}}" class="nav-link">{{__('Hợp đồng')}}</a>
                </li>
            </ul>

            <form id="form-config-noti" style="padding: 2.2rem 2.2rem;">
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table ss--nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('managerwork::managerwork.notice_items') }}</th>
                            <th class="text-center" colspan="2">{{ __('managerwork::managerwork.notification_form') }}</th>
                            <th class="text-center">{{ __('managerwork::managerwork.content_notification') }}</th>
                            <th class="text-center"></th>
                            <th class="text-center">{{ __('managerwork::managerwork.staff_created') }}</th>
                            <th class="text-center">{{ __('managerwork::managerwork.staff_processor') }}</th>
                            <th class="text-center">{{ __('managerwork::managerwork.staff_monitor') }}</th>
                            <th class="text-center">{{ __('managerwork::managerwork.staff_approve') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($listNotiConfig as $key => $item)
                            <tr class="block_{{$item['manage_config_notification_id']}}">
                                <input type="hidden" id="manage_config_notification_id" name="noti[{{$item['manage_config_notification_id']}}][manage_config_notification_id]" value="{{$item['manage_config_notification_id']}}">
                                <input type="hidden" id="manage_config_notification_message" name="noti[{{$item['manage_config_notification_id']}}][manage_config_notification_message]" value="{{$item['manage_config_notification_message']}}">
                                <input type="hidden" id="manage_config_notification_title" name="noti[{{$item['manage_config_notification_id']}}][manage_config_notification_title]" value="{{$item['manage_config_notification_title']}}">
                                <td>{{$key + 1}}</td>
                                <td class="title">{{$item['manage_config_notification_title']}}</td>
                                <td>
                                    <label class="m-checkbox m-checkbox--state-success mt-0">
                                        <input type="checkbox" name="noti[{{$item['manage_config_notification_id']}}][is_mail]" {{$item['is_mail'] == 1 ? 'checked' : ''}} value="1">
                                        <span></span>
                                    </label>
                                    <i class="fas fa-envelope"></i>
                                </td>
                                <td>
                                    <label class="m-checkbox m-checkbox--state-success mt-0">
                                        <input type="checkbox" name="noti[{{$item['manage_config_notification_id']}}][is_noti]" {{$item['is_noti'] == 1 ? 'checked' : ''}} value="1">
                                        <span></span>
                                    </label>
                                    <i class="fas fa-bell"></i>
                                </td>
                                <td class="message">
                                    {{$item['manage_config_notification_message']}}
                                </td>
                                <td>
                                    <a href="javascript:void(0)" onclick="Notification.editMessage({{$item['manage_config_notification_id']}})">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </td>
                                <td>
                                    <label class="m-checkbox m-checkbox--state-success mt-0">
                                        <input type="checkbox" name="noti[{{$item['manage_config_notification_id']}}][is_created]" {{$item['is_created'] == 1 ? 'checked' : ''}} value="1">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="m-checkbox m-checkbox--state-success mt-0">
                                        <input type="checkbox" name="noti[{{$item['manage_config_notification_id']}}][is_processor]" {{$item['is_processor'] == 1 ? 'checked' : ''}} value="1">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="m-checkbox m-checkbox--state-success mt-0">
                                        <input type="checkbox" name="noti[{{$item['manage_config_notification_id']}}][is_support]" {{$item['is_support'] == 1 ? 'checked' : ''}} value="1">
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="m-checkbox m-checkbox--state-success mt-0">
                                        <input type="checkbox" name="noti[{{$item['manage_config_notification_id']}}][is_approve]" {{$item['is_approve'] == 1 ? 'checked' : ''}} value="1">
                                        <span></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="append-popup"></div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/manager-work/manage-config/script-noti.js?v='.time())}}" type="text/javascript"></script>
@stop