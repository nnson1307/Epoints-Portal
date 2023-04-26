<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN NHÓM')}}</th>
            <th class="ss--font-size-th">{{__('LOẠI NHÓM')}}</th>
            <th class="ss--font-size-th">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr data-id="{{ ($key+1) }}">
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="ss--font-size-13">{{$key+1}}</td>
                    @endif
                    <td class="ss--font-size-13">
                        @if($item['filter_group_type'] == 'user_define')
                            <a class="ss--text-black" title="{{__('Chi tiết')}}"
                               href="{{route('admin.customer-group-filter.detail-customer-group-define',$item['id'])}}">
                                {{ $item['name'] }}
                            </a>
                        @elseif($item['filter_group_type'] == 'auto')
                            <a class="ss--text-black" title="{{__('Chi tiết')}}"
                               href="{{route('admin.customer-group-filter.detail-customer-group-define',$item['id'])}}">
                                {{ $item['name'] }}
                            </a>
                        @endif
                    </td>
                    <td class="ss--font-size-13">{{ $item['filter_group_type'] == 'auto' ? 'Tự động' : 'Tự định nghĩa' }}</td>
                    <td class="ss--font-size-13">{{ date_format($item['created_at'], 'd/m/Y') }}</td>
                    <td class="ss--font-size-13 pull-right">
                        {{--@if(in_array('customer-group.edit-submit',session('routeList')))--}}

                        @if($item['filter_group_type'] == 'user_define')
                            <button onclick="listUserGroup.deleteGroup(this,'define',{{$item['id']}})"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-modal-edit-s"
                               title="{{__('Xoá')}}"><i class="la la-trash"></i>
                            </button>
                        @elseif($item['filter_group_type'] == 'auto')
                            <button onclick="listUserGroup.deleteGroup(this,'auto',{{$item['id']}})"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-modal-edit-s"
                               title="{{__('Xoá')}}"><i class="la la-trash"></i>
                            </button>
                        @endif
                        @if($item['filter_group_type'] == 'user_define')
                            <a href="{{route('admin.customer-group-filter.edit-user-define', $item['id'])}}"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-modal-edit-s"
                                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                            </a>
                        @elseif($item['filter_group_type'] == 'auto')
                            <a href="{{route('admin.customer-group-filter.edit-customer-group-auto', $item['id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-modal-edit-s"
                               title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                            </a>
                        @endif
                        {{--@endif--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
