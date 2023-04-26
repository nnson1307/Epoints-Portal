@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ PHÂN QUYỀN')}}</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH NHÓM QUYỀN')}}
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="table-content">
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table">
                        <thead>
                        <tr>
                            <th class="ss--font-size-th" style="width: 25px">#</th>
                            <th class="ss--font-size-th">{{__('NHÓM QUYỀN')}}</th>
                            <th class="ss--font-size-th"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (isset($roleGroup))
                            @foreach ($roleGroup as $key=>$value)
                                <tr>
                                    <td class="ss--font-size-13">{{$key+1}}</td>
                                    <td class="ss--font-size-13">{{ $value['name'] }}</td>
                                    <td class="ss--font-size-13 pull-right">
                                        <a href="{{route('admin.authorization.edit',$value['id'])}}"
                                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-modal-edit-s"
                                           title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end table-content -->

        </div>
    </div>


@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('after_script')

@stop
