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

        .type_of_criteria {
            font-weight: bold;
            font-size: 20px;
        }

        .form-control-label {
            font-weight: bold;
        }
        .head-commission{
            width: 425px;
        }
    </style>
    <div class="m-portlet" id="autotable">
        {{--        /////--}}


        {{--        <form id = "form-referral-add" action="addReferral.php" method="post">--}}

        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text head-commission">
                        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
                    </h3>
                    <a href="{{route('referral.policyCommission')}}">
                        <div class="modal-footer" style="margin-left: 550px">
                            <div class="m-form__actions m--align-right w-100">
                                <button data-dismiss="modal"
                                        class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						        <span>
						            <i class="la la-arrow-left"></i>
						            <span>{{__('HỦY')}}</span>
						        </span>
                                </button>
                            </div>
                        </div>
                    </a>
                    <div class="condition_commission_gtdh_sdh" style="display:none">
                        <button type="button" onclick="referral.chooseOrderPrice()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit"
                                style="float: right;    margin-left: 10px">
                            <span>
						        <i class="la la-check"></i>
							    <span> {{__('TIẾP THEO')}}</span>
                            </span>
                        </button>
                    </div>
                    <div class="condition_commission_CPI" style="display:none">
                        <button type="button" onclick="referral.chooseCPI()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>{{__('TIẾP THEO')}}</span>
                                </span>
                        </button>
                    </div>
                    <div class="condition_commission_none">
                        <button type="button" onclick="check()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit">
                            <span>
                            <i class="la la-check"></i>
                            <span>{{__('TIẾP THEO')}}</span>
                        </span>
                        </button>
                    </div>
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
                            <div class="steps_3">
                                <ol class="stepBar step3">
                                    <li class="step current">
                                        {{__('Thông tin hoa hồng')}}
                                    </li>
                                    <li class="step">
                                        {{__('Chọn sản phẩm')}}
                                    </li>
                                    <li class="step">
                                        {{__('Điều kiện tính')}}
                                    </li>
                                </ol>
                            </div>
                            <div class="steps_2" style="display:none">
                                <ol class="stepBar step3">
                                    <li class="step current" style="width: 50%">
                                        {{__('Thông tin hoa hồng')}}
                                    </li>
                                    <li class="step" style="width: 50%">
                                        {{__('Điều kiện tính')}}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-wizard__form-step m-wizard__form-step--current" id="m_wizard_form_step_2">
                <div class="row data">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="m-form__section m-form__section--first">
                            <div class="m-form__heading">
                                <h3 class="type_of_criteria">{{__('Chọn loại tiêu chí')}}</h3>
                            </div>
                            <form id="info-commission">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm" style="max-width:50%">
                                            <div class="form-group m-form__group row">
                                                <div class="col-lg-12">
                                                    <div class="form-group m-form__group">
                                                        <label class="form-control-label">
                                                           {{__('Tên tiêu chí: ')}} <b class="text-danger">*</b>
                                                        </label>
                                                        <div class="input-group">
                                                            <input id="referral_program_name" name="referral_program_name"
                                                                   type="text"
                                                                   class="form-control m-input class"
                                                                   placeholder="{{__('Tên tiêu chí')}}"
                                                                   aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-form__group">
                                                        <label class="form-control-label">
                                                           {{__('Loại tiêu chí: ')}}<b class="text-danger">*</b></label>
                                                        <select class="form-control select2" id="type"
                                                                name="type"
                                                                onchange="view.changeType(this)">
                                                            <option value="">{{__('Chọn loại tiêu chí')}}</option>
                                                            {{--                                                            <option value="CPI">CPI</option>--}}
                                                            {{--                                                            <option value="CPS">CPS</option>--}}
                                                            @foreach($type_of_criteria as $k => $v)
                                                                <option value="{{$v}}">{{$v}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group m-form__group group_accountable_by">
                                                        <label class="form-control-label">{{__('Tính theo: ')}}<b
                                                                    class="text-danger">*</b></label>
                                                        <select class="form-control select2" id="accountable_by"
                                                                name="referral_criteria_code"
                                                                onchange="">
                                                            <option value="">{{__('Lựa chọn')}}</option>
{{--                                                            @foreach($accountable_by as $k => $v)--}}
{{--                                                                <option value="{{$k +1 }}">{{$v['referral_criteria_name']}}</option>--}}
{{--                                                            @endforeach--}}
                                                            <option value=1>{{__('Tổng giá trị đơn hàng')}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group m-form__group">
                                                        <label class="form-control-label">{{__('Áp dụng cho: ')}}<b
                                                                    class="text-danger">*</b></label>
                                                        <select class="form-control select2" id="apply_for"
                                                                name="apply_for">
                                                            @foreach($apply_for as $k => $v)
                                                                <option value="{{$v}}">{{$v}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group m-form__group">
                                                        <label class="form-control-label">{{__('Nội dung hiển thị mô tả trên app:')}}
                                                            <b class="text-danger">*</b></label>
                                                        <textarea class="summernote" id="description"
                                                                  name="description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group m-form__group row">
                                                <div class="col-lg-12">
                                                    <label class="form-control-label">{{__('Ngày bắt đầu hiệu lực: ')}}<b
                                                                class="text-danger"> *</b></label>
                                                    <div class="form-group">
                                                        <div class="m-input-icon m-input-icon--right">
                                                            <input readonly="" class="form-control date-picker-list"
                                                                   id="m_datepicker_1"
                                                                   style="background-color: #fff"
                                                                   name="date_start" value="" autocomplete="off"
                                                                   placeholder="{{__('Chọn ngày có hiệu lực')}}">
                                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                                                        </div>
                                                    </div>
                                                    <label class="form-control-label">{{__('Ngày kết thúc hiệu lực: ')}}<b
                                                                class="text-danger"> *</b></label>
                                                    <div class="form-group">
                                                        <div class="m-input-icon m-input-icon--right">
                                                            <input readonly="" class="form-control date-picker-list"
                                                                   id="m_datepicker_2"
                                                                   style="background-color: #fff"
                                                                   name="date_end" value="" autocomplete="off"
                                                                   placeholder="{{__('Chọn ngày hết hiệu lực')}}">
                                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span>
                                                        </span>
                                                        </div>
                                                    </div>
                                                    {{--/////--}}
                                                    <div class="form-group col-lg-5 ss--col-lg-5 " style="padding-left: 0">
                                                        <div class="m-widget19__action">
                                                            <a href="javascript:void(0)"
                                                               onclick="document.getElementById('getFile').click()"
                                                               class="btn m-btn--square btn-outline-successsss m-btn m-btn--icon">
															<span>
                                                                <i class="la la-plus"></i>
																<span>
																	{{__('Ảnh hiển thị trên app')}}
																</span>
															</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7 form-group">
                                                        <div class="row" >
                                                            <div class="col-lg-4" style="    padding-left: 0">
                                                                <div class="form-group m-form__group m-widget19">
                                                                    <div class="m-widget19__pic">
                                                                        <div class="wrap-imge avatar-temp">
                                                                            <img class="m--bg-metal m-image"
                                                                                 id="blah-add"
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
                                                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                                                           id="getFile" type="file"
                                                                           onchange="uploadImage(this);"
                                                                           class="form-control"
                                                                           style="display:none">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <label for="">{{__('Định dạng')}}: <b
                                                                            class="image-info image-format"></b>
                                                                </label>
                                                                <br>
                                                                <label for="">{{__('Kích thước')}}: <b
                                                                            class="image-info image-size"></b> </label>
                                                                <br>
                                                                <label for="">{{__('Dung lượng')}}: <b
                                                                            class="image-info image-capacity"></b>
                                                                </label>
                                                                <label class="max-size">{{__('Dung lượng tối đa: 10MB (10240kb)')}} </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span style="color:red">{{__('Tỉ lệ kích thước tiêu chuẩn là 3:2 ( tối thiểu 360x240)')}}</span>
                                                    <input type="hidden" id="image" name="img">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @section('after_script')

            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
            <script src="{{asset('static/backend/js/admin/affiliate/add.js?v='.time())}}"
                    type="text/javascript"></script>
            <script src =
                    "https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js">
            </script>
{{--            <script>--}}
{{--                function check() {--}}
{{--                    let type = document.getElementById('type').value;--}}
{{--                    if ( _.isEmpty(type)){--}}
{{--                        Swal.fire({--}}
{{--                            type: 'error',--}}
{{--                            title: 'Lỗi',--}}
{{--                            text: 'Vui lòng chọn loại tiêu chí',--}}
{{--                        })--}}
{{--                    }--}}
{{--                }--}}
{{--            </script>--}}
{{--            <script>--}}
{{--                function uploadImage(input) {--}}
{{--                    $('.image-info').text('');--}}
{{--                    if (input.files && input.files[0]) {--}}
{{--                        var reader = new FileReader();--}}
{{--                        var imageAvatar = $('#file_name_avatar');--}}
{{--                        reader.onload = function (e) {--}}
{{--                            $('#blah-add')--}}
{{--                                .attr('src', e.target.result);--}}
{{--                        };--}}
{{--                        reader.readAsDataURL(input.files[0]);--}}
{{--                        $('.delete-img').show();--}}
{{--                        var file_data = $('#getFile').prop('files')[0];--}}
{{--                        var form_data = new FormData();--}}
{{--                        form_data.append('file', file_data);--}}
{{--                        form_data.append('link', '_product.');--}}

{{--                        var fileInput = input,--}}
{{--                            file = fileInput.files && fileInput.files[0];--}}
{{--                        var img = new Image();--}}

{{--                        img.src = window.URL.createObjectURL(file);--}}

{{--                        img.onload = function () {--}}
{{--                            var imageWidth = img.naturalWidth;--}}
{{--                            var imageHeight = img.naturalHeight;--}}

{{--                            window.URL.revokeObjectURL(img.src);--}}

{{--                            $('.image-size').text(imageWidth + "x" + imageHeight + "px");--}}

{{--                        };--}}
{{--                        var fsize = input.files[0].size;--}}
{{--                        $('.image-capacity').text(Math.round(fsize / 1024) + 'kb');--}}

{{--                        $('.image-format').text(input.files[0].name.split('.').pop().toUpperCase());--}}

{{--                        $.ajax({--}}
{{--                            url: laroute.route("referral.upload-image"),--}}
{{--                            method: "POST",--}}
{{--                            data: form_data,--}}
{{--                            contentType: false,--}}
{{--                            cache: false,--}}
{{--                            processData: false,--}}
{{--                            success: function (data) {--}}
{{--                                imageAvatar.val(data.file);--}}
{{--                                $("#image").val(data.file)--}}

{{--                            }--}}
{{--                        });--}}
{{--                    }--}}
{{--                }--}}
{{--                var referral = {--}}
{{--                    chooseOrderPrice: function () {--}}
{{--                        $.ajax({--}}
{{--                            url: laroute.route("referral.saveInfoCommission"),--}}
{{--                            method: "POST",--}}
{{--                            data: $("#info-commission").serialize(),--}}
{{--                            success: function (res) {--}}
{{--                                if (res.error == true) {--}}
{{--                                    swal("Lỗi", res.message , "error").then(function () {--}}
{{--                                    });--}}
{{--                                } else{--}}
{{--                                    window.location.href = res.link;--}}
{{--                                }--}}
{{--                            }--}}
{{--                        })--}}
{{--                    },--}}
{{--                    chooseCPI: function () {--}}
{{--                        $.ajax({--}}
{{--                            url: laroute.route("referral.saveInfoCommission"),--}}
{{--                            method: "POST",--}}
{{--                            data: $("#info-commission").serialize(),--}}
{{--                            success: function (res) {--}}
{{--                                if (res.error == true) {--}}
{{--                                    swal("Lỗi", res.message , "error").then(function () {--}}
{{--                                    });--}}
{{--                                }else{--}}
{{--                                    window.location.href = res.link;--}}
{{--                                }--}}
{{--                            }--}}
{{--                        })--}}
{{--                    }--}}
{{--                }--}}
{{--            </script>--}}
@stop

