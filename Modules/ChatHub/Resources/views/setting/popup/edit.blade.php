<div id="modal-setting-edit" class="modal fade show" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
            .modal-lg {
                max-width: 60%;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('chathub::setting.index.EDIT_CHANNEL')}}
                </h4>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <input type="hidden" name="channel_id" value="{{$item['channel_id']}}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>
                                    {{__('chathub::setting.index.IS_DIALOGFLOW')}}:
                                </label>
                                <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        @if($item["is_dialogflow"] == 1)
                                            <input type="checkbox" checked="" class="manager-btn" name="is_dialogflow" id="is_dialogflow">
                                        @else
                                            <input type="checkbox" class="manager-btn" name="is_dialogflow" id="is_dialogflow">
                                        @endif
                                        <span></span>
                                    </label>
                                </span>
                                </div>
                            </div>
                        </div>
                        @if($item["is_dialogflow"] == 1)
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('chathub::setting.index.PROJECT_ID_DIALOGFLOW')}}:
                                    </label>
                                    <div class="form-group">
                                        <input type="text" name="project_id_dialogflow" class="form-control m-input btn-sm" value="{{$item['project_id_dialogflow']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('chathub::setting.index.PRIVATE_KEY_DIALOGFLOW')}}:
                                    </label>
                                    <div class="form-group">
                                        <textarea rows="4" cols="50"  type="text" name="private_key_dialogflow" class="form-control m-input btn-sm">{{$item['private_key_dialogflow']}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('chathub::setting.index.CLIENT_EMAIL_DIALOGFLOW')}}:
                                    </label>
                                    <div class="form-group">
                                        <input type="text" name="client_email_dialogflow" class="form-control m-input btn-sm" value="{{$item['client_email_dialogflow']}}">
                                    </div>
                                </div>
                            </div>
                        @else

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('chathub::setting.index.PROJECT_ID_DIALOGFLOW')}}:
                                    </label>
                                    <div class="form-group">
                                        <input disabled type="text" name="project_id_dialogflow" class="form-control m-input btn-sm" value="{{$item['project_id_dialogflow']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('chathub::setting.index.PRIVATE_KEY_DIALOGFLOW')}}:
                                    </label>
                                    <div class="form-group">
                                        <textarea rows="4" cols="50" disabled type="text" name="private_key_dialogflow" class="form-control m-input btn-sm">{{$item['private_key_dialogflow']}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('chathub::setting.index.CLIENT_EMAIL_DIALOGFLOW')}}:
                                    </label>
                                    <div class="form-group">
                                        <input disabled type="text" name="client_email_dialogflow" class="form-control m-input btn-sm" value="{{$item['client_email_dialogflow']}}">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>
                            <button type="button" onclick="channel.saveChannel()"
                                    class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                    <span>
                                    <i class="la la-edit"></i>
                                    <span>{{__('CẬP NHẬT')}}</span>
                                    </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
