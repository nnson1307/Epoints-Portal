@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ GÓI BẢO HÀNH')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('THÊM GÓI BẢO HÀNH')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
            <div class="m-portlet__body">
                <form id="form-create">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên gói bảo hành'):<b class="text-danger"> *</b>
                                </label>
                                <input type="text" class="form-control m-input"
                                       id="package_name" name="package_name"
                                       placeholder="@lang('Nhập tên gói bảo hành')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Giá trị bảo hành') (%):<b class="text-danger"> *</b>
                                </label>
                                <input type="text" class="form-control m-input format-money"
                                       id="percent" name="percent" placeholder="@lang('Nhập giá trị bảo hành')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Số tiền tối đa được được bảo hành'):<b class="text-danger"> *</b>
                                </label>
                                <input type="text" class="form-control m-input format-money"
                                       id="money_maximum" name="money_maximum"
                                       placeholder="@lang('Nhập số tiền tối đa được được bảo hành')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Thời hạn bảo hành'):<b class="text-danger"> *</b>
                                </label>
                                <div class="input-group">
                                    <div class="m-demo" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn ss--button-cms-piospa rdo active">
                                                <input type="radio" name="date-use" value="day" id="option1"
                                                       autocomplete="off" checked="">
                                                <span class="m--margin-left-5 m--margin-right-5">{{__('Ngày')}}</span>
                                            </label>
                                            <label class="btn btn-default rdo">
                                                <input type="radio" name="date-use" value="week" id="option2"
                                                       autocomplete="off">
                                                <span class="m--margin-left-5 m--margin-right-5">{{__('Tuần')}}</span>
                                            </label>
                                            <label class="btn btn-default rdo">
                                                <input type="radio" name="date-use" value="month" id="option3"
                                                       autocomplete="off">
                                                <span class="m--margin-left-5 m--margin-right-5">{{__('Tháng')}}</span>
                                            </label>
                                            <label class="btn btn-default rdo">
                                                <input type="radio" name="date-use" value="year" id="option4"
                                                       autocomplete="off">
                                                <span class="m--margin-left-5 m--margin-right-5">{{__('Năm')}}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control m-input"
                                               id="time_warranty" name="time_warranty">
                                        <span class="err error-time-warranty"></span>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="m-checkbox m-checkbox--air m--margin-top-10">
                                            <input id="time_warranty_unlimited" class="check-inventory-warning"
                                                   type="checkbox">
                                            {{__('Không giới hạn')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Số lần được bảo hành'):<b class="text-danger"> *</b>
                                        </label>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-group m-input-group">
                                                    <input name="number_warranty" id="number_warranty" style="text-align: right" type="text"
                                                           class="form-control" value="">
                                                    <div class="input-group-append">
                                                        <button class="btn ss--button-cms-piospa"><b>{{__('LẦN')}}</b>
                                                        </button>
                                                    </div>
                                                </div>
                                                <span class="err error-number-warranty"></span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="m-checkbox m-checkbox--air m--margin-top-10">
                                                    <input id="number_warranty_unlimited" class="check-inventory-warning"
                                                           type="checkbox">
                                                    {{__('Không giới hạn')}}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="col-lg-6">
    {{--                        <div class="form-group m-form__group">--}}
    {{--                            <label class="black_title">--}}
    {{--                                @lang('Trạng thái'):<b class="text-danger">*</b>--}}
    {{--                            </label>--}}
    {{--                            <div>--}}
    {{--                                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">--}}
    {{--                                    <label>--}}
    {{--                                        <input type="checkbox" id="is_actived"--}}
    {{--                                               onchange="" checked--}}
    {{--                                               class="manager-btn">--}}
    {{--                                        <span></span>--}}
    {{--                                    </label>--}}
    {{--                                </span>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
                            <div class="form-group m-form__group">
                                <label class="black_title">@lang('Mô tả ngắn'):</label>
                                <input type="text" class="form-control m-input"
                                       id="short_description" name="short_description"
                                       placeholder="@lang('Nhập mô tả ngắn')">
                            </div>
                            <div class="form-group m-form__group">
                                <label> {{__('Mô tả chi tiết')}}:</label>
                                <div class="summernote"></div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="form-group m-form__group mt-3">
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
                        <div class="form-group m-form__group" style="display: none;">
                            <button class="btn btn-primary color_button btn-search">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </form>
                    <div class="table-content div_table_discount">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('warranty-package')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="create.save()"
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
    <div id="my-modal"></div>
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
    <script src="{{asset('static/backend/js/warranty/warranty-package/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        create._init();
    </script>

@endsection
