@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ KHÁCH HÀNG')}}</span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"></i> {{__('CHỈNH SỬA DỰ KIẾN NHẮC SỬ DỤNG')}}</span>
                    </h2>
                    <h3 class="m-portlet__head-text">

                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-confirm">
            <div class="m-portlet__body">
                <div class="row">
                    <input type="hidden" id="is_sent_notify" name="is_sent_notify" value="{{$isSentNotify}}">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Mã đơn hàng'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['order_code']}}" disabled>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Tên khách hàng'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['full_name']}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Loại đối tượng'):
                                </label>
                                <div class="input-group">
                                    @switch($item['object_type'])
                                        @case('product')
                                        <input type="text" class="form-control m-input" value="{{__('Sản phẩm')}}" disabled>
                                        @break
                                        @case('service')
                                        <input type="text" class="form-control m-input" value="{{__('Dịch vụ')}}" disabled>
                                        @break
                                        @case('service_card')
                                        <input type="text" class="form-control m-input" value="{{__('Thẻ dịch vụ')}}" disabled>
                                        @break
                                    @endswitch

                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Tên đối tượng'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" value="{{$item['object_name']}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Hoàn thành'):
                            </label>
                            <div class="input-group date">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input id="is_finish" name="is_finish" type="checkbox" {{$item['is_finish'] == 1 ? 'checked' : ''}}>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Ngày gửi'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="sent_date" name="sent_date"
                                           value="{{\Carbon\Carbon::parse($item['sent_at'])->format('d/m/Y')}}" {{$isSentNotify == 1 ? 'disabled': ''}}>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Giờ gửi'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="sent_time" name="sent_time"
                                           value="{{\Carbon\Carbon::parse($item['sent_at'])->format('H:i')}}" {{$isSentNotify == 1 ? 'disabled': ''}}>
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ghi chú'):
                            </label>
                            <div class="input-group">
                                <textarea class="form-control" rows="5" id="note" name="note">{{$item['note']}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('customer-remind-use')}}"
                           class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" onclick="remindUse.save({{$item['customer_remind_use_id']}})"
                                class="btn  btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10">
							<span>
							<i class="la la-edit"></i>
							<span>{{__('CHỈNH SỬA')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer/customer-remind-use/script.js')}}"
            type="text/javascript"></script>
    <script>
        remindUse._init();
    </script>
@stop
