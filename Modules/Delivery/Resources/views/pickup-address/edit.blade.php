@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN HÀNG CẦN GIAO')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA ĐỊA CHỈ NHẬN HÀNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ lấy hàng'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="address" name="address" placeholder="@lang('Nhập địa chỉ lấy hàng')..."
                                    value="{{isset($data['address'])? $data['address'] : ''}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'):<b class="text-danger"> *</b>
                            </label>
                            <div class="row">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn" id="is_actived" name="is_actived"
                                            {{isset($data['is_actived'])? ($data['is_actived']==1?'checked':'') : ''}}>
                                    <span></span>
                                </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('pickup-address')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="edit.save({{$data['pickup_address_id']}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/delivery/pickup-address/script.js?v='.time())}}" type="text/javascript"></script>
@stop