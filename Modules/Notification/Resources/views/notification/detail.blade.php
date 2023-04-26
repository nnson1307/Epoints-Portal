@extends('layout')

@section('header')
    @include('components.header',['title'=> __('user.user.create.CREATE_ACCOUNT')])
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('admin::notification.detail.DETAIL_NOTIFICATION')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>

        <div class="m-portlet__body">
            @if (isset($noti) && $noti['is_brand'] == 0)
                <form id="form-edit">
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
                        <div class="kt-portlet kt-portlet--tabs">
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="kt_apps_user_edit_tab_1" role="tabpanel">
                                        <div class="kt-form__body">
                                            <div class="kt-section kt-section--first">
                                                <div class="kt-section__body">
                                                    <div class="row">
                                                        <div class="col-lg-9 col-xl-6">
                                                            <h3 class="kt-section__title kt-section__title-sm">
                                                                @lang('admin::notification.create.form.header.INFO_RECEIVER')
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="noti-id" value="{{ $noti['notification_detail_id'] }}">
                                                    <div class="form-group row m-form__group">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('Người nhận'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="m-radio-list">
                                                                <label class="m-radio m-radio--success">
                                                                    <input type="radio" name="send_to" value="all" checked disabled>
                                                                    @lang('Gửi tất cả')
                                                                    <span></span>
                                                                </label>
                                                                <label class="m-radio m-radio--success">
                                                                    <input type="radio" name="send_to" value="group" disabled
                                                                            {{ $noti['template']['from_type'] == 'group' ? 'checked' : null }}
                                                                    >
                                                                    @lang('Gửi cho một tập khách hàng tùy chọn')
                                                                    <span></span>
                                                                </label>
                                                            </div>
                                                            <div id="cover-group" style="display: {{ $noti['template']['from_type'] == 'group' ? 'block' : 'none' }};">
                                                                @if($noti['template']['from_type'] == 'group')
                                                                    <div class="kt-section__content kt-section__content--solid-- col-6 div-group" id="{{ $group->id }}" style="margin-top: 10px;">
                                                                        <div class="kt-searchbar">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control group-name" readonly="readonly" value="{{ $group->name }}">
                                                                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                                                                <div class="input-group-prepend"><span class="input-group-text remove-group" onclick="removeGroup({{ $group->id }})" id="basic-addon1" style="cursor: pointer;">X</span></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-9 col-xl-6">
                                                            <h3 class="kt-section__title kt-section__title-sm">
                                                                @lang('admin::notification.create.form.header.CONTENT')
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('admin::notification.create.form.BACKGROUND'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="kt-avatar kt-avatar--outline" id="kt_user_add_avatar">
                                                                <div id="div-image">
                                                                    <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                                                         src="{{$noti['background'] != '' ? $noti['background'] :'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                                                                         alt="{{__('Hình ảnh')}}"/>
                                                                </div>
                                                                <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title=""
                                                                      data-original-title="Cancel avatar">
                                                    </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('admin::notification.create.form.CONTENT_GROUP'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <select class="form-control" name="action_group" disabled>
                                                                <option value="1">@lang('admin::notification.create.form.ACTION_GROUP.ACTION')</option>
                                                                <option value="0" {{ $noti['template']['action_group'] == 0 ? 'selected' : null}}>
                                                                    @lang('admin::notification.create.form.ACTION_GROUP.NON_ACTION')
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('admin::notification.create.form.TITLE'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <input class="form-control" name="title" type="text" readonly
                                                                   value="{{$noti['template']['title']}}" placeholder="{{__('Tiêu đề thông báo')}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('admin::notification.create.form.SHORT_TITLE'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <input class="form-control" name="title" type="text" readonly
                                                                   value="{{$noti['template']['title_short']}}" placeholder="{{__('Tiêu đề thông báo')}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('admin::notification.create.form.FEATURE'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <input class="form-control" name="title" type="text" readonly
                                                                   value="{{$noti['template']['description']}}" placeholder="{{__('Tiêu đề thông báo')}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('admin::notification.create.form.CONTENT'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <input class="form-control" name="title" type="text" readonly
                                                                   value="{{$noti['content']}}" placeholder="{{__('Tiêu đề thông báo')}}">
                                                        </div>
                                                    </div>
                                                    {{-- ACTION--}}
                                                    <div id="cover-action">
                                                        <div class="row">
                                                            <div class="col-lg-9 col-xl-6">
                                                                <h3 class="kt-section__title kt-section__title-sm">
                                                                    @lang('admin::notification.create.form.header.ACTION')
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">
                                                                @lang('admin::notification.create.form.ACTION_NAME'):
                                                            </label>
                                                            <div class="col-lg-9 col-xl-6">
                                                                <input class="form-control" name="title" type="text" readonly
                                                                       value="{{$noti['template']['action_name']}}" placeholder="{{__('Tiêu đề thông báo')}}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">
                                                                @lang('admin::notification.create.form.END_POINT'):
                                                            </label>
                                                            <div class="col-lg-9 col-xl-6">
                                                                <select class="form-control" id="end_point" name="end_point" disabled>
                                                                    @foreach($notiTypeList as $notiType)
                                                                        <option value="{{ $notiType['action'] }}" data-type="{{ $notiType['detail_type'] }}"
                                                                                {{ $noti['action'] == $notiType['action'] ? 'selected' : null }}
                                                                        >
                                                                            {{ $notiType['type_name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" id="end-point-detail">
                                                            <label class="col-xl-3 col-lg-3 col-form-label">
                                                                @lang('admin::notification.create.form.END_POINT_DETAIL'):
                                                            </label>
                                                            <div class="col-lg-9 col-xl-6">
                                                                <input class="form-control" name="title" type="text" readonly
                                                                       value="{{$object['object_name']}}" placeholder="@lang('admin::notification.create.form.placeholder.END_POINT_DETAIL')">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- END ACTION--}}

                                                    {{-- SEND --}}
                                                    <div class="row">
                                                        <div class="col-lg-9 col-xl-6">
                                                            <h3 class="kt-section__title kt-section__title-sm">
                                                                @lang('admin::notification.create.form.header.SCHEDULE')
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">
                                                            @lang('admin::notification.create.form.SCHEDULE'):
                                                        </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="m-radio-list">
                                                                <label class="m-radio m-radio--success">
                                                                    <input type="radio" name="send_time_radio" value="0" checked disabled>
                                                                    @lang('admin::notification.create.form.SEND_NOW')
                                                                    <span></span>
                                                                </label>
                                                                <label class="m-radio m-radio--success">
                                                                    <input type="radio" name="send_time_radio" value="1" disabled
                                                                           @if($noti['template']['send_type'] == 'schedule') checked @endif
                                                                    >
                                                                    @lang('admin::notification.create.form.SEND_SCHEDULE')
                                                                    <span></span>
                                                                </label>
                                                            </div>
                                                            <div id="schedule-time" style="display: none;">
                                                                <div class="row">
                                                                    <div class="col-xl-6">
                                                                        <select class="form-control" name="schedule_time" disabled>
                                                                            <option value="specific_time">
                                                                                @lang('admin::notification.create.form.SPECIFIC_TIME')
                                                                            </option>
                                                                            <option value="non_specific_time"
                                                                                    @if($noti['template']['schedule_option'] == 'none') selected @endif
                                                                            >
                                                                                @lang('admin::notification.create.form.NON_SPECIFIC_TIME')
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-xl-6">
                                                                        <div class="input-group date" id="specific_time_display"
                                                                             @if($noti['template']['schedule_option'] != null && $noti['template']['schedule_option'] != 'specific')
                                                                             style="display: none;"
                                                                             @else
                                                                             style="display: flex;"
                                                                                @endif
                                                                        >
                                                                            <input type="text" class="form-control"
                                                                                   name="specific_time"
                                                                                   value="{{ $noti['template']['send_at'] }}"
                                                                                   placeholder="@lang('admin::notification.create.form.placeholder.SPECIFIC_TIME')"
                                                                                   id="specific_time"
                                                                                   disabled
                                                                            >
                                                                            <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-check glyphicon-th"></i>
                                                                    </span>
                                                                            </div>
                                                                        </div>

                                                                        <div id="non_specific_time_display" @if($noti['template']['schedule_option'] != 'none') style="display: none;" @endif>
                                                                            <div class="row">
                                                                                <div class="col-xl-6">
                                                                                    <input type="text" class="form-control"
                                                                                           name="non_specific_time"
                                                                                           value="{{ $noti['template']['schedule_value'] }}"
                                                                                           placeholder="@lang('admin::notification.create.form.placeholder.NON_SPECIFIC_TIME')"
                                                                                           id="non_specific_time"
                                                                                           disabled
                                                                                    >
                                                                                </div>
                                                                                <div class="col-xl-6">
                                                                                    <select class="form-control" name="time_type" disabled>
                                                                                        <option value="hour">
                                                                                            @lang('admin::notification.create.form.HOUR')
                                                                                        </option>
                                                                                        <option value="minute"
                                                                                                @if($noti['template']['schedule_value_type'] == 'minute') selected @endif
                                                                                        >
                                                                                            @lang('admin::notification.create.form.MINUTE')
                                                                                        </option>
                                                                                        <option value="day"
                                                                                                @if($noti['template']['schedule_value_type'] == 'day') selected @endif
                                                                                        >
                                                                                            @lang('admin::notification.create.form.DAY')
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- END SEND --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet kt-portlet--tabs">
                            {{--                @include('user::index.include.tabs')--}}
                        </div>
                    </div>
                </form>

                {{-- Modal action--}}
                <div id="end-point-modal"></div>
            @endif
        </div>

        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('admin.notification')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('QUAY LẠI')</span>
                            </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('after_script')
    <script type="text/template" id="image-tpl">
        <div class="kt-avatar__holder" style="background-image: url({link});background-position: center;"></div>
    </script>
    <script src="{{ asset('static/backend/js/admin/notification/editor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/admin/notification/script.js') }}" type="text/javascript"></script>
@stop
