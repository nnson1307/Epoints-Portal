{{--@if(count($listRemind) != 0)--}}
    <div class="col-12 mt-3 ml-2">
        <div class="row list-remind">
            @foreach($listRemind as $key => $item)
                <div class="col-8 block-info-remind {{$item['is_sent'] == 0 && \Carbon\Carbon::parse($item['date_remind']) > \Carbon\Carbon::now() ? '' : 'block-disabled'}}" >
                    <div class="row">
                        <div class="col-2">
                            <p class="mb-0">{{\Carbon\Carbon::parse($item['date_remind'])->format('H:i')}}</p>
                            <p class="mb-0">{{\Carbon\Carbon::parse($item['date_remind'])->format('d-m-Y')}}</p>
                        </div>
                        <div class="col-8">
{{--                            <p class="mb-0 font-weight-bold">{{$item['created_name'].' '.__('managerwork::managerwork.created_remind_for').' '.$item['staff_name']}}</p>--}}
                            @if($item['title'] == null)
                                @if($item['manage_work_id'] == null)
                                    <p class="mb-0 font-weight-bold">{{$item['created_name'].' '.__('managerwork::managerwork.created_remind_for').' '.$item['staff_name']}}</p>
                                @else
                                    <p class="mb-0 font-weight-bold">{{$item['created_name'].' '.__('managerwork::managerwork.created_remind_work_for',['manage_work_title' => $item['manage_work_title']]).' '.$item['staff_name']}}</p>
                                @endif
                            @else
                                <p class="mb-0 font-weight-bold">{{$item['title']}}</p>
                            @endif
                            <p class="mb-0">{{$item['description']}}</p>
                        </div>
                        <div class="col-2">
                            @if($item['is_sent'] == 0 && \Carbon\Carbon::parse($item['date_remind']) > \Carbon\Carbon::now())
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" id="is_active_{{$item['manage_remind_id']}}" {{$item['is_active'] == 1 ? 'checked' : '' }} onchange="Remind.changeActive({{$item['manage_remind_id']}})">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"  {{$item['is_active'] == 1 ? 'checked' : '' }} disabled>
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
{{--                @if($item['is_sent'] == 0 && \Carbon\Carbon::parse($item['date_remind']) > \Carbon\Carbon::now())--}}
                <div class="col-2 pt-4">
                    @if(\Illuminate\Support\Facades\Session::has('is_staff_work_project') == false || \Illuminate\Support\Facades\Session::get('is_staff_work_project') == 1)
                        <a href="javascript:void(0)" onclick="Remind.showPopup({{$item['manage_remind_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="{{ __('managerwork::managerwork.update') }}"><i class="la la-edit"></i></a>
                        <button onclick="Remind.removeRemind({{$item['manage_remind_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="{{ __('managerwork::managerwork.delete_th') }}"><i class="la la-trash"></i></button>
                    @endif
                </div>
{{--                @endif--}}
            @endforeach
        </div>
    </div>
{{--@else--}}
{{--    <div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">--}}
{{--        <div class="h-50">--}}
{{--            <div class="d-flex align-items-center text-center justify-content-center" style="height: 300px" >--}}
{{--                <div>--}}
{{--                    <h5 class="d-block">{{ __('managerwork::managerwork.no_remind') }}</h5>--}}
{{--                    <button type="button" style="border-radius:20px" onclick="Remind.showPopup()" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">--}}
{{--                        <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.ADD_REMIND') }}--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}