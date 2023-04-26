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
                        CẬP NHẬT TỪ KHÓA HOT
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
                    <label for="example-text-input" class="col-2 col-form-label">Từ khóa hot</label>
                    <div class="col-10">
                        <a href="javascript:void(0)" onclick="config.addKey()" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mb-2">
                            <span>
                                <i class="fa fa-plus-circle p-1"></i>
                                <span>Thêm từ khóa</span>
                            </span>
                        </a>
                        <div class="list-keyhot">
                            @foreach($arrHot as $key => $item)
                                <div id="key{{$key}}">
                                    <input type="text" value="{{$item}}" name="key[{{$key}}]" class="form-control mb-2 w-50 d-inline">
                                    <button type="button" onclick="config.removeKey({{$key}})"
                                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                            title="Xóa"><i class="la la-trash"></i></button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

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
                        <a href="javascript:void(0)" onclick="config.updateKey()"
                           class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-edit"></i>
                                <span>{{__('CẬP NHẬT')}}</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="config_id" id="config_id" value="{{$detail['config_id']}}">
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
        var sum = "{{count($arrHot)}}";
    </script>
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/config/script.js?v='.time()) }}"></script>
@stop


