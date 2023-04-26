@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ DỊCH VỤ')}}</span>
@stop
@section('content')
    <style>
        .m-image-show {
            width: 100px;
            height: 100px;
            background: #ccc;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <div class="m-portlet__head-title">
                        <h2 class="m-portlet__head-text title_index">
                            <span>{{__('CHI TIẾT DỊCH VỤ')}}</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form action="" method="post" id="formDetail" novalidate="novalidate">
                {!! csrf_field() !!}
                <div class="row">
                    <input type="hidden" value="{{$item['service_id']}}" name="service_id_hidden"
                           id="service_id_hidden">
                    <div class="form-group m-form__group col-6">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 font-13">
                                    <label>{{__('Tên dịch vụ')}}:</label>
                                </div>
                                <div class="col-lg-9 font-13">
                                    <strong>{{$item['service_name']}}</strong>
                                </div>
                            </div>

                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 font-13">
                                    <label>{{__('Mã dịch vụ')}}:</label>
                                </div>
                                <div class="col-lg-9 font-13">
                                    <strong>{{$item['service_code']}}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 font-13">
                                    <label>{{__('Nhóm dịch vụ')}}:</label>
                                </div>
                                <div class="col-lg-9 font-13">
                                    <strong>{{$item['name']}}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 font-13">
                                    <label>{{__('Thời gian sử dụng')}}:</label>
                                </div>
                                <div class="col-lg-9 font-13">
                                    <strong>{{$item['time']}} @lang('phút')</strong>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 font-13">
                                    <label>{{__('Giá dịch vụ')}}:</label>
                                </div>
                                <div class="col-lg-9 font-13">
                                    <strong>{{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('VNĐ')</strong>
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
                                            <input disabled id="is_surcharge" name="is_surcharge"
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
                    </div>

                    <div class="form-group m-form__group col-6 row">
                        <div class="form-group m-form__group col-lg-4">
                            <div>
                                <label class="font-13">{{__('Ảnh đại diện')}}:</label>
                            </div>
                            @if($item['service_avatar']!=null)
                                <img class="m--bg-metal  m-image-show img-sd"
                                     src="{{$item['service_avatar']}}" height="100px;">
                            @endif
                        </div>
                        <div class="form-group m-form__group col-lg-8">
                            <div>
                                <label class="font-13">{{__('Ảnh kèm theo')}}:</label>

                            </div>
                            @foreach($itemImage as $key=>$value)
                                <img class="m--bg-metal m-image-show img-sd m--margin-top-5"
                                     src="{{$value['name']}}" height="100px;">
                            @endforeach
                        </div>
                        <div class="form-group" style="margin-bottom: 30px;">
                            <div class="m-checkbox-list">
                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                    <input type="checkbox" id="is_upload_image_ticket" name="is_upload_image_ticket" {{$item['is_upload_image_ticket'] == 1 ? 'checked': ''}} disabled>
                                    <span></span> @lang('Cần hình ảnh khi hoàn thành yêu cầu xử lý')
                                </label>
                            </div>
                            <div class="m-checkbox-list">
                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                    <input type="checkbox" id="is_upload_image_sample" name="is_upload_image_sample" {{$item['is_upload_image_sample'] == 1 ? 'checked': ''}} disabled>
                                    <span></span> @lang('Cần hình ảnh mẫu khi hoàn thành yêu cầu xử lý')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <span class="font-13 font-weight-bold">{{__('Chi nhánh')}}:</span>
                </div>
                <div id="autotable">
                    <div class="table-content">
                        @include('admin::service.list-branch-detail')
                    </div>
                </div>
                <div class="form-group m-form__group m--margin-top-10">
                    <span class="font-13 font-weight-bold">{{__('Dịch vụ đi kèm')}}:</span>
                </div>
                <div id="autotable2">
                    <div class="table-content">
                        @include('admin::service.list-material-service-detail')
                    </div>
                </div>
                <div class="form-group m-form__group m--margin-top-10">
                    <span class="font-13 font-weight-bold">{{__('Sản phẩm sử dụng')}}:</span>
                </div>
                <div id="autotable1">
                    <div class="table-content">
                        @include('admin::service.list-material-detail')
                    </div>
                </div>
                <div class="form-group m-form__group m--margin-top-10">
                    <label>
                        <i class="fa fa-edit"></i>
                        {{__('Thông tin giới thiệu chi tiết')}}:
                    </label>
                    <div class="input-group m-input-group">
                            <textarea disabled="disabled" id="detail_description"
                                      name="detail_description" rows="5" class="form-control summernote"
                                      placeholder="{{__('Thông tin mô tả')}}">{{$item['detail_description']}}</textarea>
                    </div>
                </div>
            </form>


        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('admin.service')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('THOÁT')}}</span>
						</span>
                    </a>
                    <a href="{{route('admin.service.edit',$item['service_id'])}}"
                       class="btn btn-primary color_button son-mb"
                       title="View">
                        <i class="la la-edit"></i>{{__('CHỈNH SỬA')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        jQuery(document).ready(function () {
            $('.summernote').summernote({
                height: 150,
                width: '100%',
                // focus: true,
                placeholder: '{{__('Nhập thông tin chi tiết')}}',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
            $('.summernote').summernote('disable');
            $('.note-btn').attr('title', '');
        });

    </script>
    <script>
        let routess = laroute.route('admin.service.list-branch-detail', {'id': $('#service_id_hidden').val()});

        $('#autotable').PioTable({
            baseUrl: routess
        });

        let routess1 = laroute.route('admin.service.list-material-detail', {'id': $('#service_id_hidden').val()});
        $('#autotable1').PioTable({
            baseUrl: routess1
        });
    </script>
@stop