@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        .modal .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
        .nav-tabs .nav-item:hover {
            cursor: pointer;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link.active {
            color:#6f727d;
            border-bottom: #6f727d;
            background: #EEF3F9;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link {
            padding: 15px;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('managerwork::managerwork.config_status_role') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                   onclick="ManageConfig.updateConfigRole()"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
							<span> {{ __('managerwork::managerwork.update_config') }}</span>
                        </span>
                </a>
            </div>
        </div>

        <div class="m-portlet__body p-0">
            <ul class="nav nav-tabs nav-pills mb-3" role="tablist" style="margin-bottom: 0;">
                <li class="nav-item">
                    <a href="{{route('manager-work.manage-config.status')}}" class="nav-link">{{ __('managerwork::managerwork.work_status') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('manager-project.manage-config.status')}}" class="nav-link">{{ __('managerwork::managerwork.project_status') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('manager-work.manage-config.role')}}" class="nav-link active">{{ __('managerwork::managerwork.role') }}</a>
                </li>
            </ul>
            <form id="form-config-role" style="padding: 2.2rem 2.2rem;">
                <table class="table table-striped m-table ss--header-table ss--nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('managerwork::managerwork.data_role') }}</th>
                        <th class="text-center">{{ __('managerwork::managerwork.all') }}</th>
                        <th class="text-center">{{ __('managerwork::managerwork.branch') }}</th>
                        <th class="text-center">{{ __('managerwork::managerwork.department') }}</th>
                        <th class="text-center">{{ __('managerwork::managerwork.own') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($listRole as $key => $item)
                        <tr>
                            <input type="hidden" name="role[{{$key}}][id]" value="{{$item['id']}}">
                            <td>{{$key + 1}}</td>
                            <td>{{$item['name']}}</td>
                            <td class="text-center"><input type="radio" name="role[{{$key}}][check]" value="is_all" {{isset($item['is_all']) && $item['is_all'] == 1 ? 'checked' : ''}}></td>
                            <td class="text-center"><input type="radio" name="role[{{$key}}][check]" value="is_branch" {{isset($item['is_branch']) && $item['is_branch'] == 1 ? 'checked' : ''}}></td>
                            <td class="text-center"><input type="radio" name="role[{{$key}}][check]" value="is_department" {{isset($item['is_department']) && $item['is_department'] == 1 ? 'checked' : ''}}></td>
                            <td class="text-center"><input type="radio" name="role[{{$key}}][check]" value="is_own" {{isset($item['is_own']) && $item['is_own'] == 1 ? 'checked' : ''}}></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/manager-work/manage-config/script-role.js?v='.time())}}" type="text/javascript"></script>
@stop
