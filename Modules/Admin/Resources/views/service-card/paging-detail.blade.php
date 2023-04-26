<div class="table-responsive">
    <table id="add-product-version"
           class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr class="ss--font-size-th">
            <th>#</th>
            <th>{{__('MÃ THẺ DỊCH VỤ')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('KHÁCH HÀNG')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('CHI NHÁNH')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGÀY BÁN')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrayServiceCardPaginate as $key=>$value)
            <tr class="ss--font-size-13">
                <td>{{($key+1)}}</td>
                <td>
                    @if($value['isActived']==1)
                        {{ $value['code'] }}
                    @else
                        ****************
                    @endif
                </td>
                <td class="ss--text-center">{{ $value['customer'] }}</td>
                <td class="ss--text-center">{{ $value['branch'] }}</td>
                <td class="ss--text-center">{{ $value['createdAt'] }}</td>
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
                    <li><a onclick="firstAndLastPage(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="firstAndLastPage({{$page-1}})" title="Previous"
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
                if (is_int(count($arrayAllServiceCard) / 10) == true) {
                    $totalPage = (count($arrayAllServiceCard) / 10) + 1;
                } else {
                    $totalPage = (int)(count($arrayAllServiceCard) / 10) + 2;
                }
                ?>
                @for ($i=1;$i<$totalPage;$i++)
                    @if($i==$page)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active" onclick="pageClick(this)"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="pageClick(this)">{{ $i }}</a></li>
                    @endif
                @endfor
                {{-- Next Page Link --}}
                @if($page<(int)(count($arrayAllServiceCard)/10)+1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="firstAndLastPage({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="firstAndLastPage({{$totalPage-1}})"
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
                @if(count($arrayAllServiceCard)>0)
                    {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($arrayServiceCardPaginate)}}
                    {{__('của')}} {{ count($arrayAllServiceCard) }}
                @else
                    {{__('Hiển thị 0 - 0 của 0')}}
                @endif
            </span>
            </div>
        </div>
    </div>
</div>