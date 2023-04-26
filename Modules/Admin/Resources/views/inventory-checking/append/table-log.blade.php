<table
        class="table table-striped m-table ss--header-table ss--nowrap">
    <thead>
    <tr class="ss--uppercase ss--font-size-th">
        <th>#</th>
        <th>{{__('NGƯỜI THỰC HIỆN')}}</th>
        <th>{{__('NGÀY THỰC HIỆN')}}</th>
        <th>{{__('NỘI DUNG THỰC HIỆN')}}</th>
        <th>{{__('LÝ DO')}}</th>
    </tr>
    </thead>
    <tbody class="block-list-log">
    @foreach($listLog as $keyLog => $itemLog)
        <tr>
            <td>{{($listLog->currentPage() - 1)* $listLog->perPage() + $keyLog+1}}</td>
            <td>{{$itemLog['full_name']}}</td>
            <td>{{\Carbon\Carbon::parse($itemLog['created_at'])->format('d/m/Y H:i')}}</td>
            <td>{!! $itemLog['content'] !!}</td>
            <td>{{$itemLog['reason']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $listLog->links('admin::inventory-checking.helpers.paging-log') }}