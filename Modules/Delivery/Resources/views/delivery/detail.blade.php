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
                        @lang('CHI TIẾT ĐƠN HÀNG CẦN GIAO')
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
                        <input type="hidden" id="order_id" value="{{$item['order_id']}}">
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
                            <input type="text" class="form-control m-input" value="{{$item['full_name']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thông tin giao hàng'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$item['contact_name']}}" disabled>
                            <input type="text" class="form-control m-input" value="{{$item['contact_phone']}}" disabled>
                            <input type="text" class="form-control m-input" value="{{$item['contact_address']}}"
                                   disabled>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số lần giao dự kiến'):
                            </label>
                            <input type="text" class="form-control m-input"
                                   value="{{$item['total_transport_estimate']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tổng tiền cần thu'):
                            </label>
                            <input type="text" class="form-control m-input"
                                   value="{{number_format($item['amount'] - $amountPaid, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                   disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'):
                            </label><br>
                            @if($item['delivery_status']=='packing')
                                <span class="m-badge m-badge--success" style="width:20%;">@lang('Đóng gói')</span>
                            @elseif($item['delivery_status']=='preparing')
                                <span class="m-badge m-badge--primary" style="width:20%;">@lang('Chuẩn bị')</span>
                            @elseif($item['delivery_status']=='delivering')
                                <span class="m-badge m-badge--info" style="width:20%;">@lang('Đang giao')</span>
                            @elseif($item['delivery_status']=='delivered')
                                <span class="m-badge m-badge--metal" style="width:20%;">@lang('Đã giao')</span>
                            @elseif($item['delivery_status']=='cancel')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width:20%;">@lang('Đã hủy')</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Sản phẩm'):
                    </label>
                    <div class="table-responsive">
                        <table class="table table-striped m-table">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('Mã sản phẩm')</th>
                                <th class="tr_thead_list">@lang('Tên sản phẩm')</th>
                                <th class="tr_thead_list text-center">@lang('Số lượng')</th>
                                <th class="tr_thead_list text-center">@lang('Số lượng đã giao')</th>
                                {{--                                <th class="tr_thead_list text-center">@lang('Số lượng chưa giao')</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($orderDetail) > 0)
                                @foreach($orderDetail as $v)
                                    <tr>
                                        <td>{{$v['object_code']}}</td>
                                        <td>{{$v['object_name']}}</td>
                                        <td class="text-center">{{$v['total_quantity']}}</td>
                                        <td class="text-center">{{$v['finish_quantity']}}</td>
                                        {{--                                        <td class="text-center">{{$v['un_finish_quantity']}}</td>--}}
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Lịch sử giao hàng'):
                    </label>
                </div>
                <div class="form-group m-form__group">
                    <div class="table-responsive">
                        <table class="table table-striped m-table" id="table_history">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('Đơn vị vận chuyển')</th>
                                <th class="tr_thead_list">@lang('Người giao hàng')</th>
                                {{--                                <th class="tr_thead_list">Thời gian bắt đầu</th>--}}
                                {{--                                <th class="tr_thead_list">Thời gian kết thúc</th>--}}
                                <th class="tr_thead_list">@lang('Thời gian')</th>
                                <th class="tr_thead_list">@lang('Thông tin khách hàng')</th>
                                <th class="tr_thead_list">@lang('Số tiền cần thu')</th>
                                <th class="tr_thead_list">@lang('Trạng thái')</th>
                                <th class="tr_thead_list">@lang('Xác nhận thanh toán')</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($history as $v)
                                <tr class="tr_history">
                                    <td>
                                        {{$v['transport_name']}}
                                        <input type="hidden" class="delivery_history_id"
                                               value="{{$v['delivery_history_id']}}">
                                    </td>
                                    <td>{{$v['staff_name']}}</td>
                                    {{--                                    <td>{{$item['delivery_start']}}</td>--}}
                                    {{--                                    <td>{{$item['delivery_end']}}</td>--}}
                                    <td>

                                        @lang('Thời gian giao hàng dự kiến'):
                                        <strong>{{\Carbon\Carbon::parse($v['time_ship'])->format('d/m/Y H:i')}}</strong>
                                        <br>
                                        @lang('Thời gian lấy hàng'):
                                        <strong>{{$v['time_pick_up'] != null ? \Carbon\Carbon::parse($v['time_pick_up'])->format('d/m/Y H:i') : ''}}</strong>
                                        <br>
                                        @lang('Thời gian giao hàng'):
                                        <strong>{{$v['time_drop'] != null ? \Carbon\Carbon::parse($v['time_drop'])->format('d/m/Y H:i') : ''}}</strong>
                                    </td>
                                    <td>
                                        @lang('Người nhận'): <strong>{{$v['contact_name']}}</strong> <br>
                                        @lang('Sđt'): <strong>{{$v['contact_phone']}}</strong> <br>
                                        @lang('Địa chỉ'): <strong>{{$v['contact_address']}}</strong><br>
                                        @lang('Nơi lấy hàng'): <strong>{{$v['pick_up']}}</strong><br>
                                    </td>
                                    <td>{{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')</td>
                                    <td style="width:15%;">
                                        <select class="form-control status">
                                            <option></option>
                                            @if ($v['status'] == 'new')
                                                <option value="new" {{$v['status'] == 'new' ? 'selected' : ''}}>
                                                    {{--                                                    @lang('Mới')--}}
                                                    @lang('Đóng gói')
                                                </option>
                                            @endif
                                            @if (in_array($v['status'], ['new', 'inprogress']))
                                                <option value="inprogress" {{$v['status'] == 'inprogress' ? 'selected' : ''}}>
                                                    {{--                                                    @lang('Đang giao')--}}
                                                    @lang('Đã nhận hàng')
                                                </option>
                                            @endif
                                            @if (in_array($v['status'], ['new', 'inprogress', 'success']))
                                                <option value="success" {{$v['status'] == 'success' ? 'selected' : ''}}>
                                                    {{--                                                    @lang('Hoàn thành')--}}
                                                    @lang('Đã giao hàng')
                                                </option>
                                            @endif
                                            @if (in_array($v['status'], ['new', 'inprogress', 'success','confirm']))
                                                <option value="confirm" {{$v['status'] == 'confirm' ? 'selected' : ''}}>
                                                    @lang('Xác nhận đã giao hàng')
                                                </option>
                                            @endif
                                            @if (in_array($v['status'], ['new', 'inprogress', 'cancel']))
                                                <option value="cancel" {{$v['status'] == 'cancel' ? 'selected' : ''}}>
                                                    @lang('Hủy')
                                                </option>
                                            @endif
                                            @if (in_array($v['status'], ['new', 'inprogress', 'fail']))
                                                <option value="fail" {{$v['status'] == 'fail' ? 'selected' : ''}}>
                                                    @lang('Thất bại')
                                                </option>
                                            @endif
                                            @if (in_array($v['status'], ['new', 'inprogress', 'pending']))
                                                <option value="pending" {{$v['status'] == 'pending' ? 'selected' : ''}}>
                                                    @lang('Đang chờ xử lý')
                                                </option>
                                            @endif
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        @if(in_array($v['status'], ['new','inprogress', 'confirm', 'success']) && $v['amount'] > 0 && $v['verified_payment'] == 0)
                                            <a href="javascript:void(0)"
                                               onclick="detail.modalConfirmReceipt({{$v['delivery_history_id']}})"
                                               class="btn btn-primary btn-sm">
                                                @lang('Xác nhận')
                                            </a>
                                        @elseif(in_array($v['status'], ['cancel', 'fail']))
                                            @lang('Đã hủy')
                                        @else
                                            @lang('Không cần xác nhận')
                                        @endif
                                    </td>
                                    <td>
                                        @if($item['delivery_status'] !='delivered' && !in_array($v['status'], ['success', 'confirm', 'cancel', 'fail']))
                                            <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                               href="{{route('delivery-history.edit', $v['delivery_history_id'])}}">
                                                <i class="la la-edit"></i>
                                            </a>
                                        @endif
                                        <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                           href="{{route('delivery-history.show', $v['delivery_history_id'])}}">
                                            <i class="la la-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @if(count($v['detail']) > 0)
                                    <tr>
                                        <td colspan="8">
                                            <div class="m-accordion m-accordion--default" id="m_accordion_1"
                                                 role="tablist">
                                                <!--begin::Item-->
                                                <div class="m-accordion__item">
                                                    <div class="m-accordion__item-head" role="tab"
                                                         id="m_accordion_{{$v['delivery_history_id']}}_item_{{$v['delivery_history_id']}}_head"
                                                         data-toggle="collapse"
                                                         href="#m_accordion_{{$v['delivery_history_id']}}_item_{{$v['delivery_history_id']}}_body">
                                                        <span class="m-accordion__item-icon"><i class="la la-cube"></i></span>
                                                        <span class="m-accordion__item-title">@lang('Sản phẩm giao hàng')</span>
                                                        <span class="m-accordion__item-mode"></span>
                                                    </div>
                                                    <div class="m-accordion__item-body collapse show"
                                                         id="m_accordion_{{$v['delivery_history_id']}}_item_{{$v['delivery_history_id']}}_body">
                                                        <div class="m-accordion__item-content">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered m-table">
                                                                    <thead>
                                                                    <tr class="bg_tr_white">
                                                                        <th>@lang('Mã sản phẩm')</th>
                                                                        <th>@lang('Tên sản phẩm')</th>
                                                                        <th>@lang('SKU')</th>
                                                                        <th>@lang('Số lượng giao')</th>
                                                                        <th>@lang('Ghi chú')</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($v['detail'] as $v1)
                                                                        <tr class="bg_tr_white">
                                                                            <td>{{$v1['product_code']}}</td>
                                                                            <td>
                                                                                {{$v1['product_name']}}
                                                                                @if(in_array($v1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                                                                    ({{__('quà tặng')}})
                                                                                @endif
                                                                            </td>
                                                                            <td>{{$v1['sku']}}</td>
                                                                            <td>{{$v1['quantity']}}</td>
                                                                            <td>{{$v1['note']}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Item-->
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('delivery')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="detail.save({{$item['delivery_id']}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU TRẠNG THÁI')</span>
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
    <script>
        var decimalQuantity = parseInt('{{$decimalQuantity}}');
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/delivery/delivery/script.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        detail._init();
    </script>
@stop


