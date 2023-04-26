<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th></th>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Nội dung')}}</th>
            <th class="tr_thead_list">{{__('Người tạo')}}</th>
            <th class="tr_thead_list">{{__('Ngày tạo')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <a href="javascript:void(0)" onclick="detail.popEditNote('{{$item['customer_note_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Cập nhật')}}">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                    <td> {{isset($page) ? ($page-1)*10 + $key+1 : $key+1}}</td>
                    <td>{{$item['note']}}</td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}