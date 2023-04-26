@if(isset($LIST_TIME))
    <div class="form-group text-center">
        <span>
            <?php
            $totalPage = 0;
            if (is_int(count($data_time) / 7) == true) {
                $totalPage = (count($data_time) / 7) + 1;
            } else {
                $totalPage = (int)(count($data_time) / 7) + 2;
            }
            ?>
            @if($page_time!=1)
                <button type="button" class="btn color-button btn-icon btn-circle" onclick="step_time.preAndNextPage({{(int)($page_time-1)}})">
                    <i class="fa fa-angle-left"></i>
                </button>
            @endif
                <strong>
                    {{$title_time[0].' - '.$title_time[count($title_time)-1]}}
                </strong>

                @if($page_time<$totalPage-1)
                <button type="button" class="btn color-button btn-icon btn-circle"
                        onclick="step_time.preAndNextPage({{(int)($page_time)+1}})"><i
                            class="fa fa-angle-right"></i></button>
            @else
                <button type="button" class="btn color-button btn-icon btn-circle" disabled><i
                            class="fa fa-angle-right"></i></button>
            @endif
        </span>
    </div>
    <div class="form-group kt-section__content">
        <div class="kt-scroll" data-scroll="true"
             style="height: 300px; overflow: auto;">
            <table class="table table-bordered" id="table-time">
                <thead class="thead-time">
                <tr>
                    @foreach($LIST_TIME as $v)
                        @php $name=''; @endphp
                        @if($v['name']=='Monday')
                            @php $name='Thứ hai'; @endphp
                        @elseif($v['name']=='Tuesday')
                            @php $name='Thứ ba'; @endphp
                        @elseif($v['name']=='Wednesday')
                            @php $name='Thứ tư'; @endphp
                        @elseif($v['name']=='Thursday')
                            @php $name='Thứ năm'; @endphp
                        @elseif($v['name']=='Friday')
                            @php $name='Thứ sáu'; @endphp
                        @elseif($v['name']=='Saturday')
                            @php $name='Thứ bảy'; @endphp
                        @elseif($v['name']=='Sunday')
                            @php $name='Chủ nhật'; @endphp
                        @endif
                        <th class="text-center">
                            <button type="button"
                                    class="btn btn-light btn-circle btn-icon">
                                {{date('d',strtotime($v['day']))}}
                            </button>
                            <p>{{$name}}</p>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr class="tr-table">
                    @foreach($LIST_TIME as $value)
                        @if($value['name']=='Monday')
                            <td class="text-center">
                                @foreach($value['time'] as $time)
                                    @if($value['is_actived'])
                                        <div class="div_time">
                                            @if(date('Y-m-d H:i')<$value['day'].' '.$time)
                                                <button type="button"
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="div_time">
                                            <button type="button" disabled
                                                    class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                    value="{{$time}}" data-date="{{$value['day']}}"
                                                    onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                {{$time}}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach

                            </td>
                        @elseif($value['name']=='Tuesday')
                            <td class="text-center">
                                @foreach($value['time'] as $time)
                                    @if($value['is_actived'])
                                        <div class="div_time">
                                            @if(date('Y-m-d H:i')<$value['day'].' '.$time)
                                                <button type="button"
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="div_time">
                                            <button type="button" disabled
                                                    class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                    value="{{$time}}" data-date="{{$value['day']}}"
                                                    onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                {{$time}}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        @elseif($value['name']=='Wednesday')
                            <td class="text-center">
                                @foreach($value['time'] as $time)
                                    @if($value['is_actived'])
                                        <div class="div_time">
                                            @if(date('Y-m-d H:i')<$value['day'].' '.$time)
                                                <button type="button"
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="div_time">
                                            <button type="button" disabled
                                                    class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                    value="{{$time}}" data-date="{{$value['day']}}"
                                                    onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                {{$time}}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        @elseif($value['name']=='Thursday')
                            <td class="text-center">
                                @foreach($value['time'] as $time)
                                    @if($value['is_actived'])
                                        <div class="div_time">
                                            @if(date('Y-m-d H:i')<$value['day'].' '.$time)
                                                <button type="button"
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="div_time">
                                            <button type="button" disabled
                                                    class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                    value="{{$time}}" data-date="{{$value['day']}}"
                                                    onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                {{$time}}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        @elseif($value['name']=='Friday')
                            <td class="text-center">
                                @foreach($value['time'] as $time)
                                    @if($value['is_actived'])
                                        <div class="div_time">
                                            @if(date('Y-m-d H:i')<$value['day'].' '.$time)
                                                <button type="button"
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="div_time">
                                            <button type="button" disabled
                                                    class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                    value="{{$time}}" data-date="{{$value['day']}}"
                                                    onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                {{$time}}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        @elseif($value['name']=='Saturday')
                            <td class="text-center">
                                @foreach($value['time'] as $time)
                                    @if($value['is_actived'])
                                        <div class="div_time">
                                            @if(date('Y-m-d H:i')<$value['day'].' '.$time)
                                                <button type="button"
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="div_time">
                                            <button type="button" disabled
                                                    class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                    value="{{$time}}" data-date="{{$value['day']}}"
                                                    onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                {{$time}}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        @elseif($value['name']=='Sunday')
                            <td class="text-center">
                                @foreach($value['time'] as $time)
                                    @if($value['is_actived'])
                                        <div class="div_time">
                                            @if(date('Y-m-d H:i')<$value['day'].' '.$time)
                                                <button type="button"
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                        value="{{$time}}" data-date="{{$value['day']}}"
                                                        onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                    {{$time}}
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="div_time">
                                            <button type="button" disabled
                                                    class="btn btn-light time btn-elevate btn-pill btn-sm"
                                                    value="{{$time}}" data-date="{{$value['day']}}"
                                                    onclick="step_time.click_time(this,'{{$value['day']}}')">
                                                {{$time}}
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        @endif
                    @endforeach
                </tr>

                </tbody>

            </table>
        </div>
    </div>

@endif