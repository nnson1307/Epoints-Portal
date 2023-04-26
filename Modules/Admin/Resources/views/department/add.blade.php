<div class="modal fade" id="modalAdd" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('THÊM PHÒNG BAN')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-department">
                    <div class="m-portlet__body">
                        <div class="form-group">
                            <label>
                                {{__('Tên phòng ban')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" id="department_name" name="department_name" class="form-control m-input"
                                   placeholder="{{__('Nhập tên phòng ban')}}">
                            <span class="department-name"></span>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label class="black_title">--}}
{{--                                @lang('Thông tin nhánh cha'):<b class="text-danger">*</b>--}}
{{--                            </label>--}}
{{--                            <div class="input-group">--}}
{{--                                <select class="form-control" id="branch_id" name="branch_id" style="width:100%">--}}
{{--                                    <option></option>--}}
{{--                                    @foreach($optionBranch as $v)--}}
{{--                                        <option value="{{$v['branch_id']}}">{{$v['branch_name']}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="row">--}}
{{--                            <div class="form-group col-lg-6">--}}
{{--                                <label class="black_title">--}}
{{--                                    @lang('Chức vụ'):--}}
{{--                                    <b class="text-danger">*</b>--}}
{{--                                </label>--}}
{{--                                <div class="input-group">--}}
{{--                                    <select class="form-control" id="staff_title_id" name="staff_title_id" onchange="view.changeTitle(this)" style="width:100%">--}}
{{--                                        <option></option>--}}
{{--                                        @foreach($optionTitle as $v)--}}
{{--                                            <option value="{{$v['staff_title_id']}}">{{$v['staff_title_name']}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-lg-6">--}}
{{--                                <label class="black_title">--}}
{{--                                    @lang('Người quản lý'):--}}
{{--                                    <b class="text-danger">*</b>--}}
{{--                                </label>--}}
{{--                                <div class="input-group">--}}
{{--                                    <select class="form-control" id="staff_id" name="staff_id" style="width:100%">--}}
{{--                                        <option></option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>
                        <button type="button" onclick="Department.add()"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-left-10 m--margin-bottom-5">
                   <span class="ss--text-btn-mobi">
                    <i class="la la-check"></i>
                    <span>{{__('LƯU THÔNG TIN')}}</span>
                    </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


