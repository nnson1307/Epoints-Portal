<div class="m-portlet m-portlet--head-sm tab_work_detail pb-5">
    <nav class="nav">
        <a class="hover-cursor nav-link "
           onclick="ChangeTab.tabComment('comment')">{{ __('managerwork::managerwork.comment') }}</a>
        <a class="hover-cursor nav-link"
           onclick="ChangeTab.tabComment('document')">{{ __('managerwork::managerwork.document') }}</a>
        <a class="hover-cursor nav-link"
           onclick="ChangeTab.tabComment('remind')">{{ __('managerwork::managerwork.remind') }}</a>
        @if($detail['parent_id'] == null)
            <a class="hover-cursor nav-link"
               onclick="ChangeTab.tabComment('sub_task')">{{ __('managerwork::managerwork.child_task') }}</a>
        @endif
        <a class="hover-cursor nav-link"
           onclick="ChangeTab.tabComment('history')">{{ __('managerwork::managerwork.history') }}</a>
        <a class="hover-cursor nav-link active" onclick="ChangeTab.tabComment('location')">@lang('Vị trí')</a>
    </nav>

    <div class="col-12 mt-3 ml-2">
        @if (count($dataLocation) > 0)
            <div class="m-widget3">
                @foreach($dataLocation as $v)
                    <div class="m-widget3__item">
                        <div class="m-widget3__header">
                            <div class="m-widget3__user-img">
                                <img class="m-widget3__img"
                                     src="{{$v['staff_avatar']}}"
                                     alt="">
                            </div>
                            <div class="m-widget3__info">
														<span class="m-widget3__username font-weight-bold">
															{{$v['staff_name']}}
														</span><br>
                                <span class="m-widget3__time">
                                            <div id="map-{{$v['manage_work_location_id']}}"
                                                 style="width: 150px; height: 100px;"></div>
                                                {{\Carbon\Carbon::parse($v['created_at'])->format('d/m/Y H:i')}}
                                        </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @lang('Không có dữ liệu')
        @endif
    </div>

</div>




