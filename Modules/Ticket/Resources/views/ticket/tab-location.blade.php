{{--<div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" style="height: 300px; overflow: hidden;">--}}
    <div class="m-widget3">
        @if (count($dataLocation) > 0)
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
                                            <div id="map-{{$v['ticket_location_id']}}" style="width: 150px; height: 100px;"></div>
                                                {{\Carbon\Carbon::parse($v['created_at'])->format('d/m/Y H:i')}}
                                        </span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
{{--</div>--}}