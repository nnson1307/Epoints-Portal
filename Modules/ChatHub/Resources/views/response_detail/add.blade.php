@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('chathub::response_detail.index.RESPONSE_DETAIL')</span>
@endsection
@section('after_styles')
    <link href="{{ asset('vender/select2/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vender/select2/select2.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<style>
    .modal-backdrop {
        position: relative !important;
    }
</style>

<!--begin::Portlet-->
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-open-box"></i>
                </span>
                <h2 class="m-portlet__head-text">
                    @lang('chathub::response_detail.index.ADD_RESPONSE_DETAIL')
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
        </div>
    </div>
    <form id="form">
        {!! csrf_field() !!}
        <div class="m-portlet__body" id="autotable">
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_detail.index.BRAND'):
                    </label>
                    <select name="brand[]" class="form-control m-input select2" id="brand" multiple>
                        @if (isset($brand))
                            @foreach ($brand as $item)
                                <option value="{{$item['brand_name']}}">{{$item['brand_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_detail.index.SUB_BRAND'):
                    </label>
                    <select name="sub_brand[]" class="form-control m-input select2" id="sub_brand" multiple>
                        @if (isset($sub_brand))
                            @foreach ($sub_brand as $item)
                                <option value="{{$item['sub_brand_name']}}">{{$item['sub_brand_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_detail.index.SKU'):
                    </label>
                    <select name="sku[]" class="form-control m-input select2" id="sku" multiple>
                        @if (isset($sku))
                            @foreach ($sku as $item)
                                <option value="{{$item['sku_name']}}">{{$item['sku_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_detail.index.ATTRIBUTE'):
                    </label>
                    <select name="attribute[]" class="form-control m-input select2" id="attribute" multiple>
                        @if (isset($attribute))
                            @foreach ($attribute as $item)
                                <option value="{{$item->getAttributes()['attribute_name']}}">{{$item->getAttributes()['attribute_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_detail.index.USE_TEMPLATE'):
                    </label>
                    <input type="checkbox" name="use_template" value="template" onchange="MyDisabled()">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_detail.index.CONTENT'):
                    </label>
                    <input type="text" name="response_content" class="form-control m-input" id="response_content">
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_detail.index.TEMPLATE'):
                    </label>
                    <div class="input-group">
                        <select class="form-control"  style="width: 100%" id="response_element_id" name="response_element_id" disabled>
                            {{-- <option value=""></option> --}}
                            @if (isset($response_element))
                                @foreach ($response_element as $item)
                                    <option value="{{$item['response_element_id']}}">{{$item['title']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">    
                    <a href="{{route('chathub.response_detail')}}" class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>@lang('chathub::response_detail.index.CANCEL')</span>
                        </span>
                    </a>
                    <button type="button" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10" onclick="response_detail.Add()">
                        <span>
                        <i class="la la-check"></i>
                        <span>@lang('chathub::response_detail.index.SAVE')</span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn-add m--margin-left-10" onclick="response_detail.AddNew()">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                            <span>@lang('chathub::response_detail.index.SAVE_NEW')</span>
                        </span>
                    </button>

                </div>
            </div>
        </div>
    </form>
</div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response_detail/add.js?v='.time())}}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function($) {
            $('#response_element_id').val('');
            // trigger select2 for each untriggered select2_multiple box
            $('.select2').each(function (i, obj) {
                if (!$(obj).data("select2"))
                {
                    $(obj).select2();
                }
            });
        });
    </script>
    <script>
        function MyDisabled(){
            if($('#response_element_id').attr('disabled')){
                $('#response_content').val('');
                $("#response_element_id").attr("disabled", false);
                $("#response_content").attr("disabled", true);
            }else{
                $('#response_element_id').val('');
                $("#response_element_id").attr("disabled", true);
                $("#response_content").attr("disabled", false);
            }
        }
    </script>
@stop