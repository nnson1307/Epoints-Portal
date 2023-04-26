@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;">{{ __('Cấu hình hiển thị') }}</span>
@stop
@section('after_style')
    <style>
        .item__center {
            text-align: center;
        }

        .kt-avatar__holder {
            width: 200px !important;
            height: 200px !important;
        }

        label .required {
            color: red !important;
        }

        .label-color {
            color: black;
            font-weight: 500;
            font-size: 14px !important;
        }

        .block_status {
            align-items: center;
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: auto;
        }

        .block_status .status {
            margin-top: 5px;
        }

        .block_position {
            display: flex;
            align-items: center;
            padding: 10px;
            width: 150px;
            border-radius: 4px;
            border: 1px #C0C0C0 solid;
            justify-content: center;
            gap: 40px;
        }

        .block_position i {
            font-size: 18px !important;
            cursor: pointer;
        }

        .block_position span {
            font-size: 18px !important;
        }

        .block_destination {
            display: flex;
            flex-direction: column-reverse;
        }

        .form-control-feedback {
            margin-top: 5px !important;
            font-size: 12px;
            font-style: italic;
            font-weight: 400;
        }

        #position-error {
            display: none !important;
        }
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ __('Chi tiết banner') }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile">
            <form id="form-data" action="" style="margin-bottom:100px" method="POST">
                <div class="m-portlet__body mt-5">
                    <div class="form-group row">
                        <label class="col-xl-2 col-lg-2 col-form-label label-color">
                            {{ __('Ảnh banner :') }} <span class="required">*</span>
                        </label>
                        <div class="col-lg-10 col-xl-10">
                            <div class="kt-avatar kt-avatar--outline" id="kt_user_add_avatar">
                                <div id="image-config-banner">
                                    <div class="kt-avatar__holder"
                                        style="background-image: url('{{ $itemConfigDisplayDetail->image }}');
                                                         background-position: center;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-2 col-lg-2 col-form-label label-color">
                            {{ __('Tiêu đề chính') }} <span class="required">*</span>
                        </label>
                        <div class="col-lg-10 col-xl-10">
                            <input class="form-control" id="main_title" disabled
                                value="{{ $itemConfigDisplayDetail->main_title }}" name="main_title" type="text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-2 col-lg-2 col-form-label label-color">
                            {{ __('Tiêu đề phụ') }}
                        </label>
                        <div class="col-lg-10 col-xl-10">
                            <input class="form-control" id="sub_title" disabled
                                value="{{ $itemConfigDisplayDetail->sub_title }}" name="sub_title" type="text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-2 col-lg-2 col-form-label label-color">
                            {{ __('Tên hành động') }} <span class="required">*</span>
                        </label>
                        <div class="col-lg-10 col-xl-10">
                            <input class="form-control" disabled value="{{ $itemConfigDisplayDetail->action_name }}"
                                id="action_name" name="action_name" type="text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-2 col-lg-2 col-form-label label-color">
                            {{ __('Đích đến') }} <span class="required">*</span>
                        </label>
                        <div class="col-lg-10 col-xl-10 block_destination">
                            <select type="text" disabled onchange="configDisplayDetail.loadDestination(this)"
                                name="destination" id="destination" class="form-control ss--select-2" style="width: 100%">
                                <option value="">{{ __('Chọn đích đến') }}</option>
                                @foreach ($configCategoryDetail as $item)
                                    <option
                                        {{ $item->key_destination == $itemConfigDisplayDetail->categoryDestination->key_destination ? 'selected' : '' }}
                                        value="{{ $item->key_destination }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-2 col-lg-2 col-form-label label-color">
                            {{ __('Đích đến chi tiết') }} <span class="required">*</span>
                        </label>
                        <div class="col-lg-10 col-xl-10">
                            <select type="text" name="destination_detail" disabled id="destination_detail"
                                class="form-control ss--select-2" style="width: 100%">
                                <option value="">{{ __('Chọn đích đến chi tiết') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" style="justify-content:space-between; align-items: center;">
                        <label class="col-xl-2 col-lg-2 col-form-label label-color">
                            {{ __('Thứ tự hiển thị') }}
                        </label>

                        <div class="postion col-lg-4 col-xl-4">
                            <div class="block_position">
                                <input type="number" hidden id="position"
                                    value="{{ !empty($itemConfigDisplayDetail->position) ? $itemConfigDisplayDetail->position : $positionMaxConfigDisplayDetail }}">
                                <i class="fa fa-plus-circle plus" disabled onclick="configDisplayDetail.increment()"></i>
                                <span
                                    id="text_position">{{ !empty($itemConfigDisplayDetail->position) ? $itemConfigDisplayDetail->position : $positionMaxConfigDisplayDetail }}</span>
                                <i class="fas fa-minus-circle" disabled onclick="configDisplayDetail.decrement()"></i>
                            </div>
                        </div>
                        <div class="status col-lg-6 col-xl-6 block_status">
                            <label class="label-color">
                                {{ __('Trạng thái :') }}
                            </label>
                            <div>
                                <div class="status">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input type="checkbox" disabled
                                                onchange="configDisplayDetail.togglePosition(this)" id="status"
                                                {{ $itemConfigDisplayDetail->status == 1 ? 'checked' : '' }}
                                                name="status">
                                            <span></span>
                                        </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@php
$itemSurvey = $itemConfigDisplayDetail->categoryDestination->key_destination == 'survey' ? $itemConfigDisplayDetail->survey->survey_id : '';
$itemPromotion = $itemConfigDisplayDetail->categoryDestination->key_destination == 'promotion' ? $itemConfigDisplayDetail->promotion->promotion_id : '';
$itemProduct = $itemConfigDisplayDetail->categoryDestination->key_destination == 'product_detail' ? $itemConfigDisplayDetail->product->product_id : '';
$itemPost = $itemConfigDisplayDetail->categoryDestination->key_destination == 'post_detail' ? $itemConfigDisplayDetail->post->new_id : '';

@endphp
@section('after_script')
    <script>
        const ID_CONFIG_DISPLAY = '{{ $id }}'
        const SURVEY_ITEM = '{{ $itemSurvey }}'
        const PRODUCT_ITEM = '{{ $itemProduct }}'
        const PROMOTION_ITEM = '{{ $itemPromotion }}'
        const POST_ITEM = '{{ $itemPost }}'
    </script>
    <script type="text/template" id="image-tpl">
    <div class="kt-avatar__holder" style="background-image: url({link});background-position: center;"></div></script>
    <script src="{{ asset('static/backend/js/config-display/main.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        configDisplayDetail.init();
    </script>
@endsection
