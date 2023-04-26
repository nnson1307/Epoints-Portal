<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th ss--text-center ">#</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Tên đánh giá') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Giá trị') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Thứ tự') }}</th>

        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--text-center">{{$list->perpage()*($list->currentpage()-1)+($key+1)}}</td>
                    <td class="ss--text-center">{{$item['name']}}</td>
                    <td class="ss--text-center">{{$item['value']}}</td>
                    <td class="ss--text-center">{{$item['order']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>
