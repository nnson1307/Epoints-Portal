<div class="modal fade" id="popup-list-staff" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-cog ss--icon-title m--margin-right-5"></i>
                    {{ __('Danh sách nhân viên') }}
                </h4>
            </div>
            <div class="modal-header bg w-100">
                <div class="row w-100" >
                    <div class="col-3">
                        <input type="text" class="form-control search" placeholder="{{__('Nhập tên nhân viên')}}">
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="d-flex">
                            <button onclick="ManagerWork.clearSearchPageListStaff()" class="btn  btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </button>
                            <button onclick="ManagerWork.searchPageListStaff()" class="btn ss--button-cms-piospa m-btn--icon">
                                {{ __('TÌM KIẾM') }}
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body table-responsive show-list-staff">
                <table class="table table-striped m-table m-table--head-bg-default">
                    <thead class="bg">
                    <th class="tr_thead_list">{{__('Tài khoản')}}</th>
                    <th class="tr_thead_list">{{__('Vai trò')}}</th>
                    </thead>
                    <tbody>
                    @foreach($listStaff as $item)
                        <tr>
                            <td>{{$item['full_name']}}</td>
                            <td>{{$item['staff_id'] == $processor_id ? __('Người thực hiện') : __('Người hỗ trợ')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $listStaff->links('manager-work::managerWork.helpers.paging-list-staff') }}
            </div>
            <input type="hidden" id="manage_work_id_popup_list_staff" value="{{$manage_work_id}}">
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
