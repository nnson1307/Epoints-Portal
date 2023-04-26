@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" style="height: 20px;">
        {{__('SMS')}}
    </span>
@endsection
@section('content')
    <style>
        .modal-lg {
            max-width: 85%;
        }

        input[type="file"] {
            display: none;
        }
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA CHIẾN DỊCH')}}
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

                        <input id="name" type="text" value="{{$campaign->name}}" class="form-control"
                               placeholder="{{__('Nhập tên chiến dịch')}}">
                        <span class="text-danger error-name"></span>

                    </div>

                    <div class="form-group m-form__group">
                        <label class="black-title">{{__('Chi phí chiến dịch')}}:<b class="text-danger">*</b></label>
                            <input name="cost_edit" id="cost_edit"
                                   class="form-control m-input class"
                                   placeholder="{{__('Hãy nhập chi phí cho chiến dịch')}}"
                                   value="{{number_format($campaign['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
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
                                               onchange="EditCampaign.changeCreateDeal();"
                                               {{$campaign['is_deal_created'] == 1 ? 'checked' : ''}}
                                               class="manager-btn">
                                        <span></span>
                                    </label>
                                </span>
                        </div>
                    </div>
                    <div class="form-group m-form__group" id="popup_create_deal" {{$campaign['is_deal_created'] == 0 ? 'hidden' : ''}}>
                        <a href="javascript:void(0)" onclick="EditCampaign.popupEditDeal({{$campaign['campaign_id']}})" class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                            <i class="la la-plus"></i>@lang('Thêm thông tin deal')</a>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Tham số')}}:
                        </label>
                        <div>
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-right-10 m--margin-bottom-5 ss--btn-mobiles ss--font-weight-200"
                                    onclick="EditCampaign.valueParameter('customer-name')">
                                    {{__('Tên khách hàng')}}
                            </button>
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-right-10 m--margin-bottom-5 ss--btn-mobiles ss--font-weight-200"
                                    onclick="EditCampaign.valueParameter('full-name')">
                                    {{__('Họ & Tên')}}
                            </button>
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-right-10 m--margin-bottom-5 ss--btn-mobiles ss--font-weight-200"
                                    onclick="EditCampaign.valueParameter('customer-birthday')">
                                    {{__('Ngày sinh')}}
                            </button>
                            <button href="javascript:void(0)"
                                    class="btn ss--btn-parameter m--margin-bottom-5 ss--btn-mobiles ss--font-weight-200"
                                    onclick="EditCampaign.valueParameter('customer-gender')">
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
                    <textarea onkeyup="EditCampaign.countCharacter(this)" rows="5" cols="40" id="message-content"
                              class="form-control m-input"
                              placeholder="{{__('Nhập tin nhắn mẫu')}}">{{$campaign->content}}</textarea>
                            <i class="pull-right">{{__('Số ký tự')}}: <i class="count-character">0</i>/480</i>
                            <span class="text-danger error-count-character">
                        </span>
                        </div>

                    </div>
                </div>
            </div>
            {{--<div class="form-group m-form__group row">--}}
            {{--<label for="" class="col-lg-2">--}}
            {{--Trạng thái: <b class="text-danger">*</b>--}}
            {{--</label>--}}
            {{--<div class="col-lg-3">--}}
            {{--<input type="text" class="form-control" disabled value="Mới">--}}
            {{--</div>--}}
            {{--</div>--}}
            <div class="form-group m-form__group">
                <label>
                    {{__('Thời gian gửi')}}: <b class="text-danger">*</b>
                </label>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input {{$campaign->is_now==1?'disabled':''}}  readonly=""
                                   class="form-control m-input daterange-picker"
                                   id="day-send"
                                   name="day-send" autocomplete="off" placeholder="{{__('Chọn ngày gửi')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                     <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input {{$campaign->is_now==1?'disabled':''}} readonly=""
                                   class="form-control m-input daterange-picker"
                                   id="time-send"
                                   name="created_at" autocomplete="off" placeholder="{{__('Chọn giờ')}}">
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
                            <option {{$campaign->branch_id==$key?'selected':''}} value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-branch"></span>
                </div>
            </div>
            <div class="form-group m-form__group">
                <div class="input-group">
                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                        <input {{$campaign->is_now==1?'checked':''}} id="is_now" type="checkbox">
                        {{__('Gửi ngay')}}
                        <span></span>
                    </label>
                </div>

            </div>
            <div class="form-group m-form__group">
                <div class="row">
                    <div class="col-lg-6">
                        {{--@if(in_array('admin.campaign.submit-edit',session('routeList')))--}}
                            <button onclick="EditCampaign.saveChange()" type="button"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn color_button m-btn--wide m--margin-right-10 m--margin-bottom-5">
                                <i class="la la-check"></i>
                                {{__('LƯU THÔNG TIN')}}
                            </button>
                            {{--<button type="button"--}}
                                    {{--class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn--wide m--margin-bottom-5">--}}
                                {{--<i class="fa fa-plus-circle"></i>--}}
                                {{--<span class="m--margin-left-5">{{__('GỬI TIN NHẮN')}}</span>--}}
                            {{--</button>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group m-form__group row">
                <div class="col-lg-12">
                    <div class="pull-left m--margin-right-5  ss--width--100 ss--text-btn-mobi">
                        <br>
                        <span class="m--margin-top-5 ss--font-weight-500 ss--font-size-13">{{__('DANH SÁCH KHÁCH HÀNG NHẬN TIN NHẮN')}}</span>
                    </div>
                    <div class="pull-right m--margin-right-5  ss--width--100 ss--text-btn-mobi">
                        <a data-toggle="modal" href="javascript:void(0)"
                           onclick="EditCampaign.emptyListCustomerLead()"
                           class="ss--padding-left-padding-right-1rem m-btn--wide ss--btn-mobiles btn btn-outline-successsss m-btn m-btn--icon m--margin-bottom-5">
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            {{__('Thêm khách hàng tiềm năng')}}
                        </a>
                        <a data-toggle="modal" href="javascript:void(0)"
                           data-target="#add-customer-group" onclick="EditCampaign.emptyListCustomerGroup()"
                           class="ss--padding-left-padding-right-1rem m-btn--wide ss--btn-mobiles btn btn-outline-successsss m-btn m-btn--icon m--margin-bottom-5">
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            {{__('Thêm khách hàng tự định nghĩa')}}
                        </a>
{{--                        @if(in_array('admin.campaign.submit-edit',session('routeList')))--}}
                            <a data-toggle="modal" href="javascript:void(0)"
                               data-target="#add-customer" onclick="EditCampaign.emptyListCustomer()"
                               class="ss--padding-left-padding-right-1rem m-btn--wide ss--btn-mobiles btn btn-outline-successsss m-btn m-btn--icon m--margin-bottom-5">
                                <i class="fa fa-plus-circle m--margin-right-5"></i>
                                {{__('Thêm khách hàng')}}
                            </a>
                        {{--@endif--}}
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-8"></div>
                <div class="col-lg-4">
                    <span class="text-danger pull-right error-customer-"></span>
                </div>

            </div>
            <div class="form-group m-form__group m--margin-bottom-10">
                <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true"
                     style="height: 300px; overflow: hidden;">
                    <table class="table table-striped m-table ss--header-table">
                        <thead class="bg">
                        <tr class="ss--font-size-th ss--nowrap">
                            <th>#</th>
                            <th class="ss--max-width-200">{{__('TÊN KHÁCH HÀNG')}}</th>
                            <th class="ss--text-center">{{__('SỐ ĐIỆN THOẠI')}}</th>
                            <th>{{__('NỘI DUNG TIN NHẮN')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="table-list-customer table_list_body" style="font-size: 12px">
                        @foreach($listLog as $key=>$value)
                            <tr>
                                <td width="5%" class="stt">{{$key+1}}</td>
                                <td width="50%" style="white-space: normal">{{$value['customer_name']}}</td>
                                <td width="10%" class="ss--text-center">{{$value['phone']}}</td>
                                <td width="10%">{{$value['message']}}</td>
                                <td width="10%">
                                    <button onclick="EditCampaign.removeCustomerLog(this,'{{ $value['id'] }}')"
                                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                            title="Delete" data-value="{{$value['phone']}}">
                                        <i class="la la-trash"></i>
                                    </button>
                                </td>
                                {{--<td class="ss--display-none">--}}
                                {{--<input type="hidden" value="{{ $value['id'] }}" class="id-log">--}}
                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m-form__actions--solid m--align-right">
                    <a href="{{route('admin.sms.sms-campaign')}}"
                       class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10 ss--btn">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </a>
                    <button onclick="EditCampaign.saveLog()"
                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
                         	<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
                                <span>{{__('LƯU LẠI')}}</span>
							</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
    @include('admin::marketing.sms.campaign.modal-customer-lead')
    <div class="modal fade" id="add-customer-group" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::marketing.sms.campaign.add-customer-group')
        </div>
    </div>
    <div class="modal fade" id="add-customer" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::marketing.sms.campaign.add-customer')
        </div>
    </div>
    <!--end::Portlet-->
    <input type="hidden" id="hidden-daySent" value="{{$daySent}}">
    <input type="hidden" id="hidden-timeSent" value="{{$timeSent}}">
    <input type="hidden" id="id" value="{{$id}}">


    <div class="modal fade" id="modalChooseFile" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ss--title m--font-bold">
                        <i class="la la-files-o ss--icon-title m--margin-right-5"></i>
                        {{__('THÊM FILE')}}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__body">
                        <div class="form-group row">
                            {{--<label for="file-upload" class="ss--custom-file-upload">--}}
                            {{--<i class="fa fa-cloud-upload"></i> Custom Upload--}}
                            {{--</label>--}}
                            {{--<input onchange="EditCampaign.chooseFile()" type="file" id="file_excel"--}}
                            {{--value="CHỌN TỆP">--}}
                            {{--<span class="department-name"></span>--}}
                            <div class="col-lg-8">
                                <input style="font-size: 0.8rem !important; margin-top: 0px !important;"
                                       class="file-name-excels ss--text-black m--margin-top-10 form-control" value=""
                                       readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="file_excel" class="ss--btn ss--custom-file-upload ss--font-size-13">
                                    <i class="fa fa-cloud-upload"></i> {{__('CHỌN TỆP')}}
                                </label>
                                <label class="error-file-name-excels text-danger"></label>
                                <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                       id="file_excel" onchange="EditCampaign.showNameFile()" type="file">
                            </div>
                        </div>
                        <div class="form-group">
                            <a class="m--font-boldest ss--color-default"
                               href="{{ route('admin.campaign.export.file',['type'=>'xls']) }}">
                                {{__('Tải file mẫu')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>
                            <button onclick="EditCampaign.chooseFile()"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m--margin-bottom-5 m--margin-left-10 m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span class="ss--text-btn-mobi">
						<i class="la la-check"></i>
						<span>{{__('THÊM FILE')}}</span>
						</span>
                            </button>
                        </div>
                    </div>
                </div>
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
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};

        // trigger click
        @if($campaign['is_deal_created'] == 1)
        $.getJSON(laroute.route('translate'), function (json) {
            $('#my-modal-create').html('');
            $.ajax({
                url: laroute.route('admin.campaign.sms-popup-edit-deal'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    'campaign_id': {{$campaign['campaign_id']}}
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
    </script>
    <script src="{{asset('static/backend/js/admin/marketing/sms/campaign/edit.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/marketing/sms/campaign/add.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="customer-list-tpl">
        <tr>
            <td width="5%" class="stt2">{stt}</td>
            <td width="50%" title="{name_title}" style="white-space: normal">
                <p>{name}</p>
                <input type="hidden" name="customer_id" value="{customer_id}">
                <input type="hidden" name="customer_id" value="{name}">
            </td>
            <td width="10%" class="ss--text-center">
                {phone}
                <input type="hidden" name="customer_id" value="{phone}">
            </td>
            <td width="10%" class="ss--text-center">
                {birthday}
                <input type="hidden" name="hiddenBirthday" value="{birthday}">
            </td>
            <td width="10%" class="ss--text-center">{gender}
                <input type="hidden" name="hiddenGender" value="{gender}">
            </td>
            <td width="10%">{branchName}</td>
            <td width="10%">
                <label class="m-checkbox m-checkbox--air">
                    <input class="check" name="check" type="checkbox" {is_checked} onchange="EditCampaign.checkCustomer('[{customer_id}]')">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
    <script type="text/template" id="customer-group-list-tpl">
        <tr>
            <td width="5%" class="stt2">{stt}</td>
            <td width="50%" title="{name_title}" style="white-space: normal">
                <p>{name}</p>
                <input type="hidden" name="customer_id" value="{customer_id}">
                <input type="hidden" name="customer_id" value="{name}">
            </td>
            <td width="10%" class="ss--text-center">
                {phone}
                <input type="hidden" name="customer_id" value="{phone}">
            </td>
            <td width="10%" class="ss--text-center">
                {birthday}
                <input type="hidden" name="hiddenBirthday" value="{birthday}">
            </td>
            <td width="10%" class="ss--text-center">{gender}
                <input type="hidden" name="hiddenGender" value="{gender}">
            </td>
            <td width="10%">{branchName}</td>
            <td width="10%">
                <label class="m-checkbox m-checkbox--air">
                    <input class="check-group" name="check-group" type="checkbox" {is_checked} onchange="EditCampaign.checkCustomer('[{customer_id}]')">
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
                {phone}
            </td>
            <td width="10%">{sale_name}
            </td>
            <td width="10%">{customer_type}</td>
            <td width="10%">{customer_source_name}</td>
            <td width="10%">{pipeline_name}</td>
            <td width="10%">{journey_name}</td>
            <td width="10%">
                <label class="m-checkbox m-checkbox--air">
                    <input class="check-lead" name="check-lead" {is_checked} type="checkbox" onchange="EditCampaign.checkCustomerLead('[{customer_lead_id}]')">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
    <script type="text/template" id="customer-list-append">
        <tr>
            <td width="5%" class="stt">{stt}</td>
            <td width="50%" style="white-space: normal">
                {name}
                <input type="hidden" name="customer_id" value="{customer_id}">
                <input type="hidden" name="customer_id" value="{name}">
            </td>
            <td width="10%" class="ss--text-center">
                {phone}
                <input type="hidden" name="customer_id" value="{phone}">
            </td>
            <td width="10%" class="contentmessage">
                {content}
                <input type="hidden" name="hiddenBirthday" value="{content}">
                <input type="hidden" name="type_customer" value="{type_customer}">
            </td>
            <td width="10%" hidden>
                <input type="hidden" name="object_id" value="{object_id}">
            </td>
            <td width="10%" >
{{--                <button onclick="EditCampaign.removeCustomer(this)"--}}
                <button onclick="EditCampaign.removeCustomerLog(this)"
                        class="aaaa m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Delete" data-value="{phone}">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
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
@endsection