<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--font-size-th">
            <th>#</th>
            <th class="ss--text-center">{{__('PHIẾU')}}</th>
            <th>{{__('MÃ PHIẾU')}}</th>
            <th class="ss--text-center">{{__('KHO')}}</th>
            <th class="ss--text-center">LOẠI</th>
            <th class="ss--text-center" style="white-space:nowrap">{{__('SỐ LƯỢNG')}}</th>
            <th class="ss--text-center" style="white-space:nowrap">{{__('NGƯỜI TẠO')}}</th>
            <th class="ss--text-center">{{__('NGÀY TẠO')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($LIST as $key=>$value)
            <tr class="ss--font-size-13">
                <td>{{$key+1}}</td>
                <td class="ss--text-center">
                    @if($value['promissoryNote']=='input')
                        <b class="m--font-success ss--text-center">{{__('Phiếu nhập')}}</b>
                    @else
                        <b class="m--font-danger ss--text-center">{{__('Phiếu xuất')}}</b>
                    @endif
                </td>
                <td>{{$value['code']}}</td>
                <td class="ss--text-center">{{$value['warehouse']}}</td>
                <td class="ss--text-center">
                    @if($value['type']=='normal')
                        <span class="">{{__('Thường')}}</span>
                    @elseif($value['type']=='transfer')
                        <span class="">{{__('Chuyển kho')}}</span>
                    @elseif($value['type']=='checking')
                        <span class="">{{__('Kiểm kho')}}</span>
                    @elseif($value['type']=='return')
                        <span class="">{{__('Hủy')}}</span>
                    @elseif($value['type']=='retail')
                        <span class="">{{__('Bán lẻ')}}</span>
                    @endif
                </td>
                <td class="ss--text-center">{{$value['quantity']}}</td>
                <td class="ss--text-center">{{$value['user']}}</td>
                <td class="ss--text-center">{{(new DateTime($value['createdAt']))->format('d/m/Y')}}</td>
            </tr>
        @endforeach
    </table>
</div>
<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        @if((int)(count($data))>6)
            <ul class="m-datatable__pager-nav" style="float: right">
                @if($page>1)
                    <li><a onclick="History.pageClick(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first"
                           data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="History.pageClick({{$page-1}})" title="Previous"
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
                if (is_int(count($data) / 6) == true) {
                    $totalPage = (count($data) / 6) + 1;
                } else {
                    $totalPage = (int)(count($data) / 6) + 2;
                }
                ?>
                @for ($i=1;$i<$totalPage;$i++)
                    @if($i==$page)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                               onclick="History.pageClick({{ $i }})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link"
                               onclick="History.pageClick({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                {{-- Next Page Link --}}
                @if($page<$totalPage-1)
                    <li><a title="Next" class="m-datatable__pager-link"
                           onclick="History.pageClick({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="History.pageClick({{$totalPage-1}})"
                           class="m-datatable__pager-link m-datatable__pager-link--last"
                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                @else
                    <li><a title="Next"
                           class="m-datatable__pager-link m-datatable__pager-link--disabled"
                           disabled="disabled"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last"
                           class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                           disabled="disabled"
                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                @endif
            </ul>
        @endif
        <div class="m-datatable__pager-info" style="float: left">
                                <span class="m-datatable__pager-detail">
                                    @if(count($LIST)>0)
                                        {{__('Hiển thị')}} {{($page-1)*6+1}} - {{($page-1)*6 + count($LIST)}}
                                        {{__('của')}} {{ count($data) }}
                                    @else
                                        {{__('Hiển thị 0 - 0 của 0')}}
                                    @endif
                                </span>
        </div>
    </div>
</div>