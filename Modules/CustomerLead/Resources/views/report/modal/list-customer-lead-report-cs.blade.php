
<div class="form-group table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">{{__('TÊN LEAD')}}</th>
            <th class="tr_thead_list">{{__('SỐ ĐIỆN THOẠI')}}</th>
            <th class="tr_thead_list">{{__('EMAIL')}}</th>
            <th class="tr_thead_list">{{__('ĐỊA CHỈ')}}</th>
            <th class="tr_thead_list">{{__('GIỚI TÍNH')}}</th>
            <th class="tr_thead_list">{{__('NGÀY DIỄN RA')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$item['full_name']}}</td>
                    <td>{{$item['phone']}}</td>
                    <td>{{$item['email']}}</td>
                    <td>{{$item['address']}}</td>
                    <td>
                        @if($item['gender']=='male')
                            {{__('Nam')}}
                        @elseif($item['gender']=='female')
                            {{__('Nữ')}}
                        @endif
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}