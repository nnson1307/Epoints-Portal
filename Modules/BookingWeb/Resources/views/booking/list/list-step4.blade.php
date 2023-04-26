@if(isset($LIST_STAFF))
    <table class="table">
        <tbody>
        @foreach($LIST_STAFF as $item)
            <tr>
                <td class="td-img">
                    <div class="kt-avatar kt-avatar--outline kt-avatar--circle" id="kt_apps_user_add_avatar">
                        @if(isset($item['staff_avatar']))
                            <div class="kt-avatar__holder"
                                 style="background-image: url('{{asset($item['staff_avatar'])}}');height: 70px;width: 70px;background-repeat: no-repeat;"></div>
                        @else
                            <div class="kt-avatar__holder"
                                 style="background-image: url('{{asset('static/booking-template/image/person.png')}}');height: 70px;width: 70px;background-repeat: no-repeat;"></div>
                        @endif

                    </div>
                </td>
                <td>
                    <span class="kt-font-bold">{{$item['name']}}</span><br/>
                    <span class="weight-400">
                        <i class="la la-intersex"></i>
                        @if($item['gender']=='male')
                            Nam
                        @elseif($item['gender']=='female')
                            Nữ
                        @endif

                    </span><br/>
                    {{--                    <span class="weight-400"><i class="la la-thumbs-up"></i>{{__('6 năm kinh nghiệm')}}</span>--}}
                </td>
                <td>
                    <div class="inputGroup">
                        <input class="radio" name="staff" type="radio" data-staff="{{$item['name']}}"
                               value="{{$item['staff_id']}}"/>
                        <label class="label-radio"></label>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif