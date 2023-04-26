@extends('layout')
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
@endsection
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('QUẢN LÝ HOA HỒNG') }}
    </span>
@endsection
@section('content')
    <form id="form-banner" autocomplete="off">
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="la la-th-list"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            {{ __('CHI TIẾT HOA HỒNG') }}
                        </h3>
                    </div>
                </div>

                <div class="m-portlet__head-tools nt-class">
                    <a href="{{ route('admin.commission') }}"
                       class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>TRỞ VỀ</span>
                        </span>
                    </a>
                </div>
            </div>

            <div class="m-portlet__body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="commission-tab" data-toggle="tab" href="#commision" role="tab"
                           aria-controls="home" aria-selected="true">@lang('Thông tin hoa hồng')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab"
                           aria-controls="profile" aria-selected="false">@lang('Công thức tính')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="staff-tab" data-toggle="tab" href="#staff" role="tab"
                           aria-controls="contact" aria-selected="false">@lang('Áp dụng cho nhân viên')</a>
                    </li>
                </ul>

                <!-- Detail page -->
                <div class="tab-content" id="myTabContent">
                    @if(isset($item))
                        <!-- Thông tin hoa hồng -->
                        <div class="tab-pane fade show active" id="commision" role="tabpanel"
                             aria-labelledby="commission-tab">
                            <div id="add-commission-step-1">
                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Tên hoa hồng') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <input id="commission_name" name="commission_name" type="text"
                                               class="form-control m-input class"
                                               value="{{$item['commission_name']}}" disabled
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
                                                multiple="multiple" disabled>
                                            @if (isset($TAG_LIST))
                                                @foreach ($TAG_LIST as $tag_item)
                                                    <option value="{{$tag_item['tags_id']}}" {{in_array($tag_item['tags_name'], $item['tag']) ? 'selected': ''}}>
                                                        {{ $tag_item['tags_name'] }}
                                                    </option>
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
                                            <input type="checkbox" id="status" checked class="manager-btn"
                                                   {{$item['status'] == 1 ? 'checked': ''}} disabled>
                                            <span></span>
                                            </label>
                                            </span>
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
                                {{--                                            <input type="text" class="form-control" id="apply_time" name="apply_time"--}}
                                {{--                                                   value="{{$item['apply_time']}}" disabled>--}}

                                {{--                                            <div class="input-group-append">--}}
                                {{--                                                <select class="form-control" id="apply_time_type"--}}
                                {{--                                                        name="apply_time_type" disabled>--}}
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
                                {{--                                            <input type="text" class="form-control" value="{{$item['calc_apply_time']}}"--}}
                                {{--                                                   disabled>--}}
                                {{--                                        </div>--}}
                                {{--                                        <span class="errs error-display"></span>--}}
                                {{--                                    </div>--}}

                                {{--                                    <div class="form-group m-form__group">--}}
                                {{--                                        <label>--}}
                                {{--                                            {{ __('Thời gian hiệu lực từ') }}: <b class="text-danger">*</b>--}}
                                {{--                                        </label>--}}

                                {{--                                        <div class="input-group date">--}}
                                {{--                                            <input type="text" class="form-control m-input" readonly=""--}}
                                {{--                                                   placeholder="@lang('Ngày bắt đầu')" id="start_effect_time"--}}
                                {{--                                                   name="start_effect_time"--}}
                                {{--                                                   value="{{\Carbon\Carbon::parse($item['start_effect_time'])->format('d/m/Y')}}"--}}
                                {{--                                                   disabled>--}}
                                {{--                                            <div class="input-group-append">--}}
                                {{--                                                <span class="input-group-text"><i--}}
                                {{--                                                            class="la la-calendar-check-o glyphicon-th"></i></span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <span class="errs error-name"></span>--}}
                                {{--                                    </div>--}}

                                {{--                                    <div class="form-group m-form__group">--}}
                                {{--                                        <label>--}}
                                {{--                                            {{ __('Thời gian hiệu lực đến') }}:--}}
                                {{--                                        </label>--}}
                                {{--                                        <div class="input-group">--}}
                                {{--                                            <input type="text" class="form-control m-input" readonly=""--}}
                                {{--                                                   placeholder="@lang('Ngày kết thúc')" id="end_effect_time"--}}
                                {{--                                                   name="end_effect_time"--}}
                                {{--                                                   value="{{\Carbon\Carbon::parse($item['end_effect_time'])->format('d/m/Y')}}"--}}
                                {{--                                                   disabled>--}}
                                {{--                                            <div class="input-group-append">--}}
                                {{--                                                <span class="input-group-text"><i--}}
                                {{--                                                            class="la la-calendar-check-o glyphicon-th"></i></span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <span class="errs error-name"></span>--}}
                                {{--                                    </div>--}}

                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Mô tả') }}:
                                    </label>
                                    <div class="input-group">
                                            <textarea id="description" name="description"
                                                      class="form-control m-input class" rows="6" disabled=""
                                                      cols="50">{{$item['description']}}</textarea>
                                    </div>
                                    <span class="errs error-display"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Công thức tính -->
                        <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{ __('Chọn giá trị cần tính') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="commission_type" id="commission_type" class="form-control"
                                                    onchange="commission.changeType()" disabled>
                                                <option value="order" {{$item['commission_type'] == 'order' ? 'selected': ''}}>@lang('Theo doanh thu đơn hàng')</option>
                                                <option value="kpi" {{$item['commission_type'] == 'kpi' ? 'selected': ''}}>@lang('Theo KPI')</option>
                                                <option value="contract" {{$item['commission_type'] == 'contract' ? 'selected': ''}}>@lang('Theo hợp đồng')</option>
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
                                                       disabled
                                                       value="0" {{$item['commission_calc_by'] == 0 ? 'checked': ''}}>
                                                <label for="commission_calc_by">Theo dạng mức</label><br>
                                            </div>

                                            <div class="col-lg-4">
                                                <input type="radio" id="commission_calc_by" name="commission_calc_by"
                                                       disabled
                                                       value="1" {{$item['commission_calc_by'] == 1 ? 'checked': ''}}>
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
                                                    class="form-control" disabled>
                                                <option value="personal" {{$item['commission_scope'] == 'personal' ? 'selected': ''}}>@lang('Cá nhân')</option>
                                                <option value="group" {{$item['commission_scope'] == 'group' ? 'selected': ''}}>@lang('Theo nhóm')</option>
                                                <option value="company" {{$item['commission_scope'] == 'company' ? 'selected': ''}}>@lang('Theo công ty')</option>
                                                <option value="branch" {{$item['commission_scope'] == 'branch' ? 'selected': ''}}>@lang('Theo chi nhánh')</option>
                                                <option value="department" {{$item['commission_scope'] == 'department' ? 'selected': ''}}>@lang('Theo phòng ban')</option>
                                            </select>
                                        </div>
                                        <span class="errs error-name"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 div_col_right">
                                    @switch($item['commission_type'])
                                        @case('order')
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{ __('Áp dụng cho đơn hàng có loại hàng hoá') }}: <b
                                                            class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="order_commission_type" id="order_commission_type"
                                                            disabled
                                                            class="form-control"
                                                            onchange="commission.changeOrderType()">
                                                        <option value="all" {{$item['order_commission_type'] == 'all' ? 'selected': ''}}>@lang('Tất cả')</option>
                                                        <option value="product" {{$item['order_commission_type'] == 'product' ? 'selected': ''}}>@lang('Sản phẩm')</option>
                                                        <option value="service" {{$item['order_commission_type'] == 'service' ? 'selected': ''}}>@lang('Dịch vụ')</option>
                                                        <option value="service_card" {{$item['order_commission_type'] == 'service_card' ? 'selected': ''}}>@lang('Thẻ dịch vụ')</option>
                                                    </select>
                                                </div>
                                                <span class="errs error-name"></span>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{ __('Áp dụng cho đơn hàng có nhóm hàng hoá') }}:
                                                </label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                           value="{{$item['order_group_name']}}" disabled>
                                                </div>
                                                <span class="errs error-display"></span>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{ __('Áp dụng đơn hàng có hàng hoá') }}: <b
                                                            class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                           value="{{$item['order_object_name']}}" disabled>
                                                </div>
                                                <span class="errs error-name"></span>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{ __('Tính theo phiếu thu của đơn hàng (đã thanh toán)')}}: <b
                                                            class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    @switch($item['order_commission_calc_by'])
                                                        @case('paid-not-ship')
                                                            <input type="text" class="form-control" disabled
                                                                   value="@lang('Từng phiếu thu không bao gồm phí vận chuyển')">
                                                            @break
                                                        @case('paid-ship')
                                                            <input type="text" class="form-control" disabled
                                                                   value="@lang('Từng phiếu thu bao gồm phí vận chuyển')">
                                                            @break
                                                        @case('total-paid-not-ship')
                                                            <input type="text" class="form-control" disabled
                                                                   value="@lang('Phiếu thu của đơn hàng ở trạng thái đã thanh toán không bao gồm phí vận chuyển')">
                                                            @break
                                                        @case('total-paid-ship')
                                                            <input type="text" class="form-control" disabled
                                                                   value="@lang('Phiếu thu của đơn hàng ở trạng thái đã thanh toán bao gồm phí vận chuyển')">
                                                            @break
                                                    @endswitch
                                                </div>
                                                <span class="errs error-name"></span>
                                            </div>
                                            @break;
                                        @case('kpi')
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{ __('Tính theo') }}: <b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="kpi_commission_calc_by" id="kpi_commission_calc_by"
                                                            class="form-control" disabled>
                                                        <option value="all" {{$item['kpi_commission_calc_by'] == 0 ? 'selected': ''}}>@lang('Tất cả')</option>
                                                        @foreach($optionCriteria as $v)
                                                            <option value="{{$v['kpi_criteria_id']}}" {{$item['kpi_commission_calc_by'] == $v['kpi_criteria_id'] ? 'selected': ''}}>{{$v['kpi_criteria_name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="errs error-name"></span>
                                            </div>
                                            @break;
                                        @case('contract')
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{ __('Tính theo') }}: <b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="contract_commission_calc_by"
                                                            id="contract_commission_calc_by" disabled
                                                            class="form-control">
                                                        <option value="all-paid" {{$item['contract_commission_calc_by'] == 'all-paid' ? 'selected': ''}}>@lang('Tổng số hợp đồng đã thanh toán')</option>
                                                        <option value="all-half-paid" {{$item['contract_commission_calc_by'] == 'all-half-paid' ? 'selected': ''}}>@lang('Tổng số hợp đồng thanh toán từng phần')</option>
                                                        <option value="paid-revenue" {{$item['contract_commission_calc_by'] == 'paid-revenue' ? 'selected': ''}}>@lang('Doanh thu hợp đồng đã thanh toán')</option>
                                                        <option value="half-paid-revenue" {{$item['contract_commission_calc_by'] == 'half-paid-revenue' ? 'selected': ''}}>@lang('Doanh thu hợp đồng thanh toán từng phần')</option>
                                                    </select>
                                                </div>
                                                <span class="errs error-name"></span>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{ __('Loại hợp đồng') }}:
                                                </label>
                                                <div class="input-group">
                                                    <select name="contract_commission_type"
                                                            id="contract_commission_type" disabled
                                                            class="form-control">
                                                        <option value="0" {{$item['contract_commission_type'] == 0 ? 'selected': ''}}>@lang('Tất cả')</option>
                                                        @foreach ($optionContractCategory as $v)
                                                            <option value="{{$v['contract_category_id']}}" {{$item['contract_commission_type'] == $v['contract_category_id'] ? 'selected': ''}}>{{ $v['contract_category_name'] }}</option>
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
                                                    <select name="contract_commission_condition"
                                                            id="contract_commission_condition" disabled
                                                            class="form-control">
                                                        <option value="all" {{$item['contract_commission_condition'] == 'all' ? 'selected': ''}}>@lang('Tất cả')</option>
                                                        <option value="new" {{$item['contract_commission_condition'] == 'new' ? 'selected': ''}}>@lang('Bán mới')</option>
                                                        <option value="extend" {{$item['contract_commission_condition'] == 'extend' ? 'selected': ''}}>@lang('Gia hạn')</option>
                                                        <option value="renew" {{$item['contract_commission_condition'] == 'renew' ? 'selected': ''}}>@lang('Tái ký (đã hoàn thành hồ sơ)')</option>
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
                                                        <select name="contract_commission_operation"
                                                                onchange="commission.changeContractOperation()" disabled
                                                                id="contract_commission_operation" class="form-control">
                                                            <option value="no_limit" {{$item['contract_commission_operation'] == 'no_limit' ? 'selected': ''}}>@lang('Không giới hạn')</option>
                                                            <option value=">" {{$item['contract_commission_operation'] == '>' ? 'selected': ''}}>
                                                                &gt;
                                                            </option>
                                                            <option value="=" {{$item['contract_commission_operation'] == '=' ? 'selected': ''}}>
                                                                &#61;
                                                            </option>
                                                            <option value="<"> {{$item['contract_commission_operation'] == '<' ? 'selected': ''}}
                                                                &lt;
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="input-group col-lg-9" style="padding-left: 0px;">
                                                        <input type="number" class="form-control m-input class"
                                                               id="contract_commission_time"
                                                               name="contract_commission_time"
                                                               value="{{number_format($item['contract_commission_time'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                                               disabled>
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
                                                    <select name="contract_commission_apply"
                                                            id="contract_commission_apply"
                                                            class="form-control" disabled>
                                                        <option value="all" {{$item['contract_commission_apply'] == 'all' ? 'selected': ''}}>@lang('Tất cả')</option>
                                                        <option value="internal" {{$item['contract_commission_apply'] == 'internal' ? 'selected': ''}}>@lang('Nội bộ')</option>
                                                        <option value="external" {{$item['contract_commission_apply'] == 'external' ? 'selected': ''}}>@lang('Bên ngoài')</option>
                                                        <option value="agency" {{$item['contract_commission_apply'] == 'agency' ? 'selected': ''}}>@lang('Đại lý')</option>
                                                        <option value="individual" {{$item['contract_commission_apply'] == 'individual' ? 'selected': ''}}>@lang('Cá nhân')</option>
                                                        <option value="bussiness" {{$item['contract_commission_apply'] == 'bussiness' ? 'selected': ''}}>@lang('Doanh nghiệp')</option>
                                                    </select>
                                                </div>
                                                <span class="errs error-name"></span>
                                            </div>
                                            @break;
                                    @endswitch
                                </div>
                            </div>

                            <div class="form-group div_table">
                                @switch($item['commission_type'])
                                    @case('order')
                                        @include('commission::components.detail.order-table')
                                        @break
                                    @case('kpi')
                                        @include('commission::components.detail.kpi-table')
                                        @break
                                    @case('contract')
                                        @include('commission::components.detail.contract-table')
                                        @break
                                @endswitch
                            </div>
                        </div>

                        <!-- Áp dụng cho nhân viên -->
                        <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">

                            <!-- Table review -->
                            <div class="table-content m--padding-top-30">
                                @include('commission::components.detail.staff-commission-table', ['STAFF_DATA' => $STAFF_DATA])
                            </div>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection

@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>

    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{ asset('static/backend/js/admin/commission/script.js?v=' . time()) }}"></script>
    <script>
        detail._init();
    </script>
@stop
