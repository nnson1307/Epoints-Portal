@if(count($listHistory) != 0)
    <div class="container">
        <div class="row">
            <div class="main-timeline w-100">
                @foreach($listHistory as $key => $item)
                    <div class="timeline">
                        <div class="timeline-icon"></div>
                        <div class="timeline-content">
                            <span class="date">{{$key == \Carbon\Carbon::now()->format('d/m/Y') ? __('Hôm nay') : $key}}</span>
                            {{--                                    <h5 class="title">Web Desginer</h5>--}}
{{--                            <p class="description">--}}
{{--                                --}}
{{--                            </p>--}}
                            @foreach($item as $itemValue)
                                {{__('Lúc')}} {{\Carbon\Carbon::parse($itemValue['created_at'])->format('H:i')}}<br>
                                {!!' - <strong>'.$itemValue['staff_name'].'</strong> '.$itemValue['message'] !!}<br>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="h-50">
        <h5 style="height: 300px" class="d-flex align-items-center text-center justify-content-center">{{ __('managerwork::managerwork.no_data') }}</h5>
    </div>
@endif