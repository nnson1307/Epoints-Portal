@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ DỊCH VỤ')}}</span>
@stop
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        .dropzone img {
            border-radius: 10px;
            vertical-align: middle;
            width: 114px;
            height: 114px;
        }
    </style>
    <style>

        .add-group-section {
            margin-top: 28px;
            text-align: right;
        }

        .kill-padding-left {
            padding-left: 0 !important;
        }

        .kil-padding-right {
            padding-right: 0 !important;
        }

        .required {
            color: red;
        }

        .full_dz {
            width: 100%;
        }

        .active-btn {
            background-color: #5867dd !important;
            border-color: #5867dd !important;
            color: white !important;
        }

        .image-preview {
            width: 100%;
            text-align: center;
            padding: 20px;
            min-height: 100px;
        }

        .img-preview {
            width: 100%;
        }

        .img-preview-large {
            width: 200px;
        }

        .detail-sec {
            background: white;
            border: 4px solid #f7f7fa;
            padding: 10px;
        }

        .btn-unit {
            background-color: #384AD7;
            border: 1px solid #384AD7;
            color: white;
            padding: 8px 0px;
            height: 38px;
            width: 100%;
            text-align: center;
            font-weight: bold;
        }

        .img-section {
            text-align: center;
        }

        .btn-delete-img {
            display: block;
            margin: auto;
            margin-top: 10px;
        }


    </style>
    @include('admin::service.modal-edit-image')
    @include('admin::service.add-service-category')
    @include('admin::service.inc.edit-commission')
    <form action="" id="formEdit">
        <div class="m-portlet m-portlet--head-sm">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-edit"></i>
                            </span>
                            <h2 class="m-portlet__head-text">
                                {{__('CHỈNH SỬA DỊCH VỤ')}}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <div>
                        <div onmouseover="onmouseoverAddNew()" onmouseout="onmouseoutAddNew()"
                             class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new"
                             m-dropdown-toggle="hover" aria-expanded="true">
                            <a href="#"
                               class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                <i class="la la-plus m--hide"></i>
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="m-dropdown__wrapper dropdow-add-new" style="z-index: 101;display: none">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                  style="left: auto; right: 21.5px;"></span>
                                <div class="m-dropdown__inner">
                                    <div class="m-dropdown__body">
                                        <div class="m-dropdown__content">
                                            <ul class="m-nav">
                                                <li class="m-nav__item">
                                                    <a data-toggle="modal"
                                                       data-target="#add" href="" class="m-nav__link">
                                                        <i class="m-nav__link-icon la la-users"></i>
                                                        <span class="m-nav__link-text">{{__('Thêm nhóm dịch vụ')}} </span>
                                                    </a>
                                                    {{--<div class="input-group-append">--}}
                                                    {{--<button data-toggle="modal"--}}
                                                    {{--data-target="#add" class="btn btn-primary" type="button">--}}
                                                    {{--<i class="la la-plus"></i>Thêm mới--}}
                                                    {{--</button>--}}
                                                    {{--</div>--}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">
                {{--@include('admin::service.modal-description')--}}
                <input type="hidden" name="service_id" id="service_id_hidden" value="{{$item['service_id']}}">
                <input type="hidden" name="description_hidden" id="description_hidden"
                       value="{{$item['detail_description']}}">
                {{--{!! Form::open(['route'=>'admin.service.submitAdd',"id"=>"form", 'class' => ' m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed ']) !!}--}}


                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label>{{__('Nhóm dịch vụ')}}:<b class="text-danger">*</b></label>
                            <div class="input-group m-input-group">
                                {!! Form::select("service_category_id",$optionCategory,$item['service_category_id'],["class"=>"form-control","id"=>"service_category_id","autocomplete"=>"off","style"=>"width:100%"]) !!}

                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label>
                                    {{__('Tên dịch vụ')}}:<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <input type="text" class="form-control m-input"
                                           name="service_name" id="service_name"
                                           value="{{$item['service_name']}}">
                                </div>
                                <span class="error_service_name"></span>
                            </div>
                            <div class="col-lg-6">
                                <div {{ $errors->has('time') ? ' has-danger' : '' }}>
                                    <label>
                                        {{__('Thời gian sử dụng')}}:
                                    </label>
                                    <div class="input-group">
                                        <input type="number" id="time" class="form-control m-input"
                                               aria-describedby="basic-addon2" name="time"
                                               value="{{$item['time']}}">

                                        <div class="input-group-append">
                                            <span class="input-group-text ">{{__('Phút')}}</span>
                                        </div>

                                    </div>
                                    @if ($errors->has('time'))
                                        <span class="form-control-feedback">
                                                        {{ $errors->first('time') }}
                                                     </span>
                                        <br>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Trạng thái')}}:
                            </label>
                            <div class="row">
                                <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="h_is_actived" name="is_actived"
                                                   type="checkbox" {{$item['is_actived']==1?'checked':''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-lg-6 m--margin-top-5">
                                    <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Phụ thu')}}:
                            </label>
                            <div class="row">
                                <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_surcharge" name="is_surcharge"
                                                   type="checkbox" {{$item['is_surcharge']==1?'checked':''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-lg-6 m--margin-top-5">
                                    <i>{{__('Chọn để kích hoạt phụ thu')}}</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group" {{ $errors->has('old_price') ? ' has-danger' : '' }}>
                            <label>{{__('Giá dịch vụ')}}:<b class="text-danger">*</b></label>
                            <div class="input-group m-input-group ">
                                <input class="form-control m-input" name="price_standard"
                                       id="price_standard"
                                       value="{{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group"
                             {{ $errors->has('new_price') ? ' has-danger' : '' }} style="display: none">
                            <label>{{__('Giá chi nhánh')}}:<b class="text-danger">*</b></label>
                            <div class="input-group m-input-group">
                                <input class="form-control m-input" id="new_price"
                                       name="new_price"
                                       value="{{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <a class="btn btn-sm m-btn--icon color" data-toggle="modal" data-target="#edit-commission">
                                {{__('Thêm hoa hồng')}}
                            </a>
                        </div>

                        <div class="form-group m-form__group padding_row border">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group m-form__group">
                                        <label>
                                            <b>{{__('Chi nhánh')}}</b>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8 text-right">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label>
                                                <input type="checkbox" class="manager-btn" name="checkAll" id="checkAll" {{count($branch_price) > 0 ? 'checked': ''}}>
                                                <span></span>

                                            </label>
                                        </span>
                                </div>
                            </div>
                            <div class="col-12" id="branch_service">
                                <div class="form-group m-form__group input-group m-input-group" {{ $errors->has('branch_id') ? ' has-danger' : '' }}>
                                    <select id="branch_id" name="branch_id" style="width: 100%"
                                            multiple="multiple" size="2">
                                        @foreach($optionBranch as $key=>$value)
                                            <option value="{{$key}}" {{in_array($key,$selectBranch)?'selected':''}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="hiddenBranch" name="hiddenBranch">
                                </div>
                                <div class="form-group m-form__group" id="frm_branch_service"
                                     style="display:{{count($branch_price) > 0 ? 'block': 'none'}}">
                                    <div class="table-responsive">
                                        <table class="table table-striped m-table m-table--head-bg-default"
                                               id="table_branch">
                                            <thead class="bg" style="white-space: nowrap;">
                                            <tr>
                                                <th class="tr_thead_list width-150">{{__('Chi nhánh')}}</th>
                                                <th class="tr_thead_list width-110">{{__('Giá dịch vụ')}}</th>
                                                <th class="tr_thead_list width-250">{{__('Giá chi nhánh')}}</th>
                                                @if(session()->get('brand_code') == 'giakhang')
                                                    <th class="tr_thead_list width-250">{{__('Giá tuần')}}</th>
                                                    <th class="tr_thead_list width-250">{{__('Giá tháng')}}</th>
                                                    <th class="tr_thead_list width-250">{{__('Giá năm')}}</th>
                                                @else
                                                    <th hidden class="tr_thead_list width-250">{{__('Chi tuần')}}</th>
                                                    <th hidden class="tr_thead_list width-250">{{__('Giá tháng')}}</th>
                                                    <th hidden class="tr_thead_list width-250">{{__('Giá năm')}}</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($branch_price as $key=>$value)
                                                <tr class="branch_tb">
                                                    <td>
                                                        {{$value['branch_name']}}
                                                        <input type="hidden" class="stt" value="{{$key+1}}">
                                                        <input type="hidden" id="service_branch_price_id"
                                                               name="service_branch_price_id"
                                                               value="{{$value['service_branch_price_id']}}">
                                                        <input type="hidden" name="id_branch[]"
                                                               value="{{$value['branch_id']}}">
                                                    </td>
                                                    <td class="old_price">
                                                        {{number_format($value['old_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                                        <input type="hidden" value="{{$value['old_price']}}">
                                                    </td>
                                                    <td>
                                                        <input style="text-align: right"
                                                               class="new_{{$key+1}} form-control btn-sm width-250"
                                                               name="new_price" id="new_price"
                                                               value="{{$value['new_price']}}"></td>
                                                    @if(session()->get('brand_code') == 'giakhang')
                                                        <td>
                                                            <input style="text-align: right"
                                                                   class="new_{{$key+1}} form-control btn-sm width-250"
                                                                   name="price_week" id="price_week"
                                                                   value="{{$value['price_week']}}"></td>
                                                        <td>
                                                            <input style="text-align: right"
                                                                   class="new_{{$key+1}} form-control btn-sm width-250"
                                                                   name="price_month" id="price_month"
                                                                   value="{{$value['price_month']}}"></td>
                                                        <td>
                                                            <input style="text-align: right"
                                                                   class="new_{{$key+1}} form-control btn-sm width-250"
                                                                   name="price_year" id="new_price"
                                                                   value="{{$value['price_year']}}"></td>
                                                    @else
                                                        <td hidden>
                                                            <input style="text-align: right"
                                                                   class="new_{{$key+1}} form-control btn-sm width-250"
                                                                   name="price_week" id="price_week"
                                                                   value="{{$value['price_week']}}"></td>
                                                        <td hidden>
                                                            <input style="text-align: right"
                                                                   class="new_{{$key+1}} form-control btn-sm width-250"
                                                                   name="price_month" id="price_month"
                                                                   value="{{$value['price_month']}}"></td>
                                                        <td hidden>
                                                            <input style="text-align: right"
                                                                   class="new_{{$key+1}} form-control btn-sm width-250"
                                                                   name="price_year" id="new_price"
                                                                   value="{{$value['price_year']}}"></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-4">
                                    <a href="javascript:void(0)"
                                       onclick="document.getElementById('getFile').click()"
                                       class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm ảnh đại diện')}}
                                                </span>
                                            </span>
                                    </a>
                                </div>
                                <div class="col-lg-8">
                                    <a href="javascript:void(0)"
                                       onclick="service.image_dropzone()"
                                       class="btn btn-sm m-btn--icon color">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>
                                                {{__('Thêm ảnh dịch vụ')}}
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group ">
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="hidden" id="service_avatar" name="service_avatar" value="">

                                    <input type="hidden" id="service_avatar_edit"
                                           name="service_avatar_edit" value="{{$item['service_avatar']}}">

                                    <div class="wrap-img avatar float-left">
                                        @if($item['service_avatar']!=null)
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{$item['service_avatar']}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img" style="display: block">
                                                    <a href="javascript:void(0)" onclick="service.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                 </span>
                                        @else
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img">
                                                    <a href="javascript:void(0)" onclick="service.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                    </span>
                                        @endif
                                        <input type="hidden" id="service_avatar" name="service_avatar" value="">
                                    </div>
                                    <div class="form-group m-form__group float-left m--margin-left-20 warning_img">
                                        <span class="error_img" style="color:red;"></span>

                                    </div>
                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                           data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                           id="getFile"
                                           type="file"
                                           onchange="uploadImage(this);" class="form-control"
                                           style="display:none">
                                </div>
                                <div class="col-lg-8">
                                    <div class="image-show">
                                        @foreach($itemImage as $i)
                                            <div class="wrap-img image-show-child">
                                                <input type="hidden" name="service_image" class="service_image"
                                                       value="{{$i->name}}">
                                                <img class='m--bg-metal m-image img-sd '
                                                     src='{{$i->name}}' alt='{{__('Hình ảnh')}}' width="100px"
                                                     height="100px">
                                                <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="service.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">
                                {{__('Nhắc sử dụng lại')}}:
                            </label>
                            <div class="row">
                                <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_remind" name="is_remind" type="checkbox"
                                                   value="{{$item['is_remind']}}"
                                                   onchange="service.changeRemind(this)"
                                                    {{$item['is_remind'] == 1 ? 'checked': ''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-lg-6 m--margin-top-5">
                                    <i>{{__('Chọn để kích hoạt nhắc sử dụng lại')}}</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group div_remind_value"
                             style="display:{{$item['is_remind'] == 1 ? 'block': 'none'}};">
                            <label class="black-title">
                                {{__('Số ngày nhắc lại')}}:
                            </label>
                            <div class="input-group m-input-group">
                                <input type="text" class="form-control m-input"
                                       name="remind_value" id="remind_value"
                                       value="{{!empty($item['remind_value']) ? $item['remind_value'] : 1}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Mô tả ngắn')}}:
                            </label>
                            <div class="input-group">
                            <textarea name="description1" id="description1"
                                      class="form-control" cols="5" rows="5">{{$item['description']}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="div_radio_kq">
                                <div class="m-checkbox-list">
                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                        <input type="checkbox" id="is_upload_image_ticket"
                                               name="is_upload_image_ticket" {{$item['is_upload_image_ticket'] == 1 ? 'checked': ''}}>
                                        <span></span> @lang('Cần hình ảnh khi hoàn thành yêu cầu xử lý')
                                    </label>
                                </div>
                                <div class="m-checkbox-list">
                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                        <input type="checkbox" id="is_upload_image_sample"
                                               name="is_upload_image_sample" {{$item['is_upload_image_sample'] == 1 ? 'checked': ''}}>
                                        <span></span> @lang('Cần hình ảnh mẫu khi hoàn thành yêu cầu xử lý')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group padding_row border">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group m-form__group">
                                        <label>
                                            <b>{{__('Sản phẩm sử dụng')}}</b>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8 text-right">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label>
                                                <input type="checkbox" class="manager-btn" name="check_product"
                                                       id="check_product" {{ count($itemMaterial) > 0 ? 'checked': ''}}>
                                                <span></span>

                                            </label>
                                        </span>
                                </div>
                            </div>
                            <div class="col-12" id="product_service"
                                 style="display:{{ count($itemMaterial) > 0 ? 'block': 'none'}}">
                                <div class="form-group m-form__group">
                                    <select id="product_id" name="product_id[]" readonly=""
                                            class="form-control m-input" style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <div class="table-responsive">
                                        <table class="table m-table m-table--head-bg-default" id="table_product">
                                            <thead class="bg" style="white-space: nowrap;">
                                            <tr>
                                                <th class="tr_thead_list">#</th>
                                                <th class="tr_thead_list">{{__('Sản phẩm')}}</th>
                                                <th class="tr_thead_list">{{__('Số lượng')}}</th>
                                                <th class="tr_thead_list">{{__('Đơn vị tính')}}</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($itemMaterial as $key=>$value)
                                                <tr class="pro_tb">
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$value['product_child_name']}}
                                                        <input type="hidden" name="product_hidden"
                                                               value="{{$value['material_id']}}">
                                                        <input type="hidden" id="product_name" name="product_name"
                                                               value="{{$value['product_name']}}">
                                                    </td>
                                                    <td>
                                                        <input style="text-align: center; height:30px; font-size: 13px"
                                                               type='text' id='quantity' name='quantity'
                                                               class='form-control quantity btn-ct-input text-center'
                                                               value="{{$value['quantity']}}">
                                                    </td>
                                                    <td>
                                                        <select id="unit_id_{{$value['material_id']}}"
                                                                class="form-control unit_load width-250" name="unit_id">
                                                            <option></option>
                                                            @foreach($optionUnit as $k=>$v)
                                                                @if($k==$value['unit_id'])
                                                                    <option value="{{$k}}" selected>{{$v}}</option>
                                                                @else
                                                                    <option value="{{$k}}">{{$v}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a class='remove_product' href="javascript:void(0)"
                                                           style="color: #a1a1a1">
                                                            <i class='la la-trash'></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group m-form__group padding_row border">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group m-form__group">
                                        <label>
                                            <b>{{__('Dịch vụ đi kèm')}}</b>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8 text-right">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label>
                                                <input type="checkbox" class="manager-btn"
                                                       name="check_service_accompanied" id="check_service_accompanied" {{ count($itemServiceMaterial) > 0 ? 'checked': ''}}>
                                                <span></span>

                                            </label>
                                        </span>
                                </div>
                            </div>
                            <div class="col-12" id="service_accompanied"
                                 style="display:{{ count($itemServiceMaterial) > 0 ? 'block': 'none'}}">
                                <div class="form-group m-form__group">
                                    <select id="service_accompanied_id" name="service_accompanied_id[]" readonly=""
                                            class="form-control m-input" style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <div class="table-responsive">
                                        <table class="table m-table m-table--head-bg-default"
                                               id="table_service_accompanied">
                                            <thead class="bg">
                                            <tr>
                                                <th class="tr_thead_list" style="width: 5%">#</th>
                                                <th class="tr_thead_list width-350">{{__('Tên dịch vụ')}}</th>
                                                <th class="tr_thead_list text-center">{{__('Số lượng')}}</th>
                                                <th class="tr_thead_list"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($itemServiceMaterial as $key => $object)
                                                <tr class="accompanied_tb">
                                                    <td>{{$key+1}}</td>
                                                    <td class="product">
                                                        {{ $object['service_name'] }}
                                                        <input type="hidden" id="service_accompanied_hidden"
                                                               name="service_accompanied_hidden"
                                                               value="{{ $object['material_id'] }}">
                                                        <input type="hidden" id="service_code_accompanied_hidden"
                                                               name="service_code_accompanied_hidden"
                                                               value="{{ $object['material_code'] }}">
                                                    </td>
                                                    <td class="quantity text-center">
                                                        1
                                                    </td>

                                                    <td class="del">
                                                        <a class="remove_service_accompanied" href="javascript:void(0)"
                                                           style="color: #a1a1a1">
                                                            <i class="la la-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group m-form__group">
                    <label>
                        <i class="fa fa-edit"></i>
                        {{__('Mô tả chi tiết')}}
                    </label>
                    <div class="summernote"></div>
                    {{--<textarea class=" form-control m-input" name="detail_description"--}}
                    {{--id="detail_description"></textarea>--}}
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right ">
                        <a href="{{route('admin.service')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_save m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
                        </button>
                    </div>
                    {{--<div class="m-form__actions m--align-right">--}}
                    {{--<button type="submit" class="btn btn-primary" id="btn_add"><i--}}
                    {{--class="la la-save m--margin-right-5"></i>Lưu--}}
                    {{--lại--}}
                    {{--</button>--}}
                    {{--<a href="{{route('admin.customer')}}" class="btn btn-danger"><i--}}
                    {{--class="fa fa-reply m--margin-right-"></i>Hủy</a>--}}
                    {{--</div>--}}
                </div>
            </div>

        </div>
    </form>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="branch-tpl">
        <tr class="branch_tb">
            <td class="branch">
                {branch_name}
                <input type="hidden" class="stt" value="{stt}">
                <input type="hidden" id="service_branch_price_id" name="service_branch_price_id">
                <input type="hidden" class="branch_hidden" id="branch_hidden" name="branch_hidden" value="{branch_id}">
            </td>
            <td class="old_price">
                <div>{old_price}
                    <input type="hidden" id="old_tb" name="old_tb" value="{old_price_hide}">
                </div>
            </td>
            <td class="new_price"><input style="text-align: right" class="new_{stt} form-control btn-sm width-250"
                                         id="new_tb"
                                         name="new_tb" value="{new_price}"></td>
            @if(session()->get('brand_code') == 'giakhang')
                <td class="price_week">
                    <input style="text-align: right" class="new_{stt} form-control m-input btn-sm width-250"
                           id="week_tb"
                           name="new_tb" value="{price_week}">
                    <center><span class="error_price_week" style="color: red"></span></center>
                </td>
                <td class="price_month">
                    <input style="text-align: right" class="new_{stt} form-control m-input btn-sm width-250"
                           id="month_tb"
                           name="new_tb" value="{price_month}">
                    <center><span class="error_price_month" style="color: red"></span></center>
                </td>
                <td class="price_year">
                    <input style="text-align: right" class="new_{stt} form-control m-input btn-sm width-250"
                           id="year_tb"
                           name="new_tb" value="{price_year}">
                    <center><span class="error_price_year" style="color: red"></span></center>
                </td>
            @else
                <td class="price_week" hidden>
                    <input style="text-align: right" class="new_{stt} form-control m-input btn-sm width-250"
                           id="week_tb"
                           name="new_tb" value="{price_week}">
                    <center><span class="error_price_week" style="color: red"></span></center>
                </td>
                <td class="price_month" hidden>
                    <input style="text-align: right" class="new_{stt} form-control m-input btn-sm width-250"
                           id="month_tb"
                           name="new_tb" value="{price_month}">
                    <center><span class="error_price_month" style="color: red"></span></center>
                </td>
                <td class="price_year" hidden>
                    <input style="text-align: right" class="new_{stt} form-control m-input btn-sm width-250"
                           id="year_tb"
                           name="new_tb" value="{price_year}">
                    <center><span class="error_price_year" style="color: red"></span></center>
                </td>
            @endif
        </tr>

    </script>
    <script type="text/template" id="product-tpl">
        <tr class="pro_tb">
            <td>{stt}</td>
            <td class="product">
                {product_name}
                <input type="hidden" id="product_hidden" name="product_hidden" value="{product_id}">
                <input type="hidden" id="product_name" name="product_name" value="{product_name}">
            </td>
            <td class="quantity">
                <input style="text-align: center; height:30px; font-size: 13px" type="text" name="quantity"
                       id="quantity"
                       class="quantity form-control btn-ct-input">
                <span class="error_quantity" style="color: #ff0000"></span>
            </td>
            <td class="unit_id ">
                <select class="form-control unit width-250" id="unit_id_{id_unit}" name='unit_id'>
                </select>
            </td>
            <td class="del">
                <a class="remove_product" href="javascript:void(0)" style="color: #a1a1a1">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="service-accompanied-tpl">
        <tr class="accompanied_tb">
            <td>{stt}</td>
            <td class="product">
                {service_accompanied_name}
                <input type="hidden" id="service_accompanied_hidden" name="service_accompanied_hidden"
                       value="{service_accompanied_id}">
                <input type="hidden" id="service_code_accompanied_hidden" name="service_code_accompanied_hidden"
                       value="{service_accompanied_code}">
            </td>
            <td class="quantity text-center">
                1
            </td>

            <td class="del">
                <a class="remove_service_accompanied" href="javascript:void(0)" style="color: #a1a1a1">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="avatar-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="javascript:void(0)" onclick="service.remove_avatar()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="service_avatar" name="service_avatar" value="">
    </script>
    <script type="text/template" id="imgeShow">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img-sv" value="{link_hidden}">
            <img class='m--bg-metal m-image img-sd '
                 src='{{'{link}'}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="service.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
        </div>


    </script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/service/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/detail.js?v='.time())}}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            $('.summernote').summernote({
                height: 150,
                // focus: true,
                placeholder: '{{__('Nhập thông tin chi tiết')}}',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
            $('.note-btn').attr('title', '');
            $('.summernote').summernote('code', $('#description_hidden').val());
        });

        sttBranch = {{count($branch_price)}}
    </script>
    <script type="text/template" id="imgeShow">
        <div class="image-edit m--margin-left-10">
            <img class='m--bg-metal m-image-show img-sd exist-image-db'
                 src='{{asset('{link}')}}' alt='{{__('Hình ảnh')}}'>
        </div>
    </script>
@stop
