<div class="modal fade" id="popup-phase" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('Chỉnh sửa giai đoạn') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-phase">
                    <div class="row">
                        <div class="col-lg-12 block block_{n}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group m-form__group">
                                        <label class="black-title">{{ __('Dự án') }}:</label>
                                        <input type="text" class="form-control m-input" disabled readonly value="{{$detail['manage_project_name']}}">
                                        <input type="hidden" class="form-control m-input manage_project_id" name="manage_project_id" value="{{$detail['manage_project_id']}}">
                                        <input type="hidden" class="form-control m-input manage_project_phase_id" name="manage_project_phase_id" value="{{$detail['manage_project_phase_id']}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group m-form__group">
                                        <label class="black-title">{{ __('Tên giai đoạn') }}:<b class="text-danger">*</b></label>
                                        <input type="text" class="form-control m-input name" name="name" value="{{$detail['name']}}" placeholder="{{__('Nhập tên giai đoạn')}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group m-form__group">
                                        <label class="black-title">{{ __('Người chịu trách nhiệm') }}:<b class="text-danger">*</b></label>
                                        <select class="form-control select2 pic"  name="pic">
                                            <option value="">{{__('Người chịu trách nhiệm')}}</option>
                                            @foreach($listStaff as $item)
                                                <option value="{{$item['staff_id']}}" {{$detail['pic'] == $item['staff_id'] ? 'selected' : ''}}>{{$item['staff_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group m-form__group">
                                        <label class="black-title">{{ __('Trạng thái') }}:<b class="text-danger">*</b></label>
                                        <select class="form-control select2 status"  name="status">
                                            <option value="">{{__('Chọn trạng thái')}}</option>
                                            <option value="new" {{$detail['status'] == 'new' ? 'selected' : ''}}>{{__('Mới')}}</option>
                                            <option value="processing" {{$detail['status'] == 'processing' ? 'selected' : ''}}>{{__('Đang thực hiện')}}</option>
                                            <option value="success" {{$detail['status'] == 'success' ? 'selected' : ''}}>{{__('Hoàn thành')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group m-form__group">
                                        <label class="black-title">{{ __('Ngày bắt đầu') }}:</label>
                                        <input type="text" class="form-control m-input date_start" value="{{$detail['date_start'] != '' ? \Carbon\Carbon::parse($detail['date_start'])->format('d/m/Y') : ''}}" name="date_start" placeholder="{{__('Ngày bắt đầu')}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group m-form__group">
                                        <label class="black-title">{{ __('Ngày kết thúc') }}:</label>
                                        <input type="text" class="form-control m-input date_end" value="{{$detail['date_end'] != '' ? \Carbon\Carbon::parse($detail['date_end'])->format('d/m/Y') : ''}}" name="date_end" placeholder="{{__('Ngày kết thúc')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
                        <button type="button" onclick="Phase.updatePhase()"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group-append-select{
        width: 100px;
    }
</style>