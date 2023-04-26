
<div class="m-portlet m-portlet--head-sm tab_work_detail pb-5">
    <nav class="nav">
        <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('comment')">{{ __('managerwork::managerwork.comment') }}</a>
        <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('document')">{{ __('managerwork::managerwork.document') }}</a>
        <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('remind')">{{ __('managerwork::managerwork.remind') }}</a>
        @if($detail['parent_id'] == null)
            <a class="hover-cursor nav-link active" onclick="ChangeTab.tabComment('sub_task')">{{ __('managerwork::managerwork.child_task') }}</a>
        @endif
        <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('history')">{{ __('managerwork::managerwork.history') }}</a>
    </nav>

    <div class="col-12 mt-3 ml-2">
        <form id="form-search" autocomplete="off">
            <div class="row">
                <div class="col-2">
                    <select class="form-control selectForm" name="manage_status_id" id="manage_status_id_search">
                        <option value="">{{ __('managerwork::managerwork.status') }}</option>
                        @foreach($listStatus as $item)
                            <option value="{{$item['manage_status_id']}}">{{$item['manage_status_name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <div class="m-input-icon m-input-icon--right">
                        <input type="text" class="form-control searchDateForm" name="date_created_detail" placeholder="{{ __('managerwork::managerwork.date_created') }}">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <div class="col-2">
                    <div class="m-input-icon m-input-icon--right">
                        <input type="text" class="form-control searchDateForm" name="date_end" placeholder="{{ __('managerwork::managerwork.date_expiration') }}">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <div class="col-2">
                    <select class="form-control selectForm selectFormSearch" name="date_overdue">
                        <option value="">{{ __('managerwork::managerwork.date_overdue') }}</option>
                        <option value="10">10 {{ __('managerwork::managerwork.day') }}</option>
                        <option value="20">20 {{ __('managerwork::managerwork.day') }}</option>
                        <option value="30">30 {{ __('managerwork::managerwork.day') }}</option>
                        <option value="40">40 {{ __('managerwork::managerwork.day') }}</option>
                    </select>
                </div>
                <div class="col-4">
                    <button type="button" data-dismiss="modal" class="btn btn-metal" onclick="WorkChild.removeSearchWork()">
                        <span class="ss--text-btn-mobi">
                            <span>{{ __('managerwork::managerwork.delete_th') }}</span>
                        </span>
                    </button>
                    <button type="button" onclick="WorkChild.search({{$detail['manage_work_id']}})" class="btn ss--btn-search">
                        {{ __('managerwork::managerwork.search') }}
                        <i class="fa fa-search ss--icon-search"></i>
                    </button>
                </div>
                @if(\Illuminate\Support\Facades\Session::has('is_staff_work_project') == false || \Illuminate\Support\Facades\Session::get('is_staff_work_project') == 1)
                    <div class="col-12 text-right">
                        <button type="button" style="border-radius:20px" onclick="WorkChild.showPopup()" class=" ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                            <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.add_child_task') }}
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
<div class="col-12">
    <div class="row append-list-remind">
            @include('manager-project::work.append.append-list-work-child')
    </div>
</div>
</div>
<form id="form-file" autocomplete="off">
    <div id="block_append"></div>
    <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">
</form>



<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v='. time()) }}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/manager-project/managerWork/detail-work-child.js?v='.time())}}" type="text/javascript"></script>
<script>
    var ManagerWork = {
        jsonLang: JSON.parse(localStorage.getItem('tranlate')),
        submitCopy: function (id) {

            swal({
                title: "{{ __('managerwork::managerwork.copy_work') }}",
                text: "{{ __('managerwork::managerwork.are_you_copy') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('managerwork::managerwork.yes') }}",
                cancelButtonText: "{{ __('managerwork::managerwork.cancel') }}"
            }).then(function (result) {
                if (result.value) {
                    $.post(laroute.route('manager-work.copy', {id: id}), function () {
                        swal(
                            "{{ __('managerwork::managerwork.copy_success') }}",
                            '',
                            'success'
                        );
                    });
                }
            });
        },

        approve : function (id){
            $.getJSON(laroute.route('translate'), function (json) {
                swal({
                    title: json['Duyệt công việc'],
                    text: json["Bạn có muốn duyệt không?"],
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: json['Đồng ý'],
                    cancelButtonText: json['Hủy']
                }).then(function(result) {
                    if (result.value) {
                        $.post(laroute.route('manager-work.approve', { id: id }), function() {
                            swal(
                                json['Duyệt công việc thành công.'],
                                '',
                                'success'
                            ).then((result) => {
                                window.location.reload();
                            });
                        });
                    }
                });
            });
        },
    }
</script>

