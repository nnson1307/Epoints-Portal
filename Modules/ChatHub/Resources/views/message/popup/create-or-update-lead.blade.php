<div class="modal" id="modal-message-create-lead"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 100% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('TẠO KHÁCH HÀNG TIỀM NĂNG')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-register">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <div class="input-group">
                                            <label class="black_title">
                                                @lang('Loại khách hàng'):<b class="text-danger">*</b>
                                            </label>

                                            <select class="form-control" id="customer_type_create" name="customer_type"
                                                    style="width:100%;" onchange="viewLead.changeType(this)">
{{--                                                <option></option>--}}
                                                <option value="personal" {{isset($dataCustomerLead['customer_type']) && $dataCustomerLead['customer_type'] == 'personal' ? 'selected' : ''}}>@lang('Cá nhân')</option>
                                                <option value="business" {{isset($dataCustomerLead['customer_type']) && $dataCustomerLead['customer_type'] == 'business' ? 'selected' : ''}}>@lang('Doanh nghiệp')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="append_type"></div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Họ & tên'):<b class="text-danger">*</b>
                                        </label>
                                        <input type="text" class="form-control m-input" id="full_name" name="full_name"
                                               value="{{isset($dataCustomerLead['full_name']) ? $dataCustomerLead['full_name'] : ''}}"
                                               placeholder="@lang('Họ và tên')">
                                    </div>

                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Số điện thoại'):<b class="text-danger">*</b>
                                        </label>
                                        <input type="text" class="form-control m-input phone" id="phone" name="phone"
                                               value="{{isset($dataCustomerLead['phone']) ? $dataCustomerLead['phone'] : ''}}"
                                               placeholder="@lang('Số điện thoại')">
                                    </div>
                                    <div class="phone_append"></div>
                                    <div class="form-group m-form__group">
                                        <a href="javascript:void(0)" onclick="viewLead.addPhone()"
                                           class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                            <i class="la la-plus"></i> @lang('Thêm số điện thoại')
                                        </a>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Email'):
                                        </label>
                                        <input type="text" class="form-control m-input" id="email" name="email"
                                               value="{{isset($dataCustomerLead['email']) ? $dataCustomerLead['email'] : ''}}"
                                               placeholder="@lang('Email')">
                                        <span class="error_email color_red"></span>
                                    </div>
                                    <div class="email_append"></div>
                                    <div class="form-group m-form__group">
                                        <a href="javascript:void(0)" onclick="viewLead.addEmail()"
                                           class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                            <i class="la la-plus"></i> @lang('Thêm email')
                                        </a>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Nguồn khách hàng'):<b class="text-danger">*</b>
                                        </label>

                                        <div class="input-group">
                                            <select class="form-control" id="customer_source" name="customer_source"
                                                    style="width:100%;">
                                                <option></option>
                                                @foreach($optionSource as $v)
                                                    @if(isset($dataCustomerLead['customer_source']) && $dataCustomerLead['customer_source'] == $v['customer_source_id'])
                                                    <option value="{{$v['customer_source_id']}}" selected>{{$v['customer_source_name']}}</option>
                                                    @else
                                                        <option value="{{$v['customer_source_id']}}">{{$v['customer_source_name']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Pipeline'):<b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select class="form-control" id="pipeline_code" name="pipeline_code"
                                                    style="width:100%;">
                                                <option></option>
                                                @foreach($optionPipeline as $v)
                                                    @if(isset($dataCustomerLead['pipeline_code']) && $dataCustomerLead['pipeline_code'] == $v['pipeline_code'])
                                                    <option value="{{$v['pipeline_code']}}" selected>{{$v['pipeline_name']}}</option>
                                                    @else
                                                        <option value="{{$v['pipeline_code']}}">{{$v['pipeline_name']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Hành trình'):<b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select class="form-control journey" id="journey_code" name="journey_code"
                                                    style="width:100%;">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black-title">
                                            @lang('Giới tính'):
                                        </label>
                                        <div class="m-form__group form-group ">
                                            <div class="m-radio-inline">
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender" value="male" checked> @lang('Nam')
                                                    <span
                                                            class="span"></span>
                                                </label>
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender" value="female"> @lang('Nữ') <span
                                                            class="span"></span>
                                                </label>
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender" value="other"> @lang('Khác') <span
                                                            class="span"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Địa chỉ'):
                                    </label>
                                    <input type="text" class="form-control m-input" id="address" name="address"
                                           value="{{isset($dataCustomerLead['address']) ? $dataCustomerLead['address'] : ''}}"
                                           placeholder="@lang('Địa chỉ')">
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-6">
                                        <label class="black_title">
                                            @lang('Tỉnh thành'):
                                        </label>

                                        <select name="province_id" id="province_id" class="form-control"
                                                style="width: 100%" onchange="viewLead.changeProvince(this)">
                                            <option></option>
                                            @foreach($optionProvince as $v)
                                                <option value="{{$v['provinceid']}}">{{$v['type'] .' '. $v['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="black_title">
                                            @lang('Quận huyện'):
                                        </label>
                                        <select name="district_id" id="district_id"
                                                class="form-control district" style="width: 100%">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if(in_array('customer-lead.permission-assign-revoke', session('routeList')))
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Nguời được phân bổ'):
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="sale_id" name="sale_id"
                                                style="width:100%;">
                                            @foreach($optionStaff as $v)
                                                <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="div_business_clue">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Đầu mối doanh nghiệp'):
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="business_clue" name="business_clue"
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($optionBusiness as $v)
                                                <option value="{{$v['customer_lead_code']}}">{{$v['full_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Zalo'):
                                </label>
                                <input type="text" class="form-control m-input"
                                       value="{{isset($dataCustomerLead['zalo']) ? $dataCustomerLead['zalo'] : ''}}"
                                       id="zalo" name="zalo" placeholder="@lang('Zalo')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Fan page'):
                                </label>
                                <input type="text" class="form-control m-input" id="fanpage" name="fanpage"
                                       value="value="{{isset($dataCustomerLead['fanpage']) ? $dataCustomerLead['fanpage'] : ''}}""
                                       placeholder="@lang('Fan page')">
                            </div>
                            <div class="fanpage_append"></div>
                            <div class="form-group m-form__group">
                                <a href="javascript:void(0)" onclick="viewLead.addFanpage()"
                                   class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                    <i class="la la-plus"></i> @lang('Thêm fanpage')
                                </a>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tag'):
                                </label>
                                <div>
                                    <select class="form-control" id="tag_id" name="tag_id" multiple style="width:100%;">
                                        <option></option>
                                        @foreach($optionTag as $v)
                                            <option value="{{$v['tag_id']}}">{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="more_info">
                                @if(count($customDefine) > 0)
                                    @foreach($customDefine as $v)
                                        <div class="form-group m-form__group">
                                            <label>
                                                {{$v['title']}}:
                                            </label>
                                            <div class="m-input-icon m-input-icon--right">
                                                @switch($v['type'])
                                                    @case('text')
                                                    <input type="text" id="{{$v['key']}}" name="{{$v['key']}}"
                                                           class="form-control m-input" maxlength="190">
                                                    @break;
                                                    @case('boolean')
                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox" class="manager-btn" checked
                                                                           id="{{$v['key']}}" name="{{$v['key']}}"
                                                                           value="1"
                                                                           onchange="viewLead.changeBoolean(this)">
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                    @break;
                                                @endswitch
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group div_add_contact" style="display:none;">
                        <div class="table-responsive">
                            <table class="table table-striped m-table m-table--head-bg-default" id="table-contact">
                                <thead class="bg">
                                <tr>
                                    <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
                                    <th class="tr_thead_list">@lang('SỐ ĐIỆN THOẠI')</th>
                                    <th class="tr_thead_list">@lang('EMAIL')</th>
                                    <th class="tr_thead_list">@lang('ĐỊA CHỈ')</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="form-group m-form__group">
                            <a href="javascript:void(0)" onclick="viewLead.addContact()"
                               class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                <i class="la la-plus"></i> @lang('Thêm liên hệ')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button onclick="viewLead.cancelSaveLead()"
                            class="btn bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="viewLead.saveOrUpdate()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>