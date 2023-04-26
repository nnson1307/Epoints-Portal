<div class="form-group">
    <p class="text-content">{{__('Thông tin liên hệ')}}</p>
    @if(isset($info['hot_line']))
        <p><i class="la la-phone"></i>&nbsp;{{$info['hot_line']}}</p>
    @endif
    @if(isset($info['website']))
        <p><i class="la la-globe"></i>&nbsp;{{$info['website']}}</p>
    @endif
    @if(isset($info['email']))
        <p><i class="la la-envelope"></i>&nbsp;{{$info['email']}}</p>
    @endif
    @if(isset($info['fanpage']))
        <p><i class="la la-facebook-f"></i>&nbsp;
            <a target="_blank" href="{{$info['fanpage']}}">{{__('Facebook')}}</a>
        </p>
    @endif
    @if(isset($info['zalo']))
        <p><i class="la la-wechat"></i>&nbsp;{{$info['zalo']}}</p>
    @endif
    @if(isset($info['instagram_page']))
        <p><i class="la la-instagram"></i>&nbsp;{{$info['instagram_page']}}</p>
    @endif
</div>
<div class="form-group">
    <p class="text-content">{{__('Địa chỉ')}}</p>
    <p>{{$info['address'].', '.$info['district_name'].', '.$info['province_name']}}</p>
{{--    <div id="map" style="height:100px;"></div>--}}
</div>
<div class="form-group">
    <p class="text-content">{{__('Thời gian làm việc')}}</p>
    @if($setting[0]['is_actived'])
        @if(isset($time_working))
            @foreach($time_working as $item)
                @if($item['is_actived'])
                    <p>{{date('H:i',strtotime($item['start_time'])).' - '.date('H:i',strtotime($item['end_time'])).' '.'('.$item['vi_name'].')'}} </p>
                @else
                    <p class="time-off">{{date('H:i',strtotime($item['start_time'])).' - '.date('H:i',strtotime($item['end_time'])).' '.'('.$item['vi_name'].')'}} </p>
                @endif
            @endforeach
        @endif
    @endif


</div>