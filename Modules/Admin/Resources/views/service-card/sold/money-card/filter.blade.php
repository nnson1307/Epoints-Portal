<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary">
        <thead>
        <tr>
            <th>#</th>
            <th>{{__('MÃ THẺ')}}</th>
            <th>{{__('Tên Thẻ Dịch vụ')}}</th>
            <th class="ss--text-center">{{__('KH MUA')}}</th>
            <th class="ss--text-center">{{__('KH KÍCH HOẠT')}}</th>
            <th class="ss--text-center">{{__('NV BÁN')}}</th>
            <th class="ss--text-center">{{__('NV KÍCH HOẠT')}}</th>
            <th class="ss--text-center">{{__('CHI NHÁNH')}}</th>
            <th class="ss--text-center">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center">{{__('NGÀY KÍCH HOẠT')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $key=>$value)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
{{--                        @if($value['customer_actived']!='')--}}
                            <a href="{{route('admin.service-card.sold.detail',['money',$value['card_code']])}}"
                               class="ss--text-black">
                                {{$value['card_code']}}
                            </a>
{{--                        @else--}}
{{--                            ****************--}}
{{--                        @endif--}}
                    </td>
                    <td class="ss--text-center">{{$value['service_card_name']}}</td>
                    <td class="ss--text-center">{{$value['customer_pay']}}</td>
                    <td class="ss--text-center">{{$value['customer_actived']}}</td>
                    <td class="ss--text-center">{{$value['staff_sold']}}</td>
                    <td class="ss--text-center">{{$value['staff_actived']}}</td>
                    <td class="ss--text-center">{{$value['branch']}}</td>
                    @if($value['is_actived']==1)
                        <td class="ss--text-center"><h6 class="m--font-success">{{__('Đã kích hoạt')}}</h6></td>
                    @else
                        <td class="ss--text-center"><h6 class="m--font-danger">{{__('Chưa kích hoạt')}}</h6></td>
                    @endif
                    <td class="ss--text-center">
                        {{$value['actived_date']!=''?date_format(new DateTime($value['actived_date']), 'd/m/Y'):''}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@include('admin::service-card.sold.paging-filter')
