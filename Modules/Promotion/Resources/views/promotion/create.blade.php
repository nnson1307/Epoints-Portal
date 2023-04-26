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
                        @lang('TẠO CHƯƠNG TRÌNH KHUYẾN MÃI')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">
            <form id="form-register">
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Tên chương trình'):<b class="text-danger">*</b>
                    </label>
                    <div class="col-lg-9 col-xl-9">
                        <input type="text" class="form-control m-input"
                               id="promotion_name" name="promotion_name" placeholder="@lang('Nhập tên chương trình')">
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
                                       checked> @lang('Giảm giá')
                                <span></span>
                            </label>
                            <div class="m-demo discount_value" data-code-preview="true" data-code-html="true"
                                 data-code-js="false">
                                <div class="m-demo__preview  m-demo__preview--btn">
                                    <div class="m-radio-list">
                                        <label class="form-group m-radio m-radio--check-bold m-radio--state-brand">
                                            <input type="radio" name="promotion_type_value" value="percent" checked
                                                   onchange="view.changeTypeValue('percent')"> @lang('Phần trăm')
                                            <span></span>
                                        </label>
                                        <input type="text" id="promotion_type_discount_value_percent"
                                               class="form-group form-control m-input"
                                               placeholder="@lang('Nhập phần trăm')" value="0"
                                               onchange="view.changePercent(this)">
                                        <label class="form-group m-radio m-radio--check-bold m-radio--state-brand">
                                            <input type="radio" name="promotion_type_value" value="same"
                                                   onchange="view.changeTypeValue('same')"> @lang('Đồng giá')
                                            <span></span>
                                        </label>
                                        <input type="text" id="promotion_type_discount_value_same"
                                               class="form-group form-control m-input"
                                               placeholder="@lang('Nhập số tiền')" value="0"
                                               style="display:none;"
                                               onchange="view.changeSamePrice(this)">
                                        <label class="form-group m-radio m-radio--check-bold m-radio--state-brand">
                                            <input type="radio" name="promotion_type_value" value="custom"
                                                   onchange="view.changeTypeValue('custom')"> @lang('Tùy chỉnh')
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <label class="m-radio m-radio--bold m-radio--state-success">
                                <input type="radio" name="promotion_type" onchange="view.changeType(2)"
                                       value="2"> @lang('Quà tặng')
                                <span></span>
                            </label>
{{--                            <label class="m-radio m-radio--bold m-radio--state-success">--}}
{{--                                <input type="radio" name="promotion_type" onchange="view.changeType(3)"--}}
{{--                                       value="3"> @lang('Tích lũy')--}}
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
                        <input type="text" class="form-control m-input"
                               id="quota" name="quota" placeholder="@lang('Nhập số lượng khuyến mãi')">
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
                                   id="start_date" name="start_date">
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
                                   id="end_date" name="end_date">
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
                                       onchange="view.changeIsTime(this)">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="div_time">

                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Chi nhánh áp dụng'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group">
                        <select class="form-control" id="branch_apply" name="branch_apply" multiple>
                            <option value="all" selected>@lang('Tất cả')</option>
                            @foreach($branch as $v)
                                <option value="{{$v['branch_id']}}">{{$v['branch_name']}}</option>
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
                            <option value="all">@lang('Tất cả')</option>
                            <option value="live">@lang('Trực tiếp')</option>
                            <option value="app">@lang('App')</option>
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
                            <option value="1">@lang('Tất cả khách hàng')</option>
                            <option value="2">@lang('Hạng thành viên')</option>
                            <option value="3">@lang('Nhóm khách hàng')</option>
                            <option value="4">@lang('Khách hàng chỉ định')</option>
                        </select>
                    </div>
                </div>
                <div class="div_object_apply">

                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Loại hiển thị trên app'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group">
                        <select class="form-control" id="type_display_app" name="type_display_app">
                            <option value="all">@lang('Tất cả')</option>
                            <option value="apply_to">@lang('Theo đối tượng áp dụng')</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Hiển thị trên app'):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group date">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input id="is_display" name="is_display" type="checkbox" checked=""
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
                                <input id="is_feature" name="is_feature" type="checkbox" checked=""
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
                                        @if(isset($position1))
                                            {{$position1['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="{{$position1['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position2))
                                            {{$position2['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="{{$position2['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position3))
                                            {{$position3['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="{{$position3['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position4))
                                            {{$position4['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="{{$position4['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @if(isset($position5))
                                            {{$position5['promotion_name']}} <br> <i class="la la-arrows"></i>
                                            <input type="hidden" name="promotion_id" value="{{$position5['promotion_id']}}">
                                        @else
                                            <input type="hidden" name="promotion_id" value="">
                                        @endif
                                    </li>
                                    <li class="ui-state-default ui-sortable-handle">
                                        @lang('Vị trí hiện tại') <br> <i class="la la-arrows"></i>
                                        <input type="hidden" name="promotion_id" value="current">
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
                                 src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
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
                    <textarea class="form-control" id="description" name="description" cols="5" rows="5"></textarea>
                    <span class="error_description color_red"></span>
                </div>

            </div>

            <div class="form-group m-form__group row">
                <label class="col-xl-3 col-lg-3  black_title">
                    @lang('Mô tả chi tiết'):
                </label>
                <div class="col-lg-9 col-xl-9 input-group">
                    <textarea class="form-control" id="description_detail" name="description_detail"></textarea>
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
                    <input type="hidden" id="discount_type" name="discount_type" value="percent">
                    <input type="hidden" id="discount_value_percent" name="discount_value_percent" value="0">
                    <input type="hidden" id="discount_value_same" name="discount_value_same" value="0">
                    <div class="form-group m-form__group" style="display: none;">
                        <button class="btn btn-primary color_button btn-search">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </form>
                <div class="table-content div_table_discount">

                </div>
            </div>
            <div class="form-group m-form__group" id="autotable-gift">
                <div class="table-content div_table_gift">

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
                        <button type="button" onclick="view.submitCreate()"
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
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/promotion/promotion/script.js?v='.time())}}"
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
@stop


