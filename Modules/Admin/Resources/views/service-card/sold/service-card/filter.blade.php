<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('MÃ THẺ')}}</th>
            <th class="ss--font-size-th">{{__('TÊN THẺ DỊCH VỤ')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('KH MUA')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('KH KÍCH HOẠT')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NV BÁN')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NV KÍCH HOẠT')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('CHI NHÁNH')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGÀY KÍCH HOẠT')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('GHI CHÚ')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('HÀNH ĐỘNG')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $key=>$value)
                <tr>
                    <td class="ss--font-size-13">{{$key+1}}</td>
                    <td class="ss--font-size-13">
{{--                        @if($value['customer_actived']!='')--}}
                            <a href="{{route('admin.service-card.sold.detail',['type'=>'service','code'=>$value['card_code']])}}"
                               class="ss--text-black">
                                {{$value['card_code']}}
                            </a>
{{--                        @else--}}
{{--                            ****************--}}
{{--                        @endif--}}
                    </td>
                    <td class="ss--font-size-13">{{$value['service_card_name']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['customer_pay']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['customer_actived']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['staff_sold']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['staff_actived']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['branch']}}</td>
                    @if($value['is_actived']==1)
                        @if ($value['is_deleted'] == 0)
                            <td class="ss--text-center ss--font-size-13"><h6
                                        class="m--font-success">{{__('Đã kích hoạt')}}</h6></td>
                        @else
                            <td class="ss--text-center ss--font-size-13"><h6
                                        class="m--font-danger">{{__('Đã huỷ')}}</h6></td>
                        @endif
                    @else
                        <td class="ss--text-center ss--font-size-13"><h6
                                    class="m--font-danger">{{__('Chưa kích hoạt')}}</h6></td>
                    @endif
                    <td class="ss--font-size-13" style="text-align: center">
                        {{$value['actived_date']!=''?date_format(new DateTime($value['actived_date']), 'd/m/Y'):''}}
                    </td>
                    <td class="ss--font-size-13">
                        {{ $value['note'] }}
                    </td>
                    <td class="ss--text-center">
                        @if (in_array('admin.service-card.sold.edit',session('routeList')))
                            @if($value['is_actived'] == 1 && $value['is_deleted'] == 0)
                                <a href="{{ route('admin.service-card.sold.edit', ['type'=>'service','code'=>$value['card_code']]) }}"
                                   title="{{__('Cập nhật')}}"
                                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-edit"></i>
                                </a>
                            @endif
                        @endif
                        @if($value['is_actived'] == 1 && $value['is_deleted'] == 0 && $value['is_reserve'] == 0 && $value['is_use'] == 1)
                            @if ($value['showButtonReserve'] == 1)
                                <button title="{{__('Bảo lưu')}}"
                                        onclick="serviceCard.reserve('{{$value['card_code']}}')"
                                        class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-save"></i>
                                </button>
                            @endif
                            <button title="{{__('Cộng dồn')}}"
                                    onclick="serviceCardSold.modalAccrual('{{$value['card_code']}}')"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-plus"></i>
                            </button>
                        @endif
                        @if($value['is_actived'] == 1 && $value['is_deleted'] == 0 && $value['is_reserve'] == 1)
                            <button title="{{__('Mở bảo lưu')}}"
                                    onclick="serviceCard.openReservation('{{$value['card_code']}}')"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-share-square"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@include('admin::service-card.sold.paging-filter')