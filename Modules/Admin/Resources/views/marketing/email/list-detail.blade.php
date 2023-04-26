<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default table_list">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('KHÁCH HÀNG')}}</th>
            <th class="tr_thead_list text-center">{{__('EMAIL')}}</th>
            <th class="tr_thead_list text-center">{{__('NGƯỜI TẠO')}}</th>
            <th class="tr_thead_list text-center">{{__('NGƯỜI GỬI')}}</th>
            <th class="tr_thead_list text-center">{{__('NGÀY TẠO')}}</th>
            <th class="tr_thead_list text-center">{{__('NGÀY GỬI')}}</th>
            <th class="tr_thead_list text-center">{{__('TRẠNG THÁI')}}</th>
        </tr>
        </thead>
        <tbody class="table_list_body" style="font-size: 13px">
        @if(isset($LIST))
            @foreach($LIST as $key=>$value)
                <tr class="old">
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                        <input type="hidden" name="name" value="{{$value['id']}}">
                    </td>
                    <td>{{$value['customer_name']}}
                        {{--<input type="hidden" name="name" value="{{$value['customer_name']}}">--}}
                    </td>
                    <td class="text-center">{{$value['email']}}
                        {{--<input type="hidden" name="email" value="{{$value['email']}}">--}}
                    </td>
                    {{--<td>--}}
                    {{--{{$value['content_sent']}}--}}
                    {{--<input type="hidden" name="content" value="{{$value['content_sent']}}">--}}
                    {{--</td>--}}
                    <td class="text-center">
                        {{$value['name_add']}}
                    </td>
                    <td class="text-center">{{$value['name_sent']}}</td>
                    <td class="text-center">{{date("d/m/Y",strtotime($value['created_at']))}}</td>
                    @if($value['time_sent']!=null)
                        <td class="text-center">{{date("d/m/Y",strtotime($value['time_sent']))}}</td>
                    @endif
                    @if($value['email_status']=='new')
                        <td style="color: #0a8cf0" class="text-center">{{__('Mới')}}</td>
                    @elseif($value['email_status']=='cancel')
                        <td style="color: #ff0000" class="text-center">{{__('Hủy')}}</td>
                    @elseif($value['email_status']=='sent')
                        <td style="color: #008000" class="text-center">{{__('Thành công')}}</td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
