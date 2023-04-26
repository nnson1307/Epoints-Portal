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
                           id="date_actived"
                           value="{{\Carbon\Carbon::parse($data['date_actived'])->format('d/m/Y H:i')}}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Ngày hết hạn'):<b class="text-danger"> *</b>
                    </label>
                    <input type="text" class="form-control m-input" id="date_expired" disabled
                           value="{{$data['date_expired'] != null ? \Carbon\Carbon::parse($data['date_expired'])->format('d/m/Y H:i') : __('Vô hạn')}}">
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
                    <input type="text" class="form-control m-input" id="warranty_value" disabled
                           value="{{number_format($data['warranty_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-6">
        <div class="form-group m-form__group">
            <label class="black_title">@lang('Trạng thái'):</label>
            <div class="input-group">
                <select class="form-control" id="status" name="status" disabled
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
                    {{--                                    <a href="javascript:void(0)"--}}
                    {{--                                       onclick="productImage.imageDropzone()"--}}
                    {{--                                       class="btn btn-sm m-btn--icon color">--}}
                    {{--                                        <span>--}}
                    {{--                                            <i class="la la-plus"></i>--}}
                    {{--                                            <span>--}}
                    {{--                                                {{__('Thêm ảnh')}}--}}
                    {{--                                            </span>--}}
                    {{--                                        </span>--}}
                    {{--                                    </a>--}}
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
                                                    <a href="javascript:void(0)"
                                                       onclick="productImage.removeImage(this)">
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
            <td>
                @switch($data['object_type'])
                    @case('product')
                    <a href="{{route('admin.product-child-new.detail', $data['object_type_id'])}}"
                       target="_blank">
                        {{$data['object_code']}}
                    </a>
                    @break
                    @case('service')
                    <a href="{{route('admin.service.detail', $data['object_type_id'])}}"
                       target="_blank">
                        {{$data['object_code']}}
                    </a>
                    @break
                    @case('service_card')
                    <a href="{{route('admin.service-card.detail', $data['object_type_id'])}}"
                       target="_blank">
                        {{$data['object_code']}}
                    </a>
                    @break
                @endswitch

            </td>
            <td>
                <div class="form-group m-form__group">
                    <input type="text" class="form-control m-input" value="{{$data['object_serial']}}"
                           id="object_serial" name="object_serial"
                           placeholder="@lang('Nhập số serial')" disabled>
                </div>
            </td>
            <td>
                <div class="form-group m-form__group">
                    <input type="text" class="form-control m-input" value="{{$data['object_note']}}"
                           id="object_note" name="object_note"
                           placeholder="@lang('Nhập ghi chú')" disabled>
                </div>
            </td>
            </tbody>
        </table>
    </div>
</div>