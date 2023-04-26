@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NỘI DUNG HỖ TRỢ')}} </span>
@endsection
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        .form-control-feedback {
            color: red;
        }

        .title_header {
            color: #008990;
            font-weight: 400;
        }
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                   <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        @lang('CẤU HÌNH CHUNG')
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <!--end: Search Form -->
            <div class="table-content ">
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table ss--nowrap">
                        <thead>
                        <tr>
                            <th class="ss--font-size-th">#</th>
                            <th class="ss--font-size-th">@lang('Name')</th>
                            <th>@lang('Value')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($LIST) && $LIST->count() > 0)
                            <?php $n = 1 ?>
                            @foreach($LIST as $key=>$item)
                                <tr>
{{--                                    @if($item['key'] == 'hot_search' || $item['key'] == 'auto_apply_branch')--}}
                                        <td>{{ ($n++) }}</td>
                                        <td>
                                            <a class="m-link" style="color:#464646;" href="{{ route('admin.config.detail-config-general', ['id' => $item['config_id']]) }}"
                                               title="{{ $item['name'] }}">
                                                {{__(subString($item['name']))}}
                                            </a>
                                        </td>
                                        <td>
                                            <p title="{{ $item['value'] }}">
                                                {{ subString($item['value']) }}
                                            </p>
                                        </td>
                                        <td>
                                            @if(in_array('admin.config.edit-config-general',session('routeList')))
                                                <a href="{{route('admin.config.edit-config-general', ['id' => $item['config_id']])}}"
                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                   title="Cập nhật"><i class="la la-edit"></i></a>
                                            @endif
                                        </td>
{{--                                    @endif--}}
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">
                                    @lang('Không có dữ liệu')
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@stop
@section('after_script')
    {{--<script src="{{asset('static/backend/js/admin/faq/script.js?v='.time())}}" type="text/javascript"></script>--}}
    <script>
        $(".m_selectpicker").selectpicker();
    </script>
@stop