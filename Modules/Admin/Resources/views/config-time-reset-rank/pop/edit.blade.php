<div id="modal-edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-book"></i> CHỈNH SỬA THỜI GIAN THIẾT LẬP THỨ HẠNG THÀNH VIÊN
                </h4>
            </div>
            <form id="form-edit">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="black-title">
                            Tên:<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control btn-sm" name="name" id="name"
                               placeholder="Hãy nhập tên..." value="{{$item['name']}}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="black-title">
                            Tháng thiết lập:<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control btn-sm" name="value" id="value"
                               placeholder="Hãy nhập tháng thiết lập..." value="{{$item['value']}}">
                    </div>
                    <div class="form-group">
                        <label class="black-title">
                            Khoảng cách tháng:
                        </label>
                        <select class="form-control" name="type" id="type" style="width: 100%;" disabled>
                            <option value="one_month"
                                    {{$item['type'] == '' || $item['type'] == 'one_month' ? 'selected':''}}>1 tháng</option>
                            <option value="two_month" {{$item['type'] == 'two_month' ? 'selected':''}}>2 tháng</option>
                            <option value="three_month" {{$item['type'] == 'three_month' ? 'selected':''}}>3 tháng</option>
                            <option value="four_month" {{$item['type'] == 'four_month' ? 'selected':''}}>4 tháng</option>
                            <option value="six_month" {{$item['type'] == 'six_month' ? 'selected':''}}>6 tháng</option>
                            <option value="one_year" {{$item['type'] == 'one_year' ? 'selected':''}}>12 tháng</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>{{__('HỦY')}}</span>
                                    </span>
                            </button>
                            <button type="button"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-active m--margin-left-10"
                                    onclick="index.submit_edit('{{$id}}')">
                                    <span>
                                        <i class="la la-check"></i>
                                        <span>{{__('LƯU THÔNG TIN')}}</span>
                                    </span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
