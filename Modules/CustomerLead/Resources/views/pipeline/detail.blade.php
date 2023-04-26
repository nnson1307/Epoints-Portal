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
                        @lang('CHI TIẾT PIPELINE')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Tên pipeline'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="pipeline_name" name="pipeline_name"
                       value="{{isset($data['pipeline_name'])?$data['pipeline_name']:''}}"
                       placeholder="@lang('Nhập tên pipeline')" disabled>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Chọn danh mục pipeline'):<b class="text-danger">*</b>
                </label>

                <input type="text " class="form-control m-input" id="pipeline_cat"
                    value="{{isset($data['pipeline_category_code'])? $data['pipeline_category_name'] :''}}" disabled>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Chủ sở hữu'):<b class="text-danger">*</b>
                </label>
                <select class="form-control" name="owner_id" id="owner_id" style="width:100%" disabled>
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
                <input type="text" class="form-control m-input" id="time_revoke_lead" name="time_revoke_lead"
                       placeholder="@lang('Nhập số ngày')" disabled
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
                                       class="manager-btn" disabled>
                                <span></span>
                            </label>
                        </span>
                </div>
            </div>
            <div class="form-group m-form__group">
                <div class="row" style="">
                    <div class="col-5"> @lang('Tên hành trình')</div>
                    <div class="col-5"> @lang('Trạng thái chuyển đổi')</div>
                    <div class="col-1"></div>
                </div>
                <div id="journey">
                    @if(isset($listJourney) && count($listJourney) > 0)
                        @foreach($listJourney as $key => $value)
                            <?php $arrJourneyStatus = explode(',',$value['journey_updated']);?>
                            <div class="row mt-2 count-journey">
                                <div class="col-5">
                                    <input type="text" class="form-control m-input journey_name"
                                           value="{{$value['journey_name']}}" disabled>
                                </div>
                                <div class="col-5">
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
                            </div>
                        @endforeach
                    @endif
                </div>
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
                    <a href="{{route('customer-lead.pipeline.edit', $data['pipeline_id'])}}"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('CHỈNH SỬA')</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_script')
    <script>
        $('.status').select2();
    </script>
@endsection