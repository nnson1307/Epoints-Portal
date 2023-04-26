
<div class="form-group table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">{{__('TÊN DEAL')}}</th>
            <th class="tr_thead_list">{{__('MÃ DEAL')}}</th>
            <th class="tr_thead_list">{{__('TÊN NHÂN VIÊN')}}</th>
            <th class="tr_thead_list">{{__('NGÀY TẠO')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$item['deal_name']}}</td>
                    <td>{{$item['deal_code']}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}