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
                        @lang('CHI TIẾT PHIẾU GIAO HÀNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-register">
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
                                   placeholder="@lang('Nhập người nhận')..." disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số điện thoại người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_phone" name="contact_phone" value="{{$item['contact_phone']}}"
                                   placeholder="@lang('Nhập số điện thoại người nhận')..." disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_address" name="contact_address" value="{{$item['contact_address']}}"
                                   placeholder="@lang('Nhập địa chỉ người nhận')..." disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian giao hàng dự kiến'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly="" placeholder="@lang('Thời gian giao hàng dự kiến')"
                                       id="time_ship" name="time_ship" value="{{\Carbon\Carbon::parse($item['time_ship'])->format('d/m/Y H:i')}}" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian lấy hàng'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$item['time_pick_up'] != null ? \Carbon\Carbon::parse($item['time_pick_up'])->format('d/m/Y H:i') : ''}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian giao hàng'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$item['time_drop'] != null ? \Carbon\Carbon::parse($item['time_drop'])->format('d/m/Y H:i') : ''}}" disabled>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Hình ảnh lấy hàng'):
                                </label><br>
                                @if($item['image_pick_up'] == null)
                                    <img class="m--bg-metal m-image img-sd" id="blah"
                                         src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                         alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                @else
                                    <img class="m--bg-metal m-image img-sd" id="blah"
                                         src="{{$item['image_pick_up']}}" onclick="zoomImage(`{{$item['image_pick_up']}}`)"
                                         alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                @endif

                            </div>
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Hình ảnh giao hàng'):
                                </label><br>
                                @if($item['image_drop'] == null)
                                    <img class="m--bg-metal m-image img-sd" id="blah"
                                         src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                         alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                @else
                                    <img class="m--bg-metal m-image img-sd" id="blah"
                                         src="{{$item['image_drop']}}" onclick="zoomImage(`{{$item['image_drop']}}`)"
                                         alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                @endif
                            </div>
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
                            <select class="form-control" style="width: 100%" id="transport_id" name="transport_id" disabled>
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
                                   id="transport_code" name="transport_code" placeholder="@lang('Nhập mã đơn vị vận chuyển')..." value="{{$item['transport_code']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên'):
                            </label>
                            <select class="form-control" style="width: 100%" id="delivery_staff" name="delivery_staff" disabled>
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
{{--                                   id="pick_up" name="pick_up" placeholder="@lang('Nhập nơi lấy hàng')..." value="{{$item['pick_up']}}" disabled>--}}
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
                            <textarea class="form-control m-input" id="note" name="note" rows="5" cols="5" disabled>{{$item['note']}}</textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ghi chú nhân viên giao hàng'):
                            </label>
                            <textarea class="form-control m-input" id="note" name="note" rows="5" cols="5" disabled>{{$item['delivery_note']}}</textarea>
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
                                <span class="m-badge m-badge--metal" style="width:20%;">@lang('Xác nhận đã giao hàng')</span>
                            @elseif($item['status']=='cancel')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width:20%;">@lang('Hủy')</span>
                            @elseif($item['status']=='fail')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width:20%;">@lang('Thất bại')</span>
                            @elseif($item['status']=='pending')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width:20%;">@lang('Đang chờ xử lý')</span>
                            @endif
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
                                    <td>
                                        {{$v['product_name']}}
                                        @if(in_array($v['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                            ({{__('quà tặng')}})
                                        @endif
                                    </td>
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
{{--                        <button type="button" onclick="detail.save({{$item['delivery_id']}})"--}}
{{--                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">--}}
{{--                            <span>--}}
{{--                                <i class="la la-check"></i>--}}
{{--                                <span>@lang('LƯU TRẠNG THÁI')</span>--}}
{{--                        </span>--}}
{{--                        </button>--}}
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="my-modal"></div>

{{--    <div id="modal-image" class="modal fade" role="dialog" style="padding-right: 16px; display: block;">--}}
{{--        <div class="modal-dialog modal-dialog-centered" style="height: 60%;">--}}
{{--            <!-- Modal content-->--}}
{{--            <div class="modal-content h-100">--}}

{{--                <div class="modal-body h-100 text-center">--}}
{{--                    <figure class="zoo-item w" data-zoo-scale="2" data-zoo-image="https://matthews.piospa.com/uploads/admin/delivery-history/20200630/6159350076830062020_delivery-history.jpg"></figure>--}}
{{--                    <span class='zoom h-100' id='zoo-item'>--}}
{{--                        <img id="image-zoom" src='' class="img-fluid h-100"/>--}}
{{--                    </span>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}

    <div class="modal fade" id="modal-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="height: 80%;">
            <div class="modal-content h-100">
                <div class="modal-body h-100 text-center">
                    <span class='zoom h-100' id='zoo-item'>
                        <img id="image-zoom" src='' class="img-fluid h-100"/>
                    </span>
                </div>
            </div>
        </div>
    </div>

@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/zoomove.min.css')}}">
    <style>
        /* styles unrelated to zoom */
        * { border:0; margin:0; padding:0; }
        p { position:absolute; top:3px; right:28px; color:#555; font:bold 13px/1 sans-serif;}

        /* these styles are for the demo, but are not required for the plugin */
        .zoom {
            display:inline-block;
            position: relative;
        }

        .zoom img {
            display: block;
        }

        .zoomImg {
            width: 200% !important;
            height: 200% !important;
        }

        .zoom img::selection { background-color: transparent; }
    </style>
@stop
@section('after_script')
{{--    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>--}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
{{--    <script src="{{asset('static/backend/js/delivery/delivery-history/zoomove.min.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('static/backend/js/delivery/delivery-history/jquery.zoom.js')}}" type="text/javascript"></script>
{{--    <script type='text/javascript'>--}}
{{--        $('.zoo-item').ZooMove();--}}
{{--    </script>--}}
{{--    <script src="{{asset('static/backend/js/delivery/delivery-history/script.js?v='.time())}}" type="text/javascript"></script>--}}
{{--    <script>--}}
{{--        detail._init();--}}
{{--    </script>--}}
<script>
    $(document).ready(function(){
        $('#zoo-item').zoom();
    });

    function zoomImage(image) {
        $('#image-zoom').attr('src',image);
        $('.zoomImg').remove();
        $('#zoo-item').zoom();
        $('#modal-image').modal('show');
    }
</script>
@stop


