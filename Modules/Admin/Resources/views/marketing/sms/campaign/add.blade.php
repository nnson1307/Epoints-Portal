@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" style="height: 20px;">
        {{__('SMS')}}
    </span>
@endsection
@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÊM CHIẾN DỊCH')}}
                    </h3>

                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Tên chiến dịch')}}: <b class="text-danger">*</b>
                        </label>
                        <input id="name" type="text" class="form-control" placeholder="{{__('Nhập tên chiến dịch')}}">
                        <span class="text-danger error-name"></span>

                    </div>

                    <div class="form-group m-form__group">
                        <label class="black-title">{{__('Chi phí chiến dịch')}}:<b class="text-danger">*</b></label>
                            <input name="cost" id="cost"
                                   class="form-control m-input class"
                                   placeholder="{{__('Hãy nhập chi phí cho chiến dịch')}}"
                                   aria-describedby="basic-addon1">
                            <span class="text-danger error-cost"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Cho phép tạo deal'):<b class="text-danger">*</b>
                        </label>
                        <div>
                                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" id="is_deal_created" name="is_deal_created"
                                               onchange="AddCampaign.changeCreateDeal();"
                                               class="manager-btn">
                                        <span></span>
                                    </label>
                                </span>
                        </div>
                    </div>
                    <div class="form-group m-form__group" id="popup_create_deal" hidden>
                        <a href="javascript:void(0)" onclick="AddCampaign.popupCreateDeal()" class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                            <i class="la la-plus"></i>@lang('Thêm thông tin deal')</a>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Tham số')}}:
                        </label>
                        <div class="form-group m-form__group">
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-right-10 m--margin-bottom-5 ss--font-weight-200"
                                    onclick="AddCampaign.valueParameter('customer-name')">
                                    {{__('Tên khách hàng')}}
                            </button>
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-right-10 m--margin-bottom-5 ss--font-weight-200"
                                    onclick="AddCampaign.valueParameter('full-name')">{{__('Họ & Tên')}}
                            </button>
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-right-10 m--margin-bottom-5 ss--font-weight-200"
                                    onclick="AddCampaign.valueParameter('customer-birthday')">{{__('Ngày sinh')}}
                            </button>
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-bottom-5 ss--font-weight-200"
                                    onclick="AddCampaign.valueParameter('customer-gender')">
                                    {{__('Giới tính')}}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Nội dung tin nhắn mẫu')}}: <b class="text-danger">*</b>
                        </label>

                        <div class="form-group m-form__group">
                    <textarea onkeyup="AddCampaign.countCharacter(this)" id="message-content" rows="5" cols="40"
                              class="form-control m-input"
                              placeholder="{{__('Nhập tin nhắn mẫu')}}"></textarea>
                            <i class="pull-right">{{__('Số ký tự')}}: <i class="count-character">0</i>{{__('/480 ký tự')}}</i>
                            <span class="text-danger error-count-character"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label>
                    {{__('Thời gian gửi')}}: <b class="text-danger">*</b>
                </label>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input readonly="" class="form-control m-input daterange-picker"
                                   id="day-send"
                                   name="created_at" autocomplete="off" placeholder="Chọn ngày gửi">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                     <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="m-input-icon m-input-icon--right">
                            <input readonly="" class="form-control m-input daterange-picker"
                                   id="time-send"
                                   name="time-send" autocomplete="off" placeholder="Chọn giờ">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                     <span><i class="la la-clock-o"></i></span></span>
                        </div>
                    </div>
                </div>
                <span class="text-danger error-datetime"></span>

            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-6">
                    <select name="branchOption" class="form-control" id="branchOption">
                        <option value="">{{__('Chọn chi nhánh')}}</option>
                        @foreach($branch as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-branch"></span>
                </div>
            </div>
            <div class="form-group m-form__group">

                <div class="input-group">
                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                        <input id="is_now" type="checkbox">
                        {{__('Gửi ngay')}}
                        <span></span>
                    </label>
                </div>

            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m-form__actions--solid m--align-right">
                    <a href="{{route('admin.sms.sms-campaign')}}"
                       class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </a>
                        <button type="button" onclick="AddCampaign.saveInfo()"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                      	<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                </div>
            </div>

        </div>
    </div>

    <div id="my-modal-create">

    </div>
    <input type="hidden" id="load-modal-create" value="0">
    <!--end::Portlet-->

@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/marketing/sms/campaign/add.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="amount-day">
        <div class="form-group m-form__group">
            <div class="row">
                <label class="form-control-label col-lg-3">
                    {{__('Nhắc lịch trước')}}:
                </label>
                <input name="remindCalendarValue" id="remindCalendarValue" type="text"
                       class="form-control col-lg-3" value="">
                <div class="col-lg-1"></div>
                <select disabled name="remindCalendarOption"
                        id="remindCalendarOption"
                        class="form-control col-lg-3">
                    <option value="day">{{__('Ngày')}}</option>
                </select>
            </div>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-3 m--margin-left-45">
                    <span class="m-form__help err error-remindCalendarOption"></span>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="choose-time">
        <div class="form-group m-form__group row">

            <label class="form-control-label col-lg-3">
                {{__('Chọn giờ')}}:
            </label>
            <input id="start-time" class="form-control col-lg-3"
                   readonly="" placeholder="{{__('Chọn giờ')}}" type="text">

        </div>
    </script>
    <script type="text/template" id="choose-day-time">
        <div class="form-group m-form__group row">
            <label class="form-control-label col-lg-3">
                {{__('Thời gian gửi tin')}}:
            </label>
            <div class="m-input-icon m-input-icon--right col-lg-4" id="m_daterangepicker_6">
                <input readonly="" class="form-control m-input daterange-picker"
                       id="time" name="time" autocomplete="off"
                       placeholder="{{__('Chọn ngày')}}">
                <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
            </div>
            <div class="col-lg-1"></div>
            <input id="start-time" class="form-control col-lg-2"
                   readonly="" placeholder="{{__('Chọn giờ')}}" type="text">

        </div>
    </script>

    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="dealSms.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25%;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="dealSms.changeObject(this)">
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
                <a href="javascript:void(0)" onclick="dealSms.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop