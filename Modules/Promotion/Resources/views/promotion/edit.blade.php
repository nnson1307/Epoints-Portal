@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ CHƯƠNG TRÌNH KHUYẾN MÃI')</span>
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
                        @lang('CHỈNH SỬA CHƯƠNG TRÌNH KHUYẾN MÃI')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('fnb.orders',session('routeList')))
                    <a href="{{route('fnb.promotion.edit',['id' => $item['promotion_id']])}}" class="btn ss--button-cms-piospa btn-sm mr-3">
                        {{__('Cập nhật nội dung')}}
                    </a>
                @endif
            </div>
        </div>

        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Tên chương trình'):<b class="text-danger">*</b>
                    </label>
                    <div class="col-lg-9 col-xl-9">
                        <input type="text" class="form-control m-input"
                               id="promotion_name" name="promotion_name" placeholder="@lang('Nhập tên chương trình')"
                               value="{{$item['promotion_name']}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Loại chương trình'):<b class="text-danger">*</b>
                    </label>
                    <div class="col-lg-9 col-xl-9">
                        <div class="m-radio-list">

                            <label class="m-radio m-radio--bold m-radio--state-success">
                                <input type="radio" name="promotion_type" onchange="view.changeType(1)" value="1"
                                        {{$item['promotion_type'] == 1 ? 'checked' : ''}}> @lang('Giảm giá')
                                <span></span>
                            </label>
                            <div class="m-demo discount_value" data-code-preview="true" data-code-html="true"
                                 data-code-js="false"
                                 style="display: {{$item['promotion_type'] == 1 ? 'block': 'none'}};">
                                <div class="m-demo__preview  m-demo__preview--btn">
                                    <div class="m-radio-list">
                                        <label class="form-group m-radio m-radio--check-bold m-radio--state-brand">
                                            <input type="radio" name="promotion_type_value" value="percent"
                                                   {{$item['promotion_type_discount'] == 'percent' ? 'checked' : ''}}
                                                   onchange="view.changeTypeValue('percent')"> @lang('Phần trăm')
                                            <span></span>
                                        </label>
                                        <input type="text" id="promotion_type_discount_value_percent"
                                               class="form-group form-control m-input"
                                               placeholder="@lang('Nhập phần trăm')"
                                               value="{{number_format($item['promotion_type_discount_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                               onchange="view.changePercent(this)">
                                        <label class="form-group m-radio m-radio--check-bold m-radio--state-brand">
                                            <input type="radio" name="promotion_type_value" value="same"
                                                   {{$item['promotion_type_discount'] == 'same' ? 'checked' : ''}}
                                                   onchange="view.changeTypeValue('same')"> @lang('Đồng giá')
                                            <span></span>
                                        </label>
                                        <input type="text" id="promotion_type_discount_value_same"
                                               class="form-group form-control m-input"
                                               placeholder="@lang('Nhập số tiền')"
                                               value="{{number_format($item['promotion_type_discount_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                               onchange="view.changeSamePrice(this)">
                                        <label class="form-group m-radio m-radio--check-bold m-radio--state-brand">
                                            <input type="radio" name="promotion_type_value" value="custom"
                                                   {{$item['promotion_type_discount'] == 'custom' ? 'checked' : ''}}
                                                   onchange="view.changeTypeValue('custom')"> @lang('Tùy chỉnh')
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <label class="m-radio m-radio--bold m-radio--state-success">
                                <input type="radio" name="promotion_type" onchange="view.changeType(2)"
                                       value="2" {{$item['promotion_type'] == 2 ? 'checked' : ''}}> @lang('Quà tặng')
                                <span></span>
                            </label>
{{--                            <label class="m-radio m-radio--bold m-radio--state-success">--}}
{{--                                <input type="radio" name="promotion_type" onchange="view.changeType(3)"--}}
{{--                                       value="3" {{$item['promotion_type'] == 3 ? 'checked' : ''}}> @lang('Tích lũy')--}}
{{--                                <span></span>--}}
{{--                            </label>--}}
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Số lượng khuyến mãi'):
                    </label>
                    <div class="col-lg-9 col-xl-9 div_quota">
                        @if($item['promotion_type'] == 1)
                            <input type="text" class="form-control m-input"
                                   id="quota" name="quota"
                                   value="{{number_format($item['quota'] != null ? $item['quota'] : 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                   placeholder="@lang('Nhập số lượng khuyến mãi')">
                        @elseif ($item['promotion_type'] == 2)
                            <input type="text" class="form-control m-input"
                                   id="quota" name="quota"
                                   value="{{number_format($item['quota'] != null ? $item['quota'] : 0, 0)}}"
                                   placeholder="@lang('Nhập số lượng khuyến mãi')">
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Ngày bắt đầu'):<b class="text-danger">*</b>
                    </label>
                    <div class="col-lg-9 col-xl-9">
                        <div class="input-group date">
                            <input type="text" class="form-control m-input" readonly=""
                                   placeholder="@lang('Ngày bắt đầu')"
                                   id="start_date" name="start_date"
                                   value="{{\Carbon\Carbon::parse($item['start_date'])->format('d/m/Y H:i')}}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Ngày kết thúc'):<b class="text-danger">*</b>
                    </label>
                    <div class="col-lg-9 col-xl-9">
                        <div class="input-group date">
                            <input type="text" class="form-control m-input" readonly=""
                                   placeholder="@lang('Ngày kết thúc')"
                                   id="end_date" name="end_date"
                                   value="{{\Carbon\Carbon::parse($item['end_date'])->format('d/m/Y H:i')}}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Khuyến mãi theo giờ'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group date">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input id="is_time_campaign" name="is_time_campaign" type="checkbox"
                                       {{$item['is_time_campaign'] == 1 ? 'checked' : ''}} onchange="view.changeIsTime(this)">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="div_time">
                    @if($item['is_time_campaign'] == 1)
                        <div class="form-group m-form__group row">
                            <label class="col-xl-3 col-lg-3  black_title">
                                @lang('Giờ khuyến mãi'):
                            </label>
                            <div class="col-lg-9 col-xl-9">
                                <div class="m-radio-list">
                                    <label class="m-radio m-radio--bold m-radio--state-success">
                                        <input type="radio" name="time_type" value="D" onchange="view.changeTime('D')"
                                                {{$item['time_type'] == 'D' ? 'checked' : ''}}> @lang('Hàng ngày')
                                        <span></span>
                                    </label>
                                    <div class="m-demo daily" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        @if($daily != null)
                                            <div class="m-demo__preview  m-demo__preview--btn">
                                                <div class="form-group m-form__group row">
                                                    <label class="col-xl-3 col-lg-3  black_title">
                                                        @lang('Giờ bắt đầu'):
                                                    </label>
                                                    <div class="col-lg-9 col-xl-9">
                                                        <div class="input-group date">
                                                            <input type="text" class="form-control m-input" readonly=""
                                                                   id="start_time"
                                                                   name="start_time" value="{{$daily['start_time']}}"
                                                                   placeholder="@lang('Giờ bắt đầu')">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i
                                                                            class="la la-clock-o"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error_start_time_daily color_red"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-xl-3 col-lg-3  black_title">
                                                        @lang('Giờ kết thúc'):
                                                    </label>
                                                    <div class="col-lg-9 col-xl-9">
                                                        <div class="input-group date">
                                                            <input type="text" class="form-control m-input" readonly=""
                                                                   id="end_time"
                                                                   name="end_time" value="{{$daily['end_time']}}"
                                                                   placeholder="@lang('Giờ kết thúc')">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i
                                                                            class="la la-clock-o"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <label class="m-radio m-radio--bold m-radio--state-success">
                                        <input type="radio" name="time_type" value="W"
                                               onchange="view.changeTime('W')" {{$item['time_type'] == 'W' ? 'checked' : ''}}> @lang('Hàng tuần')
                                        <span></span>
                                    </label>
                                    <div class="m-demo weekly" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        @if($weekly != null)
                                            <div class="m-demo__preview  m-demo__preview--btn">
                                                <div class="form-group m-form__group row">
                                                    <label class="col-xl-3 col-lg-3  black_title">
                                                        @lang('Giờ bắt đầu'):
                                                    </label>
                                                    <div class="col-lg-9 col-xl-9">
                                                        <div class="input-group date">
                                                            <input type="text" class="form-control m-input" readonly=""
                                                                   id="default_start_time" name="default_start_time"
                                                                   value="{{$weekly['default_start_time']}}"
                                                                   placeholder="@lang('Giờ bắt đầu')">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i
                                                                            class="la la-clock-o"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error_default_start_time color_red"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-xl-3 col-lg-3  black_title">
                                                        @lang('Giờ kết thúc'):
                                                    </label>
                                                    <div class="col-lg-9 col-xl-9">
                                                        <div class="input-group date">
                                                            <input type="text" class="form-control m-input" readonly=""
                                                                   id="default_end_time" name="default_end_time"
                                                                   value="{{$weekly['default_end_time']}}"
                                                                   placeholder="@lang('Giờ kết thúc')">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i
                                                                            class="la la-clock-o"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error_default_end_time color_red"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <span>@lang('Giờ khuyến mãi khác')</span>
                                                    <div class="table-responsive">
                                                        <table class="table table-inverse">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="5">
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox"
                                                                               onchange="view.checkAllWeek(this)"> @lang('Cả tuần')
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_monday"
                                                                               name="is_monday"
                                                                               {{$weekly['is_monday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkDay(this, 'Monday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>@lang('Thứ 2')</td>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_other_monday"
                                                                               name="is_other_monday"
                                                                               {{$weekly['is_monday'] == 1 ? '' : 'disabled'}}
                                                                               {{$weekly['is_other_monday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkOther(this, 'Monday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ bắt đầu')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_monday_start_time"
                                                                                       name="is_other_monday_start_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_monday_start_time']}}"
                                                                                       placeholder="@lang('Giờ bắt đầu')" {{$weekly['is_other_monday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_start_time_monday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ kết thúc')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_monday_end_time"
                                                                                       name="is_other_monday_end_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_monday_end_time']}}"
                                                                                       placeholder="@lang('Giờ kết thúc')" {{$weekly['is_other_monday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_end_time_monday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_tuesday"
                                                                               name="is_tuesday"
                                                                               {{$weekly['is_tuesday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkDay(this, 'Tuesday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>@lang('Thứ 3')</td>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_other_tuesday"
                                                                               name="is_other_tuesday"
                                                                               onchange="view.checkOther(this, 'Tuesday')"
                                                                                {{$weekly['is_tuesday'] == 1 ? '' : 'disabled'}}
                                                                                {{$weekly['is_other_tuesday'] == 1 ? 'checked' : ''}}>
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ bắt đầu')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_tuesday_start_time"
                                                                                       name="is_other_tuesday_start_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_tuesday_start_time']}}"
                                                                                       placeholder="@lang('Giờ bắt đầu')"
                                                                                        {{$weekly['is_other_tuesday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_start_time_tuesday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ kết thúc')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_tuesday_end_time"
                                                                                       name="is_other_tuesday_end_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_tuesday_end_time']}}"
                                                                                       placeholder="@lang('Giờ kết thúc')"
                                                                                        {{$weekly['is_other_tuesday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_end_time_tuesday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_wednesday"
                                                                               name="is_wednesday"
                                                                               {{$weekly['is_wednesday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkDay(this, 'Wednesday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>@lang('Thứ 4')</td>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_other_wednesday"
                                                                               name="is_other_wednesday"
                                                                               onchange="view.checkOther(this, 'Wednesday')"
                                                                                {{$weekly['is_wednesday'] == 1 ? '' : 'disabled'}}
                                                                                {{$weekly['is_other_wednesday'] == 1 ? 'checked' : ''}}>
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ bắt đầu')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_wednesday_start_time"
                                                                                       name="is_other_wednesday_start_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_wednesday_start_time']}}"
                                                                                       placeholder="@lang('Giờ bắt đầu')"
                                                                                        {{$weekly['is_other_wednesday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_start_time_wednesday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ kết thúc')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_wednesday_end_time"
                                                                                       name="is_other_wednesday_end_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_wednesday_end_time']}}"
                                                                                       placeholder="@lang('Giờ kết thúc')"
                                                                                        {{$weekly['is_other_wednesday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_end_time_wednesday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_thursday"
                                                                               name="is_thursday"
                                                                               {{$weekly['is_thursday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkDay(this, 'Thursday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>@lang('Thứ 5')</td>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_other_thursday"
                                                                               name="is_other_thursday"
                                                                               onchange="view.checkOther(this, 'Thursday')"
                                                                                {{$weekly['is_thursday'] == 1 ? '' : 'disabled'}}
                                                                                {{$weekly['is_other_thursday'] == 1 ? 'checked' : ''}}>
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ bắt đầu')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_thursday_start_time"
                                                                                       name="is_other_thursday_start_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_thursday_start_time']}}"
                                                                                       placeholder="@lang('Giờ bắt đầu')"
                                                                                        {{$weekly['is_other_thursday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_start_time_thursday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ kết thúc')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_thursday_end_time"
                                                                                       name="is_other_thursday_end_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_thursday_end_time']}}"
                                                                                       placeholder="@lang('Giờ kết thúc')"
                                                                                        {{$weekly['is_other_thursday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_end_time_thursday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_friday"
                                                                               name="is_friday"
                                                                               {{$weekly['is_friday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkDay(this, 'Friday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>@lang('Thứ 6')</td>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_other_friday"
                                                                               name="is_other_friday"
                                                                               onchange="view.checkOther(this, 'Friday')"
                                                                                {{$weekly['is_friday'] == 1 ? '' : 'disabled'}}
                                                                                {{$weekly['is_other_friday'] == 1 ? 'checked' : ''}}>
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ bắt đầu')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_friday_start_time"
                                                                                       name="is_other_friday_start_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_friday_start_time']}}"
                                                                                       placeholder="@lang('Giờ bắt đầu')"
                                                                                        {{$weekly['is_other_friday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_start_time_friday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ kết thúc')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_friday_end_time"
                                                                                       name="is_other_friday_end_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_friday_end_time']}}"
                                                                                       placeholder="@lang('Giờ kết thúc')"
                                                                                        {{$weekly['is_other_friday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_end_time_friday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_saturday"
                                                                               name="is_saturday"
                                                                               {{$weekly['is_saturday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkDay(this, 'Saturday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>@lang('Thứ 7')</td>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_other_saturday"
                                                                               name="is_other_saturday"
                                                                               onchange="view.checkOther(this, 'Saturday')"
                                                                                {{$weekly['is_saturday'] == 1 ? '' : 'disabled'}}
                                                                                {{$weekly['is_other_saturday'] == 1 ? 'checked' : ''}}>
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ bắt đầu')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_saturday_start_time"
                                                                                       name="is_other_saturday_start_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_saturday_start_time']}}"
                                                                                       placeholder="@lang('Giờ bắt đầu')"
                                                                                        {{$weekly['is_other_saturday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_start_time_saturday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ kết thúc')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_saturday_end_time"
                                                                                       name="is_other_saturday_end_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_saturday_end_time']}}"
                                                                                       placeholder="@lang('Giờ kết thúc')"
                                                                                        {{$weekly['is_other_saturday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_end_time_saturday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_sunday"
                                                                               name="is_sunday"
                                                                               {{$weekly['is_sunday'] == 1 ? 'checked' : ''}}
                                                                               onchange="view.checkDay(this, 'Sunday')">
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>@lang('Chủ nhật')</td>
                                                                <td>
                                                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                                                        <input type="checkbox" id="is_other_sunday"
                                                                               name="is_other_sunday"
                                                                               onchange="view.checkOther(this, 'Sunday')"
                                                                                {{$weekly['is_sunday'] == 1 ? '' : 'disabled'}}
                                                                                {{$weekly['is_other_sunday'] == 1 ? 'checked' : ''}}>
                                                                        <span></span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ bắt đầu')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_sunday_start_time"
                                                                                       name="is_other_sunday_start_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_sunday_start_time']}}"
                                                                                       placeholder="@lang('Giờ bắt đầu')"
                                                                                        {{$weekly['is_other_sunday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_start_time_sunday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group m-form__group row">
                                                                        <label class="col-xl-3 col-lg-3  black_title">
                                                                            @lang('Giờ kết thúc')
                                                                        </label>
                                                                        <div class="col-lg-9 col-xl-9">
                                                                            <div class="input-group date">
                                                                                <input type="text"
                                                                                       class="form-control m-input"
                                                                                       id="is_other_sunday_end_time"
                                                                                       name="is_other_sunday_end_time"
                                                                                       readonly=""
                                                                                       value="{{$weekly['is_other_sunday_end_time']}}"
                                                                                       placeholder="@lang('Giờ kết thúc')"
                                                                                        {{$weekly['is_other_sunday'] == 1 ? '' : 'disabled'}}>
                                                                            </div>
                                                                            <span class="error_end_time_sunday color_red"></span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <label class="m-radio m-radio--bold m-radio--state-success">
                                        <input type="radio" name="time_type" value="M"
                                               onchange="view.changeTime('M')" {{$item['time_type'] == 'M' ? 'checked' : ''}}> @lang('Hàng tháng')
                                        <span></span>
                                    </label>
                                    <div class="m-demo monthly" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        @if(count($monthly) > 0)
                                            <div class="m-demo__preview  m-demo__preview--btn">
                                                <div class="table-responsive">
                                                    <table class="table" id="table-monthly">
                                                        <thead class="thead-default">
                                                        <tr>
                                                            <th>@lang('Ngày khuyến mãi')</th>
                                                            <th>@lang('Giờ bắt đầu')</th>
                                                            <th>@lang('Giờ kết thúc')</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($monthly as $k => $v)
                                                            <tr class="tr_monthly">
                                                                <td>
                                                                    <input type="hidden" class="number"
                                                                           value="{{$k+1}}">
                                                                    <input type="text"
                                                                           class="form-control m-input run_date"
                                                                           readonly=""
                                                                           name="run_date"
                                                                           placeholder="@lang('Ngày khuyến mãi')"
                                                                           value="{{\Carbon\Carbon::parse($v['run_date'])->format('d/m/Y')}}">
                                                                    <span class="error_run_date_{{$k+1}} color_red"></span>
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                           class="form-control m-input start_time"
                                                                           readonly=""
                                                                           placeholder="@lang('Giờ bắt đầu')"
                                                                           value="{{$v['start_time']}}">
                                                                    <span class="error_start_time_{{$k+1}} color_red"></span>
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                           class="form-control m-input end_time"
                                                                           value="{{$v['end_time']}}" readonly=""
                                                                           placeholder="@lang('Giờ bắt đầu')">
                                                                    <span class="error_end_time_{{$k+1}} color_red"></span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button type="button"
                                                        class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                                        onclick="view.addTimeMonthly()">
                                                    <i class="la la-plus"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <label class="m-radio m-radio--bold m-radio--state-success">
                                        <input type="radio" name="time_type" value="R"
                                               onchange="view.changeTime('R')" {{$item['time_type'] == 'R' ? 'checked' : ''}}> @lang('Từ ngày đến ngày')
                                        <span></span>
                                    </label>
                                    <div class="m-demo form_to" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        @if($dateTime != null)
                                            <div class="m-demo__preview  m-demo__preview--btn">
                                                <div class="form-group m-form__group row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group m-form__group row">
                                                            <label class="col-xl-3 col-lg-3  black_title">
                                                                @lang('Từ ngày'):
                                                            </label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <div class="input-group date">
                                                                    <input type="text" class="form-control m-input"
                                                                           readonly="" id="form_date"
                                                                           name="form_date"
                                                                           placeholder="@lang('Ngày bắt đầu')"
                                                                           value="{{\Carbon\Carbon::parse($dateTime['form_date'])->format('d/m/Y')}}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i
                                                                                    class="la la-calendar-check-o glyphicon-th"></i></span>
                                                                    </div>
                                                                </div>
                                                                <span class="error_from_date color_red"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group m-form__group row">
                                                            <label class="col-xl-3 col-lg-3  black_title">
                                                                @lang('Giờ'):
                                                            </label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <div class="input-group date">
                                                                    <input type="text" class="form-control m-input"
                                                                           id="start_time" name="start_time"
                                                                           placeholder="@lang('Giờ')" readonly=""
                                                                           value="{{$dateTime['start_time']}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group m-form__group row">
                                                            <label class="col-xl-3 col-lg-3  black_title">
                                                                @lang('Đến ngày'):
                                                            </label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <div class="input-group date">
                                                                    <input type="text" class="form-control m-input"
                                                                           readonly="" id="to_date" name="to_date"
                                                                           placeholder="@lang('Ngày kết thúc')"
                                                                           value="{{\Carbon\Carbon::parse($dateTime['to_date'])->format('d/m/Y')}}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i
                                                                                    class="la la-calendar-check-o glyphicon-th"></i></span>
                                                                    </div>
                                                                </div>
                                                                <span class="error_to_date color_red"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group m-form__group row">
                                                            <label class="col-xl-3 col-lg-3  black_title">
                                                                @lang('Giờ'):
                                                            </label>
                                                            <div class="col-lg-9 col-xl-9">
                                                                <div class="input-group date">
                                                                    <input type="text" class="form-control m-input"
                                                                           id="end_time" name="end_time"
                                                                           placeholder="@lang('Giờ')" readonly=""
                                                                           value="{{$dateTime['end_time']}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Chi nhánh áp dụng'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group">
                        <select class="form-control" id="branch_apply" name="branch_apply" multiple>
                            <option value="all" {{$item['branch_apply'] == 'all' ? 'selected' : ''}} >@lang('Tất cả')</option>
                            @foreach($branch as $v)
                                @if(in_array($v['branch_id'], str_split($item['branch_apply'])))
                                    <option value="{{$v['branch_id']}}" selected>{{$v['branch_name']}}</option>
                                @else
                                    <option value="{{$v['branch_id']}}">{{$v['branch_name']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Hình thức đặt hàng'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group">
                        <select class="form-control" id="order_source" name="order_source">
                            <option value="all" {{$item['order_source'] == 'all' ? 'selected' : ''}}>@lang('Tất cả')</option>
                            <option value="live" {{$item['order_source'] == 'live' ? 'selected' : ''}}>@lang('Trực tiếp')</option>
                            <option value="app" {{$item['order_source'] == 'app' ? 'selected' : ''}}>@lang('App')</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Đối tượng áp dụng'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group">
                        <select class="form-control" id="promotion_apply_to" name="promotion_apply_to"
                                onchange="view.changeObjectApply(this)">
                            <option></option>
                            <option value="1" {{$item['promotion_apply_to'] == 1 ? 'selected' : ''}}>@lang('Tất cả khách hàng')</option>
                            <option value="2" {{$item['promotion_apply_to'] == 2 ? 'selected' : ''}}>@lang('Hạng thành viên')</option>
                            <option value="3" {{$item['promotion_apply_to'] == 3 ? 'selected' : ''}}>@lang('Nhóm khách hàng')</option>
                            <option value="4" {{$item['promotion_apply_to'] == 4 ? 'selected' : ''}}>@lang('Khách hàng chỉ định')</option>
                        </select>
                    </div>
                </div>
                <div class="div_object_apply">
                    @if($item['promotion_apply_to'] == 2)
                        <div class="div_member_level">
                            <div class="form-group m-form__group row">
                                <label class="col-xl-3 col-lg-3  black_title">
                                    @lang('Hạng thành viên'):<b class="text-danger">*</b>
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div class="input-group">
                                        <select class="form-control" id="member_level_id" name="member_level_id"
                                                multiple
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($memberLevel as $v)
                                                <option value="{{$v['member_level_id']}}" {{in_array($v['member_level_id'], $arrObjectApply) ? 'selected' : ''}}>{{$v['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($item['promotion_apply_to'] == 3)
                        <div class="div_customer_group">
                            <div class="form-group m-form__group row">
                                <label class="col-xl-3 col-lg-3  black_title">
                                    @lang('Nhóm khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div class="input-group">
                                        <select class="form-control" id="customer_group_id" name="customer_group_id"
                                                multiple
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($customerGroup as $v)
                                                <option value="{{$v['customer_group_id']}}"
                                                        {{in_array($v['customer_group_id'], $arrObjectApply) ? 'selected' : ''}}>{{$v['group_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($item['promotion_apply_to'] == 4)
                        <div class="div_customer">
                            <div class="form-group m-form__group row">
                                <label class="col-xl-3 col-lg-3  black_title">
                                    @lang('Khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div class="input-group">
                                        <select class="form-control" id="customer_id" name="customer_id" multiple
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($customer as $v)
                                                <option value="{{$v['customer_id']}}" {{in_array($v['customer_id'], $arrObjectApply) ? 'selected' : ''}}>{{$v['full_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Loại hiển thị trên app'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group">
                        <select class="form-control" id="type_display_app" name="type_display_app">
                            <option value="all" {{$item['type_display_app'] == 'all' ? 'selected' : ''}}>@lang('Tất cả')</option>
                            <option value="apply_to" {{$item['type_display_app'] == 'apply_to' ? 'selected' : ''}}>@lang('Theo đối tượng áp dụng')</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Trạng thái'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group date">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input id="is_actived" name="is_actived" type="checkbox" {{$item['is_actived'] == 1 ? 'checked' : ''}}>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Hiển thị trên app'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group date">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input id="is_display" name="is_display" type="checkbox" {{$item['is_display'] == 1 ? 'checked' : ''}}
                                    onchange="view.changeIsDisplay(this)">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>

                <div class="form-group m-form__group row is_feature">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Hiển thị nổi bật trang chủ'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group date">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input id="is_feature" name="is_feature" type="checkbox"
                                       {{$item['is_feature'] == 1 ? 'checked' : ''}}
                                       onchange="view.changeIsFeature(this)">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="div_feature">
                    <div class="form-group m-form__group row">
                        <label class="col-xl-3 col-lg-3 col-form-label div_feature">
                            @lang('Vị trí hiển thị nổi bật'):
                        </label>
                        <div class="col-lg-9 col-xl-9 div_feature">
                            <div class="row div_position div_position_fix">
                                <ul class="dmm" style="display: flex;list-style: none;padding: 6px 40px;width: 100%">
                                    <li>@lang('Vị trí') 1</li>
                                    <li>@lang('Vị trí') 2</li>
                                    <li>@lang('Vị trí') 3</li>
                                </ul>
                                <ul id="sortable" class="ui-sortable">
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position1) && $position1['promotion_id'] == $item['promotion_id'])
                                            @lang('Vị trí hiện tại') <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="current">
                                        @elseif(isset($position1) && $position1['promotion_id'] != $item['promotion_id'])
                                            {{$position1['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id"
                                                   value="{{$position1['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position2) && $position2['promotion_id'] == $item['promotion_id'])
                                            @lang('Vị trí hiện tại') <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="current">
                                        @elseif(isset($position2) && $position2['promotion_id'] != $item['promotion_id'])
                                            {{$position2['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id"
                                                   value="{{$position2['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position3) && $position3['promotion_id'] == $item['promotion_id'])
                                            @lang('Vị trí hiện tại') <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="current">
                                        @elseif(isset($position3) && $position3['promotion_id'] != $item['promotion_id'])
                                            {{$position3['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id"
                                                   value="{{$position3['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position4) && $position4['promotion_id'] == $item['promotion_id'])
                                            @lang('Vị trí hiện tại') <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="current">
                                        @elseif(isset($position4) && $position4['promotion_id'] != $item['promotion_id'])
                                            {{$position4['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id"
                                                   value="{{$position4['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position5) && $position5['promotion_id'] == $item['promotion_id'])
                                            @lang('Vị trí hiện tại') <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="current">
                                        @elseif(isset($position5) && $position5['promotion_id'] != $item['promotion_id'])
                                            {{$position5['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id"
                                                   value="{{$position5['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if($item['position_feature'] == null || $item['position_feature'] == 6)
                                            @lang('Vị trí hiện tại') <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="current">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                </ul>
                                <ul class="dmm" style="display: flex;list-style: none;padding: 6px 40px;width: 100%">
                                    <li>@lang('Vị trí') 4</li>
                                    <li>@lang('Vị trí') 5</li>
                                    <li>@lang('Mặc định')</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="m-form__group form-group row">
                <label class="col-lg-3 col-form-label">@lang('Ảnh đại diện'):</label>
                <div class="col-lg-2">
                    <div class="form-group m-form__group m-widget19">
                        <div class="m-widget19__pic">
                            <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                 src="{{$item['image'] != null ? $item['image'] : "https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"}}"
                                 alt="Hình ảnh"/>
                        </div>
                        <input type="hidden" id="image" name="image">
                        <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                               data-msg-accept="Hình ảnh không đúng định dạng"
                               id="getFile" type='file'
                               onchange="uploadAvatar2(this,'vi');"
                               class="form-control"
                               style="display:none"/>
                        <div class="m-widget19__action" style="max-width: 170px">
                            <a href="javascript:void(0)"
                               onclick="document.getElementById('getFile').click()"
                               class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    @lang('Tải ảnh lên')
                                                </span>
                                            </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3 black_title">
                    @lang('Mô tả ngắn'):
                </label>
                <div class="col-lg-9 col-xl-9">
                        <textarea class="form-control" id="description" name="description" cols="5"
                                  rows="5">{{$item['description']}}</textarea>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Mô tả chi tiết'):
                </label>
                <div class="col-lg-9 col-xl-9 input-group">
                        <textarea class="form-control" id="description_detail"
                                  name="description_detail">{{$item['description_detail']}}</textarea>
                </div>
            </div>
            <div class="form-group m-form__group">
                <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                        onclick="view.showModal('product')">
                    <i class="la la-plus"></i> @lang('THÊM SẢN PHẨM')
                </button>
                <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                        onclick="view.showModal('service')">
                    <i class="la la-plus"></i> @lang('THÊM DỊCH VỤ')
                </button>
                <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                        onclick="view.showModal('service_card')">
                    <i class="la la-plus"></i> @lang('THÊM THẺ DỊCH VỤ')
                </button>
            </div>

            <div class="form-group m-form__group" id="autotable-discount">
                <form class="frmFilter">
                    <input type="hidden" id="discount_type" name="discount_type"
                           value="{{$item['promotion_type_discount']}}">
                    <input type="hidden" id="discount_value_percent" name="discount_value_percent"
                           value="{{$item['promotion_type_discount_value']}}">
                    <input type="hidden" id="discount_value_same" name="discount_value_same"
                           value="{{$item['promotion_type_discount_value']}}">
                    <div class="form-group m-form__group" style="display: none;">
                        <button class="btn btn-primary color_button btn-search">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </form>
                <div class="table-content div_table_discount">
                    @if($item['promotion_type'] == 1)
                        <div class="table-responsive">
                            <table class="table table-striped m-table m-table--head-bg-default" id="table-discount">
                                <thead class="bg">
                                <tr>
                                    <th class="tr_thead_list">{{__('TÊN')}}</th>
                                    <th class="tr_thead_list">{{__('GIÁ GỐC')}}</th>
                                    <th class="tr_thead_list">{{__('GIÁ KHUYẾN MÃI')}}</th>
                                    <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($listResult) > 0)
                                    @foreach($listResult as $v)
                                        <tr>
                                            <td>
                                                {{$v['object_name']}}
                                                <input type="hidden" class="object_type" value="{{$v['object_type']}}">
                                                <input type="hidden" class="object_code" value="{{$v['object_code']}}">
                                            </td>
                                            <td>{{number_format($v['base_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                            <td>
                                                <input class="form-control promotion_price"
                                                       {{$item['promotion_type_discount'] == 'custom' ? '' : 'disabled'}}
                                                       placeholder="@lang('Nhập giá khuyến mãi')"
                                                       onchange="view.changePromotionPrice(this, '{{$v['object_code']}}')"
                                                       value="{{number_format($v['promotion_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                            </td>
                                            <td>
                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                        <label>
                                                            <input class="is_detail_active" type="checkbox"
                                                                   {{$v['is_actived'] == 1 ? 'checked' : ''}}
                                                                   onchange="view.changeStatus(this, '{{$v['object_code']}}')">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)"
                                                   onclick="view.removeTr(this, '{{$v['object_code']}}', 'discount', '{{$v['object_type']}}')"
                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                   title="Delete">
                                                    <i class="la la-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {{ $listResult->links('helpers.paging') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group m-form__group" id="autotable-gift">
                <div class="table-content div_table_gift">
                    @if($item['promotion_type'] == 2)
                        <div class="table-responsive">
                            <table class="table table-striped m-table m-table--head-bg-default" id="table-gift">
                                <thead class="bg">
                                <tr>
                                    <th class="tr_thead_list">{{__('TÊN')}}</th>
                                    <th class="tr_thead_list">{{__('SỐ LƯỢNG CẦN MUA')}}</th>
                                    <th class="tr_thead_list">{{__('SỐ LƯỢNG QUÀ TẶNG')}}</th>
                                    <th class="tr_thead_list">{{__('LOẠI QUÀ TẶNG')}}</th>
                                    <th class="tr_thead_list">{{__('QUÀ TẶNG')}}</th>
                                    <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($listResult) > 0)
                                    @foreach($listResult as $v)
                                        <tr>
                                            <td style="width:20%;">
                                                {{$v['object_name']}}
                                                <input type="hidden" class="object_type"
                                                       value="{{$v['object_type']}}">
                                                <input type="hidden" class="object_code"
                                                       value="{{$v['object_code']}}">
                                            </td>
                                            <td style="width:15%;">
                                                <input class="form-control quantity_buy"
                                                       placeholder="@lang('Nhập số lượng cần mua')"
                                                       onchange="view.changeQuantityBuy(this, '{{$v['object_code']}}')"
                                                       value="{{$v['quantity_buy']}}">
                                                <span class="error_quantity_buy_{{$v['object_code']}}"></span>
                                            </td>
                                            <td style="width:15%;">
                                                <input class="form-control quantity_gift"
                                                       placeholder="@lang('Nhập số lượng quà tặng')"
                                                       onchange="view.changeNumberGift(this, '{{$v['object_code']}}')"
                                                       value="{{$v['quantity_gift']}}">
                                                <span class="error_quantity_gift_{{$v['object_code']}}"></span>
                                            </td>
                                            <td style="width:15%;">
                                                <select class="form-control gift_object_type" style="width:100%;"
                                                        onchange="view.changeGiftType(this, '{{$v['object_code']}}')">
                                                    <option></option>
                                                    <option value="product" {{$v['gift_object_type'] == 'product' ? 'selected' : ''}}>@lang('Sản phẩm')</option>
                                                    <option value="service" {{$v['gift_object_type'] == 'service' ? 'selected' : ''}}>@lang('Dịch vụ')</option>
                                                    <option value="service_card" {{$v['gift_object_type'] == 'service_card' ? 'selected' : ''}}>@lang('Thẻ dịch vụ')</option>
                                                </select>
                                                <span class="error_gift_object_type_{{$v['object_code']}}"></span>
                                            </td>
                                            <td style="width:25%;">
                                                <select class="form-control gift_object_id" style="width:100%;"
                                                        {{$v['gift_object_type'] == null ? 'disabled' : ''}}
                                                        onchange="view.changeGift(this, '{{$v['object_code']}}')">
                                                    <option></option>
                                                    <option value="{{$v['gift_object_id']}}"
                                                            selected>{{$v['gift_object_name']}}</option>
                                                </select>
                                            </td>
                                            <td>
                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                        <label>
                                                            <input class="is_detail_active" type="checkbox"
                                                                   {{$v['is_actived'] == 1 ? 'checked' : ''}}
                                                                   onchange="view.changeStatus(this, '{{$v['object_code']}}')">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)"
                                                   onclick="view.removeTr(this, '{{$v['object_code']}}', 'gift', '{{$v['object_type']}}')"
                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                   title="Delete">
                                                    <i class="la la-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            {{ $listResult->links('helpers.paging')}}
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('promotion')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button"
                                onclick="view.submitEdit('{{$item['promotion_id']}}', '{{$item['promotion_code']}}')"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <input type="hidden" id="promotion_code" value="{{$item['promotion_code']}}">
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
    <script src="{{asset('static/backend/js/promotion/promotion/script.js')}}"
            type="text/javascript"></script>
    <script>
        view._init();
    </script>
    <script type="text/template" id="tpl-quota">
        <input type="text" class="form-control m-input" id="quota" name="quota"
               placeholder="@lang('Nhập số lượng khuyến mãi')">
    </script>
    <script type="text/template" id="tpl-daily">
        <div class="m-demo__preview  m-demo__preview--btn">
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Giờ bắt đầu'):
                </label>
                <div class="col-lg-9 col-xl-9">
                    <div class="input-group date">
                        <input type="text" class="form-control m-input" readonly="" id="start_time"
                               name="start_time"
                               placeholder="@lang('Giờ bắt đầu')">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-clock-o"></i></span>
                        </div>
                    </div>
                    <span class="error_start_time_daily color_red"></span>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Giờ kết thúc'):
                </label>
                <div class="col-lg-9 col-xl-9">
                    <div class="input-group date">
                        <input type="text" class="form-control m-input" readonly="" id="end_time"
                               name="end_time"
                               placeholder="@lang('Giờ kết thúc')">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-clock-o"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-weekly">
        <div class="m-demo__preview  m-demo__preview--btn">
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Giờ bắt đầu'):
                </label>
                <div class="col-lg-9 col-xl-9">
                    <div class="input-group date">
                        <input type="text" class="form-control m-input" readonly=""
                               id="default_start_time" name="default_start_time"
                               placeholder="@lang('Giờ bắt đầu')">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-clock-o"></i></span>
                        </div>
                    </div>
                    <span class="error_default_start_time color_red"></span>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Giờ kết thúc'):
                </label>
                <div class="col-lg-9 col-xl-9">
                    <div class="input-group date">
                        <input type="text" class="form-control m-input" readonly=""
                               id="default_end_time" name="default_end_time"
                               placeholder="@lang('Giờ kết thúc')">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-clock-o"></i></span>
                        </div>
                    </div>
                    <span class="error_default_end_time color_red"></span>
                </div>
            </div>
            <div class="form-group m-form__group">
                <span>@lang('Giờ khuyến mãi khác')</span>
                <div class="table-responsive">
                    <table class="table table-inverse">
                        <tbody>
                        <tr>
                            <td colspan="5">
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" onchange="view.checkAllWeek(this)"> @lang('Cả tuần')
                                    <span></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_monday" name="is_monday"
                                           onchange="view.checkDay(this, 'Monday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>@lang('Thứ 2')</td>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_other_monday" name="is_other_monday" disabled
                                           onchange="view.checkOther(this, 'Monday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ bắt đầu')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_monday_start_time"
                                                   name="is_other_monday_start_time" readonly=""
                                                   placeholder="@lang('Giờ bắt đầu')"
                                                   disabled>
                                        </div>
                                        <span class="error_start_time_monday color_red"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ kết thúc')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_monday_end_time"
                                                   name="is_other_monday_end_time" readonly=""
                                                   placeholder="@lang('Giờ kết thúc')"
                                                   disabled>
                                        </div>
                                        <span class="error_end_time_monday color_red"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_tuesday" name="is_tuesday"
                                           onchange="view.checkDay(this, 'Tuesday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>@lang('Thứ 3')</td>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_other_tuesday" name="is_other_tuesday" disabled
                                           onchange="view.checkOther(this, 'Tuesday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ bắt đầu')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_tuesday_start_time"
                                                   name="is_other_tuesday_start_time" readonly=""
                                                   placeholder="@lang('Giờ bắt đầu')"
                                                   disabled>
                                        </div>
                                        <span class="error_start_time_tuesday color_red"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ kết thúc')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_tuesday_end_time"
                                                   name="is_other_tuesday_end_time" readonly=""
                                                   placeholder="@lang('Giờ kết thúc')"
                                                   disabled>
                                        </div>
                                        <span class="error_end_time_tuesday color_red"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_wednesday" name="is_wednesday"
                                           onchange="view.checkDay(this, 'Wednesday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>@lang('Thứ 4')</td>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_other_wednesday" name="is_other_wednesday" disabled
                                           onchange="view.checkOther(this, 'Wednesday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ bắt đầu')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_wednesday_start_time"
                                                   name="is_other_wednesday_start_time" readonly=""
                                                   placeholder="@lang('Giờ bắt đầu')"
                                                   disabled>
                                        </div>
                                        <span class="error_start_time_wednesday color_red"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ kết thúc')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_wednesday_end_time"
                                                   name="is_other_wednesday_end_time" readonly=""
                                                   placeholder="@lang('Giờ kết thúc')"
                                                   disabled>
                                        </div>
                                        <span class="error_end_time_wednesday color_red"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_thursday" name="is_thursday"
                                           onchange="view.checkDay(this, 'Thursday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>@lang('Thứ 5')</td>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_other_thursday" name="is_other_thursday" disabled
                                           onchange="view.checkOther(this, 'Thursday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ bắt đầu')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_thursday_start_time"
                                                   name="is_other_thursday_start_time" readonly=""
                                                   placeholder="@lang('Giờ bắt đầu')"
                                                   disabled>
                                        </div>
                                        <span class="error_start_time_thursday color_red"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ kết thúc')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_thursday_end_time"
                                                   name="is_other_thursday_end_time" readonly=""
                                                   placeholder="@lang('Giờ kết thúc')"
                                                   disabled>
                                        </div>
                                        <span class="error_end_time_thursday color_red"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_friday" name="is_friday"
                                           onchange="view.checkDay(this, 'Friday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>@lang('Thứ 6')</td>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_other_friday" name="is_other_friday" disabled
                                           onchange="view.checkOther(this, 'Friday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ bắt đầu')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_friday_start_time"
                                                   name="is_other_friday_start_time" readonly=""
                                                   placeholder="@lang('Giờ bắt đầu')"
                                                   disabled>
                                        </div>
                                        <span class="error_start_time_friday color_red"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ kết thúc')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_friday_end_time"
                                                   name="is_other_friday_end_time" readonly=""
                                                   placeholder="@lang('Giờ kết thúc')"
                                                   disabled>
                                        </div>
                                        <span class="error_end_time_friday color_red"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_saturday" name="is_saturday"
                                           onchange="view.checkDay(this, 'Saturday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>@lang('Thứ 7')</td>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_other_saturday" name="is_other_saturday" disabled
                                           onchange="view.checkOther(this, 'Saturday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ bắt đầu')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_saturday_start_time"
                                                   name="is_other_saturday_start_time" readonly=""
                                                   placeholder="@lang('Giờ bắt đầu')"
                                                   disabled>
                                        </div>
                                        <span class="error_start_time_saturday color_red"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ kết thúc')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_saturday_end_time"
                                                   name="is_other_saturday_end_time" readonly=""
                                                   placeholder="@lang('Giờ kết thúc')"
                                                   disabled>
                                        </div>
                                        <span class="error_end_time_saturday color_red"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_sunday" name="is_sunday"
                                           onchange="view.checkDay(this, 'Sunday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>@lang('Chủ nhật')</td>
                            <td>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-info">
                                    <input type="checkbox" id="is_other_sunday" name="is_other_sunday" disabled
                                           onchange="view.checkOther(this, 'Sunday')">
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ bắt đầu')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_sunday_start_time"
                                                   name="is_other_sunday_start_time" readonly=""
                                                   placeholder="@lang('Giờ bắt đầu')"
                                                   disabled>
                                        </div>
                                        <span class="error_start_time_sunday color_red"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group m-form__group row">
                                    <label class="col-xl-3 col-lg-3  black_title">
                                        @lang('Giờ kết thúc')
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input"
                                                   id="is_other_sunday_end_time"
                                                   name="is_other_sunday_end_time" readonly=""
                                                   placeholder="@lang('Giờ kết thúc')"
                                                   disabled>
                                        </div>
                                        <span class="error_end_time_sunday color_red"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-monthly">
        <div class="m-demo__preview  m-demo__preview--btn">
            <div class="table-responsive">
                <table class="table" id="table-monthly">
                    <thead class="thead-default">
                    <tr>
                        <th>@lang('Ngày khuyến mãi')</th>
                        <th>@lang('Giờ bắt đầu')</th>
                        <th>@lang('Giờ kết thúc')</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                    onclick="view.addTimeMonthly()">
                <i class="la la-plus"></i>
            </button>
        </div>
    </script>
    <script type="text/template" id="tpl-from-to">
        <div class="m-demo__preview  m-demo__preview--btn">
            <div class="form-group m-form__group row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group row">
                        <label class="col-xl-3 col-lg-3  black_title">
                            @lang('Từ ngày'):
                        </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly="" id="form_date"
                                       name="form_date" placeholder="@lang('Ngày bắt đầu')">
                                <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                            <span class="error_from_date color_red"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group row">
                        <label class="col-xl-3 col-lg-3  black_title">
                            @lang('Giờ'):
                        </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" id="start_time" name="start_time"
                                       placeholder="@lang('Giờ')" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group row">
                        <label class="col-xl-3 col-lg-3  black_title">
                            @lang('Đến ngày'):
                        </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly="" id="to_date" name="to_date"
                                       placeholder="@lang('Ngày kết thúc')">
                                <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                            <span class="error_to_date color_red"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group row">
                        <label class="col-xl-3 col-lg-3  black_title">
                            @lang('Giờ'):
                        </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" id="end_time" name="end_time"
                                       placeholder="@lang('Giờ')" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-tr-monthly">
        <tr class="tr_monthly">
            <td>
                <input type="hidden" class="number" value="{stt}">
                <input type="text" class="form-control m-input run_date" readonly=""
                       name="run_date" placeholder="@lang('Ngày khuyến mãi')">
                <span class="error_run_date_{stt} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input start_time" readonly=""
                       placeholder="@lang('Giờ bắt đầu')">
                <span class="error_start_time_{stt} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input end_time" readonly="" placeholder="@lang('Giờ kết thúc')">
                <span class="error_end_time_{stt} color_red"></span>
            </td>
        </tr>
    </script>
    <script type="text/template" id="tpl-member-level">
        <div class="div_member_level">
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Hạng thành viên'):<b class="text-danger">*</b>
                </label>
                <div class="col-lg-9 col-xl-9">
                    <div class="input-group">
                        <select class="form-control" id="member_level_id" name="member_level_id" multiple
                                style="width:100%;">
                            <option></option>
                            @foreach($memberLevel as $v)
                                <option value="{{$v['member_level_id']}}">{{$v['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-customer-group">
        <div class="div_customer_group">
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Nhóm khách hàng'):<b class="text-danger">*</b>
                </label>
                <div class="col-lg-9 col-xl-9">
                    <div class="input-group">
                        <select class="form-control" id="customer_group_id" name="customer_group_id" multiple
                                style="width:100%;">
                            <option></option>
                            @foreach($customerGroup as $v)
                                <option value="{{$v['customer_group_id']}}">{{$v['group_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-customer">
        <div class="div_customer">
            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Khách hàng'):<b class="text-danger">*</b>
                </label>
                <div class="col-lg-9 col-xl-9">
                    <div class="input-group">
                        <select class="form-control" id="customer_id" name="customer_id" multiple
                                style="width:100%;">
                            <option></option>
                            @foreach($customer as $v)
                                <option value="{{$v['customer_id']}}">{{$v['full_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-time">
        <div class="form-group m-form__group row">
            <label class="col-xl-3 col-lg-3  black_title">
                @lang('Giờ khuyến mãi'):
            </label>
            <div class="col-lg-9 col-xl-9">
                <div class="m-radio-list">
                    <label class="m-radio m-radio--bold m-radio--state-success">
                        <input type="radio" name="time_type" value="D" onchange="view.changeTime('D')"
                               checked> @lang('Hàng ngày')
                        <span></span>
                    </label>
                    <div class="m-demo daily" data-code-preview="true" data-code-html="true"
                         data-code-js="false">
                        <div class="m-demo__preview  m-demo__preview--btn">
                            <div class="form-group m-form__group row">
                                <label class="col-xl-3 col-lg-3  black_title">
                                    @lang('Giờ bắt đầu'):
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div class="input-group date">
                                        <input type="text" class="form-control m-input" readonly="" id="start_time"
                                               name="start_time"
                                               placeholder="@lang('Giờ bắt đầu')">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="la la-clock-o"></i></span>
                                        </div>
                                    </div>
                                    <span class="error_start_time_daily color_red"></span>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-xl-3 col-lg-3  black_title">
                                    @lang('Giờ kết thúc'):
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div class="input-group date">
                                        <input type="text" class="form-control m-input" readonly="" id="end_time"
                                               name="end_time"
                                               placeholder="@lang('Giờ kết thúc')">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="la la-clock-o"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label class="m-radio m-radio--bold m-radio--state-success">
                        <input type="radio" name="time_type" value="W"
                               onchange="view.changeTime('W')"> @lang('Hàng tuần')
                        <span></span>
                    </label>
                    <div class="m-demo weekly" data-code-preview="true" data-code-html="true"
                         data-code-js="false"></div>
                    <label class="m-radio m-radio--bold m-radio--state-success">
                        <input type="radio" name="time_type" value="M"
                               onchange="view.changeTime('M')"> @lang('Hàng tháng')
                        <span></span>
                    </label>
                    <div class="m-demo monthly" data-code-preview="true" data-code-html="true"
                         data-code-js="false"></div>
                    <label class="m-radio m-radio--bold m-radio--state-success">
                        <input type="radio" name="time_type" value="R"
                               onchange="view.changeTime('R')"> @lang('Từ ngày đến ngày')
                        <span></span>
                    </label>
                    <div class="m-demo form_to" data-code-preview="true" data-code-html="true"
                         data-code-js="false"></div>
                </div>
            </div>
        </div>
    </script>
    <script>
        $(document).ready(function () {
            @if($item['is_time_campaign'] == 1 && $dateTime != null)
            $('#form_date, #to_date').datepicker({
                startDate: '0d',
                language: 'en',
                orientation: "bottom left", todayHighlight: !0,
                format: 'dd/mm/yyyy'
            });

            $("#start_time, #end_time").timepicker({
                minuteStep: 15,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });
            @elseif($item['is_time_campaign'] == 1 && $weekly != null)
            $("#default_start_time, " +
                "#default_end_time, " +
                "#is_other_monday_start_time, " +
                "#is_other_monday_end_time, " +
                "#is_other_tuesday_start_time, " +
                "#is_other_tuesday_end_time, " +
                "#is_other_wednesday_start_time, " +
                "#is_other_wednesday_end_time, " +
                "#is_other_thursday_start_time, " +
                "#is_other_thursday_end_time, " +
                "#is_other_friday_start_time, " +
                "#is_other_friday_end_time, " +
                "#is_other_saturday_start_time, " +
                "#is_other_saturday_end_time, " +
                "#is_other_sunday_start_time, " +
                "#is_other_sunday_end_time").timepicker({
                minuteStep: 15,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });
            @elseif($item['is_time_campaign'] == 1 && count($monthly) > 0)
            $('.run_date').datepicker({
                startDate: '0d',
                language: 'en',
                orientation: "bottom left", todayHighlight: !0,
                format: 'dd/mm/yyyy',
            });

            $(".start_time, .end_time").timepicker({
                minuteStep: 15,
                defaultTime: "",
                showMeridian: !1,
                snapToStep: !0,
            });
            stt = $('#table-monthly >tbody tr').length;
            @endif

            @if($item['promotion_type'] == 1)
            $('#autotable-discount').PioTable({
                baseUrl: laroute.route('promotion.list-discount')
            });

            $.ajax({
                url: laroute.route('promotion.load-session-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    promotion_code: $('#promotion_code').val()
                },
                success:function () {
                    $('.btn-search').trigger('click');
                }
            });

            new AutoNumeric.multiple('.promotion_price', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}},
                eventIsCancelable: true,
                minimumValue: 0
            });
            @else
            $('#autotable-gift').PioTable({
                baseUrl: laroute.route('promotion.list-gift')
            });

            $.ajax({
                url: laroute.route('promotion.load-session-all'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    promotion_code: $('#promotion_code').val()
                },
                success:function () {
                    $('#autotable-gift').PioTable('refresh');
                }
            });

            $('.quantity_buy, .quantity_gift').ForceNumericOnly();

            $.getJSON(laroute.route('translate'), function (json) {
                $('.gift_object_type').select2({
                    placeholder: json['Chọn loại quà tặng']
                });
                $('.gift_object_id').select2({
                    placeholder: json['Chọn quà tặng']
                });

                $.each($('#table-gift').find("tr"), function () {
                    var gift_object_type = $(this).find($('.gift_object_type')).val();

                    if (gift_object_type != '' || gift_object_type != 'undefined') {
                        $(this).find($('.gift_object_id')).select2({
                            width: '100%',
                            placeholder: json["Chọn quà tặng"],
                            ajax: {
                                url: laroute.route('promotion.list-option'),
                                data: function (params) {
                                    return {
                                        search: params.term,
                                        page: params.page || 1,
                                        type: gift_object_type
                                    };
                                },
                                dataType: 'json',
                                method: 'POST',
                                processResults: function (data) {
                                    data.page = data.page || 1;
                                    return {
                                        results: data.items.map(function (item) {
                                            if (gift_object_type == 'product') {
                                                return {
                                                    id: item.product_child_id,
                                                    text: item.product_child_name,
                                                    code: item.product_code
                                                };
                                            } else if (gift_object_type == 'service') {
                                                return {
                                                    id: item.service_id,
                                                    text: item.service_name,
                                                    code: item.service_code
                                                };
                                            } else if (gift_object_type == 'service_card') {
                                                return {
                                                    id: item.service_card_id,
                                                    text: item.card_name,
                                                    code: item.code
                                                };
                                            }
                                        }),
                                        pagination: {
                                            more: data.pagination
                                        }
                                    };
                                },
                            }
                        });
                    }
                });
            });
            @endif

            @if($item['promotion_type_discount'] == 'percent')
            $('#promotion_type_discount_value_percent').css('display', 'block');
            $('#promotion_type_discount_value_same').css('display', 'none');
            @elseif($item['promotion_type_discount'] == 'same')
            $('#promotion_type_discount_value_percent').css('display', 'none');
            $('#promotion_type_discount_value_same').css('display', 'block');
            @elseif($item['promotion_type_discount'] == 'custom')
            $('#promotion_type_discount_value_percent').css('display', 'none');
            $('#promotion_type_discount_value_same').css('display', 'none');
            @endif
        });
    </script>
@stop


