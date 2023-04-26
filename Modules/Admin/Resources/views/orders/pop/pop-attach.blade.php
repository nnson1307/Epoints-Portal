<div class="modal fade" id="popup-attach" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    {{__('Ghi chú/Dịch vụ thêm')}}
                </h4>
            </div>
            <div class="modal-body">
                @if (count($list) > 0)
                    <table class="table m-table m-table--head-bg-default" id="table-attach">
                        <thead class="bg">
                        <tr>
                            <th style="border-right: none; font-size: 14px">{{ $object_name }}</th>
                            <th style="border-left: none">{{ $object_price }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" style="border: none">
                                   <b> @lang('Chọn dịch vụ thêm')</b>
                                </td>
                            </tr>
                        @foreach($list as $v)
                         
                            <tr class="tr_attach">
                                <td style="border: none; padding-left: 30px;">
                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                        <input type="checkbox" class="check_attach"
                                                {{isset($attachChoose[$v['object_id']]) ? 'checked': ''}}> {{$v['object_name']}}
                                        <span></span>
                                    </label>

                                    <input type="hidden" class="object_type" value="{{$v['object_type']}}">
                                    <input type="hidden" class="object_id" value="{{$v['object_id']}}">
                                    <input type="hidden" class="object_code" value="{{$v['object_code']}}">
                                    <input type="hidden" class="object_name" value="{{$v['object_name']}}">
                                    <input type="hidden" class="price" value="{{$v['promotion_price'] > 0 ? $v['promotion_price'] : $v['price']}}">
                                </td>
                                <td style="border: none">
                                  @if($v['promotion_price'] > 0)
                                    {{number_format($v['promotion_price'] , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    <span style="font-size: 12px;color: #9e9e9e;text-decoration: line-through;">
                                      {{number_format( $v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    </span>
                                  @else
                                    {{number_format( $v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                  @endif

                                </td>
                                <td style="width:20%; display: none;border: none">
                                    <input style="text-align: center;" type="text"
                                           class="quantity_attach form-control btn-ct-input"
                                           value="{{isset($attachChoose[$v['object_id']]) ? $attachChoose[$v['object_id']]['quantity']: 1}}" readonly>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="form-group">
                    <label>@lang('Ghi chú'):</label>
                    <input type="text" class="form-control kt-quick-search__input" id="note_object"
                           placeholder="Nhập ghi chú..." value="{{$note}}">
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('ĐÓNG')}}</span>
						</span>
                        </button>

                        <button class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md
                            m--margin-left-10" onclick="order.chooseAttach('{{$stt}}')">
							<span>
							<i class="la la-check"></i>
							<span>{{__('THÊM')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>