<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Đơn vị vận chuyển')}}</th>
{{--            <th class="tr_thead_list">{{__('Chi phí')}}</th>--}}
            <th class="tr_thead_list">{{__('Địa chỉ')}}</th>
            <th class="tr_thead_list">{{__('Người đại diện')}}</th>
            <th class="tr_thead_list">{{__('SĐT')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>{{$item['transport_name']}}</td>
{{--                    <td>--}}
{{--                        {{number_format($item['charge'],0,"",",")}}--}}
{{--                    </td>--}}
                    <td>{{$item['address']}}</td>
                    <td>{{$item['contact_name']}}</td>
                    <td>{{$item['contact_phone']}}</td>
                    <td style="width: 100px">
                        @if(in_array('admin.transport.submitedit',session('routeList')))
                            <button value="{{$item['transport_id']}}"
                                    onclick="transport.edit({{$item['transport_id']}})"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('admin.warehouse.delete',session('routeList')))
                            @if($item['is_system'] == 0)
                                <button onclick="transport.remove(this, {{$item['transport_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Delete">
                                    <i class="la la-trash"></i>
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
