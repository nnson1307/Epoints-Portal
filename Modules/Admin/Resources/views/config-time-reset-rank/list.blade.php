<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tên')}}</th>
            <th class="tr_thead_list">{{__('Tháng thiết lập')}}</th>
            <th class="tr_thead_list">{{__('Khoảng cách tháng')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['value']}}</td>
                    <td>
                        @if($item['type'] == 'one_month')
                            1 tháng
                        @elseif($item['type'] == 'two_month')
                            2 tháng
                        @elseif($item['type'] == 'three_month')
                            3 tháng
                        @elseif($item['type'] == 'four_month')
                            4 tháng
                        @elseif($item['type'] == 'six_month')
                            6 tháng
                        @elseif($item['type'] == 'one_year')
                            12 tháng
                        @endif
                    </td>
                    <td>
                        {{--                        @if(in_array('admin.branch.edit',session('routeList')))--}}
                        <a href="javascript:void(0)" onclick="index.edit({{$item['id']}})"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                        {{--                        @endif--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
