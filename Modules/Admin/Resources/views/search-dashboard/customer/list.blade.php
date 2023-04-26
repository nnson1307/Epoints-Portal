<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead class="ss--font-size-th">
        <tr class="ss--uppercase ss--nowrap">
            <th>#</th>
            <th>{{__('Tên khách hàng')}}</th>
            <th>{{__('Chi nhánh')}}</th>
            <th class="ss--text-center">{{__('Số điện thoại')}}</th>
            <th class="ss--text-center">{{__('Ngày cập nhật')}}</th>
            <th class="ss--text-center">{{__('Email')}}</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $color = ["success", "brand", "danger", "accent", "warning", "metal", "primary", "info"];
        ?>
        @if(isset($listCustomer))
            @foreach ($listCustomer as $key => $item)
                @php($num = rand(0,7))
                @if($item['customer_id']!=1)
                    <tr class="ss--font-size-13">
                        @if(isset($page))
                            <td>{{ ($page-1)*10 + $key+1}}</td>
                        @else
                            <td>{{$key+1}}</td>
                        @endif
                        <td>
                            <div class="m-list-pics m-list-pics--sm">
                                @if($item['customer_avatar']!=null)
                                    <div class="m-card-user m-card-user--sm">
                                        <div class="m-card-user__pic">
                                            <img src="/{{$item['customer_avatar']}}"
                                                 onerror="this.onerror=null;this.src='https://placehold.it/40x40/00a65a/ffffff/&text=' + '{{substr(str_slug($item['full_name']),0,1)}}';"
                                                 class="m--img-rounded m--marginless" alt="photo" width="40px"
                                                 height="40px">
                                        </div>
                                        <div class="m-card-user__details">
                                            <a href="{{route("admin.customer.detail",$item['customer_id'])}}"
                                               class="m-card-user__name line-name font-name">{{$item['full_name']}}</a>
                                            <span class="m-card-user__email font-sub">
                                                {{$item['group_name']}}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span style="width: 150px;">
                                        <div class="m-card-user m-card-user--sm">
                                            <div class="m-card-user__pic">
                                                <div class="m-card-user__no-photo m--bg-fill-{{$color[$num]}}">
                                                    <span>
                                                        {{substr(str_slug($item['full_name']),0,1)}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="m-card-user__details">
                                                <a href="{{route("admin.customer.detail",$item['customer_id'])}}"
                                                   class="m-card-user__name line-name font-name">{{$item['full_name']}}</a>
                                                <span class="m-card-user__email font-sub">{{$item['group_name']}}</span>
                                            </div>
                                        </div>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>{{$item['branch_name']}}</td>
                        <td class="ss--text-center">{{$item['phone1']}}</td>
                        <td class="ss--text-center">{{date("d/m/Y",strtotime($item['updated_at']))}}</td>
                        <td class="ss--text-center">{{$item['email']}}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>

    </table>
</div>
<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if(count($dataCustomer)>10)
                @if($page>1)
                    <li><a onclick="Paginate.pageClickCustomer(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="Paginate.pageClickCustomer({{$page-1}})" title="Previous"
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
                if (is_int(count($dataCustomer) / 10) == true) {
                    $totalPage = (count($dataCustomer) / 10) + 1;
                } else {
                    $totalPage = (int)(count($dataCustomer) / 10) + 2;
                }
                ?>
                @for ($i=1;$i<$totalPage;$i++)
                    @if($i==$page)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                               onclick="Paginate.pageClickCustomer({{$i}})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="Paginate.pageClickCustomer({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                @if($page<$totalPage-1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="Paginate.pageClickCustomer({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="Paginate.pageClickCustomer({{$totalPage-1}})"
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
                @if(count($dataCustomer)>0)
                    {{__('Hiển thị')}} {{($page-1)*10+1}}
                    - {{($page-1)*10 + count($listCustomer)}}
                    {{__('của')}} {{ count($dataCustomer) }}
                @else
                    {{__('Hiển thị 0 - 0 của 0')}}
                @endif
            </span>
        </div>
    </div>
</div>