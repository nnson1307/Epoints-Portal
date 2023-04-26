<div class="table-responsive" role="tabpanel">
    @if($result!=null)
        <table class="table m-table ss--header-table" id="tb-product-inventory">
            <thead>
            <tr class="ss--font-size-th" style="text-transform: uppercase;">
                <th>#</th>
                <th>{{__('MÃ SẢN PHẨM')}}</th>
                <th>{{__('TÊN SẢN PHẨM')}}</th>
                <th class="ss--font-size-th ss--text-center ss--nowrap">{{__('NGÀY TẠO')}}</th>
                @if(Auth::user()->is_admin==1)
                    <th class="ss--text-center">{{__('TẤT CẢ KHO')}}</th>
                @endif
                @foreach($wareHouse as $key=>$value)
                    <th class="ss--text-center">{{$value['name']}}</th>
                @endforeach
                {{--<th></th>--}}

            </tr>
            </thead>
            <tbody>
            @foreach($result as $key=> $item)
                <tr class="ss--font-size-13">
                    <td>{{$key+1}}</td>
                    <td class="product-code ss--font-size-13" style="width:130px;max-width: 150px">
                        {{$item['productCode']}}
                    </td>
                    <td class="product-name" style="max-width: 320px">
                        <a href="javascript:void (0)"
                           onclick="getHistory('{{ $item['productCode'] }}')"
                           data-toggle="modal" data-target="#history"
                           class="ss--text-black"> {{$item['productName']}}</a>
                    </td>
                    <td class="ss--font-size-13 ss--nowrap"
                        style="text-align: center">{{(new DateTime($item['createdAt']))->format('d/m/Y')}}</td>
                    @if(Auth::user()->is_admin==1)
                        <td style="text-align: center">
                            @if($item['productInventory']==null)
                                0
                            @else
                                {{$item['productInventory']}}
                            @endif
                        </td>
                    @endif
                    @foreach($item['warehouse'] as $k=> $i)
                        <td class="ss--text-center">{{$i}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        {{__('Không tìm thấy dữ liệu')}}
    @endif
</div>

<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if(count($data)>10)
                @if($page>1)
                    <li><a onclick="firstOrLastPageSearch(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="firstOrLastPageSearch({{$page-1}})" title="Previous"
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
                @for ($i=1;$i<(int)(count($data)/10)+2;$i++)
                    @if($i==1)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                               onclick="pageSearchClick(this)"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="pageSearchClick(this)">{{ $i }}</a></li>
                    @endif
                @endfor
                {{-- Next Page Link --}}
                @if($page<(int)(count($result)/10)+1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="firstOrLastPageSearch({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="firstOrLastPageSearch({{(int)(count($result)/10)+1}})"
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
{{--                Hiển thị {{count($result)/10}} - {{($page-1)*10 + count($result)}} của {{ count($data) }}--}}
                @if(count($data)>0)
                    {{__('Hiển thị')}} {{($page-1)*10+1}}
                    - {{($page-1)*10 + count($result)}}
                    {{__('của')}} {{ count($data) }}
                @else
                    {{__('Hiển thị 0 - 0 của 0')}}
                @endif
            </span>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#tb-product-inventory").tableHeadFixer({"head": false, "left": 4});
    });
</script>




