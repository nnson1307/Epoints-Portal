<div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content clear-form" id="add-manager-work">
        <div class="modal-header">
            <h4 class="modal-title ss--title m--font-bold">
                <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                {{ __('managerwork::managerwork.add_work') }}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group m-form__group">
                <div class="form-check pl-0">
                    <label class="form-check-label m-checkbox m-checkbox--air">
                        <input type="checkbox" class="form-check-input check-page" name="is_approve_id_check" id="is_approve_id_check">
                        <input type="hidden" name="is_approve_id">
                        <span></span>
                        <div class="pt-1">{{ __('managerwork::managerwork.work_approve') }}</div>
                    </label>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('managerwork::managerwork.title') }} <b class="text-danger">*</b>
                </label>
                <input type="text" name="manage_work_title" class="form-control m-input"
                       placeholder="{{ __('managerwork::managerwork.enter_title') }}...">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('managerwork::managerwork.type_work') }} <b class="text-danger">*</b>
                </label>
                <?php $check_first_value = true; ?>
                <div class="input-group m-radio-inline">
                    @if (count($typeWork) < 5)
                        @foreach ($typeWork as $work_id => $name_work)
                            <label class="m-radio cus">
                                <input type="radio" name="manage_type_work_id" value="{{ $work_id }}"{{ $check_first_value == true ? ' checked' : '' }}>{{ $name_work }}
                                <span></span>
                            </label>
                            <?php $check_first_value = false; ?>
                        @endforeach
                        <select name="manage_type_work_id" class="d-none" multiple>
                            @foreach ($typeWork as $work_id => $name_work)
                                <option value="{{ $work_id }}" {{ $check_first_value == true ? ' selected' : '' }}>
                                    {{ $name_work }}</option>
                                <?php $check_first_value = false; ?>
                            @endforeach
                        </select>
                        <select name="day_of_month[]" class="d-none" multiple>
                            @for ($i = 0; $i < 32; $i++)
                                <option value="{{$i}}"></option>
                            @endfor
                        </select>
                        <select name="day_of_week[]" class="d-none" multiple>
                            @for ($i = 2; $i < 9; $i++)
                                <option value="{{$i}}"></option>
                            @endfor
                        </select>
                    @else
                        <select name="manage_type_work_id" class="form-control select2 select2-active">
                            @foreach ($typeWork as $work_id => $name_work)
                                <option value="{{ $work_id }}" {{ $check_first_value == true ? ' selected' : '' }}>
                                    {{ $name_work }}</option>
                                <?php $check_first_value = false; ?>
                            @endforeach
                        </select>
                    @endif

                </div>
            </div>
            <div class="form-group m-form__group">
                <div class="form-check pl-0">
                    <label class="form-check-label m-checkbox m-checkbox--air">
                        <input type="checkbox" class="form-check-input check-page check_start_date_check"
                               name="check_start_date_check" id="check_start_date_check" value="1">
                        <span></span>
                        <div class="pt-1">{{ __('managerwork::managerwork.no_startdate') }}</div>
                    </label>
                </div>
                <input type="hidden" name="check_start_date">
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('managerwork::managerwork.date_expiration') }} <b class="text-danger">*</b>
                        </label>
                        <div class="input-group date date-multiple">
                            <input type="text" class="form-control m-input daterange-input" readonly
                                   placeholder="{{ __('managerwork::managerwork.date_expiration') }}" name="date_issue" id="date_issue">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                        <div class="input-group date date-single d-none">
                            <input type="text" class="form-control m-input date-timepicker" readonly
                                   placeholder="{{ __('managerwork::managerwork.date_expiration') }}" name="date_issue_single" id="date_issue_single">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('managerwork::managerwork.time') }} <b class="text-danger">*</b>
                        </label>
                        <div>
                            {{-- <div class="timepicker timepicker1" dir="ltr">
                                <input type="text" class="hh N" min="0" max="1000" placeholder="Ngày" maxlength="2" />:
                                <input type="text" class="mm N" min="0" max="23" placeholder="Giờ" maxlength="2" />
                            </div> --}}
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="time" placeholder="{{ __('managerwork::managerwork.enter_time') }}">
                                <div class="input-group-append">
                                    <select class="input-group-text" name="time_type">
                                        <option value="d" selected>{{ __('managerwork::managerwork.day') }}</option>
                                        <option value="h">{{ __('managerwork::managerwork.hour') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('managerwork::managerwork.select_staff') }} <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select name="processor_id" class="form-control select2 select2-active">
                                <option value="">{{ __('managerwork::managerwork.select_staff') }}</option>
                                @foreach ($staffList as $key => $value)
                                    <option value="{{ $key }}" {{ $key == \Auth::id() ? ' selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('managerwork::managerwork.select_staff_approve') }}
                        </label>
                        <div class="input-group">
                            <select name="approve_id" id="approve_id" class="form-control select2 select2-active">
                                <option value="">{{ __('managerwork::managerwork.select_staff_approve') }}</option>
                                @foreach ($staffList as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="accordion">
                <div class="card border-0">
                    <div class="card-header bg-white border-0">
                        <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapseTwo">
{{--                            <h5><i class="fa fa-chevron-circle-down mr-2" aria-hidden="true"></i>@lang('Thông tin công việc')</h5>--}}
                            <h5><i class="fa fa-chevron-circle-down mr-2" aria-hidden="true"></i>{{ __('managerwork::managerwork.work_info') }}</h5>
                        </a>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.select_staff_support') }}
                                        </label>
                                        <div class="input-group">
                                            <select name="processor[]" class="form-control select2 select2-active" multiple
                                                    data-placeholder="{{ __('managerwork::managerwork.select_staff_support') }}">
                                                <option value=""></option>
                                                @foreach ($staffList as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.parent_task') }}
                                        </label>
                                        <div class="input-group">
                                            <select name="parent_id" class="form-control select2 select2-active">
                                                <option value="">{{ __('managerwork::managerwork.select_parent_task') }}</option>
                                                @foreach ($managerWorkList as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.process') }}
                                        </label>
                                        <input id="progress100" value="0" type="text" name="progress" max="100" class="form-control m-input"
                                               placeholder="{{ __('managerwork::managerwork.enter_process') }}...">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.status') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="manage_status_id" class="form-control select2 select2-active">
                                                <option value="">{{ __('managerwork::managerwork.status') }}</option>
                                                @foreach ($manageStatusList as $key => $value)
                                                    <option value="{{ $key }}"
                                                            {{ $key == 1 ? ' selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group m-form__group">
                                        <label> {{ __('managerwork::managerwork.description_work') }}</label>
                                        <textarea name="description" class="form-control m-input summernote">
                                </textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.project') }}
                                        </label>
                                        <div class="input-group">
                                            <select name="manage_project_id" id="manage_project_id" class="form-control">
                                                <option value="">{{ __('managerwork::managerwork.select_project') }}</option>
                                                @foreach ($projectList as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.customer_work') }}
                                        </label>
                                        <div class="input-group">
                                            <select name="customer_id" class="form-control select2 select2-active">
                                                <option value="">{{ __('managerwork::managerwork.select_customer') }}</option>
                                                @foreach ($customersList as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Tags')
                                        </label>
                                        <div class="input-group">
                                            <select name="manage_tag_id[]" class="form-control select2 select2-active"
                                                    multiple data-placeholder="Chọn tag">
                                                <option value="">{{ __('managerwork::managerwork.select_tags') }}</option>
                                                @foreach ($manageTagsList as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.type_tags_work') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="type_card_work" class="form-control select2 select2-active">
                                                <option value="">{{ __('managerwork::managerwork.select_type_tags_work') }}</option>
                                                @foreach ($typeWorkTagsList as $key => $value)
                                                    <option value="{{ $key }}"
                                                            {{ $key == 'bonus' ? ' selected' : '' }}   >{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('managerwork::managerwork.priority') }}
                                        </label>
                                        <div class="input-group">
                                            <select name="priority" class="form-control select2 select2-active">
                                                <option value="">{{ __('managerwork::managerwork.priority') }}</option>
                                                @foreach ($priorityWorkList as $key => $value)
                                                    <option value="{{ $key }}"
                                                            {{ $key == 2 ? ' selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{--                            <div class="col-lg-12 text-right">--}}
                                {{--                                <button type="button" class="btn btn-sm m-btn--icon bg-light color"--}}
                                {{--                                    onclick="ManagerWork.createRemind()">--}}
                                {{--                                    <span>--}}
                                {{--                                        <span class="fa fa-calendar pr-1" aria-hidden="true"></span>--}}
                                {{--                                        {{ __('Thêm nhắc nhở') }}--}}
                                {{--                                    </span>--}}
                                {{--                                </button>--}}
                                {{--                                <div class="remind-add mt-3 mb-3"></div>--}}
                                {{--                            </div>--}}
                                {{--                            <div class="col-lg-12 text-right">--}}
                                {{--                                <button type="button" class="btn btn-sm m-btn--icon bg-light color"--}}
                                {{--                                onclick="ManagerWork.RepeatNotification()">--}}
                                {{--                                    <span>--}}
                                {{--                                        <span class="fa fa-bell pr-1" aria-hidden="true"></span>--}}
                                {{--                                        {{ __('Tần suất lặp lại') }}--}}
                                {{--                                    </span>--}}
                                {{--                                </button>--}}
                                {{--                                <div class="repeat-html d-none"></div>--}}
                                {{--                            </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                    <span class="ss--text-btn-mobi text-uppercase">
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('managerwork::managerwork.cancel') }}</span>
                    </span>
                    </button>

                    <button type="button" onclick="ManagerWork.addClose()"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi text-uppercase">
                        <i class="la la-check"></i>
                        <span>{{ __('managerwork::managerwork.save_info') }}</span>
                    </span>
                    </button>
                    <button type="button" onclick="ManagerWork.add()"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi text-uppercase">
                        <i class="fa fa-plus-circle m--margin-right-10"></i>
                        <span>{{ __('managerwork::managerwork.save_info_created') }}</span>
                    </span>
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
