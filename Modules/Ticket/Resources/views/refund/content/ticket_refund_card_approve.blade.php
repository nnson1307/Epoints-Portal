<div class="card mb-4" id="ticket_refund_{{$ticketItem->ticket_id}}" data-id="{{$ticketItem->ticket_id}}">
    <div class="card-header bg-white" id="heading{{$ticketItem->ticket_id}}" data-toggle="collapse" data-target="#collapse{{$ticketItem->ticket_id}}"
        aria-expanded="true" aria-controls="collapse{{$ticketItem->ticket_id}}">
        <h4 class="fz-1_5rem color-primary m--font-bold m-0">
            <i class="fa fa-bookmark fz-1_5rem color-primar mr-3"></i>{{$ticketItem->ticket_code}}
            <span class="float-right"><i class="fas fa-angle-down fz-1_5rem"></i></span>
        </h4>
    </div>
    <div id="collapse{{$ticketItem->ticket_id}}" class="collapse show" aria-labelledby="heading{{$ticketItem->ticket_id}}">
        <div class="card-body">
            <div class="material_temple">
                @if (count($materialListRefund) > 0)
                <h4 class="fz-1_5rem mb-4">{{ __('Vật tư tạm ứng') }}:</h4>
                <div class="table-responsive">
                    <table
                        class="table table-striped m-table s--header-table ss--nowrap text-center">
                        <thead class="bg">
                            <tr>
                                <th class="ss--font-size-th">#</th>
                                <th class="ss--font-size-th">{{ __('Mã vật tư') }}</th>
                                <th class="ss--font-size-th">{{ __('Tên vật tư') }}</th>
                                <th class="ss--font-size-th">{!! __('Số lượng duyệt <br> tạm ứng') !!}</th>
                                <th class="ss--font-size-th">{!! __('Số lượng <br> thực tế') !!}</th>
                                <th class="ss--font-size-th">{!! __('Số lượng <br> hoàn ứng') !!}</th>
                                <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                                <th class="ss--font-size-th">{!! __('Số lượng duyệt <br> hoàn ứng') !!}</th>
                                <th class="ss--font-size-th">{{ __('Ghi chú') }}</th>
                                <th class="ss--font-size-th"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_quantity = 0;
                            @endphp
                            @foreach ($materialListRefund as $key => $item)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                        <input type="hidden" name="product[{{$item->ticket_id}}][product_id][]" value="{{$item->ticket_request_material_detail_id}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_request_material_detail_id}}][type]" value="A">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_request_material_detail_id}}][ticket_id]" value="{{$item->ticket_id}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_request_material_detail_id}}][obj_id]" value="{{$item->ticket_request_material_detail_id}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_request_material_detail_id}}][quantity]" value="{{$item->quantity_return}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_request_material_detail_id}}][money]" value="{{ $item->price*$item->quantity_return }}">
                                    </td>
                                    <td>{{ $item->product_code }}</td>
                                    <td style="white-space: initial;">{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity_approve }}</td>
                                    <td>{{ $item->quantity_reality }}</td>
                                    <td id="refund_id_{{$item->ticket_refund_item_id}}" data-value="{{$item->quantity_return}}">{{ $item->quantity_return }}</td>
                                    <td>{{ $item->unit_name }}</td>
                                    <td>{{ $item->quantity_refund }}</td>
                                    <td><span class="text-ellipsis">{{ $item->note }}</span></td>
                                    <td>
                                        @if ($check_edit)
                                        <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                            href="javascript:void(0);" onclick="Refund.show_approve_item({{$item->ticket_refund_item_id}},true)">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>    
                                        @endif
                                    </td>
                                    @php
                                        $total_quantity += $item->quantity_refund;
                                    @endphp
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h5 class="modal-title color-primary m--font-bold font-primary fw-500">
                    {{ __('Số lượng vật tư hoàn ứng') }}: <span class="total_quantity" data-value="{{ $total_quantity }}">{{ $total_quantity }}</span>
                </h5>
                @endif
            </div>
            @if (count($acceptanceIncurred) > 0)
            <div class="material-incurred mt-3">
                <h4 class="fz-1_5rem mb-4">{{ __('Vật tư phát sinh') }}:</h4>
                <div class="table-responsive">
                    <table
                        class="table table-striped m-table s--header-table ss--nowrap text-center">
                        <thead class="bg">
                            <tr>
                                <th class="ss--font-size-th">#</th>
                                <th class="ss--font-size-th">{{ __('Tên vật tư') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng') }}</th>
                                <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                                <th class="ss--font-size-th">{{ __('Thành tiền') }}</th>
                                <th class="ss--font-size-th">{{ __('Số tiền duyệt') }}</th>
                                <th class="ss--font-size-th">{{ __('Ghi chú') }}</th>
                                <th class="ss--font-size-th"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($acceptanceIncurred as $key => $item)
                                <tr>
                                    <td>
                                        {{$key+1}}
                                        <input type="hidden" name="product[{{$item->ticket_id}}][product_id][]" value="{{$item->ticket_acceptance_incurred_id}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_acceptance_incurred_id}}][type]" value="I">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_acceptance_incurred_id}}][ticket_id]" value="{{$item->ticket_id}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_acceptance_incurred_id}}][obj_id]" value="{{$item->ticket_acceptance_incurred_id}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_acceptance_incurred_id}}][quantity]" value="{{$item->quantity}}">
                                        <input type="hidden" name="product_item[{{$item->ticket_id}}][{{$item->ticket_acceptance_incurred_id}}][money]" value="{{$item->money_refund}}">
                                    </td>
                                    <td style="white-space: initial;">{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit_name }}</td>
                                    <td>{{ number_format($item->money, 0, '', '.') }} VND</td>
                                    <td id="refund_id_{{$item->ticket_refund_item_id}}" data-value="{{$item->money_refund}}">{{ number_format($item->money_refund, 0, '', '.') }} VND</td>
                                    <td><span class="text-ellipsis">{{ $item->note }}</span></td>
                                    <td>
                                        @if ($check_edit)
                                        <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                            href="javascript:void(0);" onclick="Refund.show_approve_item({{$item->ticket_refund_item_id}})">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $total_money += $item->money_refund;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h5 class="modal-title color-primary m--font-bold font-primary fw-500">
                    {{ __('Tổng tiền') }}: <span class="total_money" data-value="{{$total_money}}">{{ number_format($total_money, 0, '', '.') }}</span> VND
                </h5>
            </div>
            @endif
        </div>
        <div class="card-footer d-flex bg-white">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>
                        {{ __('Hồ sơ chứng từ') }}:
                    </label>
                    @php
                        $acceptance_file = '';
                    @endphp
                    <div class="div_file_ticket" data-id="{{$ticketItem->ticket_id}}">
                        @if (isset($file['refund']) && count($file['refund']) > 0)
                            @foreach ($file['refund'] as $v)
                                @if ($v['type'] == 'refund')
                                <div class="form-group m-form__group div_file d-flex mt-3">
                                    <input type="hidden" name="{{$ticketItem->ticket_id}}[refund][]" value="{{ $v['path_file'] }}">
                                    <a target="_blank" href="{{ url($v['path_file']) }}"
                                        class="file_ticket">
                                        {{ fileNameCustom($v['path_file']) }}
                                    </a>
                                </div>
                                @else
                                @php
                                    $acceptance_file .= '
                                    <div class="form-group m-form__group div_file d-flex mt-3">
                                        <input type="hidden" name="'.$ticketItem->ticket_id.'[acceptance][]" value="'.$v['path_file'].'">
                                        <a target="_blank" href="'.url($v['path_file']).'"
                                            class="file_ticket">
                                            '.fileNameCustom($v['path_file']).'
                                        </a>
                                    </div>';
                                @endphp
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>
                        {{ __('Biên bản nghiệm thu') }}:
                    </label>
                    <div class="div_file_ticket" data-id="{{$ticketItem->ticket_id}}">
                        @if (isset($file['acceptance']) && count($file['acceptance']) > 0)
                            @foreach ($file['acceptance'] as $v)
                                <div class="form-group m-form__group div_file d-flex mt-3">
                                    {{-- <input type="hidden" name="{{$ticketItem->ticket_id}}[acceptance][]" value="{{ $v['path_file'] }}"> --}}
                                    <a target="_blank" href="{{ url($v['path_file']) }}"
                                        class="file_ticket">
                                        {{ fileNameCustom($v['path_file']) }}
                                    </a>
                                </div>
                            @endforeach
                        @endif
                        {!! $acceptance_file !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>