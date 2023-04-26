<div class="table-responsive" role="tabpanel">
    <table class="table m-table m-table--head-bg-primary tb-service-card-list" id="tb-service-card-list">
        <thead>
        <tr>
            <th>#</th>
            <th>{{__('Thẻ dịch vụ')}}</th>
            <th style="text-align: right">{{__('Giá thẻ')}}</th>
            <th style="text-align: center">{{__('Số lượng còn')}}</th>
            @foreach($BRANCH as $key=>$value)
                <th style="text-align: center">{{$value}}</th>
            @endforeach
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($LIST as $key=> $item)
            <tr>
                <td><label style="width: 20px"></label>{{$key+1}}</td>
                <td><label style="width: 150px"></label>
                    <a class="test m-link m--font-boldest"
                       href='{{route("admin.service-card-list.detail",$item["cardListId"])}}' >
                        {{$item["cardListName"]}}
                    </a>
                    @if($item['serviceCardType']=='money')
                        <i class="la la-money m--margin-left-5"></i>
                    @endif
                    @if($item['serviceCardType']=='service')
                        <i class="m-menu__link-icon flaticon-open-box m--margin-left-5"></i>
                    @endif
                </td>
                <td  style="text-align: right">
                    <label style="width: 100px"></label>
                    {{number_format((int)$item['price'],0,",",",")}}
                </td>
                <td class="product-name"  style="text-align: center">
                    <label style="width: 90px"></label>{{$item['total']}}
                </td>
                @foreach($item['branch'] as $k=> $i)
                    <td style="text-align: center"><label style="width: 200px"></label>{{$i}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded">
        <ul class="m-datatable__pager-nav">
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
                @if($i==$page)
                    <li><a class="m-datatable__pager-link m-datatable__pager-link--active" onclick="pageSearchClick(this)"
                           title="1">{{ $i }}</a></li>
                @else
                    <li><a class="m-datatable__pager-link" onclick="pageSearchClick(this)">{{ $i }}</a></li>
                @endif
            @endfor
            {{-- Next Page Link --}}
            @if($page<(int)(count($data)/10)+1)
                <li><a title="Next" class="m-datatable__pager-link" onclick="firstOrLastPageSearch({{$page+1}})"
                       data-page=""><i class="la la-angle-right"></i></a></li>
                <li><a title="Last" onclick="firstOrLastPageSearch({{(int)(count($data)/10)+1}})"
                       class="m-datatable__pager-link m-datatable__pager-link--last"
                       data-page=""><i class="la la-angle-double-right"></i></a></li>
            @else
                <li><a title="Next" class="m-datatable__pager-link m-datatable__pager-link--disabled" disabled="disabled"
                       data-page=""><i class="la la-angle-right"></i></a></li>
                <li><a title="Last"
                       class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled" disabled="disabled"
                       data-page=""><i class="la la-angle-double-right"></i></a></li>
            @endif
        </ul>
        <div class="m-datatable__pager-info">
            <span class="m-datatable__pager-detail">
                {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}} {{__('của')}} {{ count($data) }}
            </span>
        </div>
    </div>
</div>
