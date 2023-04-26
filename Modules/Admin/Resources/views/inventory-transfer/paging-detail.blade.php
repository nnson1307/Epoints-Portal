<div class="table-responsive">
    <table id="add-product-version"
           class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--font-size-th ss--nowrap">
            <th>#</th>
            <th>SẢN PHẨM</th>
            <th class="ss--text-center">ĐƠN VỊ TÍNH</th>
            <th class="ss--text-center">GIÁ NHẬP</th>
            <th class="ss--text-center">SỐ LƯỢNG</th>
            <th class="ss--text-center">{{__('TỔNG TIỀN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $key=>$value)
                <tr class="ss--font-size-13">
                    <td>{{($key+1)}}</td>
                    <td style="width: 300px">{{ $value['productName'] }}
                    </td>
                    <td class="ss--text-center">
                        {{--<div class="input-group m-input-group m-input-group--air">--}}
                        {{--<input style="text-align: center" readonly type="text"--}}
                        {{--class="form-control"--}}
                        {{--value="{{ $value['unitName'] }}">--}}
                        {{--</div>--}}
                        {{ $value['unitName'] }}
                    </td>
                    <td class="ss--text-center">
                        {{--<div class="input-group m-input-group m-input-group--air">--}}
                        {{--<input style="text-align: right" type="text" readonly--}}
                        {{--class="form-control"--}}
                        {{--value="{{number_format($value['currentPrice'],0,",",",")}}">--}}
                        {{--</div>--}}
                        {{number_format($value['currentPrice'],0,",",",")}}
                    </td>
                    <td class="ss--text-center">
                        {{--<div class="input-group m-input-group m-input-group--air">--}}
                        {{--<input style="text-align: center" type="text" readonly--}}
                        {{--class="form-control"--}}
                        {{--value="{{number_format($value['quantity'],0,",",".")}}">--}}
                        {{--</div>--}}
                        {{number_format($value['quantity'],0,",",".")}}
                    </td>
                    <td class="ss--text-center">
                        {{--<div class="input-group m-input-group m-input-group--air">--}}
                        {{--<input style="text-align: right" type="text" readonly--}}
                        {{--class="form-control"--}}
                        {{--value="{{number_format($value['total'],0,",",",")}}">--}}
                        {{--</div>--}}
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
                    <li><a onclick="InventoryTransfer.pageClick(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first"
                           data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="InventoryTransfer.pageClick({{$page-1}})" title="Previous"
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
                               onclick="InventoryTransfer.pageClick({{ $i }})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link"
                               onclick="InventoryTransfer.pageClick({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                {{-- Next Page Link --}}
                @if($page<(int)(count($LIST)/10)+1)
                    <li><a title="Next" class="m-datatable__pager-link"
                           onclick="InventoryTransfer.pageClick({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="InventoryTransfer.pageClick({{$totalPage-1}})"
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