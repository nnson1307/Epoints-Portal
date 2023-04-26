@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ HỢP ĐỒNG')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("PHÂN QUYỀN DỮ LIỆU")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--@if(in_array('contract.contract.create',session('routeList')))--}}
                    <button onclick="saveConfig()"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('LƯU PHÂN QUYỀN')</span>
                                    </span>
                    </button>
                {{--@endif--}}
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                </form>
                <div class="table-content m--padding-top-15">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default">
                                <thead class="bg">
                                    <tr>
                                        <th class="tr_thead_list" width="35%">@lang('PHÂN QUYỀN XEM DỮ LIỆU THEO ROLE')</th>
                                        <th class="tr_thead_list text-center" width="15%">@lang('TẤT CẢ')</th>
                                        <th class="tr_thead_list text-center" width="15%">@lang('CHI NHÁNH')</th>
                                        <th class="tr_thead_list text-center" width="15%">@lang('PHÒNG BAN')</th>
                                        <th class="tr_thead_list text-center" width="15%">@lang('SỞ HỮU')</th>
                                        <th class="tr_thead_list text-center" width="5%"></th>
                                    </tr>
                                </thead>
                            <tbody style="font-size: 13px" id="config-role">
                                @foreach($optionRoleGroup as $key => $value)
                                    <tr>
                                        <td>{{$value['name']}}</td>
                                        <td class="text-center">
                                            <input type="radio" name="{{$value['id']}}" value="all" {{$value['role_data_type'] == 'all' ? 'checked' : ''}}>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="{{$value['id']}}" value="branch" {{$value['role_data_type'] == 'branch' ? 'checked' : ''}}>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="{{$value['id']}}" value="department" {{$value['role_data_type'] == 'department' ? 'checked' : ''}}>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="{{$value['id']}}" value="own" {{$value['role_data_type'] == 'own' ? 'checked' : ''}}>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)"
                                               onclick="removeChecked({{$value['id']}})"
                                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                               title="{{__('Xoá phân quyền')}}">
                                                <i class="la la-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- end table-content -->
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script type="text/javascript">
        function saveConfig() {
            $.getJSON(laroute.route('translate'), function (json) {
                var listConfig = [];
                $('#config-role').find('input:checked').each(function(k, v){
                    let itemConfig = {
                        'role_group_id': $(v).attr('name'),
                        'role_data_type': $(v).val()
                    };
                    listConfig.push(itemConfig);
                    itemConfig = {};
                });
                $.ajax({
                    url: laroute.route('contract.role-data.submit-config'),
                    data: {
                        'listConfig': listConfig
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                    }
                });

            });
        }
        function removeChecked(id) {
            $(`input[name="${id}"]`).each(function(k,v){
                $(v).prop('checked', false)
            })
        }
    </script>
@stop
