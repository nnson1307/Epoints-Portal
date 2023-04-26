<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('MÃ PHIẾU')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('KHO XUẤT')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('KHO NHẬP')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGƯỜI TẠO')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr data-id="{{ ($key+1) }}" class="ss--nowrap">
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ (($page-1)*10 + $key + 1) }}</td>
                    @else
                        <td class="ss--font-size-13">{{ ($key + 1) }}</td>
                    @endif
                    <td class="ss--font-size-13">
                        <a href="{{route('admin.inventory-transfer.detail',$item['id'])}}"
                           title="Chi tiết" class="ss--text-black"> {{ $item['transferCode'] }}
                        </a>
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['warehouseFrom'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['warehouseTo'] }}</td>
                    <td class="ss--text-center ss--font-size-13 ss--nowrap">{{ $item['user'] }}</td>
                    <td class="ss--text-center ss--font-size-13 ss--nowrap">
                        @if($item['status']=='new')
                            <span class="m-badge ss--button-cms-piospa m-badge--wide">{{__('Mới')}}</span>
                        @elseif($item['status']=='success')
                            <span class="m-badge ss--status-success m-badge--wide">{{__('Hoàn thành')}}</span>
                        @elseif($item['status']=='draft')
                            <span class="m-badge ss--status-draft m-badge--wide">{{__('Lưu nháp')}}</span>
                        @elseif($item['status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--wide">{{__('Hủy')}}</span>
                        @elseif($item['status']=='inprogress')
                            <span class="m-badge ss--status-processing m-badge--wide">{{__('Đang xử lý')}}</span>
                        @endif
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{(new DateTime($item['createdAt']))->format('d/m/Y')}}</td>
                    <td class="pull-right ss--font-size-13">
                        @if($item['status']!='success')
                            @if(Auth::user()->is_admin==1||in_array('admin.inventory-transfer.edit',session('routeList')))
                                <a href="{{route('admin.inventory-transfer.edit',$item['id'])}}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-modal-edit-s"
                                   title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                                </a>
                            @endif
                            @if(in_array('admin.inventory-transfer.remove',session('routeList')))
                                <button onclick="InventoryTransfer.remove(this, '{{ $item['id'] }}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Xóa')}}"><i class="la la-trash"></i>
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
