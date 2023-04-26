@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ CHI NHÁNH')}}</span>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--head-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                            <h2 class="m-portlet__head-text">
                                {{__('DANH SÁCH QUYỀN TRANG')}}
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
                                    <th class="ss--font-size-th">{{__('TRANG')}}</th>
                                    <th class="ss--font-size-th text-center">
                                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                            <input class="check-all-page" onclick="RolePage.checkAll(this)"
                                                   type="checkbox">
                                            <span></span>
                                        </label>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($pages))
                                    @foreach ($pages as $key=>$value)
                                        <tr>
                                            <td class="ss--font-size-13">
                                                {{$key+1}}
                                                <input type="hidden" class="id-page" value="{{$value['id']}}">
                                            </td>
                                            <td class="ss--font-size-13">{{ $value['name'] }}</td>
                                            <td class="ss--font-size-13 text-center">
                                                <label class="m-checkbox m-checkbox--air">
                                                    <input class="check-page" {{$value['role_page']==1?"checked":""}} type="checkbox" onclick="RolePage.checkPage(this)">
                                                    <span></span>
                                                </label>
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
        </div>
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--head-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                            <h2 class="m-portlet__head-text">
                                {{__('DANH SÁCH QUYỀN CHỨC NĂNG')}}
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
                                    <th class="ss--font-size-th">{{__('CHỨC NĂNG')}}</th>
                                    <th class="ss--font-size-th text-center">
                                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                            <input class="check-all-action" type="checkbox" onclick="RoleAction.checkAll(this)">
                                            <span></span>
                                        </label>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($action))
                                    @foreach ($action as $key=>$value)
                                        <tr>
                                            <td class="ss--font-size-13">{{$key+1}}
                                                <input type="hidden" class="id-action" value="{{$value['id']}}">
                                            </td>
                                            <td class="ss--font-size-13">{{ $value['title'] }}</td>
                                            <td class="ss--font-size-13 text-center">
                                                <label class="m-checkbox m-checkbox--air">
                                                    <input class="check-action" type="checkbox"
                                                           onclick="RoleAction.checkAction(this)" {{$value['role_action']==1?"checked":""}}>
                                                    <span></span>
                                                </label>
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
        </div>
    </div>
    <input type="hidden" id="staffTitleId" value="{{$staffTitleId}}">
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/authorization/script.js?v='.time())}}"
            type="text/javascript"></script>

@endsection
