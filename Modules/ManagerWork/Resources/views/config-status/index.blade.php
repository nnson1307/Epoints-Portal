@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">{{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        .modal .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
        .nav-tabs .nav-item:hover , .fa-plus-circle:hover , .kt-checkbox input:hover{
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
        .color-select {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border: 0;
            border-radius: 50%;
            padding: 0;
            overflow: hidden;
            box-shadow: 2px 2px 5px rgba(0,0,0,.1);
        }
        .color-select::-webkit-color-swatch-wrapper {
            padding: 0;
        }
        .color-select::-webkit-color-swatch {
            border: none;
        }
        .fa-plus-circle {
            color:#22AF72;
            font-size: 20px;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
        .kt-checkbox {
            margin-bottom: 0;
        }
        td a {
            color:#6f727d !important;
        }
        td a:hover {
            text-decoration: unset;
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
                <a href="{{route('manager-work.manage-config.status-edit')}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <span> {{ __('managerwork::managerwork.edit') }}</span>
                    </span>
                </a>
            </div>
        </div>

        <div class="m-portlet__body p-0">
            <ul class="nav nav-tabs nav-pills mb-3" role="tablist" style="margin-bottom: 0;">
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link active">{{ __('managerwork::managerwork.work_status') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('manager-project.manage-config.status')}}" class="nav-link">{{ __('managerwork::managerwork.project_status') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('manager-work.manage-config.role')}}" class="nav-link">{{ __('managerwork::managerwork.role') }}</a>
                </li>
            </ul>
            <form id="form-config-status" style="padding: 2.2rem 2.2rem;">
                <?php $n = 0 ?>
                @foreach($listStatus as $key => $item)
                    <?php $n = $n + count($item) ?>
                    <table class="table table-striped m-table ss--header-table ss--nowrap groupId{{$item[0]['manage_status_group_config_id']}}">
                        <thead>
                            <tr>
                                <th width="22%" colspan="2"><h5>{{$key}} <span title="{{$item[0][getValueByLang('note_')]}}">(?)</span></h5></th>
                                <th width="3%"></th>
                                <th width="55%"></th>
                                <th width="5%"></th>
                                <th width="5%" class="text-right mb-0"><h5>{{ __('managerwork::managerwork.edit_th') }}</h5></th>
                                <th width="5%" class="text-right mb-0"><h5>{{ __('managerwork::managerwork.delete_th') }}</h5></th>
                            </tr>
                        </thead>
                        <tbody class="scroll{{$item[0]['manage_status_group_config_id']}}">
                        @if(isset($item))
                            @foreach($item as $keyValue => $value)
                                <tr class="block_{{$value['manage_status_group_config_id']}}_{{$keyValue}}">
                                    <td width="3%"><input type="color" disabled name="group[{{$value['manage_status_group_config_id']}}][{{$keyValue}}][manage_color_code]" class="color-select" value="{{$value['manage_color_code']}}"></td>
                                    <td>
                                        {{$value['manage_status_config_title']}}
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-right d-inline"></i>
                                    </td>
                                    <td>
                                        <select class="form-control select2Full" multiple name="group[{{$value['manage_status_group_config_id']}}][{{$keyValue}}][manage_status_config_map][]">
                                            <option value=""> </option>
                                            @foreach($listStatusSelect as $itemSelect)
                                                <option value="{{$itemSelect['manage_status_id']}}" {{in_array($itemSelect['manage_status_id'],collect($value['list_status'])->pluck('manage_status_id')->toArray()) ? 'selected' : ''}}>{{$itemSelect['manage_status_name']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label>
                                                <input type="checkbox"
                                                       class="manager-btn" {{$value['is_active'] == 1 ? 'checked' : ''}} disabled>
                                                <span></span>
                                            </label>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <label class="kt-checkbox kt-checkbox--bold">
                                            <input type="checkbox" disabled name="group[{{$value['manage_status_group_config_id']}}][{{$keyValue}}][is_edit]" {{$value['is_edit'] == 1 ? 'checked' : ''}}>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <label class="kt-checkbox kt-checkbox--bold">
                                            <input type="checkbox" disabled name="group[{{$value['manage_status_group_config_id']}}][{{$keyValue}}][is_deleted]" {{$value['is_deleted'] == 1 ? 'checked' : ''}}>
                                            <span></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                @endforeach
            </form>
        </div>
    </div>
@stop
@section('after_script')
    <script>
        var count = '{{$n}}';
        $('.select2Full').select2({
            disabled: true
        });

        $(document).ready(function () {
            scrollBlock();
        })
    </script>

    <script src="{{asset('static/backend/js/manager-work/manage-config/script-status.js?v='.time())}}" type="text/javascript"></script>
@stop
