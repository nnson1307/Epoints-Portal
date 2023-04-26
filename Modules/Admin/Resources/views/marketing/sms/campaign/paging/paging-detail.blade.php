<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead class="bg">
        <tr class="ss--font-size-13 ss--nowrap">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN KHÁCH HÀNG')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('SỐ ĐIỆN THOẠI')}}</th>
            <th class="ss--font-size-th">{{__('NỘI DUNG TIN NHẮN')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGƯỜI TẠO')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGƯỜI GỬI')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGÀY TẠO')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGÀY GỬI')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('TRẠNG THÁI')}}</th>
        </tr>
        </thead>
        <tbody class="ss--font-size-13">
        @if(isset($LIST))
            @foreach($LIST as $key=>$value)
                <tr>
                    <td class="ss--text-center">{{$key+1}}</td>
                    <td class="ss--nowrap">{{$value['customer']}}</td>
                    <td class="ss--text-center ss--nowrap">{{$value['phone']}}</td>
                    <td>{{$value['message']}}</td>
                    <td class="ss--text-center ss--nowrap">{{$value['created_by']}}</td>
                    <td class="ss--text-center ss--nowrap">{{$value['sent_by']}}</td>
                    <td class="ss--text-center ss--nowrap">{{(new DateTime($value['created_at']))->format('d/m/Y')}}</td>
                    <td class="ss--text-center ss--nowrap">
                        @if($value['time_sent_done']!=null)
                            {{(new DateTime($value['time_sent_done']))->format('d/m/Y')}}
                        @endif
                    </td>
                    <td class="ss--text-center ss--nowrap">
                        @if($value['sms_status']=='sent')
                            @if($value['error_code']==null)
                                <span class="">{{__('Thành công')}}</span>
                            @else
                                <span class="">{{__('Lỗi')}}</span>
                            @endif
                        @elseif($value['sms_status']=='cancel')
                            <span class="">{{__('Đã hủy')}}</span>
                        @elseif($value['sms_status']=='new')
                            <span class="">{{__('Chưa gửi')}}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if($page>1)
                <li><a onclick="SmsCampaign.pageClick(1)" title="First"
                       class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                class="la la-angle-double-left">
                        </i></a></li>
                <li><a onclick="SmsCampaign.pageClick({{$page-1}})" title="Previous"
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
            if (is_int(count($data) / 10) == true) {
                $totalPage = (count($data) / 10) + 1;
            } else {
                $totalPage = (int)(count($data) / 10) + 2;
            }
            ?>
            @for ($i=1;$i<$totalPage;$i++)
                @if($i==$page)
                    <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                           onclick="SmsCampaign.pageClick({{$i}})"
                           title="1">{{ $i }}</a></li>
                @else
                    <li><a class="m-datatable__pager-link" onclick="SmsCampaign.pageClick({{$i}})">{{ $i }}</a></li>
                @endif
            @endfor
            {{-- Next Page Link --}}
        <!--                --><?php //dd($page,$totalPage)?>
            @if($page<$totalPage-1)
                <li><a title="Next" class="m-datatable__pager-link"
                       onclick="SmsCampaign.pageClick({{$page+1}})"
                       data-page=""><i class="la la-angle-right"></i></a></li>
                <li><a title="Last" onclick="SmsCampaign.pageClick({{(int)(count($data)/10)+1}})"
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
        </ul>
        <div class="m-datatable__pager-info" style="float: left">
                            <span class="m-datatable__pager-detail">
                                @if(count($data)>0)
                                    {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}} {{__('của')}} {{ count($data) }}
                                @else
                                    {{__('Hiển thị 0 - 0 của 0')}}
                                @endif
                            </span>
        </div>
    </div>

</div>