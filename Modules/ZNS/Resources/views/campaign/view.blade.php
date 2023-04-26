@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ CHIẾN DỊCH ZNS')}}
    </span>
@endsection
@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT CHIẾN DỊCH')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label>
                        {{ __('Tên chiến dịch') }}:
                    </label>
                </div>
                <div class="col-lg-5">
                    <span>
                        {{ $item->name }}
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label>
                        {{ __('Tên loại chiến dịch') }}:
                    </label>
                </div>
                <div class="col-lg-5">
                    <span>
                        @if($item->campaign_type == "zns")
                            {{ __('Zalo template API') }}
                        @elseif($item->campaign_type == "follower")
                            {{ __('Zalo Follower API') }}
                        @elseif($item->campaign_type == "broadcast")
                            {{ __('Zalo BroadCast') }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label>
                        {{ __('Chi nhánh') }}:
                    </label>
                </div>
                <div class="col-lg-5">
                    <span>
                        {{ isset($branch[$item->branch_id])?$branch[$item->branch_id]:'' }}
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label>
                        {{ __('Tên template:') }}
                    </label>
                </div>
                <div class="col-lg-5">
                    <div class="form-group m-form__group">
                        {{$item->template_name}}
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label>
                        {{ __('Nội dung tin nhắn mẫu') }}:
                    </label>
                </div>
                <div class="col-lg-5">
                    <div class="form-group m-form__group">
                        <iframe class="innerIframe d-block border-0" src="{{ $item->preview }}" name="innerIframe"
                                id="content_zns" width="500px" height="400px"></iframe>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label>
                        {{ __('Số thông báo gửi') }}:
                    </label>
                </div>
                <div class="col-lg-5">
                    <span>
                        {{ count($list_customer_send) }}
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-lg-3">
                    <label>
                        {{ __('Thông báo gửi thành công') }}:
                    </label>
                </div>
                <div class="col-lg-5">
                    <span>{{ $mess_send_success }}</span>
                </div>
            </div>
            <div class="form-group m-form__group" id="autotable">
                <div class="pull-left m--margin-right-5  ss--width--100 ss--text-btn-mobi">
                    <br>
                    <span
                            class="m--margin-top-5 ss--font-weight-500 ss--font-size-13">{{ __('DANH SÁCH KHÁCH HÀNG NHẬN TIN NHẮN') }}</span>
                </div>
                <div class="list-log-detail-campaign">
                    <div class="table-responsive" style="height: 350px; overflow: scroll;">
                        <table class="table table-striped m-table ss--header-table">
                            <thead class="bg">
                            <tr class="ss--font-size-13 ss--nowrap">
                                <th class="ss--font-size-th">#</th>
                                <th class="ss--font-size-th">{{__('TÊN KHÁCH HÀNG')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('SỐ ĐIỆN THOẠI')}}</th>
                                <th class="ss--font-size-th">{{__('NGƯỜI TẠO')}}</th>
{{--                                <th class="ss--font-size-th">{{__('NGƯỜI GỬI')}}</th>--}}
                                <th class="ss--font-size-th ss--text-center">{{__('NGÀY TẠO')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('NGÀY GỬI')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('TRẠNG THÁI')}}</th>
                                <th class="ss--font-size-th text-center">{{__('NỘI DUNG THÔNG BÁO')}}</th>
                            </tr>
                            </thead>
                            <tbody class="table-list-customer table_list_body" style="font-size: 12px">
                            @if ($list_customer_send)
                                @foreach ($list_customer_send as $key => $customer_info)
                                    <tr>
                                        <td class="stt">
                                            {{$key + 1}}
                                            <input type="hidden" name="customer_id[{{$customer_info->type_customer}}][]"
                                                   class="customer_id_{{$customer_info->type_customer}}{{$customer_info->user_id}}"
                                                   value="{{$customer_info->user_id}}">
                                            <input type="hidden"
                                                   name="name_customer[{{$customer_info->type_customer}}][{{$customer_info->user_id}}]"
                                                   class="" value="{{$customer_info->full_name}}">
                                            <input type="hidden"
                                                   name="phone_customer[{{$customer_info->type_customer}}][{{$customer_info->user_id}}]"
                                                   class="" value="{{$customer_info->phone}}">
                                            <input type="hidden"
                                                   name="type_customer[{{$customer_info->type_customer}}][{{$customer_info->user_id}}]"
                                                   class="" value="{{$customer_info->type_customer}}">
                                        </td>
                                        <td style="white-space: normal">{{ $customer_info->type_customer == 'lead' ? $customer_info->full_name_lead : $customer_info->full_name}}</td>
                                        <td class="ss--text-center">{{$customer_info->phone}}</td>
                                        <td>{{$customer_info->created_by_full_name}}</td>
                                        <td class="ss--text-center">
                                            {{$customer_info->created_at ? date_format(new DateTime($item->created_at), 'd/m/Y H:i') : ''}}
                                        </td>
                                        <td class="ss--text-center">
                                            {{$customer_info->time_sent ? date_format(new DateTime($item->time_sent), 'd/m/Y H:i') : ''}}
                                        </td>
                                        <td class="ss--text-center">
                                            @if ($customer_info->status == 'new')
                                                {{__('Mới')}}
                                            @elseif($customer_info->status == 'sent')
                                                {{__('Đã gửi')}}
                                            @elseif($customer_info->status == 'error')
                                                {{__('Đã lên lịch')}}
                                            @endif
                                        </td>
                                        <td class="text-center text-primary">
                                            <a href="{{ $item->preview }}" target="_blank" class="text-center text-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
                <div class="m-form__actions m-form__actions--solid m--align-right">
                    <a href="{{route('zns.campaign')}}"
                       class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
						 <span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>@lang('HỦY')</span>
						</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/sms/campaign/index.js?v='.time())}}"
            type="text/javascript"></script>
@stop