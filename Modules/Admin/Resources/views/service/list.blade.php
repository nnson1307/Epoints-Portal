<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            {{--<th>Mã</th>--}}
            {{--<th class="tr_thead_list"></th>--}}
            <th class="tr_thead_list">{{__('Dịch vụ')}}</th>
            <th class="tr_thead_list">{{__('Nhóm')}}</th>
            <th class="tr_thead_list">{{__('Giá')}}</th>
            <th class="tr_thead_list">{{__('Người tạo')}}</th>
            <th class="tr_thead_list">{{__('Người cập nhật')}}</th>
            <th class="tr_thead_list">{{__('Thời gian tạo')}}</th>
            <th class="tr_thead_list">{{__('Thời gian cập nhật')}}</th>
            {{-- <th class="tr_thead_list">{{__('Thời gian')}}</th> --}}
{{--            <th class="tr_thead_list">{{__('Tình trạng')}}</th>--}}
            <th class="tr_thead_list"></th>
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
                    <td>
                        @if($item['number']>0)
                            <i class="la la-check"></i>
                        @endif
                        <a class="m-link" style="color:#464646" title="{{__('Xem chi tiết')}}"
                           href='{{route("admin.service.detail",$item['service_id'])}}'>
                            {{$item['service_name']}}
                        </a>

                    </td>
                    <td>{{$item['name']}}</td>
                    <td>{{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{$item['create_full_name']}}</td>
                    <td>{{$item['update_full_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if(in_array('admin.service.edit',session('routeList')))
                            <a href="{{route('admin.service.edit',array ('id'=>$item['service_id']))}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Cập nhật')}}">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('admin.service.remove',session('routeList')))
                            <button onclick="service.remove(this, {{$item['service_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}">
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
