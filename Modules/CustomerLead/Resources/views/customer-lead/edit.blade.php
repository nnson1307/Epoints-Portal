@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ KHÁCH HÀNG TIỀM NĂNG')</span>
@stop
@section("after_style")
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
<link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
<link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@stop
@section('content')

    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("CHỈNH SỬA KHÁCH HÀNG TIỀM NĂNG")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
             
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Loại khách hàng'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="customer_type" name="customer_type"
                                                style="width:100%;" onchange="view.changeType(this)">
                                            <option></option>
                                            <option value="personal" {{$item['customer_type'] == 'personal' ? 'selected' : ''}}>@lang('Cá nhân')</option>
                                            <option value="business" {{$item['customer_type'] == 'business' ? 'selected' : ''}}>@lang('Doanh nghiệp')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Tên khách hàng'):<b class="text-danger">*</b>
                                    </label>
                                    <textarea type="text" class="form-control m-input" id="full_name" name="full_name"
                                           placeholder="@lang('Tên khách hàng')">{{$item['full_name']}}</textarea>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Số điện thoại'):
                                    </label>
                                    <input type="text" class="form-control m-input phone" id="phone" name="phone"
                                           placeholder="@lang('Số điện thoại')" value="{{$item['phone']}}">
                                </div>
                                <div class="phone_append">
                                    @if(count($arrPhone) > 0)
                                        @foreach($arrPhone as $k => $v)
                                            <div class="form-group m-form__group div_phone_attach">
                                                <div class="input-group">
                                                    <input type="hidden" class="number_phone"
                                                           value="{{$k + 10000}}">
                                                    <input type="text" class="form-control phone phone_attach"
                                                           placeholder="@lang('Số điện thoại')"
                                                           value="{{$v['phone']}}">
                                                    <div class="input-group-append">
                                                        <a class="btn btn-secondary" href="javascript:void(0)"
                                                           onclick="view.removePhone(this)">
                                                            <i class="la la-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <span class="error_phone_attach_{{$k + 10000}} color_red"></span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" onclick="view.addPhone()"
                                       class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                        <i class="la la-plus"></i> @lang('Thêm số điện thoại')
                                    </a>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Email'):
                                    </label>
                                    <input type="text" class="form-control m-input" id="email" name="email"
                                           placeholder="@lang('Email')" value="{{$item['email']}}">
                                </div>
                                <div class="email_append">
                                    @if(count($arrEmail) > 0)
                                        @foreach($arrEmail as $k => $v)
                                            <div class="form-group m-form__group div_email_attach">
                                                <div class="input-group">
                                                    <input type="hidden" class="number_email"
                                                           value="{{$k + 10000}}">
                                                    <input type="text" class="form-control email_attach"
                                                           value="{{$v['email']}}" placeholder="@lang('Email')">
                                                    <div class="input-group-append">
                                                        <a class="btn btn-secondary" href="javascript:void(0)"
                                                           onclick="view.removeEmail(this)">
                                                            <i class="la la-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <span class="error_email_attach_{{$k + 10000}} color_red"></span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" onclick="view.addEmail()"
                                       class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                        <i class="la la-plus"></i> @lang('Thêm email')
                                    </a>
                                </div>
                                <div class="zone-business" style="display:{{ $item['customer_type'] == 'business' ? 'block' : 'none' }}">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Website'):
                                        </label>
                                        <input type="text" class="form-control m-input" id="website" name="website" value="{{ $item['website'] }}" placeholder="@lang('Website')">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Lĩnh vực kinh doanh'):<b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select class="form-control" id="business_id" name="business_id" style="width:100%;">
                                                @if($listBussiness)
                                                    @foreach($listBussiness as $bussiness)
                                                        <option value="{{ $bussiness['id'] }}" {{ $bussiness['id'] == $item['business_id'] ? 'selected' : '' }}>{{ $bussiness['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-form__group form-group">
                                    <label class="col-form-label">@lang('Hình ảnh'):</label>
                                    <div class="form-group m-form__group m-widget19">
                                        <div class="m-widget19__pic">
                                            <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                                 src="{{$item['avatar'] != null ? $item['avatar'] : asset('static/backend/images/image-user.png')}}"
                                                 alt="Hình ảnh"/>
                                        </div>
                                        <input type="hidden" id="avatar" name="avatar">
                                        <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                               data-msg-accept="Hình ảnh không đúng định dạng"
                                               id="getFile" type='file'
                                               onchange="uploadAvatar(this);"
                                               class="form-control"
                                               style="display:none"/>
                                        <div class="m-widget19__action" style="max-width: 100%;">
                                            <a href="javascript:void(0)"
                                               onclick="document.getElementById('getFile').click()"
                                               class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    @lang('Tải ảnh lên')
                                                </span>
                                            </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-6">
                                        <label class="black_title">
                                            @lang('Tỉnh thành'):
                                        </label>
    
                                        <select name="province_id" id="province_id" class="form-control"
                                                style="width: 100%" onchange="view.changeProvince(this)">
                                            <option></option>
                                            @foreach($optionProvince as $v)
                                                <option value="{{$v['provinceid']}}"
                                                        {{$item['province_id'] == $v['provinceid'] ? 'selected': ''}}>{{$v['type'] .' '. $v['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="black_title">
                                            @lang('Quận huyện'):
                                        </label>
    
                                        <select name="district_id" id="district_id"
                                                class="form-control district" style="width: 100%">
                                            @foreach($optionDistrict as $v)
                                                <option value="{{$v['districtid']}}"
                                                        {{$item['district_id'] == $v['districtid'] ? 'selected': ''}}>{{$v['type'] .' '. $v['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Địa chỉ'):
                                    </label>
                                    <textarea class="form-control m-input" id="address" name="address"
                                           placeholder="@lang('Địa chỉ')">{{$item['address']}}</textarea>
                                </div>
                                {{-- <div class="div_business_clue"
                                     style="display:{{$item['customer_type'] == 'personal' ? 'block' : 'none'}};">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Đầu mối doanh nghiệp'):
                                        </label>
                                        <div class="input-group">
                                            <select class="form-control" id="business_clue" name="business_clue"
                                                    style="width:100%;">
                                                <option></option>
                                                @foreach($optionBusiness as $v)
                                                    <option value="{{$v['customer_lead_code']}}"
                                                            {{$v['customer_lead_code'] == $item['business_clue'] ? 'selected' : ''}}>{{$v['full_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Zalo profile'):
                                    </label>
                                    <input type="text" class="form-control m-input" id="zalo" name="zalo"
                                           placeholder="@lang('Zalo')" value="{{$item['zalo']}}">
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Fan page'):
                                    </label>
                                    <input type="text" class="form-control m-input" id="fanpage" name="fanpage"
                                           placeholder="@lang('Facebook profile')" value="{{$item['fanpage']}}">
                                </div>
                                <div class="fanpage_append">
                                    @if (count($arrFanpage) > 0)
                                        @foreach($arrFanpage as $k => $v)
                                            <div class="form-group m-form__group div_fanpage_attach">
                                                <div class="input-group">
                                                    <input type="hidden" class="number_fanpage"
                                                           value="{{$k + 10000}}">
                                                    <input type="text" class="form-control fanpage_attach"
                                                           value="{{$v['fanpage']}}"
                                                           placeholder="@lang('Fan page')">
                                                    <div class="input-group-append">
                                                        <a class="btn btn-secondary" href="javascript:void(0)"
                                                           onclick="view.removeFanpage(this)">
                                                            <i class="la la-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <span class="error_fanpage_attach_{{$k + 10000}} color_red"></span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" onclick="view.addFanpage()"
                                       class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                        <i class="la la-plus"></i> @lang('Thêm fanpage')
                                    </a>
                                </div>
                                <div class="zone-business" style="display:{{ $item['customer_type'] == 'business' ? 'block' : 'none' }}">
                                    <div class="form-group m-form__group">
                                        <div class="form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Mã số thuế'):
                                            </label>
                                            <input type="text" class="form-control m-input" id="tax_code"
                                                    name="tax_code"
                                                    placeholder="@lang('Mã số thuế')" value="{{$item['tax_code']}}">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Người đại diện'):
                                            </label>
                                            <input type="text" class="form-control m-input" id="representative"
                                                    name="representative"
                                                    placeholder="@lang('Người đại diện')"
                                                    value="{{$item['representative']}}">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Số lượng nhân sự'):
                                            </label>
                                            <input type="text" class="form-control m-input" value={{ $item['employ_qty'] }} id="employ_qty" name="employ_qty" placeholder="@lang('Số lượng nhân sự')">
                                        </div>
                                        {{-- <div class="form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Hot line'):<b class="text-danger">*</b>
                                            </label>
                                            <input type="text" class="form-control m-input" id="hotline" name="hotline"
                                                    placeholder="@lang('Hot line')" value="{{$item['hotline']}}">
                                        </div> --}}
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black-title">
                                            @lang('Ngày thành lập'):
                                        </label>
                                        <div class="input-group date_edit">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input class="form-control m-input birthday" name="birthday" id="birthday" value="{{ App\Helpers\Helper::formatDate($item['birthday']) }}"
                                                    readonly placeholder="{{__('Ngày thành lập')}}" type="text">
                                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="zone-personal" style="display:{{ $item['customer_type'] == 'personal' ? 'block' : 'none' }}">
                                    <div class="form-group m-form__group">
                                        <label class="black-title">
                                            @lang('Giới tính'):
                                        </label>
                                        <div class="m-form__group form-group">
                                            <div class="m-radio-inline">
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender"
                                                        value="male" {{$item['gender'] == 'male' ? 'checked' : ''}}> @lang('Nam')
                                                    <span class="span"></span>
                                                </label>
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender"
                                                        value="female" {{$item['gender'] == 'female' ? 'checked' : ''}}> @lang('Nữ')
                                                    <span class="span"></span>
                                                </label>
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender"
                                                        value="other" {{$item['gender'] == 'other' ? 'checked' : ''}}> @lang('Khác')
                                                    <span class="span"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black-title">
                                            @lang('Ngày sinh nhật'):
                                        </label>
                                        <div class="input-group date_edit">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input class="form-control m-input birthday" name="birthday" id="birthday" value="{{ App\Helpers\Helper::formatDate($item['birthday']) }}"
                                                    readonly placeholder="{{__('Ngày sinh nhật')}}" type="text">
                                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
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
                                                               class="form-control m-input" value="{{$item[$v['key']]}}"
                                                               maxlength="190">
                                                        @break;
                                                        @case('boolean')
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                        <input type="checkbox" class="manager-btn"
                                                                               id="{{$v['key']}}" name="{{$v['key']}}"
                                                                               value="{{$item[$v['key']] != null ? $item[$v['key']] : 0}}"
                                                                               onchange="view.changeBoolean(this)" {{$item[$v['key']] == 1 ? 'checked': ''}}>
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
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Nguồn khách hàng'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="customer_source" name="customer_source"
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($optionSource as $v)
                                                <option value="{{$v['customer_source_id']}}" {{$item['customer_source'] == $v['customer_source_id'] ? 'selected': ''}}>{{$v['customer_source_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Tag'):
                                    </label>
                                    <div>
                                        <select class="form-control" id="tag_id" name="tag_id" multiple style="width:100%;">
                                            <option></option>
                                            @foreach($optionTag as $v)
                                                <option value="{{$v['tag_id']}}" {{in_array($v['tag_id'], $arrMapTag) ? 'selected': ''}}>{{$v['name']}}</option>
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
                                                style="width:100%;" disabled>
                                            <option></option>
                                            @foreach($optionPipeline as $v)
                                                <option value="{{$v['pipeline_code']}}"
                                                        {{$item['pipeline_code'] == $v['pipeline_code'] ? 'selected': ''}}>{{$v['pipeline_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Hành trình'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="journey_code" name="journey_code"
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($optionJourney as $v)
                                                <option value="{{$v['journey_code']}}"
                                                        {{$item['journey_code'] == $v['journey_code'] ? 'selected': ''}}>{{$v['journey_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Ghi chú'):
                                    </label>
                                    <textarea type="text" id="note" class="form-control m-input" name="note" rows="5"
                                    placeholder="@lang('Ghi chú')">{{ $item['note'] }}</textarea>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Chi nhánh'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="branch_code" name="branch_code" style="width:100%;">
                                            @if($listBranch)
                                                @foreach($listBranch as $branch)
                                                    <option value="{{ $branch['branch_code'] }}" {{ $branch['branch_code'] == $item['branch_code'] ? 'selected' : '' }}>{{ $branch['branch_name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group zone-business"
                     style="display:{{$item['customer_type'] == 'business' ? 'block' : 'none'}};">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default" id="table-contact">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
                                <th class="tr_thead_list">@lang('SỐ ĐIỆN THOẠI')</th>
                                <th class="tr_thead_list">@lang('EMAIL')</th>
                                <th class="tr_thead_list">@lang('CHỨC VỤ')</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($arrContact) > 0)
                                @foreach($arrContact as $k => $v)
                                    <tr class="tr_contact">
                                        <td>
                                            <input type="hidden" class="number_contact" value="{{$k + 10000}}">
                                            <input type="text" class="form-control m-input full_name_contact"
                                                   placeholder="@lang('Họ và tên')" value="{{$v['full_name']}}">
                                            <span class="error_full_name_contact_{{$k + 10000}} color_red"></span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control m-input phone phone_contact"
                                                   placeholder="@lang('Số điện thoại')" value="{{$v['phone']}}">
                                            <span class="error_phone_contact_{{$k + 10000}} color_red"></span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control email_contact"
                                                   placeholder="@lang('Email')" value="{{$v['email']}}">
                                            <span class="error_email_contact_{{$k + 10000}} color_red"></span>
                                        </td>
                                        <td>
                                            <select class="form-control staff-title-contact" id="staff_title_id" name="staff_title_id">
                                                @if($listStaffTitle)
                                                    @foreach($listStaffTitle as $value)
                                                        <option value="{{ $value['staff_title_id'] }}" {{$value['staff_title_id'] == $v['staff_title_id'] ? 'selected' : ''}}> {{ $value['staff_title_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- <input type="text" class="form-control m-input address_contact"
                                                   placeholder="@lang('Chức vụ')" value="{{$v['staff_title_name']}}"> --}}
                                            <span class="error_address_contact_{{$k + 10000}} color_red"></span>
                                        </td>
                                        <td>
                                            <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                               href="javascript:void(0)" onclick="view.removeContact(this)">
                                                <i class="la la-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="form-group m-form__group">
                        <a href="javascript:void(0)" onclick="view.addContact({{ $listStaffTitle }})"
                           class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                            <i class="la la-plus"></i> @lang('Thêm liên hệ')
                        </a>
                    </div> --}}
                </div>
                <input type="hidden" id="customer_lead_code" value="{{$item['customer_lead_code']}}">
            
            </form>
        </div>
        <div class="modal-footer">
            <div class="m-form__actions m--align-right w-100">
                <a href="{{ route('customer-lead') }}" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                    <span>
                    <i class="la la-arrow-left"></i>
                    <span>HỦY</span>
                    </span>
                </a>
                <button type="button" onclick="edit.save({{$item['customer_lead_id']}}, false, true)"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-check"></i>
                            <span>@lang('LƯU THÔNG TIN')</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection

@section('after_script')
<script src="{{asset('static/backend/js/customer-lead/customer-lead/script.js?v='.time())}}"
    type="text/javascript"></script>
   <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script>
        let loadingCreate = false;
        listLead._init();
        @if(isset($param['id']))
            listLead.detail({{$param['id']}})
        @endif
        $('.birthday').datepicker({format: "dd/mm/yyyy"});
        $("#sale_id").select2({
            placeholder: listLead.jsonLang["Chọn nhân viên được phân bổ"],
        });
         $('#branch_code').select2({
            placeholder: listLead.jsonLang["Chi nhánh trung tâm"],
        });
        $('#business_id').select2({
            placeholder: listLead.jsonLang["Lĩnh vực kinh doanh"],
        });
        $("#province_id").select2({
            placeholder: listLead.jsonLang["Chọn tỉnh/thành"],
        });
        $("#district_id").select2({
            placeholder: listLead.jsonLang["Chọn quận/huyện"],
        });
        $("input[name='search']").select2();
        $("select[name='tag_id']").select2();
        $("select[name='customer_type']").select2();
        $("select[name='assign']").select2();

        $("select[name='customer_source']").select2();

        $("select[name='sale_id']").select2();

        $("select[name='pipeline_code']").select2();

        $("select[name='journey_code']").select2();
    </script>
    <script type="text-template" id="tpl-phone">
        <div class="form-group m-form__group div_phone_attach">
            <div class="input-group">
                <input type="hidden" class="number_phone" value="{number}">
                <input type="text" class="form-control phone phone_attach" placeholder="@lang('Số điện thoại')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removePhone(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_phone_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-email">
        <div class="form-group m-form__group div_email_attach">
            <div class="input-group">
                <input type="hidden" class="number_email" value="{number}">
                <input type="text" class="form-control email_attach" placeholder="@lang('Email')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeEmail(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_email_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-fanpage">
        <div class="form-group m-form__group div_fanpage_attach">
            <div class="input-group">
                <input type="hidden" class="number_fanpage" value="{number}">
                <input type="text" class="form-control fanpage_attach" placeholder="@lang('Fan page')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeFanpage(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_fanpage_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text/template" id="tpl-type">
        <div class="form-group m-form__group">
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Mã số thuế'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="tax_code" name="tax_code"
                       placeholder="@lang('Mã số thuế')">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Người đại diện'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="representative" name="representative"
                       placeholder="@lang('Người đại diện')">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Hot line'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="hotline" name="hotline"
                       placeholder="@lang('Hot line')">
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-contact">
        <tr class="tr_contact">
            <td>
                <input type="hidden" class="number_contact" value="{number}">
                <input type="text" class="form-control m-input full_name_contact" placeholder="@lang('Họ và tên')">
                <span class="error_full_name_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input phone phone_contact" placeholder="@lang('Số điện thoại')">
                <span class="error_phone_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control email_contact" placeholder="@lang('Email')">
                <span class="error_email_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input address_contact" placeholder="@lang('Địa chỉ')">
                <span class="error_address_contact_{number} color_red"></span>
            </td>
            <td>
                <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:void(0)" onclick="view.removeContact(this)">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop
