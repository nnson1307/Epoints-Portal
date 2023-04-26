<style>
    th,td {
        text-align: center;
    }
</style>
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary">
        <thead>
        <tr>
            <th>#</th>
            <th>Tên thuế</th>
            <th>Tính thêm</th>
            <th>{{__('Ngày tạo')}}</th>
            <th>{{__('Trạng thái')}}</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['value']}}
                        @if ($item['type']=='percent')
                            %
                        @else
                            VNĐ
                        @endif
                    </td>
                    <td>{{$item['created_at']}}</td>
                    <td>
                        @if ($item['is_active'])
                            <span class="m-badge  m-badge--success m-badge--wide">Đang hoạt động</span>
                        @else
                            <span class="m-badge  m-badge--danger m-badge--wide">Tạm đóng</span>
                        @endif
                    </td>
                    <td>
                        <span class="actions">
                        <span class="btn-group">
                            <a class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-ellipsis-h"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                @if ($item['is_active'])
                                    <li>
                                        <a class="disabled" style="color: #ff4033" href='javascript:void (0)' onclick="tax.changeStatus(this, '{!! $item['tax_id'] !!}', 'publish')"><i class="fa fa-circle-o"></i> Tạm ngưng </a>
                                    </li>
                                @else
                                    <li>
                                        <a style="color: #1ab315" href='javascript:void (0)' onclick="tax.changeStatus(this, '{!! $item['tax_id'] !!}', 'unPublish')"><i class="fa fa-circle-o"></i> Kính hoạt</a>
                                    </li>
                                @endif
                            </ul>
                        </span>
                    </span>
                        <a href="{{route('admin.tax.edit',array('id'=>$item['tax_id']))}}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="View">
                            <i class="la la-edit"></i>
                        </a>
                        <button onclick="tax.remove(this, '{{ $item['tax_id'] }}')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="Delete">
                            <i class="la la-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}