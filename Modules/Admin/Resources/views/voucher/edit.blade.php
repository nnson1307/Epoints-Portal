@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-promotion.png')}}" alt="" style="height: 20px;">
        {{__('KHUYẾN MÃI')}}
    </span>
@endsection
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA MÃ GIẢM GIÁ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        {!! Form::open(["route"=>"admin.voucher.submitEdit","method"=>"POST","id"=>"form", 'class' => 'm-form--group-seperator-dashed ']) !!}
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>{{__('Hình thức áp dụng')}} <span class="required"><b
                                            class="text-danger">*</b></span></label>

                            <select class="form-control select2" id="voucher_type" name="voucher_type"
                                    style="width:100%;">
                                <option></option>
                                <option value="order" {{$voucher->voucher_type == 'order' ? 'selected': ''}}>@lang('Đơn hàng')</option>
                                <option value="ship" {{$voucher->voucher_type == 'ship' ? 'selected': ''}}>@lang('Phí vận chuyển')</option>
                            </select>

                            @if ($errors->has('voucher_type'))
                                <span class="form-control-feedback">
                                                 {{ $errors->first('voucher_type') }}
                            </span>
                                <br>
                            @endif
                        </div>
                        <div class="form-group col-lg-6">
                            <label>{{__('Mã giảm giả')}} <span class="required">*</span></label>
                            <input type="hidden" name="voucher_id" value="{{$voucher->voucher_id}}">
                            {!! Form::text("code",$voucher->code,["class"=>"form-control","id"=>"code","autocomplete"=>"off","placeholder"=>__("Nhập mã giảm giá")]); !!}
                            <span class="form-control-feedback error-code"></span>

                            @if ($errors->has('code'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('code') }}
                                    </span>
                                <br>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Tiêu đề')}} <span class="required"><b class="text-danger">*</b></span></label>
                        <input type="hidden" name="voucher_title" value="{{$voucher->voucher_title}}">
                        {!! Form::text("voucher_title",$voucher->voucher_title,["class"=>"form-control","id"=>"voucher_title","autocomplete"=>"off","placeholder"=>__("Nhập tiêu đề")]); !!}
                        <span class="form-control-feedback error-code text-danger"></span>
                        @if ($errors->has('voucher_title'))
                            <span class="form-control-feedback">{{ $errors->first('voucher_title') }}</span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{__('Điểm đổi voucher')}} <span class="required"><b
                                        class="text-danger">*</b></span></label>
                        <input type="hidden" name="point" value="{{$voucher->point}}">
                        {!! Form::number("point",$voucher->point,["class"=>"form-control","id"=>"point","autocomplete"=>"off","placeholder"=>__("Nhập điểm đổi voucher")]); !!}
                        <span class="form-control-feedback text-danger"></span>
                        @if ($errors->has('point'))
                            <span class="form-control-feedback">{{ $errors->first('point') }}</span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group radio-sale">
                        <div>
                            <label class="m-radio ss--m-radio--success m--margin-right-30">
                                <input type="radio" name="type" value="sale_cash"
                                       @if($voucher->type == "sale_cash") checked
                                       @endif autocomplete="off"> {{__('Theo tiền mặt')}}
                                <span></span>
                            </label>
                            <label class="m-radio ss--m-radio--success  m--margin-right-30">
                                <input type="radio" name="type" value="sale_percent"
                                       @if($voucher->type == "sale_percent") checked
                                       @endif autocomplete="off"> {{__('Theo phần trăm')}}
                                <span></span>
                            </label>
                        </div>

                        @if ($errors->has('type'))
                            <span class="form-control-feedback">
                                             {{ $errors->first('type') }}
                                        </span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{__('Giá trị giảm')}} : <span class="required">*</span></label>
                        <div class="div_voucher_money">
                            {!! Form::text((($voucher->type == "sale_cash") ? "cash" : "percent"),(($voucher->type == "sale_cash") ? number_format($voucher->cash,isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : $voucher->percent),["class"=> (($voucher->type == "sale_cash") ? "form-control format-money" : "form-control format-percent"),"autocomplete"=>"off","id"=>"voucher-money","placeholder"=>__("Nhập giá trị giảm")]); !!}
                        </div>

                        @if ($errors->has('percent'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('percent') }}
                                </span>
                            <br>
                        @endif
                        @if ($errors->has('cash'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('cash') }}
                                </span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{__('Tiền giảm tối đa')}} : <span class="required">*</span></label>
                                    <div class="div_max_price">
                                        {!! Form::text("max_price",number_format($voucher->max_price,isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),["class"=>"form-control","autocomplete"=>"off","placeholder"=>__("Nhập số tiền giảm tối đa")]); !!}
                                    </div>

                                    @if ($errors->has('max_price'))
                                        <span class="form-control-feedback">
                                     {{ $errors->first('max_price') }}
                                </span>
                                        <br>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{__('Giá trị đơn hàng tối thiểu')}} : <span
                                                class="required">*</span></label>
                                    {!! Form::text("required_price",number_format($voucher->required_price,isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),["class"=>"form-control format-money","autocomplete"=>"off","placeholder"=>__("Nhập giá trị đơn hàng tối thiểu")]); !!}

                                    @if ($errors->has('required_price'))
                                        <span class="form-control-feedback">
                                     {{ $errors->first('required_price') }}
                                </span>
                                        <br>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 form-group">
                            <label>{{__('Số lần sử dụng')}} : <span class="required">*</span></label>
                            {!! Form::text("quota",$voucher->quota,["class"=>"form-control","autocomplete"=>"off","placeholder"=>__("Nhập hạn mức sử dụng")]); !!}

                            @if ($errors->has('quota'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('quota') }}
                                </span>
                                <br>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>{{__('Ngày hết hạn')}} <span class="required">*</span></label>
                            <div class="m-input-icon m-input-icon--right">
                                {!! Form::text("expire_date",\Carbon\Carbon::parse($voucher->expire_date)->format('d/m/Y'),["class"=>"form-control date-picker-expire","id"=>"code","autocomplete"=>"off","readonly"=>"readonly"]); !!}
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                     <span><i class="la la-calendar"></i></span></span>
                            </div>
                            @if ($errors->has('expire_date'))
                                <span class="form-control-feedback">
                                     {{ $errors->first('expire_date') }}
                                    </span>
                                <br>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Giảm giá đặc biệt')}}</label>
                        <div class="input-group row">
                            <div class="col-lg-1">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox"
                                               name="sale_special"{{$voucher->sale_special == 1 ? 'checked' : ''}}>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-6 m--margin-top-5">
                                <i> {{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group ">
                        <div class="row">
                            <div class="col-lg-3  w-col-mb-100">
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
                            <div class="col-lg-9  w-col-mb-100 div_avatar">
                                <input type="hidden" id="voucher_img" name="voucher_img" value="">
                                <input type="hidden" id="img_old" name="img_old" value="{{$voucher->voucher_img}}">
                                @if($voucher->voucher_img !=null)
                                    <div class="wrap-img avatar float-left">
                                        <img class="m--bg-metal m-image img-sd" id="blah"
                                             src="{{$voucher->voucher_img}}"
                                             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        <span class="delete-img" style="display: block">
                                                    <a href="javascript:void(0)" onclick="Voucher.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                    </div>
                                @else
                                    <div class="wrap-img avatar float-left">
                                        <img class="m--bg-metal m-image img-sd" id="blah"
                                             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        <span class="delete-img">
                                                    <a href="javascript:void(0)" onclick="Voucher.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                    </div>
                                @endif
                                <div class="form-group m-form__group float-left m--margin-left-20 warning_img">
                                    <label for="">{{__('Định dạng')}}: <b class="image-info image-format"></b> </label>
                                    <br>
                                    <label for="">{{__('Kích thước')}}: <b class="image-info image-size"></b>
                                    </label>
                                    <br>
                                    <label for="">{{__('Dung lượng')}}: <b class="image-info image-capacity"></b>
                                    </label><br>
                                    <label for="">{{__('Cảnh báo')}}: <b
                                                class="image-info">{{__('Tối đa 10MB (10240KB)')}}</b>
                                    </label><br>
                                    <span class="error_img" style="color:red;"></span>

                                </div>

                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                       data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                       id="getFile" type="file"
                                       onchange="uploadImage(this);" class="form-control"
                                       style="display:none">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="member-level-field">
                        <label>{{__('Cấp độ áp dụng')}} <span class="required"><b
                                        class="text-danger">*</b></span></label>

                        <select class="form-control" id="member_level_apply" name="member_level_apply[]" multiple
                                style="width:100%;">
                            <option value="all"
                                    {{$voucher->member_level_apply == 'all' || $voucher->member_level_apply == null ? 'selected' : ''}}>
                                {{__('Tất cả')}}
                            </option>
                            @foreach($member_level as $k => $v)
                                @if(in_array($v['member_level_id'], str_split($voucher->member_level_apply)))
                                    <option value="{{$v['member_level_id']}}" selected>{{$v['name']}}</option>
                                @else
                                    <option value="{{$v['member_level_id']}}">{{$v['name']}}</option>
                                @endif
                            @endforeach
                        </select>

                        @if ($errors->has('member_level_apply[]'))
                            <span class="form-control-feedback">
                                                 {{ $errors->first('member_level_apply[]') }}
                                            </span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group" id="customer-group-field">
                        <label>{{__('Nhóm khách hàng áp dụng')}} <span class="required"></span></label>

                        <select class="form-control" id="customer_group_apply" name="customer_group_apply[]" multiple
                                style="width:100%;">
                            <option value="all" {{$voucher->customer_group_apply == 'all' || $voucher->customer_group_apply == null ? 'selected' : ''}}>
                                {{__('Tất cả')}}</option>
                            @foreach($customer_group as $k => $v)
                                @if(in_array($v['customer_group_id'], explode(',', $voucher->customer_group_apply)))
                                    <option value="{{$v['customer_group_id']}}" selected>{{$v['group_name']}}</option>
                                @else
                                    <option value="{{$v['customer_group_id']}}">{{$v['group_name']}}</option>
                                @endif
                            @endforeach
                        </select>

                        @if ($errors->has('customer_group_apply[]'))
                            <span class="form-control-feedback">
                                                 {{ $errors->first('customer_group_apply[]') }}
                                            </span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 row form-group radio-sale">
                            <div>
                                <label class="m-radio ss--m-radio--success  m--margin-right-30">
                                    <input type="radio" name="type_using" value="public"
                                            {{$voucher->type_using == 'public' ? 'checked' : ''}}>
                                    {{__('Sử dụng tất cả')}}
                                    <span></span>
                                </label>
                                <label class="m-radio ss--m-radio--success">
                                    <input type="radio" name="type_using" value="private"
                                            {{$voucher->type_using == 'private' ? 'checked' : ''}}>
                                    {{__('Nội bộ')}}
                                    <span></span>
                                </label>
                            </div>

                            @if ($errors->has('type'))
                                <span class="form-control-feedback">
                                             {{ $errors->first('type') }}
                                        </span>
                                <br>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Cho phép khách hàng vãng lai sử dụng')}}</label>
                        <div class="input-group row">
                            <div class="col-lg-1">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" name="using_by_guest" {{$voucher->using_by_guest == '1' ? 'checked' : ''}}>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-6 m--margin-top-5">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Số lần 1 khách hàng sử dụng')}} : <span class="required"><b
                                        class="text-danger">*</b></span></label>
                        {!! Form::text("number_of_using",$voucher->number_of_using,["class"=>"form-control","placeholder"=>__("Nhập hạn mức 1 khách hàng sử dụng")]); !!}

                        @if ($errors->has('number_of_using'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('number_of_using') }}
                                </span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{__('Mô tả ngắn')}}</label>
                        {!! Form::textarea("description",$voucher->description,["class"=>"form-control",'rows' => 4, 'cols' => 5,"placeholder"=>__("Nhập mô tả ngắn")]); !!}
                        @if ($errors->has('description'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('description') }}
                                    </span>
                            <br>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{__('Hình thức')}}</label>
                        <div class="row form-group m--margin-left-2">
                            <div class="kill-padding-left kill-padding-right">
                                <button type="button"
                                        class="ss--font-size-13 btn m-btn--square @if($voucher->object_type == "all") active-btn @endif  btn-secondary m-btn--wide btnObjectType"
                                        data-type="all">
                                    <span class="">{{__('Tất cả')}}</span>
                                </button>
                            </div>
                            <div class="kill-padding-left kill-padding-right">
                                <button type="button"
                                        class="ss--font-size-13 btn m-btn--square @if($voucher->object_type == "service_card") active-btn @endif btn-secondary m-btn--wide btnObjectType"
                                        data-type="service_card">
                                    <span class="">{{__('Theo thẻ dịch vụ')}}</span>
                                </button>
                            </div>
                            <div class="kill-padding-left kill-padding-right">
                                <button type="button"
                                        class="ss--font-size-13 btn m-btn--square @if($voucher->object_type == "product") active-btn @endif btn-secondary m-btn--wide btnObjectType"
                                        data-type="product">
                                    <span class=""> {{__('Theo sản phẩm')}}</span>
                                </button>
                            </div>
                            <div class="kill-padding-left kill-padding-right">
                                <button type="button"
                                        class="ss--font-size-13 btn m-btn--square @if($voucher->object_type == "service") active-btn @endif btn-secondary m-btn--wide btnObjectType"
                                        data-type="service">
                                    <span class="">{{__('Theo dịch vụ')}}</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="object_type" value="{{$voucher->object_type}}">
                    </div>
                    <div class="form-group @if($voucher->object_type != "product") hide-input @endif object-type-input"
                         id="product-field">
                        <div class="form-group">
                            <label>{{__('Chọn loại sản phẩm')}} <span class="required">*</span></label>
                            {!! Form::select("product_type",$product_cate,null,["class"=>"form-control product_type select2","autocomplete"=>"off",($voucher->object_type != "product") ? "disabled":"","style"=>"width:100%"]); !!}
                        </div>
                        <div class="form-group">
                            <label>{{__('Sản phẩm')}} <span class="required">*</span></label>
                            {!! Form::select("product_id[]",($voucher->object_type == "product") ? $list : [""=>__("Tất cả")], ($voucher->object_type == "product") ?$object : null,["class"=>"form-control format-select","multiple","autocomplete"=>"off",($voucher->object_type != "product") ? "disabled":"","style"=>"width:100%"]); !!}

                            @if ($errors->has('product_id[]'))
                                <span class="form-control-feedback">
                                             {{ $errors->first('product_id[]') }}
                                        </span>
                                <br>
                            @endif
                        </div>

                    </div>
                    <div class="form-group @if($voucher->object_type != "service")hide-input @endif object-type-input"
                         id="service-field">
                        <div class="form-group">
                            <label>{{__('Chọn loại dịch vụ')}} <span class="required">*</span></label>
                            {!! Form::select("service_type",$service_cate,null,["class"=>"form-control service_type select2","autocomplete"=>"off",($voucher->object_type != "service") ? "disabled":"","style"=>"width:100%"]); !!}
                        </div>
                        <div class="orm-group">
                            <label>{{__('Dịch vụ')}} <span class="required">*</span></label>
                            {!! Form::select("service_id[]",($voucher->object_type == "service") ? $list : [""=>__("Tất cả")],($voucher->object_type == "service") ?$object:null,["class"=>"form-control format-select","multiple","autocomplete"=>"off",($voucher->object_type != "service") ? "disabled":"","style"=>"width:100%"]); !!}

                            @if ($errors->has('service_id[]'))
                                <span class="form-control-feedback">
                                                 {{ $errors->first('service_id[]') }}
                                            </span>
                                <br>
                            @endif
                        </div>

                    </div>
                    <div class="form-group @if($voucher->object_type != "service_card")hide-input @endif object-type-input"
                         id="service-card-field">
                        <div class="form-group">
                            <label>{{__('Chọn loại thẻ dịch vụ')}} <span class="required">*</span></label>
                            {!! Form::select("service_card_type",$service_card_type,null,["class"=>"form-control service_card_type select2","autocomplete"=>"off",($voucher->object_type != "service_card") ? "disabled":"","style"=>"width:100%"]); !!}
                        </div>
                        <div class="form-group">
                            <label>{{__('Thẻ Dịch vụ')}} <span class="required">*</span></label>
                            {!! Form::select("service_card_id[]",($voucher->object_type == "service_card") ?$list:[""=>__("Tất cả")],($voucher->object_type == "service_card") ?$object:null,["class"=>"form-control format-select","multiple","autocomplete"=>"off",($voucher->object_type != "service_card") ? "disabled":"","style"=>"width:100%"]); !!}

                            @if ($errors->has('service_card_id[]'))
                                <span class="form-control-feedback">
                                                 {{ $errors->first('service_card_id[]') }}
                                            </span>
                                <br>
                            @endif
                        </div>

                    </div>
                    <div class="form-group">
                        <label>{{__('Trạng thái')}}</label>
                        <div class="input-group row">
                            <div class="col-lg-1">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" @if($voucher->is_actived == 1) checked
                                               @endif  name="is_actived">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-6 m--margin-top-5">
                                <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Chi nhánh')}} <span class="required">*</span></label>
                        {!! Form::select("branch_id[]",$branch,$branch_selected,["class"=>"form-control select2 format-select","multiple","autocomplete"=>"off","style"=>"width:100%"]); !!}

                        @if ($errors->has('branch_id'))
                            <span class="form-control-feedback">
                                             {{ $errors->first('branch_id') }}
                                        </span>
                            <br>
                        @endif
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label>{{__('Màu nền')}} </label>
                            {!! Form::text("background_color",$voucher->background_color,["class"=>"form-control","id"=>"background_color","autocomplete"=>"off","placeholder"=>__("Nhập màu nền")]); !!}
                            <span class="form-control-feedback error-code text-danger"></span>
                            @if ($errors->has('background_color'))
                                <span class="form-control-feedback">{{ $errors->first('background_color') }}</span>
                                <br>
                            @endif
                        </div>
                        <div class="form-group col-lg-4">
                            <label>{{__('Màu chữ')}} </label>
                            {!! Form::text("text_color",$voucher->text_color,["class"=>"form-control","id"=>"text_color","autocomplete"=>"off","placeholder"=>__("Nhập màu chữ")]); !!}
                            <span class="form-control-feedback error-code text-danger"></span>
                            @if ($errors->has('text_color'))
                                <span class="form-control-feedback">{{ $errors->first('text_color') }}</span>
                                <br>
                            @endif
                        </div>
                        <div class="form-group col-lg-4">
                            <label>{{__('Màu nội dung')}} </label>
                            {!! Form::text("content_color",$voucher->content_color,["class"=>"form-control","id"=>"content_color","autocomplete"=>"off","placeholder"=>__("Nhập màu nội dung")]); !!}
                            <span class="form-control-feedback error-code text-danger"></span>
                            @if ($errors->has('content_color'))
                                <span class="form-control-feedback">{{ $errors->first('content_color') }}</span>
                                <br>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Mô tả chi tiết')}}</label>
                        {!! Form::textarea("detail_description",$voucher->detail_description,["class"=>"form-control summernote","placeholder"=>"Nhập mô tả ngắn"]); !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
                        <div class="m-form__actions m--align-right">
                            <button type="button" href="javascript:void(0)"
                                    onclick="location.href='{{route('admin.voucher')}}'"
                                    class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                                                    <span class="ss--text-btn-mobi">
                                                    <i class="la la-arrow-left"></i>
                                                    <span>{{__('HỦY')}}</span>
                                                    </span>
                            </button>
                            <button type="submit"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5 class-submit-edit"
                                    onclick="document.getElementById('form').submit()"
                            >
                                                        <span class="ss--text-btn-mobi">
                                                        <i class="la la-check"></i>
                                                        <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
                                                        </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <input type="hidden" id="idVoucher" value="{{$voucher->voucher_id}}">
    </div>
@endsection
@section("modal_section")
    {{--    @include("admin::order.popup.create-source")--}}
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    {{--    <link rel="stylesheet" href="{{asset('static/backend/demo/css/admin/voucher/voucher.css')}}">--}}
    <style>
        .btn.m-btn--square {
            padding-right: 1rem !important;
            padding-left: 1rem !important;
        }

        .m--margin-left-2 {
            margin-left: 2px !important;
        }
    </style>
@endsection
@section('after_script')

    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

    <script src="{{asset('static/backend/js/admin/voucher/voucher.js?v='.time())}}" type="text/javascript"></script>
    {{--@if(Session::has("error"))--}}
    {{--<script>--}}

    {{--$.notify({--}}
    {{--// options--}}
    {{--message: '{{Session::get("error")}}'--}}
    {{--}, {--}}
    {{--// settings--}}
    {{--type: 'danger'--}}
    {{--});--}}
    {{--</script>--}}
    {{--@endif--}}
    <script type="text/template" id="avatar-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="javascript:void(0)" onclick="Voucher.remove_avatar()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="voucher_img" name="voucher_img">
    </script>
    <script type="text/template" id="imgShow">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="voucher_img" value="{link_hidden}">
            <img class='m--bg-metal m-image img-sd '
                 src='{{asset('{link}')}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="service.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
        </div>
    </script>
    <script type="text/template" id="tpl-voucher-money">
        {!! Form::text("voucher-money",null,["class"=>"form-control format-money","id"=>"voucher-money","placeholder"=>__("Nhập giá trị giảm")]); !!}
    </script>
    <script type="text/template" id="tpl-max-price">
        {!! Form::text("max_price",null, ["id" => "max_price", "class"=>"form-control","placeholder"=>__("Nhập số tiền giảm tối đa"), "readonly" => "readonly"]); !!}
    </script>

    @if (Session::has("statusss"))
        <script>
            $.getJSON(laroute.route('translate'), function (json) {
                swal(
                    json['Cập nhật khuyến mãi thành công'],
                    '',
                    'success'
                );
            });
        </script>
    @endif
@stop
