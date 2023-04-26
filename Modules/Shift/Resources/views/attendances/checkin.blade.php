<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modalChecking" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 40% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #008990!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> VÀO CA
                </h5>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>
                        Vị trí làm việc:
                    </label>
                    <input type="text" id="branch" value="{{$data['branch_name']}}" class="form-control m-input"
                        readonly>
                    <input type="hidden" id="time_working_staff_id" value="{{$data['time_working_staff_id']}}">
                    <input type="hidden" id="checkin_branch_id" value="{{$data['branch_id']}}">
                </div>
                <div class="form-group">
                    <div class="m-section" style="margin: 0 0 0 0;">
                        <label>
                            Kết nối internet:
                        </label>
                        <div class="m-section__content">
                            <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                                <div class="m-demo__preview" style="border: 1px solid #f7f7fa; padding: 15px;">
                                    <span class="m-section__sub">
                                        <b>Tên</b> : FPT Telecom
                                    </span>
                                    <span class="m-section__sub">
                                        <b>BSSID</b> : {{$ip?? ""}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="m-section" style="margin: 0 0 0 0;">
                        <div class="m-section__content">
                            <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                                <div class="m-demo__preview" style="border: 1px solid #f7f7fa; padding: 15px;">
                                    @if($data != null)
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <label class="m-radio">
                                                <input type="radio" name="shift" value="{{$data['shift_id']}}" checked>
                                                {{$data['shift_name']}}
                                                <span></span>
                                            </label>    
                                        </div>
                                        <div class="col-lg-4 text-left">
                                            <span>({{ \Carbon\Carbon::createFromFormat('H:i:s', $data['working_time'])->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $data['working_end_time'])->format('H:i') }})</span>  
                                            <input type="hidden" id="working_time" value="{{$data['working_time']}}">
                                        </div>
                                    </div>
                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </button>

                        <button class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md
                                m--margin-left-10" onclick="attendances.checkin()">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('ĐỒNG Ý')</span>
                            </span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>