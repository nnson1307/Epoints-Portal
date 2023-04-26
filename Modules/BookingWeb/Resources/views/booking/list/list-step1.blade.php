@if(isset($LIST_BRANCH))
    <label>Hiện có <strong>{{count($LIST_BRANCH)}}</strong> {{__('chi nhánh tại')}} </label>
    <div class="kt-section__content">
        <table class="table">
            <tbody>
            @foreach($LIST_BRANCH as $item)
                <tr>
                    <td class="td-img">
                        @if(isset($item['avatar']))
                            <img src="{{asset($item['avatar'])}}">
                        @else
                            <img src="{{asset('static/booking-template/image/default-placeholder.png')}}">
                        @endif

                    </td>
                    <td>
                        <span class="kt-font-bold">{{$item['branch_name']}}</span><br/>
                        <span class="weight-400">{{$item['address']}}</span>
                    </td>
                    <td>
                        <div class="inputGroup">
                            <input class="radio" name="branch" type="radio" data-branch="{{$item['branch_name']}}"
                                   value="{{$item['branch_id']}}" onclick="step1.check_branch('{{$item['branch_id']}}')"/>
                            <label class="label-radio"></label>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <label>{{__('Không tìm thấy chi nhánh')}}</label>
@endif