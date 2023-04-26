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
                        @lang('THÊM PIPELINE')
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
                    placeholder="@lang('Nhập tên pipeline')">
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chọn danh mục pipeline'):<b class="text-danger">*</b>
                    </label>
                    <select class="form-control" name="pipeline_cat" id="pipeline_cat" style="width:100%"
                        onchange="listDefaultJourney()">
                        <option value=""></option>
                        @if(isset($listCategory)  && count($listCategory) > 0)
                            @foreach($listCategory as $key => $value)
                                <option value="{{$value['pipeline_category_code']}}">
                                    {{__($value['pipeline_category_name'])}}
                                </option>
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
                                <option value="{{$value['staff_id']}}">
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
                           placeholder="@lang('Nhập số ngày')">
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Thiết lập mặc định'):<b class="text-danger">*</b>
                    </label>
                    <div>
                         <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input type="checkbox" id="is_default"
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
                        <div class="col-1 deal-created"> @lang('Có tạo deal')</div>
                        <div class="col-1 contract-created"> @lang('Có tạo hợp đồng')</div>
                        <div class="col-1"></div>
                    </div>
                    {{--         START JOURNEY           --}}
                    <div id="journey">

                    </div>
                    {{--         END JOURNEY           --}}
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
                        <button type="button" onclick="create.save()"
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