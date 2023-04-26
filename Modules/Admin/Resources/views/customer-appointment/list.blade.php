<div class="table-responsive list" style="display: none">
    {{--<form class="m-form m-form--fit m-form--label-align-right frmFilter">--}}
        <div class="input-group col-lg-6">
            <input type="text" class="form-control" name="search"
                   placeholder="Nhập nội dung tìm kiếm">
            <div class="input-group-append">
                <button class="btn btn-primary" id="search">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    {{--</form>--}}
    <br/>
    <table class="table table-striped m-table m-table--head-bg-primary table-list">
        <thead>
        <tr>
            <th>#</th>
            <th>{{__('Khách hàng')}}</th>
            <th>{{__('Số điện thoại')}}</th>
            <th>{{__('Giờ hẹn')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item['full_name_cus']}}</td>
                    <td>{{$item['phone1']}}</td>
                    <td>{{$item['time']}}</td>
                    {{--<td>--}}

                    {{--<a class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
                    {{--href='{{route("admin.service.detail",$item['service_id'])}}' ><i class="la la-eye"></i>--}}
                    {{--</a>--}}
                    {{--<a href="{{route('admin.service.edit',array ('id'=>$item['service_id']))}}"--}}
                    {{--class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
                    {{--title="View">--}}
                    {{--<i class="la la-edit"></i>--}}
                    {{--</a>--}}

                    {{--<button onclick="service.remove(this, {{$item['service_id']}})"--}}
                    {{--class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"--}}
                    {{--title="Delete">--}}
                    {{--<i class="la la-trash"></i>--}}
                    {{--</button>--}}
                    {{--</td>--}}
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
    {{ $LIST->links('helpers.paging') }}
</div>

