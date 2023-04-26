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
                        {{__('admin::notification.edit.EDIT_NOTIFICATION')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
    @if (isset($noti) && $noti['is_brand'] == 0)
        <form id="form-edit">
            <div class="m-portlet__body">
            <div class="kt-content  kt-grid__item kt-grid__item--fluid fix-margin" id="kt_content">
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
                                                        @lang('Thông tin người nhận')
                                                    </h3>
                                                </div>
                                            </div>
                                            <input type="hidden" id="noti-id" value="{{ $noti['notification_detail_id'] }}">
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">
                                                    @lang('Người nhận'):
                                                    <span class="color_red">*</span></label>
{{--                                                <div class="m-form__group form-group row">--}}
{{--                                                    <label class="col-3 col-form-label">{{__('Người nhận')}}: <b class="text-danger">*</b> </label>--}}
{{--                                                    <div class="col-9">--}}
{{--                                                        <div class="m-radio-list">--}}
{{--                                                            <label class="m-radio m-radio--success">--}}
{{--                                                                <input type="radio" name="send_to" value="all" checked> {{__('Gửi tất cả')}}--}}
{{--                                                                <span></span>--}}
{{--                                                            </label>--}}
{{--                                                            <label class="m-radio m-radio--success">--}}
{{--                                                                <input type="radio" name="send_to" {{ $noti['template']['from_type'] == 'group' ? 'checked' : null }} value="group"> {{__('Gửi cho một tập khách hàng tùy chọn')}}--}}
{{--                                                                <span></span>--}}
{{--                                                            </label>--}}
{{--                                                        </div>--}}
{{--                                                        <div id="cover-group" style="display: none;">--}}
{{--                                                            @if($noti['template']['from_type'] == 'group')--}}
{{--                                                                <div class="kt-section__content kt-section__content--solid-- col-6 div-group" id="{{ $group->id }}" style="margin-top: 10px;">--}}
{{--                                                                    <div class="kt-searchbar">--}}
{{--                                                                        <div class="input-group">--}}
{{--                                                                            <div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fa fa-edit"></i></span></div>--}}
{{--                                                                            <input type="text" class="form-control group-name" readonly="readonly" value="{{ $group->name }}">--}}
{{--                                                                            <input type="hidden" name="group_id" value="{{ $group->id }}">--}}
{{--                                                                            <div class="input-group-prepend"><span class="input-group-text remove-group" onclick="removeGroup({{ $group->id }})" id="basic-addon1" style="cursor: pointer;">X</span></div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            @endif--}}
{{--                                                            <div class="row" style="margin-top: 10px;">--}}
{{--                                                                <div class="col-4">--}}
{{--                                                                    <button type="button" class="btn btn-brand btn-bold color_button " onclick="handleClickGroup()">--}}
{{--                                                                        {{__('Chọn nhóm khách hàng')}}--}}
{{--                                                                    </button>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="m-radio-list">
                                                        <label class="m-radio m-radio--success">
                                                            <input type="radio" name="send_to" value="all" checked>
                                                            @lang('Gửi tất cả')
                                                            <span></span>
                                                        </label>
                                                        <label class="m-radio m-radio--success">
                                                            <input type="radio" name="send_to" value="group"
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
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-4">
                                                                <button type="button" class="btn btn-brand btn-bold color_button" onclick="handleClickGroup()">
                                                                    @lang('admin::notification.create.form.BTN_ADD_SEGMENT')
                                                                </button>
                                                            </div>
                                                        </div>
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
                                            <div class="m-form__group form-group row">
                                                <label class="col-lg-3 col-form-label">Background:</label>
                                                <div class="col-lg-2">
                                                    <div class="form-group m-form__group m-widget19">
                                                        <div class="m-widget19__pic">
                                                            <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                                                 src="{{$noti['background'] != '' ? $noti['background'] :'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                                                                 alt="{{__('Hình ảnh')}}"/>
                                                        </div>
                                                        <input type="hidden" id="background" name="background">
                                                        <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                                               data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                                               id="getFile" type='file'
                                                               onchange="script.uploadAvatar(this);"
                                                               class="form-control"
                                                               style="display:none"/>
                                                        <div class="m-widget19__action" style="max-width: 170px">
                                                            <a href="javascript:void(0)"
                                                               onclick="document.getElementById('getFile').click()"
                                                               class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    {{__('Tải ảnh lên')}}
                                                </span>
                                            </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">
                                                    @lang('admin::notification.create.form.CONTENT_GROUP'):
                                                    <span class="color_red">*</span></label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <select class="form-control" name="action_group">
                                                        <option value="1">@lang('admin::notification.create.form.ACTION_GROUP.ACTION')</option>
                                                        <option value="0" {{ $noti['template']['action_group'] == 0 ? 'selected' : null}}>@lang('admin::notification.create.form.ACTION_GROUP.NON_ACTION')</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">
                                                    @lang('admin::notification.create.form.TITLE'):
                                                    <span class="color_red">*</span></label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input class="form-control" name="title" type="text"
                                                           value="{{ $noti['template']['title'] }}" placeholder="@lang('admin::notification.create.form.placeholder.TITLE')">
                                                    @if ($errors->has('title'))
                                                        <div class="form-control-feedback">{{ $errors->first('title') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">
                                                    @lang('admin::notification.create.form.SHORT_TITLE'):
                                                    <span class="color_red">*</span></label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input class="form-control" name="short_title" type="text"
                                                           value="{{ $noti['template']['title_short'] }}" placeholder="@lang('admin::notification.create.form.placeholder.SHORT_TITLE')">
                                                    @if ($errors->has('short_title'))
                                                        <div class="form-control-feedback">{{ $errors->first('short_title') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">
                                                    @lang('admin::notification.create.form.FEATURE'):
                                                    <span class="color_red">*</span></label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <textarea class="form-control" name="feature" rows="5">{{ $noti['template']['description'] }}</textarea>
                                                    @if ($errors->has('feature'))
                                                        <div class="form-control-feedback">{{ $errors->first('feature') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">
                                                    @lang('admin::notification.create.form.CONTENT'):
                                                    <span class="color_red">*</span></label>
                                                <div class="col-lg-9 col-xl-6">
                                                <textarea class="form-control" id="content-notification" name="content" rows="5">
                                                    {{ $noti['content'] }}
                                                </textarea>
                                                    @if ($errors->has('content'))
                                                        <div class="form-control-feedback">{{ $errors->first('content') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-m-form__group form-group row">
                                                <label class="col-3 black-title">{{__('Chi phí chiến dịch')}}:<b class="text-danger">*</b></label>
                                                <div class="input-group m-input-group col-9">
                                                    <input name="cost" id="cost"
                                                           class="form-control m-input class"
                                                           placeholder="{{__('Hãy nhập chi phí cho chiến dịch')}}"
                                                           value="{{number_format($noti['template']['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                                           aria-describedby="basic-addon1">
                                                    @if ($errors->has('cost'))
                                                        <div class="form-control-feedback">{{ $errors->first('cost') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-m-form__group form-group row">
                                                <label class="col-3 black_title">
                                                    @lang('Cho phép tạo deal'):<b class="text-danger">*</b>
                                                </label>
                                                <div class="col-9">
                                                     <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                        <label>
                                                            <input type="checkbox" id="is_deal_created" name="is_deal_created"
                                                                   onchange="edit.changeCreateDeal();"
                                                                   {{$noti['template']['is_deal_created'] == 1 ? 'checked' : ''}}
                                                                   class="manager-btn">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-m-form__group form-group row" id="popup_create_deal" {{$noti['template']['is_deal_created'] == 0 ? 'hidden' : ''}}>
                                                <label class="col-3 black_title">
                                                    @lang('Thông tin deal'):<b class="text-danger">*</b>
                                                </label>
                                                <div class="col-9">
                                                    <a href="javascript:void(0)" onclick="edit.popupEditLead({{$noti['template']['notification_template_id']}})" class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                                        <i class="la la-plus"></i>@lang('Thêm thông tin deal')</a>
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
                                                        <span class="color_red">*</span></label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <input class="form-control" name="action_name" type="text" id="action_name"
                                                               value="{{ $noti['template']['action_name'] }}" placeholder="@lang('admin::notification.create.form.placeholder.ACTION_NAME')">
                                                        @if ($errors->has('action_name'))
                                                            <div class="form-control-feedback">{{ $errors->first('action_name') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">
                                                        @lang('admin::notification.create.form.END_POINT'):
                                                        <span class="color_red">*</span></label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <select class="form-control" id="end_point" name="end_point">
                                                            @foreach($notiTypeList as $notiType)
                                                                @if($notiType['from'] == 'backoffice' || $notiType['from'] == 'all')
                                                                    <option value="{{ $notiType['action'] }}" data-type="{{ $notiType['detail_type'] }}" data-id="{{ $notiType['id'] }}" is-detail="{{ $notiType['is_detail'] }}"
                                                                            {{ $noti['action'] == $notiType['action'] ? 'selected' : null }}
                                                                    >
                                                                        {{ $notiType['type_name'] }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{--                                            @if($noti['action_params'] != null)--}}
                                                <div class="form-group row" id="end-point-detail" style="display: none;">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">
                                                        @lang('admin::notification.create.form.END_POINT_DETAIL'):
                                                        <span class="color_red">*</span></label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <input class="form-control" name="end_point_detail_click" type="text" id="end_point_detail_click" onclick="handleClick()"
                                                               value="{{$object['object_name']}}"
                                                               placeholder="@lang('admin::notification.create.form.placeholder.END_POINT_DETAIL')">
                                                        <input class="form-control" name="end_point_detail" type="hidden" value="{{$object['object_id']}}">
                                                        <input class="form-control" name="is_detail" value="@if($noti['action_params'] != null) 1 @else 0 @endif" type="hidden">
                                                        <input class="form-control" name="notification_type_id" type="hidden" value="{{$noti['template']['notification_type_id']}}">
                                                    </div>
                                                </div>
                                                {{--@endif--}}
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
                                                    <span class="color_red">*</span></label>

                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="m-radio-list">
                                                        <label class="m-radio m-radio--success">
                                                            <input type="radio" name="send_time_radio" value="0" checked>
                                                            @lang('admin::notification.create.form.SEND_NOW')
                                                            <span></span>
                                                        </label>
                                                        <label class="m-radio m-radio--success">
                                                            <input type="radio" name="send_time_radio" value="1"
                                                                   @if($noti['template']['send_type'] == 'schedule') checked @endif
                                                            >
                                                            @lang('admin::notification.create.form.SEND_SCHEDULE')
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <div id="schedule-time" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-xl-5">
                                                                <select class="form-control" name="schedule_time">
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
                                                            <div class="col-xl-7">
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
                                                                    >
                                                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-check glyphicon-th"></i>
                                                                    </span>
                                                                    </div>
                                                                </div>

                                                                <div id="non_specific_time_display" @if($noti['template']['schedule_option'] != 'none') style="display: none;" @endif>
                                                                    <div class="row">
                                                                        <div class="col-xl-5">
                                                                            <input type="text" class="form-control"
                                                                                   name="non_specific_time"
                                                                                   value="{{ $noti['template']['schedule_value'] }}"
                                                                                   placeholder="@lang('admin::notification.create.form.placeholder.NON_SPECIFIC_TIME')"
                                                                                   id="non_specific_time"
                                                                            >
                                                                        </div>
                                                                        <div class="col-xl-7">
                                                                            <select class="form-control" name="time_type">
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
            </div>

            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.notification')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>
                        <button type="button" onclick="script.submit_edit(1)"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>

            <div id="my-modal-create">
            </div>
            <input type="hidden" id="load-modal-create" value="0">
            <div id="my-modal-edit">
            </div>
            <input type="hidden" id="load-modal-edit" value="0">
            <input type="hidden" id="switch_deal_created" value="0">
        </form>

    </div>
    @endif

    {{-- Modal action--}}
    <div id="end-point-modal"></div>
    <div id="group-modal"></div>
@endsection

@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{ asset('static/backend/js/notification/notification/script.js') }}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/notification/notification/group.js')}}"
            type="text/javascript"></script>
    <script>
        @if($group != null)
            var groupId = '{{ $group->id }}';
        @endif
                // trigger click
        @if($noti['template']['is_deal_created'] == 1)
        $.getJSON(laroute.route('translate'), function (json) {
            $('#my-modal-create').html('');
            $.ajax({
                url: laroute.route('admin.notification.noti-popup-edit-deal'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'notification_template_id': {{$noti['template']['notification_template_id']}}
                },
                success: function (res) {
                    $('#my-modal-edit').html(res.html);

                    $('#pipeline_code').select2({
                        placeholder: json['Chọn pipeline']
                    });

                    $('#journey_code').select2({
                        placeholder: json['Chọn hành trình']
                    });


                    $(".object_quantity").TouchSpin({
                        initval: 1,
                        min: 1,
                        buttondown_class: "btn btn-default down btn-ct",
                        buttonup_class: "btn btn-default up btn-ct"

                    });
                    $("#end_date_expected").datepicker({
                        todayHighlight: !0,
                        autoclose: !0,
                        format: "dd/mm/yyyy",
                        // minDate: new Date(),
                    });
                    $('#pipeline_code').change(function () {
                        $.ajax({
                            url: laroute.route('customer-lead.load-option-journey'),
                            dataType: 'JSON',
                            data: {
                                pipeline_code: $('#pipeline_code').val(),
                            },
                            method: 'POST',
                            success: function (res) {
                                $('.journey').empty();
                                $.map(res.optionJourney, function (a) {
                                    $('.journey').append('<option value="' + a.journey_code + '">' + a.journey_name + '</option>');
                                });
                            }
                        });
                    });

                    new AutoNumeric.multiple('#amount', {
                        currencySymbol: '',
                        decimalCharacter: '.',
                        digitGroupSeparator: ',',
                        decimalPlaces: decimal_number,
                        eventIsCancelable: true,
                        minimumValue: 0
                    });
                }
            });
        });
        @endif
        @if($noti['action'] == 'brand') var brandId = '{{ json_decode($noti['action_params'], true)['brand_id'] }}'
        @elseif($noti['action'] == 'faq') var faqId = '{{ json_decode($noti['action_params'], true)['faq_id'] }}' @endif
    </script>
    <script type="text/template" id="image-tpl">
        <div class="kt-avatar__holder" style="background-image: url({link});background-position: center;"></div>
    </script>
    <script type="text/javascript">
        // Summernote.generate('content-notification');
        // Summernote.uploadImg('content-notification');
        $('#end_point').select2();
        $('#content-notification').summernote({
            height: 150,
            placeholder: '{{__('Nhập nội dung')}}...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });
    </script>
    <script type="text/javascript">
        Summernote.generate('content-notification');
        Summernote.uploadImg('content-notification');
    </script>
    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="dealNoti.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25%;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="dealNoti.changeObject(this)">
                    <option></option>
                </select>
                <span class="error_object color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input object_price" name="object_price" style="background-color: white;"
                       id="object_price_{stt}" value="" readonly>
                <input type="hidden" class="object_id" name="object_id">
            </td>
            <td style="width: 9%">
                <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity"
                       id="object_quantity_{stt}" style="text-align: center" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_discount" name="object_discount"
                       id="object_discount_{stt}" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_amount" name="object_amount" style="background-color: white;"
                       id="object_amount_{stt}" value="" readonly>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="dealEmail.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop
