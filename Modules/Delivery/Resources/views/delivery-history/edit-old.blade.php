@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN HÀNG CẦN GIAO')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">

                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA PHIẾU GIAO HÀNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mã đơn hàng'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$item['order_code']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Người đặt'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$item['orderer']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_name" name="contact_name" value="{{$item['contact_name']}}"
                                   placeholder="@lang('Nhập người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số điện thoại người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_phone" name="contact_phone" value="{{$item['contact_phone']}}"
                                   placeholder="@lang('Nhập số điện thoại người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_address" name="contact_address" value="{{$item['contact_address']}}"
                                   placeholder="@lang('Nhập địa chỉ người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian giao hàng dự kiến'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly=""
                                       placeholder="@lang('Thời gian giao hàng dự kiến')"
                                       id="time_ship" name="time_ship"
                                       value="{{\Carbon\Carbon::parse($item['time_ship'])->format('d/m/Y H:i')}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'):
                            </label><br>
                            @if($item['status']=='new')
                                <span class="m-badge m-badge--success" style="width:20%;">@lang('Đóng gói')</span>
                            @elseif($item['status']=='inprogress')
                                <span class="m-badge m-badge--primary" style="width:20%;">@lang('Đã nhận hàng')</span>
                            @elseif($item['status']=='success')
                                <span class="m-badge m-badge--info" style="width:20%;">@lang('Đã giao hàng')</span>
                            @elseif($item['status']=='confirm')
                                <span class="m-badge m-badge--metal"
                                      style="width:20%;">@lang('Xác nhận đã giao hàng')</span>
                            @elseif($item['status']=='cancel')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width:20%;">@lang('Hủy')</span>
                            @elseif($item['status']=='fail')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width:20%;">@lang('Thất bại')</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số tiền cần thu'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="amount" name="amount" placeholder="@lang('Nhập số tiền cần thu')..."
                                   value="{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Hình thức giao'):
                            </label>
                            <select class="form-control" style="width: 100%" id="transport_id" name="transport_id">
                                <option></option>
                                @foreach($optionTransport as $v)
                                    <option value="{{$v['transport_id']}}" {{$v['transport_id'] == $item['transport_id'] ? 'selected' : ''}}>{{$v['transport_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mã đơn vị vận chuyển'):
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="transport_code" name="transport_code"
                                   placeholder="@lang('Nhập mã đơn vị vận chuyển')..."
                                   value="{{$item['transport_code']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên'):<b class="text-danger">*</b>
                            </label>
                            <select class="form-control" style="width: 100%" id="delivery_staff" name="delivery_staff">
                                <option></option>
                                @foreach($optionCarrier as $v)
                                    <option value="{{$v['user_carrier_id']}}" {{$v['user_carrier_id'] == $item['delivery_staff'] ? 'selected' : ''}}>{{$v['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nơi lấy hàng'):
                            </label>
                            {{--                            <input type="text" class="form-control m-input"--}}
                            {{--                                   id="pick_up" name="pick_up" placeholder="@lang('Nhập nơi lấy hàng')..." value="{{$item['pick_up']}}">--}}
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="pick_up" name="pick_up" disabled>
                                    @if(isset($optionPickupAddress) && count($optionPickupAddress) > 0)
                                        <option value=""></option>
                                        @foreach($optionPickupAddress as $v)
                                            <option value="{{$v['warehouse_id']}}"
                                                    {{($item['warehouse_id_pick_up'] == $v['warehouse_id'])?'selected':''}}>{{$v['name'] . ' - ' . $v['address']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ghi chú'):
                            </label>
                            <textarea class="form-control m-input" id="note" name="note" rows="5"
                                      cols="5">{{$item['note']}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default">
                            <thead class="bg">
                            <tr>
                                <th>@lang('Mã sản phẩm')</th>
                                <th>@lang('Tên sản phẩm')</th>
                                <th>@lang('Số lượng giao')</th>
                                <th>@lang('SKU')</th>
                                <th>@lang('Ghi chú')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product as $v)
                                <tr>
                                    <td>{{$v['product_code']}}</td>
                                    <td>{{$v['product_name']}}</td>
                                    <td>{{$v['quantity']}}</td>
                                    <td>{{$v['sku']}}</td>
                                    <td>{{$v['note']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('delivery-history')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="edit.save({{$item['delivery_history_id']}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                    <span>
                                                        <i class="la la-check"></i>
                                                        <span>@lang('LƯU')</span>
                                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/delivery/delivery-history/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        edit._init();
    </script>
@stop


