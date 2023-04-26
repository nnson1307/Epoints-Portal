<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tên kho')}}</th>
            <th class="tr_thead_list">{{__('Tên chi nhánh')}}</th>
            <th class="tr_thead_list">{{__('Địa chỉ')}}</th>
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
                    <td>{{$item['name']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['address']}}</td>
                    <td>
                        @if(in_array('admin.warehouse.submitedit',session('routeList')))
                            <button value="{{$item['warehouse_id']}}"
                                    onclick="warehouse.edit({{$item['warehouse_id']}})"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    id="edit1">
                                <i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('admin.transport.remove',session('routeList')))
                            <button onclick="warehouse.remove(this, {{$item['warehouse_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Delete">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
