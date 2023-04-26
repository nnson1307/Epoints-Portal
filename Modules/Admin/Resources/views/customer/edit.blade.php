@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ KHÁCH HÀNG')}}</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        input[type=file] {
            padding: 10px;
            background: #fff;
        }

        .m-widget5 .m-widget5__item .m-widget5__pic > img {
            width: 100%
        }

        .m-image {
            /*padding: 5px;*/
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }

        .form-control-feedback {
            color: red;
        }

        .file_customer {
            white-space: nowrap;
            width: 80%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    @include('admin::customer.add-customer-group')
    @include('admin::customer.add-customer-refer')
    @include('admin::customer.pop.modal-image')
    @include('admin::customer.pop.modal-file')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA KHÁCH HÀNG')}}
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div>
                    <div onmouseover="onmouseoverAddNew()" onmouseout="onmouseoutAddNew()"
                         class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new"
                         m-dropdown-toggle="hover" aria-expanded="true">
                        <a href="#"
                           class="m-portlet__nav-link btn btn-lg btn-secondary m-btn m-btn--outline-2x m-btn--icon m-btn--icon-only m-dropdown__toggle">
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
                                                    <span class="m-nav__link-text">{{__('Thêm nhóm khách hàng')}} </span>
                                                </a>
                                            </li>
                                            <li class="m-nav__item">
                                                <a data-toggle="modal"
                                                   data-target="#add_customer_refer" href="" class="m-nav__link">
                                                    <i class="m-nav__link-icon fa fa-user-plus"></i>
                                                    <span class="m-nav__link-text">{{__('Thêm người giới thiệu')}}</span>
                                                </a>
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
        <form id="form-edit">
            <div class="m-portlet__body">
                {!! csrf_field() !!}
                <input type="hidden" id="customer_id" name="customer_id" value="{{$item['customer_id']}}">

                <div class="row">
                    <div class="col-lg-2">
                        <input type="hidden" id="customer_avatar" name="customer_avatar"
                               value="{{$item['customer_avatar']}}">
                        <input type="hidden" id="customer_avatar_upload" name="customer_avatar_upload"
                               value="">
                        <div class="form-group m-form__group m-widget19">
                            <div class="m-widget19__pic">
                                @if($item['customer_avatar']!=null)
                                    <img class="m--bg-metal m-image img-sd" id="blah"
                                         src="{{$item['customer_avatar']}}" width="220px" height="220px"
                                         alt="{{__('Hình ảnh')}}"/>
                                @else
                                    <img class="m--bg-metal  m-image img-sd" id="blah"
                                         src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                                         alt="{{__('Hình ảnh')}}" width="220px" height="220px"/>
                                @endif

                            </div>
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                   id="getFile" type='file'
                                   onchange="uploadImage(this);"
                                   class="form-control"
                                   style="display:none"/>


                            <div class="m-widget19__action" style="max-width: 155px">
                                <a href="javascript:void(0)"
                                   onclick="document.getElementById('getFile').click()" style="width: 100%"
                                   class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    {{__('Tải ảnh lên')}}
                                                </span>
                                            </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4">
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__('Tên khách hàng')}}:<b class="text-danger">*</b></span>
                                    </label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" id="full_name" name="full_name"
                                                   class="form-control m-input"
                                                   placeholder="@lang('Nhập tên khách hàng')"
                                                   value="{{$item['full_name']}}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-user"></i></span></span>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        @lang("Mã hồ sơ"):
                                    </label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" id="profile_code" name="profile_code"
                                                   class="form-control m-input "
                                                   placeholder="{{__("Mã hồ sơ")}}" value="{{$item['profile_code']}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Ngày sinh')}}:
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <select class="form-control" style="width: 100%" id="day" name="day">
                                                <option></option>
                                                @for($i=1;$i<=31;$i++)
                                                    @if($i==$day)
                                                        <option value="{{$i}}" selected>{{$i}}</option>
                                                    @else
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-4 m">
                                            <select class="form-control" style="width: 100%"
                                                    id="month" name="month">
                                                <option></option>
                                                @for($i=1;$i<=12;$i++)
                                                    @if($i==$month)
                                                        <option value="{{$i}}" selected>{{$i}}</option>
                                                    @else
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-4 y">
                                            <select class="form-control" style="width: 100%"
                                                    id="year" name="year">
                                                <option></option>
                                                @for($i=1940;$i<= date("Y");$i++)
                                                    @if($i==$year)
                                                        <option value="{{$i}}" selected>{{$i}}</option>
                                                    @else
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <span class="error_birthday" style="color: #ff0000"></span>
                                </div>
                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Giới tính')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="m-form__group form-group">

                                        <div class="m-radio-inline">
                                            <label class="m-radio cus">
                                                <input type="radio" name="gender"
                                                       value="male" {{$item['gender']=='male'?'checked':''}}> {{__('Nam')}}
                                                <span></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="gender"
                                                       value="female" {{$item['gender']=='female'?'checked':''}}> {{__('Nữ')}}
                                                <span></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="gender"
                                                       value="other" {{$item['gender']=='other'?'checked':''}}> {{__('Khác')}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>


                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Số điện thoại')}}:<b
                                                class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="number" id="phone1" name="phone1"
                                                   class="form-control m-input"
                                                   placeholder="@lang('Thêm số điện thoại') 1"
                                                   onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                                   value="{{$item['phone1']}}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-phone"></i></span></span>
                                        </div>
                                    </div>
                                    <span class="error_phone1" style="color: #ff0000"></span>
                                </div>
                                @if($item['phone2']!=null)
                                    <div class="m-form__group form-group phone2" style="display: block">
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="phone2" name="phone2"
                                                   placeholder="@lang('Thêm số điện thoại') 2"
                                                   onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                                   value="{{$item['phone2']}}">
                                            <div class="input-group-append">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-danger  color_button m-btn m-btn--custom m-btn--icon delete-phone">
									<span>
										<span class="sp-rm-sdt2">{{__('XÓA')}}</span>
									</span>
                                                </a>
                                            </div>
                                        </div>
                                        <span class="error_phone2" style="color: #ff0000"></span>
                                    </div>
                                    <div class="m-form__group form-group add">
                                        <a href="javascript:void(0)"
                                           class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color"
                                           style="display: none">
									<span>
										<i class="fa fa-plus-circle"></i>
										<span>{{__('Thêm số điện thoại')}}</span>
									</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="m-form__group form-group phone2" style="display: none">
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="phone2" name="phone2"
                                                   placeholder="@lang('Thêm số điện thoại') 2"
                                                   onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                            <div class="input-group-append">
                                                <a href="javascript:void(0)"
                                                   class="btn btn-danger color_button m-btn m-btn--custom m-btn--icon delete-phone">
									<span>
										<span class="sp-rm-sdt2">{{__('XÓA')}}</span>
									</span>
                                                </a>
                                            </div>
                                        </div>
                                        <span class="error_phone2" style="color: #ff0000"></span>
                                    </div>
                                    <div class="m-form__group form-group">
                                        <a href="javascript:void(0)"
                                           class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
									<span>
										<i class="fa fa-plus-circle"></i>
										<span>{{__('Thêm số điện thoại')}}</span>
									</span>
                                        </a>
                                    </div>
                                @endif
                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Địa chỉ')}}:
                                    </label>

                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <select name="province_id" id="province_id" class="form-control"
                                                    onchange='addressCustomer.changeProvince()'>
                                                <option></option>
                                                @foreach($optionProvince as $key => $value)
                                                    @if ($item['province_id'] == $key)
                                                        <option value="{{$key}}" selected>{{$value}}</option>
                                                    @else
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <div class="input-group">
                                            <input type="hidden" name="district_hide" id="district_hide"
                                                   value="{{$item['district_id']}}">
                                            <select name="district_id" id="district_id" class="form-control district"
                                                    onchange='addressCustomer.changeDistrict()'
                                                    title="@lang('Chọn quận / huyện')" style="width: 100%">
                                                @if($item['district_id'] != null)
                                                    <option value="{{$item['district_id']}}"
                                                            selected>{{$item['district_type'] . ' '. $item['district_name']}}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 d">
                                        <select name="ward_id" id="ward_id"
                                                class="form-control ward_id" style="width: 100%">
                                            <option value="">{{__('Chọn phường/xã')}}</option>
                                            @foreach($listWard as $itemWard)
                                                <option value="{{$itemWard['ward_id']}}" {{$itemWard['ward_id'] == $item['ward_id'] ? 'selected' : ''}}>{{$itemWard['type'].' '.$itemWard['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group m-form__group">
                                            <div class="input-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input id="address" name="address" class="form-control  autosizeme"
                                                           placeholder="@lang('Nhập địa chỉ khách hàng')"
                                                           data-autosize-on="true"
                                                           style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;"
                                                           value="{{$item['address']}}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span><i class="la la-map-signs"></i></span></span>
                                                </div>
                                            </div>
                                            @if ($errors->has('address'))
                                                <span class="form-control-feedback">
                                                         {{ $errors->first('address') }}
                                                    </span>
                                                <br>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input id="postcode" name="postcode" class="form-control"
                                                   placeholder="@lang("Nhập post code")"
                                                   data-autosize-on="true" value="{{$item['postcode']}}"
                                                   style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-map-marker"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Email')}}:
                                    </label>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text" id="email" name="email" class="form-control m-input"
                                               placeholder="Vd: piospa@gmail.com" value="{{$item['email']}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-at"></i></span></span>
                                    </div>
                                    <span class="error_email" style="color: #ff0000"></span>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__('Loại khách hàng')}}:
                                    </label>
                                    <div class="m-input-icon m-input-icon--right">
                                        <select id="customer_type" name="customer_type"
                                                onchange="changeCustomerType(this)"
                                                title="@lang("Chọn loại khách hàng")"
                                                class="form-control m-input" style="width: 100%">
                                            <option value="personal" {{isset($item['customer_type']) && $item['customer_type'] == 'personal' ? 'selected ' : ''}}>
                                                @lang('Cá nhân')
                                            </option>
                                            <option value="business" {{isset($item['customer_type']) && $item['customer_type'] == 'business' ? 'selected ' : ''}}>
                                                @lang('Doanh nghiệp')
                                            </option>
                                        </select>
                                    </div>
                                    <span class="error_type_customer" style="color: #ff0000"></span>
                                </div>
                                <div class="open-business-input form-group m-form__group" {{isset($item['customer_type']) && $item['customer_type'] == 'personal' ? 'hidden ' : ''}}>
                                    <label class="black-title">@lang("Mã số thuế"):</label>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text" id="tax_code"
                                               value="{{isset($item['tax_code']) && $item['tax_code'] != '' ? $item['tax_code'] : ''}}"
                                               name="tax_code" class="form-control m-input" minlength="11"
                                               maxlength="13">
                                    </div>
                                </div>
                                <div class="open-business-input form-group m-form__group" {{isset($item['customer_type']) && $item['customer_type'] == 'personal' ? 'hidden ' : ''}}>
                                    <label class="black-title">
                                        @lang("Người đại diện"):
                                    </label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" id="representative" name="representative"
                                                   class="form-control m-input " maxlength="191"
                                                   value="{{isset($item['representative']) && $item['representative'] != '' ? $item['representative'] : ''}}"
                                                   placeholder="{{__("Người đại diện")}}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-user"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="open-business-input form-group m-form__group" {{isset($item['customer_type']) && $item['customer_type'] == 'personal' ? 'hidden ' : ''}}>
                                    <label class="black-title">{{__('Hotline')}}:</label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="number" id="hotline" name="hotline"
                                                   value="{{isset($item['hotline']) && $item['hotline'] != '' ? $item['hotline'] : ''}}"
                                                   class="form-control m-input " maxlength="15" minlength="10"
                                                   placeholder="@lang("Nhập hotline")"
                                                   onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-phone"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Trạng thái')}}:
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-2">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived" name="is_actived" type="checkbox"
                                {{$item['is_actived']==1?'checked':''}}>
                        <span></span>
                    </label>
                </span>
                                        </div>
                                        <div class="col-lg-10 m--margin-top-5">
                                            <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-4">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Nhóm khách hàng')}}:<b
                                                class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select id="customer_group_id" name="customer_group_id" class="form-control"
                                                style="width: 100%">
                                            <option></option>
                                            @foreach($optionGroup as $key=>$value)
                                                @if($key==$item['customer_group_id'])
                                                    <option value="{{$key}}" selected>{{$value}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="add-info">
                                    <div class="form-group">
                                        <label>
                                            {{__('Nguồn khách hàng')}}:
                                        </label>
                                        <select class="form-control" id="customer_source_id"
                                                name="customer_source_id" title="{{__('Chọn nguồn khách hàng')}}"
                                                style="width: 100%">
                                            <option></option>
                                            @foreach($optionSource as $key=>$value)

                                                @if($key==$item['customer_source_id'])
                                                    <option value="{{$key}}" selected>{{$value}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        {{--{!! Form::select("customer_source_id",$optionSource,$item['customer_source_id'],["class"=>"form-control","id"=>"customer_source_id","title"=>"Chọn nhóm khách hàng","autocomplete"=>"off"]) !!}--}}

                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{__('Người giới thiệu')}}:
                                        </label>
                                        <div class="input-group m-input-group">
                                            <select class="form-control" name="customer_refer_id"
                                                    id="customer_refer_id" style="width: 100%">
                                                @if ($getRefer != null)
                                                    <option value="{{$getRefer['customer_id']}}">{{$getRefer['full_name_refer']}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        @if ($errors->has('customer_refer_id'))
                                            <span class="form-control-feedback">
                                                {{ $errors->first('customer_refer_id') }}
                                         </span>
                                            <br>
                                        @endif
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{__('Facebook')}}:
                                        </label>
                                        <div class="m-input-icon m-input-icon--right">
                                            <input id="facebook" name="facebook" class="form-control m-input"
                                                   type="text"
                                                   placeholder="{{__('Nhập link facebook')}}"
                                                   value="{{$item['facebook']}}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-facebook"></i></span></span>
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
                                                                   class="form-control m-input"
                                                                   value="{{$item[$v['key']]}}"
                                                                   maxlength="190">
                                                            @break;
                                                        @case('boolean')
                                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox" class="manager-btn"
                                                                           id="{{$v['key']}}" name="{{$v['key']}}"
                                                                           value="{{$item[$v['key']]}}"
                                                                           onchange="customer.changeBoolean(this)" {{$item[$v['key']] == 1 ? 'checked': ''}}>
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

                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                       onclick="customer.modalImage()">
                                        <i class="fa fa-plus-circle"></i> @lang('Ảnh kèm theo')
                                    </a>
                                </div>
                                <div class="div_image_customer image-show row">
                                    @if (count($getFile) > 0)
                                        @foreach($getFile as $v)
                                            @if($v['type'] == 'image')
                                                <div class="wrap-img image-show-child">
                                                    <input type="hidden" name="img-link-customer" value="{{$v['link']}}">
                                                    <input type="hidden" name="img-name-customer" value="{{$v['file_name']}}">
                                                    <input type="hidden" name="img-type-customer" value="{{$v['file_type']}}">

                                                    <img class="m--bg-metal m-image img-sd " src="{{$v['link']}}"
                                                         alt="Hình ảnh" width="100px" height="100px">
                                                    <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="customer.removeImage(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                       onclick="customer.modalFile()">
                                        <i class="fa fa-plus-circle"></i> @lang('File kèm theo')
                                    </a>
                                </div>

                                <div class="div_file_customer">
                                    @if (count($getFile) > 0)
                                        @foreach($getFile as $v)
                                            @if($v['type'] == 'file')
                                                <div class="form-group m-form__group div_file row">
                                                    <input type="hidden" name="file-customer" value="{{$v['link']}}">

                                                    <input type="hidden" name="file-link-customer" value="{{$v['link']}}">
                                                    <input type="hidden" name="file-name-customer" value="{{$v['file_name']}}">
                                                    <input type="hidden" name="file-type-customer" value="{{$v['file_type']}}">

                                                    <a target="_blank" href="{{$v['link']}}" class="file_customer">
                                                        {{$v['file_name']}}
                                                    </a>
                                                    <a style="color:black;"
                                                       href="javascript:void(0)" onclick="customer.removeFile(this)">
                                                        <i class="la la-trash"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

{{--                                <div class="form-group m-form__group">--}}
{{--                                    <label>--}}
{{--                                        {{__('Ghi chú')}}:--}}
{{--                                    </label>--}}
{{--                                    <div class="input-group m-input-group ">--}}
{{--                                        <textarea id="note" name="note" class="form-control autosizeme" rows="8"--}}
{{--                                                  placeholder="{{__('Nhập thông tin ghi chú')}}"--}}
{{--                                                  data-autosize-on="true"--}}
{{--                                                  style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">{{$item['note']}}</textarea>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                @if (count($getParameter) > 0)--}}
{{--                                    <div class="form-group m-form__group parameter">--}}
{{--                                        @foreach($getParameter as $v)--}}
{{--                                            <a href="javascript:void(0)"--}}
{{--                                               class="btn btn-sm ss--btn-parameter ss--font-weight-200"--}}
{{--                                               style="color: black;"--}}
{{--                                               onclick="customer.append_parameter('{{$v['content']}}')">--}}
{{--                                                {{$v['parameter_name']}}--}}
{{--                                            </a>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.customer')}}"
                           class="btn btn-metal bold-huy m-btn  m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HUỶ')}}</span>
						</span>
                        </a>


                        <button type="submit"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-edit m--margin-left-10">
							<span>
							<i class="la la-edit"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <input type="hidden" class="hidden-add-info" value="0">
    <input type="hidden" id="view_mode" value="{{$params['view_mode'] ?? null}}">
    {{--@include('admin::customer.add-customer-group')--}}
    {{--@include('admin::customer.add-customer-refer')--}}
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <style>
        .ss--font-weight-200 {
            font-weight: 200 !important;
        }

        .ss--btn-parameter {
            color: #5a6268;
            background-color: #ebebeb;
            border-color: #ebebeb;
        }
    </style>
@stop
@section('after_script')
    {{--    <script>--}}
    {{--        import Options from "../../../../../public/vendors/bootstrap-switch/docs/options.html";--}}
    {{--        function changeCustomerType(e){--}}
    {{--            if($(e).val() == 'personal'){--}}
    {{--                $('.open-business-input').attr('hidden', true);--}}
    {{--            }else{--}}
    {{--                $('.open-business-input').removeAttr('hidden');--}}
    {{--            }--}}
    {{--        }--}}
    {{--        // Shorthand for $( document ).ready()--}}
    {{--        $(function () {--}}
    {{--            $('#m_datepicker_1').datepicker({--}}
    {{--                todayHighlight: true,--}}
    {{--                orientation: "bottom left",--}}
    {{--                templates: {--}}
    {{--                    leftArrow: '<i class="la la-angle-left"></i>',--}}
    {{--                    rightArrow: '<i class="la la-angle-right"></i>'--}}
    {{--                }--}}
    {{--            });--}}

    {{--        });--}}
    {{--        export default {--}}
    {{--            components: {Options}--}}
    {{--        }--}}
    {{--    </script>--}}
    <script src="{{asset('static/backend/js/admin/customer/edit.js?v='.time())}}" type="text/javascript"></script>
    <script type="text/template" id="tpl-image">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img-link-customer" value="{imageLink}">
            <input type="hidden" name="img-name-customer" value="{imageName}">
            <input type="hidden" name="img-type-customer" value="{imageType}">

            <img class="m--bg-metal m-image img-sd " src="{imageLink}" alt="Hình ảnh" width="100px" height="100px">
            <span class="delete-img-sv" style="display: none;">
                <a href="javascript:void(0)" onclick="customer.removeImage(this)">
                    <i class="la la-close"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="tpl-file">
        <div class="form-group m-form__group div_file row">
            <input type="hidden" name="file-link-customer" value="{fileLink}">
            <input type="hidden" name="file-name-customer" value="{fileName}">
            <input type="hidden" name="file-type-customer" value="{fileType}">
            <a target="_blank" href="{fileLink}" class="file_customer">
                {fileName}
            </a>
            <a style="color:black;"
               href="javascript:void(0)" onclick="customer.removeFile(this)">
                <i class="la la-trash"></i>
            </a>
        </div>
    </script>
    <script>
        customer.dropzoneCustomer();
        customer.dropzoneFile();
    </script>
@stop