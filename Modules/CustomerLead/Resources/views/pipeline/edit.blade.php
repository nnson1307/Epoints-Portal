@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ KHÁCH HÀNG TIỀM NĂNG')</span>
@stop

@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA PIPELINE')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-create-pipeline">
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tên pipeline'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" class="form-control m-input" id="pipeline_name" name="pipeline_name"
                           value="{{isset($data['pipeline_name'])?$data['pipeline_name']:''}}"
                           placeholder="@lang('Nhập tên pipeline')">
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chọn danh mục pipeline'):<b class="text-danger">*</b>
                    </label>
                    <select class="form-control" name="pipeline_cat" id="pipeline_cat" style="width:100%" disabled>
                        @if(isset($listCategory)  && count($listCategory) > 0)
                            @foreach($listCategory as $key => $value)
                                <option value="{{$value['pipeline_category_code']}}"
                                        {{$value['pipeline_category_code'] == $data['pipeline_category_code'] ?'selected':''}}>
                                    {{$value['pipeline_category_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chủ sở hữu'):<b class="text-danger">*</b>
                    </label>
                    <select class="form-control" name="owner_id" id="owner_id" style="width:100%">
                        <option value=""></option>
                        @if(isset($listStaff)  && count($listStaff) > 0)
                            @foreach($listStaff as $key => $value)
                                <option value="{{$value['staff_id']}}"
                                        {{$value['staff_id'] == $data['owner_id'] ?'selected':''}}>
                                    {{$value['full_name']}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Thời gian tối đa để lead chuyển đổi khi được phân công (ngày)'):<b class="text-danger">*</b>
                    </label>
                    <input type="number" class="form-control m-input" id="time_revoke_lead" name="time_revoke_lead"
                           placeholder="@lang('Nhập số ngày')"
                           value="{{isset($data['time_revoke_lead'])? $data['time_revoke_lead'] :''}}">
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Thiết lập mặc định'):<b class="text-danger">*</b>
                    </label>
                    <div>
                         <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input type="checkbox" id="is_default" {{$data['is_default']==1?'checked':''}}
                                       onchange=""
                                       class="manager-btn">
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="row" style="">
                        <div class="col-4"> @lang('Tên hành trình')</div>
                        <div class="col-1"></div>
                        <div class="col-4"> @lang('Trạng thái chuyển đổi')</div>
                        <div class="col-1" {{$data['pipeline_category_code'] =='CUSTOMER' ? '' : 'hidden' }}> @lang('Có tạo deal')</div>
                        <div class="col-1" {{$data['pipeline_category_code'] !='CUSTOMER' ? '' : 'hidden' }}> @lang('Có tạo hợp đồng')</div>
                        <div class="col-1"></div>
                    </div>
                    <div id="journey">
                        <div class="append-journey sortable">
                        @if(isset($listJourney) && count($listJourney) > 0)
                            @foreach($listJourney as $key => $value)
                                <?php $arrJourneyStatus = explode(',',$value['journey_updated']);?>
                            <div class="row mt-2 count-journey">
                                <div class="col-4">
                                    <input type="text" class="form-control m-input journey_name"
                                           value="{{$value['journey_name']}}" disabled>
                                </div>

                                <div class="col-1">
                                    <label>
                                        @if($value['default_system'] == 'new')
                                            {{ __('Mới') }}
                                        @elseif($value['default_system'] == 'new')
                                            {{ __('Bắt đầu') }}
                                        @elseif($value['default_system'] == 'fail')
                                            {{ __('Thất bại') }}
                                        @elseif($value['default_system'] == 'win')
                                            {{ __('Thành công') }}
                                        @elseif($value['default_system'] == 'win')
                                            {{ __('Kết thúc') }}
                                        @endif
                                    </label>
                                </div>
                                <div class="col-4">
                                    <select class="form-control status" name="journey_status"
                                            style="width:100%" multiple="multiple" disabled>
                                        @foreach($listJourney as $key2 => $value2)
                                            @if($value['journey_name'] != $value2['journey_name'])
                                                <option value="{{$value2['journey_name']}}" {{in_array($value2['journey_id'], $arrJourneyStatus)?'selected':''}} >
                                                    {{$value2['journey_name']}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1" {{$data['pipeline_category_code'] =='CUSTOMER' ? '' : 'hidden' }}>
                                     <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input type="checkbox" id="is_deal_created"
                                                   onchange=""
                                                   {{$value['is_deal_created'] == 1 ? 'checked' : ''}}
                                                   class="manager-btn is_deal_created">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-1" {{$data['pipeline_category_code'] !='CUSTOMER' ? '' : 'hidden' }}>
                                     <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input type="checkbox" id="is_contract_created"
                                                   onchange=""
                                                   {{$value['is_contract_created'] == 1 ? 'checked' : ''}}
                                                   class="manager-btn is_contract_created">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-1 row_icon">
                                    <a href="javascript:void(0)" onclick="create.editJourney(this)"
                                       class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill edit_journey"
                                       title="@lang('Chỉnh sửa')"><i class="la la-edit"></i>
                                    </a>
                                    <i class="fa fa-sort"></i>
                                </div>
                                <input type="hidden" class="journey_code"
                                       value="{{isset($value['journey_code'])? $value['journey_code'] :''}}">
                            </div>
                                    @break
                            @endforeach
                        @endif
                        @if(isset($listJourney) && count($listJourney) > 0)
                                @foreach($listJourney as $key => $value)
                                    @if($key != 0 && $key <= (count($listJourney) - $total))
                                    <?php $arrJourneyStatus = explode(',',$value['journey_updated']);?>
                                    <div class="row mt-2 count-journey add-input">
                                        <div class="col-4">
                                            <input type="text" class="form-control m-input journey_name"
                                                   value="{{$value['journey_name']}}" disabled>
                                        </div>
                                        <div class="col-1">
                                            <label>
                                                @if($value['default_system'] == 'new')
                                                    {{ __('Mới') }}
                                                @elseif($value['default_system'] == 'new')
                                                    {{ __('Bắt đầu') }}
                                                @elseif($value['default_system'] == 'fail')
                                                    {{ __('Thất bại') }}
                                                @elseif($value['default_system'] == 'win')
                                                    {{ __('Thành công') }}
                                                @elseif($value['default_system'] == 'win')
                                                    {{ __('Kết thúc') }}
                                                @endif
                                            </label>
                                        </div>
                                        <div class="col-4">
                                            <select class="form-control status" name="journey_status"
                                                    style="width:100%" multiple="multiple" disabled>
                                                @foreach($listJourney as $key2 => $value2)
                                                    @if($value['journey_name'] != $value2['journey_name'])
                                                        <option value="{{$value2['journey_name']}}" {{in_array($value2['journey_id'], $arrJourneyStatus)?'selected':''}} >
                                                            {{$value2['journey_name']}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1" {{$data['pipeline_category_code'] =='CUSTOMER' ? '' : 'hidden' }}>
                                             <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                    <input type="checkbox" id="is_deal_created"
                                                           onchange=""
                                                           {{$value['is_deal_created'] == 1 ? 'checked' : ''}}
                                                           class="manager-btn is_deal_created">
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="col-1" {{$data['pipeline_category_code'] !='CUSTOMER' ? '' : 'hidden' }}>
                                             <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                    <input type="checkbox" id="is_contract_created"
                                                           onchange=""
                                                           {{$value['is_contract_created'] == 1 ? 'checked' : ''}}
                                                           class="manager-btn is_contract_created">
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="col-1 row_icon">
                                            <a href="javascript:void(0)" onclick="create.editJourney(this)"
                                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill edit_journey"
                                               title="@lang('Chỉnh sửa')"><i class="la la-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="edit.removeJourneyOld(this, '{{$value['journey_code']}}')"
                                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                               title="@lang('Xóa')"><i class="la la-trash"></i>
                                            </a>
                                            <i class="fa fa-sort"></i>
                                        </div>
                                        <input type="hidden" class="journey_code"
                                               value="{{isset($value['journey_code'])? $value['journey_code'] :''}}">
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        @if(isset($listJourney) && count($listJourney) > 0)
                                @foreach($listJourney as $key => $value)
                                    @if($key > (count($listJourney) - $total))
                                        <?php $arrJourneyStatus = explode(',',$value['journey_updated']);?>
                                        <div class="row mt-2 count-journey">
                                            <div class="col-4">
                                                <input type="text" class="form-control m-input journey_name"
                                                       value="{{$value['journey_name']}}" disabled>
                                            </div>
                                            <div class="col-1">
                                                <label>
                                                    @if($value['default_system'] == 'new')
                                                        {{ __('Mới') }}
                                                    @elseif($value['default_system'] == 'new')
                                                        {{ __('Bắt đầu') }}
                                                    @elseif($value['default_system'] == 'fail')
                                                        {{ __('Thất bại') }}
                                                    @elseif($value['default_system'] == 'win')
                                                        {{ __('Thành công') }}
                                                    @elseif($value['default_system'] == 'win')
                                                        {{ __('Kết thúc') }}
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="col-4">
                                                <select class="form-control status" name="journey_status"
                                                        style="width:100%" multiple="multiple" disabled>
                                                    @foreach($listJourney as $key2 => $value2)
                                                        @if($value['journey_name'] != $value2['journey_name'])
                                                            <option value="{{$value2['journey_name']}}" {{in_array($value2['journey_id'], $arrJourneyStatus)?'selected':''}} >
                                                                {{$value2['journey_name']}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-1" {{$data['pipeline_category_code'] =='CUSTOMER' ? '' : 'hidden' }}>
                                             <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                    <input type="checkbox" id="is_deal_created"
                                                           onchange=""
                                                           {{$value['is_deal_created'] == 1 ? 'checked' : ''}}
                                                           class="manager-btn is_deal_created">
                                                    <span></span>
                                                </label>
                                            </span>
                                            </div>
                                            <div class="col-1" {{$data['pipeline_category_code'] != 'CUSTOMER' ? '' : 'hidden' }}>
                                             <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                    <input type="checkbox" id="is_contract_created"
                                                           onchange=""
                                                           {{$value['is_contract_created'] == 1 ? 'checked' : ''}}
                                                           class="manager-btn is_contract_created">
                                                    <span></span>
                                                </label>
                                            </span>
                                            </div>
                                            <div class="col-1 row_icon">
                                                <a href="javascript:void(0)" onclick="create.editJourney(this)"
                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill edit_journey"
                                                   title="@lang('Chỉnh sửa')"><i class="la la-edit"></i>
                                                </a>
                                                <i class="fa fa-sort"></i>
                                            </div>
                                            <input type="hidden" class="journey_code"
                                                   value="{{isset($value['journey_code'])? $value['journey_code'] :''}}">
                                        </div>
                                    @endif
                                @endforeach

                        @endif
                        </div>
                    </div>
                    <input type="hidden" id="pipeline_code" value="{{$data['pipeline_code']}}">
                </div>
                <div>
                    <button type="button" class="btn btn-brand " id="button-add" href="javascript:void(0)"
                            onclick="create.addJourney()">
                        @lang('Thêm hành trình')</button>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('customer-lead.pipeline')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="edit.save({{$data['pipeline_id']}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('after_script')
    <script type="text/template" id="append-input">
        <div class="row mt-2 count-journey add-input">
            <input type="hidden" class="number" value="{number}">
            <div class="col-4">
                <input type="text" class="form-control m-input journey_name" name="">
                <span class="error_journey_name_{number} color_red"></span>
            </div>
            <div class="col-1"></div>
            <div class="col-4">
                <select class="form-control status" name="journey_status" style="width:100%" multiple="multiple">
                    <option></option>
                </select>
                <span class="error_status_{number} color_red"></span>
            </div>
            <div class="col-1" {hidden}>
                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input type="checkbox" id="is_deal_created"
                               onchange=""
                               class="manager-btn is_deal_created">
                        <span></span>
                    </label>
                </span>
            </div>
            <div class="col-1" {hidden_deal}>
                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input type="checkbox" id="is_contract_created"
                               onchange=""
                               class="manager-btn is_contract_created">
                        <span></span>
                    </label>
                </span>
            </div>
            <div class="col-1 row_icon">
                <a href="javascript:void(0)" onclick="create.saveJourney(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill save_journey">
                    <i class="la la-check"></i>
                </a>
                <a href="javascript:void(0)" onclick="create.removeJourney(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
                <i class="fa fa-sort"></i>
            </div>
            <input type="hidden" class="journey_code"
                   value="">
        </div>
    </script>
    <script src="{{asset('static/backend/js/customer-lead/pipeline/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="edit-row-tpl">
        <a href="javascript:void(0)" onclick="create.editJourney(this)"
           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill edit_journey">
            <i class="la la-edit"></i>
        </a>
    </script>
    <script type="text/template" id="save-row-tpl">
        <a href="javascript:void(0)" onclick="create.saveJourney(this)"
           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill save_journey">
            <i class="la la-check"></i>
        </a>
    </script>
@endsection