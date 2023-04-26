@extends('layout')
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
    <style>
        .note-editor {
            width: 100%;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('QUẢN LÝ HOA HỒNG') }}
    </span>
@endsection
@section('content')
    <style>
        .btn.btn-default, .btn.btn-secondary.active {
            color: #fff !important;
            background: #4fc4ca !important;
            border-color: #4fc4ca !important;
        }
    </style>

    {{--<form id="form-banner" autocomplete="off">--}}
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="fa fa-plus-circle"></i>
                        </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('THÊM MỚI HOA HỒNG') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('admin.commission')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                </a>
            </div>
        </div>

        <div class="m-wizard m-wizard--1 m-wizard--success m-wizard--step-between" id="m_wizard">

            <!--begin: Form Wizard Head -->
            <div class="m-wizard__head m-portlet__padding-x">

                <!--begin: Form Wizard Nav -->
                <div class="m-wizard__nav">
                    <div class="m-wizard__steps" style="width: auto; margin: auto;">
                        <div class="m-wizard__step m-wizard__step--current" m-wizard-target="m_wizard_form_step_1"
                             style="padding-right: 10px;">
                            <div class="m-wizard__step-info">
                                <a href="#" class="m-wizard__step-number">
                                    <span><span>1</span></span>
                                </a>
                                <div class="m-wizard__step-line">
                                    <span></span>
                                </div>
                                <div class="m-wizard__step-label">
                                    @lang('Thông tin hoa hồng')
                                </div>
                            </div>
                        </div>
                        <div class="m-wizard__step" m-wizard-target="m_wizard_form_step_2">
                            <div class="m-wizard__step-info">
                                <a href="#" class="m-wizard__step-number">
                                    <span><span>2</span></span>
                                </a>
                                <div class="m-wizard__step-line">
                                    <span></span>
                                </div>
                                <div class="m-wizard__step-label">
                                    @lang('Công thức tính')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end: Form Wizard Nav -->
            </div>

            <!--end: Form Wizard Head -->

            <!--begin: Form Wizard Form-->
            <div class="m-wizard__form">

                <form class="m-form m-form--label-align-left- m-form--state-" id="m_form" novalidate="novalidate">

                    <!--begin: Form Body -->
                    <div class="m-portlet__body">

                        <!--begin: Form Wizard Step 1-->
                        <div class="m-wizard__form-step" id="m_wizard_form_step_1">
                            <div id="add-commission-step-1">
                                <!-- Tên hoa hồng -->
                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Tên hoa hồng') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <input id="commission_name" name="commission_name" type="text"
                                               class="form-control m-input class"
                                               placeholder="{{ __('Nhập tên hoa hồng') }}">
                                    </div>
                                    <span class="errs error-name"></span>
                                </div>

                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Tags') }}:
                                    </label>
                                    <div class="input-group">
                                        <select style="width: 100%;" name="tags_id[]" id="tags_id"
                                                class="form-control m-input ss--select-2 js-tags"
                                                multiple="multiple">
                                            @if (isset($TAG_LIST))
                                                @foreach ($TAG_LIST as $tag_item)
                                                    <option value="{{ $tag_item['tags_id'] }}">{{ $tag_item['tags_name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <span class="errs error-name"></span>
                                </div>

                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Trạng thái hoạt động') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label class="ss--switch">
                                            <input type="checkbox" id="status" checked class="manager-btn">
                                            <span></span>
                                            </label>
                                            </span>
                                    </div>
                                    <span class="errs error-display"></span>
                                </div>

                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Mô tả') }}:
                                    </label>
                                    <div class="input-group">
                                            <textarea id="description" name="description"
                                                      class="form-control m-input class"
                                                      placeholder="Nhập mô tả tại đây" rows="6"
                                                      cols="50"></textarea>
                                    </div>
                                    <span class="errs error-display"></span>
                                </div>

                                {{--                                    <div class="form-group m-form__group">--}}
                                {{--                                        <label>--}}
                                {{--                                            {{ __('Thời gian áp dụng hoa hồng này cho nhân viên mỗi') }}: <b--}}
                                {{--                                                    class="text-danger">*</b>--}}
                                {{--                                        </label>--}}

                                {{--                                        <a class="quotetag" href="#">[?]--}}
                                {{--                                            <span class="classic">--}}
                                {{--                                                Nếu bạn nhập trường 'Thời gian áp dụng hoa hồng này cho nhân viên mỗi' là:<br>--}}
                                {{--                                                <strong>1 tháng:</strong> Hệ thống sẽ tính dựa trên doanh thu/KPI (các tiêu chí áp dụng tính hoa hồng) trong 1 tháng<br>--}}
                                {{--                                                <strong>3 tháng:</strong> Hệ thống sẽ tính dựa trên doanh thu/KPI (các tiêu chí áp dụng tính hoa hồng) trong 3 tháng gần nhất bao gồm tháng hiện tại<br>--}}
                                {{--                                            </span>--}}
                                {{--                                        </a>--}}

                                {{--                                        <div class="input-group">--}}
                                {{--                                            <input type="text" class="form-control" id="apply_time" name="apply_time">--}}

                                {{--                                            <div class="input-group-append">--}}
                                {{--                                                <select class="form-control" id="apply_time_type" name="apply_time_type">--}}
                                {{--                                                    <option>@lang('Tháng')</option>--}}
                                {{--                                                    --}}{{--<option>@lang('Tuần')</option>--}}
                                {{--                                                </select>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}

                                {{--                                        <span class="errs error-display"></span>--}}
                                {{--                                    </div>--}}

                                {{--                                    <div class="form-group m-form__group" id="input-apply-time">--}}
                                {{--                                        <label>--}}
                                {{--                                            {{ __('Lấy giá trị tính dựa trên') }}: <b class="text-danger">*</b>--}}
                                {{--                                        </label>--}}

                                {{--                                        <a class="quotetag" href="#">[?]--}}
                                {{--                                            <span class="classic">--}}
                                {{--                                            Nếu bạn chọn 'Thời gian áp dụng hoa hồng này cho nhân viên mỗi' là <strong>3 tháng</strong><br>--}}
                                {{--                                            Và nhập trường 'Lấy giá trị tính dựa trên' là:<br>--}}
                                {{--                                            <strong>1 tháng:</strong> Hệ thống sẽ so sánh điều kiện tính dựa trên doanh thu/KPI của mỗi tháng trong vòng 3 tháng gần nhất bao gồm tháng hiện tại<br>--}}
                                {{--                                            <strong>3 tháng:</strong> Hệ thống sẽ so sánh điều kiện tính dựa trên doanh thu/KPI của tổng 3 tháng trong vòng 3 tháng gần nhất bao gồm tháng hiện tại<br>--}}
                                {{--                                            </span>--}}
                                {{--                                        </a>--}}

                                {{--                                        <div class="input-group">--}}
                                {{--                                            <select class="form-control" id="calc_apply_time" name="calc_apply_time">--}}
                                {{--                                                <option></option>--}}
                                {{--                                            </select>--}}
                                {{--                                        </div>--}}
                                {{--                                        <span class="errs error-display"></span>--}}
                                {{--                                    </div>--}}

                                {{--                                    <div class="form-group m-form__group">--}}
                                {{--                                        <label>--}}
                                {{--                                            {{ __('Thời gian hiệu lực từ') }}: <b class="text-danger">*</b>--}}
                                {{--                                        </label>--}}

                                {{--                                        <div class="input-group date">--}}
                                {{--                                            <input type="text" class="form-control m-input" readonly="" placeholder="@lang('Ngày bắt đầu')" id="start_effect_time" name="start_effect_time">--}}
                                {{--                                            <div class="input-group-append">--}}
                                {{--                                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <span class="errs error-name"></span>--}}
                                {{--                                    </div>--}}

                                {{--                                    <div class="form-group m-form__group">--}}
                                {{--                                        <label>--}}
                                {{--                                            {{ __('Thời gian hiệu lực đến') }}:--}}
                                {{--                                        </label>--}}
                                {{--                                        <div class="input-group">--}}
                                {{--                                            <input type="text" class="form-control m-input" readonly="" placeholder="@lang('Ngày kết thúc')" id="end_effect_time" name="end_effect_time">--}}
                                {{--                                            <div class="input-group-append">--}}
                                {{--                                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <span class="errs error-name"></span>--}}
                                {{--                                    </div>--}}
                            </div>
                        </div>
                        <!--end: Form Wizard Step 1-->

                        <!--begin: Form Wizard Step 2-->
                        <div class="m-wizard__form-step m-wizard__form-step--current" id="m_wizard_form_step_2">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{ __('Chọn giá trị cần tính') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="commission_type" id="commission_type" class="form-control"
                                                    onchange="commission.changeType()">
                                                <option value="order">@lang('Theo doanh thu đơn hàng')</option>
                                                <option value="kpi">@lang('Theo KPI')</option>
                                                <option value="contract">@lang('Theo hợp đồng')</option>
                                            </select>
                                        </div>
                                        <span class="errs error-name"></span>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{ __('Loại hoa hồng') }}: <b class="text-danger">*</b>
                                        </label>

                                        <a class="quotetag" href="#">[?]
                                            <span class="classic">
                                                <strong>+ M1 (Dạng mức):</strong> Khi đơn hàng vào khoảng nào sẽ nhân giá trị khoảng tương ứng.<br>
                                                <strong>+ M2 (Dạng bậc thang):</strong> Chia nhỏ đơn hàng để nhân giá trị rồi cộng lại.<br>
                                                +VD: Giá trị đơn hàng = 650.000<br>
                                                Khoảng giá trị: <br>
                                                0-100.000 => 2%,<br>
                                                100.000-500.000 => 3%,<br>
                                                500.000 trở lên => 5%<br>
                                                => Tính theo K1 : = 650.000 x 5%<br>
                                                => Tính theo K2 : = 100.000 x 2% + (500.000-100.000) x 3% + (650.000-500.000) x 5%<br>
                                            </span>
                                        </a>

                                        <div class="input-group">
                                            <div class="col-lg-4">
                                                <input type="radio" id="commission_calc_by" name="commission_calc_by"
                                                       value="0" checked>
                                                <label for="commission_calc_by">Theo dạng mức</label><br>
                                            </div>

                                            <div class="col-lg-4">
                                                <input type="radio" id="commission_calc_by" name="commission_calc_by"
                                                       value="1">
                                                <label for="commission_calc_by">Theo dạng bậc thang</label><br>
                                            </div>
                                        </div>
                                        <span class="errs error-name"></span>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{ __('Tính theo giá trị của') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="commission_scope" id="commission_scope"
                                                    onchange="commission.changeScope()"
                                                    class="form-control">
                                                <option value="personal">@lang('Cá nhân')</option>
                                                <option value="group">@lang('Theo nhóm')</option>
                                            </select>
                                        </div>
                                        <span class="errs error-name"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 div_col_right">

                                </div>
                            </div>
                            <div class="form-group div_table">

                            </div>
                        </div>

                        <!--end: Form Wizard Step 2-->
                    </div>

                    <!--end: Form Body -->

                    <!--begin: Form Actions -->
                    <div class="m-portlet__foot m-portlet__foot--fit m--margin-top-40">
                        <div class="m-form__actions m-form__actions">
                            <div class="m--align-right">
                                <button class="btn btn-metal m-btn m-btn--custom m-btn--icon"
                                        data-wizard-action="prev">
																	<span>
																		<i class="la la-arrow-left"></i>&nbsp;&nbsp;
																		<span>@lang('Trở về')</span>
																	</span>
                                </button>
                                <button class="btn btn-success m-btn m-btn--custom m-btn--icon"
                                        data-wizard-action="submit">
																	<span>
																		<i class="la la-check"></i>&nbsp;&nbsp;
																		<span>@lang('Lưu')</span>
																	</span>
                                </button>
                                <button class="btn btn-info m-btn m-btn--custom m-btn--icon"
                                        data-wizard-action="next">
																	<span>
																		<span>@lang('Tiếp theo')</span>&nbsp;&nbsp;
                                                                        <i class="la la-arrow-right"></i>
																	</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!--end: Form Actions -->
                </form>
            </div>

            <!--end: Form Wizard Form-->
        </div>
    </div>
    {{--</form>--}}
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

    <script src="{{ asset('static/backend/js/admin/commission/script.js?v=' . time()) }}"></script>

    <script>
        WizardDemo.init('create');
        commission.changeType();
    </script>

    <script type="text/template" id="col-right-order-tpl">
        <div class="form-group m-form__group">
            <label>
                {{ __('Áp dụng cho đơn hàng có loại hàng hoá') }}: <b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="order_commission_type" id="order_commission_type"
                        class="form-control" onchange="commission.changeOrderType()">
                    <option value="all">@lang('Tất cả')</option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
            </div>
            <span class="errs error-name"></span>
        </div>
        <div class="form-group m-form__group">
            <label>
                {{ __('Áp dụng cho đơn hàng có nhóm hàng hoá') }}:
            </label>
            <div class="input-group">
                <select name="order_commission_group_type" id="order_commission_group_type"
                        onchange="commission.changeOrderGroup()"
                        class="form-control">
                    <option value="all" selected>@lang('Tất cả')</option>
                </select>
            </div>
            <span class="errs error-display"></span>
        </div>
        <div class="form-group m-form__group">
            <label>
                {{ __('Áp dụng đơn hàng có hàng hoá') }}: <b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="order_commission_object_type" id="order_commission_object_type" multiple
                        class="form-control">
                    <option value="all" selected>@lang('Tất cả')</option>
                </select>
            </div>
            <span class="errs error-name"></span>
        </div>
        <div class="form-group m-form__group">
            <label>
                {{ __('Tính theo phiếu thu của đơn hàng (đã thanh toán)')}}: <b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select class="form-control" name="order_commission_calc_by" id="order_commission_calc_by">
{{--                    <option value="paid-not-ship">--}}
{{--                        @lang('Từng phiếu thu không bao gồm phí vận chuyển')--}}
{{--                    </option>--}}
                    <option value="paid-ship">
                        @lang('Từng phiếu thu bao gồm phí vận chuyển')
                    </option>
{{--                    <option value="total-paid-not-ship">--}}
{{--                        @lang('Phiếu thu của đơn hàng ở trạng thái đã thanh toán không bao gồm phí vận chuyển')--}}
{{--                    </option>--}}
                    <option value="total-paid-ship">
                        @lang('Phiếu thu của đơn hàng ở trạng thái đã thanh toán bao gồm phí vận chuyển')
                    </option>
                </select>
            </div>
            <span class="errs error-name"></span>
        </div>
    </script>

    <script type="text/template" id="col-right-kpi-tpl">
        <div class="form-group m-form__group">
            <label>
                {{ __('Tính theo') }}: <b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="kpi_commission_calc_by" id="kpi_commission_calc_by"
                        class="form-control">
                    <option value="all">@lang('Tất cả')</option>
                </select>
            </div>
            <span class="errs error-name"></span>
        </div>
    </script>

    <script type="text/template" id="col-right-contract-tpl">
        <div class="form-group m-form__group">
            <label>
                {{ __('Tính theo') }}: <b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="contract_commission_calc_by" id="contract_commission_calc_by" onchange="commission.changeContractCalcBy(this)"
                        class="form-control">
                    <option value="all-paid">@lang('Tổng số hợp đồng đã thanh toán')</option>
{{--                    <option value="all-half-paid">@lang('Tổng số hợp đồng thanh toán từng phần')</option>--}}
                    <option value="paid-revenue">@lang('Doanh thu hợp đồng đã thanh toán')</option>
                    <option value="half-paid-revenue">@lang('Doanh thu hợp đồng thanh toán từng phần')</option>
                </select>
            </div>
            <span class="errs error-name"></span>
        </div>
        <div class="form-group m-form__group">
            <label>
                {{ __('Loại hợp đồng') }}:
            </label>
            <div class="input-group">
                <select name="contract_commission_type" id="contract_commission_type"
                        class="form-control">
                    <option value="0">@lang('Tất cả')</option>
                    @foreach ($optionContractCategory as $v)
                        <option value="{{ $v['contract_category_id'] }}">{{ $v['contract_category_name'] }}</option>
                    @endforeach
                </select>
            </div>
            <span class="errs error-display"></span>
        </div>
        <div class="form-group m-form__group">
            <label>
                {{ __('Điều kiện là') }}: <b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="contract_commission_condition" id="contract_commission_condition"
                        class="form-control">
                    <option value="all">@lang('Tất cả')</option>
                    <option value="new">@lang('Bán mới')</option>
                    <option value="extend">@lang('Gia hạn')</option>
                    <option value="renew">@lang('Tái ký (đã hoàn thành hồ sơ)')</option>
                </select>
            </div>
            <span class="errs error-name"></span>
        </div>
        <div class="form-group m-form__group">
            <label>
                {{ __('Thời hạn hợp đồng') }}: <b class="text-danger">*</b>
            </label>

            <div class="row">
                <div class="col-lg-3">
                    <select name="contract_commission_operation" onchange="commission.changeContractOperation()"
                            id="contract_commission_operation" class="form-control">
                        <option value="no_limit">@lang('Không giới hạn')</option>
                        <option value=">">&gt;</option>
                        <option value="=">&#61;</option>
                        <option value="<">&lt;</option>
                    </select>
                </div>

                <div class="input-group col-lg-9" style="padding-left: 0px;">
                    <input type="number" id="contract_commission_time" name="contract_commission_time"
                           class="form-control m-input class" value="1" disabled>
                </div>
            </div>

            <span class="errs error-name"></span>
        </div>
        <div class="form-group m-form__group">
            <label>
                {{ __('Áp dụng cho đối tượng') }}: <b class="text-danger">*</b>
            </label>
            <div>

            </div>
            <div class="input-group">
                <select name="contract_commission_apply" id="contract_commission_apply"
                        class="form-control">
                    <option value="all">@lang('Tất cả')</option>
                    <option value="internal">@lang('Nội bộ')</option>
                    <option value="external">@lang('Bên ngoài')</option>
                    <option value="partner">@lang('Đại lý')</option>
                </select>
            </div>
            <span class="errs error-name"></span>
        </div>
    </script>

    <script type="text/template" id="order-template">
        <tr class="tr_template">
            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="min-order-{stt}" name="min-order"
                           value="0">

                    <div class="input-group-append">
                        <span class="input-group-text text_type_default">@lang('VNĐ')</span>
                    </div>
                </div>
                <span class="error_valid_min_value_{stt} color_red"></span>
                <input type="hidden" class="number" value="{stt}">
            </td>

            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="max-order-{stt}" name="max-order"
                           value="0">

                    <div class="input-group-append">
                        <span class="input-group-text text_type_default">@lang('VNĐ')</span>
                    </div>
                </div>
                <span class="error_valid_max_value_{stt} color_red"></span>
            </td>

            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="order-commission-value-{stt}"
                           name="order-commission-value" value="0">

                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary active">
                                <input type="radio" name="config-operation-{stt}" checked
                                       value="0"> @lang('VNĐ')
                            </label>
                            <label class="btn btn-secondary">
                                <input type="radio" name="config-operation-{stt}" value="1">
                                %
                            </label>
                        </div>
                    </div>
                </div>
                <span class="error_valid_commission_value_{stt} color_red"></span>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="commission.removeTr(this, 'order')"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                   title="Delete">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>

    <script type="text/template" id="kpi-template">
        <tr class="tr_template">
            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="min-kpi-{stt}" name="min-kpi"
                           value="0">

                    <div class="input-group-append">
                        <span class="input-group-text text_type_default">%</span>
                    </div>
                </div>
                <span class="error_valid_min_kpi_{stt} color_red"></span>
                <input type="hidden" class="number" value="{stt}">
            </td>

            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="max-kpi-{stt}" name="max-kpi"
                           value="0">

                    <div class="input-group-append">
                        <span class="input-group-text text_type_default">%</span>
                    </div>
                </div>
                <span class="error_valid_max_kpi_{stt} color_red"></span>
            </td>

            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="kpi-commission-value-{stt}"
                           name="kpi-commission-value" value="0">

                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary active">
                                <input type="radio" name="config-operation-{stt}" checked
                                       value="0"> @lang('VNĐ')
                            </label>
                            {{--                            <label class="btn btn-secondary">--}}
                            {{--                                <input type="radio" name="config-operation-{stt}" value="1">--}}
                            {{--                                %--}}
                            {{--                            </label>--}}
                        </div>
                    </div>
                </div>
                <span class="error_valid_commission_value_{stt} color_red"></span>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="commission.removeTr(this, 'kpi')"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                   title="Delete">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>

    <script type="text/template" id="contract-template">
        <tr class="tr_template">
            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="min-contract-{stt}"
                           name="min-contract" value="0">

{{--                    <div class="input-group-append">--}}
{{--                        <span class="input-group-text text_type_default">@lang('VNĐ')</span>--}}
{{--                    </div>--}}
                </div>
                <span class="error_valid_min_contract_{stt} color_red"></span>
                <input type="hidden" class="number" value="{stt}">
            </td>

            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="max-contract-{stt}"
                           name="max-contract" value="0">

{{--                    <div class="input-group-append">--}}
{{--                        <span class="input-group-text text_type_default">@lang('VNĐ')</span>--}}
{{--                    </div>--}}
                </div>
                <span class="error_valid_max_contract_{stt} color_red"></span>
            </td>

            <td>
                <div class="input-group" style="padding-left: 0px;">
                    <input type="text" class="form-control m-input numeric_child" id="contract-commission-value-{stt}"
                           name="contract-commission-value" value="0">

                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary active label_config_operation_money_{stt}">
                                <input type="radio" name="config-operation-{stt}" checked
                                       value="0"> @lang('VNĐ')
                            </label>
                            <label class="btn btn-secondary label_config_operation_percent_{stt}">
                                <input type="radio" name="config-operation-{stt}" value="1">
                                %
                            </label>
                        </div>
                    </div>
                </div>
                <span class="error_valid_commission_value_{stt} color_red"></span>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="commission.removeTr(this, 'contract')"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                   title="Delete">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop
