
<div class="m-portlet m-portlet--head-sm tab_work_detail">
    <nav class="nav">
        <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('comment')">{{ __('managerwork::managerwork.comment') }}</a>
        <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('document')">{{ __('managerwork::managerwork.document') }}</a>
        <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('remind')">{{ __('managerwork::managerwork.remind') }}</a>

    @if($detail['parent_id'] == null)
            <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('sub_task')">{{ __('managerwork::managerwork.child_task') }}</a>
        @endif
        <a class="hover-cursor nav-link active" onclick="ChangeTab.tabComment('history')">{{ __('managerwork::managerwork.history') }}</a>
    </nav>

    <div class="col-12 mt-5">
        <form id="form-search-history">
            <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">
            <div class="row">
                <div class="col-3">
                    <div class="m-input-icon m-input-icon--right">
                        <input type="text" class="form-control searchDate" name="created_at" >
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <div class="col-3">
                    <select class="form-control selectForm" onchange="History.search()" name="staff_id">
                        <option value="">{{ __('managerwork::managerwork.staff_processor') }}</option>
                        @foreach($listStaff as $item)
                            <option value="{{$item['staff_id']}}">{{$item['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">
        <div class="h-50">
            <h5 style="height: 300px" class="d-flex align-items-center text-center justify-content-center">{{ __('managerwork::managerwork.no_data') }}</h5>
        </div>
    </div>
</div>

<script src="{{asset('static/backend/js/manager-project/managerWork/detail-work-history.js?v='.time())}}"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v=' . time()) }}" type="text/javascript"></script>

