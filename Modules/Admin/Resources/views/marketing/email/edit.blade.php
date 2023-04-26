@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-email.png')}}" alt="" style="height: 20px;"> {{__('EMAIL')}}</span>
@stop
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        .modal-lg {
            max-width: 65% !important;
        }

    </style>

    @include('admin::marketing.email.modal-excel')
    @include('admin::marketing.email.model-content-customer')
    @include('admin::marketing.email.modal-customer')
    @include('admin::marketing.email.modal-customer-group')
    @include('admin::marketing.email.modal-customer-lead')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA CHIẾN DỊCH')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">

            <div class="m-portlet__body">
                <input type="hidden" id="campaign_id" name="campaign_id" value="{{$item['campaign_id']}}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Tên chiến dịch')}}:<b class="text-danger">*</b></label>

                            <input class="form-control" id="name_edit" name="name_edit"
                                   placeholder="{{__('Hãy nhập tên chiến dịch')}}..." value="{{$item['name']}}">
                            <span class="error_slug" style="color: #ff0000"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Chi nhánh')}}:<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <select name="branch_id_edit" id="branch_id_edit" class="form-control m-input"
                                        style="width: 100%">
                                    <option value="">{{__('Chọn chi nhánh')}}</option>
                                    @foreach($optionBranch as $key=>$value)
                                        @if($item['branch_id']==$key)
                                            <option value="{{$key}}" selected>{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Chi phí chiến dịch')}}:<b class="text-danger">*</b></label>
                                <input name="cost_edit" id="cost_edit"
                                       class="form-control m-input class"
                                       placeholder="{{__('Hãy nhập chi phí cho chiến dịch')}}"
                                       value="{{number_format($item['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                       aria-describedby="basic-addon1">
                            <span class="error_slug" style="color: #ff0000"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Cho phép tạo deal'):<b class="text-danger">*</b>
                            </label>
                            <div>
                                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" id="is_deal_created" name="is_deal_created"
                                               onchange="edit.changeCreateDeal();"
                                               {{$item['is_deal_created'] == 1 ? 'checked' : ''}}
                                               class="manager-btn">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group m-form__group" id="popup_create_deal" {{$item['is_deal_created'] == 0 ? 'hidden' : ''}}>
                            <a href="javascript:void(0)" onclick="edit.popupEditLead({{$item['campaign_id']}})" class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                <i class="la la-plus"></i>@lang('Thêm thông tin deal')</a>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Tham số')}}:</label>
                            <div class="row">
                                <div class="col-md-5 col-xs-6  m--margin-top-10">
                                    <button type="button" class="btn btn-secondary active param_email_auto"
                                            onclick="edit.append_para('{name}')"
                                            style="width: 100%">{{__('Tên khách hàng')}}
                                    </button>
                                </div>
                                <div class="col-md-5 col-xs-6  m--margin-top-10">
                                    <button type="button" class="btn btn-secondary  active param_email_auto"
                                            onclick="edit.append_para('{full_name}')" style="width: 100%">{{__('Họ & Tên')}}
                                    </button>
                                </div>
                                <div class="col-md-5 col-xs-6  m--margin-top-10">
                                    <button type="button" class="btn btn-secondary  active param_email_auto"
                                            onclick="edit.append_para('{gender}')"
                                            style="width: 100%">{{__('Giới tính')}}
                                    </button>
                                </div>
                                <div class="col-md-5 col-xs-6  m--margin-top-10">
                                    <button type="button" class="btn btn-secondary active param_email_auto"
                                            onclick="edit.append_para('{birthday}')" style="width: 100%">{{__('Ngày sinh')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Nội dung mẫu')}}:</label>
                            <div class="m-scrollable m-scroller ps ps--active-y scroll_son" data-scrollable="true"
                                 style="height: 280px; overflow: hidden;">
                                <div class="content" id="content"></div>
                            </div>
                            {{--<textarea class="form-control" cols="50" rows="15" id="content"--}}
                            {{--name="content">{{$item['content']}}</textarea>--}}
                            <input type="hidden" id="content_hidden" value="{{$item['content']}}">
                            <span class="error_content_html" style="color: #ff0000"></span>

                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label>{{__('Thời gian gửi')}}:<b class="text-danger">*</b></label>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <div class="input-group date">
                                @if($item['is_now']==1)
                                    <input type="text" readonly class="form-control  m-input"
                                           placeholder="{{__('Chọn ngày gửi')}}"
                                           id="day_sent" name="day_sent"
                                           value="{{date('d/m/Y',strtotime($item['value']))}}" disabled>
                                @else
                                    <input type="text" readonly class="form-control  m-input"
                                           placeholder="{{__('Chọn ngày gửi')}}"
                                           id="day_sent" name="day_sent"
                                           value="{{date('d/m/Y',strtotime($item['value']))}}">
                                @endif

                                <div class="input-group-append">
                        <span class="input-group-text">
                        <i class="la la-calendar"></i>
                        </span>
                                </div>

                            </div>
                            <span class="error_time" style="color: #ff0000">

                            </span>
                            <div class="m-checkbox-list m--margin-top-10">
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success">
                                    @if($item['is_now']==1)
                                        <input type="checkbox" id="is_now" name="is_now" value="1" checked> {{__('Gửi ngay')}}
                                    @else
                                        <input type="checkbox" id="is_now" name="is_now" value="0"> {{__('Gửi ngay')}}
                                    @endif

                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group  col-lg-6">
                            <div class="input-group timepicker">
                                @if($item['is_now']==1)
                                    <input class="form-control m-input" id="time_sent" name="time_sent" readonly=""
                                           placeholder="Chọn giờ gửi..."
                                           type="text" value="{{date('H:i',strtotime($item['value']))}}" disabled>
                                @else
                                    <input class="form-control m-input" id="time_sent" name="time_sent" readonly=""
                                           placeholder="Chọn giờ gửi..."
                                           type="text" value="{{date('H:i',strtotime($item['value']))}}">
                                @endif

                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group bdb_email">
{{--                    @if(in_array('admin.email.save-log',session('routeList')))--}}
                        <button type="button" class="btn btn-info color_button son-mb m-btn--wide"
                                onclick="edit.submit_edit()">
                            <i class="la la-edit"></i> <span class="middle_text">{{__('CHỈNH SỬA')}}</span>

                        </button>
                        {{--<button type="button"--}}
                                {{--class="btn btn-info color_button son-mb m-btn--wide m--margin-left-10 bt-send"--}}
                                {{--onclick="edit.send_mail()">--}}
                            {{--<i class="fa fa-plus-circle"></i> <span class="middle_text"> {{__('GỬI EMAIL')}}</span>--}}
                        {{--</button>--}}
                    {{--@endif--}}
                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-6">
                            <span class="font-13 font-weight-bold">{{__('DANH SÁCH KHÁCH HÀNG NHẬN EMAIL')}}</span>
                        </div>
                        <div class="col-lg-6">
                            <div class="float-right width_right_mb">
                                <a href="javascript:void(0)" onclick="edit.modal_customer_lead()"
                                   class="btn btn-sm m-btn--icon color son-mb">
                                        <span>
                                            <i class="fa fa-plus-circle"></i>
                                            <span>
                                                {{__('Thêm khách hàng tiềm năng')}}
                                            </span>
                                        </span>
                                </a>
                                <a href="javascript:void(0)" onclick="edit.modal_customer_group()"
                                   class="btn btn-sm m-btn--icon color son-mb">
                                        <span>
                                            <i class="fa fa-plus-circle"></i>
                                            <span>
                                                {{__('Thêm khách hàng tự định nghĩa')}}
                                            </span>
                                        </span>
                                </a>
{{--                                @if(in_array('admin.email.save-log',session('routeList')))--}}
                                    <a href="javascript:void(0)" onclick="edit.modal_customer()"
                                       class="btn btn-sm m-btn--icon color son-mb">
                                        <span>
                                            <i class="fa fa-plus-circle"></i>
                                            <span>
                                                {{__('Thêm khách hàng')}}
                                            </span>
                                        </span>
                                    </a>
{{--                                    <a href="javascript:void(0)" onclick="edit.modal_file()"--}}
{{--                                       class="btn btn-sm m-btn--icon color son-mb m--margin-left-10">--}}
{{--                                        <span>--}}
{{--                                            <i class="la la-files-o"></i>--}}
{{--                                            <span>--}}
{{--                                                {{__('Chọn file')}}--}}
{{--                                            </span>--}}
{{--                                        </span>--}}
{{--                                    </a>--}}
                                {{--@endif--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4 append m-section__content" id="m_blockui_1_content">
                    <div class="table-responsive m--margin-top-10">
                        <div class="m-scrollable m-scroller ps ps--active-y w-100" data-scrollable="true"
                             style="height: 200px; overflow: hidden;">
                            <table class="table table-striped m-table m-table--head-bg-default table_list">
                                <thead class="bg">
                                <tr>
                                    <th class="tr_thead_list">#</th>
                                    <th class="tr_thead_list">{{__('Khách hàng')}}</th>
                                    <th class="tr_thead_list">{{__('Email')}}</th>
                                    <th class="tr_thead_list">{{__('Nội dung')}}</th>
                                    <th class="tr_thead_list"></th>
                                </tr>
                                </thead>
                                <tbody class="table_list_body" style="font-size: 13px">
                                @if(count($list_log)>0)
                                    @foreach($list_log as $key=>$value)
                                        <tr class="old">
                                            <td class="stt">{{$key+1}}
                                            </td>
                                            <input type="hidden" name="id" value="{{$value['id']}}">
                                            <td>{{$value['customer_name']}}
                                                <input type="hidden" name="name" value="{{$value['customer_name']}}">
                                            </td>
                                            <td>{{$value['email']}}
                                                <input type="hidden" name="email" value="{{$value['email']}}">
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" onclick="edit.seen_content_old(this)"><i
                                                            class="la la-eye"></i></a>
                                                {{--<input type="hidden" name="content" value="{{$value['content_sent']}}">--}}
                                                <textarea style="display:none;"
                                                          name="content">{{$value['content_sent']}}</textarea>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" onclick="edit.remove_old(this)"
                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btn-delete"
                                                   title="Xóa" data-value="{{$value['email']}}">
                                                    <i class="la la-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <span class="tb_log" style="color: #ff0000"></span>
                    </div>
                </div>

            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid m--align-right">
                        <a href="{{route('admin.email')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" onclick="edit.save_log()"
                                class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add m--margin-left-10">
							<span>
							<i class="la la-check"></i>
                                <span>{{__('LƯU LẠI')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div id="my-modal-create">
        </div>
        <input type="hidden" id="load-modal-create" value="0">
        <div id="my-modal-edit">
        </div>
        <input type="hidden" id="load-modal-edit" value="0">
        <input type="hidden" id="switch_deal_created" value="0">
    </div>



@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};

        @if($item['is_deal_created'] == 1)
        $.getJSON(laroute.route('translate'), function (json) {
            $('#my-modal-create').html('');
            $.ajax({
                url: laroute.route('admin.email.email-popup-edit-deal'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'email_campaign_id': {{$item['campaign_id']}}
                },
                success: function (res) {
                    $('#my-modal-edit').html(res.html);

                    $('#pipeline_code').select2({
                        placeholder: json['Chọn pipeline']
                    });

                    $('#journey_code').select2({
                        placeholder: json['Chọn hành trình']
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
    </script>
    <script src="{{asset('static/backend/js/admin/marketing/email/edit.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/marketing/email/add.js?v='.time())}}" type="text/javascript"></script>
    {{--<script src="{{asset('static/backend/js/admin/marketing/email/send-mail.js')}}" type="text/javascript"></script>--}}
    <script type="text/template" id="customer-list-tpl">
        <tr>
            <td width="5%">{stt}</td>
            <td width="50%" style="white-space: normal">
                {name}
                <input type="hidden" name="customer_id" value="{customer_id}">
            </td>
            <td width="10%">
                {email}
            </td>
            <td width="10%">{birthday}</td>
            <td width="10%">{gender}</td>
            <td width="10%">{branch_name}</td>
            <td width="10%">
                <label class="m-checkbox m-checkbox--air">
                    <input class="check" name="check" {is_checked} type="checkbox" onchange="edit.checkCustomer('[{customer_id}]')">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
    <script type="text/template" id="customer-group-list-tpl">
        <tr>
            <td width="5%">{stt}</td>
            <td width="50%" style="white-space: normal">
                {name}
                <input type="hidden" name="customer_id" value="{customer_id}">
            </td>
            <td width="10%">
                {email}
            </td>
            <td width="10%">{birthday}</td>
            <td width="10%">{gender}</td>
            <td width="10%">{branch_name}</td>
            <td width="10%">
                <label class="m-checkbox m-checkbox--air">
                    <input class="check-group" name="check-group" {is_checked} type="checkbox" onchange="edit.checkCustomer('[{customer_id}]')">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
    <script type="text/template" id="customer-lead-list-tpl">
        <tr>
            <td width="5%">{stt}</td>
            <td width="30%" style="white-space: normal">
                {name}
                <input type="hidden" name="customer_lead_id" value="{customer_lead_id}">
            </td>
            <td width="20%">
                {email}
            </td>
            <td width="10%">{sale_name}
            </td>
            <td width="10%">{customer_type}</td>
            <td width="10%">{customer_source_name}</td>
            <td width="10%">{pipeline_name}</td>
            <td width="10%">{journey_name}</td>
            <td width="10%">
                <label class="m-checkbox m-checkbox--air">
                    <input class="check-lead" name="check-lead" {is_checked} type="checkbox" onchange="edit.checkCustomerLead('[{customer_lead_id}]')">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
    <script type="text/template" id="list-send-tpl">
        <tr class="send">
            <td width="5%" class="stt">{stt}</td>
            <td width="30%" title="{name_title}" style="white-space: normal">
                {name}
                <input type="hidden" name="name" value="{name}">
            </td>
            <td width="10%">{email}
                <input type="hidden" name="email" value="{email}">
            </td>
            <td width="10%">
                <a href="javascript:void(0)" onclick="edit.seen_content(this)"><i class="la la-eye"></i></a>
                <textarea style="display:none;" name="content">{content}</textarea>
            </td>
            <td width="10%" hidden>
                <input type="hidden" name="type_customer" value="{type_customer}">
            </td>
            <td width="10%" hidden>
                <input type="hidden" name="object_id" value="{object_id}">
            </td>
            <td width="10%">
                <a href="javascript:void(0)" onclick="edit.remove(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btn-delete"
                   title="Xóa" data-value="{email}">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-object">
    <tr class="add-object">
        <td style="width:15%;">
            <select class="form-control object_type" style="width:100%;"
                    onchange="dealEmail.changeObjectType(this)">
                <option></option>
                <option value="product">@lang('Sản phẩm')</option>
                <option value="service">@lang('Dịch vụ')</option>
                <option value="service_card">@lang('Thẻ dịch vụ')</option>
            </select>
            <span class="error_object_type color_red"></span>
        </td>
        <td style="width:25%;">
            <select class="form-control object_code" style="width:100%;"
                    onchange="dealEmail.changeObject(this)">
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
