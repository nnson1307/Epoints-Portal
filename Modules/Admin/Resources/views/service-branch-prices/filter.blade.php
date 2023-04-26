<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table" id="tb-branch-price">

        <thead>
        <tr class="ss--font-size-th ss--nowrap">
            <th>#</th>
            <th>{{__('DỊCH VỤ')}}</th>
            <th class="ss--text-center">{{__('NHÓM')}}</th>
            <th class="ss--text-center">{{__('GIÁ CHUẨN')}}</th>
            @foreach ($BRANCH as $key => $value)
                <th class="ss--text-center">{{ $value }}</th>
            @endforeach
            <th></th>
        </tr>
        </thead>
        <tbody>

        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr class="ss--font-size-13">
                    <td>{{$key+1}}</td>
                    <td>{{$item[0]['service_name']}}</td>
                    <td class="ss--text-center">{{$item[0]['service_category_name']}}</td>
                    <td class="ss--text-center ss--nowrap">{{ number_format($item[0]['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) }}</td>
                    @foreach ($item[1] as $v)
                        <td class="ss--text-center ss--nowrap">{{ ($v == 0) ? 'Không có' : number_format($v, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) }}</td>
                    @endforeach
                    <td>
                        @if(in_array('admin.service-branch-price.edit',session('routeList')))
                            <a href="{{route('admin.service-branch-price.edit',array ('id'=>$item[0]['service_id']))}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="View">
                                <i class="la la-edit"></i>
                            </a>
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
        @if(count($data)>10)
            <ul class="m-datatable__pager-nav" style="float: right">
                @if($page>1)
                    <li><a onclick="pageClickFilter(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="pageClickFilter({{$page-1}})" title="Previous"
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
                               onclick="pageClickFilter({{$i}})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="pageClickFilter({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                @if($page<$totalPage-1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="pageClickFilter({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="pageClickFilter({{(int)(count($data)/10)+1}})"
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
        @endif
        <div class="m-datatable__pager-info" style="float: left">
            <span class="m-datatable__pager-detail">
                Hiển thị {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}} của {{ count($data) }}
            </span>
        </div>
    </div>
</div>