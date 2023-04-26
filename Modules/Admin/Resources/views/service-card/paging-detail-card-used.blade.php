<div class="table-responsive">
    <table id="add-product-version"
           class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr class="ss--font-size-th">
            <th>#</th>
            <th>{{__('MÃ THẺ DỊCH VỤ')}}</th>
            <th class="ss--text-center">{{__('KHÁCH HÀNG')}}</th>
            <th class="ss--text-center">{{__('NGÀY SỬ DỤNG')}}</th>
            <th class="ss--text-center">{{__('TRẠNG THÁI')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayCardUsedPaginate as $key=>$value)
            <tr class="ss--font-size-13">
                <td>{{($key+1)}}</td>
                <td>{{ $value['card_code'] }}</td>
                <td class="ss--text-center">{{ $value['full_name'] }}</td>
                <td class="ss--text-center">{{date_format(new DateTime($value['day_use'] ), 'd/m/Y')}}</td>
                <td class="ss--text-center">
                    @if(strtotime(date("Y-m-d")) > strtotime(date_format(new DateTime($value['expired_date']), 'Y-m-d')) )
                        <b class="m--font-danger">{{__('Hết hạn')}}</b>
                    @else
                        <b class="m--font-success">{{__('Đang sử dụng')}}</b>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="row ss--m--margin-top--20 m--margin-bottom-20">
    <div class="m-datatable m-datatable--default col-lg-12">
        <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
            <ul class="m-datatable__pager-nav" style="float: right">
                @if($page>1)
                    <li><a onclick="CardUsed.firstAndLastPage(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="CardUsed.firstAndLastPage({{$page-1}})" title="Previous"
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
                @for ($i=1;$i<(int)(count($listServiceCardUsed)/10)+2;$i++)
                    @if($i==$page)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active" onclick="pageClick(this)"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="CardUsed.pageClick(this)">{{ $i }}</a></li>
                    @endif
                @endfor
                {{-- Next Page Link --}}
                @if($page<(int)(count($listServiceCardUsed)/10)+1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="CardUsed.firstAndLastPage({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="CardUsed.firstAndLastPage({{(int)(count($listServiceCardUsed)/10)+1}})"
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
                    @if(count($listServiceCardUsed)>0)
                        {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($arrayCardUsedPaginate)}}
                        {{__('của')}} {{ count($listServiceCardUsed) }}
                    @else
                        {{__('Hiển thị 0 - 0 của 0')}}
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>