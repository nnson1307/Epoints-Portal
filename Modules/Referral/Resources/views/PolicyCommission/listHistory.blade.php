@if(count($log)!=0)
<div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="main-timeline w-100">
                @foreach($log as $k => $v)
                <div class="timeline">
                    <div class="timeline-icon"></div>
                    <div class="timeline-content">
                        <span class="date">{{$v['day']}}</span>
                        Lúc {{$v['hour']}}<br>
                        - <strong>{{$v['staff_name']}}</strong> {{$v['content']}}<br>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="not_find">
    <i class="la la-search-plus"> </i>
    <span>Chưa có dữ liệu</span>
</div>
@endif