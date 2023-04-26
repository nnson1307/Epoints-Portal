<div class="modal fade" id="modal-config" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="config_search">
                <div class="modal-header">
                    <h4 class="modal-title ss--title m--font-bold">
                        <i class="fa fa-cog ss--icon-title m--margin-right-5"></i>
                        {{ __('THÊM NGUỒN KHÁCH HÀNG TIỀM NĂNG') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="form-config">
                        <div class="row">
                            <div class="col-12">
                                <p>Team Marketing :<span class="text-danger">*</span></p>
                                <select class="form-control" name="team_marketing_id">
                                    <option value="">Chọn nhóm</option>
                                    @foreach($listTeam as $item)
                                        <option value="{{$item['team_id']}}" {{$detail != null && $detail['team_marketing_id'] == $item['team_id'] ? 'selected' : ''}}>{{$item['team_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <p>Đường dẫn google sheet :<span class="text-danger">*</span></p>
                                <input type="text" class="form-control" name="link" placeholder="Nhập đường dẫn google sheet" value="{{$detail != null ? $detail['link'] : ''}}">
                            </div>
                            <div class="col-12 form-group">
                                <p>Phòng ban :<span class="text-danger">*</span></p>
                                <select class="form-control " name="department_id[]" multiple>
                                    @foreach($listDepartment as $item)
                                        <option value="{{$item['department_id']}}" {{$detail != null && in_array($item['department_id'],$detail['list_department']) ? 'selected' : '' }}>{{$item['department_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-5">
                                        <p>Phân bổ xoay vòng tự động :<span class="text-danger">*</span></p>
                                    </div>
                                    <div class="col-7">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px; padding-top: 4px">
                                            <input type="checkbox" name="is_rotational_allocation" id="is_rotational_allocation" {{$detail == null ? 'checked' : ($detail != null && $detail['is_rotational_allocation'] == 1 ? 'checked' : '')}} class="manager-btn">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="row">
                                    <div class="col-5">
                                        <p>Trạng thái :<span class="text-danger">*</span></p>
                                    </div>
                                    <div class="col-7">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px; padding-top: 4px">
                                            <input type="checkbox" name="is_active" id="is_active" {{$detail == null ? 'checked' : ( $detail != null && $detail['is_active'] == 1 ? 'checked' : '')}} class="manager-btn">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="cpo_customer_lead_config_source_id" value="{{$detail != null ? $detail['cpo_customer_lead_config_source_id'] : ''}}">
                    </form>
                </div>
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

                        <button type="button" onclick="config.saveConfig()"
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
