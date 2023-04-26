@extends('layout')
@section('title_header')
    <span class="title_header">QUẢN LÝ CẤU HÌNH CHUNG</span>
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
                        CẬP NHẬT CẤU HÌNH TỰ ĐỘNG PHÂN ĐƠN HÀNG CHO CHI NHÁNH
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form method="POST" id="form-update">
            {{ csrf_field() }}
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">Tự động phân đơn hàng cho chi nhánh</label>
                    <div class="col-10">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" name="auto_apply_branch"
                                           {{ ($detail['value'] == 1) ? 'checked' : '' }} class="manager-btn" value="1">
                                    <span></span>
                                </label>
                         </span>
                    </div>
                </div>
                @foreach($configDetail as $item)
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$item['name']}}</label>
                        <div class="col-4">
                            <input type="number" name="{{$item['key']}}" class="form-control input-number" {{$detail['value'] == 0 ? "disabled" :""}} value="{{$item['value']}}">
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>{{__('HỦY')}}</span>
                                </span>
                        </a>
                        <a href="javascript:void(0)"  onclick="config.updateBrand()"
                           class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>{{__('CẬP NHẬT')}}</span>
                                </span>
                        </a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="config_id" value="{{$detail['config_id']}}">
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/config/script.js?v='.time()) }}"></script>
@stop


