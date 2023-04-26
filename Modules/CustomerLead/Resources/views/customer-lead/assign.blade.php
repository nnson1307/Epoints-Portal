@extends('layout')
@section('title_header')
    <span class="title_header">@lang('PHÂN BỔ LEAD')</span>
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
                        @lang('PHÂN BỔ LEAD')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
            <div class="m-portlet__body">
                <form id="form-assign">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Phòng ban'):<b class="text-danger">*</b>
                    </label>
                    <select class="form-control" name="department" id="department" style="width:100%" multiple>
                        <option value=""></option>
                        @if(isset($optionDepartment)  && count($optionDepartment) > 0)
                            @foreach($optionDepartment as $key => $value)
                                <option value="{{$value['department_id']}}">
                                    {{__($value['department_name'])}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chọn sales'):<b class="text-danger">*</b>
                    </label>
                    <select class="form-control" name="staff" id="staff" style="width:100%" multiple>
                    </select>
                </div>
                <div class="input-group m-input-group m-input-group--solid m--margin-top-10">
                    <label class="m-checkbox m-checkbox--state-success">
                        <input type="checkbox" id="checkAllSale" onclick="assign.checkAllSale()">
                        {{__('Tất cả sales')}}
                        <span></span>
                    </label>
                </div>
                </form>
                <div class="form-group m-form__group mt-3">
                    <label class="black_title">
                        @lang('Chọn danh sách lead'):<b class="text-danger">*</b>
                    </label>
                    <div class="input-group m-input-group m-input-group--solid m--margin-top-10">
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" id="checkAllLead" onclick="assign.checkAllLead()">
                            {{__('Tất cả lead')}}
                            <span></span>
                        </label>
                    </div>
                    <div id="autotable">
                        <div class="padding_row bg">
                            <form class="frmFilter">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group m-form__group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="search"
                                                       placeholder="{{__('Nhập tên hoặc mã dịch vụ')}}">
                                            </div>
                                        </div>
                                    </div>
                                    @php $i = 0; @endphp
                                    @foreach ($FILTER as $name => $item)
                                        @if ($i > 0 && ($i % 4 == 0))
                                        @endif
                                        @php $i++; @endphp
                                        <div class="col-lg-3 form-group input-group">
                                            @if(isset($item['text']))
                                                <div class="input-group-append">
                                                <span class="input-group-text">
                                                    {{ $item['text'] }}
                                                </span>
                                                </div>
                                            @endif
                                            {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker', 'id' => $name]) !!}
                                        </div>
                                        @endforeach
                                        <div class="col-lg-3">
                                            <div class="form-group m-form__group">
                                                <select class="form-control m_selectpicker" name="pipeline_code " id="pipeline_code" style="width:100%">
                                                    <option value="">{{__('Chọn pipeline')}}</option>
                                                    @if(isset($listPipeline)  && count($listPipeline) > 0)
                                                        @foreach($listPipeline as $key => $value)
                                                            <option value="{{$key}}">
                                                                {{$value}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group m-form__group">
                                                <select class="form-control journey" name="journey_code " id="journey_code" style="width:100%">
                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group m-form__group text-right">
                                                <button class="btn btn-primary color_button btn-search">
                                                    {{__('TÌM KIẾM')}} <i
                                                            class="fa fa-search ic-search m--margin-left-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-content m--margin-top-30">
                            @include('customer-lead::customer-lead.assign-list-lead')
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('customer-lead')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="assign.submit()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('PHÂN BỔ')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        assign._init();
        // $("#pipeline").select2({
        //     placeholder: listLead.jsonLang["Chọn pipeline"],
        // });
    </script>
@endsection