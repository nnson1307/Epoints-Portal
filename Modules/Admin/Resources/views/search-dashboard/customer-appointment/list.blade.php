<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead class="ss--font-size-th">
        <tr class="ss--uppercase ss--nowrap">
            <th>#</th>
            <th>{{__('MÃ LỊCH HẸN')}}</th>
            <th class="ss--text-center">{{__('KHÁCH HÀNG')}}</th>
            <th>{{__('DỊCH VỤ')}}</th>
            <th class="ss--text-center">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center">{{__('THỜI GIAN HẸN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($listCustomerAppointment))
            @foreach ($listCustomerAppointment as $key => $item)
                <tr class="ss--font-size-13">
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td class="">{{$item['customer_appointment_code']}}</td>
                    <td class="ss--text-center">{{$item['full_name']}}</td>
                    <td class="">{{$item['service']}}</td>
                    <td class="ss--text-center">
                        @if($item['status']=='new')
                            <span class="m-badge m-badge--info m-badge--wide">{{__('Mới')}}</span>
                        @elseif($item['status']=='confirm')
                            <span class="m-badge m-badge--success m-badge--wide">{{__('Đã xác nhận')}}</span>
                        @elseif($item['status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--wide">{{__('Đã hủy')}}</span>
                        @elseif($item['status']=='finish')
                            <span class="m-badge m-badge--primary m-badge--wide">{{__('Đã hoàn thành')}}</span>
                        @elseif($item['status']=='wait')
                            <span class="m-badge m-badge--warning m-badge--wide">{{__('Chờ phục vụ')}}</span>
                        @endif
                    </td>
                    <td class="ss--text-center">{{$item['dateTime']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if(count($dataCustomerAppointment)>10)
                @if($page>1)
                    <li><a onclick="Paginate.pageClickCustomerAppointment(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="Paginate.pageClickCustomerAppointment({{$page-1}})" title="Previous"
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
                if (is_int(count($dataCustomerAppointment) / 10) == true) {
                    $totalPage = (count($dataCustomerAppointment) / 10) + 1;
                } else {
                    $totalPage = (int)(count($dataCustomerAppointment) / 10) + 2;
                }
                ?>
                @for ($i=1;$i<$totalPage;$i++)
                    @if($i==$page)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                               onclick="Paginate.pageClickCustomerAppointment({{$i}})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="Paginate.pageClickCustomerAppointment({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                @if($page<$totalPage-1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="Paginate.pageClickCustomerAppointment({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="Paginate.pageClickCustomerAppointment({{$totalPage-1}})"
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
                @if(count($dataCustomerAppointment)>0)
                    {{__('Hiển thị')}} {{($page-1)*10+1}}
                    - {{($page-1)*10 + count($listCustomerAppointment)}}
                    {{__('của')}} {{ count($dataCustomerAppointment) }}
                @else
                    {{__('Hiển thị 0 - 0 của 0')}}
                @endif
            </span>
        </div>
    </div>
</div>
