@extends('layout')
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        .modal-backdrop {
            position: relative !important;
        }

        input[type=file] {
            padding: 10px;
            background: #fff;
        }

        .m-image {
            padding: 5px;
            max-width: 155px;
            max-height: 155px;
            width: 155px;
            height: 155px;
            background: #ccc;
        }

        .err {
            color: red;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="ss--title m--font-bold m-portlet__head-text">
                                <i class="fa flaticon-plus m--margin-right-5"></i>
                                {{__('CẬP NHẬT THẺ DỊCH VỤ')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div>
                            <div onmouseover="ServiceCard.onmouseoverAddNew()"
                                 onmouseout="ServiceCard.onmouseoutAddNew()"
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
                                                        <a data-toggle="modal" data-target="#add-group"
                                                           class="m-nav__link">
                                                            <i class="m-nav__link-icon la la-cc-mastercard"></i>
                                                            <span class="m-nav__link-text">{{__('Thêm nhóm thẻ')}} </span>
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
                {!! Form::open(["id"=>"form", 'class' => 'm-form--group-seperator-dashed ']) !!}
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form group">
                                <label>{{__('Nhóm thẻ')}} : <span class="text-danger">*</span></label>
                                {!! Form::select("service_card_group_id",$_group_card,(isset($_card) ? $_card->service_card_group_id : null),["class"=>"form-control","id"=>"card_group","autocomplete"=>"off"]) !!}
                                <span class="err error-service-card-group"></span>
                            </div>
                            <br>
                            <div class="form-group">
                                <label>{{__('Tên thẻ dịch vụ')}}: <span class="text-danger">*</span></label>
                                {!! Form::text("name",$_card->name,["class"=>"form-control"]); !!}
                                <span class="err error-service-card-name"></span>
                            </div>
                            <div class="form-group">
                                <label>{{__('Giá thẻ')}} : <span class="text-danger">*</span></label>
                                {!! Form::text("price",number_format($_card->price,0,"",","),["class"=>"form-control"]); !!}
                                <span class="err error-price"></span>
                            </div>
                            <div class="service-type-section">
                                @if(($_card->service_card_type=="money" || $errors->has('money')) && !$errors->has('price'))
                                    @include("admin::service-card.inc.money-type")
                                @else
                                    @include("admin::service-card.inc.service-type")
                                @endif
                            </div>

                            <div class="form-group">
                                <label>{{__('Hạn sử dụng')}} : <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="m-demo" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-primary rdo active">
                                                <input type="radio" name="date-use" value="day" id="option1"
                                                       autocomplete="off" checked="">
                                                {{__('Ngày')}}
                                            </label>
                                            <label class="btn btn-default rdo">
                                                <input type="radio" name="date-use" value="week" id="option2"
                                                       autocomplete="off">
                                                {{__('Tuần')}}
                                            </label>
                                            <label class="btn btn-default rdo">
                                                <input type="radio" name="date-use" value="month" id="option3"
                                                       autocomplete="off">
                                                {{__('Tháng')}}
                                            </label>
                                            <label class="btn btn-default rdo">
                                                <input type="radio" name="date-use" value="year" id="option3"
                                                       autocomplete="off">
                                                {{__('Năm')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <input {{$_card->date_using==0?'disabled':''}} style="text-align: right"
                                               type="text" name="date_using" id="date_using"
                                               value="{{$_card->date_using}}" class="form-control">
                                        <span class="err error-date-using"></span>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="m-checkbox m-checkbox--air">
                                            <input {{$_card->date_using==0?'checked':''}} id="date-using-not-limit"
                                                   class="check-inventory-warning"
                                                   type="checkbox">
                                            {{__('Không giới hạn')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{__('Số lần sử dụng')}} : <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="input-group m-input-group">
                                            <input name="number_using" id="number_using" style="text-align: right"
                                                   type="text"
                                                   class="form-control"
                                                   {{$_card->number_using==0?'disabled':''}} value="{{$_card->number_using}}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><b>{{__('LẦN')}}</b>
                                                </button>
                                            </div>
                                        </div>
                                        <span class="err error-number-using"></span>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="m-checkbox m-checkbox--air">
                                            <input {{$_card->number_using==0?'checked':''}} id="number-using-not-limit"
                                                   class="check-inventory-warning"
                                                   type="checkbox">
                                            {{__('Không giới hạn')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group m-form__group m-widget19">
                                        <div class="m-widget19__pic">
                                            @if($_card->image!=null)
                                                <img class="m--bg-metal m--padding-5 m-image" id="blah"
                                                     src="/{{$_card->image}}"
                                                     alt="{{__('Hình ảnh')}}">
                                            @else
                                                <img class="m--bg-metal m--padding-5 m-image" id="blah"
                                                     src="{{asset('uploads/admin/customer/hinhanh-default.png')}}"
                                                     alt="{{__('Hình ảnh')}}">
                                            @endif
                                        </div>
                                        <input id="getFile" type="file" onchange="ServiceCard.uploadImage(this);"
                                               class="form-control"
                                               style="display:none">


                                        <div class="m-widget19__action">
                                            <a href="javascript:void(0)"
                                               onclick="document.getElementById('getFile').click()"
                                               class="btn m-btn--square  btn-outline-primary btn-sm m-btn--icon">
                                    <span>
                                    <i class="la la-image"></i>
                                    <span>
                                    {{__('Chọn hình ảnh')}}
                                    </span>
                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2"></div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group m-form__group">
                                        <label for="">{{__('Tình trạng')}}:</label>
                                        <div class="input-group">
                                            <label class="m-checkbox m-checkbox--air">
                                                <input {{$_card->is_actived==1?'checked':''}} id="is-actived"
                                                       class="check-inventory-warning"
                                                       type="checkbox">
                                                {{__('Hoạt động')}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label> {{__('Mô tả')}}:</label>
                                <textarea id="description" placeholder="Nhập mô tả cho thẻ" class="form-control m-input"
                                          rows="12">{!! $_card->description !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m--align-right">
                                <a href="{{route('admin.service-card')}}"
                                   class="btn btn-danger m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                                            <span>
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('Thoát')}}</span>
                                            </span>
                                </a>

                                <div class="btn-group">
                                    <button type="button" onclick="ServiceCard.edit()"
                                            class="btn-save btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
                                                <span>
                                            <i class="la la-check"></i>
                                            <span>{{__('Cập nhật')}}</span>
                                            </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{--<div class="form-group m-form__group">--}}
                {{--<div align="right">--}}
                {{--<button type="submit" class="btn btn-primary"><i class="la la-save"></i>{{__('Lưu lại')}}</button>--}}

                {{--<a href="{{ route('admin.service-card') }}" class="btn btn-danger"> <i--}}
                {{--class="fa fa-reply"></i>{{__('Hủy')}}</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                <input type="hidden" name="file_name_avatar" id="file_name_avatar" value="">
                {!! Form::close() !!}

            </div>
        </div>
    </div>

    <input type="hidden" value="{{$_card->image}}" id="oldImage">
    <input type="hidden" value="{{$_card->service_card_type}}" id="type">
    <input type="hidden" name="file_name_avatar" id="file_name_avatar" value="">
    <input type="hidden" name="id" id="id" value="{{$_card->service_card_id}}">
@stop

@section("modal_section")
    @include("admin::service-card.popup.create-group")
@stop

@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/service-card.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card/crud.js')}}" type="text/javascript"></script>
    <script>
        var Summernote = {
            init: function () {
                $(".summernote").summernote({height: 208})
            }
        };
        jQuery(document).ready(function () {
            Summernote.init()
        });
    </script>
@stop

