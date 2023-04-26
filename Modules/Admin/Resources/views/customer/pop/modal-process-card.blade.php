<div id="modal-process-card" class="modal fade" role="dialog">
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
                    <i class="la la-book"></i> {{__('TẠO THẺ LIỆU TRÌNH')}}
                </h4>
            </div>
            <form id="form-process-card">
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            {{__('Thẻ dịch vụ')}}:<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select id="service_card_id" name="service_card_id"
                                    class="form-control m-input" style="width: 100%" onchange="detail.choose_service_card(this)">
                                <option></option>
                                @foreach($optionServiceCard as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="black-title">
                                {{__('Ngày kích hoạt')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control btn-sm" name="actived_date" id="actived_date"
                                   disabled placeholder="{{__('Chọn ngày kích hoạt')}}" onchange="detail.change_active_date(this)">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="black-title">
                                {{__('Ngày hết hạn')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control btn-sm" name="expired_date" id="expired_date" disabled placeholder="{{__('Chọn ngày kết thúc')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="black-title">
                                {{__('Số lần đã sử dụng')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control btn-sm" name="count_using" id="count_using" disabled placeholder="{{__('Nhập số lần sử dụng')}}...">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="black-title">
                                {{__('Số lần còn lại')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control btn-sm" name="end_using" id="end_using" disabled placeholder="{{__('Nhập số lần còn lại')}}...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>{{__('HUỶ')}}</span>
                                    </span>
                            </button>
                            <button type="button" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-active m--margin-left-10"
                                    onclick="detail.submit_process_card('{{$customer_id}}')">
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
