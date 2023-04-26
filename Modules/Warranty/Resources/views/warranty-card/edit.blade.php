@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ THẺ BẢO HÀNH')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA THẺ BẢO HÀNH')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thông tin khách hàng'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input" disabled
                                   value="{{$data['customer_name']}} - {{$data['customer_phone']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Gói bảo hành'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input" disabled
                                   value="{{$data['packed_name']}}">
                            <input type="hidden" id="packed_id" value="{{$data['packed_id']}}">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Ngày kích hoạt'):<b class="text-danger"> *</b>
                                    </label>
                                    <input type="text" class="form-control m-input" disabled
                                           id="date_actived" value="{{$data['date_actived']}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Ngày hết hạn'):<b class="text-danger"> *</b>
                                    </label>
                                    <input type="text" class="form-control m-input" id="date_expired" disabled
                                           value="{{$data['date_expired']}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số lần được bảo hành'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input" id="quota" disabled
                                   value="{{$data['quota'] == 0 ? __('Vô hạn'): $data['quota']}}">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Phần trăm bảo hành'):<b class="text-danger"> *</b>
                                    </label>
                                    <input type="text" class="form-control m-input" id="warranty_percent" disabled
                                           value="{{number_format($data['warranty_percent'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Giá trị tối đa được bảo hành'):<b class="text-danger"> *</b>
                                    </label>
                                    <input type="text" class="form-control m-input" id="warranty_value"  disabled
                                           value="{{number_format($data['warranty_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">@lang('Trạng thái'):</label>
                                <div class="input-group">
                                    <select class="form-control" id="status" name="status"
                                            style="width:100%;">
                                        <option value="new" {{$data['status'] == 'new' ? 'selected' : ''}}>@lang('Mới')</option>
                                        <option value="actived" {{$data['status'] == 'actived' ? 'selected' : ''}}>@lang('Kích hoạt')</option>
                                        <option value="cancel" {{$data['status'] == 'cancel' ? 'selected' : ''}}>@lang('Huỷ')</option>
                                        <option value="finish" {{$data['status'] == 'finish' ? 'selected' : ''}}>@lang('Hoàn thành')</option>
                                    </select>
                                </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label> @lang('Nội dung bảo hành'):</label>
                            <div class="summernote">{!! $data['description'] !!}</div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <a href="javascript:void(0)"
                                       onclick="productImage.imageDropzone()"
                                       class="btn btn-sm m-btn--icon color">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>
                                                {{__('Thêm ảnh')}}
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <div class="image-show">
                                        @if (isset($listImage) && count($listImage) > 0)
                                            @foreach($listImage as $v)
                                                <div class="wrap-img image-show-child list-image-old">
                                                    <input type="hidden" name="product_image" class="product_image"
                                                           value="{{$v['link']}}">
                                                    <img class='m--bg-metal m-image img-sd '
                                                         src='{{$v['link']}}' alt='{{__('Hình ảnh')}}' width="100px"
                                                         height="100px">
                                                    <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="productImage.removeImage(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-content m--padding-top-30">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('TÊN ĐỐI TƯỢNG')</th>
                                <th class="tr_thead_list">@lang('MÃ ĐỐI TƯỢNG')</th>
                                <th class="tr_thead_list">@lang('SỐ SERIAL')</th>
                                <th class="tr_thead_list">@lang('GHI CHÚ')</th>
                            </tr>
                            </thead>
                            <tbody>
                                <td>{{$data['object_name']}}</td>
                                <td>{{$data['object_code']}}</td>
                                <td>
                                    <div class="form-group m-form__group">
                                        <input type="text" class="form-control m-input" value="{{$data['object_serial']}}"
                                               id="object_serial" name="object_serial" {{$data['status'] != 'new' ? 'disabled' : ''}}
                                               placeholder="@lang('Nhập số serial')">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group m-form__group">
                                        <input type="text" class="form-control m-input" value="{{$data['object_note']}}"
                                               id="object_note" name="object_note" {{$data['status'] != 'new' ? 'disabled' : ''}}
                                               placeholder="@lang('Nhập ghi chú')">
                                    </div>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('warranty-card')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="edit.save('{{$data['warranty_card_code']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('warranty::warranty-card.modal-add-image')
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/warranty/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/warranty/warranty-card/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        edit._init();
    </script>
    <script type="text/template" id="imgeShow">
        <div class="wrap-img image-show-child list-image-new">
            <input type="hidden" name="img-sv" value="{link_hidden}" class="product_image">
            <img class='m--bg-metal m-image img-sd '
                 src='{{'{link}'}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                <a href="javascript:void(0)" onclick="productImage.removeImage(this)">
                    <i class="la la-close"></i>
                </a>
            </span>
        </div>
    </script>

@endsection