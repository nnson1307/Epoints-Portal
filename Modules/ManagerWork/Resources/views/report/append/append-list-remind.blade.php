@if(count($listRemind) != 0)
    <div class="col-12 mt-3">
        <div class="row list-remind">
            @foreach($listRemind as $key => $item)
                <div class="col-10 ml-0 block-info-remind {{$item['is_sent'] == 0 && \Carbon\Carbon::parse($item['date_remind']) > \Carbon\Carbon::now() ? '' : 'block-disabled'}}" >
                    <div class="row">
                        <div class="col-11">
                            @if($item['manage_work_id'] != null)
                                <a href="{{route('manager-work.detail',['id' => $item['manage_work_id']])}}">
                            @else
                                <a href="javascript:void(0)">
                            @endif
                            @if($item['title'] == null)
                                @if($item['manage_work_id'] == null)
                                    <p class="mb-0 font-weight-bold">{{$item['created_name'].' '.__('managerwork::managerwork.created_remind_for').' '.$item['staff_name']}}</p>
                                @else
                                    <p class="mb-0 font-weight-bold">{{$item['created_name'].' '.__('managerwork::managerwork.created_remind_work_for',['manage_work_title' => $item['manage_work_title']]).' '.$item['staff_name']}}</p>
                                @endif
                            @else
                                <p class="mb-0 font-weight-bold">{{$item['title']}}</p>
                            @endif
                            </a>
                            <p class="mb-0"><i class="far fa-calendar" style="font-size: 12px !important;"></i> {{\Carbon\Carbon::parse($item['date_remind'])->format('H:i d-m-Y')}}</p>
                            <p class="mb-0 ">{{$item['description']}}</p>
                        </div>
{{--                        <div class="col-2">--}}
{{--                            @if($item['is_sent'] == 0 && \Carbon\Carbon::parse($item['date_remind']) > \Carbon\Carbon::now())--}}
{{--                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">--}}
{{--                                    <label style="margin: 0 0 0 10px; padding-top: 4px">--}}
{{--                                        <input type="checkbox" disabled id="is_active_{{$item['manage_remind_id']}}"  {{$item['is_active'] == 1 ? 'checked' : '' }} >--}}
{{--                                        <span></span>--}}
{{--                                    </label>--}}
{{--                                </span>--}}
{{--                            @else--}}
{{--                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">--}}
{{--                                <label style="margin: 0 0 0 10px; padding-top: 4px">--}}
{{--                                    <input type="checkbox"  {{$item['is_active'] == 1 ? 'checked' : '' }} disabled>--}}
{{--                                    <span></span>--}}
{{--                                </label>--}}
{{--                            </span>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>
                </div>
{{--                @if($item['is_sent'] == 0 && \Carbon\Carbon::parse($item['date_remind']) > \Carbon\Carbon::now())--}}
                    <div class="col-2 pt-4">
                        <label class="m-checkbox m-checkbox--state-success mt-0">
                            <input type="checkbox" name="remind[{{$item['manage_remind_id']}}]"  value="{{$item['manage_remind_id']}}" >
                            <span></span>
                        </label>
                    </div>
{{--                @endif--}}
            @endforeach
        </div>
    </div>
@endif