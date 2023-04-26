
<div class="m-portlet m-portlet--head-sm tab_work_detail">
    <nav class="nav">
        <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('comment')">{{ __('managerwork::managerwork.comment') }}</a>
        <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('document')">{{ __('managerwork::managerwork.document') }}</a>
        <a class="hover-cursor nav-link active" onclick="ChangeTab.tabComment('remind')">{{ __('managerwork::managerwork.remind') }}</a>

    @if($detail['parent_id'] == null)
            <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('sub_task')">{{ __('managerwork::managerwork.child_task') }}</a>
        @endif
        <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('history')">{{ __('managerwork::managerwork.history') }}</a>
        <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('location')">@lang('Vị trí')</a>
    </nav>
{{--        @if(count($listRemind) != 0)--}}
        <div class="col-12 mt-3 ml-2">
            <form id="form-search">
                <div class="row">
                    <input type="hidden" name="sort_date_remind" id="sort_date_remind" value="DESC">
                    <div class="col-2">
                        <input type="text" class="form-control" id="description" name="description" placeholder="{{ __('managerwork::managerwork.content') }}">
                    </div>
                    <div class="col-2">
                        <select class="form-control selectForm" name="staff_id">
                            <option value="">{{ __('managerwork::managerwork.remind_for') }}</option>
                            @foreach($listStaff as $item)
                                <option value="{{$item['staff_id']}}">{{$item['staff_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text" class="form-control searchDate" name="date_remind" >
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="button" data-dismiss="modal" class="btn btn-metal" onclick="Remind.removeSearchRemind()">
                            <span class="ss--text-btn-mobi">
                                <span>{{ __('managerwork::managerwork.delete_th') }}</span>
                            </span>
                        </button>
                        <button type="button" onclick="Remind.search({{$detail['manage_work_id']}})" class="btn ss--btn-search">
                            {{ __('managerwork::managerwork.search') }}
                            <i class="fa fa-search ss--icon-search"></i>
                        </button>
                        <a href="javascript:void(0)" onclick="Remind.sortListRemind()"><i class="fas fa-sort-numeric-down fa-sort-numeric-down-fix"></i></a>
                    </div>
{{--                        @if(count($listRemind) != 0)--}}
                    @if(\Illuminate\Support\Facades\Session::has('is_staff_work_project') == false || \Illuminate\Support\Facades\Session::get('is_staff_work_project') == 1)
                        <div class="col-2 text-right">
                            <button type="button" style="border-radius:20px" onclick="Remind.showPopup()" class=" ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                                <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.ADD_REMIND') }}
                            </button>
                        </div>
                    @endif
{{--                        @endif--}}
                </div>
            </form>
        </div>
{{--        @endif--}}
    <div class="col-12 pb-5">
        <div class="row append-list-remind">
            @include('manager-work::managerWork.append.append-list-remind')
        </div>
    </div>

</div>

