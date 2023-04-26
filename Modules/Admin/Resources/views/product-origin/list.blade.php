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
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
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
    .dropdown:hover .dropbtn{
        background-color: #3e8e41;
    }
</style>


<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-primary">
        <thead>
            <tr>
                <th>#</th>
                <th>Mã</th>
                <th>Quốc gia</th>
                <th>{{__('Ngày tạo')}}</th>
                <th>{{__('Trạng thái')}}</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
        @foreach ($LIST as $key=> $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item['product_origin_code']}}</td>
                <td>{{$item['product_origin_name']}}</td>
                <td>{{$item['created_at']}}</td>
                <td>
                    @if ($item['is_active'])
                        <span class="m-badge  m-badge--success m-badge--wide">Đang hoạt động</span>
                    @else
                        <span class="m-badge  m-badge--danger m-badge--wide">Tạm ngưng</span>
                    @endif
                </td>
                <td>
                    <div class="dropdown">
                        <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill dropbtn"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                        <div class="dropdown-content">
                            @if ($item['is_active'])
                                <a style="color: #ff4033" href='javascript:void (0)' onclick="productOrigin.changeStatus(this,'{!! $item['product_origin_id'] !!}', 'publish')"><i class="fa fa-circle-o"></i> Tạm ngưng </a>
                            @else
                                <a style="color: #1ab315" href='javascript:void (0)' onclick="productOrigin.changeStatus(this,'{!! $item['product_origin_id'] !!}', 'unPublish')"><i class="fa fa-circle-o"></i> Kính hoạt</a>
                            @endif
                        </div>
                    </div>



                    <a href="{{route('admin.product-origin.edit',array('product_origin_id'=>$item['product_origin_id']))}}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                       title="View">
                        <i class="la la-edit"></i>
                    </a>
                    <button onclick="productOrigin.remove(this, {{$item['product_origin_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
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