@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN HÀNG CẦN GIAO')</span>
@stop
@section('content')
    <style>
        .color-red {
            color:red
        }
        .tr_product .form-control , .length_input , .width_input , .height_input{
            padding:0;
            text-align:center;
        }
        span.select2{
            width: 100% !important;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">

                     </span>
                    <h2 class="m-portlet__head-text">
                        <img class="img-fluid" style="width: 30px;" src="{{asset('static/backend/images/doc.png')}}" >@lang('CHI TIẾT PHIẾU GIAO HÀNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
    </div>

    <form id="form-register">
            <div class="m-portlet__body">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">
                            <div class="row">
                                <div class="col-12 m-portlet m-portlet--head-sm">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/doc_icon.png')}}" >
                                                <h2 class="m-portlet__head-text">
                                                    @lang('Thông tin đơn hàng')
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between pt-3">
                                                <span>@lang('Mã đơn hàng'):</span>
                                                <span><strong>{{$item['order_code']}}</strong></span>
                                            </div>
                                            <div class="col-12 d-flex justify-content-between pt-3">
                                                <span>@lang('Người đặt'):</span>
                                                <span><strong>{{$item['orderer']}}</strong></span>
                                            </div>
                                            <div class="col-12 d-flex justify-content-between pt-3">
                                                <span>@lang('Tổng giá trị đơn hàng'):</span>
                                                <span><strong>{{number_format($item['amount'])}} VNĐ</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 m-portlet m-portlet--head-sm">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/doc_icon.png')}}" >
                                                <h2 class="m-portlet__head-text">
                                                    @lang('Thông tin phiếu giao hàng')
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="row">
                                            <div class="col-12 form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Người nhận'):
                                                </label>
                                                <input type="text" class="form-control m-input" disabled
                                                       id="contact_name" name="contact_name"
                                                       value="{{$item['contact_name'] != null ? $item['contact_name'] : ''}}"
                                                       placeholder="@lang('Nhập người nhận')...">
                                            </div>

                                            <div class=" col-12 form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Số điện thoại người nhận'):<b class="text-danger">*</b>
                                                </label>
                                                <input type="text" class="form-control m-input" disabled
                                                       id="contact_phone" name="contact_phone"
                                                       value="{{$item['contact_phone'] != null ? $item['contact_phone'] : ''}}"
                                                       placeholder="@lang('Nhập số điện thoại người nhận')...">
                                            </div>
                                            <div class="col-6 form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Tỉnh/thành phố'):<b class="text-danger">*</b>
                                                </label>
                                                <input type="text" disabled class="form-control" value="{{$item['province_name']}}">
                                            </div>
                                            <div class="col-6 form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Quận/huyện'):<b class="text-danger">*</b>
                                                </label>
                                                <input type="text" disabled class="form-control" value="{{$item['district_name']}}">
                                            </div>
                                            <div class="col-12 form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Phường/xã'):<b class="text-danger">*</b>
                                                </label>
                                                <input type="text" disabled class="form-control" value="{{$item['ward_name']}}">
                                            </div>

                                            <div class="col-12 form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Địa chỉ người nhận'):<b class="text-danger">*</b>
                                                </label>
                                                <input type="text" disabled class="form-control" value="{{$item['contact_address']}}">
                                            </div>

                                            <div class="col-12 form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Ghi chú'):
                                                </label>
                                                <textarea class="form-control m-input" id="note" name="note" rows="5" cols="5" disabled>{{$item['note']}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-8 pl-3">
                            <div class="row">
                                <div class="col-12 m-portlet m-portlet--head-sm ml-4">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/gift.png')}}" >
                                                <h2 class="m-portlet__head-text">
                                                    @lang('Thông tin sản phẩm')
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped m-table m-table--head-bg-default" id="table_product">
                                                        <thead class="bg">
                                                        <tr>
                                                            <th>@lang('Mã sản phẩm')</th>
                                                            <th>@lang('Tên sản phẩm')</th>
                                                            <th>@lang('Số lượng giao')</th>
                                                            <th>@lang('Ghi chú')</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($product as $v)
                                                            <tr>
                                                                <td>
                                                                    <span>{{$v['product_code']}}</span>
                                                                </td>
                                                                <td>
                                                                    <span>{{$v['product_name']}}</span>
                                                                    @if(in_array($v['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                                                        ({{__('quà tặng')}})
                                                                    @endif
                                                                </td>
                                                                <td>{{$v['quantity']}}</td>
                                                                <td>{{$v['note']}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    <span class="error-table" style="color: #ff0000"></span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <hr>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="row">
                                                            <div class="col-4 d-flex align-items-center">
                                                                <label class="mb-0">
                                                                    @lang('Trọng lượng'):<b class="text-danger">*</b>
                                                                </label>
                                                            </div>
                                                            <div class="col-4">
                                                                <input type="text" class="form-control" id="weight" name="weight" disabled value="{{$item['weight']}}">
                                                            </div>
                                                            <div class="col-4">
                                                                <select class="form-control select2-fix" id="type_weight" name="type_weight" disabled>
                                                                    <option value="gam" {{$item['type_weight'] == 'gam' ? 'selected' : ''}}>gam</option>
                                                                    <option value="kg" {{$item['type_weight'] == 'kg' ? 'selected' : ''}}>kg</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <div class="row">
                                                            <div class="col-3 d-flex align-items-center">
                                                                <label class="mb-0">
                                                                    @lang('Kích thước'):<b class="text-danger">*</b>
                                                                </label>
                                                            </div>
                                                            <div class="col-2">
                                                                <input type="text" class="form-control length_input" disabled value="{{$item['length']}}">
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" class="form-control width_input" disabled value="{{$item['width']}}">
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" class="form-control height_input" disabled value="{{$item['height']}}">
                                                            </div>
                                                            <div class="col-1 d-flex align-items-center">
                                                                <label class="mb-0">cm</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 m-portlet m-portlet--head-sm ml-4">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/car.png')}}" >
                                                <h2 class="m-portlet__head-text">
                                                    @lang('Thông tin vận chuyển')
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <label class="col-3">{{__('Nơi lấy hàng')}}: </label>
                                                            <div class="col-9">
                                                                <select class="form-control select2-fix pick_up" style="width: 100%" id="pick_up" name="pick_up" disabled>
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
                                                    </div>
                                                    <div class="col-6  d-flex align-items-center">
                                                        <label>{{__('Địa chỉ lấy hàng')}}: </label>
                                                        <span>
                                                            <strong class="pick_up_address">
                                                                @if(isset($optionPickupAddress) && count($optionPickupAddress) > 0)
                                                                    @foreach($optionPickupAddress as $v)
                                                                        @if($item['warehouse_id_pick_up'] == $v['warehouse_id'])
                                                                            {{' '.$v['name'] . ' - ' . $v['address']}}
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </strong>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 mt-3">
                                                <label class="mb-0">
                                                    @lang('Cách thức nhận hàng'):<b class="text-danger">*</b>
                                                </label>
                                                <select class="form-control select2-fix selectReceiptProduct" disabled>
                                                    <option value="delivery_unit" {{$item['shipping_unit'] == 'delivery_unit' ? 'selected' : ''}}>{{__('Đơn vị vận chuyển giao hàng')}}</option>
                                                    <option value="staff" {{$item['shipping_unit'] == 'staff' ? 'selected' : ''}}>{{__('Nhân viên giao hàng')}}</option>
                                                </select>
                                            </div>
                                            <div class="col-12 block-receipt-product mt-3 {{$item['shipping_unit'] != 'delivery_unit' ? 'd-none' : ''}}">
                                                <div class="row">
                                                    <div class="col-12 mt-3">
                                                        <label><strong>{{__('Thông tin đối tác')}}</strong></label>
                                                        <nav>
                                                            <div class="nav nav-tabs nav-tabs-delivery" id="nav-tab" role="tablist" >
                                                                @if ($item['service_id'] == 3)
                                                                    <a class="nav-item nav-link active show" id="normal-delivery-tab" data-toggle="tab" href="#normal-delivery" role="tab" aria-controls="normal-delivery" aria-selected="true">{{__('Thường')}} <img class="img-fluid" src="{{asset('static/backend/images/car.png')}}"></a>
                                                                @endif
                                                                @if ($item['service_id'] == 1)
                                                                    <a class="nav-item nav-link" id="fast-delivery-tab" data-toggle="tab" href="#fast-delivery" role="tab" aria-controls="fast-delivery" aria-selected="false">{{__('Nhanh')}} <img class="img-fluid" src="{{asset('static/backend/images/plane.png')}}"></a>
                                                                @endif
                                                                @if ($item['service_id'] == 2)
                                                                    <a class="nav-item nav-link" id="day-delivery-tab" data-toggle="tab" href="#day-delivery" role="tab" aria-controls="day-delivery" aria-selected="false">{{__('Trong ngày')}} <img class="img-fluid" src="{{asset('static/backend/images/clock.png')}}"></a>
                                                                @endif
                                                            </div>
                                                        </nav>
                                                        <div class="tab-content" id="nav-tabContent">
                                                            <div class="tab-pane fade show active" id="normal-delivery" role="tabpanel" aria-labelledby="normal-delivery-tab">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped m-table m-table--head-bg-default">
                                                                        <thead class="bg">
                                                                        <tr>
                                                                            <th class="tr_thead_list">{{__('Đối tác vận chuyển')}}</th>
                                                                            <th class="tr_thead_list">{{__('Dịch vụ')}}</th>
                                                                            <th class="tr_thead_list">{{__('Phí dự kiến')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td><img style="width:100px" class="img-fluid" src="{{asset('static/backend/images/ghn_icon.png')}}"></td>
                                                                            <td>{{$item['name_service']}}</td>
                                                                            <td>{{number_format($item['fee'])}} đ</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>{{__('Thời gian giao hàng dự kiến')}}: {{\Carbon\Carbon::parse($item['time_ship'])->format('d/m/Y')}}</label>
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label class="form-check-label m-checkbox m-checkbox--air">
                                                            <input disabled type="checkbox" name="is_insurance" value="1" id="is_insurance" {{$item['shipping_unit'] == 'delivery_unit' && $item['is_insurance'] == 1 ? 'checked' : ''}}>
                                                            <span></span>
                                                            <div class="pt-1">{{ __('Bảo hiểm hàng hoá') }} <span class="color-red">{{number_format($item['insurance_fee'])}}đ</span></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <div class="row">
                                                            <label class="form-check-label m-checkbox m-checkbox--air col-3  d-flex align-items-center">
                                                                <strong>{{ __('Lưu ý') }}: </strong>
                                                            </label>
                                                            <div class="col-9">
                                                                <select class="form-control select2-fix required_note" disabled>
                                                                    <option value="KHONGCHOXEMHANG"  {{$item['shipping_unit'] == 'delivery_unit' && $item['required_note'] == 'KHONGCHOXEMHANG' ? 'selected' : ''}}>{{__('Không cho xem hàng')}}</option>
                                                                    <option value="CHOTHUHANG"  {{$item['shipping_unit'] == 'delivery_unit' && $item['required_note'] == 'CHOTHUHANG' ? 'selected' : ''}}>{{__('Cho thử hàng')}}</option>
                                                                    <option value="CHOXEMHANGKHONGTHU"  {{$item['shipping_unit'] == 'delivery_unit' && $item['required_note'] == 'CHOXEMHANGKHONGTHU' ? 'selected' : ''}}>{{__('Cho xem hàng không cho thử')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr class="w-100">
                                                    <div class="col-6">
                                                        <div class="pt-1">{{ __('Thu hộ tiền (COD)') }} <span class="color-red">{{number_format($item['cod_amount'])}}đ</span></div>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <label>{{__('Tổng phí trả ĐTGH')}}: <span class="color-red">{{number_format($item['total_fee'])}}đ</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 block-receipt-staff mt-3 {{$item['shipping_unit'] == 'delivery_unit' ? 'd-none' : ''}}">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>{{__('Thời gian giao hàng dự kiến')}}: <b class="text-danger">*</b></label>
                                                        <div class="m-input-icon m-input-icon--right">
                                                            <input type="text" class="form-control searchDateForm" disabled value="{{\Carbon\Carbon::parse($item['time_ship'])->format('d/m/Y')}}" placeholder="{{__('Chọn thời gian giao hàng dự kiến')}}">
                                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                        <span><i class="la la-calendar"></i></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label>{{__('Nhân viên')}}: <b class="text-danger">*</b></label>
                                                        <select class="form-control select2-fix" style="width: 100%" id="delivery_staff" name="delivery_staff" disabled>
                                                            <option value="">{{__('chọn nhân viên giao hàng')}}</option>
                                                            @foreach($optionCarrier as $v)
                                                                <option value="{{$v['user_carrier_id']}}" {{$item['delivery_staff'] == $v['user_carrier_id'] ? 'selected' : ''}}>{{$v['full_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <hr class="w-100">
                                                    <div class="col-6">
                                                        <div class="pt-1">{{ __('Thu hộ tiền (COD)') }} <span class="color-red">{{number_format($item['cod_amount'])}}đ</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="order_id" value="{{$item['order_id']}}">

                                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit mb-3">
                                        <div class="m-form__actions m--align-right">
                                            <a href="{{route('delivery-history')}}"
                                               class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                                <span>
                                                    <i class="la la-arrow-left"></i>
                                                    <span>@lang('HỦY')</span>
                                                </span>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <div id="my-modal"></div>

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


