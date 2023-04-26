<div class="table-responsive">
    <table id="add-product-version"
           class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--uppercase ss--font-size-th ss--nowrap">
            <th>#</th>
            <th>{{__('Sản phẩm')}}</th>
            <th class="ss--text-center">{{__('Đơn vị tính')}}</th>
            <th></th>
            {{--<th class="ss--text-center">Giá bán</th>--}}
            <th class="ss--text-center">{{__('Giá nhập')}}</th>
            <th></th>
            <th class="ss--text-center">{{__('Số lượng')}}</th>
            <th></th>
            <th class="ss--text-center">{{__('Tổng tiền')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $key=>$value)
                <tr class="ss--font-size-13 ss--nowrap">
                    <td>{{($key+1)}}</td>
                    <td>{{ $value['childName'] }}
                    </td>
                    <td class="ss--text-center">
                        {{ $value['unitName'] }}
                    </td>
                    <td></td>
                    <td class="ss--text-center">
                        {{number_format($value['currentPrice'],0,",",",")}}
                    </td>
                    <td></td>
                    <td class="ss--text-center">
                        {{number_format($value['quantity'],0,",",".")}}
                    </td>
                    <td></td>
                    <td class="ss--text-center">
                        {{number_format($value['total'],0,",",",")}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        @if((int)(count($data))>10)
            <ul class="m-datatable__pager-nav" style="float: right">
                @if($page>1)
                    <li><a onclick="InventoryOut.pageClick(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first"
                           data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="InventoryOut.pageClick({{$page-1}})" title="Previous"
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
                               onclick="InventoryOut.pageClick({{ $i }})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link"
                               onclick="InventoryOut.pageClick({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                {{-- Next Page Link --}}
                @if($page<(int)(count($LIST)/10)+1)
                    <li><a title="Next" class="m-datatable__pager-link"
                           onclick="InventoryOut.pageClick({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="InventoryOut.pageClick({{$totalPage-1}})"
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
                                        {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}}
                                        {{__('của')}} {{ count($data) }}
                                    @else
                                        {{__('Hiển thị 0 - 0 của 0')}}
                                    @endif
                                </span>
        </div>
    </div>
</div>