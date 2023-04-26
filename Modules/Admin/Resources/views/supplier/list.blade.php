<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr class="ss--font-size-13">
            <th>#</th>
            <th>{{__('TÊN NHÀ CUNG CẤP')}}</th>
            <th>{{__('ĐỊA CHỈ')}}</th>
            <th class="ss--text-center">{{__('TÊN NGƯỜI ĐẠI DIỆN')}}</th>
            <th class="ss--text-center">{{__('CHỨC VỤ')}}</th>
            <th class="ss--text-center">{{__('SĐT')}}</th>
            <th class="ss--text-center"></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr class="ss--font-size-13">
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="ss--font-size-13">{{$key+1}}</td>
                    @endif
                    <td>{{ $item['supplier_name'] }}</td>
                    <td>{{ $item['address']}}</td>
                    <td style="width:200px" class="ss--text-center">{{ $item['contact_name']}}</td>
                    <td class="ss--text-center">{{ $item['contact_title']}}</td>
                    <td class="ss--text-center">{{ $item['contact_phone']}}</td>

                    <td style="width:100px" class="ss--text-center">
                        @if(in_array('admin.supplier.submit-edit',session('routeList')))
                            <button onclick="Supplier.edit({{$item['supplier_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('admin.supplier.remove',session('routeList')))
                            <button onclick="Supplier.remove(this, '{{ $item['supplier_id'] }}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
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