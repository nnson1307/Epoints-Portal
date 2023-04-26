<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead class="ss--font-size-th">
        <tr class="ss--uppercase ss--nowrap">
            <th>#</th>
            <th>{{__('MÃ ĐƠN HÀNG')}}</th>
            <th class="ss--text-center">{{__('KHÁCH HÀNG')}}</th>
            <th>{{__('NGƯỜI TẠO')}}</th>
            <th class="ss--text-right">{{__('TỔNG TIỀN')}}</th>
            <th class="ss--text-center">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center">{{__('NGÀY TẠO')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($listOrder))
            @foreach ($listOrder as $key => $item)
                <tr class="ss--font-size-13">
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td class="">
                        <a class="m-link" style="color:#464646" title="Chi tiết" href="{{route('admin.order.detail',$item['order_id'])}}">
                            {{$item['order_code']}}
                        </a>
                    </td>
                    <td class="ss--text-center">{{$item['full_name']}}</td>
                    <td class="">{{$item['staff_name']}}</td>
                    <td class="ss--text-right">
                        {{number_format($item['total'],0,"",",")}} đ
                    <td class="ss--text-center">
                        @if($item['process_status']=='new')
                            <span style="color: #ffffff" class="m-badge m-badge--warning m-badge--wide">
                                {{__('Chưa thanh toán')}}
                            </span>
                        @elseif($item['process_status']=='paysuccess')
                            <span class="m-badge m-badge--success m-badge--wide">
                                {{__('Đã thanh toán')}}
                            </span>
                        @elseif($item['process_status']=='payfail')
                            <span class="m-badge m-badge--danger m-badge--wide">
                                {{__('Hủy')}}
                            </span>
                        @elseif($item['process_status']=='delivering')
                            <span class="m-badge m-badge--metal m-badge--wide">
                                {{__('Đang giao')}}
                            </span>
                        @endif
                    </td>
                    <td class="ss--text-center">{{date("d/m/Y",strtotime($item['created_at']))}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if(count($dataOrder)>10)
                @if($page>1)
                    <li><a onclick="Paginate.pageClickOrder(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="Paginate.pageClickOrder({{$page-1}})" title="Previous"
                           class="m-datatable__pager-link m-datatable__pager-link--prev"><i
                                    class="la la-angle-left"></i></a></li>
                @else
                    <li><a title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                           disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                    <li><a title="Previous"
                           class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled"
                           disabled="disabled"><i class="la la-angle-left"></i></a></li>
                @endif
                <?php
                $totalPage = 0;
                if (is_int(count($dataOrder) / 10) == true) {
                    $totalPage = (count($dataOrder) / 10) + 1;
                } else {
                    $totalPage = (int)(count($dataOrder) / 10) + 2;
                }
                ?>
                @for ($i=1;$i<$totalPage;$i++)
                    @if($i==$page)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                               onclick="Paginate.pageClickOrder({{$i}})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="Paginate.pageClickOrder({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                @if($page<$totalPage-1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="Paginate.pageClickOrder({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="Paginate.pageClickOrder({{$totalPage-1}})"
                           class="m-datatable__pager-link m-datatable__pager-link--last"
                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                @else
                    <li><a title="Next" class="m-datatable__pager-link m-datatable__pager-link--disabled"
                           disabled="disabled"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last"
                           class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                           disabled="disabled"
                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                @endif
            @endif
        </ul>
        <div class="m-datatable__pager-info" style="float: left">
            <span class="m-datatable__pager-detail">
                @if(count($dataOrder)>0)
                    {{__('Hiển thị')}} {{($page-1)*10+1}}
                    - {{($page-1)*10 + count($listOrder)}}
                    {{__('của')}} {{ count($dataOrder) }}
                @else
                    {{__('Hiển thị 0 - 0 của 0')}}
                @endif
            </span>
        </div>
    </div>
</div>