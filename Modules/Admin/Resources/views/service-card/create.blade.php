@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/service-card.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services-card.png')}}" alt="" style="height: 20px;">
        {{__('THẺ DỊCH VỤ')}}
    </span>
@endsection
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

        .err {
            color: red;
        }
        .m-image {
             /*padding: 2px;*/
             max-width: 100px;
             max-height: 100px;
             width: 100px;
             height: 100px;
             background: #ccc;
         }
    </style>
    @include('admin::service-card.inc.add-commission')
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::service-card-group.add')
        </div>
    </div>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÊM THẺ DỊCH VỤ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
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
                                               data-target="#modalAdd" href="" class="m-nav__link">
                                                <i class="m-nav__link-icon la la-users"></i>
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
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form group">
                        <label>{{__('Nhóm thẻ')}} : <span class="text-danger">*</span></label>
                        {!! Form::select("service_card_group_id",$_group_card,null,["class"=>"form-control ss--width-100-","id"=>"card_group","autocomplete"=>"off",'style'=>'width:100%']) !!}
                        <span class="err error-service-card-group"></span>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>{{__('Tên thẻ dịch vụ')}}: <span class="text-danger">*</span></label>
                        {!! Form::text("name",null,["class"=>"form-control","id"=>"service_card_name"]); !!}
                        <span class="err error-service-card-name"></span>
                    </div>
                    <div class="form-group">
                        <label>{{__('Giá thẻ')}} : <span class="text-danger">*</span></label>
                        {!! Form::text("price",null,["class"=>"form-control"]); !!}
                        <span class="err error-price"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <a class="btn btn-sm m-btn--icon color" data-toggle="modal" data-target="#add-commission">
                            {{__('Thêm hoa hồng')}}
                        </a>
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
                                                   type="checkbox">
                                            <span></span>
                                        </label>
                                    </span>
                            </div>
                            <div class="col-lg-6 m--margin-top-5">
                                <i>{{__('Chọn để kích hoạt phụ thu')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="service-type-section">
                        @if ($errors->has('money'))
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
                                    <label class="btn ss--button-cms-piospa rdo active">
                                        <input type="radio" name="date-use" value="day" id="option1"
                                               autocomplete="off" checked="">
                                        <span class="m--margin-left-5 m--margin-right-5">{{__('Ngày')}}</span>
                                    </label>
                                    <label class="btn btn-default rdo">
                                        <input type="radio" name="date-use" value="week" id="option2"
                                               autocomplete="off">
                                        <span class="m--margin-left-5 m--margin-right-5">{{__('Tuần')}}</span>
                                    </label>
                                    <label class="btn btn-default rdo">
                                        <input type="radio" name="date-use" value="month" id="option3"
                                               autocomplete="off">
                                        <span class="m--margin-left-5 m--margin-right-5">{{__('Tháng')}}</span>
                                    </label>
                                    <label class="btn btn-default rdo">
                                        <input type="radio" name="date-use" value="year" id="option3"
                                               autocomplete="off">
                                        <span class="m--margin-left-5 m--margin-right-5">{{__('Năm')}}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                {!! Form::text("date_using",null,["class"=>"form-control","style"=>"text-align: right"]); !!}
                                <span class="err error-date-using"></span>
                            </div>
                            <div class="col-lg-4">
                                <label class="m-checkbox m-checkbox--air m--margin-top-10">
                                    <input id="date-using-not-limit" class="check-inventory-warning"
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
                            <div class="col-lg-6">
                                <div class="input-group m-input-group">
                                    <input name="number_using" id="number_using" style="text-align: right" type="text"
                                           class="form-control" value="">
                                    <div class="input-group-append">
                                        <button class="btn ss--button-cms-piospa"><b>{{__('LẦN')}}</b>
                                        </button>
                                    </div>
                                </div>
                                <span class="err error-number-using"></span>
                            </div>
                            <div class="col-lg-4">
                                <label class="m-checkbox m-checkbox--air m--margin-top-10">
                                    <input id="number-using-not-limit" class="check-inventory-warning"
                                           type="checkbox">
                                    {{__('Không giới hạn')}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-lg-4 ss--padding-left-0">
                                <div class="form-group m-form__group m-widget19">
                                    <div class="m-widget19__pic">
                                        <img class="m--bg-metal m-image" id="blah"
                                             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                             alt="{{__('Hình ảnh')}}">
                                    </div>
                                    <input accept=".png, .jpg, .jpeg" id="getFile" type="file"
                                           onchange="ServiceCard.uploadImage(this);"
                                           class="form-control"
                                           style="display:none">
                                </div>
                            </div>
                            <div class="col-lg-8 ss--padding-left-0">
                                <div class="form-group m-form__group">
                                    <div class="m-widget19__action">
                                        <a href="javascript:void(0)"
                                           onclick="document.getElementById('getFile').click()"
                                           class="btn m-btn--square ss--button-cms-piospa m-btn--icon">
                                        <span>
                                        <i class="la la-image"></i>
                                        <span>
                                        {{__('Chọn hình ảnh')}}
                                        </span>
                                        </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="">{{__('Định dạng')}}: <b class="image-info image-format"></b> </label>
                                    <br>
                                    <label for="">{{__('Kích thước')}}: <b class="image-info image-size"></b> </label>
                                    <br>
                                    <label for="">{{__('Dung lượng')}}: <b class="image-info image-capacity"></b> </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label> {{__('Mô tả')}}:</label>
                        <div class="summernote"></div>
                        {{--<textarea id="description" placeholder="Nhập mô tả cho thẻ" class="form-control m-input"--}}
                        {{--rows="12"></textarea>--}}
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            {{__('Nhắc sử dụng lại')}}:
                        </label>
                        <div class="row">
                            <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_remind" name="is_remind" type="checkbox" value="0"
                                                   onchange="ServiceCard.changeRemind(this)">
                                            <span></span>
                                        </label>
                                    </span>
                            </div>
                            <div class="col-lg-6 m--margin-top-5">
                                <i>{{__('Chọn để kích hoạt nhắc sử dụng lại')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group div_remind_value" style="display:none;">
                        <label class="black-title">
                            {{__('Số ngày nhắc lại')}}:
                        </label>
                        <div class="input-group m-input-group">
                            <input type="text" class="form-control m-input"
                                   name="remind_value" id="remind_value" value="1">
                        </div>
                        <span class="err error_remind_value"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer m--margin-right-20">
            <div class="form-group m-form__group">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.service-card')}}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                            <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('HỦY')}}</span>
                                            </span>
                        </a>
                        <button type="button" onclick="ServiceCard.add('close')"
                                class="ss--btn-mobiles btn-save btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                                <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>{{__('LƯU THÔNG TIN')}}</span>
                                            </span>
                        </button>
                        <button type="button" onclick="ServiceCard.add('addNew')"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md ss--btn btn-save m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                            <i class="fa fa-plus-circle m--margin-right-10"></i>
                                <span> {{__('LƯU & TẠO MỚI')}}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6" style="display: none">
        <label>{{__('Mã thẻ dịch vụ')}} <span class="text-danger">*</span></label>
        {!! Form::text("code",$_code_group,["class"=>"form-control","id"=>"code","autocomplete"=>"off"]); !!}
        @if ($errors->has('code'))
            <span class="form-control-feedback">
                {{ $errors->first('code') }}
            </span>
            <br>
        @endif
    </div>
    <input type="hidden" value="service" id="type">
    <input type="hidden" name="file_name_avatar" id="file_name_avatar" value="">
@endsection

@section("modal_section")
    @include("admin::service-card.popup.create-group")
@endsection
@section('after_script')

    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/service-card/crud.js')}}" type="text/javascript"></script>
    <script>
        var Summernote = {
            init: function () {
                $(".summernote").summernote({
                    height: 208,
                    placeholder: '{{__('Nhập nội dung')}}...',
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']]
                    ]
                })
            }
        };
        jQuery(document).ready(function () {
            Summernote.init();
            $('.note-btn').attr('title', '');
        });
    </script>
@stop

