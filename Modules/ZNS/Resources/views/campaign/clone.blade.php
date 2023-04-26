@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-sms.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ CHIẾN DỊCH ZNS') }}
    </span>
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
@endsection
@section('content')
    <!--begin::Portlet-->
    <form class="m-portlet" id="form-add">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('SAO CHÉP CHIẾN DỊCH') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {{-- <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('OA') }}: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="form-group m-form__group">
                        <select name="oa" class="form-control">
                            @foreach ($branch as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div> --}}
            <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('Tên chiến dịch') }}: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="form-group m-form__group">
                        <input id="name" name="name" type="text" class="form-control" placeholder="{{ __('Nhập tên chiến dịch') }}" value="{{$item->name}}">
                        <input type="hidden" name="campaign_type" value="{{ isset($params['campaign_type'])?$params['campaign_type']:'zns' }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('Template') }}: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="form-group m-form__group">
                        <select name="zns_template_id" class="form-control">
                            <option value="">{{ __('Chọn Template') }}</option>
                            @foreach ($template_option as $key => $value)
                                <option value="{{$key}}" {{$item->zns_template_id == $key?' selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="template_id" id="template_id" value="{{$item->template_id}}">
                        <input type="hidden" name="template_price" id="template_price" value="{{$item->price}}">
                    </div>
                    <div class="form-group m-form__group">
{{--                        <textarea id="message-content" rows="5" cols="40"--}}
{{--                            class="form-control m-input" disabled></textarea>--}}
{{--                        <i class="pull-right">{{ __('Số ký tự') }}: <i--}}
{{--                                class="count-character">0</i>{{ __('/480 ký tự') }}</i>--}}
                        <iframe class="innerIframe d-block border-0" src="{{ $item->preview }}" name="innerIframe" id="content_zns" width="500px" height="400px"></iframe>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('Thời gian gửi') }}: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-3 form-group">
                                <label class="m-radio cus">
                                    <input type="radio" name="check_type" value="1"{{$item->is_now?' checked':''}}>{{ __('Gửi ngay') }}
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-lg-9">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="col-lg-4 col-sm-12">
                                        <label class="m-radio cus">
                                            <input type="radio" name="check_type" value="0"{{$item->is_now?'':' checked'}}>{{ __('Gửi vào lúc') }}:
                                            <span></span>
                                        </label>
                                    </span>
                                    <div class="m-input-icon m-input-icon--right w-50">
                                        <input readonly class="form-control m-input daterange-picker" id="time_send" name="time_send"
                                               autocomplete="off" placeholder="{{ __('Ngày tạo') }}" value="{{$item->is_now == 0 && $item->time_sent ?date_format(new DateTime($item->time_sent), 'd/m/Y H:i'): ''}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('Chi nhánh') }}: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="form-group m-form__group">
                        <select name="branch_id" class="form-control">
                            @foreach($branch as $key => $value)
                                <option value="{{$key}}"{{ $item->branch_id == $key?' selected':'' }}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('Hoạt động') }}:
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="d-flex">
                        <div class="col-md-4">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox" class="manager-btn" name="is_actived" value="1"{{$item->is_actived?' checked':''}}>
                                    <span></span>
                                </label>
                            </span>
                        </div>
                        <div class="col-md-8">
                            {{__('Trạng thái')}} : 
                            @if ($item->status == 'cancle')
                            {{__('Đã hủy')}}
                            @elseif($item->status == 'sent')
                            {{__('Đã gửi')}}
                            @else
                            {{__('Đã lên lịch')}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="form-group m-form__group row">
                <div class="col-lg-12">
                    <div class="pull-left m--margin-right-5  ss--width--100 ss--text-btn-mobi">
                        <br>
                        <span
                            class="m--margin-top-5 ss--font-weight-500 ss--font-size-13">{{ __('DANH SÁCH KHÁCH HÀNG NHẬN TIN NHẮN') }}</span>
                    </div>
                    <div class="pull-right m--margin-right-5  ss--width--100 ss--text-btn-mobi">
                        <a href="javascript:void(0)"
                            onclick="AddCampaign.showListCustomer('add-group-potential')"
                            class="ss--padding-left-padding-right-1rem m-btn--wide ss--btn-mobiles btn btn-outline-successsss m-btn m-btn--icon m--margin-bottom-5">
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            {{ __('Thêm khách hàng tiềm năng') }}
                        </a>
                        <a href="javascript:void(0)"
                            onclick="AddCampaign.showListCustomer('add-group-define')"
                            class="ss--padding-left-padding-right-1rem m-btn--wide ss--btn-mobiles btn btn-outline-successsss m-btn m-btn--icon m--margin-bottom-5">
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            {{ __('Thêm khách hàng tự định nghĩa') }}
                        </a>
                        <a href="javascript:void(0)"
                            onclick="AddCampaign.showListCustomer('add-customer')"
                            class="ss--padding-left-padding-right-1rem m-btn--wide ss--btn-mobiles btn btn-outline-successsss m-btn m-btn--icon m--margin-bottom-5">
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            {{ __('Thêm khách hàng') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group m--margin-bottom-10">
                <div class="m-scrollable m-scroller ps ps--active-y">
                    <table class="table table-striped m-table ss--header-table" id="list-customer-get-notification-table">
                        <thead class="bg">
                            <tr class="ss--font-size-th ss--nowrap">
                                <th>#</th>
                                <th>{{ __('TÊN KHÁCH HÀNG') }}</th>
                                <th>{{ __('SỐ ĐIỆN THOẠI') }}</th>
                                <th>{{ __('NỘI DUNG TIN NHẮN') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-list-customer table_list_body" style="font-size: 12px">
                            @if ($list_customer_send)
                            @foreach ($list_customer_send as $key => $customer_info)
                            <tr>
                                <td class="stt">
                                    {{$key + 1}}
                                    <input type="hidden" name="customer_id[{{$customer_info->type_customer}}][]" class="customer_id_{{$customer_info->type_customer}}{{$customer_info->user_id}}" value="{{$customer_info->user_id}}">
                                    <input type="hidden" name="name_customer[{{$customer_info->type_customer}}][{{$customer_info->user_id}}]" class="" value="{{$customer_info->full_name}}">
                                    <input type="hidden" name="phone_customer[{{$customer_info->type_customer}}][{{$customer_info->user_id}}]" class="" value="{{$customer_info->phone}}">
                                    <input type="hidden" name="type_customer[{{$customer_info->type_customer}}][{{$customer_info->user_id}}]" class="" value="{{$customer_info->type_customer}}">
                                </td>
                                <td style="white-space: normal">{{ $customer_info->type_customer == 'lead' ? $customer_info->full_name_lead : $customer_info->full_name}}</td>
                                <td class="">{{$customer_info->phone}}</td>
                                <td>{{$customer_info->message}}</td>
                                <td>
                                    <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-customer"
                                    title="Delete" data-value="1">
                                    <i class="la la-trash"></i>
                                </button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m-form__actions--solid m--align-right">
                    <a href="{{ route('zns.campaign') }}"
                        class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </a>
                    <button type="button" onclick="AddCampaign.confirmPopup()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-check"></i>
                            <span>{{ __('LƯU THÔNG TIN') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <!--end::Portlet-->
    <div class="modal fade" id="show-list-customer" role="dialog"></div>
    <div class="modal fade" id="confirm" role="dialog"></div>
    <div class="d-none" id="button-delete-customer">
        <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-customer"
            title="Delete" data-value="1">
            <i class="la la-trash"></i>
        </button>
    </div>
    <div class="d-none" id="eye-link-preview">
        <a href="{link}" target="_blank" class="text-center text-primary">
            <i class="fa fa-eye"></i>
        </a>
    </div>
@endsection
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{ asset('static/backend/js/zns/campaign/clone.js?v=' . time()) }}"
        type="text/javascript">
    </script>
@stop
