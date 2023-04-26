<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="img_sub"></th>
            <th class="tr_thead_list">{{__('Mã khách hàng')}}</th>
            <th class="tr_thead_list">{{__('Mã hồ sơ')}}</th>
            <th class="tr_thead_list">{{__('Chi nhánh')}}</th>
            <th class="tr_thead_list">{{__('Số điện thoại')}}</th>
            <th class="tr_thead_list">{{__('Người tạo')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th class="tr_thead_list">{{__('Ngày tạo')}}</th>
            <th class="tr_thead_list">{{__('Ngày cập nhật')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $color = ["success", "brand", "danger", "accent", "warning", "metal", "primary", "info"];
        ?>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                @php($num = rand(0,7))

                <tr>
                    @if(isset($page))
                        <td class="text_middle">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="text_middle">{{$key+1}}</td>
                    @endif
                    <td>
                        <div class="m-list-pics m-list-pics--sm">
                            @if($item['customer_avatar']!=null)
                                <div class="m-card-user m-card-user--sm">
                                    <div class="m-card-user__pic">
                                        <img src="{{$item['customer_avatar']}}"
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
                    <td class="text_middle">{{$item['customer_code']}}</td>
                    <td class="text_middle">{{$item['profile_code']}}</td>
                    <td class="text_middle">{{$item['branch_name']}}</td>
                    <td class="text_middle">{{$item['phone1']}}</td>
                    <td class="text_middle">{{$item['staff_name']}}</td>
                    <td>
                        @if(in_array('admin.customer.change-status',session('routeList')))
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="customer.changeStatus(this, '{!! $item['customer_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="customer.changeStatus(this, '{!! $item['customer_id'] !!}', 'unPublish')"
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="" disabled>
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           class="manager-btn" disabled>
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td class="text_middle">{{$item['created_at'] != null ? \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i') : ''}}</td>
                    <td class="text_middle">{{$item['updated_at'] != null ? \Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y H:i'): ''}}</td>
                    <td class="text_middle">
                        @if($item['customer_id']!=1)
                            @if(in_array('admin.customer.submitAcitve',session('routeList')))
                                <a class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   href="javascript:void(0)" onclick="customer.active({{$item['customer_id']}})">
                                    <i class="la la-adn"></i>
                                </a>
                            @endif
                            @if(in_array('admin.customer.edit',session('routeList')))
                                <a href="{{route('admin.customer.edit',array ('id'=>$item['customer_id']))}}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Cập nhật')}}">
                                    <i class="la la-edit"></i>
                                </a>
                            @endif
                            @if(in_array('admin.customer.remove',session('routeList')))
                                <button onclick="customer.remove(this, {{$item['customer_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Xoá')}}">
                                    <i class="la la-trash"></i>
                                </button>
                            @endif

                            <a class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               href="javascript:void(0)" onclick="customer.showModalBranch({{$item['customer_id']}})">
                                <i class="la la-bank"></i>
                            </a>

                            @if(in_array('admin.customer.customer-log', session('routeList')))
                                <a href="{{route('admin.customer.customer-log') . '?id=' .$item['customer_id']}}"
                                   target="_blank"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Lịch sử thay đổi')">
                                    <i class="fa fa-history"></i>
                                </a>
                            @endif

                            @if(in_array('admin.customer.customer-care', session('routeList')))
                                <a class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   href="javascript:void(0)"
                                   onclick="layout.getModalFromIcon('{{Auth()->id()}}', '', '{{$item['customer_id']}}', 'customer', '{{$item['phone1']}}', '{{session()->get('brand_code')}}')">
                                    <i class="la la-gratipay"></i>
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
{{ $LIST->links('helpers.paging') }}

