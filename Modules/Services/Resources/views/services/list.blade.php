<style>

    .dropbtn {
        background-color: #4CAF50;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 9;
    }

    /*/ Links inside the dropdown /*/
    .dropdown-content a {
        color: #ff7652;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }

</style>


<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary">
        <thead>
        <tr>
            <th>#</th>
            <th>Mã dịch vụ</th>
            <th>Tên dịch vụ</th>
            <th>Thời gian</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))

            @foreach ($LIST as $key=>$item)
                <tr>
                    <td>{{ ($key+1) }}</td> {{--$key la dem so phan tu cua mang dem tu 0 tro di, nhung o day ta +1 nen no se chay tu 1 2 3 4 5 6--}}
                    <td>{{ $item['service_code'] }}</td> {{--va xuat ra 1 day so thu tu tang dan theo so phan tu cua mang--}}
                    <td>{{ $item['service_name'] }}</td>
                    <td>{{ $item['time'] }}</td>
                    <td>
                        @if ($item['is_active'])
                            <span class="m-badge  m-badge--success m-badge--wide">Active</span>
                        @else
                            <span class="m-badge  m-badge--danger m-badge--wide">Deactive</span>
                        @endif
                    </td>

                    <td>
                        <div class="dropdown">
                            <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill dropbtn">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                            <div class="dropdown-content">
                                @if ($item['is_active'])
                                    <a style="color: #ff4033" href='javascript:void (0)'
                                       onclick="service.changeStatus(this,'{!! $item['service_id'] !!}', 'publish')"><i
                                                class="fa fa-circle-o"></i> Tạm ngưng </a>
                                @else
                                    <a style="color: #1ab315" href='javascript:void (0)'
                                       onclick="service.changeStatus(this,'{!! $item['service_id'] !!}', 'unPublish')"><i
                                                class="fa fa-circle-o"></i> Kính hoạt</a>
                                @endif
                            </div>
                        </div>
                        <a href="{{route('services.edit',array('id'=>$item['service_id']))}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="View"><i class="la la-edit"></i></a>
                        <button onclick="service.remove(this, '{{ $item['service_id'] }}')"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="Delete"><i class="la la-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

{{ $LIST->links('helpers.paging') }}



