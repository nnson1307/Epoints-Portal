@if(count($listHistory) != 0)
<div class="row">
    <div class="main-timeline w-100">
        @foreach($listHistory as $key => $item)
            <div class="timeline">
                <div class="timeline-icon"></div>
                <div class="timeline-content-date">
                    <p><strong>{{$key}}</strong></p>
                </div>
                <div class="timeline-content">
                    @foreach($item as $keyItem => $itemChild)
                        <?php $tmp = explode('_',$keyItem) ?>
                        @if($tmp[1] == 'work')
                            <a href="{{route('manager-work.detail',['id' => $tmp[0]])}}">
                                <span class="date">[{{$item[$keyItem][0]['manage_work_code']}}] {{$item[$keyItem][0]['manage_work_title']}} </span>
                            </a>
                        @else
                            <a href="{{route('manager-project.project.project-info-overview',['id' => $tmp[0]])}}">
                                <span class="date">[{{$item[$keyItem][0]['prefix_code']}}] {{$item[$keyItem][0]['manage_project_name']}} </span>
                            </a>
                        @endif

                        @foreach($itemChild as $itemValue)
                            {{__('Lúc')}} {{\Carbon\Carbon::parse($itemValue['created_at'])->format('H:i')}}<br>
                            {!!' - <strong>'.$itemValue['staff_name'].'</strong> '.$itemValue['message'] !!}<br>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
    <div class="h-50">
        <h5 style="height: 300px" class="d-flex align-items-center text-center justify-content-center">{{ __('managerwork::managerwork.no_data') }}</h5>
    </div>
@endif
