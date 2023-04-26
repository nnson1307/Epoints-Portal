<div class="modal fade " role="dialog" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title_index">@lang('CHI TIẾT CÔNG NỢ')</h5>
                {{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--                    <span aria-hidden="true">×</span>--}}
                {{--                </button>--}}
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>@lang('Mã khách hàng'):</label> {{$itemReceipt['customer_code']}}
                </div>
                <div class="form-group">
                    <label>@lang('Tên khách hàng'):</label>
                    @if(in_array('admin.customer.detail',session('routeList')))
                        <a href="{{route("admin.customer.detail", $itemReceipt['customer_id'])}}" target="_blank">
                            {{$itemReceipt['customer_name']}}
                        </a>
                    @else
                        {{$itemReceipt['customer_name']}}
                    @endif
                </div>

                @if(count($order_detail)>0)
                    <div class="m-section bdb_order">
                        <div class="m-section__content">
                            <div class="table-responsive">
                                <table class="table table-striped m-table">
                                    <thead style="white-space: nowrap;">
                                    <tr>
                                        <th class="tr_thead_od_detail">@lang('TÊN DỊCH VỤ')</th>
                                        <th class="tr_thead_od_detail">@lang('GIÁ DỊCH VỤ')</th>
                                        <th class="tr_thead_od_detail text-center">@lang('SỐ LƯỢNG')</th>
                                        <th class="tr_thead_od_detail text-center">@lang('GIẢM GIÁ')</th>
                                        <th class="tr_thead_od_detail text-center">@lang('MÃ GIẢM GIÁ')</th>
                                        <th class="tr_thead_od_detail">{{__('TỔNG TIỀN')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody style="font-size: 12px">
                                    @foreach($order_detail as $item)
                                        <tr>
                                            <td>{{$item['object_name']}}</td>
                                            <td>{{number_format($item['price'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')</td>
                                            <td class="text-center">{{$item['quantity']}}</td>
                                            <td class="text-center">{{number_format($item['discount'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')</td>
                                            <td class="text-center">
                                                @if($item['voucher_code']!=null)
                                                    {{$item['voucher_code']}}
                                                @else
                                                    {{--                                                    @lang('Không có')--}}
                                                @endif

                                            </td>
                                            <td>{{number_format($item['amount'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-md-3 w-me-40 font-13">
                            <label>@lang('Tổng tiền nợ')</label>
                        </div>
                        <div class="col-md-9 w-me-60 font-13">
                        <span class="float-right">
                            <strong>{{number_format($itemReceipt['amount'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')</strong>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-md-3 w-me-40 font-13">
                            <label>{{__('Đã thanh toán')}}</label>
                        </div>
                        <div class="col-md-9 w-me-60 font-13">
                        <span class="float-right">
                            <strong>{{number_format($itemReceipt['amount_paid'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')</strong>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-3 w-me-40  font-13">@lang('Còn nợ'):</label>
                        <div class="col-lg-9 w-me-40 ">
                            <span class="font-13 font-weight-bold cl_receipt_amount"
                                  style="float: right;color: red">{{number_format($itemReceipt['amount'] - $itemReceipt['amount_paid'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')</span>
                            <input type="hidden" class="form-control m--font-bolder" disabled="disabled"
                                   name="receipt_amount" id="receipt_amount"
                                   value="{{($itemReceipt['amount'] - $itemReceipt['amount_paid'])}}">
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-md-3 w-me-40 font-13">
                            <label>{{__('Ghi chú')}}</label>
                        </div>
                        <div class="col-md-9 w-me-60 font-13">
                        <span class="float-right">
                            <strong>{{$itemReceipt['note']}}</strong>
                        </span>
                        </div>
                    </div>
                </div>
                @if(count($receipt)>0)
                    <div class="form-group m-form__group bdb_order">
                        <div class="m-section__content">
                            <div class="table-responsive">
                                <table class="table table-striped m-table">
                                    <thead style="white-space: nowrap;">
                                    <tr>
                                        <th class="tr_thead_od_detail">@lang('NGÀY THANH TOÁN')</th>
                                        <th class="tr_thead_od_detail">@lang('NGƯỜI THU')</th>
                                        <th class="tr_thead_od_detail">@lang('TIỀN THANH TOÁN')</th>
                                    </tr>
                                    </thead>
                                    <tbody style="font-size: 12px">
                                    @foreach($receipt as $item)
                                        <tr>
                                            <td>
                                                {{date("H:i d/m/Y",strtotime($item['receipt_date']))}}
                                            </td>
                                            <td>
                                                {{$item['full_name']}}
                                            </td>
                                            <td>
                                                {{number_format($item['amount_paid'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                            </td>
                                        </tr>
                                        @if(isset($item['receipt_detail']))
                                            @foreach($item['receipt_detail'] as $item_detail)
                                                <tr>
                                                    <td>

                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        {{$item_detail['payment_method_name']}} :
                                                        {{number_format($item_detail['amount'],  isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                                        @lang('đ')
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn"
                        data-dismiss="modal">
                    <span>
                        <i class="la la-arrow-left"></i><span>{{__('HỦY')}}</span>
                    </span>
                </button>
                {{--                <button type="submit$" onclick="indexDebt.receipt('{{$itemReceipt['receipt_id']}}')" id="btn_order"--}}
                {{--                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">--}}
                {{--							<span>--}}
                {{--							<i class="la la-reorder"></i>--}}
                {{--							<span>THANH TOÁN</span>--}}
                {{--							</span>--}}
                {{--                </button>--}}
            </div>
        </div>
    </div>
</div>
