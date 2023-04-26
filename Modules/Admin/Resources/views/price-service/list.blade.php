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
            <th>Dịch vụ</th>
            <th>Nhóm</th>
            <th>{{__('Chi nhánh')}}</th>
            <th>Giá</th>
            <th>Tình trạng</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item['service_name']}}</td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['new_price']}}</td>
                    <td>
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="service_branch_price.changeStatus(this, '{!! $item['service_branch_price_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="service_branch_price.changeStatus(this, '{!! $item['service_branch_price_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td>

                        <a class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           href='{{route("admin.service-branch-price.detail",$item['service_id'])}}' ><i class="la la-eye"></i>
                        </a>
                        <a href="{{route('admin.service-branch-price.edit',array ('id'=>$item['service_branch_price_id']))}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="View">
                            <i class="la la-edit"></i>
                        </a>

                        <button onclick="service_branch_price.remove(this, {{$item['service_branch_price_id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
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
