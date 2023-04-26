@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }
        .type_of_criteria{
            font-weight: bold;
            font-size: 20px;
        }
        .form-control-label{
            font-weight: bold;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
                    </h3>
                    <a href="{{route('referral.addCommission')}}">
                    <div class="modal-footer" style="margin-left: 560px">
                        <div class="m-form__actions m--align-right w-100">
                            <button data-dismiss="modal" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						        <span>
						            <i class="la la-arrow-left"></i>
						            <span>{{__('HỦY')}}</span>
						        </span>
                            </button>
                        </div>
                    </div>
                    </a>
                    <a href="{{route('referral.commissionConditionOrderPrice')}}"
                       class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit">
                        <span>
                            <i class="la la-check"></i>
                            <span>{{__('TIẾP THEO')}}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="m-wizard m-wizard--5 m-wizard--success m-wizard--step-first" id="m_wizard">
            <div class="m-portlet__padding-x">
            </div>
            <div class="m-wizard__head m-portlet__padding-x">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="m-wizard__nav">
                            <div class="m-wizard__steps">
                                <div class="m-wizard__step m-wizard__step--current" m-wizard-target="m_wizard_form_step_1">
                                    <div class="m-wizard__step-info">
                                        <a href="#" class="m-wizard__step-number">
                                            <span class="m-wizard__step-label">
													Chọn loại tiêu chí
											</span>
                                            <span class="m-wizard__step-icon"><i class="la la-check"></i></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="m-wizard__step" m-wizard-target="m_wizard_form_step_3">
                                    <div class="m-wizard__step-info">
                                        <a href="#" class="m-wizard__step-number">
                                            <span class="m-wizard__step-label">
													Điều kiện tính hoa hồng
											</span>
                                            <span class="m-wizard__step-icon"><i class="la la-check"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-wizard__form-step m-wizard__form-step--current" id="m_wizard_form_step_2">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="m-form__section m-form__section--first">
                            <div class="m-form__heading">
                                <h3 class="type_of_criteria">Chọn loại tiêu chí</h3>
                            </div>
                            <div class="container">
                                <div class="row" >
                                    <div class="col-sm"  >
                                        <div class="form-group m-form__group row">
                                            <div class="col-lg-12">
                                                <div class="form-group m-form__group">
                                                    <label class="form-control-label">
                                                        Tên tiêu chí: <b class="text-danger">*</b>
                                                    </label>
                                                    <div class="input-group">
                                                        <input id="product-name" name="product_name" type="text" class="form-control m-input class"
                                                               placeholder="{{__('Tên tiêu chí')}}"
                                                               aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="form-control-label">Loại tiêu chí: <b class="text-danger">*</b></label>
                                                    <select class="form-control select2" id="type_criteria" onchange="view.changeType(this)">
                                                        <option value="">Chọn loại tiêu chí</option>
                                                        <option value="CPS">CPS</option>
                                                        <option value="CPI">CPI</option>
                                                    </select>
                                                </div>
                                                <div class="form-group m-form__group group_accountable_by">
                                                    <label class="form-control-label">Tính theo: <b class="text-danger">*</b></label>
                                                    <select class="form-control select2" onchange="view.changeChoice(this)">
                                                        <option value="Lựa chọn">Tổng giá trị đơn hàng</option>
                                                        <option value="Tổng giá trị đơn hàng">Tổng giá trị đơn hàng</option>
                                                        <option value="Số đơn hàng">Số đơn hàng</option>
                                                        <option value="Sản phẩm">Sản phẩm</option>
                                                        <option value="Danh mục sản phẩm">Danh mục sản phẩm</option>
                                                        <option value="Dịch vụ">Dịch vụ</option>
                                                        <option value="Nhóm dịch vụ">Nhóm dịch vụ</option>
                                                        <option value="Thẻ dịch vụ">Thẻ dịch vụ</option>
                                                        <option value="Loại thẻ dịch vụ">Loại thẻ dịch vụ</option>
                                                        <option value="Số lần đặt lịch ">Số lần đặt lịch</option>
                                                    </select>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="form-control-label">Áp dụng cho: <b class="text-danger">*</b></label>
                                                    <select class="form-control select2">
                                                        <option value="">Tất cả</option>
                                                        <option value="1">Loại 1</option>
                                                        <option value="2">Loại 2</option>
                                                    </select>
                                                </div>

                                                <div class="form-group m-form__group">
                                                    <label class="form-control-label">Nội dung hiển thị mô tả trên app: <b class="text-danger">*</b></label>
                                                    <div class="summernote"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm"  >
                                        <div class="form-group m-form__group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Thời gian hiệu lực từ: <b class="text-danger"> *</b></label>
                                                <div class="form-group">
                                                    <div class="m-input-icon m-input-icon--right">
                                                        <input readonly="" class="form-control date-picker-list" id="m_datepicker_1" style="background-color: #fff" name="date_end" value="" autocomplete="off" placeholder="Chọn ngày có hiệu lực">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                                                    </div>
                                                </div>
                                                <label class="form-control-label">Thời gian hiệu lực đến:</label>
                                                <div class="form-group">
                                                    <div class="m-input-icon m-input-icon--right">
                                                        <input readonly="" class="form-control date-picker-list" id="m_datepicker_2" style="background-color: #fff" name="date_end" value="" autocomplete="off" placeholder="Chọn ngày hết hiệu lực">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group option_1_order_value">
                                                    <label class="form-control-label">Áp dụng cho đơn hàng có loại hàng hóa: <b class="text-danger">*</b></label>
                                                    <select class="form-control select2">
                                                        <option value="Tất cả">Tất cả</option>
                                                        <option value="Loại 1">Loại 1</option>
                                                        <option value="Loại 2">Loại 2</option>
                                                        <option value="Loại 3">Loại 3</option>
                                                    </select>
                                                </div>
                                                <div class="form-group m-form__group option_2_order_value">
                                                    <label class="form-control-label">Áp dụng cho đơn hàng có nhóm hàng hóa: <b class="text-danger">*</b></label>
                                                    <select class="form-control select2">
                                                        <option value="Tất cả">Tất cả</option>
                                                        <option value="Nhóm 1">Nhóm 1</option>
                                                        <option value="Nhóm 2">Nhóm 2</option>
                                                        <option value="Nhóm 3">Nhóm 3</option>
                                                    </select>
                                                </div>
                                                <div class="form-group m-form__group option_3_order_value">
                                                    <label class="form-control-label">Áp dụng cho đơn hàng có 1 trong những loại hàng hóa: <b class="text-danger">*</b></label>
                                                    <select class="form-control select2">
                                                        <option value="Tất cả">Tất cả</option>
                                                        <option value="Loại 1">Loại 1</option>
                                                        <option value="Loại 2">Loại 2</option>
                                                        <option value="Loại 3">Loại 3</option>
                                                    </select>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <div class="row" style="margin-left: 0px">
                                                        <div class="m-widget19__action mb-3">
                                                            <a href="javascript:void(0)" onclick="document.getElementById('getFile').click()"
                                                               class="btn m-btn--square btn-outline-successsss m-btn m-btn--icon">
															        <span>
                                                                        <i class="la la-plus"></i>
																    <span>
																	    {{__('Ảnh hiển thị trên app')}}
																    </span>
															        </span>
                                                            </a>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="form-group m-form__group m-widget19">
                                                                    <div class="m-widget19__pic">
                                                                        <div class="wrap-imge avatar-temp">
                                                                            <img class="m--bg-metal m-image" id="blah-add"
                                                                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                                                 alt="{{__('Hình ảnh')}}">
                                                                            <span class="delete-img">
                                                                                        <span href="javascript:void(0)"
                                                                                              onclick="ProductDeleteImageAdd.deleteAvatar()">
                                                                                            <i class="la la-close"></i>
                                                                                        </span>
                                                                                     </span>
                                                                        </div>
                                                                    </div>
                                                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" id="getFile" type="file"
                                                                           onchange="uploadImage(this);"
                                                                           class="form-control"
                                                                           style="display:none">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <label for="">{{__('Định dạng')}}: <b class="image-info image-format"></b> </label>
                                                                <br>
                                                                <label for="">{{__('Kích thước')}}: <b class="image-info image-size"></b> </label>
                                                                <br>
                                                                <label for="">{{__('Dung lượng')}}: <b class="image-info image-capacity"></b> </label>
                                                                <label class="max-size">{{__('Cảnh báo: Tối đa 10MB (10240KB)')}}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span style="color:red">Tỉ lệ kích thước tiêu chuẩn là 3:2( tối thiểu 360x240)</span>
                                                {{--                                                ///////--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--        <ol class="stepBar step3">--}}
            {{--            <li class="step current" style="background-color:#0099FF">--}}
            {{--                1--}}
            {{--            </li>--}}
            {{--            <li class="step current" style="background-color: #C0C0C0	">--}}
            {{--                2--}}
            {{--            </li>--}}
            {{--            <li class="step current" style="background-color: #C0C0C0	">--}}
            {{--                3--}}
            {{--            </li>--}}
            {{--        </ol>--}}
        </div>
        @include('admin::product.modal.excel-image')
        @endsection
        @section('after_script')

            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
            <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>

            <script>
                $('.select2').select2();
                $('#m_datepicker_1,#m_datepicker_2').datepicker({
                    rtl: mUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left"
                });
            </script>

            <script>
                var Summernote = {
                    init: function () {
                        $.getJSON(laroute.route('translate'), function (json) {
                            $(".summernote").summernote({
                                height: 208,
                                placeholder: json['Nhập nội dung'],
                                toolbar: [
                                    ['style', ['style']],
                                    ['font', ['bold', 'underline', 'clear']],
                                    ['fontname', ['fontname']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    ['table', ['table']],
                                    ['insert', ['link', 'picture', 'video']],
                                    ['view', ['fullscreen', 'codeview', 'help']],
                                ]
                            })
                        });
                    }
                };
                jQuery(document).ready(function () {
                    Summernote.init()
                    $('.note-btn').attr('title', '');
                });
            </script>
            <script>
                var view = {
                    changeType: function (obj) {
                        if ($(obj).val() == "CPI") {
                            $(".group_accountable_by").hide();
                            $(".option_1_order_value").hide();
                            $(".option_2_order_value").hide();
                            $(".option_3_order_value").hide();
                        } else {
                            $(".group_accountable_by").show();
                        }
                    },
                    changeChoice: function (obj) {
                        if ($(obj).val() == "Tổng giá trị đơn hàng" || $(obj).val() == "Số đơn hàng") {
                            $(".option_1_order_value").show();
                            $(".option_2_order_value").show();
                            $(".option_3_order_value").show();
                        } else {
                            $(".option_1_order_value").hide();
                            $(".option_2_order_value").hide();
                            $(".option_3_order_value").hide();
                        }
                    }
                }


            </script>
@stop

