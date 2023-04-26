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
                        CHI TIẾT TỪ KHÓA HOT
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
            {{ csrf_field() }}
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">Từ khóa hot</label>
                    <div class="col-10">
                        @if($arrHot)
                            @foreach($arrHot as $item)
                                <input type="text" disabled value="{{$item}}" class="form-control mb-2 w-50">
                            @endforeach
                        @else
                            <input type="text" disabled class="form-control mb-2 w-50">
                        @endif
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
                        @if(in_array('admin.config.edit-config-general',session('routeList')))
                            <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>CHỈNH SỬA</span>
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
    </script>
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/config/script.js?v='.time()) }}"></script>
@stop


