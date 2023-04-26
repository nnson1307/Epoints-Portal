<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">

        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('TÊN CHIẾN DỊCH')}}</th>
            <th class="tr_thead_list text-center">{{__('NGƯỜI TẠO')}}</th>
            <th class="tr_thead_list text-center">{{__('NGƯỜI GỬI')}}</th>
            <th class="tr_thead_list text-center">{{__('NGÀY TẠO')}}</th>
            <th class="tr_thead_list text-center">{{__('NGÀY GỬI')}}</th>
            <th class="tr_thead_list text-center">{{__('TỔNG CỘNG')}}</th>
            <th class="tr_thead_list text-center">{{__('THÀNH CÔNG')}}</th>
            <th class="tr_thead_list text-center">{{__('TRẠNG THÁI')}}</th>
            <th class="tr_thead_list"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>
                        <a href="{{route('admin.email.detail',array ('id'=>$item['campaign_id']))}}"
                           class="m-link" style="color:#464646">{{$item['name']}}</a>
                    </td>
                    <td class="text-center">{{$item['created_by']}}</td>
                    <td class="text-center">{{$item['sent_by']}}</td>
                    <td class="text-center">{{(new DateTime($item['created_at']))->format('d/m/Y')}}</td>
                    <td class="text-center">
                        @if($item['time_sent']!='')
                            {{(new DateTime($item['time_sent']))->format('d/m/Y')}}
                        @endif
                    </td>
                    <td class="text-center">{{$item['total']}}</td>
                    <td class="text-center">{{$item['totalSuccess']}}</td>
                    @if($item['status']=='new')
                        <td style="color: #0a8cf0" class="text-center">{{__('Mới')}}</td>
                    @elseif($item['status']=='sent')
                        <td style="color: #008000" class="text-center">{{__('Hoàn thành')}}</td>
                    @else
                        <td style="color: #ff0000" class="text-center">{{__('Hủy')}}</td>
                    @endif

                    <td class="text-center">
                        @if(in_array('admin.email.edit',session('routeList')))
                            @if($item['status']=='new')
                                <a href="{{route('admin.email.edit',array ('id'=>$item['campaign_id']))}}"
                                   title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                    <i class="la la-edit"></i>
                                </a>
                                <a href="javascript:void(0)" onclick="email.cancel(this, '{{ $item['campaign_id']}}')"
                                   title="{{__('Hủy')}}" style="color: #a1a1a1"><i class="la la-trash"></i>
                                </a>
                            @endif
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
                <li><a onclick="firstAndLastPage(1)" title="First"
                       class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                class="la la-angle-double-left">
                        </i></a></li>
                <li><a onclick="firstAndLastPage({{$page-1}})" title="{{__('Previous')}}"
                       class="m-datatable__pager-link m-datatable__pager-link--prev"><i
                                class="la la-angle-left"></i></a></li>
            @else
                <li><a title="First"
                       class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                       disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                <li><a title="{{__('Previous')}}"
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
                @if($i==1)
                    <li><a class="m-datatable__pager-link m-datatable__pager-link--active" onclick="pageClick({{$i}})"
                           title="1">{{ $i }}</a></li>
                @else
                    <li><a class="m-datatable__pager-link" onclick="pageClick({{$i}})">{{ $i }}</a></li>
                @endif
            @endfor
            {{-- {{__('Next')}} Page Link --}}
        <!--                --><?php //dd($page,$totalPage)?>
            @if($page<$totalPage-1)
                <li><a title="{{__('Next')}}" class="m-datatable__pager-link" onclick="firstAndLastPage({{$page+1}})"
                       data-page=""><i class="la la-angle-right"></i></a></li>
                <li><a title="Last" onclick="firstAndLastPage({{(int)(count($LIST)/10)+1}})"
                       class="m-datatable__pager-link m-datatable__pager-link--last"
                       data-page=""><i class="la la-angle-double-right"></i></a></li>
            @else
                <li><a title="{{__('Next')}}" class="m-datatable__pager-link m-datatable__pager-link--disabled"
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
                {{__('Hiển thị')}} {{1}} - {{($page-1)*10 + count($LIST)}} {{__('của')}} {{ count($data) }}
            </span>
        </div>
    </div>

</div>
