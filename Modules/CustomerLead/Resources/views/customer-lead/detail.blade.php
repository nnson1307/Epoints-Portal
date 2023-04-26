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
                        @lang("CHI TIẾT KHÁCH HÀNG TIỀM NĂNG")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('customer-lead') }}" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide"
                   style="color: #FFFFFF">
                    <span>
                    <i class="la la-arrow-left"></i>
                    <span>{{__('Thoát')}}</span>
                    </span>
                </a>

                <a href="{{route('customer-lead.edit-lead', $item['customer_lead_id'])}}"
                   class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m--margin-left-5"
                   style="color: #FFFFFF">
                    @lang('CẬP NHẬT')
                </a>
                {{-- <button type="button" onclick=""
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-5 text-uppercase">
                    <span>
                        <span>@lang('CẬP NHẬT THẤT BẠI')</span>
                    </span>
                </button> --}}
                @if (isset($item['is_convert']) && $item['is_convert'] == 0)
                    <button type="button" onclick="detail.convertCustomer({{$item['customer_lead_id']}}, 0)"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-5 text-uppercase">
                        <span>
                            <span>@lang('CHUYỂN ĐỔI KHÁCH HÀNG')</span>
                    </span>
                    </button>
                    <button type="button" onclick="detail.convertCustomer({{$item['customer_lead_id']}}, 1)"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-5">
                        <span>
                            <span>@lang('THÊM CƠ HỘI BÁN HÀNG')</span>
                    </span>
                    </button>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="customer-lead-info">
                        <div class="row">
                            <div class="col-sm-4">
                                <p>{{ $item['customer_type'] == 'personal' ? __("Cá nhân") : __("Doanh nghiệp") }} -
                                    <b>{{ $item['full_name'] }}</b></p>
                                <p>{{ $item['phone'] }}</p>
                                <p>{{ $item['email'] }}</p>
                                <p class="mb-0 text-bold">{{ $item['assign_by'] }}</p>
                            </div>
                            <div class="col-sm-4">
                                <p>@lang('Ngày Phân Bổ'): <span
                                            class="text-bold">{{ App\Helpers\Helper::formatDate($item['allocation_date']) }}</span>
                                </p>
                                <p>@lang('Tương tác gần nhất'):
                                    <span class="text-bold">
                                        @php
                                            $date_last_care = '';
                                            if($item['date_last_care']){
                                                $date_last_care = App\Helpers\Helper::formatDate($item['date_last_care']);
                                                $date_last_care .= '('.App\Helpers\Helper::getAgoTime($item['date_last_care']).')';
                                            }
                                        @endphp
                                        {{ $date_last_care }}
                                    </span>
                                </p>
                                <p>@lang('Ngày hết hạn'): <span class="text-bold">{{ App\Helpers\Helper::formatDate($item['date_revoke']) }} ({{ App\Helpers\Helper::getAgoTime($item['date_revoke']) }})</span>
                                </p>
                                <p>@lang('Số lượng chăm sóc quá hạn'):</p>
                            </div>
                            <div class="col-sm-4">
                                <p>@lang('Ghi chú gần nhất'): {{ $item['note'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- -->
                        <div class="mt-3 d-flex">
                            <h5 class="text-uppercase text-bold mr-2">{{ $item['pipeline_name'] }}</h5>
                            <span> - {{ $item['assign_by'] }}</span>
                        </div>
                        <?php $i = 0; ?>
                        @foreach($listJourney as $v)
                            @if($v['journey_code'] == $item['journey_code'])
                                @break;
                            @else
                                    <?php $i++; ?>
                            @endif
                        @endforeach
                        <ol class="stepBar step{{count($listJourney)}}">
                            @foreach($listJourney as $key => $value)
                                <li class="step {{$key <= $i ? 'current': ''}}">
                                    {{$value['journey_name']}}
                                </li>
                            @endforeach
                        </ol>

                        {{-- <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="black_title">
                                        @lang('Họ & tên'):<b class="text-danger">*</b>
                                    </label>
                                    <input type="text" class="form-control m-input" id="full_name"
                                           name="full_name"
                                           placeholder="@lang('Họ và tên')" value="{{$item['full_name']}}"
                                           disabled>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Số điện thoại'):<b class="text-danger">*</b>
                                    </label>
                                    <input type="text" class="form-control m-input phone" id="phone"
                                           name="phone"
                                           placeholder="@lang('Số điện thoại')"
                                           value="{{$item['phone']}}"
                                           disabled>
                                </div>
                                <div class="form-group">
                                    <label class="black_title">
                                        @lang('Nguồn khách hàng'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="customer_source"
                                                name="customer_source"
                                                style="width:100%;" disabled>
                                            <option></option>
                                            @foreach($optionSource as $v)
                                                <option value="{{$v['customer_source_id']}}" {{$item['customer_source'] == $v['customer_source_id'] ? 'selected': ''}}>{{$v['customer_source_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Loại khách hàng'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="customer_type" name="customer_type"
                                                style="width:100%;" disabled>
                                            <option></option>
                                            <option value="personal" {{$item['customer_type'] == 'personal' ? 'selected' : ''}}>@lang('Cá nhân')</option>
                                            <option value="business" {{$item['customer_type'] == 'business' ? 'selected' : ''}}>@lang('Doanh nghiệp')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
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
                                <div class="form-group">
                                    <label class="black_title">
                                        @lang('Hành trình'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="journey_code" name="journey_code"
                                                style="width:100%;" disabled>
                                            <option></option>
                                            @foreach($optionJourney as $v)
                                                <option value="{{$v['journey_code']}}"
                                                        {{$item['journey_code'] == $v['journey_code'] ? 'selected': ''}}>{{$v['journey_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        {{-- @dd($historyWork) --}}
                    </div>
                    <div class="tab-content mt-3">
                        <ul id="customer-lead-detail" class="nav nav-tabs nav-pills" role="tablist"
                            style="margin-bottom: 0;">
                            <li class="nav-item">
                                <a class="nav-link active son" data-toggle="tab" show
                                   onclick="detail.changeTab('info')">@lang("THÔNG TIN CHUNG")</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link son" data-toggle="tab"
                                   onclick="detail.changeTab('deal')">@lang("Cơ hội bán hàng") ({{ count($dataDeal) }}
                                    )</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link son" data-toggle="tab"
                                   onclick="detail.changeTab('care')">@lang("CHĂM SÓC KHÁCH HÀNG")
                                    ({{ count($historyWork) }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link son" data-toggle="tab"
                                   onclick="detail.changeTab('note')">@lang("GHI CHÚ") ({{ count($listNotes) }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link son text-uppercase" data-toggle="tab"
                                   onclick="detail.changeTab('file')">@lang("Tập tin") ({{ count($listFiles) }})</a>
                            </li>
                            @if($item['customer_type'] == 'business')
                                <li class="nav-item">
                                    <a class="nav-link son text-uppercase" data-toggle="tab"
                                       onclick="detail.changeTab('contact')">@lang("Liên hệ") ({{ count($arrContact) }}
                                        )</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link son" data-toggle="tab"
                                   onclick="detail.changeTab('comment')">@lang("BÌNH LUẬN")</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="div-info" style="display: block">
                                <div class="m-demo__preview">
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Loại khách hàng'):</div>
                                                <div class="col-lg-8">{{ $item['customer_type'] == 'personal' ? __("Cá nhân") : __("Doanh nghiệp") }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Tên khách hàng'):</div>
                                                <div class="col-lg-8">{{ $item['full_name'] }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Số điện thoại') / hotline:</div>
                                                <div class="col-lg-8">{{ $item['phone'] }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-4">Email:</div>
                                                <div class="col-lg-8">{{ $item['email'] }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Địa chỉ') :</div>
                                                <div class="col-lg-8">{{ $item['address'] }}</div>
                                            </div>

                                            @if($item['customer_type'] == 'business')
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">@lang('Website') :</div>
                                                    <div class="col-lg-8">{{ $item['website'] }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">@lang('Lĩnh vực kinh doanh') :</div>
                                                    <div class="col-lg-8">{{ $item['business_name'] }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">@lang('Mã số thuế') :</div>
                                                    <div class="col-lg-8">{{ $item['tax_code'] }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">@lang('Số lượng nhân viên') :</div>
                                                    <div class="col-lg-8">{{ $item['employ_qty'] }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">@lang('Người đại diện') :</div>
                                                    <div class="col-lg-8">{{ $item['representative'] }}</div>
                                                </div>
                                            @else
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">@lang('Giới tính') :</div>
                                                    <div class="col-lg-8">{{ $item['gender'] == 'male' ? __('Nam') : __('Nữ') }}</div>
                                                </div>
                                            @endif
                                            <div class="row mb-2">
                                                <div class="col-lg-4">
                                                    @if($item['customer_type'] == 'business')
                                                        @lang('Ngày thành lập')
                                                    @else
                                                        @lang('Ngày sinh')
                                                    @endif
                                                </div>
                                                <div class="col-lg-8">{{ $item['birthday'] ? App\Helpers\Helper::formatDate($item['birthday']) : __('Chưa xác định') }}</div>
                                            </div>
                                            @if($item['customer_type'] == 'personal')
                                                <div class="row">
                                                    <div class="m-form__group form-group col-lg-4">
                                                        <label class="col-form-label pt-0">@lang('Hình ảnh'):</label>
                                                        <div class="form-group m-form__group m-widget19">
                                                            <div class="m-widget19__pic">
                                                                <img class="m--bg-metal  m-image  img-sd" id="blah"
                                                                     height="150px"
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
                                                            <div class="m-widget19__action"
                                                                 style="max-width: 100%;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2"></div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Nguồn'):</div>
                                                <div class="col-lg-8">{{ $item['source_name'] }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Tag'):</div>
                                                <div class="col-lg-8">
                                                    <div class="tag-lists">
                                                        @foreach($optionTag as $v)
                                                            @if(in_array($v['tag_id'], $arrMapTag))
                                                                <div class="item">{{ $v['name'] }}</div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Zalo'):</div>
                                                <div class="col-lg-8">{{ $item['zalo'] }}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Fan page'):</div>
                                                <div class="col-lg-8">{{ $item['fanpage'] }}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Thời gian tạo'):</div>
                                                <div class="col-lg-8">{{ App\Helpers\Helper::formatDateTime($item['created_at']) }}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Thời gian cập nhật'):</div>
                                                <div class="col-lg-8">{{ App\Helpers\Helper::formatDateTime($item['updated_at']) }}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Ngày Phân Bổ'):</div>
                                                <div class="col-lg-8">{{ App\Helpers\Helper::formatDateTime($item['allocation_date']) }}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-lg-4">@lang('Ngày hết hạn'):</div>
                                                <div class="col-lg-8">{{ App\Helpers\Helper::formatDateTime($item['date_revoke']) }}</div>
                                            </div>

                                            @if($item['customer_type'] == 'business')
                                                <div class="row">
                                                    <div class="m-form__group form-group col-lg-4">
                                                        <label class="col-form-label pt-0">@lang('Hình ảnh'):</label>
                                                        <div class="form-group m-form__group m-widget19">
                                                            <div class="m-widget19__pic">
                                                                <img class="m--bg-metal  m-image  img-sd" id="blah"
                                                                     height="150px"
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
                                                            <div class="m-widget19__action"
                                                                 style="max-width: 100%;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2"></div>
                                                </div>
                                            @endif

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
                                                                        <input type="text" id="{{$v['key']}}"
                                                                               name="{{$v['key']}}"
                                                                               class="form-control m-input"
                                                                               disabled value="{{$item[$v['key']]}}"
                                                                               maxlength="190">
                                                                        @break;
                                                                    @case('boolean')
                                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                        <input type="checkbox" class="manager-btn"
                                                                               disabled
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
                                    </div>
                                </div>
                            </div>
                            <div id="div-deal" style="display: none">
                                <div class="m-demo__preview">
                                    <div class="form-group">
                                        <div style="width: 100%; height: 300px;">
                                            <div id="autotable-deal">
                                                <form class="frmFilter">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control"
                                                               name="customer_lead_code"
                                                               value="{{$item['customer_lead_code']}}">
                                                    </div>
                                                </form>
                                                <div class="table-content mt-3" id="div-deal-list">
                                                    @include('customer-lead::customer-lead.list-deal')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="div-support" style="display: none">
                                <div class="m-demo__preview mt-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <form id="form-search-support" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <input type="text" name="manage_work_title" class="form-control"
                                                               placeholder="Nhập tiêu đề công việc">
                                                    </div>
                                                    <div class="col-3">
                                                        <select class="form-control selectForm"
                                                                name="manage_type_work_id">
                                                            <option value="">Loại công việc</option>
                                                            @foreach($listTypeWork as $itemSelect)
                                                                <option value="{{$itemSelect['manage_type_work_id']}}">{{$itemSelect['manage_type_work_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <select class="form-control selectForm" name="manage_status_id">
                                                            <option value="">Trạng thái</option>
                                                            @foreach($listStatusWork as $itemSelect)
                                                                @if(!in_array($itemSelect['manage_status_id'],[6,7]))
                                                                    <option value="{{$itemSelect['manage_status_id']}}">{{$itemSelect['manage_status_name']}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3 mt-2">
                                                        <select class="form-control selectForm" name="processor_id">
                                                            <option value="">Người thực hiện</option>
                                                            @foreach($liststaff as $itemSelect)
                                                                <option value="{{$itemSelect['staff_id']}}">{{$itemSelect['full_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3 mt-2">
                                                        <div class="m-input-icon m-input-icon--right">
                                                            <input type="text" class="form-control searchDateForm"
                                                                   name="date_end" placeholder="Ngày hết hạn">
                                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                        <span><i class="la la-calendar"></i></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-3 mt-2">
                                                        <button type="button"
                                                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide"
                                                                onclick="Work.removeSearchWork()">
                                                            <span class="ss--text-btn-mobi">
                                                                <span>Xoá</span>
                                                            </span>
                                                        </button>
                                                        <button type="button" onclick="Work.search()"
                                                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                            TÌM KIẾM
                                                            <i class="fa fa-search ss--icon-search"
                                                               style="vertical-align:initial"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="type_search" value="support">
                                                <input type="hidden" name="customer_id"
                                                       value="{{$item['customer_lead_id']}}">
                                                <input type="hidden" name="manage_work_customer_type" value="lead">
                                                <input type="hidden" name="page" id="page_support" value="1">
                                            </form>
                                        </div>
                                        <div class="col-12 list-table-work">
                                            @include('customer-lead::append.append-list-work-child')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="div-care" style="display: none">
                                <div class="m-demo__preview">
                                    <div class="row">
                                        <div class="col-12">
                                            <form id="form-search-history" autocomplete="off">
                                                <div class="padding_row row">
                                                    <div class="col-lg-3">
                                                        <input type="text" name="manage_work_title" class="form-control"
                                                               placeholder="Nhập tiêu đề công việc">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <select class="form-control selectForm"
                                                                name="manage_type_work_id">
                                                            <option value="">Loại công việc</option>
                                                            @foreach($listTypeWork as $itemSelect)
                                                                <option value="{{$itemSelect['manage_type_work_id']}}">{{$itemSelect['manage_type_work_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <select class="form-control selectForm" name="manage_status_id">
                                                            <option value="">Trạng thái</option>
                                                            @foreach($listStatusWork as $itemSelect)
                                                                @if(in_array($itemSelect['manage_status_id'],[6,7]))
                                                                    <option value="{{$itemSelect['manage_status_id']}}">{{$itemSelect['manage_status_name']}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <select class="form-control selectForm" name="processor_id">
                                                            <option value="">Người thực hiện</option>
                                                            @foreach($liststaff as $itemSelect)
                                                                <option value="{{$itemSelect['staff_id']}}">{{$itemSelect['full_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="row padding_row">
                                                    <div class="col-lg-3">
                                                        <div class="m-input-icon m-input-icon--right">
                                                            <input type="text" class="form-control searchDateForm"
                                                                   name="date_end" placeholder="Ngày hết hạn">
                                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                        <span><i class="la la-calendar"></i></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <button type="button"
                                                                class="btn btn-refresh btn-metal m-btn--icon mr-3"
                                                                onclick="Work.removeSearchWorkHistory()">
                                                            <span class="fa fa-eraser">
                                                                <span>Xoá</span>
                                                            </span>

                                                        </button>
                                                        <button type="button" onclick="Work.searchHistory()"
                                                                class="btn btn-primary color_button btn-search">
                                                            TÌM KIẾM
                                                            <i class="fa fa-search ss--icon-search"
                                                               style="vertical-align:initial"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="type_search" value="history">
                                                <input type="hidden" name="customer_id"
                                                       value="{{$item['customer_lead_id']}}">
                                                <input type="hidden" name="manage_work_customer_type" value="lead">
                                                <input type="hidden" name="page" id="page_history" value="1">
                                                <input type="hidden" id="customer_lead_id" name="customer_lead_id"
                                                       value="{{$item['customer_lead_id']}}">
                                            </form>
                                        </div>
                                        <div class="col-12 mt-2 text-right">
                                            <button type="button" id="btn-customer-care"
                                                    data-id="{{ $item['customer_lead_id'] }}"
                                                    class="p-2 btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-5">
                                                <span>
                                                    <span>@lang('CHĂM SÓC KHÁCH HÀNG')</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="col-12 list-table-work-history">
                                            @include('customer-lead::append.append-list-history-work-child')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="div-note" style="display: none">
                                <div class="row mt-3">
                                    <div class="col-12 text-right">
                                        <button type="button" data-toggle="modal" data-target="#modal-add-note"
                                                class="p-2 btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-5">
                                            <span>
                                                <span>@lang('THÊM GHI CHÚ')</span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="col-12 list-table-work-history">
                                        @include('customer-lead::append.append-list-note')
                                    </div>
                                </div>
                            </div>
                            <div id="div-file" style="display: none">
                                <div class="row mt-3">
                                    <div class="col-12 text-right">
                                        <a href="#" id="btn-show-add-file"
                                           class="p-2 btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-5">
                                            <span>
                                                <span>@lang('THÊM FILE')</span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="col-12 list-table-work-history">
                                        @include('customer-lead::append.append-list-file')
                                    </div>
                                </div>
                            </div>
                            @if($item['customer_type'] == 'business')
                                <div id="div-contact" style="display: none">
                                    <div class="row mt-3">
                                        <div class="col-12 text-right">
                                            <button type="button" data-toggle="modal" data-target="#modal-add-contact"
                                                    class="p-2 btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-5 text-uppercase">
                                                <span>
                                                    <span>@lang('THÊM LIÊN HỆ')</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="col-12 list-table-contact">
                                            @include('customer-lead::append.append-list-contact')
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    @endif
                    <div id="div-comment" style="display: none">
                    </div>
                </div>
            </div>

            <input type="hidden" id="customer_lead_code" value="{{$item['customer_lead_code']}}">
        </div>
    </div>
    </div>
    </div>
    <div id="zone-popup-show"></div>
    <div id="my-modal"></div>
@endsection

@section('after_script')
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/customer-comment.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/work.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        function registerSummernote(element, placeholder, max, callbackMax) {
            $('.description').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadImgCk(files[i]);
                        }
                    },
                    onKeydown: function (e) {
                        var t = e.currentTarget.innerText;
                        if (t.length >= max) {
                            //delete key
                            if (e.keyCode != 8)
                                e.preventDefault();
                            // add other keys ...
                        }
                    },
                    onKeyup: function (e) {
                        var t = e.currentTarget.innerText;
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    },
                    onPaste: function (e) {
                        var t = e.currentTarget.innerText;
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        // var all = t + bufferText;
                        var all = bufferText;
                        document.execCommand('insertText', false, all.trim().substring(0, max - t.length));
                        // document.execCommand('insertText', false, bufferText);
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    }
                },
            });
        }
    </script>

    <script>
        $('#staff_title_id').select2();
        $('#modal-add-note').on('hidden.bs.modal', function () {
            $('#content-note').val('');
        });

        $("button[data-dismiss=modal]").click(function () {
            $(this).closest(".modal").modal('hide');
        });

        $(document).on('click', '#btn-customer-care', function () {
            let customerLeadId = $(this).data('id');
            listLead.popupCustomerCare(customerLeadId);
        });

        $(document).on('click', '#btn-show-add-file', function () {
            $.ajax({
                method: 'POST',
                url: '{{ route('customer-lead.popup-add-file') }}',
                data: {customer_lead_id: '{{$item['customer_lead_id']}}'},
                success: function (response) {
                    if (response.error == 0) {
                        $('#zone-popup-show').html(response.data);
                        $('#modal-add-file').modal('show');
                        loadingCreate = false;
                    }
                }
            });
        });

        $(document).on('click', '.edit-file', function () {
            let fileId = $(this).data('id');

            $.ajax({
                method: 'POST',
                url: '{{ route('customer-lead.show-edit-file') }}',
                data: {fileId: fileId, customer_lead_id: '{{$item['customer_lead_id']}}'},
                success: function (response) {
                    if (response.error == 0) {
                        $('#zone-popup-show').html(response.data);
                        $('#modal-edit-file').modal('show');
                        loadingCreate = false;
                    }
                }
            });
        });

        $(document).on('change', '#files', function () {
            $('.error-file-name').html('');
            let self = $(this);
            let file = this.files[0];
            let filename = file.name;
            console.log({filename});

            var formData = new FormData();
            formData.append('file', file);
            formData.append('link', '_customerlead.');

            if (loadingCreate == false) {
                loadingCreate = true;
                $.ajax({
                    method: 'POST',
                    url: '{{ route('admin.upload-image') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function (response) {
                        console.log({response});
                        if (response.error == 0) {
                            setTimeout(() => {
                                self.closest('.form-group').find('.upload-file-name').html(filename);
                                self.closest('.frm-add-file').find('.full-path').val(response.file);
                            }, 1000);
                            loadingCreate = false;
                        }
                    }
                });
            }
        });

        $(document).on('click', '#btn-add-file', function (e) {
            e.preventDefault();
            $('.error-file-name').html('');
            let customerLeadId = '{{ $item['customer_lead_id'] }}';
            let content = $('#file-note').val();
            let submit_type = $('.submit_type').val();
            let customer_lead_file_id = $('.customer_lead_file_id').val();
            let fullPath = $('.full-path').val();

            if (fullPath == '' && submit_type != 'update') {
                $('.error-file-name').html('Bạn chưa chọn file');
            } else {

                let data = {
                    content,
                    file_name: $('.upload-file-name').text(),
                    submit_type: submit_type,
                    customer_lead_id: customerLeadId,
                    full_path: fullPath
                }

                if (customer_lead_file_id) {
                    data.customer_lead_file_id = customer_lead_file_id;
                }

                if (loadingCreate == false) {
                    loadingCreate = true;
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('customer-lead.add-file') }}',
                        data: data,
                        success: function (response) {
                            if (response.error == 0) {
                                swal(response.message, "", "success").then(function (result) {
                                    $('#modal-add-file').modal('hide');
                                    $('#modal-edit-file').modal('hide');
                                    listLead.detail(customerLeadId);
                                });

                                loadingCreate = false;
                            }
                        }
                    });
                }
            }
        });

        $(document).on('click', '#btn-add-note', function (e) {
            e.preventDefault();
            $('.error-note').html('');
            let customerLeadId = '{{ $item['customer_lead_id'] }}';
            let content = $('#content-note').val();

            if (!content) {
                $('.error-note').html('Bạn chưa nhập ghi chú');
            } else {
                if (loadingCreate == false) {
                    loadingCreate = true;
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('customer-lead.add-note') }}',
                        data: $('#frm-add-note').serialize(),
                        success: function (response) {
                            if (response.error == 0) {
                                swal(response.message, "", "success").then(function (result) {
                                    $('#modal-add-note').modal('hide');
                                    listLead.detail(customerLeadId);
                                });

                                loadingCreate = false;
                            }
                        }
                    });
                }
            }
        });

        $(document).on('click', '#btn-add-contact', function (e) {
            e.preventDefault();
            $('.error-full-name').html('');
            $('.error-phone').html('');
            let customerLeadId = '{{ $item['customer_lead_id'] }}';
            let full_name = $('#full_name').val();
            let phone = $('#phone').val();
            let validate = true;

            if (!full_name) {
                $('.error-full-name').html('Bạn chưa nhập họ tên');
                validate = false;
            }

            if (!phone) {
                $('.error-phone').html('Bạn chưa nhập số điện thoại');
                validate = false;
            }

            if (validate) {
                if (loadingCreate == false) {
                    loadingCreate = true;
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('customer-lead.add-contact') }}',
                        data: $('#frm-add-contact').serialize(),
                        success: function (response) {
                            if (response.error == 0) {
                                swal(response.message, "", "success").then(function (result) {
                                    $('#modal-add-contact').modal('hide');
                                    listLead.detail(customerLeadId);
                                });

                                loadingCreate = false;
                            }
                        }
                    });
                }
            }
        });

        $(document).on('click', '#act-customer-edit', function () {
            let customerLeadId = $(this).data('id');
            edit.popupEdit(customerLeadId, true);
        });

        function registerSummernote(element, placeholder, max, callbackMax) {
            $('.description').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadImgCk(files[i]);
                        }
                    },
                    onKeydown: function (e) {
                        var t = e.currentTarget.innerText;
                        if (t.length >= max) {
                            //delete key
                            if (e.keyCode != 8)
                                e.preventDefault();
                            // add other keys ...
                        }
                    },
                    onKeyup: function (e) {
                        var t = e.currentTarget.innerText;
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    },
                    onPaste: function (e) {
                        var t = e.currentTarget.innerText;
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        // var all = t + bufferText;
                        var all = bufferText;
                        document.execCommand('insertText', false, all.trim().substring(0, max - t.length));
                        // document.execCommand('insertText', false, bufferText);
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    }
                },
            });
        }
    </script>
    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="detail.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>

                <input type="hidden" class="object_id" name="object_id">
                <input type="hidden" class="stt_row" value="{stt}">
            </td>
            <td style="width:25% !important;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="detail.changeObject(this)">
                    <option></option>
                </select>
                <span class="error_object color_red"></span>
            </td>
            <td class="td_object_price_{stt}">
                <input type="text" class="form-control m-input object_price" name="object_price"
                       style="background-color: white;"
                       id="object_price_{stt}" value="">
            </td>
            <td style="width: 145px !important;">
                <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity"
                       id="object_quantity_{stt}" style="text-align: center; height: 30px !important" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_discount" name="object_discount"
                       id="object_discount_{stt}" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_amount" name="object_amount"
                       style="background-color: white;"
                       id="object_amount_{stt}" value="" readonly>
            </td>

            <td>
                <a href="javascript:void(0)" onclick="detail.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-object-price">
        <input type="text" class="form-control m-input object_price" name="object_price"
               style="background-color: white;"
               id="object_price_{stt}" value="{price}">
    </script>

    <script type="text-template" id="tpl-data-error">
        <input type="hidden" name="full_name[]" value="{full_name}">
        <input type="hidden" name="phone[]" value="{phone}">
        <input type="hidden" name="phone_attack[]" value="{phone_attack}">
        <input type="hidden" name="birthday[]" value="{birthday}">
        <input type="hidden" name="province_name[]" value="{province_name}">
        <input type="hidden" name="district_name[]" value="{district_name}">
        <input type="hidden" name="gender[]" value="{gender}">
        <input type="hidden" name="email[]" value="{email}">
        <input type="hidden" name="email_attach[]" value="{email_attach}">
        <input type="hidden" name="address[]" value="{address}">
        <input type="hidden" name="customer_type[]" value="{customer_type}">
        <input type="hidden" name="pipeline[]" value="{pipeline}">
        <input type="hidden" name="customer_source[]" value="{customer_source}">
        <input type="hidden" name="business_clue[]" value="{business_clue}">
        <input type="hidden" name="fanpage[]" value="{fanpage}">
        <input type="hidden" name="fanpage_attack[]" value="{fanpage_attack}">
        <input type="hidden" name="zalo[]" value="{zalo}">
        <input type="hidden" name="tag[]" value="{tag}">
        <input type="hidden" name="sale_id[]" value="{sale_id}">
        <input type="hidden" name="tax_code[]" value="{tax_code}">
        <input type="hidden" name="representative[]" value="{representative}">
        <input type="hidden" name="hotline[]" value="{hotline}">
        <input type="hidden" name="error[]" value="{error}">
    </script>
@stop
