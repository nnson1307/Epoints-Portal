@extends('layout')
@section('title_header')

@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        {{__('TẠO THÔNG BÁO MỚI')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-add">
            {!! csrf_field() !!}
            <div class="m-portlet__body">
                <h5 class="m-section__heading">{{__('Thông tin người nhận')}}</h5>
                <div class="m-form__group form-group row">
                    <label class="col-3 col-form-label">{{__('Người nhận')}}: <b class="text-danger">*</b> </label>
                    <div class="col-9">
                        <div class="m-radio-list">
                            <label class="m-radio m-radio--success">
                                <input type="radio" name="send_to" value="all" checked> {{__('Gửi tất cả')}}
                                <span></span>
                            </label>
                            <label class="m-radio m-radio--success">
                                <input type="radio" name="send_to" value="group"> {{__('Gửi cho một tập khách hàng tùy chọn')}}
                                <span></span>
                            </label>
                        </div>
                        <div id="cover-group" style="display: none;">
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-4">
                                    <button type="button" class="btn btn-brand btn-bold color_button " onclick="handleClickGroup()">
                                        {{__('Chọn nhóm khách hàng')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="m-section__heading">{{__('Nội dung thông báo')}}</h5>
                <div class="m-form__group form-group row">
                    <label class="col-lg-3 col-form-label">Background:</label>
                    <div class="col-lg-2">
                        <div class="form-group m-form__group m-widget19">
                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                     src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
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
                <div class="m-form__group form-group row">
                    <label class="col-3 col-form-label">{{__('Nhóm nội dung')}}: <b class="text-danger">*</b> </label>
                    <div class="col-9">
                        <select class="form-control" name="action_group">
                            <option value="1">{{__('Hành động')}}</option>
                            <option value="0">{{__('Không hành động')}}</option>
                        </select>
                    </div>
                </div>
                <div class="m-form__group form-group row">
                    <label class="col-3 col-form-label">{{__('Tiêu đề thông báo')}}: <b class="text-danger">*</b> </label>
                    <div class="col-9">
                        <input class="form-control" name="title" type="text"
                               value="{{old('title')}}" placeholder="{{__('Tiêu đề thông báo')}}">
                        @if ($errors->has('title'))
                            <div class="form-control-feedback">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
                </div>
                <div class="m-form__group form-group row">
                    <label class="col-3 col-form-label">{{__('Tiêu đề hiển thị ngắn')}}: <b class="text-danger">*</b> </label>
                    <div class="col-9">
                        <input class="form-control" name="short_title" type="text"
                               value="{{old('short_title')}}"
                               placeholder="{{__('Tiêu đề ngắn hiển thị trên trang danh sách thông báo')}}...">
                        @if ($errors->has('short_title'))
                            <div class="form-control-feedback">{{ $errors->first('short_title') }}</div>
                        @endif
                    </div>
                </div>
                <div class="m-form__group form-group row">
                    <label class="col-3 col-form-label">{{__('Thông tin nổi bật của thông báo')}}: <b class="text-danger">*</b>
                    </label>
                    <div class="col-9">
                        <textarea class="form-control" name="feature" rows="5">{{ old('feature') }}</textarea>
                        @if ($errors->has('feature'))
                            <div class="form-control-feedback">{{ $errors->first('feature') }}</div>
                        @endif
                    </div>
                </div>
                <div class="m-form__group form-group row">
                    <label class="col-3 col-form-label">{{__('Chi tiết thông báo')}}: <b class="text-danger">*</b> </label>
                    <div class="col-9">
                        <textarea class="form-control" id="content-notification" name="content"
                                  rows="5">{{ old('content') }}</textarea>
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
                                       onchange="script.changeCreateDeal();"
                                       class="manager-btn">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="form-m-form__group form-group row" id="popup_create_deal" hidden>
                    <label class="col-3 black_title">
                        @lang('Thông tin deal'):<b class="text-danger">*</b>
                    </label>
                    <div class="col-9">
                    <a href="javascript:void(0)" onclick="script.popupCreateDeal()" class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                        <i class="la la-plus"></i>@lang('Thêm thông tin deal')</a>
                    </div>
                </div>
                <div id="cover-action">
                    <h5 class="m-section__heading">{{__('Tùy chọn hành động')}}</h5>
                    <div class="m-form__group form-group row">
                        <label class="col-3 col-form-label">{{__('Tên hành động')}}: <b class="text-danger">*</b> </label>
                        <div class="col-9">
                            <input class="form-control" name="action_name" type="text" id="action_name"
                                   value="{{old('action_name')}}"
                                   placeholder="{{__('Nhập tên hành động')}}...">
                            @if ($errors->has('action_name'))
                                <div class="form-control-feedback">{{ $errors->first('action_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label">{{__('Đích đến')}}: <b class="text-danger">*</b></label>
                        <div class="col-9">
                            <select class="form-control" id="end_point" name="end_point">
                                @foreach($notiTypeList as $notiType)
                                    <option value="{{ $notiType['action'] }}" data-id="{{ $notiType['id'] }}" is-detail="{{ $notiType['is_detail'] }}" data-type="{{ $notiType['detail_type'] }}">{{ $notiType['type_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="end-point-detail" style="display: none;">
                        <label class="col-3 col-form-label">{{__('Đích đến chi tiết')}}: <b class="text-danger">*</b></label>
                        <div class="col-9">
                            <input class="form-control" name="end_point_detail_click" id="end_point_detail_click" type="text" onclick="handleClick()"
                                   placeholder="@lang('admin::notification.create.form.placeholder.END_POINT_DETAIL')" readonly>
                            <input class="form-control" name="end_point_detail" type="hidden">
                            <input class="form-control" name="is_detail" type="hidden">
                            <input class="form-control" name="notification_type_id" type="hidden">
                        </div>
                    </div>
                </div>
                <h5 class="m-section__heading">{{__('Lịch gửi thông báo')}}</h5>
                <div class="m-form__group form-group row">
                    <label class="col-lg-3 col-form-label">{{__('Thời gian gửi thông báo')}}: <b class="text-danger">*</b></label>
                    <div class="col-lg-9">
                        <div class="m-radio-list">
                            <label class="m-radio m-radio--success">
                                <input type="radio" name="send_time_radio" value="0" checked> @lang('admin::notification.create.form.SEND_NOW')
                                <span></span>
                            </label>
                            <label class="m-radio m-radio--success">
                                <input type="radio" name="send_time_radio" value="1"> @lang('admin::notification.create.form.SEND_SCHEDULE')
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
                                        <option value="non_specific_time">
                                            @lang('admin::notification.create.form.NON_SPECIFIC_TIME')
                                        </option>
                                    </select>
                                </div>
                                <div class="col-xl-7">
                                    <div class="input-group date" id="specific_time_display">
                                        <input type="text" class="form-control" name="specific_time"
                                               placeholder="@lang('admin::notification.create.form.placeholder.SPECIFIC_TIME')" id="specific_time">
                                        <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-check glyphicon-th"></i>
                                                                    </span>
                                        </div>
                                    </div>
                                    <div id="non_specific_time_display" style="display: none;">
                                        <div class="row">
                                            <div class="col-xl-7">
                                                <input type="text"
                                                       class="form-control"
                                                       name="non_specific_time"
                                                       placeholder="@lang('admin::notification.create.form.placeholder.NON_SPECIFIC_TIME')"
                                                       id="non_specific_time">
                                            </div>
                                            <div class="col-xl-5">
                                                <select class="form-control" name="time_type">
                                                    <option value="hour">
                                                        @lang('admin::notification.create.form.HOUR')
                                                    </option>
                                                    <option value="minute">
                                                        @lang('admin::notification.create.form.MINUTE')
                                                    </option>
                                                    <option value="day">
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
                        <button type="button" onclick="script.submit_add(1)"
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
        </form>
    </div>
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
    <script type="text/javascript">
        // Summernote.generate('content-notification');
        // Summernote.uploadImg('content-notification');

        new AutoNumeric.multiple('#cost', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0
        });
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
                <a href="javascript:void(0)" onclick="dealNoti.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop
