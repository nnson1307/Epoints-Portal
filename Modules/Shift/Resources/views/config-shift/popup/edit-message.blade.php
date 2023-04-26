<div class="modal fade" id="edit_message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-update">
                <div class="modal-body">
                    <div class="col-12">
                        <h4 class="modal-title text-center mb-5" id="exampleModalLabel">{{ __('managerwork::managerwork.update_notification') }}</h4>
                    </div>
                    <div class="col-12 form-group">
                        <label>{{ __('managerwork::managerwork.notice_items') }}</label>
                        <input type="text" class="form-control" id="manage_config_notification_title_popup" name="sf_timekeeping_notification_title" value="{{$detail['sf_timekeeping_notification_title']}}">
                    </div>
                    <div class="col-12 form-group">
                        <label>{{__('managerwork::managerwork.content_notification')}}</label>
                        <textarea class="form-control" rows="10" id="sf_timekeeping_notification_content_popup" name="sf_timekeeping_notification_content">{!! $detail['sf_timekeeping_notification_content'] !!}</textarea>
                    </div>
                    <div class="col-12 mt-3">
                        <label>{{ __('Thời gian gửi') }}</label>
                    </div>
                    <div class="col-12">
                        <select class="form-control w-25 type_send  d-inline" name="type_send">
                            <option value="0" {{$detail['type_send'] == 0 ? 'selected' : ''}}>=</option>
                            <option value="1" {{$detail['type_send'] == 1 ? 'selected' : ''}}><</option>
                            <option value="2" {{$detail['type_send'] == 2 ? 'selected' : ''}}>></option>
                        </select>
                        <input type="text" name="time_send" {{$detail['type_send'] == 0 ? 'disabled' : ''}} class="time_send form-control w-25 d-inline" value="{{number_format($detail['time_send'])}}">
                    </div>
                </div>
                <input type="hidden" id="sf_timekeeping_notification_id_popup" name="sf_timekeeping_notification_id" value="{{$detail['sf_timekeeping_notification_id']}}">
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('managerwork::managerwork.cancel') }}</button>
                    <button type="button" class="btn ss--btn-search" onclick="Config.changeMessage()">{{ __('managerwork::managerwork.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
