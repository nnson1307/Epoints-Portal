@if (count($dataCare) > 0)
    <h5>{{__('Lịch sử chăm sóc')}}</h5>
    <div style="width: 100%; height: 400px; overflow-y: scroll;">
        <div class="m-scrollable m-scroller ps ps--active-y w-100">
            @foreach($dataCare as $k => $v)
                <div class="m-timeline-2">
                    <div class="m-timeline-2__items  m--padding-top-25 m--padding-bottom-30">
                        <div class="m-timeline-2__item">
                            <span class="m-timeline-2__item-time">
                                {{\Carbon\Carbon::createFromFormat('d/m/Y', $k)->format('d/m')}}
                            </span>
                        </div>
                        @if (count($v) > 0)
                            @foreach($v as $v1)
                                <div class="m-timeline-2__item m--margin-top-30">
                                    <span class="m-timeline-2__item-time"></span>
                                    <div class="m-timeline-2__item-cricle">
                                        <i class="fa fa-genderless m--font-success"></i>
                                    </div>
                                    <div class="m-timeline-2__item-text">
                                        <strong>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v1['created_at'])->format('H:i')}}</strong>
                                        <br/>
                                        @lang('Người chăm sóc'): {{$v1['full_name']}} <br/>
                                        @lang('Loại công việc'): {{$v1['manage_type_work_name']}}
                                        <br/>
                                        @lang('Nội dung'): {!! $v1['content'] !!}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif