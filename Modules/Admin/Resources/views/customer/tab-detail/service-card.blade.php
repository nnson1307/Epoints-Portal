<div class="tab-content">
    <div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="true">
       
            <div class="m-scrollable m-scroller ps ps--active-y m--margin-top-5"
                 data-scrollable="true"
                 data-height="350" data-mobile-height="300"
                 style="height: 300px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table m-table m-table--head-separator-metal"
                           style="border-collapse: collapse;">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_lis">@lang("Tên thẻ")</th>
                            <th class="tr_thead_list">@lang("Loại thẻ")</th>
                            <th class="tr_thead_list">@lang("Mã thẻ")</th>
                            <th class="tr_thead_list">@lang("Trị giá")</th>
                            <th class="tr_thead_list">@lang("Hạn sử dụng")</th>
                            <th class="tr_thead_list">@lang("Số lần sử dụng")</th>
                            <th class="tr_thead_list">@lang("Số lần đã sử dụng")</th>
                            <th class="tr_thead_list">@lang("Hạn bảo hành")</th>
                            <th class="tr_thead_list"
                                style="width: 160px;">
                                {{__('Trạng thái')}}
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        @if(count($data)>0)
                        <tbody>
                        @foreach($data as $kc=>$vc)
                            <tr>
                                <td>{{$vc['card_name']}}</td>
                                <td class="text-center">
                                    @if($vc['service_card_type'] == 'service')
                                        @lang("Thẻ dịch vụ")
                                    @else
                                        @lang("Thẻ tiền")
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($vc['card_code']) && $vc['card_code'] != null)
                                        <a href="{{route("admin.service-card.sold.detail", ['service', $vc['card_code']])}}"
                                           class="line-name font-name">{{$vc['card_code']}}</a>
                                    @endif
                                </td>
                                <td class="text-center" style="color: #ff0000">
                                    {{number_format($vc['price'])}} @lang('đ')
                                </td>
                                <td class="text-center">
                                    @if($vc['expired_date'] != null)
                                        {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vc['expired_date'])->format('d/m/Y')}}
                                    @else
                                        @lang('Không giới hạn')
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($vc['number_using'] == 0)
                                        @lang('Không giới hạn')
                                    @else
                                        {{$vc['number_using']}}
                                    @endif
                                </td>
                                <td class="text-center">{{$vc['count_using']}}</td>
                                <td class="text-center">
                                    @if($vc['warranty_status'] == "actived" && $vc['warranty_expired'] != null)
                                        {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vc['warranty_expired'])->format('d/m/Y H:i:s')}}
                                    @elseif($vc['warranty_status'] == "actived" && $vc['warranty_expired'] == null)
                                        @lang('Không giới hạn')
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($vc['is_actived']==1)
                                        <span class="m-badge m-badge--success m-badge--wide"
                                              style="width: 80%">@lang("Đã kích hoạt")</span>
                                    @else
                                        <span class="m-badge m-badge--danger m-badge--wide"
                                              style="width: 80%">@lang("Chưa kích hoạt")</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vc['is_use'] == 1)
                                        <a href="javascript:void(0)"
                                           onclick="detail.usingCard('{{$vc['code']}}')">
                                            {{__('Sử dụng')}}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
    </div>

</div>