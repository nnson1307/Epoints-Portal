@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ CHI PHÍ GIAO HÀNG')</span>
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
                        @lang('CHỈNH SỬA CHI PHÍ GIAO HÀNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên chi phí giao hàng'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="delivery_cost_name" name="delivery_cost_name"
                                   value="{{$item['delivery_cost_name']}}"
                                   placeholder="@lang('Nhập tên chi phí giao hàng')..."
                                   {{$item['is_system']==1?'disabled':''}} >
                            @if($item['is_system']==1)
                                <input type="hidden" name="delivery_cost_name" id="delivery_cost_name" value="{{$item['delivery_cost_name']}}">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Chi phí giao hàng'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="delivery_cost" name="delivery_cost" value="{{$item['delivery_cost']}}"
                                   placeholder="@lang('Nhập chi phí giao hàng')...">
                        </div>
                    </div>
                    @if ($item['is_system'] == 0)
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tỉnh/ Thành phố'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control province" id="province_id" name="province_id[]"
                                            multiple="multiple" style="width:100%;">
                                        @if(count($optionProvince) > 0)
                                            @foreach ($optionProvince as $value)
                                                <option value="{{$value['provinceid']}}"
                                                        {{in_array($value['provinceid'], $arrProvince) ? 'selected' : ''}}>
                                                    {{$value['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Quận/ Huyện'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control district" id="district_id" name="district_id[]"
                                            multiple="multiple"
                                            style="width:100%;">
                                        @if(isset($arrDistrict) && count($arrDistrict) > 0)
                                            @if(isset($optionDistrict))
                                                @foreach($optionDistrict as $k => $v)
                                                    @if(in_array($v['districtid'], $arrDistrict))
                                                        <option value="{{$v['districtid']}}" selected>
                                                            {{$v['name']}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-6">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm ">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" class="manager-btn is_delivery_fast pr-3" {{$item['is_delivery_fast'] == 1 ? 'checked' : ''}} name="is_delivery_fast" onchange="create.changeMethod()"> {{__('Giao hàng hoả tốc')}}
                                <span></span>
                            </label>
                        </span>
                        <div class="form-group m-form__group mt-3 block-fast-delivery" style="{{$item['is_delivery_fast'] != 1 ? 'display:none' : ''}}">
                            <label class="black_title">
                                {{__('Chi phí giao hàng hoả tốc')}}:<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input format-money"
                                   id="delivery_fast_cost" name="delivery_fast_cost" value="{{number_format($item['delivery_fast_cost'])}}"
                                   placeholder="{{__('Nhập chi phí giao hàng hoả tốc')}} ...">
                        </div>
                    </div>
                </div>
                <input type="hidden" id="is_system" value="{{$item['is_system']}}">
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('delivery-cost')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="edit.save()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" id="delivery_cost_id" name="delivery_cost_id" value="{{$item['delivery_cost_id']}}">
            <input type="hidden" id="delivery_cost_code" name="delivery_cost_code" value="{{$item['delivery_cost_code']}}">
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/delivery/delivery-cost/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        create._init();
    </script>
@endsection
