<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list text-center">{{__('Đơn vị quy đổi')}}</th>
            <th class="tr_thead_list text-center">{{__('Đơn vị gốc')}}</th>
            <th class="tr_thead_list text-center">{{__('Tỉ lệ chuyển đổi')}}</th>
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
                    <td class="text-center">{{$item['name']}}</td>
                    <td class="text-center">
                        {{$item['standard_name']}}
                    </td>
                    <td class="text-center">{{$item['conversion_rate']}}</td>

                    <td>
                        @if(in_array('admin.unit_conversion.submitedit',session('routeList')))
                            <button value="{{$item['unit_conversion_id']}}"
                                    onclick="unit_conversion.edit({{$item['unit_conversion_id']}})"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('admin.unit_conversion.remove',session('routeList')))
                            <button onclick="unit_conversion.remove(this, {{$item['unit_conversion_id']}})"
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
